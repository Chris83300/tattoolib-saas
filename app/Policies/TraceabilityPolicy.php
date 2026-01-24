<?php

namespace App\Policies;

use App\Models\ClientConsentForm;
use App\Models\ParentalConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\User;

class TraceabilityPolicy
{
    /**
     * Determine whether user can view any consent forms.
     */
    public function viewAnyConsentForms(User $user): bool
    {
        return $user->isTattooer();
    }

    /**
     * Determine whether user can view consent form.
     */
    public function viewConsentForm(User $user, ClientConsentForm $consentForm): bool
    {
        // Tatoueur : peut voir tous ses formulaires
        if ($user->isTattooer() && $consentForm->user_id === $user->id) {
            return true;
        }

        // Client : peut voir uniquement ses propres formulaires
        if ($user->isClient() && $consentForm->client_id === $user->client->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether user can verify consent form.
     */
    public function verify(User $user, ClientConsentForm $consentForm): bool
    {
        // Uniquement le tatoueur peut vérifier
        return $user->isTattooer() && $consentForm->user_id === $user->id;
    }

    /**
     * Determine whether user can create consent form.
     */
    public function createConsentForm(User $user): bool
    {
        // Le client peut créer son propre formulaire
        return $user->isClient();
    }

    /**
     * Determine whether user can create parental consent.
     */
    public function createParentalConsent(User $user, ClientConsentForm $consentForm): bool
    {
        // Le client peut créer le consentement parental pour son formulaire
        return $user->isClient() &&
               $consentForm->client_id === $user->client->id &&
               $consentForm->requiresParentalConsent();
    }

    /**
     * Determine whether user can view any traceability records.
     */
    public function viewAnyTraceabilityRecords(User $user): bool
    {
        // Uniquement les tatoueurs peuvent voir la tracabilité
        return $user->isTattooer();
    }

    /**
     * Determine whether user can view traceability record.
     */
    public function viewTraceabilityRecord(User $user, TraceabilityRecord $record): bool
    {
        // Uniquement le tatoueur propriétaire peut voir l'enregistrement
        return $user->isTattooer() && $record->user_id === $user->id;
    }

    /**
     * Determine whether user can create traceability record.
     */
    public function createTraceabilityRecord(User $user): bool
    {
        // Uniquement les tatoueurs peuvent créer des enregistrements de tracabilité
        return $user->isTattooer();
    }

    /**
     * Determine whether user can update traceability record.
     */
    public function updateTraceabilityRecord(User $user, TraceabilityRecord $record): bool
    {
        // Uniquement le tatoueur propriétaire peut modifier
        return $user->isTattooer() && $record->user_id === $user->id;
    }

    /**
     * Determine whether user can delete traceability record.
     */
    public function deleteTraceabilityRecord(User $user, TraceabilityRecord $record): bool
    {
        // Uniquement le tatoueur propriétaire peut supprimer
        return $user->isTattooer() && $record->user_id === $user->id;
    }

    /**
     * Determine whether user can add materials to traceability.
     */
    public function addMaterials(User $user, TraceabilityRecord $record): bool
    {
        return $this->updateTraceabilityRecord($user, $record);
    }

    /**
     * Determine whether user can add photos to traceability.
     */
    public function addPhotos(User $user, TraceabilityRecord $record): bool
    {
        return $this->updateTraceabilityRecord($user, $record);
    }

    /**
     * Determine whether user can verify traceability.
     */
    public function verifyTraceability(User $user, TraceabilityRecord $record): bool
    {
        return $this->updateTraceabilityRecord($user, $record);
    }

    /**
     * Determine whether user can generate traceability reports.
     */
    public function generateReports(User $user): bool
    {
        // Uniquement les tatoueurs peuvent générer des rapports
        return $user->isTattooer();
    }

    /**
     * Determine whether user can access traceability statistics.
     */
    public function accessStatistics(User $user): bool
    {
        // Uniquement les tatoueurs peuvent voir les statistiques
        return $user->isTattooer();
    }
}
