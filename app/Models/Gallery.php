<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    /**
     * Table name
     */
    protected $table = 'galleries';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Fields allowed for mass assignment
     */
    protected $fillable = [
        'vendor_id',
        'image',
    ];

    /**
     * Automatically maintain timestamps
     */
    public $timestamps = true;

    /**
     * Accessor: get full URL for the image
     */
    public function getUrlAttribute()
    {
        return asset('gallery/' . $this->image);
    }

    
}
