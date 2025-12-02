<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorStandardUsage extends Model
{
    protected $table = 'vendor_standard_usage';

    protected $fillable = [
        'vendor_id',
        'plan_id',
        'vendor_subscription_id',
        'active_standard',
        'is_active',
    ];

    protected $casts = [
        'active_standard' => 'integer',
        'is_active'       => 'boolean',
    ];

    /**
     * Kis vendor ka standard listings usage hai
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Kis plan ke against usage hai
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Kis subscription (billing cycle) ke against usage hai
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(VendorSubscription::class, 'vendor_subscription_id');
    }
}
