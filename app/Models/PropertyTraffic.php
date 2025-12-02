<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyTraffic extends Model
{
    protected $table = 'property_traffic';

    protected $fillable = [
        'property_id',
        'vendor_id',
        'visitor_id',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
