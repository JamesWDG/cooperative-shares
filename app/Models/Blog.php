<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'slug',
        'short_des',
        'long_des',
        'read_in_minutes',
        'featured_img'
    ];
}
