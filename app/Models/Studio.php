<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'description',
        'logo_url',
        'is_verified',
        'opening_hours',
        'social_media_links',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'opening_hours' => 'array',
        'social_media_links' => 'array',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    public function tattooers()
    {
        return $this->hasMany(Tattooer::class);
    }

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }
}
