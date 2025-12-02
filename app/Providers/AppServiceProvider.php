<?php

namespace App\Providers;

use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\CmsPage;
use App\Models\Advertisement;
use App\Models\VendorSubscription;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Formatting Money.
        Blade::directive('moneyFormat', function ($expression) {
            return "<?php
                \$amount = {$expression};
                if (\$amount >= 1000000) {
                    echo '$' . number_format(\$amount / 1000000, 3, '.', '') . 'M';
                } elseif (\$amount >= 1000) {
                    echo '$' . number_format(\$amount / 1000, 3, '.', '') . 'K';
                } else {
                    echo '$' . number_format(\$amount, 2);
                }
            ?>";
        });
        // Share social-links and form-search with all views
        
        $cmsPage = CmsPage::where('page_key', 'home')->first();
        $footerPage = CmsPage::where('page_key', 'footer')->first();
        $content = $cmsPage->content ?? [];
        $heroBanner       = $content['hero_banner']       ?? null;
        $social_links       = $heroBanner['social_links']       ?? null;
        $search_form       = $heroBanner['search_form']       ?? null;
        
        
        // $homepageSpotlightBannerAds = AdsPurchased::with('advertisement')->whereHas('advertisement',function($query){
        //     return $query->where('package_name', "Homepage Spotlight Banner");
        // })->whereDate('from_date', '<=', now()->endOfDay())
        //     ->whereDate('to_date', '>=', now()->startOfDay())
        //     ->get();
        
        $advertisements = Advertisement::with(['purchasedAds' => function ($q) {
            $q->whereDate('from_date', '<=', today())
              ->whereDate('to_date', '>=', today())
              ->with('vendor.listings');
        }])
        ->whereHas('purchasedAds', function ($q) {
            $q->whereDate('from_date', '<=', today())
              ->whereDate('to_date', '>=', today());
        })
        ->get()
        ->keyBy('package_name');
        
        $adsByPackage = $advertisements->mapWithKeys(function ($ad) {
            return [
                $ad->package_name => $ad->purchasedAds,
            ];
        });
        // ->toArray(); 
        
        // dd($adsByPackage);
        View::share([
            'social_links' => $social_links,
            'search_form'  => $search_form,
            'adsByPackage' => $adsByPackage,
            'footerPage' => $footerPage
        ]);

        
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $authUser = Auth::user();
        
                $adminnotifications = Notification::with(['user'])
                    ->where('notification_for', 'Admin')
                    ->where('status', 'UnRead')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
                $vendornotifications = Notification::with(['user'])
                    ->where('notification_for', 'Vendor')
                    ->where('receiver_id', $authUser->id)
                    ->where('status', 'UnRead')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
                $usernotifications = Notification::with(['user'])
                    ->where('notification_for', 'User')
                    ->where('receiver_id', $authUser->id)
                    ->where('status', 'UnRead')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
                // ✅ Vendor subscription + Co-Op access
                $vendorSubscription  = null;
                $vendorHasCoopAccess = false;
        
                // Only try vendor subscription if this user actually can be vendor (optional check)
                $vendorSubscription = VendorSubscription::active()
                    ->where('vendor_id', $authUser->id)
                    ->with('plan')
                    ->first();
        
                if ($vendorSubscription && $vendorSubscription->plan && $vendorSubscription->plan->allow_coop) {
                    $vendorHasCoopAccess = true;
                }
        
                // Wishlist count for this user
                $userWishlistCount = 0;
                $wishlist = Wishlist::withCount('items')
                    ->where('user_id', $authUser->id)
                    ->first();
        
                if ($wishlist) {
                    $userWishlistCount = $wishlist->items_count;
                }
        
                $view->with([
                    'adminnotifications' => $adminnotifications,
                    'vendornotifications' => $vendornotifications,
                    'usernotifications' => $usernotifications,
                    'vendorSubscription'  => $vendorSubscription,
                    'vendorHasCoopAccess' => $vendorHasCoopAccess,
                    'userWishlistCount'   => $userWishlistCount, 
                ]);
            } else {
                $view->with([
                    'adminnotifications' => collect(),
                    'vendornotifications' => collect(),
                    'usernotifications' => collect(),
                    'vendorSubscription' => collect(),
                    'vendorHasCoopAccess' => collect(),
                    'userWishlistCount'   => 0, 
                ]);
            }
        });


    }
}
