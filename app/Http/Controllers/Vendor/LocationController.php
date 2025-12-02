<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;

class LocationController extends Controller
{
    public function countries()
    {
        // return response()->json(LocationHelper::getCountries());
        // dd(LocationHelper::getCountries());
    }

    public function states()
    {
        $states = LocationHelper::getStates('United States');

        State::insert(
            $states->map(function ($stateName) {
                return [
                    'name' => $stateName,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray()
        );
    }

    public function cities($id)
    {
        try {

            $cities = City::where('state_id', $id)->get();
            return response()->json([
                'status' => true,
                'cities' => $cities,
            ],200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => true,
                'message' => "Error : $e!",
            ],200);
        }
    }
}
