<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\PropertyTraffic;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $viewFolderPath = 'screens.user.';

    public function index()
    {
        $user = Auth::user();

        // 1. Listings count he visited (distinct listings)
        $listingCount = PropertyTraffic::where('visitor_id', $user->id)
            ->distinct('property_id')
            ->count('property_id');

        // 2. Appointment count for this user (through leads)
        $appointmentCount = Appointment::whereHas('lead', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        // 3. Leads count for this user
        $leadCount = Lead::where('user_id', $user->id)->count();

        // 4. Last 3 saved listings (wishlist)
        $wishlistItems = collect();

        $wishlist = Wishlist::where('user_id', $user->id)->first();
        if ($wishlist) {
            $wishlistItems = $wishlist->items()
                ->with('listing')
                ->latest()
                ->take(3)
                ->get();
        }

        return view($this->viewFolderPath . 'dashboard', [
            'listingCount'     => $listingCount,
            'appointmentCount' => $appointmentCount,
            'leadCount'        => $leadCount,
            'wishlistItems'    => $wishlistItems,
        ]);
    }
}
