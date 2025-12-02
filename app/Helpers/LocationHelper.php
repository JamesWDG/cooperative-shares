<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationHelper
{
    public static function getCountries()
    {
        return Cache::remember('countries', now()->addDays(30), function () {
            $response = Http::get('https://countriesnow.space/api/v0.1/countries');

            if ($response->successful()) {
                $data = $response->json();
                return collect($data['data'])->pluck('country', 'country')->sort();
            }

            return collect();
        });
    }

    public static function getStates($country)
    {
        $cacheKey = "states_{$country}";

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($country) {
            $response = Http::post('https://countriesnow.space/api/v0.1/countries/states', [
                'country' => $country
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['states'])) {
                    return collect($data['data']['states'])->pluck('name', 'name')->sort();
                }
            }

            return collect();
        });
    }

    public static function getCities($country, $state)
    {
        $cacheKey = "cities_{$country}_{$state}";

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($country, $state) {
            $response = Http::post('https://countriesnow.space/api/v0.1/countries/state/cities', [
                'country' => $country,
                'state' => $state
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'])) {
                    return collect($data['data'])->sort();
                }
            }

            return collect();
        });
    }

    // public static function getCountryOptions()
    // {
    //     return self::getCountries()->map(function ($name, $key) {
    //         return [
    //             'value' => $name,
    //             'text' => $name
    //         ];
    //     })->values();
    // }

    // public static function getStateOptions($country)
    // {
    //     return self::getStates($country)->map(function ($name, $key) {
    //         return [
    //             'value' => $name,
    //             'text' => $name
    //         ];
    //     })->values();
    // }
}
