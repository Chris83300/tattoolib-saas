<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudioAccountingEntry extends Model
{
    use SoftDeletes;

    // =============================================
    // CONSTANTES
    // =============================================

    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';
    const TYPE_ARTIST_PAYOUT = 'artist_payout';
    const TYPE_OTHER = 'other';

    // =============================================
    // CONFIGURATION
    // =============================================

    protected $fillable = [
        'studio_id',
        'entry_type',
        'amount',
        'description',
        'category',
        'payment_id',
        'studio_artist_id',
        'transaction_date',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'attachments' => 'array',
    ];

    // =============================================
    // RELATIONS
    // =============================================

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function studioArtist()
    {
        return $this->belongsTo(StudioArtist::class);
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeIncome($query)
    {
        return $query->where('entry_type', self::TYPE_INCOME);
    }

    public function scopeExpense($query)
    {
        return $query->where('entry_type', self::TYPE_EXPENSE);
    }

    public function scopeArtistPayout($query)
    {
        return $query->where('entry_type', self::TYPE_ARTIST_PAYOUT);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // =============================================
    // HELPERS
    // =============================================

    public function isIncome(): bool
    {
        return $this->entry_type === self::TYPE_INCOME;
    }

    public function isExpense(): bool
    {
        return $this->entry_type === self::TYPE_EXPENSE;
    }

    public function isArtistPayout(): bool
    {
        return $this->entry_type === self::TYPE_ARTIST_PAYOUT;
    }

    /**
     * Badge HTML selon type
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->entry_type) {
            self::TYPE_INCOME => '<span class="badge bg-success">💰 Revenu</span>',
            self::TYPE_EXPENSE => '<span class="badge bg-danger">💸 Dépense</span>',
            self::TYPE_ARTIST_PAYOUT => '<span class="badge bg-info">👤 Versement</span>',
            self::TYPE_OTHER => '<span class="badge bg-secondary">📋 Autre</span>',
            default => '<span class="badge bg-light">❓</span>',
        };
    }

    /**
     * Formater montant pour affichage
     */
    public function getFormattedAmountAttribute(): string
    {
        $prefix = $this->isIncome() ? '+' : ($this->isExpense() || $this->isArtistPayout() ? '-' : '');
        return $prefix . number_format($this->amount, 2, ',', ' ') . '€';
    }
}
