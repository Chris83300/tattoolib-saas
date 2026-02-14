<?php

namespace App\Enums;

enum BookingRequestStatus: string
{
    case PENDING = 'pending';                    // demande envoyée par le client
    case ACCEPTED = 'accepted';                  // tatoueur a accepté + envoyé conditions
    case DEPOSIT_REQUESTED = 'deposit_requested'; // en attente de paiement acompte
    case DEPOSIT_PAID = 'deposit_paid';          // acompte payé
    case DATE_CONFIRMED = 'date_confirmed';      // date du RDV confirmée par les 2 parties
    case COMPLETED = 'completed';                // prestation effectuée
    case BALANCE_PAID = 'balance_paid';          // solde payé en ligne
    case BALANCE_PAID_OFFLINE = 'balance_paid_offline'; // solde payé hors plateforme
    case FULLY_COMPLETED = 'fully_completed';    // tout terminé (acompte + solde)
    case REJECTED = 'rejected';                  // refusée par le tattooer
    case CANCELLED = 'cancelled';                // annulée (par client ou tatoueur) après acceptation
    case EXPIRED = 'expired';                    // délai dépassé (acompte non payé)
    case NO_SHOW = 'no_show';                    // client absent au RDV

    /**
     * Labels français pour l'affichage
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::ACCEPTED => 'Accepté',
            self::DEPOSIT_REQUESTED => 'En attente d\'acompte',
            self::DEPOSIT_PAID => 'Acompte payé',
            self::DATE_CONFIRMED => 'Date confirmée',
            self::COMPLETED => 'Terminé',
            self::BALANCE_PAID => 'Solde payé (en ligne)',
            self::BALANCE_PAID_OFFLINE => 'Solde payé (hors ligne)',
            self::FULLY_COMPLETED => 'Prestation complète',
            self::REJECTED => 'Refusé',
            self::CANCELLED => 'Annulé',
            self::EXPIRED => 'Expiré',
            self::NO_SHOW => 'Client absent',
        };
    }

    /**
     * Couleurs pour les badges UI (retourne les classes Tailwind complètes)
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'jaune-alerte',
            self::ACCEPTED => 'beige-peau',
            self::DEPOSIT_REQUESTED => 'ambre-warning',
            self::DEPOSIT_PAID => 'vert-succes',
            self::DATE_CONFIRMED => 'beige-peau',
            self::COMPLETED => 'vert-succes',
            self::BALANCE_PAID => 'vert-succes',
            self::BALANCE_PAID_OFFLINE => 'vert-succes',
            self::FULLY_COMPLETED => 'vert-succes',
            self::REJECTED => 'rouge-alerte',
            self::CANCELLED => 'rouge-alerte',
            self::EXPIRED => 'rouge-alerte',
            self::NO_SHOW => 'rouge-alerte',
        };
    }

    /**
     * Vérifie si la transition vers un autre statut est autorisée
     */
    public function canTransitionTo(self $target): bool
    {
        return match($this) {
            self::PENDING => in_array($target, [self::ACCEPTED, self::REJECTED]),
            self::ACCEPTED => in_array($target, [self::DEPOSIT_REQUESTED, self::CANCELLED]),
            self::DEPOSIT_REQUESTED => in_array($target, [self::DEPOSIT_PAID, self::EXPIRED, self::CANCELLED]),
            self::DEPOSIT_PAID => in_array($target, [self::DATE_CONFIRMED, self::CANCELLED]),
            self::DATE_CONFIRMED => in_array($target, [self::COMPLETED, self::NO_SHOW, self::CANCELLED]),
            self::COMPLETED => in_array($target, [self::BALANCE_PAID, self::BALANCE_PAID_OFFLINE, self::FULLY_COMPLETED]),
            self::BALANCE_PAID => in_array($target, [self::FULLY_COMPLETED]),
            self::BALANCE_PAID_OFFLINE => in_array($target, [self::FULLY_COMPLETED]),
            self::FULLY_COMPLETED => false, // État terminal
            self::REJECTED => false, // État terminal
            self::CANCELLED => false, // État terminal
            self::EXPIRED => false, // État terminal
            self::NO_SHOW => false, // État terminal
        };
    }

    /**
     * Obtenir les transitions possibles depuis ce statut
     */
    public function getPossibleTransitions(): array
    {
        return match($this) {
            self::PENDING => [self::ACCEPTED, self::REJECTED],
            self::ACCEPTED => [self::DEPOSIT_REQUESTED, self::CANCELLED],
            self::DEPOSIT_REQUESTED => [self::DEPOSIT_PAID, self::EXPIRED, self::CANCELLED],
            self::DEPOSIT_PAID => [self::DATE_CONFIRMED, self::CANCELLED],
            self::DATE_CONFIRMED => [self::COMPLETED, self::NO_SHOW, self::CANCELLED],
            self::COMPLETED => [self::BALANCE_PAID, self::BALANCE_PAID_OFFLINE, self::FULLY_COMPLETED],
            self::BALANCE_PAID => [self::FULLY_COMPLETED],
            self::BALANCE_PAID_OFFLINE => [self::FULLY_COMPLETED],
            self::FULLY_COMPLETED => [],
            self::REJECTED => [],
            self::CANCELLED => [],
            self::EXPIRED => [],
            self::NO_SHOW => [],
        };
    }

    /**
     * Vérifie si le statut est terminal (plus de transitions possibles)
     */
    public function isTerminal(): bool
    {
        return empty($this->getPossibleTransitions());
    }

    /**
     * Vérifie si le statut est actif (en cours)
     */
    public function isActive(): bool
    {
        return in_array($this, [self::ACCEPTED, self::DEPOSIT_REQUESTED, self::DEPOSIT_PAID, self::DATE_CONFIRMED]);
    }

    /**
     * Vérifie si le statut permet le paiement d'acompte
     */
    public function allowsDepositPayment(): bool
    {
        return $this === self::DEPOSIT_REQUESTED;
    }

    /**
     * Vérifie si le statut permet l'envoi de designs
     */
    public function allowsDesignSending(): bool
    {
        return in_array($this, [self::DEPOSIT_PAID, self::DATE_CONFIRMED]);
    }

    /**
     * Vérifie si le statut permet la confirmation de date
     */
    public function allowsDateConfirmation(): bool
    {
        return $this === self::DEPOSIT_PAID;
    }

    /**
     * Vérifie si le statut permet le paiement du solde
     */
    public function allowsBalancePayment(): bool
    {
        return $this === self::COMPLETED;
    }
}
