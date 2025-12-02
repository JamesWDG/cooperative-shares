<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementPromotion extends Model
{
    protected $guarded = ['id'];
    
    public function advertisement() {
        return $this->belongsTo(Advertisement::class);
    }
}
