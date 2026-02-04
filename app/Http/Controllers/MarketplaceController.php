<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\Pierceur;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    /**
     * Page marketplace
     */
    public function index(): View
    {
        return view('marketplace.index');
    }

    /**
     * Profil public artiste
     */
    public function show(string $slug): View
    {
        // Chercher dans tattooers
        $artist = Tattooer::where('slug', $slug)
            ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
            ->with('user')
            ->first();

        $type = 'tattooer';

        // Si pas trouvé, chercher dans pierceurs
        if (!$artist) {
            $artist = Pierceur::where('slug', $slug)
                ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
                ->with('user')
                ->first();

            $type = 'pierceur';
        }

        abort_if(!$artist, 404, 'Artiste non trouvé');

        // Charger les relations
        $artist->load(['media']);

        // Stats
        $stats = [
            'rating' => 0, // Temporaire, à calculer quand les reviews existeront
            'reviews_count' => 0, // Temporaire
            'appointments_count' => $artist->appointments()->whereIn('status', ['completed', 'confirmed'])->count(),
            'years_experience' => max(1, now()->diffInYears($artist->created_at)),
        ];

        // Portfolio
        $portfolio = $artist->getMedia('portfolio');

        return view('marketplace.show', compact('artist', 'type', 'stats', 'portfolio'));
    }
}
