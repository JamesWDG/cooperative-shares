<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBlog extends Model
{
    protected $table = 'vendor_blogs';

    protected $fillable = [
        'vendor_id',
        'title',
        'slug',
        'short_des',
        'long_des',
        'read_in_minutes',
        'featured_img'
    ];
}
