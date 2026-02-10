<?php

namespace App\Enums;

enum ConversationStatus: string
{
    case PENDING = 'pending';        // avant acceptation
    case ACTIVE = 'active';          // chat ouvert (texte uniquement avant acompte)
    case FULL_ACCESS = 'full_access'; // après acompte (images autorisées)
    case CLOSING = 'closing';        // J+30 post-RDV, lecture seule bientôt
    case CLOSED = 'closed';          // fermé définitivement

    /**
     * Labels français pour l'affichage
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::ACTIVE => 'Actif',
            self::FULL_ACCESS => 'Accès complet',
            self::CLOSING => 'En cours de fermeture',
            self::CLOSED => 'Fermé',
        };
    }

    /**
     * Couleurs pour les badges UI
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::ACTIVE => 'green',
            self::FULL_ACCESS => 'blue',
            self::CLOSING => 'yellow',
            self::CLOSED => 'red',
        };
    }

    /**
     * Vérifie si la transition vers un autre statut est autorisée
     */
    public function canTransitionTo(self $target): bool
    {
        return match($this) {
            self::PENDING => in_array($target, [self::ACTIVE, self::CLOSED]),
            self::ACTIVE => in_array($target, [self::FULL_ACCESS, self::CLOSING, self::CLOSED]),
            self::FULL_ACCESS => in_array($target, [self::CLOSING, self::CLOSED]),
            self::CLOSING => in_array($target, [self::CLOSED]),
            self::CLOSED => false, // État terminal
        };
    }

    /**
     * Obtenir les transitions possibles depuis ce statut
     */
    public function getPossibleTransitions(): array
    {
        return match($this) {
            self::PENDING => [self::ACTIVE, self::CLOSED],
            self::ACTIVE => [self::FULL_ACCESS, self::CLOSING, self::CLOSED],
            self::FULL_ACCESS => [self::CLOSING, self::CLOSED],
            self::CLOSING => [self::CLOSED],
            self::CLOSED => [],
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
     * Vérifie si le statut est actif (discussion possible)
     */
    public function isActive(): bool
    {
        return in_array($this, [self::ACTIVE, self::FULL_ACCESS]);
    }

    /**
     * Vérifie si l'envoi de messages est permis
     */
    public function allowsMessaging(): bool
    {
        return in_array($this, [self::ACTIVE, self::FULL_ACCESS]);
    }

    /**
     * Vérifie si l'envoi d'images est permis
     */
    public function allowsImages(): bool
    {
        return $this === self::FULL_ACCESS;
    }

    /**
     * Vérifie si la conversation est en lecture seule
     */
    public function isReadOnly(): bool
    {
        return $this === self::CLOSING;
    }

    /**
     * Vérifie si la conversation est fermée
     */
    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }

    /**
     * Vérifie si la conversation peut être archivée
     */
    public function canBeArchived(): bool
    {
        return in_array($this, [self::CLOSING, self::CLOSED]);
    }

    /**
     * Vérifie si la conversation peut être supprimée
     */
    public function canBeDeleted(): bool
    {
        return $this === self::CLOSED;
    }
}
