<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StudioArtistService
{
    public function __construct(
        private StripeService $stripeService
    ) {}

    /**
     * Créer un nouvel artiste rattaché au salon
     */
    public function createArtist(Studio $studio, array $data): StudioArtist
    {
        return DB::transaction(function () use ($studio, $data) {

            // 1. Créer le User
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'studio_id' => $studio->id,
                'is_studio_artist' => true,
            ]);

            // 2. Créer le StudioArtist
            $artist = StudioArtist::create([
                'studio_id' => $studio->id,
                'user_id' => $user->id,
                'artist_name' => $data['artist_name'],
                'slug' => Str::slug($data['artist_name']),
                'bio' => $data['bio'] ?? null,
                'specialties' => $data['specialties'] ?? [],
                'joined_at' => now(),
                'status' => 'active',
            ]);

            // 3. Créer Stripe Connect pour l'artiste
            $stripeAccount = $this->stripeService->createConnectAccount($user);
            $artist->update([
                'stripe_connect_account_id' => $stripeAccount->id
            ]);

            // 4. Mettre à jour abonnement salon
            $this->updateStudioSubscription($studio);

            return $artist;
        });
    }

    /**
     * Exporte les données en CSV
     *
     * @param array $data
     * @param string $filename
     * @return string Path du fichier
     */
    public function exportToCSV(array $data, string $filename = 'artist_export.csv'): string
    {
        $filepath = storage_path('app/exports/' . $filename);

        // Créer dossier si nécessaire
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $file = fopen($filepath, 'w');

        // Header CSV
        fputcsv($file, ['Type', 'ID', 'Date', 'Client', 'Details']);

        // Appointments
        foreach ($data['appointments'] as $appt) {
            fputcsv($file, [
                'Appointment',
                $appt['id'],
                $appt['date'],
                $appt['client_name'],
                $appt['tattoo_type'] . ' - ' . $appt['price'] . '€'
            ]);
        }

        // Traçabilité
        foreach ($data['traceability_records'] as $trace) {
            fputcsv($file, [
                'Traçabilité',
                $trace['id'],
                $trace['date'],
                $trace['client_name'],
                'Consentement: ' . $trace['consent_signed']
            ]);
        }

        fclose($file);

        return $filepath;
    }

    /**
     * Supprimer un artiste (soft delete + export données)
     */
    public function deleteArtist(Studio $studio, StudioArtist $artist): array
    {
        return DB::transaction(function () use ($studio, $artist) {

            // 1. Exporter TOUTES les données (LÉGAL)
            $exportData = $this->exportArtistData($artist);

            // 2. Sauvegarder export en CSV
            $csvPath = $this->exportToCSV(
                $exportData,
                "artist_{$artist->id}_export_" . now()->format('Y-m-d') . ".csv"
            );

            // 3. Soft delete artiste
            $artist->update([
                'status' => 'deleted',
                'left_at' => now(),
                'is_active' => false,
            ]);
            $artist->delete();

            // 4. Désactiver user
            $artist->user->update([
                'is_studio_artist' => false,
                'studio_id' => null,
            ]);

            // 5. Mettre à jour abonnement salon
            $this->updateStudioSubscription($studio);

            return [
                'export_data' => $exportData,
                'csv_path' => $csvPath,
                'artist_id' => $artist->id,
                'deleted_at' => now()->toDateTimeString(),
            ];
        });
    }

    /**
     * Mettre à jour l'abonnement Stripe du salon
     */
    private function updateStudioSubscription(Studio $studio): void
    {
        $subscription = $studio->studioSubscriptions()
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return;
        }

        $activeCount = $studio->activeArtists()->count();

        // Mettre à jour compteurs
        $subscription->current_artists = $activeCount;
        $subscription->updatePricing();

        // Mettre à jour Stripe
        // TODO: Implémenter Stripe subscription update
    }

    /**
     * Exporter toutes les données d'un artiste (traçabilité légale)
     *
     * @param StudioArtist $artist
     * @return array
     */
    public function exportArtistData(StudioArtist $artist): array
    {
        // 1. Récupérer tous les appointments (polymorphic)
        $appointments = $artist->appointments()
            ->with(['client', 'bookingRequest'])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->start_time->format('Y-m-d H:i'),
                    'client_name' => $appointment->client->user->name ?? 'N/A',
                    'client_email' => $appointment->client->user->email ?? 'N/A',
                    'tattoo_type' => $appointment->bookingRequest->tattoo_type ?? 'N/A',
                    'price' => $appointment->final_price ?? 0,
                    'status' => $appointment->status,
                ];
            })
            ->toArray();

        // 2. Récupérer traçabilité (CRITIQUE pour légalité)
        $traceability = [];

        // Vérifier si la table TraceabilityRecord existe
        if (class_exists('\App\Models\TraceabilityRecord')) {
            $traceability = \App\Models\TraceabilityRecord::where(function($query) use ($artist) {
                    // Système actuel avec user_id
                    if (Schema::hasColumn('traceability_records', 'user_id')) {
                        $query->orWhere('user_id', $artist->user->id);
                    }
                    // Si polymorphic (futur)
                    if (Schema::hasColumn('traceability_records', 'artist_type')) {
                        $query->orWhere('artist_type', 'App\Models\StudioArtist')
                              ->where('artist_id', $artist->id);
                    }
                })
                ->with('client')
                ->get()
                ->map(function ($record) {
                    return [
                        'id' => $record->id,
                        'date' => $record->created_at->format('Y-m-d'),
                        'client_name' => $record->client->user->name ?? 'N/A',
                        'tattoo_location' => $record->tattoo_location ?? 'N/A',
                        'consent_signed' => ($record->consent_signed ?? false) ? 'Oui' : 'Non',
                        'id_verified' => ($record->id_verified ?? false) ? 'Oui' : 'Non',
                    ];
                })
                ->toArray();
        }

        // 3. Récupérer care sheets
        $careSheets = [];
        if (class_exists('\App\Models\ClientCareSheet')) {
            $careSheets = \App\Models\ClientCareSheet::where(function($query) use ($artist) {
                    // Adapter selon ta structure
                    if (Schema::hasColumn('client_care_sheets', 'user_id')) {
                        $query->where('user_id', $artist->user->id);
                    }
                    if (Schema::hasColumn('client_care_sheets', 'artist_type')) {
                        $query->where('artist_type', 'App\Models\StudioArtist')
                              ->where('artist_id', $artist->id);
                    }
                })
                ->with('client')
                ->get()
                ->map(function ($sheet) {
                    return [
                        'client' => $sheet->client->user->name ?? 'N/A',
                        'date' => $sheet->created_at->format('Y-m-d'),
                        'instructions' => $sheet->instructions ?? '',
                    ];
                })
                ->toArray();
        }

        // 4. Informations artiste
        $artistInfo = [
            'name' => $artist->artist_name ?? $artist->user->name,
            'email' => $artist->user->email,
            'phone' => $artist->phone,
            'joined_at' => $artist->joined_at->format('Y-m-d'),
            'left_at' => now()->format('Y-m-d'),
            'studio' => $artist->studio->name,
            'total_appointments' => $artist->total_appointments,
            'total_revenue' => $artist->total_revenue,
        ];

        return [
            'artist_info' => $artistInfo,
            'appointments' => $appointments,
            'traceability_records' => $traceability,
            'care_sheets' => $careSheets,
            'stats' => [
                'total_appointments' => count($appointments),
                'total_traceability' => count($traceability),
                'total_revenue' => $artist->total_revenue,
            ],
            'exported_at' => now()->toDateTimeString(),
        ];
    }
}
