<?php

namespace App\Filters\Web\Listings;

use Closure;

class SquareFeetFilter
{
    function handle($query,Closure $next)  {
        $term = request()->sqfeet;
        
        if(!$term || $term == '') return $next($query);
        
        return $next($query->where('size_in_ft', $term));
    }
}
