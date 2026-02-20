<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case PENDING = 'pending';
    case INVESTIGATING = 'investigating';
    case RESOLVED = 'resolved';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::INVESTIGATING => 'En investigation',
            self::RESOLVED => 'Résolu',
            self::REJECTED => 'Rejeté',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::INVESTIGATING => 'info',
            self::RESOLVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::PENDING => 'heroicon-o-clock',
            self::INVESTIGATING => 'heroicon-o-magnifying-glass',
            self::RESOLVED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }
}
