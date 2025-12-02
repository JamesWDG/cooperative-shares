<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Support\Facades\DB;


class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getWishlist()
    {
        if(!auth()->check()){
            return response()->json([
                'status' => false,
                'error' => 'Unauthenticated',
                'message' => 'Please login first !'
                ],400);
        }
        
        return auth()->user()->wishlist()
            ->with('items.listing')
            ->first();
    }


    /**
     * Show the form for creating a new resource.
     */
    public function add(Listing $listing)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'error' => 'Unauthenticated',
                'message' => 'Please login first !'
            ], 400);
        }
    
        try {
    
            DB::beginTransaction();
    
            // 1) User wishlist find/create
            $wishlist = auth()->user()->wishlist()
                ->firstOrCreate([]);
    
            // 2) Add item if not already there
            $wishlist->items()->firstOrCreate([
                'listing_id' => $listing->id
            ]);
    
            DB::commit();
    
            return response()->json([
                'status'  => true,
                'message' => 'Added to wishlist!'
            ]);
    
        } catch (\Throwable $e) {
    
            DB::rollBack();
    
            return response()->json([
                'status'  => false,
                'error'   => 'Server Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function remove(Listing $listingId)
    {
        if(!auth()->check()){
            return response()->json([
                'status' => false,
                'error' => 'Unauthenticated',
                'message' => 'Please login first !'
                ],400);
        }
        $wishlist = auth()->user()->wishlist;
    
        if ($wishlist) {
            $wishlist->items()->where('listing_id', $listingId)->delete();
        }
        return response()->json([
                'status' => true,
                'message' => 'Removed from wishlist!'
                ],200);
    }
    
    
}
