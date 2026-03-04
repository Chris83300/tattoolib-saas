<?php

namespace App\Enums;

enum SubscriptionPlan: string
{
    case STARTER = 'starter';
    case PRO     = 'pro';
    case STUDIO  = 'studio';

    public function label(): string
    {
        return match ($this) {
            self::STARTER => 'Starter',
            self::PRO     => 'Pro',
            self::STUDIO  => 'Studio',
        };
    }

    public function price(): float
    {
        return match ($this) {
            self::STARTER => 9.99,
            self::PRO     => 29.99,
            self::STUDIO  => 59.99,
        };
    }

    public function pricePerExtraArtist(): float
    {
        return match ($this) {
            self::STUDIO => 24.99,
            default      => 0.0,
        };
    }

    public function commissionRate(): float
    {
        return match ($this) {
            self::STARTER => 0.07, // 7%
            self::PRO     => 0.0,
            self::STUDIO  => 0.0,
        };
    }

    public function hasCommission(): bool
    {
        return $this === self::STARTER;
    }

    /** Prix bêta-testeur (-30%). */
    public function betaPrice(): float
    {
        return round($this->price() * 0.70, 2);
    }

    public function betaPricePerExtraArtist(): float
    {
        return round($this->pricePerExtraArtist() * 0.70, 2);
    }

    public function trialDays(): int
    {
        return 14;
    }

    public function features(): array
    {
        $starter = [
            'Profil artiste vérifié',
            'Visible dans la marketplace',
            'Gestion des demandes & RDV',
            'Messagerie client',
            'Acompte sécurisé (Stripe)',
            'Fiches clients & traçabilité',
            'Consentements & soins',
            'Notifications automatiques',
            'Commission 7% par prestation',
        ];

        $pro = [
            'Tout le plan Starter',
            '0% de commission',
            'Mise en avant dans la marketplace',
            'Export PDF complet',
            'Export comptabilité CSV/Excel',
            'Badge PRO vérifié',
            'Support prioritaire',
        ];

        $studio = [
            'Tout le plan Pro',
            '1 artiste inclus',
            'Gestion multi-artistes',
            'Dashboard studio centralisé',
            'Planning global',
            'Statistiques & revenus',
            'Profil studio marketplace',
            'Facturation centralisée',
            'Panel Filament avancé',
        ];

        return match ($this) {
            self::STARTER => $starter,
            self::PRO     => $pro,
            self::STUDIO  => $studio,
        };
    }
}
