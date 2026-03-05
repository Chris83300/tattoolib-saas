<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClientCareSheet;
use App\Models\ClientConsentForm;
use App\Models\ParentalConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\BookingRequest;
use App\Models\Client;

class PdfExportService
{
    /**
     * Fiche de soins post-tatouage/piercing.
     * Obligation légale : art. R.1311-12 du Code de la Santé Publique.
     */
    public function generateCareSheet(ClientCareSheet $careSheet): \Barryvdh\DomPDF\PDF
    {
        $careSheet->load(['client', 'tattooer.user', 'studio']);

        $professional = $careSheet->tattooer ?? $careSheet->studio;
        $isStudio = $careSheet->studio !== null;

        return Pdf::loadView('pdf.care-sheet', [
            'careSheet' => $careSheet,
            'professional' => $professional,
            'isStudio' => $isStudio,
            'client' => $careSheet->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Formulaire de consentement client.
     * Obligation légale : information préalable du futur tatoué (art. R.1311-12 CSP).
     */
    public function generateConsentForm(ClientConsentForm $consentForm): \Barryvdh\DomPDF\PDF
    {
        $consentForm->load(['tattooer.user', 'studio', 'client']);

        $professional = $consentForm->tattooer ?? $consentForm->studio;
        $isStudio = $consentForm->studio !== null;

        return Pdf::loadView('pdf.consent-form', [
            'consentForm' => $consentForm,
            'professional' => $professional,
            'isStudio' => $isStudio,
            'client' => $consentForm->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Formulaire de consentement parental (mineurs).
     * Obligation légale : art. R.1311-11 du CSP.
     */
    public function generateParentalConsent(ParentalConsentForm $parentalConsent): \Barryvdh\DomPDF\PDF
    {
        $parentalConsent->load(['tattooer.user', 'clientConsentForm.client']);

        $professional = $parentalConsent->tattooer;
        $isStudio = false; // Le consentement parental est lié à un artisan

        return Pdf::loadView('pdf.parental-consent', [
            'parentalConsent' => $parentalConsent,
            'professional' => $professional,
            'isStudio' => $isStudio,
            'consentForm' => $parentalConsent->clientConsentForm,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Fiche de traçabilité (encres, aiguilles, lots).
     * Obligation légale : traçabilité des produits et matériels.
     * Note : needles_used et inks_used sont des colonnes JSON sur TraceabilityRecord.
     */
    public function generateTraceabilityRecord(TraceabilityRecord $record): \Barryvdh\DomPDF\PDF
    {
        $record->load(['client', 'tattooer.user', 'studio']);

        $professional = $record->tattooer ?? $record->studio;
        $isStudio = $record->studio !== null;

        return Pdf::loadView('pdf.traceability-record', [
            'record' => $record,
            'professional' => $professional,
            'isStudio' => $isStudio,
            'client' => $record->client,
            'needles' => $record->needles_used ?? [],
            'inks' => $record->inks_used ?? [],
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Récapitulatif fiche client complète.
     */
    public function generateClientSummary(Client $client, $professional): \Barryvdh\DomPDF\PDF
    {
        $careSheets = ClientCareSheet::forArtisan($professional)
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        $consentForms = ClientConsentForm::forArtisan($professional)
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        $traceRecords = TraceabilityRecord::forArtisan($professional)
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        $isStudio = $professional instanceof \App\Models\Studio;

        return Pdf::loadView('pdf.client-summary', [
            'client' => $client,
            'professional' => $professional,
            'isStudio' => $isStudio,
            'careSheets' => $careSheets,
            'consentForms' => $consentForms,
            'traceRecords' => $traceRecords,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Reçu de prestation (mini-facture).
     */
    public function generateReceipt(BookingRequest $booking): \Barryvdh\DomPDF\PDF
    {
        $booking->load(['client', 'bookable.user', 'bookable.studio']);

        $professional = $booking->bookable;
        $isStudio = $professional instanceof \App\Models\Studio;

        return Pdf::loadView('pdf.receipt', [
            'booking' => $booking,
            'professional' => $professional,
            'isStudio' => $isStudio,
            'client' => $booking->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }
}
