<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Studio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'slug', 'bio', 'address', 'city', 'postal_code',
        'country', 'phone', 'email', 'website', 'siret',
        'hygiene_certificate', 'ars_declaration_number', 'logo_url', 'banner_url',
        'opening_hours', 'is_active', 'stripe_account_id', 'stripe_onboarding_complete',
        'payment_model', 'max_artists'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_active' => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'joined_at' => 'datetime',
    ];

    // Relations
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function artists()
    {
        return $this->hasMany(StudioArtist::class);
    }

    public function activeArtists()
    {
        return $this->artists()->where('is_active', true);
    }
}
