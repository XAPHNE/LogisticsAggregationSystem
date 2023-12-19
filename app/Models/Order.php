<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'source_location',
        'destination_location',
        'distance',
        'fleet_id',
        'weight',
        'load_at',
        'price',
        'status',
        'order_placed_by'
    ];

    public function sourceLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'source_location');
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location');
    }

    public function fleet() : BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function placedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'order_placed_by');
    }
}
