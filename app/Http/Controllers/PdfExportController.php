<?php

namespace App\Http\Controllers;

use App\Models\ClientCareSheet;
use App\Models\ClientConsentForm;
use App\Models\ParentalConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Services\PdfExportService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfExportController extends Controller
{
    public function __construct(private readonly PdfExportService $pdfService)
    {
    }

    /**
     * Retourne l'artisan authentifié (Tattooer ou Piercer) ou aborte.
     */
    private function artisan()
    {
        $artisan = auth()->user()?->artisan();

        if (!$artisan) {
            abort(403, 'Accès réservé aux professionnels.');
        }

        return $artisan;
    }

    /**
     * Fiche de soins.
     */
    public function careSheet(ClientCareSheet $careSheet): Response
    {
        $artisan = $this->artisan();

        abort_if($careSheet->tattooer_id !== $artisan->id, 403);

        $pdf = $this->pdfService->generateCareSheet($careSheet);

        return $pdf->download('fiche-soins-' . $careSheet->id . '.pdf');
    }

    /**
     * Formulaire de consentement client.
     */
    public function consentForm(ClientConsentForm $consentForm): Response
    {
        $artisan = $this->artisan();

        abort_if($consentForm->tattooer_id !== $artisan->id, 403);

        $pdf = $this->pdfService->generateConsentForm($consentForm);

        return $pdf->download('consentement-' . $consentForm->id . '.pdf');
    }

    /**
     * Consentement parental.
     */
    public function parentalConsent(ParentalConsentForm $parentalConsent): Response
    {
        $artisan = $this->artisan();

        // ParentalConsentForm stocke l'user_id du professionnel
        abort_if($parentalConsent->user_id !== auth()->id(), 403);

        $pdf = $this->pdfService->generateParentalConsent($parentalConsent);

        return $pdf->download('consentement-parental-' . $parentalConsent->id . '.pdf');
    }

    /**
     * Fiche de traçabilité.
     */
    public function traceabilityRecord(TraceabilityRecord $traceabilityRecord): Response
    {
        $artisan = $this->artisan();

        abort_if($traceabilityRecord->tattooer_id !== $artisan->id, 403);

        $pdf = $this->pdfService->generateTraceabilityRecord($traceabilityRecord);

        return $pdf->download('tracabilite-' . $traceabilityRecord->id . '.pdf');
    }

    /**
     * Récapitulatif fiche client.
     */
    public function clientSummary(Client $client): Response
    {
        $artisan = $this->artisan();

        $pdf = $this->pdfService->generateClientSummary($client, $artisan);

        return $pdf->download('fiche-client-' . $client->id . '.pdf');
    }

    /**
     * Reçu de prestation.
     */
    public function receipt(BookingRequest $booking): Response
    {
        $artisan = $this->artisan();

        abort_if(
            $booking->bookable_id !== $artisan->id || $booking->bookable_type !== get_class($artisan),
            403
        );

        $pdf = $this->pdfService->generateReceipt($booking);

        return $pdf->download('recu-prestation-' . $booking->id . '.pdf');
    }
}
