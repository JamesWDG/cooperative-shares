<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactFormController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\TrainingVideosController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\AdsPurchasedController;

use App\Http\Controllers\Admin\CMS\HeaderController;
use App\Http\Controllers\Admin\CMS\FooterController;
use App\Http\Controllers\Admin\CMS\HomeController;
use App\Http\Controllers\Admin\CMS\AboutController;
use App\Http\Controllers\Admin\CMS\FaqsController;
use App\Http\Controllers\Admin\CMS\PrivacyPolicyController;
use App\Http\Controllers\Admin\CMS\TermsConditionsController;
use App\Http\Controllers\Admin\CMS\CooperativeDifferencesController;
use App\Http\Controllers\Admin\CMS\ContactSettingsController;
use App\Http\Controllers\Admin\CMS\SocialIconsController;
use App\Http\Controllers\Admin\NotificationController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('cms')->name('cms.')->group(function () {

    /** -------------------------
     * HEADER PAGE CMS ROUTES
     * ------------------------- */
    Route::name('header.')->controller(HeaderController::class)->group(function () {
        Route::get('/header/list', 'headerList')->name('list');
        Route::get('/header/{type}', 'showHeaderPage')->name('page');
        Route::post('/header/update', 'updateHeaderSection')->name('update');
    });

    /** -------------------------
     * FOOTER PAGE CMS ROUTES
     * ------------------------- */
    Route::name('footer.')->controller(FooterController::class)->group(function () {
        Route::get('/footer/list', 'footerList')->name('list');
        Route::get('/footer/{type}', 'showFooterPage')->name('page');
        Route::post('/footer/update', 'updateFooterSection')->name('update');
    });

    /** -------------------------
     * HOME PAGE CMS ROUTES
     * ------------------------- */
    Route::name('home.')->controller(HomeController::class)->group(function () {
        Route::get('/home/list', 'homeList')->name('list');
        Route::get('/home/{type}', 'showHomePage')->name('page');
        Route::post('/home/update', 'updateHomeSection')->name('update');
    });

    /** -------------------------
     * ABOUT PAGE CMS ROUTES
     * ------------------------- */
    Route::name('about.')->controller(AboutController::class)->group(function () {
        Route::get('/about/list', 'aboutList')->name('list');
        Route::get('/about/{type}', 'showAboutPage')->name('page');
        Route::post('/about/update', 'updateAboutSection')->name('update');
    });

    /** -------------------------
     * FAQ PAGE CMS ROUTES
     * ------------------------- */
    Route::name('faqs.')->controller(FaqsController::class)->group(function () {
        Route::get('/faqs/list', 'faqsList')->name('list');
        Route::get('/faqs/{type}', 'showFaqsPage')->name('page');
        Route::post('/faqs/update', 'updateFaqsSection')->name('update');
    });

    /** -------------------------
     * PRIVACY POLICY PAGE CMS ROUTES
     * ------------------------- */
    Route::name('privacy.')->controller(PrivacyPolicyController::class)->group(function () {
        Route::get('/privacy-policy/list', 'privacyList')->name('list');
        Route::get('/privacy-policy/{type}', 'showPrivacyPage')->name('page');
        Route::post('/privacy-policy/update', 'updatePrivacySection')->name('update');
    });

    /** -------------------------
     * TERMS & CONDITIONS ROUTES
     * ------------------------- */
    Route::name('terms.')->controller(TermsConditionsController::class)->group(function () {
        Route::get('/terms-conditions/list', 'termsList')->name('list');
        Route::get('/terms-conditions/{type}', 'showTermsPage')->name('page');
        Route::post('/terms-conditions/update', 'updateTermsSection')->name('update');
    });

    /** -------------------------
     * COOPERATIVE DIFFERENCES ROUTES
     * ------------------------- */
    Route::name('cooperative-differences.')->controller(CooperativeDifferencesController::class)->group(function () {
        Route::get('/cooperative-differences/list', 'differencesList')->name('list');
        Route::get('/cooperative-differences/{type}', 'showDifferencesPage')->name('page');
        Route::post('/cooperative-differences/update', 'updateDifferencesSection')->name('update');
    });

    /** -------------------------
     * CONTACT SETTINGS CMS ROUTES
     * ------------------------- */
    Route::name('contact-settings.')->controller(ContactSettingsController::class)->group(function () {
        Route::get('/contact-settings/list', 'contactList')->name('list');
        Route::get('/contact-settings/{type}', 'showContactPage')->name('page');
        Route::post('/contact-settings/update', 'updateContactSection')->name('update');
    });

    /** -------------------------
     * SOCIAL ICONS CMS ROUTES
     * ------------------------- */
    Route::name('social-icons.')->controller(SocialIconsController::class)->group(function () {
        Route::get('/social-icons/list', 'socialList')->name('list');
        Route::get('/social-icons/{type}', 'showSocialPage')->name('page');
        Route::post('/social-icons/update', 'updateSocialSection')->name('update');
    });

});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::controller(NotificationController::class)->group(function () {
    // List all notifications
    Route::get('/notifications', 'list')->name('notifications');

    // View single notification by slug
    Route::get('/notifications/{slug}', 'view')->name('notifications.view');

    // Delete multiple (AJAX)
    Route::post('/notifications/delete-multiple', 'deleteMultiple')
        ->name('notifications.delete-multiple');
});


Route::controller(UserController::class)->group(function(){
    Route::get('/users', 'index')->name('users');
    Route::get('/users/{user}/detail', 'detail')->name('user.detail');
});

Route::controller(AdminAuthController::class)->group(function () {
    Route::get('/profile', 'profileView')->name('profile');
    Route::post('/profile', 'profileStore')->name('profile.store');
    Route::post('/profile-image', 'profileImageStore')->name('profileImage.store');
    Route::delete('/profile-image', 'profileImageDestory')->name('profileImage.destory');
    Route::post('/password-update', 'passwordUpdate')->name('password.update');
});
Route::controller(ListingController::class)->group(function () {
    Route::get('/listings', 'index')->name('listings');
    Route::get('/listings/{listing}/detail', 'detail')->name('listing.detail');
    Route::delete('/listing/{listing}', [ListingController::class, 'destroy'])->name('listing.delete');

});

Route::controller(LeadController::class)->group(function(){
    Route::get('/leads', 'index')->name('leads');
    Route::get('/leads/{lead}/detail', 'detail')->name('lead.detail');
});
Route::controller(ContactFormController::class)->group(function(){
    Route::get('/contact-forms', 'index')->name('contact-forms');
    Route::get('/contact-forms/{contact}', 'detail')->name('contact.detail');
});

Route::controller(ReviewController::class)->group(function () {
    Route::get('/reviews', 'index')->name('reviews');
    Route::get('/reviews/create', 'create')->name('review.create');
    Route::post('/reviews', 'store')->name('review.store');
    Route::get('/reviews/{listing}/edit', 'edit')->name('review.edit');
    Route::put('/reviews/{review}', 'update')->name('review.update');
    Route::delete('/reviews/{review}', 'destory')->name('review.delete');
});

Route::controller(ServiceController::class)->group(function () {
    Route::get('/services', 'index')->name('services');
    Route::get('/services/create', 'create')->name('service.create');
    Route::post('/services', 'store')->name('service.store');
    Route::get('/services/{listing}/edit', 'edit')->name('service.edit');
    Route::put('/services/{service}', 'update')->name('service.update');
    Route::delete('/services/{service}', 'destory')->name('service.delete');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('blogs');
    Route::get('/blogs/create', 'create')->name('blog.create');
    Route::post('/blogs', 'store')->name('blog.store');
    Route::get('/blogs/{listing}/edit', 'edit')->name('blog.edit');
    Route::put('/blogs/{blog}', 'update')->name('blog.update');
    Route::delete('/blogs/{blog}', 'destory')->name('blog.delete');
});

Route::controller(TrainingVideosController::class)->group(function () {
    Route::get('/training-videos', 'index')->name('training-videos');
    Route::get('/training-videos/create', 'create')->name('training-video.create');
    Route::post('/training-videos', 'store')->name('training-video.store');
    Route::get('/training-videos/{trainingvideo}/edit', 'edit')->name('training-video.edit');
    Route::put('/training-videos/{trainingvideo}', 'update')->name('training-video.update');
    Route::delete('/training-videos/{trainingvideo}', 'destory')->name('training-video.delete');
});

Route::controller(AdvertisementController::class)->group(function () {
    Route::get('/advertisements', 'index')->name('advertisements');
    Route::get('/advertisements/create', 'create')->name('advertisement.create');
    Route::post('/advertisements', 'store')->name('advertisement.store');
    Route::get('/advertisements/{advertisement}/edit', 'edit')->name('advertisement.edit');
    Route::put('/advertisements/{advertisement}', 'update')->name('advertisement.update');
    Route::delete('/advertisements/{advertisement}', 'destory')->name('advertisement.delete');
});

Route::controller(AdsPurchasedController::class)->group(function () {
    Route::get('/ads-purchased', 'index')->name('ads-purchased');
    Route::get('/ads-purchased/create', 'create')->name('ads-purchased.create');
    Route::post('/ads-purchased', 'store')->name('ads-purchased.store');
    Route::get('/ads-purchased/{ads_purchased}/edit', 'edit')->name('ads-purchased.edit');
    Route::put('/ads-purchased/{ads_purchased}', 'update')->name('ads-purchased.update');
    Route::delete('/ads-purchased/{ads_purchased}', 'destory')->name('ads-purchased.delete');
});

