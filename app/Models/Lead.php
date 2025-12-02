<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lead extends Model
{
    protected $guarded = [];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
    public function user()
    {
        // user who created the inquiry/lead
        return $this->belongsTo(User::class);
    }
}
