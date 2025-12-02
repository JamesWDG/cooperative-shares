<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\VendorSubscription;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Refund;

class SubscriptionController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $vendor = Auth::user();

        $plans = Plan::orderBy('price')->get();

        $activeSubscription = VendorSubscription::with('plan')
            ->where('vendor_id', $vendor->id)
            ->where('is_active', 1)
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->first();

        $activePlan = optional($activeSubscription)->plan;

        $activePlanBullets = [];

        if ($activePlan) {
            if (!is_null($activePlan->standard_limit)) {
                $activePlanBullets[] = "Up to {$activePlan->standard_limit} active standard listings at a time.";
            } else {
                $activePlanBullets[] = "Unlimited standard listings.";
            }

            if ($activePlan->featured_free_limit == 0) {
                $activePlanBullets[] = "All featured listings are billed as pay-per-slot (no free featured listings).";
            } else {
                $activePlanBullets[] = "{$activePlan->featured_free_limit} free featured listings; additional featured listings are pay-per-slot.";
            }

            if ($activePlan->allow_coop) {
                $activePlanBullets[] = "Co-Op feature enabled: you can post blogs in the Co-Op section (Premium-only feature).";
            } else {
                $activePlanBullets[] = "Co-Op blogging feature is not available on this plan.";
            }
        }

        return view('screens.vendor.subscription-plans', [
            'plans'              => $plans,
            'activeSubscription' => $activeSubscription,
            'activePlan'         => $activePlan,
            'activePlanBullets'  => $activePlanBullets,
        ]);
    }

    /**
     * Stripe charge + subscription create (AJAX JSON)
     */
    /**
 * Stripe charge + subscription create (AJAX JSON)
 */
        public function createStripeSubscriptionPayment(Request $request)
        {
            // 1) Validate request (JSON-friendly)
            $validator = Validator::make($request->all(), [
                'stripeToken' => 'required',
                'plan_id'     => 'required|exists:plans,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Please select a valid subscription plan and enter your payment details.',
                    'error'  => $validator->errors()->first(), // extra info if you want to show it
                ], 422);
            }

            $plan   = Plan::findOrFail($request->plan_id);
            $vendor = Auth::user();

            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                DB::beginTransaction();

                // (Front-end should already show "Processing payment, please wait...")
                // 2) Stripe Charge
                $charge = Charge::create([
                    'amount'      => (int) round($plan->price * 100), // cents
                    'currency'    => 'usd',
                    'description' => "Subscription plan: {$plan->name} (Vendor ID: {$vendor->id})",
                    'source'      => $request->stripeToken,
                    'metadata'    => [
                        'vendor_id' => $vendor->id,
                        'plan_id'   => $plan->id,
                        'plan_name' => $plan->name,
                    ],
                ]);

                // 3) Handle Stripe status (succeeded / pending / failed)
                if ($charge->status === 'pending') {
                    DB::commit(); // nothing created yet, but keep it clean

                    return response()->json([
                        'status' => 'processing',
                        'msg'    => 'Your payment is being processed. Please stay on this page — we will update your subscription as soon as the payment is confirmed.',
                    ], 200);
                }

                if ($charge->status !== 'succeeded') {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'msg'    => 'We could not complete your payment. Please double-check your card details or try another card. If this continues, contact support.',
                    ], 400);
                }

                // 4) Deactivate previous active subscriptions
                VendorSubscription::where('vendor_id', $vendor->id)
                    ->where('is_active', 1)
                    ->update(['is_active' => 0]);

                // 5) Create new subscription
                $startsAt  = now();
                $expiresAt = now()->addMonth();

                $subscription = VendorSubscription::create([
                    'vendor_id'        => $vendor->id,
                    'plan_id'          => $plan->id,
                    'is_active'        => 1,
                    'expires_at'       => $expiresAt,
                    'started_at'       => $startsAt,
                    'stripe_charge_id' => $charge->id,
                    'amount'           => $plan->price,
                ]);

                // 6) Create INVOICE for Subscription Plan (paid)
                $invoiceNumber = 'INV-' . now()->format('YmdHis') . '-' . $vendor->id;

                $invoice = Invoice::create([
                    'vendor_id'      => $vendor->id,
                    'type'           => 'Subscription Plan',
                    'reference_id'   => $subscription->id, // reference to subscription
                    'invoice_number' => $invoiceNumber,
                    'amount'         => $plan->price,
                    'currency'       => strtoupper($charge->currency ?? 'usd'),
                    'payment_method' => 'stripe',
                    'transaction_id' => $charge->id,
                    'status'         => 'paid',
                    'issued_at'      => now(),
                    'paid_at'        => now(),
                    'due_at'         => null,
                    'meta'           => [
                        'subscription_id'    => $subscription->id,
                        'plan_id'            => $plan->id,
                        'plan_name'          => $plan->name,
                        'period_start'       => $startsAt->toDateTimeString(),
                        'period_end'         => $expiresAt->toDateTimeString(),
                        'stripe_receipt_url' => $charge->receipt_url ?? null,
                        'card_last4'         => $charge->payment_method_details->card->last4 ?? null,
                        'card_brand'         => $charge->payment_method_details->card->brand ?? null,
                    ],
                ]);

                // 7) Admin Notification for new subscription
                $adminId = User::where('role', 'admin')->value('id');

                $vendorFullName = trim($vendor->first_name . ' ' . $vendor->last_name);

                $slug = Str::slug('subscription-' . $invoiceNumber . '-' . uniqid());

                $title = "New subscription purchased: {$plan->name}";

                $content = "Vendor #{$vendor->id} ({$vendorFullName}) has purchased the {$plan->name} subscription plan.
                Invoice #: {$invoice->invoice_number}
                Amount: {$invoice->amount} {$invoice->currency}.";

                $this->notificationService->createNotification(
                    $vendor->id,          // sender_id (vendor)
                    $adminId,             // receiver_id (admin)
                    $title,
                    $content,
                    $slug,
                    'subscription',       // type
                    'Admin'               // notification_for
                );



                DB::commit();

                return response()->json([
                    'status'       => true,
                    'msg'          => 'Your subscription is now active. Redirecting you to your subscription page…',
                    'redirect_url' => route('vendor.subscription.plans'),
                ]);

            } catch (\Stripe\Exception\CardException $e) {
                DB::rollBack();

                // Stripe's own detailed card error message
                $error   = $e->getError();
                $message = $error->message ?? $e->getMessage();

                Log::warning('Stripe card error on subscription', [
                    'vendor_id' => $vendor->id ?? null,
                    'plan_id'   => $plan->id ?? null,
                    'message'   => $message,
                ]);

                return response()->json([
                    'status'  => false,
                    'message' => $message,
                ], 402); // Payment Required

            } catch (\Stripe\Exception\ApiErrorException $e) {
                DB::rollBack();

                // Stripe API error message directly
                $message = $e->getMessage();

                Log::error('Stripe API error on subscription', [
                    'vendor_id' => $vendor->id ?? null,
                    'plan_id'   => $plan->id ?? null,
                    'error'     => $message,
                ]);

                return response()->json([
                    'status'  => false,
                    'message' => $message,
                ], 500);

            } catch (\Exception $e) {
                DB::rollBack();

                // Optional: auto-refund if charge succeeded but internal error occurred
                if ($charge && isset($charge->id) && $charge->status === 'succeeded') {
                    try {
                        Refund::create([
                            'charge' => $charge->id,
                        ]);
                    } catch (\Exception $refundError) {
                        Log::error('Stripe auto-refund failed on subscription error', [
                            'charge_id' => $charge->id,
                            'error'     => $refundError->getMessage(),
                        ]);
                    }
                }

                Log::error('Subscription error', [
                    'vendor_id' => $vendor->id ?? null,
                    'plan_id'   => $plan->id ?? null,
                    'error'     => $e->getMessage(),
                ]);

                return response()->json([
                    'status'  => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }


        

}
