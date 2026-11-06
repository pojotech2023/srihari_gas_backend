<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'deliveryman_id',
        'assigned_at',
        'status',
        'total_price',
        'delivery_charge',
        'trip_type',
        'distance_km',
        'round_trip_km',
        'payment_status',
        'payment_method',
    ];

    protected $with = [
        'items.product',
        'address',
        'deliveryman',
        'transaction',
    ];

    protected $appends = [
        'created_at_formatted',
        'updated_at_formatted',
        'assigned_at_formatted',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'float',
            'delivery_charge' => 'float',
            'distance_km' => 'float',
            'round_trip_km' => 'float',
            'assigned_at' => 'datetime',
        ];
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getCreatedAtFormattedAttribute(): ?string
    {
        return $this->created_at ? $this->created_at->format('M d, Y h:i A') : null;
    }

    public function getUpdatedAtFormattedAttribute(): ?string
    {
        return $this->updated_at ? $this->updated_at->format('M d, Y h:i A') : null;
    }

    public function getAssignedAtFormattedAttribute(): ?string
    {
        return $this->assigned_at ? $this->assigned_at->format('M d, Y h:i A') : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function deliveryman(): BelongsTo
    {
        return $this->belongsTo(Deliveryman::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
