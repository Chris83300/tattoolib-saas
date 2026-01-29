<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\Pierceur;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArtistController extends Controller
{
    public function show(string $slug)
    {
        // Chercher dans tattooers, pierceurs OU studios
        $artist = Tattooer::where('slug', $slug)->first()
                  ?? Pierceur::where('slug', $slug)->first()
                  ?? Studio::where('slug', $slug)->first();

        if (!$artist) {
            abort(404, 'Artiste non trouvé');
        }

        // Vérifier que l'artiste est actif
        if (!$artist->user) {
            abort(404, 'Artiste non trouvé');
        }

        // Si l'artiste est en attente de validation, afficher la page d'attente
        if ($artist->user->status === 'pending_verification') {
            // Permettre la prévisualisation si le paramètre ?preview=true est présent
            if (request()->get('preview') === 'true' || request()->query('preview') === 'true') {
                // Continuer vers le profil public en mode prévisualisation
                // Log pour débogage
                Log::info('Preview mode activated for artist: ' . $artist->slug);

                // Charger les relations nécessaires pour la prévisualisation
                $artist->load([
                    'user',
                    'workingHours',
                ]);
            } else {
                return view('artists.pending-validation', compact('artist'));
            }
        } elseif ($artist->user->status !== 'active') {
            abort(404, 'Profil non accessible');
        }

        // Charger relations (sans appointments pour éviter l'erreur)
        $artist->load([
            'user',
            'complianceRecords',
            'workingHours',
        ]);

        // Portfolio via Spatie
        $portfolioRealizations = $artist->getMedia('portfolio');
        $portfolioDrawings = $artist->getMedia('drawings');
        $portfolioBeforeAfter = $artist->getMedia('before_after');

        // Stats (valeurs par défaut car pas de données appointments)
        $stats = [
            'average_delay' => $this->calculateAverageDelay($artist),
            'min_price' => $artist->min_price ?? 150,
            'rating' => 0.0,
            'reviews_count' => 0,
            'platform_tattoos' => 0,
        ];

        // Horaires
        $workingHours = $this->formatWorkingHours($artist);
        $isOpenNow = $this->isOpenNow($artist);

        return view('artists.show', compact(
            'artist',
            'portfolioRealizations',
            'portfolioDrawings',
            'portfolioBeforeAfter',
            'stats',
            'workingHours',
            'isOpenNow'
        ));
    }

    private function calculateAverageDelay($artist)
    {
        // Logic pour calculer délai moyen (en semaines)
        // Basé sur prochaines disponibilités
        return '2-3'; // Exemple
    }

    private function formatWorkingHours($artist)
    {
        // Si studio artist, prendre horaires du studio
        $hours = $artist instanceof \App\Models\StudioArtist
            ? $artist->studio->workingHours
            : $artist->workingHours;

        // Formatter par jour
        return $hours->groupBy('day_of_week')->map(function($day) {
            $first = $day->first();
            return [
                'open' => $first->open_time,
                'close' => $first->close_time,
                'is_closed' => $first->is_closed,
            ];
        });
    }

    private function isOpenNow($artist)
    {
        $now = now();
        $dayOfWeek = $now->dayOfWeek; // 0 = dimanche, 1 = lundi, etc.

        $todayHours = $artist instanceof \App\Models\StudioArtist
            ? $artist->studio->workingHours()->where('day_of_week', $dayOfWeek)->first()
            : $artist->workingHours()->where('day_of_week', $dayOfWeek)->first();

        if (!$todayHours || $todayHours->is_closed) {
            return false;
        }

        $currentTime = $now->format('H:i');
        return $currentTime >= $todayHours->open_time && $currentTime <= $todayHours->close_time;
    }
}
