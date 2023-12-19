<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Distance extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_location',
        'destination_location',
        'distance'
    ];

    public function sourceLocation() : BelongsTo
    {
        return $this->belongsTo(Location::class, 'source_location');
    }

    public function destinationLocation() : BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location');
    }

    /*public static function getSourceLocation(): array
    {
        return self::distinct()->pluck('source_location')->toArray();
    }

    public static function getDestinationLocation(): array
    {
        return self::distinct()->pluck('destination_location')->toArray();
    }*/
}
