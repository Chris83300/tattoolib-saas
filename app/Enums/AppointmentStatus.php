<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case CONFIRMED = 'confirmed';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';
    case NO_SHOW_CLIENT = 'no_show_client';
    case NO_SHOW_ARTIST = 'no_show_artist';
    case DISPUTED = 'disputed';

    /**
     * Labels français pour l'affichage
     */
    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Programmé',
            self::CONFIRMED => 'Confirmé',
            self::IN_PROGRESS => 'En cours',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
            self::NO_SHOW => 'Client absent',
            self::NO_SHOW_CLIENT => 'Client absent',
            self::NO_SHOW_ARTIST => 'Artiste absent',
            self::DISPUTED => 'Contestation',
        };
    }

    /**
     * Couleurs pour les badges UI
     */
    public function color(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::CONFIRMED => 'green',
            self::IN_PROGRESS => 'yellow',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'red',
            self::NO_SHOW => 'red',
            self::NO_SHOW_CLIENT => 'red',
            self::NO_SHOW_ARTIST => 'orange',
            self::DISPUTED => 'purple',
        };
    }

    /**
     * Vérifie si la transition vers un autre statut est autorisée
     */
    public function canTransitionTo(self $target): bool
    {
        return match($this) {
            self::SCHEDULED => in_array($target, [self::CONFIRMED, self::CANCELLED]),
            self::CONFIRMED => in_array($target, [self::IN_PROGRESS, self::CANCELLED, self::NO_SHOW_CLIENT, self::NO_SHOW_ARTIST]),
            self::IN_PROGRESS => in_array($target, [self::COMPLETED, self::CANCELLED]),
            self::COMPLETED => false, // État terminal
            self::CANCELLED => false, // État terminal
            self::NO_SHOW => false, // État terminal
            self::NO_SHOW_CLIENT => false, // État terminal
            self::NO_SHOW_ARTIST => false, // État terminal
            self::DISPUTED => false, // État terminal
        };
    }

    /**
     * Obtenir les transitions possibles depuis ce statut
     */
    public function getPossibleTransitions(): array
    {
        return match($this) {
            self::SCHEDULED => [self::CONFIRMED, self::CANCELLED],
            self::CONFIRMED => [self::IN_PROGRESS, self::CANCELLED, self::NO_SHOW_CLIENT, self::NO_SHOW_ARTIST],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED => [],
            self::CANCELLED => [],
            self::NO_SHOW => [],
            self::NO_SHOW_CLIENT => [],
            self::NO_SHOW_ARTIST => [],
            self::DISPUTED => [],
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
        return in_array($this, [self::SCHEDULED, self::CONFIRMED, self::IN_PROGRESS]);
    }

    /**
     * Vérifie si le RDV est dans le passé
     */
    public function isPast(): bool
    {
        return in_array($this, [self::COMPLETED, self::CANCELLED, self::NO_SHOW, self::NO_SHOW_CLIENT, self::NO_SHOW_ARTIST, self::DISPUTED]);
    }

    /**
     * Vérifie si le RDV nécessite une confirmation
     */
    public function needsConfirmation(): bool
    {
        return $this === self::SCHEDULED;
    }

    /**
     * Vérifie si le RDV peut être annulé
     */
    public function isCancellable(): bool
    {
        return in_array($this, [self::SCHEDULED, self::CONFIRMED]);
    }

    /**
     * Vérifie si le RDV peut être confirmé comme terminé
     */
    public function canBeCompleted(): bool
    {
        return $this === self::IN_PROGRESS;
    }

    /**
     * Vérifie si le RDV peut signaler une absence
     */
    public function canReportNoShow(): bool
    {
        return $this === self::CONFIRMED;
    }

    /**
     * Vérifie si le RDV peut être auto-complété (passé depuis 24h en status confirmed)
     */
    public function canBeAutoCompleted(): bool
    {
        return $this === self::CONFIRMED;
    }
}
