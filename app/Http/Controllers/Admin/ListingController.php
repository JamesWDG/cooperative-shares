<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Listing;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::paginate(8);
        return view('screens.admin.listings.index', compact('listings'));
    }
    public function detail(Listing $listing)
    {
        $listing->load('images');
        return view('screens.admin.listings.detail', get_defined_vars());
    }
}
