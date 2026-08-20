<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Deliveryman extends Model
{
    use HasApiTokens;

    protected $fillable = ['name', 'mobile_number', 'password'];

    protected $hidden = ['password'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
