<?php

namespace App\Filters\Web\Listings;

use Closure;

class BathroomFilter
{
    function handle($query,Closure $next)  {
        $term = request()->bathrooms;
        
        if(!$term || $term == '') return $next($query);
        
        return $next($query->where('bathrooms', $term));
    }
}
