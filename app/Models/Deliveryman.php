<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Deliveryman extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'mobile_number',
        'password',
        'rc_book',
        'vehicle_image',
        'photo',
        'aadhar_card',
        'pan_card',
        'driving_licence',
        'insurance',
        'fcm_token',
    ];

    protected $hidden = ['password'];

    protected $appends = [
        'aadhar_card_url',
        'driving_licence_url',
        'insurance_url',
        'photo_url',
        'vehicle_image_url',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getDrivingLicenceAttribute($value)
    {
        return $value ?: ($this->attributes['pan_card'] ?? null);
    }

    public function getInsuranceAttribute($value)
    {
        return $value ?: ($this->attributes['rc_book'] ?? null);
    }

    protected function aadharCardUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->aadhar_card) ? asset('storage/' . $this->aadhar_card) : null,
        );
    }

    protected function drivingLicenceUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $doc = $this->driving_licence ?: ($this->attributes['pan_card'] ?? null);
                return !empty($doc) ? asset('storage/' . $doc) : null;
            },
        );
    }

    protected function insuranceUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $doc = $this->insurance ?: ($this->attributes['rc_book'] ?? null);
                return !empty($doc) ? asset('storage/' . $doc) : null;
            },
        );
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->photo) ? asset('storage/' . $this->photo) : null,
        );
    }

    protected function vehicleImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->vehicle_image) ? asset('storage/' . $this->vehicle_image) : null,
        );
    }
}
