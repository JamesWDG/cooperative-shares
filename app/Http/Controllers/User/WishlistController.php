<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected $viewFolderPath = 'screens.user.';

    /**
     * Show all saved listings of current user
     */
    public function index()
    {
        $user = Auth::user();

        // Get or create wishlist for this user
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Paginate wishlist items with listing relation
        $wishlistItems = $wishlist->items()
            ->with('listing')
            ->orderByDesc('id')
            ->paginate(8);

        return view($this->viewFolderPath . 'wishlist', compact('wishlistItems'));
    }

    /**
     * Remove a wishlist item via AJAX
     */
    public function remove(Request $request)
    {
        $request->validate([
            'wishlist_item_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $wishlist = Wishlist::where('user_id', $user->id)->first();

        if (!$wishlist) {
            return response()->json([
                'status'  => false,
                'message' => 'Wishlist not found.',
            ], 404);
        }

        $item = $wishlist->items()->where('id', $request->wishlist_item_id)->first();

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Wishlist item not found.',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Listing removed from your saved list.',
        ]);
    }
}
