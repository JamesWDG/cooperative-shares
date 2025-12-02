<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadFormRequest;
use App\Models\Appointment;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::whereHas('listing', function ($query) {
            // Agar future me filter lagana ho owner pe, yahan use kar sakte:
            // $query->where('user_id', Auth::id());
        })
        ->with(['listing.user', 'appointment']) // 👈 yahan listing + user load
        ->get(); // DataTables use kar rahe ho to paginate ki jagah get() better
    
        return view('screens.admin.leads.index', compact('leads'));
    }

    public function indexOld()
    {
        $leads = Lead::whereHas('listing', function ($query) {
            //$query->where('user_id', Auth::id());
        })->with('appointment')->paginate(5);

        return view('screens.admin.leads.index', compact('leads'));
    }
    public function detail(Lead $lead)
    {
        $lead->load(['listing.user', 'appointment']);
    
        return view('screens.admin.leads.detail', compact('lead'));
    }

    public function detailOld(Lead $lead)
    {
        return view('screens.admin.leads.detail', compact('lead'));
    }
}
