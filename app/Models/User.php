<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string $role
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_verified',
        'verified_at',
        'newsletter',
        'provider',
        'provider_id',
        'profile_photo',
        'otp_code',
        'otp_expires_at',
        'last_otp_sent_at',
        'password_reset_otp',
        'password_reset_otp_expires_at',
        'last_password_reset_otp_sent_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'last_otp_sent_at' => 'datetime',
            'last_password_reset_otp_sent_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'newsletter' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->useDisk('public')
            ->singleFile();

        $this->addMediaCollection('product_images')
            ->useDisk('public');
    }

// Add this method to your model
    public function rewardPoints()
    {
        return $this->hasMany(RewardPoint::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getMediaDirectory(string $collectionName = ''): string
    {
        return match($collectionName) {
            'avatars' => 'avatars',
            'product_images' => 'products',
            default => $this->getKey()
        };
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if ($media && $media->collection_name === 'product_images') {
            // Product image conversions
            $this->addMediaConversion('product_thumb')
                ->width(150)
                ->height(150)
                ->sharpen(10)
                ->queued();

            $this->addMediaConversion('product_medium')
                ->width(300)
                ->height(300)
                ->queued();

            $this->addMediaConversion('product_large')
                ->width(1024)
                ->height(1024)
                ->queued();
        } else if ($media && $media->collection_name === 'avatars') {
            // Avatar conversions with responsive images
            $this->addMediaConversion('avatar_thumb')
                ->width(150)
                ->height(150)
                ->sharpen(10)
                ->queued();

            $this->addMediaConversion('avatar_medium')
                ->width(300)
                ->height(300)
                ->queued();

            $this->addMediaConversion('avatar_large')
                ->width(1024)
                ->height(1024)
                ->queued();

        }
    }
}
