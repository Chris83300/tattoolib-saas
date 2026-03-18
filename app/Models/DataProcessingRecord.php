<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataProcessingRecord extends Model
{
    protected $fillable = [
        'name',
        'purpose',
        'legal_basis',
        'data_categories',
        'data_subjects',
        'recipients',
        'transfers_outside_eu',
        'retention_period',
        'security_measures',
        'requires_dpia',
        'dpia_notes',
        'is_active',
    ];

    protected $casts = [
        'data_categories'      => 'array',
        'data_subjects'        => 'array',
        'recipients'           => 'array',
        'transfers_outside_eu' => 'boolean',
        'requires_dpia'        => 'boolean',
        'is_active'            => 'boolean',
    ];
}
