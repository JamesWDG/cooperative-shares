<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsPurchased extends Model
{
    protected $table = 'ads_purchased';

    protected $guarded = ['id'];

    // Relationships (optional, but useful)

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'add_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
    
    public function invoices()
    {
        return $this->hasMany(Invoice::class); 
    }
}
