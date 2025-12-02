<?php

namespace App\Filters\Web\Listings;

use Closure;

class RoomFilter
{
    function handle($query,Closure $next)  {
        $term = request()->rooms;
        
        if(!$term || $term == '') return $next($query);
        
        return $next($query->where('bedrooms', $term));
    }
}
