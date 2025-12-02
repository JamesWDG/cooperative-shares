<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorSubscription extends Model
{
    protected $fillable = [
        'vendor_id',
        'plan_id',
        'started_at',
        'expires_at',
        'stripe_charge_id',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    /**
     * Kis vendor (user) ka subscription hai
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Kaunsa plan liya hua hai
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope: sirf active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1)
                     ->where('expires_at', '>', now());
    }
}
