<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalListings = Listing::count();
//dd($totalListings);
        $totalLeads = Lead::whereHas('listing', function ($query) {
            //$query->where('user_id', Auth::id());
        })->count();

        $totalAppointments = Appointment::whereHas('lead.listing', function ($query) {
            //$query->where('user_id', Auth::id());
        })->count();

        return view('screens.admin.dashboard.index', get_defined_vars());
    }
}
