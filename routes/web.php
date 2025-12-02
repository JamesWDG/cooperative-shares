<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\WishlistController;
use App\Http\Controllers\Web\ListingController;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\ContactFormController;

/*
|--------------------------------------------------------------------------
| Admin auth routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::post('/forgot-password/send-otp',   [ForgotPasswordController::class, 'sendOtp'])->name('password.forgot.sendOtp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.forgot.verifyOtp');
Route::post('/forgot-password/reset',      [ForgotPasswordController::class, 'resetPassword'])->name('password.forgot.reset');


Route::post('/contact-submit', [ContactFormController::class, 'store'])->name('contact.submit');
// Admin login page (only view)
Route::prefix('admin')->name('admin.')->group(function () {

    // GET: /admin/login → show popup page (your admin login blade)
    Route::get('/login', function () {
        return view('screens.admin.login');
    })->name('login');

    // POST: /admin/login → login process (this is what your AJAX uses)
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login-process'); // route('admin.login-process')

    // GET: /admin/logout → protected by admin middleware
    Route::get('/logout', [AdminAuthController::class, 'destroy'])
        ->name('logout')
        ->middleware('admin');
});

Route::get('/clear-cache', function () {
    /*Artisan::call('cache:clear');*/
    Artisan::call('optimize:clear');

    return "Cache cleared";
});


Route::controller(UserAuthController::class)->middleware('web')->group(function(){
    Route::post('/register', 'register')->name('user.register');
    Route::post('/login', 'login')->name('user.login');
    Route::get('/logout','destroy')->name('user.logout');
});

Route::controller(VendorAuthController::class)->middleware('web')->group(function(){
    Route::post('/vendor-register', 'register')->name('vendor.register');
    Route::post('/vendor-login', 'login')->name('vendor.login');
    Route::get('/vendor-logout','destroy')->name('vendor.logout');
});

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::controller(ListingController::class)->group(function(){
    Route::get('/listings', 'index')->name('listings');
    Route::get('/listing-detail/{listing}','show')->name('listing.detail');
});

Route::controller(LeadController::class)->group(function(){
    Route::post('/leads', 'store')->name('lead.store');
});

Route::get('/services', function () {
    return view('screens.web.services');
})->name('services');

Route::get('/service-details', function () {
    return view('screens.web.service-details');
})->name('services.detail');


Route::middleware('auth')->name('wishlist.')->group(function () {

    // Get user's wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('index');

    // Add product to wishlist
    Route::post('/wishlist/add/{listing}', [WishlistController::class, 'add'])->name('add');

    // Remove product from wishlist
    Route::post('/wishlist/remove/{listing}', [WishlistController::class, 'remove'])->name('remove');
});

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/cooperrative-differences', [HomeController::class, 'cooperativeDifferences'])->name('cooperrative.differences');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/reviews', [HomeController::class, 'reviews'])->name('reviews');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
Route::get('/blogs/{blog}', [HomeController::class, 'blogDetail'])->name('blog-detail');
Route::get('/home/blog/{slug}', [HomeController::class, 'homeBlogDetail'])
    ->name('home.blog.detail');

Route::get('/realtor-profile/{vendor}', [HomeController::class, 'realtorProfile'])->name('realtor.profile');
Route::get('/home/vendor-blog/{slug}', [HomeController::class, 'VendorBlogDetail'])
    ->name('vendor.blog.detail');

Route::get('/tutorials', [HomeController::class, 'tutorials'])->name('tutorials');
