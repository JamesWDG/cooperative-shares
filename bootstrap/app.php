<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureVendorSubscribed;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // User routes
            Route::middleware(['web','auth.redirect'])
                ->prefix('user')
                ->name('user.')
                ->group(base_path('routes/user.php'));
            // Vendor routes
            Route::middleware(['web','auth.redirect'])
                ->prefix('vendor')
                ->name('vendor.')
                ->group(base_path('routes/vendor.php'));
            // Admin routes
            Route::middleware(['web', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'auth.redirect' => RedirectIfNotAuthenticated::class,
            'admin'         => AdminMiddleware::class,
            'vendor.subscribed'  =>EnsureVendorSubscribed::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
