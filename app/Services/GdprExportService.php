<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class GdprExportService
{
    /**
     * Générer un export JSON des données personnelles d'un utilisateur.
     * Ne contient que ses propres données (pas celles des autres utilisateurs).
     */
    public function exportUserData(User $user): string
    {
        $data = [];

        // Données de profil de base
        $data['profil'] = [
            'nom'         => $user->name,
            'prenom'      => $user->first_name,
            'email'       => $user->email,
            'telephone'   => $user->phone,
            'pseudo'      => $user->pseudo,
            'role'        => $user->role,
            'inscription' => $user->created_at?->format('d/m/Y'),
        ];

        // Données artiste (tatoueur)
        if ($tattooer = $user->tattooer) {
            $data['profil_artiste'] = $tattooer->only([
                'pseudo', 'bio', 'city', 'postal_code', 'email',
                'years_of_experience', 'minimum_price', 'siret',
                'current_plan',
            ]);
            $data['profil_artiste']['inscrit_le'] = $tattooer->created_at?->format('d/m/Y');

            $data['reservations_artiste'] = $tattooer->bookingRequests()
                ->select(['id', 'status', 'description', 'estimated_total_price', 'created_at'])
                ->get()
                ->map(fn($b) => [
                    'id'          => $b->id,
                    'statut'      => $b->status,
                    'description' => $b->description,
                    'montant'     => $b->estimated_total_price,
                    'date'        => $b->created_at?->format('d/m/Y'),
                ])
                ->toArray();
        }

        // Données artiste (pierceur)
        if ($piercer = $user->piercer) {
            $data['profil_pierceur'] = $piercer->only([
                'pseudo', 'bio', 'city', 'postal_code', 'email',
                'years_of_experience', 'minimum_price', 'siret',
                'current_plan',
            ]);
        }

        // Données client
        if ($client = $user->client) {
            $data['profil_client'] = $client->only([
                'first_name', 'last_name', 'pseudo', 'phone', 'email', 'birth_date',
            ]);

            $data['reservations_client'] = BookingRequest::where('client_id', $client->id)
                ->select(['id', 'status', 'description', 'estimated_total_price', 'created_at'])
                ->get()
                ->map(fn($b) => [
                    'id'          => $b->id,
                    'statut'      => $b->status,
                    'description' => $b->description,
                    'montant'     => $b->estimated_total_price,
                    'date'        => $b->created_at?->format('d/m/Y'),
                ])
                ->toArray();
        }

        // Notifications (100 dernières)
        $data['notifications'] = $user->notifications()
            ->latest()
            ->limit(100)
            ->get(['type', 'read_at', 'created_at'])
            ->toArray();

        // Consentements CGU
        $data['consentements'] = [
            'cgu_acceptees_le'       => $user->cgu_accepted_at?->format('d/m/Y H:i'),
            'version_cgu'            => $user->cgu_version_accepted,
            'confidentialite_le'     => $user->privacy_accepted_at?->format('d/m/Y H:i'),
            'version_confidentialite'=> $user->privacy_version_accepted,
        ];

        // Export vers le disque local (privé)
        $filename  = 'gdpr-exports/' . $user->id . '_' . now()->format('Y-m-d') . '.json';
        Storage::disk('local')->put(
            $filename,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return $filename;
    }
}
