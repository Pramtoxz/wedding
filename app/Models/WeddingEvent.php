<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingEvent extends Model
{
    protected $fillable = [
        'wedding_id',
        'event_name',
        'event_date',
        'event_time',
        'location_name',
        'address',
        'map_url',
        'latitude',
        'longitude',
        'order',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
