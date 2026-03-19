<?php

namespace App\Http\Controllers\Tattooer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TattooerPortfolioController extends ArtisanBaseController
{
    /**
     * Portfolio du tattooer
     */
    public function portfolio()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Récupérer les images du portfolio par collections
        $tattoos = $tattooer->getMedia('portfolio')
            ->sortByDesc('created_at')
            ->values();

        $drawings = $tattooer->getMedia('drawings')
            ->sortByDesc('created_at')
            ->values();

        $beforeAfter = $tattooer->getMedia('before_after')
            ->sortByDesc('created_at')
            ->values();

        // Debug temporaire pour voir les collections
        Log::info('Portfolio collections', [
            'tattoos_count' => $tattoos->count(),
            'drawings_count' => $drawings->count(),
            'before_after_count' => $beforeAfter->count(),
            'all_media_count' => $tattooer->media->count()
        ]);

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.portfolio', compact('tattooer', 'tattoos', 'drawings', 'beforeAfter', 'pendingCount', 'unreadCount'));
    }

    /**
     * Upload d'images pour le portfolio
     */
    public function portfolioUpload(Request $request)
    {
        try {
            $tattooer = $this->artisan();
            $collection = $request->input('collection', 'portfolio');

            // Validation
            $request->validate([
                'images' => 'required|array|max:10', // max 10 images par upload
                'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max par image
                'collection' => 'required|in:portfolio,drawings,before_after'
            ]);

            // Vérifier la limite d'images selon le plan
            $tattooer = $this->artisan();

            // Compter le TOTAL des images portfolio (toutes collections confondues)
            $totalPortfolioCount = $tattooer->getMedia('portfolio')->count() +
                                   $tattooer->getMedia('drawings')->count() +
                                   $tattooer->getMedia('before_after')->count();

            $newImagesCount = count($request->file('images'));

            // Définir la limite selon le plan (15 images AU TOTAL)
            $maxImages = $tattooer->isPro() ? PHP_INT_MAX : 15;

            if ($totalPortfolioCount + $newImagesCount > $maxImages) {
                return response()->json([
                    'success' => false,
                    'message' => "Limite de {$maxImages} images atteinte (plan Free). Passez au plan Pro pour un portfolio illimité."
                ], 422);
            }
            $uploadedCount = 0;
            foreach ($request->file('images') as $image) {
                $tattooer->addMedia($image)
                    ->withCustomProperties([
                        'type' => $collection === 'portfolio' ? 'tattoo' : $collection,
                        'uploaded_at' => now()->toISOString()
                    ])
                    ->toMediaCollection($collection);
                $uploadedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "{$uploadedCount} image(s) uploadée(s) avec succès !"
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur upload portfolio: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'upload. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Stockage des photos avant/après
     */
    public function portfolioBeforeAfterStore(Request $request)
    {
        try {
            $tattooer = $this->artisan();

            // Validation
            $request->validate([
                'before' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
                'after' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
                'description' => 'nullable|string|max:500'
            ]);

            // Vérifier la limite d'images
            $currentCount = $tattooer->getMedia('before_after')->count();
            $maxImages = 20;

            if ($currentCount + 2 > $maxImages) {
                return response()->json([
                    'success' => false,
                    'message' => "Limite d'images dépassée. Maximum {$maxImages} images autorisées."
                ], 422);
            }

            // Générer un pair_id pour lier avant/après
            $pairId = uniqid('pair_') . '_' . time();

            // Upload avant
            $beforeMedia = $tattooer->addMedia($request->file('before'))
                ->withCustomProperties([
                    'type' => 'before',
                    'pair_id' => $pairId,
                    'description' => $request->input('description'),
                    'uploaded_at' => now()->toISOString()
                ])
                ->toMediaCollection('before_after');

            // Upload après
            $afterMedia = $tattooer->addMedia($request->file('after'))
                ->withCustomProperties([
                    'type' => 'after',
                    'pair_id' => $pairId,
                    'description' => $request->input('description'),
                    'uploaded_at' => now()->toISOString()
                ])
                ->toMediaCollection('before_after');

            return response()->json([
                'success' => true,
                'message' => 'Photos avant/après uploadées avec succès !'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur upload avant/après: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'upload. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Supprimer un média du portfolio
     */
    public function portfolioDestroy($media)
    {
        try {
            $tattooer = $this->artisan();

            // Récupérer le média
            $mediaItem = $tattooer->media()->find($media);

            if (!$mediaItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image non trouvée'
                ], 404);
            }

            // Vérifier que le média appartient bien au tattooer
            if ($mediaItem->model_id != $tattooer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé'
                ], 403);
            }

            // Supprimer le média
            $mediaItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression média: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Supprimer une paire avant/après
     */
    public function portfolioBeforeAfterDestroy($beforeId, $afterId)
    {
        try {
            $tattooer = $this->artisan();

            // Récupérer les médias
            $beforeMedia = $tattooer->media()->find($beforeId);
            $afterMedia = $tattooer->media()->find($afterId);

            if (!$beforeMedia || !$afterMedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Images non trouvées'
                ], 404);
            }

            // Vérifier que les médias appartiennent bien au tattooer
            if ($beforeMedia->model_id != $tattooer->id || $afterMedia->model_id != $tattooer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé'
                ], 403);
            }

            // Supprimer les deux médias
            $beforeMedia->delete();
            $afterMedia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Paire avant/après supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression avant/après: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression. Veuillez réessayer.'
            ], 500);
        }
    }
}
