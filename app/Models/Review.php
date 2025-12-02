<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'client_name',
        'client_role',
        'review_text',
        'client_image',
        'rating',
        'status'
    ];
}
