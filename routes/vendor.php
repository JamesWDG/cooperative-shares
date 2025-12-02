<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ListingController;
use App\Http\Controllers\Vendor\LocationController;
use App\Http\Controllers\Vendor\NotificationController;
use App\Http\Controllers\Vendor\SubscriptionController;
use App\Http\Controllers\Vendor\GalleryController;
use App\Http\Controllers\Vendor\AnalyticsController;
use App\Http\Controllers\Vendor\InvoiceController;
use App\Http\Controllers\Vendor\VendorBlogController;
use App\Http\Controllers\Vendor\MarketingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Vendor Subscription Routes (No subscription required)
|--------------------------------------------------------------------------
| Ye routes un vendors ke liye bhi accessible honge jinka subscription
| abhi active nahi hai / expire ho chuka hai. Inhi pages se woh plan
| purchase karenge.
*/

Route::get('/subscription-plans', [SubscriptionController::class, 'index'])
    ->name('subscription.plans');

// Stripe charge route for subscription modal (AJAX)
Route::post('/subscription/stripe/charge', [SubscriptionController::class, 'createStripeSubscriptionPayment'])
    ->name('stripe.post');


/*
|--------------------------------------------------------------------------
| Vendor Protected Routes (Require active subscription)
|--------------------------------------------------------------------------
| In sab routes par 'vendor.subscribed' middleware lagega. Agar subscription
| inactive / expired hai to middleware unhe wapas subscription-plans par
| redirect kar dega.
*/

Route::middleware('vendor.subscribed')->group(function () {

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        // List all notifications
        Route::get('/notifications', 'list')->name('notifications');

        // View single notification by slug
        Route::get('/notifications/{slug}', 'view')->name('notifications.view');

        // Delete multiple (AJAX)
        Route::post('/notifications/delete-multiple', 'deleteMultiple')
            ->name('notifications.delete-multiple');
    });

    // Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    // Payment methods
    Route::get('/payment-methods', function () {
        return view('screens.vendor.payment-methods');
    })->name('payment.methods');

    // Profile & account
    Route::controller(VendorAuthController::class)->group(function () {
        Route::get('/profile', 'profileView')->name('profile');
        Route::post('/profile', 'profileStore')->name('profile.store');
        Route::post('/profile-image', 'profileImageStore')->name('profileImage.store');
        Route::delete('/profile-image', 'profileImageDestory')->name('profileImage.destory');
        Route::post('/password-update', 'passwordUpdate')->name('password.update');
        Route::post('/profile-logo', 'profileLogoStore')->name('profileLogo.store');
        Route::delete('/profile-logo', 'profileLogoDestory')->name('profileLogo.destory');
    });

    // Location (country/state/city API)
    Route::controller(LocationController::class)->group(function () {
        Route::get('/countries', 'countries')->name('get.countries');
        Route::get('/states', 'states')->name('get.states');
        Route::get('/cities/{state}', 'cities')->name('get.cities');
    });

    // Listings
Route::controller(ListingController::class)->group(function () {

    Route::get('/listings', 'index')->name('listings');

    // Single create view (always shows Standard + Featured options)
    Route::get('/listings/create', 'create')->name('listing.create');

    // ✅ Single store method (handles: active plan + paid featured)
    Route::post('/listings', 'store')->name('listing.store');

    // ✅ Single validate endpoint (property + plan usage + payment decision)
    // You can keep the method name validatePaidFeaturedListing in controller,
    // just the route path + name are now generic.
    Route::post('/listings/validate', 'validatePaidFeaturedListing')
        ->name('listing.validate');

    // Edit / update / delete
    Route::get('/listings/{listing}/edit', 'edit')->name('listing.edit');
    Route::put('/listings/{listing}', 'update')->name('listing.update');
    Route::delete('/listings/{listing}', 'destory')->name('listing.delete');

    Route::delete('/listings/image/{listing}', 'deleteMainImage')
        ->name('listing.main.image.delete');

    Route::delete('listings/files/{listing}', 'deleteFile')
        ->name('listing.file.delete');
});


    // Leads
    Route::controller(LeadController::class)->group(function(){
        Route::get('/leads', 'index')->name('leads');
        Route::get('/leads/{lead}/edit', 'edit')->name('lead.edit');
        Route::put('/leads/{lead}', 'update')->name('lead.update');
        Route::delete('/leads/{lead}', 'destroy')->name('lead.delete');
    });

    // Appointments
    Route::controller(AppointmentController::class)->group(function(){
        Route::get('/appointments', 'index')->name('appointments');
        Route::put('/appointments/{appointment}', 'update')->name('appointment.update.status');
        Route::delete('/appointments/{appointment}', 'destroy')->name('appointment.delete');
    });

    // Marketing plans
    
    Route::controller(MarketingController::class)->group(function () {
        Route::get('/get-form-modal/{advertisement}', 'getModalForm')->name('get.modal.form');
        Route::get('/marketing-plans', 'index')->name('marketing.plans');
        Route::post('/marketing-plans/purchaseAd/{advertisement}', 'purchaseAd')->name('marketing.plans.purchase.ad');
    });
    
    // Calendar
    Route::get('/calender', function () {
        return view('screens.vendor.calender');
    })->name('calender');

    // Analytics
    Route::controller(AnalyticsController::class)->group(function () {
        Route::get('/analytics', 'analytics')->name('analytics');
    });

    // Invoices
    Route::get('/invoices', function () {
        return view('screens.vendor.invoices');
    })->name('invoices');

    Route::controller(VendorBlogController::class)->group(function () {
        Route::get('/co-op', 'index')->name('blogs');
        Route::get('/co-op/create', 'create')->name('blog.create');
        Route::post('/co-op', 'store')->name('blog.store');
        Route::get('/co-op/{listing}/edit', 'edit')->name('blog.edit');
        Route::put('/co-op/{blog}', 'update')->name('blog.update');
        Route::delete('/co-op/{blog}', 'destory')->name('blog.delete');
    });
    //Co-Op section
    // Route::get('/co-op', function () {
    //     return view('screens.vendor.co-op');
    // })->name('co.op');

    Route::controller(GalleryController::class)->group(function () {
        Route::get('/gallery', 'index')->name('gallery');
        Route::get('/gallery/create', 'create')->name('gallery.create');
        Route::post('/gallery', 'store')->name('gallery.store');
        Route::delete('/gallery/{gallery}', 'destory')->name('gallery.delete');
    });


    Route::controller(InvoiceController::class)->group(function () {
        // List all invoices
        Route::get('/invoices', 'index')->name('invoices');

        // View a single invoice
        Route::get('/invoices/{invoice}', 'view')->name('invoice.view');

        // Delete single invoice (AJAX)
        Route::delete('/invoices/{invoice}', 'destroy')->name('invoice.delete');

        // Delete multiple invoices (AJAX)
        Route::post('/invoices/delete-multiple', 'deleteMultiple')->name('invoice.delete-multiple');
    });

    


});
