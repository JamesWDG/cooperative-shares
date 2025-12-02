<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;


// Dashboard
Route::controller(DashboardController::class)->group(function () {
   Route::get('/', 'index')->name('dashboard');
});
Route::controller(UserAuthController::class)->group(function () {
    Route::get('/profile', 'profileView')->name('profile');
    Route::post('/profile', 'profileStore')->name('profile.store');
    Route::post('/profile-image', 'profileImageStore')->name('profileImage.store');
    Route::delete('/profile-image', 'profileImageDestory')->name('profileImage.destory');
    Route::post('/password-update', 'passwordUpdate')->name('password.update');
});
Route::controller(AppointmentController::class)->group(function () {
   Route::get('/appointments', 'index')->name('appointments');
});
Route::controller(WishlistController::class)->group(function () {
    Route::get('/saved-listing', 'index')->name('saved-listing');
    Route::post('/saved-listing/remove', 'remove')->name('saved-listing.remove');
});

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
