<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tattooer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TattooerController extends Controller
{
    /**
     * Liste et recherche de tatoueurs (PUBLIC)
     */
    public function index(Request $request)
    {
        $query = Tattooer::query()
            ->with(['user:id,name', 'studio:id,name,city', 'media'])
            ->verified()
            ->active();

        // Recherche textuelle
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filtrage par ville
        if ($request->has('city')) {
            $query->byCity($request->city);
        }

        // Filtrage par studio
        if ($request->has('studio_id')) {
            $query->where('studio_id', $request->studio_id);
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = ['created_at', 'name', 'city', 'weekday_wait_days'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $tattooers = $query->paginate($request->input('per_page', 12));

        // Ajouter les URLs du portfolio
        $tattooers->getCollection()->transform(function ($tattooer) {
            $tattooer->portfolio_images = $tattooer->getMedia('portfolio')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                ];
            });

            $tattooer->avatar_url = $tattooer->getFirstMediaUrl('avatar');

            return $tattooer;
        });

        return response()->json($tattooers);
    }

    /**
     * Afficher le profil public d'un tatoueur (PUBLIC)
     */
    public function show($id)
    {
        $tattooer = Tattooer::with([
            'user:id,name,email',
            'studio',
            'media',
            'workingHours'
        ])->findOrFail($id);

        // Portfolio images
        $tattooer->portfolio_images = $tattooer->getMedia('portfolio')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'name' => $media->name,
            ];
        });

        $tattooer->avatar_url = $tattooer->getFirstMediaUrl('avatar');

        return response()->json($tattooer);
    }

    /**
     * Récupérer le portfolio d'un tatoueur (PUBLIC)
     */
    public function portfolio($id)
    {
        $tattooer = Tattooer::findOrFail($id);

        $portfolio = $tattooer->getMedia('portfolio')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'name' => $media->name,
                'size' => $media->size,
                'created_at' => $media->created_at,
            ];
        });

        return response()->json([
            'tattooer_id' => $tattooer->id,
            'tattooer_name' => $tattooer->name,
            'portfolio' => $portfolio,
            'total' => $portfolio->count(),
        ]);
    }

    /**
     * Upload une image au portfolio (PROTECTED - Tatoueur uniquement)
     */
    public function uploadPortfolioImage(Request $request, Tattooer $tattooer)
    {
        // Vérifier que l'utilisateur est bien le propriétaire
        if (!$request->user()->isTattooer() || $request->user()->tattooer->id !== $tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
            'name' => 'nullable|string|max:255',
        ]);

        $media = $tattooer->addMedia($request->file('image'))
            ->usingName($request->name ?? 'Portfolio image')
            ->toMediaCollection('portfolio');

        return response()->json([
            'message' => 'Image ajoutée au portfolio',
            'image' => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ],
        ], 201);
    }

    /**
     * Supprimer une image du portfolio (PROTECTED)
     */
    public function deletePortfolioImage(Request $request, Tattooer $tattooer, $mediaId)
    {
        if (!$request->user()->isTattooer() || $request->user()->tattooer->id !== $tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $media = $tattooer->media()->where('id', $mediaId)->firstOrFail();
        $media->delete();

        return response()->json(['message' => 'Image supprimée']);
    }

    /**
     * Récupérer les créneaux disponibles (PUBLIC)
     */
    public function availability($id)
    {
        $tattooer = Tattooer::with('workingHours')->findOrFail($id);

        $weekDays = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday'
        ];

        $availableDates = [];
        $now = now();

        // Générer les 30 prochains jours
        for ($i = 0; $i < 30; $i++) {
            $date = $now->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;

            $workingHour = $tattooer->workingHours->firstWhere('day_of_week', $dayOfWeek);

            if ($workingHour && $workingHour->is_open) {
                $availableDates[] = [
                    'date' => $date->toDateString(),
                    'day_name' => $weekDays[$dayOfWeek],
                    'opening_time' => $workingHour->opening_time,
                    'closing_time' => $workingHour->closing_time,
                    'has_break' => $workingHour->is_break,
                    'break_start' => $workingHour->break_start,
                    'break_end' => $workingHour->break_end,
                ];
            }
        }

        // Horaires hebdomadaires
        $formattedWorkingHours = [];
        foreach ($weekDays as $dayIndex => $dayName) {
            $workingHour = $tattooer->workingHours->firstWhere('day_of_week', $dayIndex);

            if ($workingHour && $workingHour->is_open) {
                $formattedWorkingHours[$dayName] = [
                    'is_open' => true,
                    'opening_time' => $workingHour->opening_time,
                    'closing_time' => $workingHour->closing_time,
                    'has_break' => $workingHour->is_break,
                    'break_start' => $workingHour->break_start,
                    'break_end' => $workingHour->break_end,
                ];
            } else {
                $formattedWorkingHours[$dayName] = [
                    'is_open' => false,
                ];
            }
        }

        return response()->json([
            'tattooer_id' => $tattooer->id,
            'available_dates' => $availableDates,
            'working_hours' => $formattedWorkingHours,
            'weekday_wait_days' => $tattooer->weekday_wait_days,
            'weekend_wait_days' => $tattooer->weekend_wait_days,
        ]);
    }

    /**
     * Récupérer tous les horaires (PROTECTED)
     */
    public function getWorkingHours(Request $request, Tattooer $tattooer)
    {
        if (!$request->user()->isTattooer() || $request->user()->tattooer->id !== $tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $workingHours = $tattooer->workingHours()->get();

        return response()->json($workingHours);
    }

    /**
     * Mettre à jour tous les horaires (PROTECTED)
     */
    public function updateWorkingHours(Request $request, Tattooer $tattooer)
    {
        if (!$request->user()->isTattooer() || $request->user()->tattooer->id !== $tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            '*.day_of_week' => 'required|integer|between:0,6',
            '*.is_open' => 'required|boolean',
            '*.opening_time' => 'required_if:*.is_open,true|date_format:H:i|nullable',
            '*.closing_time' => 'required_if:*.is_open,true|date_format:H:i|after:*.opening_time|nullable',
            '*.is_break' => 'boolean',
            '*.break_start' => 'nullable|date_format:H:i',
            '*.break_end' => 'nullable|date_format:H:i',
        ]);

        // Supprimer les anciens horaires
        $tattooer->workingHours()->delete();

        // Créer les nouveaux
        foreach ($validated as $day) {
            $tattooer->workingHours()->create($day);
        }

        return response()->json([
            'message' => 'Horaires mis à jour avec succès',
            'working_hours' => $tattooer->workingHours()->get(),
        ]);
    }

    /**
     * Mettre à jour un jour spécifique (PROTECTED)
     */
    public function updateDayWorkingHours(Request $request, Tattooer $tattooer, $day)
    {
        if (!$request->user()->isTattooer() || $request->user()->tattooer->id !== $tattooer->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'is_open' => 'required|boolean',
            'opening_time' => 'required_if:is_open,true|date_format:H:i|nullable',
            'closing_time' => 'required_if:is_open,true|date_format:H:i|after:opening_time|nullable',
            'is_break' => 'boolean',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
        ]);

        $workingHour = $tattooer->workingHours()->updateOrCreate(
            ['day_of_week' => $day],
            $validated
        );

        return response()->json([
            'message' => 'Horaire mis à jour',
            'data' => $workingHour,
        ]);
    }
}
