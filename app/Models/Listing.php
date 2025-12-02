<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Filter;

class Listing extends Model
{
    use Filter;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    
    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }
    
    public function purchasedAdvertisements()
    {
        return $this->hasMany(AdsPurchased::class);
    }

}
