<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Listing;
use App\Models\VendorSubscription;
use App\Models\VendorFeatureUsage;
use App\Models\VendorStandardUsage;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getMemberSinceAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }


    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function isVendor()
    {
        return $this->role == 'vendor';
    }

    public function isUser()
    {
        return $this->role == 'user';
    }

    /**
     * Vendor ke saare subscriptions (history)
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(VendorSubscription::class, 'vendor_id');
    }

    /**
     * Current active subscription (agar ho)
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(VendorSubscription::class, 'vendor_id')
                    ->where('is_active', 1)
                    ->where('expires_at', '>', now())
                    ->latest('id');
    }

    /**
     * Feature usage record
     */
    public function featureUsage(): HasOne
    {
        return $this->hasOne(VendorFeatureUsage::class, 'vendor_id');
    }

    /**
     * Standard usage record
     */
    public function standardUsage(): HasOne
    {
        return $this->hasOne(VendorStandardUsage::class, 'vendor_id');
    }
    
    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }
    
    public function galleries()
    {
        return $this->hasMany(Gallery::class,'vendor_id');
    }
    
    public function vendorBlogs()
    {
        return $this->hasMany(VendorBlog::class,'vendor_id');
    }
    
    public function purchasedAds()
    {
        return $this->hasMany(AdsPurchased::class,'user_id');
    }
}
