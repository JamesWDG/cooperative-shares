<?php

namespace App\Filters\Web\Listings;

use Closure;

class ListingTypeFilter
{
    function handle($query,Closure $next)  {
        $term = request()->listing_type;
        
        if(!$term || $term == '') return $next($query);
        
        return $next($query->where('listed_in', 'like',$term));
    }
}
