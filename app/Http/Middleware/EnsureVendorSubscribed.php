<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorSubscription;
use Carbon\Carbon;

class EnsureVendorSubscribed
{
    public function handle(Request $request, Closure $next)
    {
        $vendor = Auth::user(); 

        if (!$vendor) {
            return $next($request);
        }

        // ALLOW subscription routes WITHOUT subscription
        if ($request->routeIs('vendor.subscription.*')) {
            return $next($request);
        }

        // Check active subscription
        $subscription = VendorSubscription::where('vendor_id', $vendor->id)
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->first();

        $now = Carbon::now();
        $hasActive = false;

        if ($subscription) {
            if ($subscription->expires_at && $subscription->expires_at->lt($now)) {
                $subscription->is_active = 0;
                $subscription->save();
            } else {
                $hasActive = true;
            }
        }

        // ❌ No active subscription → force redirect
        if (!$hasActive) {

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Your subscription is inactive or expired.',
                    'redirect_url' => route('vendor.subscription.plans'),
                ], 402);
            }

            return redirect()
                ->route('vendor.subscription.plans')
                ->with('error', 'Your subscription is inactive or expired. Please purchase a plan to continue.');
        }

        return $next($request);
    }
}
