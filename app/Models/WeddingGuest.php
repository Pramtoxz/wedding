<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WeddingGuest extends Model
{
    protected $fillable = [
        'wedding_id',
        'name',
        'partner_name',
        'whatsapp_number',
        'invitation_code',
        'status_kirim',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function rsvp(): HasOne
    {
        return $this->hasOne(WeddingRsvp::class);
    }
}
