<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $guarded = ['id'];
    
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
