<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'bride_name',
        'bride_father_name',
        'bride_mother_name',
        'bride_photo',
        'groom_name',
        'groom_father_name',
        'groom_mother_name',
        'groom_photo',
        'wedding_date',
        'cover_image',
        'opening_text',
        'closing_text',
        'music_url',
        'theme_primary_color',
        'theme_secondary_color',
        'theme_accent_color',
        'theme_font_family',
        'is_active',
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(WeddingEvent::class)->orderBy('order');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(WeddingGallery::class)->orderBy('order');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(WeddingGuest::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(WeddingRsvp::class);
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(WeddingWish::class)->where('is_approved', true)->latest();
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(WeddingGift::class)->orderBy('order');
    }
}
