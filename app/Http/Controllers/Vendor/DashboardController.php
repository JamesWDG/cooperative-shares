<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Listing;
use App\Models\PropertyTraffic;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        // ===== BASIC STATS =====
        $totalListings = Listing::where('user_id', $vendorId)->count();

        $totalLeads = Lead::whereHas('listing', function ($query) use ($vendorId) {
            $query->where('user_id', $vendorId);
        })->count();

        $totalAppointments = Appointment::whereHas('lead.listing', function ($query) use ($vendorId) {
            $query->where('user_id', $vendorId);
        })->count();

        // ===== COMMON DATES =====
        $currentMonthStart   = Carbon::now()->startOfMonth();
        $currentMonthEnd     = Carbon::now()->endOfMonth();
        $previousMonthStart  = (clone $currentMonthStart)->subMonth();
        $previousMonthEnd    = (clone $currentMonthEnd)->subMonth();
        $today               = Carbon::today();
        $startDate           = Carbon::today()->subDays(14);  // last 15 days
        $endDate             = Carbon::today();

        // ===================================================
        // TRAFFIC STATS (property_traffic se)
        // ===================================================

        // Overall traffic (vendor ki sab properties)
        $overallVisits = PropertyTraffic::where('vendor_id', $vendorId)->count();

        // Monthly traffic (current month)
        $monthlyVisits = PropertyTraffic::where('vendor_id', $vendorId)
            ->whereBetween('visited_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Previous month traffic
        $previousMonthVisits = PropertyTraffic::where('vendor_id', $vendorId)
            ->whereBetween('visited_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        if ($previousMonthVisits > 0) {
            $rawChange = (($monthlyVisits - $previousMonthVisits) / $previousMonthVisits) * 100;
            $trafficTrendText     = $rawChange >= 0 ? 'High' : 'Low';
            $trafficChangePercent = round(abs($rawChange), 2);
        } else {
            // pehle month me visits nahi the, to agar ab kuch hai to 100% High
            $trafficTrendText     = 'High';
            $trafficChangePercent = $monthlyVisits > 0 ? 100 : 0;
        }

        // Daily traffic (aaj ka)
        $dailyVisits = PropertyTraffic::where('vendor_id', $vendorId)
            ->whereDate('visited_at', $today)
            ->count();

        // Daily traffic for last 15 days
        $rawDailyTraffic = PropertyTraffic::selectRaw('DATE(visited_at) as visit_date, COUNT(*) as total')
            ->where('vendor_id', $vendorId)
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->pluck('total', 'visit_date')
            ->toArray();

        // ===================================================
        // CONVERSIONS (LEADS) STATS
        // ===================================================

        // Overall = us vendor ki sab leads ka count
        $overallLeads = $totalLeads;

        // Monthly leads (current month)
        $monthlyLeads = Lead::whereHas('listing', function ($query) use ($vendorId) {
                $query->where('user_id', $vendorId);
            })
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        // Previous month leads
        $previousMonthLeads = Lead::whereHas('listing', function ($query) use ($vendorId) {
                $query->where('user_id', $vendorId);
            })
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        if ($previousMonthLeads > 0) {
            $rawLeadChange = (($monthlyLeads - $previousMonthLeads) / $previousMonthLeads) * 100;
            $conversionTrendText     = $rawLeadChange >= 0 ? 'High' : 'Low';
            $conversionChangePercent = round(abs($rawLeadChange), 2);
        } else {
            $conversionTrendText     = 'High';
            $conversionChangePercent = $monthlyLeads > 0 ? 100 : 0;
        }

        // Daily leads (aaj ka)
        $dailyLeads = Lead::whereHas('listing', function ($query) use ($vendorId) {
                $query->where('user_id', $vendorId);
            })
            ->whereDate('created_at', $today)
            ->count();

        // Daily leads for last 15 days
        $rawDailyLeads = Lead::selectRaw('DATE(created_at) as lead_date, COUNT(*) as total')
            ->whereHas('listing', function ($query) use ($vendorId) {
                $query->where('user_id', $vendorId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('lead_date')
            ->orderBy('lead_date')
            ->pluck('total', 'lead_date')
            ->toArray();

        // ===================================================
        // BUILD CHART ARRAYS (same labels for traffic + leads)
        // ===================================================

        $trafficChartLabels  = [];
        $trafficChartData    = [];
        $conversionChartData = []; // leads data

        $cursor = $startDate->copy();
        while ($cursor <= $endDate) {
            $key = $cursor->toDateString();                 // e.g. 2025-11-20
            $trafficChartLabels[]  = $cursor->format('M d'); // e.g. "Nov 20"
            $trafficChartData[]    = $rawDailyTraffic[$key] ?? 0;
            $conversionChartData[] = $rawDailyLeads[$key] ?? 0;
            $cursor->addDay();
        }
        $plans = Plan::orderBy('price')->get();
        return view('screens.vendor.dashboard.index', [
            'totalListings'          => $totalListings,
            'totalLeads'             => $totalLeads,
            'totalAppointments'      => $totalAppointments,

            // Traffic widget
            'trafficChangePercent'   => $trafficChangePercent,
            'trafficTrendText'       => $trafficTrendText,
            'overallVisits'          => $overallVisits,
            'monthlyVisits'          => $monthlyVisits,
            'dailyVisits'            => $dailyVisits,
            'trafficChartLabels'     => $trafficChartLabels,
            'trafficChartData'       => $trafficChartData,

            // Conversions (Leads) widget
            'conversionChangePercent'=> $conversionChangePercent,
            'conversionTrendText'    => $conversionTrendText,
            'overallLeads'           => $overallLeads,
            'monthlyLeads'           => $monthlyLeads,
            'dailyLeads'             => $dailyLeads,
            'conversionChartData'    => $conversionChartData,
            // ⬇ NEW
            'plans'                   => $plans,
        ]);
    }
}
