<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'advertisements';

    protected $guarded = ['id'];
    
    public function purchasedAds()
    {
        return $this->hasMany(AdsPurchased::class, 'add_id'); 
    }
    
    public function promotions()
    {
        return $this->hasMany(AdvertisementPromotion::class); 
    }
}
