<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'title',
        'short_des',
        'long_des',
        'featured_img',
        'background_img'
    ];
}
