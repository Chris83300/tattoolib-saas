<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tattooer;
use App\Models\Piercer;

class StudioArtist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'studio_id', 'user_id', 'artisan_type', 'artisan_id',
        'role', 'joined_at', 'is_active', 'commission_rate'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_at' => 'datetime',
        'commission_rate' => 'decimal:8,2',
    ];

    // Relations
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artisan()
    {
        return $this->morphTo(
            $this->artisan_type === 'App\Models\Tattooer' ? Tattooer::class : Piercer::class,
            'artisan_id'
        );
    }
}
