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

class PdfExportController extends Controller
{
    public function __construct(private readonly PdfExportService $pdfService)
    {
    }

    /**
     * Retourne le professionnel authentifié (Tattooer, Piercer ou Studio) ou aborte.
     */
    private function professional()
    {
        $user = auth()->user();

        // Artisans indépendants
        if ($user->isArtisan()) {
            return $user->artisan();
        }

        // Studios
        if ($user->isStudio()) {
            return $user->studio;
        }

        // Studio artists (utilisent le studio du propriétaire)
        if ($user->isStudioArtist()) {
            return $user->artistStudio();
        }

        abort(403, 'Accès réservé aux professionnels.');
    }

    /**
     * Fiche de soins.
     */
    public function careSheet(ClientCareSheet $careSheet): Response
    {
        $professional = $this->professional();
        $user = auth()->user();

        // Vérifier que la fiche de soins appartient au professionnel
        if ($user->isArtisan()) {
            abort_if($careSheet->tattooer_id !== $professional->id, 403);
        } elseif ($user->isStudio() || $user->isStudioArtist()) {
            // Pour les studios : vérifier soit que la fiche appartient directement au studio,
            // soit qu'elle appartient à un des artistes du studio
            $belongsToStudio = $careSheet->studio_id === $professional->id;

            if (!$belongsToStudio && $careSheet->tattooer_id) {
                // Vérifier si l'artiste qui a créé la fiche appartient à ce studio
                $tattooer = \App\Models\Tattooer::find($careSheet->tattooer_id);
                $belongsToStudio = $tattooer && $tattooer->studio_id === $professional->id;
            }

            abort_if(!$belongsToStudio, 403);
        }

        $pdf = $this->pdfService->generateCareSheet($careSheet);

        return $pdf->download('fiche-soins-' . $careSheet->id . '.pdf');
    }

    /**
     * Formulaire de consentement client.
     */
    public function consentForm(ClientConsentForm $consentForm): Response
    {
        $professional = $this->professional();
        $user = auth()->user();

        // Vérifier que le formulaire appartient au professionnel
        if ($user->isArtisan()) {
            abort_if($consentForm->tattooer_id !== $professional->id, 403);
        } elseif ($user->isStudio() || $user->isStudioArtist()) {
            // Pour les studios : vérifier soit que le formulaire appartient directement au studio,
            // soit qu'il appartient à un des artistes du studio
            $belongsToStudio = $consentForm->studio_id === $professional->id;

            if (!$belongsToStudio && $consentForm->tattooer_id) {
                // Vérifier si l'artiste qui a créé le formulaire appartient à ce studio
                $tattooer = \App\Models\Tattooer::find($consentForm->tattooer_id);
                $belongsToStudio = $tattooer && $tattooer->studio_id === $professional->id;
            }

            abort_if(!$belongsToStudio, 403);
        }

        $pdf = $this->pdfService->generateConsentForm($consentForm);

        return $pdf->download('consentement-' . $consentForm->id . '.pdf');
    }

    /**
     * Consentement parental.
     */
    public function parentalConsent(ParentalConsentForm $parentalConsent): Response
    {
        $professional = $this->professional();
        $user = auth()->user();

        // ParentalConsentForm stocke l'user_id du professionnel
        abort_if($parentalConsent->user_id !== $user->id, 403);

        $pdf = $this->pdfService->generateParentalConsent($parentalConsent);

        return $pdf->download('consentement-parental-' . $parentalConsent->id . '.pdf');
    }

    /**
     * Fiche de traçabilité.
     */
    public function traceabilityRecord(TraceabilityRecord $traceabilityRecord): Response
    {
        $professional = $this->professional();
        $user = auth()->user();

        // Vérifier que la fiche de traçabilité appartient au professionnel
        if ($user->isArtisan()) {
            abort_if($traceabilityRecord->tattooer_id !== $professional->id, 403);
        } elseif ($user->isStudio() || $user->isStudioArtist()) {
            // Pour les studios : vérifier soit que la fiche appartient directement au studio,
            // soit qu'elle appartient à un des artistes du studio
            $belongsToStudio = $traceabilityRecord->studio_id === $professional->id;

            if (!$belongsToStudio && $traceabilityRecord->tattooer_id) {
                // Vérifier si l'artiste qui a créé la fiche appartient à ce studio
                $tattooer = \App\Models\Tattooer::find($traceabilityRecord->tattooer_id);
                $belongsToStudio = $tattooer && $tattooer->studio_id === $professional->id;
            }

            abort_if(!$belongsToStudio, 403);
        }

        $pdf = $this->pdfService->generateTraceabilityRecord($traceabilityRecord);

        return $pdf->download('tracabilite-' . $traceabilityRecord->id . '.pdf');
    }

    /**
     * Récapitulatif fiche client.
     */
    public function clientSummary(Client $client): Response
    {
        $professional = $this->professional();

        $pdf = $this->pdfService->generateClientSummary($client, $professional);

        return $pdf->download('fiche-client-' . $client->id . '.pdf');
    }

    /**
     * Reçu de prestation.
     */
    public function receipt(BookingRequest $booking): Response
    {
        $professional = $this->professional();
        $user = auth()->user();

        // Vérifier que la réservation appartient au professionnel
        if ($user->isArtisan()) {
            abort_if(
                $booking->bookable_id !== $professional->id || $booking->bookable_type !== get_class($professional),
                403
            );
        } elseif ($user->isStudio() || $user->isStudioArtist()) {
            abort_if(
                $booking->bookable_id !== $professional->id || $booking->bookable_type !== get_class($professional),
                403
            );
        }

        $pdf = $this->pdfService->generateReceipt($booking);

        return $pdf->download('recu-prestation-' . $booking->id . '.pdf');
    }
}
