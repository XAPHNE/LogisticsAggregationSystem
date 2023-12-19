<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'owned_by',
        'driven_by',
        'category_id',
        'registration_num',
        'permit_type',
        'insurance_expiry',
        'pollution_expiry',
        'fitness_expiry',
        'current_location',
        'max_height',
        'max_length',
        'max_width',
        'available_height',
        'available_length',
        'available_width',
        'status'
    ];

    public function currentLocation() : BelongsTo
    {
        return $this->belongsTo(Location::class, 'current_location');
    }

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owned_by');
    }

    public function driver() : BelongsTo
    {
        return $this->belongsTo(User::class, 'driven_by');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
