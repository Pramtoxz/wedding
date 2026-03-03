<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingGift extends Model
{
    protected $fillable = [
        'wedding_id',
        'bank_name',
        'account_number',
        'account_name',
        'qr_code_path',
        'order',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
