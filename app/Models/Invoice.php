<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'type',
        'reference_id',
        'invoice_number',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'issued_at',
        'paid_at',
        'due_at',
        'meta',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'paid_at'   => 'datetime',
        'due_at'    => 'datetime',
        'meta'      => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id'); // ya Vendor::class if separate
    }
    
    public function purchasedAd()
    {
        return $this->belongsTo(AdsPurchased::class, 'reference_id')
                ->where('type', 'Advertisement Plan');
    }
    
    
    public function subscriptionPlan()
    {
        return $this->belongsTo(AdsPurchased::class, 'reference_id')
                    ->where('type', 'Subscription Plan');
    }
}
