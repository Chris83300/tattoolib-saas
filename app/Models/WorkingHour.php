<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'tattooer_id',
        'day_of_week', // 0 (dimanche) à 6 (samedi)
        'is_open',
        'opening_time',
        'closing_time',
        'is_break',
        'break_start',
        'break_end'
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'is_break' => 'boolean',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i'
    ];

    public function tattooer()
    {
        return $this->belongsTo(Tattooer::class);
    }
}
