<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Listing;
use App\Models\Invoice;
use App\Models\AdsPurchased;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Refund;
use Carbon\Carbon;
use App\Models\User;


class MarketingController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function getModalForm(Advertisement $advertisement){
        try{
            $advertisement = $advertisement->load('purchasedAds');
             
            $html = view('includes.vendor.marketing-form',get_defined_vars())->render();
            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => 'success',
                'advertisementType' => $advertisement->type,
            ],200);
        }catch(\Exception $error){
            return response()->json([
                'status' => false,
                'error' => $error->getMessage(),
                'message' => 'Something went wrong'
                ],400);
        }
    }
    
    public function index(){
        $advertisements = Advertisement::with('purchasedAds','promotions')->get();
        return view('screens.vendor.marketing-plans',get_defined_vars());
    }
    
    public function purchaseAd(Request $request,Advertisement $advertisement){
        // 1) Validate request (JSON-friendly)
        
        // 1) Prepare validation rules
        $rules = [
            'stripeToken' => 'required',
            'add_id'      => 'required|exists:advertisements,id',
            'listing_id'  => 'required|exists:listings,id',
            'month'       => 'required',
            'week'        => $advertisement->type === 'weekly' ? 'required' : 'nullable',
        ];
    
        // 2) Dynamically add listing_image validation
        if ($advertisement->require_banner == 1) {
            $rules['listing_image'] = [
                'required',
                'image',
                "dimensions:width={$advertisement->banner_width},height={$advertisement->banner_height}"
            ];
        } else {
            $rules['listing_image'] = ['nullable', 'image'];
        }
    
        // 3) Custom error message for dimensions
        $messages = [
            'listing_image.dimensions' => "Image must be exactly {$advertisement->banner_width}x{$advertisement->banner_height} pixels."
        ];
    
        // 4) Validate request
        $validator = Validator::make($request->all(), $rules, $messages);
        
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => 'Please select a valid subscription plan and enter your payment details.',
                'message' => $validator->errors()->first(),
                'error'  => $validator->errors()->first(), // extra info if you want to show it
            ], 422);
        }
        
        Stripe::setApiKey(config('services.stripe.secret'));
        $charge = null;
        $imageName = null;
        try {
            DB::beginTransaction();
            $vendor = auth()->user();
            $packageName = $advertisement->package_name ?? '';
            $listing = Listing::findOrFail($request->listing_id);
            list($month, $year) = explode('-', $request->month);
            $week = $request->week
            ? (int) str_replace('week-', '', $request->week)
            : 0;
            
            $limitExceeded = false;
            $currentLimit = null;
            
            if ($advertisement->is_limited) {
            
                // --------------------------
                // WEEKLY LIMIT CHECK
                // --------------------------
                if ($advertisement->type == 'weekly') {
            
                    $monthStart = Carbon::create($year, $month, 1)->startOfDay();
                    $monthEnd   = Carbon::create($year, $month, 1)->endOfMonth();
                    
                    $weekStart = (clone $monthStart)->addWeeks($week - 1)->startOfWeek(Carbon::MONDAY);
                    $weekEnd   = (clone $monthStart)->addWeeks($week - 1)->endOfWeek(Carbon::SUNDAY);
                    // Ensure week falls inside the selected month
                    if ($weekStart < $monthStart) {
                        $weekStart = $monthStart;
                    }
                    if ($weekEnd > $monthEnd) {
                        $weekEnd = $monthEnd;
                    }
                    
                    $count = AdsPurchased::where('add_id', $advertisement->id)
                            ->whereBetween('created_at', [$weekStart, $weekEnd])
                            ->count();
            
                    $weeklyLimit = match ($advertisement->id) {
                        3 => 1,
                        default => null
                    };
            
                    if ($weeklyLimit && $count >= $weeklyLimit) {
                        return response()->json([
                            'status' => false,
                            'msg' => "Weekly purchase limit reached.",
                            'error' => "Only {$weeklyLimit} purchase allowed per week.",
                        ], 422);
                    }
                }
            
                // --------------------------
                // MONTHLY LIMIT CHECK
                // --------------------------
                if ($advertisement->type == 'monthly') {
            
                    // request example: 5-2026
                    $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                    $monthEnd   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
                    $count = AdsPurchased::where('add_id', $advertisement->id)
                            ->whereBetween('created_at', [$monthStart, $monthEnd])
                            ->count();
            
                    $monthlyLimit = match ($advertisement->id) {
                        4 => 2,
                        5 => 6,
                        default => null
                    };
            
                    if ($monthlyLimit && $count >= $monthlyLimit) {
                        return response()->json([
                            'status' => false,
                            'msg' => "Monthly purchase limit reached.",
                            'error' => "Only {$monthlyLimit} purchase(s) allowed per month.",
                        ], 422);
                    }
                }
            }
            
            if (request()->file('listing_image')) {
                $imageName = time() . '.' . request()->listing_image->getClientOriginalExtension();
                request()->listing_image->move(public_path('images/listig_images'), $imageName);
            }
            if ($advertisement->type == 'monthly') {
                $fromDate = Carbon::create($year, $month, 1);
                $toDate   = $fromDate->copy()->endOfMonth();
            
            } elseif ($advertisement->type == 'weekly') {
                $monthStart = Carbon::create($year, $month, 1)->startOfDay();
                $monthEnd   = Carbon::create($year, $month, 1)->endOfMonth();
                
                $fromDate = (clone $monthStart)->addWeeks($week - 1)->startOfWeek(Carbon::MONDAY);
                $toDate   = (clone $monthStart)->addWeeks($week - 1)->endOfWeek(Carbon::SUNDAY);
                // // Ensure week falls inside the selected month
                if ($fromDate < $monthStart) {
                    $fromDate = $monthStart;
                }
                if ($toDate > $monthEnd) {
                    $toDate = $monthEnd;
                }
                
                // $fromDate = Carbon::now()->setISODate($year, $week)->startOfWeek(Carbon::MONDAY);
                // $toDate   = Carbon::now()->setISODate($year, $week)->endOfWeek(Carbon::SUNDAY);
            }
            
            // 2) Stripe Charge
            $charge = Charge::create([
                'amount'      => (int) round($advertisement->amount * 100), // cents
                'currency'    => 'usd',
                'description' => "Advertisement plan: {$packageName} , Vendor ID: $vendor->id",
                'source'      => $request->stripeToken,
                'metadata'    => [
                    'vendor_id' => $vendor->id,
                    'add_id'   => $advertisement->id ?? '',
                    'add_name' => $packageName,
                    'listing_id' => $listing->id,
                    'listing_title' => $listing->property_title,
                    'month' => $month ?? 0,
                    'week' => $week,
                    'year' => $year ?? 0,
                    'from_date' => $fromDate->toDateString(),
                    'to_date'   => $toDate->toDateString(),
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
            
            $purchasedAd = $vendor->purchasedAds()->create([
                'add_id' => $advertisement->id,
                'listing_id' => $listing->id,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'month' => $month ?? 0,
                'week' => $week,
                'year' => $year ?? 0,
                'amount' => $advertisement->amount ?? 0,
                'tran_id' => $charge->id,
                'image' => $imageName,
            ]);
            
            // 6) Create INVOICE for Subscription Plan (paid)
            $invoiceNumber = 'INV-' . now()->format('YmdHis') . '-' . $vendor->id;

            $invoice = Invoice::create([
                'vendor_id'      => $vendor->id,
                'type'           => 'Advertisement Plan',
                'reference_id'   => $purchasedAd->id, // reference to purchasedAd
                'invoice_number' => $invoiceNumber,
                'amount'         => $advertisement->amount ?? 0,
                'currency'       => strtoupper($charge->currency ?? 'usd'),
                'payment_method' => 'stripe',
                'transaction_id' => $charge->id,
                'status'         => 'paid',
                'issued_at'      => now(),
                'paid_at'        => now(),
                'due_at'         => null,
                'meta'           => [
                    'purchased_add_id'    => $purchasedAd->id,
                    'add_id'   => $advertisement->id ?? '',
                    'add_name' => $packageName,
                    'listing_id' => $listing->id,
                    'listing_title' => $listing->property_title,
                    'period_start' => $fromDate->toDateTimeString(),
                    'period_end' => $toDate->toDateTimeString(),
                    'month' => $month ?? 0,
                    'week' => $week,
                    'year' => $year ?? 0,
                    'stripe_receipt_url' => $charge->receipt_url ?? null,
                    'card_last4'         => $charge->payment_method_details->card->last4 ?? null,
                    'card_brand'         => $charge->payment_method_details->card->brand ?? null,
                ],
            ]);
            
            // 7) Admin Notification for new subscription
            $adminId = User::where('role', 'admin')->value('id');

            $vendorFullName = trim($vendor->first_name . ' ' . $vendor->last_name);

            $slug = Str::slug('marketing_plan-' . $invoiceNumber . '-' . uniqid());

            $title = "New Advertisement purchased: {$advertisement->package_name}";

            $content = "Vendor #{$vendor->id} ({$vendorFullName}) has purchased the {$advertisement->package_name} advertisement plan.
            Invoice #: {$invoice->invoice_number}
            Amount: {$invoice->amount} {$invoice->currency}.";

            $this->notificationService->createNotification(
                $vendor->id,          // sender_id (vendor)
                $adminId,             // receiver_id (admin)
                $title,
                $content,
                $slug,
                'advertisement',       // type
                'Admin'               // notification_for
            );
            
        DB::commit();

        return response()->json([
            'status'       => true,
            'msg'          => 'You have purchased Ad successfully ${$advertisement?->package_name}. Redirecting ………',
            'redirect_url' => route('vendor.marketing.plans'),
        ]);
            
        }catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();

            // Stripe's own detailed card error message
            $error   = $e->getError();
            $message = $error->message ?? $e->getMessage();

            Log::warning('Stripe card error on subscription', [
                'vendor_id' => $vendor->id ?? null,
                'add_id'   => $advertisement->id ?? null,
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
                'add_id'   => $advertisement->id ?? null,
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
                'add_id'   => $advertisement->id ?? null,
                'error'     => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    
}
