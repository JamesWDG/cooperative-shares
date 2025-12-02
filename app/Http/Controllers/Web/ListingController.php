<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Models\PropertyTraffic;
use Carbon\Carbon;
use App\Filters\Web\Listings\ListingTypeFilter;
use App\Filters\Web\Listings\RoomFilter;
use App\Filters\Web\Listings\BathroomFilter;
use App\Filters\Web\Listings\SquareFeetFilter;


class ListingController extends Controller
{
    public function index(Request $request)
    { 
        // Agar Feature wali par filter apply nae krna to yeh Query hy 
        // $listings = Listing::with('images')
        //     ->where(function ($q) use ($request) {
        
        //         $q->where('listing', 'featured') // featured always included
        //           ->orWhere(function ($query) use ($request) {
                        
        //                 // SIMPLE listings → apply filters
        //                 $query->where('listing', '!=', 'featured')
        //                       ->filter([
        //                           ListingTypeFilter::class,
        //                           RoomFilter::class,
        //                           BathroomFilter::class,
        //                           SquareFeetFilter::class
        //                       ]);
        //           });
        //     })
        //     ->orderByRaw("CASE 
        //         WHEN listing = 'featured' THEN 1
        //         ELSE 2
        //     END")
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(2);
        
        // Agar Feature wali par bhi filter apply krna hy to yeh Query hy 
        
        // Load images with listings to avoid N+1 and for front image fallback
        $listings = Listing::with('images')
                ->filter([
                    ListingTypeFilter::class,
                    RoomFilter::class,
                    BathroomFilter::class,
                    SquareFeetFilter::class
                ])
                ->orderByRaw("CASE 
                    WHEN listing = 'featured' THEN 1
                    ELSE 2
                END")
                ->orderBy('created_at', 'desc') // simple walay latest dikhne chahiye
                ->paginate(15); 
                
        return view('screens.web.listings', compact('listings'));
    }
    public function show(Listing $listing)
    {
        
        // Detail page ke liye relations load
        $listing->load(['user', 'images']);

        // ðŸ”¹ TRAFFIC LOGGING (property_traffic table)
        $visitorId = auth()->check() ? auth()->id() : 0;

        PropertyTraffic::create([
            'property_id' => $listing->id,
            // yahan vendor_id wo hoga jo listing ka owner hais
            // agar column ka naam user_id hai to ye theek hai,
            // agar vendors alag table me hain to us hisab se change kar lena
            'vendor_id'   => $listing->user_id,
            'visitor_id'  => $visitorId,
            'visited_at'  => Carbon::now(),
        ]);

        // ðŸ”¹ Existing lead check (pehle jaisa hi)
        $existingLead = null;

        if (auth()->check()) {
            $existingLead = Lead::where('listing_id', $listing->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('screens.web.listing-detail', get_defined_vars());
    }
    public function indexOld()
    {
        $listings = Listing::paginate(12);
        return view('screens.web.listings', compact('listings'));
    }

    public function showOld(Listing $listing)
    {
        $listing->load('user');
        $existingLead = null;

        if (auth()->check()) {
            $existingLead = Lead::where('listing_id', $listing->id)
                ->where('user_id', auth()->user()->id)
                ->exists();
        }
        return view('screens.web.listing-detail', get_defined_vars());
    }
}
