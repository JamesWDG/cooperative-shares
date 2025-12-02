<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration_in_days',
        'standard_limit',
        'featured_free_limit',
        'allow_coop',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'duration_in_days' => 'integer',
        'standard_limit'   => 'integer',
        'featured_free_limit' => 'integer',
        'allow_coop'       => 'boolean',
    ];

    /**
     * Plan ke saare vendor subscriptions
     */
    public function vendorSubscriptions(): HasMany
    {
        return $this->hasMany(VendorSubscription::class);
    }
}
