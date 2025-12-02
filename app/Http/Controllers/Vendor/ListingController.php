<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\State;
use App\Models\VendorSubscription;
use App\Models\VendorStandardUsage;
use App\Models\VendorFeatureUsage;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Charge;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::where('user_id', Auth::id())->orderByDesc('id')->paginate(8);
        return view('screens.vendor.listings.index', compact('listings'));
    }

    /**
     * Single create page for both:
     * - Active plan (free Standard / free Featured)
     * - Paid Featured when all plan resources are used
     */
    public function create()
    {
        $states = State::all();
        $vendor = Auth::user();

        // 1. Active subscription + plan
        $subscription = VendorSubscription::active()
            ->where('vendor_id', $vendor->id)
            ->with('plan')
            ->first();

        if (!$subscription || !$subscription->plan) {
            return redirect()
                ->route('vendor.subscription.plans')
                ->with('error', 'Your subscription has expired or is not active. Please select a plan to continue creating listings.');
        }

        $plan = $subscription->plan;

        // Plan limits (null = unlimited)
        $standardLimit     = $plan->standard_limit;        // can be null
        $featuredFreeLimit = $plan->featured_free_limit;   // can be null

        // 2. Ensure usage rows per vendor + plan + subscription
        $standardUsage = VendorStandardUsage::firstOrCreate(
            [
                'vendor_id'              => $vendor->id,
                'plan_id'                => $plan->id,
                'vendor_subscription_id' => $subscription->id,
            ],
            [
                'active_standard' => 0,
                'is_active'       => 1,
            ]
        );

        $featuredUsage = VendorFeatureUsage::firstOrCreate(
            [
                'vendor_id'              => $vendor->id,
                'plan_id'                => $plan->id,
                'vendor_subscription_id' => $subscription->id,
            ],
            [
                'used_featured' => 0,
                'is_active'     => 1,
            ]
        );

        // 3. Remaining counts (null = unlimited)
        if (is_null($standardLimit)) {
            $standardRemaining = null;
        } else {
            $standardRemaining = max(0, $standardLimit - $standardUsage->active_standard);
        }

        if (is_null($featuredFreeLimit)) {
            $featuredFreeRemaining = null;
        } else {
            $featuredFreeRemaining = max(0, $featuredFreeLimit - $featuredUsage->used_featured);
        }

        // 4. All plan resources used (only when both finite)
        $allPlanUsed = false;
        if (!is_null($standardLimit) && !is_null($featuredFreeLimit)) {
            $allPlanUsed = ($standardRemaining <= 0 && $featuredFreeRemaining <= 0);
        }

        $planUsage = [
            'hasActivePlan'         => true,
            'planName'              => $plan->name,
            'standardLimit'         => $standardLimit,
            'featuredFreeLimit'     => $featuredFreeLimit,
            'standardRemaining'     => $standardRemaining,
            'featuredFreeRemaining' => $featuredFreeRemaining,
            'allPlanUsed'           => $allPlanUsed,
        ];

        // Always use the single create view
        return view('screens.vendor.listings.create', compact('states', 'planUsage'));
    }

    /**
     * Single store() handles:
     * - Free Standard listings (under plan)
     * - Free Featured listings (under plan)
     * - Paid Featured listing (when free featured used up)
     *
     * First call (no stripeToken):
     *   -> validates plan/usage
     *   -> if free allowed -> creates listing & returns success
     *   -> if payment required -> returns requires_payment=true (no insert)
     *
     * Second call (with stripeToken):
     *   -> charges Stripe
     *   -> creates listing, invoice and returns success
     */
    public function store(Request $request)
{
    // Base validation (stripeToken is optional here)
    $validator = Validator::make($request->all(), [
        'listing'             => 'required|in:simple,featured',
        'property_title'      => 'required|string|max:255',
        'description'         => 'required|string',
        'category'            => 'required|string|max:255',
        'listed_in'           => 'required|string|max:255',
        'price'               => 'required|numeric|min:0',
        'size_in_ft'          => 'required|string|max:255',
        'bedrooms'            => 'required',
        'bathrooms'           => 'required',
        'kitchens'            => 'required',
        'garages'             => 'required',
        'year_built'          => 'required|date',
        'floors'              => 'required',
        'listing_description' => 'required|string',
        'map_location'        => 'required|string',
        'address'             => 'required|string|max:255',
        'country'             => 'required|string|max:255',
        'state'               => 'required|string|max:255',
        'city'                => 'required|string|max:255',
        'zip_code'            => 'required|string|max:50',

        'main_image'          => 'required|image|max:20480',
        'files.*'             => 'nullable|file|max:20480|mimetypes:image/*,video/*',

        'stripeToken'         => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $vendor = Auth::user();

    // 1) Get active subscription + plan
    $subscription = VendorSubscription::active()
        ->where('vendor_id', $vendor->id)
        ->with('plan')
        ->first();

    if (!$subscription || !$subscription->plan) {
        return response()->json([
            'status'  => false,
            'message' => 'You do not have an active subscription plan. Please purchase a plan to add listings.',
        ], 422);
    }

    $plan = $subscription->plan;
    $planName = $plan->name ?? 'current';

    // 2) Plan limits (null = unlimited)
    $standardLimit     = $plan->standard_limit;         // NULL = unlimited
    $featuredFreeLimit = $plan->featured_free_limit;    // NULL = unlimited

    $selectedType = $request->listing; // 'simple' or 'featured'

    // 3) Ensure usage rows exist **per vendor + plan + subscription**
    $standardUsage = VendorStandardUsage::firstOrCreate(
        [
            'vendor_id'              => $vendor->id,
            'plan_id'                => $plan->id,
            'vendor_subscription_id' => $subscription->id,
        ],
        [
            'active_standard' => 0,
            'is_active'       => 1,
        ]
    );

    $featuredUsage = VendorFeatureUsage::firstOrCreate(
        [
            'vendor_id'              => $vendor->id,
            'plan_id'                => $plan->id,
            'vendor_subscription_id' => $subscription->id,
        ],
        [
            'used_featured' => 0,
            'is_active'     => 1,
        ]
    );

    // Remaining counts (for info/debug – agar UI me dikhani ho)
    $standardRemaining = is_null($standardLimit)
        ? null
        : max(0, $standardLimit - $standardUsage->active_standard);

    $featuredFreeRemaining = is_null($featuredFreeLimit)
        ? null
        : max(0, $featuredFreeLimit - $featuredUsage->used_featured);

    // Decide mode
    $mode            = null;   // 'standard_free', 'featured_free', 'featured_paid'
    $requiresPayment = false;
    $amount          = 349.00;
    $currency        = 'usd';

    /**
     * 🔹 STANDARD listing
     */
    if ($selectedType === 'simple') {

        if (!is_null($standardLimit) && $standardUsage->active_standard >= $standardLimit) {
            // Standard quota used up – construct friendly message per plan
            $limitText = $standardLimit === 1
                ? '1 Standard Listing'
                : $standardLimit . ' Standard Listings';

            $message = "You have used all {$limitText} included in your {$planName} plan. "
                . "You can still create a Featured Listing as a paid listing, or upgrade your plan to add more Standard Listings.";

            return response()->json([
                'status'  => false,
                'message' => $message,
            ], 422);
        }

        $mode = 'standard_free';

    /**
     * 🔹 FEATURED listing
     */
    } elseif ($selectedType === 'featured') {

        // If free Featured remaining or unlimited, treat as free
        if (is_null($featuredFreeLimit) || $featuredUsage->used_featured < $featuredFreeLimit) {
            $mode = 'featured_free';
        } else {
            // No free Featured left -> paid Featured flow (allowed for ALL plans)
            $mode            = 'featured_paid';
            $requiresPayment = true;
        }
    }

    /**
     * 🔹 If Featured listing requires payment but no token yet:
     *     - Basic plan: free Featured = 0 → always paid → explain clearly
     *     - Enhanced/Premium: show "you used all X free Featured"
     */
    if ($mode === 'featured_paid' && !$request->filled('stripeToken')) {

        if (!is_null($featuredFreeLimit) && $featuredFreeLimit === 0) {
            // e.g. Basic plan
            $message = "Your {$planName} plan does not include any free Featured Listings. "
                . "Please complete payment to publish this Featured Listing, or upgrade your plan to get free Featured slots.";

        } elseif (!is_null($featuredFreeLimit)) {
            // Limited free featured used up (Enhanced/Premium)
            $usedLimitText = $featuredFreeLimit === 1
                ? '1 free Featured Listing'
                : $featuredFreeLimit . ' free Featured Listings';

            $message = "You have used all {$usedLimitText} in your {$planName} plan. "
                . "Please complete payment to publish this Featured Listing, or upgrade your plan for more free Featured slots.";

        } else {
            // Theoretically not hit when NULL = unlimited, but fallback
            $message = 'Please complete payment to publish this Featured Listing.';
        }

        return response()->json([
            'status'           => true,
            'requires_payment' => true,
            'amount'           => $amount,
            'message'          => $message,
        ], 200);
    }

    try {
        DB::beginTransaction();

        $charge = null;

        // 4) Stripe Charge (only if required)
        if ($mode === 'featured_paid') {
            Stripe::setApiKey(config('services.stripe.secret'));

            $charge = Charge::create([
                'amount'      => (int) round($amount * 100), // cents
                'currency'    => $currency,
                'description' => "Paid Featured Listing (Vendor ID: {$vendor->id})",
                'source'      => $request->stripeToken,
                'metadata'    => [
                    'vendor_id'      => $vendor->id,
                    'property_title' => $request->property_title,
                    'listing_type'   => 'featured_paid',
                ],
            ]);

            if ($charge->status !== 'succeeded') {
                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'Payment could not be completed. Please try another card or contact support.',
                ], 400);
            }
        }

        // 5) MAIN IMAGE
        $mainImageFileName = null;

        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImageFileName = Str::random(20) . '_main.' . $mainImage->getClientOriginalExtension();
            $mainImage->move(public_path('storage/listing/images'), $mainImageFileName);
        }

        // 6) CREATE LISTING
        $listing = Listing::create([
            'user_id'             => $vendor->id,
            'listing'             => $selectedType, // 'simple' or 'featured'
            'property_title'      => $request->property_title,
            'description'         => $request->description,
            'category'            => $request->category,
            'listed_in'           => $request->listed_in,
            'price'               => $request->price,
            'size_in_ft'          => $request->size_in_ft,
            'bedrooms'            => $request->bedrooms,
            'bathrooms'           => $request->bathrooms,
            'kitchens'            => $request->kitchens,
            'garages'             => $request->garages,
            'year_built'          => $request->year_built,
            'floors'              => $request->floors,
            'listing_description' => $request->listing_description,

            'main_image'          => $mainImageFileName,

            'has_garages'         => $request->has_garages,
            'has_pool'            => $request->has_pool,
            'has_parking'         => $request->has_parking,
            'has_lakeview'        => $request->has_lakeview,
            'has_garden'          => $request->has_garden,
            'has_fireplace'       => $request->has_fireplace,
            'has_pet'             => $request->has_pet,
            'has_refrigerator'    => $request->has_refrigerator,
            'has_dryer'           => $request->has_dryer,
            'has_wifi'            => $request->has_wifi,
            'has_tv'              => $request->has_tv,
            'has_bbq'             => $request->has_bbq,
            'has_laundry'         => $request->has_laundry,
            'has_accessible'      => $request->has_accessible,
            'has_lawn'            => $request->has_lawn,
            'has_elevator'        => $request->has_elevator,

            'address'             => $request->address,
            'country'             => $request->country,
            'state'               => $request->state,
            'city'                => $request->city,
            'zip_code'            => $request->zip_code,
            'map_location'        => $request->map_location,
        ]);

        // 7) MULTIPLE FILES (images / videos)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $mime      = $file->getMimeType();

                $isImage = str_contains($mime, 'image');
                $isVideo = str_contains($mime, 'video');

                $fileName = Str::random(20) . '_file.' . $extension;

                if ($isImage) {
                    $folder = 'storage/listing/images';
                    $type   = 'image';
                } elseif ($isVideo) {
                    $folder = 'storage/listing/videos';
                    $type   = 'video';
                } else {
                    continue;
                }

                $file->move(public_path($folder), $fileName);

                ListingImage::create([
                    'listing_id' => $listing->id,
                    'type'       => $type,
                    'filename'   => $fileName,
                ]);
            }
        }

        // 8) UPDATE PLAN USAGE (only for free listings)
        if ($mode === 'standard_free') {
            $standardUsage->increment('active_standard');
        } elseif ($mode === 'featured_free') {
            $featuredUsage->increment('used_featured');
        }

        // 9) INVOICE (only for paid Featured)
        if ($mode === 'featured_paid' && $charge) {
            $invoiceNumber = 'INV-' . now()->format('YmdHis') . '-' . $vendor->id;

            Invoice::create([
                'vendor_id'      => $vendor->id,
                'type'           => 'Featured Listing',
                'reference_id'   => $listing->id,
                'invoice_number' => $invoiceNumber,
                'amount'         => $amount,
                'currency'       => strtoupper($currency),
                'payment_method' => 'stripe',
                'transaction_id' => $charge->id,
                'status'         => 'paid',
                'issued_at'      => now(),
                'paid_at'        => now(),
                'due_at'         => null,
                'meta'           => [
                    'listing_id'         => $listing->id,
                    'property_title'     => $listing->property_title,
                    'stripe_receipt_url' => $charge->receipt_url ?? null,
                    'card_last4'         => $charge->payment_method_details->card->last4 ?? null,
                    'card_brand'         => $charge->payment_method_details->card->brand ?? null,
                ],
            ]);
        }

        DB::commit();

        return response()->json([
            'status'           => true,
            'requires_payment' => false,
            'message'          => $mode === 'featured_paid'
                ? 'Payment successful! Your Featured Listing has been submitted and is being processed.'
                : 'Your listing has been added successfully under your active plan.',
            'redirect_url'     => route('vendor.listings'),
        ], 200);

    } catch (\Stripe\Exception\CardException $e) {
        DB::rollBack();

        $error   = $e->getError();
        $message = $error && isset($error->message)
            ? $error->message
            : 'Your card was declined. Please use a different card.';

        Log::warning('Stripe card error on paid listing', [
            'vendor_id' => $vendor->id,
            'message'   => $message,
        ]);

        return response()->json([
            'status'  => false,
            'message' => $message,
        ], 402);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        DB::rollBack();

        Log::error('Stripe API error on paid listing', [
            'vendor_id' => $vendor->id,
            'error'     => $e->getMessage(),
        ]);

        return response()->json([
            'status'  => false,
            'message' => 'Payment could not be processed at this time. Please try again in a few minutes.',
        ], 500);

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Listing store error (free/paid)', [
            'vendor_id' => $vendor->id,
            'error'     => $e->getMessage(),
        ]);

        return response()->json([
            'status'  => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}


    public function edit(Listing $listing)
    {
        $listing->load('images');
        return view('screens.vendor.listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        try {
            $mainImageFileName = $listing->main_image;

            if ($request->filled('delete_main_image') && $request->delete_main_image == 1) {
                if ($listing->main_image) {
                    $oldPath = public_path('storage/listing/images/' . $listing->main_image);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $mainImageFileName = null;
            }

            if ($request->hasFile('main_image')) {
                if ($listing->main_image) {
                    $oldPath = public_path('storage/listing/images/' . $listing->main_image);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $mainImage = $request->file('main_image');
                $mainImageFileName = Str::random(20) . '_main.' . $mainImage->getClientOriginalExtension();
                $mainImage->move(public_path('storage/listing/images'), $mainImageFileName);
            }

            $listing->update([
                'listing'             => $request->listing,
                'property_title'      => $request->property_title,
                'description'         => $request->description,
                'category'            => $request->category,
                'listed_in'           => $request->listed_in,
                'price'               => $request->price,
                'size_in_ft'          => $request->size_in_ft,
                'bedrooms'            => $request->bedrooms,
                'bathrooms'           => $request->bathrooms,
                'kitchens'            => $request->kitchens,
                'garages'             => $request->garages,
                'year_built'          => $request->year_built,
                'floors'              => $request->floors,
                'listing_description' => $request->listing_description,

                'main_image'          => $mainImageFileName,

                'has_garages'         => $request->has_garages,
                'has_pool'            => $request->has_pool,
                'has_parking'         => $request->has_parking,
                'has_lakeview'        => $request->has_lakeview,
                'has_garden'          => $request->has_garden,
                'has_fireplace'       => $request->has_fireplace,
                'has_pet'             => $request->has_pet,
                'has_refrigerator'    => $request->has_refrigerator,
                'has_dryer'           => $request->has_dryer,
                'has_wifi'            => $request->has_wifi,
                'has_tv'              => $request->has_tv,
                'has_bbq'             => $request->has_bbq,
                'has_laundry'         => $request->has_laundry,
                'has_accessible'      => $request->has_accessible,
                'has_lawn'            => $request->has_lawn,
                'has_elevator'        => $request->has_elevator,

                'has_fitness_center'              => $request->has_fitness_center,
                'has_common_room'                 => $request->has_common_room,
                'has_guest_suite'                 => $request->has_guest_suite,
                'has_all_appliances_included'     => $request->has_all_appliances_included,
                'has_all_appliances_not_included' => $request->has_all_appliances_not_included,
                'has_washer_dryer_included'       => $request->has_washer_dryer_included,
                'has_washer_dryer_not_included'   => $request->has_washer_dryer_not_included,

                'address'            => $request->address,
                'country'            => $request->country,
                'state'              => $request->state,
                'city'               => $request->city,
                'zip_code'           => $request->zip_code,
                'map_location'       => $request->map_location,
            ]);

            if ($request->has('deleted_files')) {
                $toDeleteIds = $request->input('deleted_files', []);

                $imagesToDelete = ListingImage::where('listing_id', $listing->id)
                    ->whereIn('id', $toDeleteIds)
                    ->get();

                foreach ($imagesToDelete as $img) {
                    if ($img->type === 'image') {
                        $path = public_path('storage/listing/images/' . $img->filename);
                    } else {
                        $path = public_path('storage/listing/videos/' . $img->filename);
                    }

                    if (file_exists($path)) {
                        @unlink($path);
                    }

                    $img->delete();
                }
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $mime      = $file->getMimeType();
                    $extension = strtolower($file->getClientOriginalExtension());

                    $isImage = str_contains($mime, 'image');
                    $isVideo = str_contains($mime, 'video');

                    if (!$isImage && !$isVideo) {
                        continue;
                    }

                    $fileName = Str::random(20) . '_file.' . $extension;

                    if ($isImage) {
                        $folder = 'storage/listing/images';
                        $type   = 'image';
                    } else {
                        $folder = 'storage/listing/videos';
                        $type   = 'video';
                    }

                    $file->move(public_path($folder), $fileName);

                    ListingImage::create([
                        'listing_id' => $listing->id,
                        'type'       => $type,
                        'filename'   => $fileName,
                    ]);
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Your Listing Has Been Updated Successfully!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => "Error: {$e->getMessage()}",
            ], 200);
        }
    }

    public function destory(Listing $listing)
    {
        try {
            $listing->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Your Listing Deleted Successfully!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => "Error Message, $e",
            ], 200);
        }
    }

    public function deleteMainImage(Listing $listing)
    {
        try {
            if ($listing->main_image) {
                $path = public_path('storage/listing/images/' . $listing->main_image);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }

            $listing->main_image = null;
            $listing->save();

            return response()->json([
                'status'  => true,
                'message' => 'Main image deleted successfully!',
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => "Error Message, $e",
            ], 200);
        }
    }

    public function deleteFile(Request $request, Listing $listing)
    {
        $files = json_decode($listing->files);
        $path  = $request->file_path;

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                $filteredFiles = array_filter($files, function ($file) use ($path) {
                    return $file->path !== $path;
                });

                $filteredFiles = array_values($filteredFiles);

                if (count($filteredFiles) > 0) {
                    $listing->files = json_encode($filteredFiles);
                } else {
                    $listing->files = null;
                }

                $listing->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'File Deleted Successfully!',
                ], 200);
            }

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => "Error Message : $e",
            ], 200);
        }
    }
}
