<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingRsvp extends Model
{
    protected $fillable = [
        'wedding_id',
        'wedding_guest_id',
        'guest_name',
        'attendance_status',
        'guest_count',
        'notes',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(WeddingGuest::class, 'wedding_guest_id');
    }
}
