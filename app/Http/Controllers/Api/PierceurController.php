<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pierceur;
use App\Http\Resources\PierceurResource;
use App\Http\Resources\PierceurCollection;
use App\Http\Requests\Api\Pierceur\UpdatePierceurRequest;
use App\Http\Requests\Api\Pierceur\UpdateSpecializationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PierceurController extends Controller
{
    /**
     * Display a listing of piercers.
     */
    public function index(Request $request): PierceurCollection
    {
        $query = Pierceur::query()
            ->with(['user', 'studio'])
            ->active();

        // Filtrage par spécialisation
        if ($request->has('specialization')) {
            $query->bySpecialization($request->specialization);
        }

        // Recherche
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filtrage par ville
        if ($request->has('city')) {
            $query->byCity($request->city);
        }

        $piercers = $query->paginate(12);

        return new PierceurCollection($piercers);
    }

    /**
     * Display the specified pierceur.
     */
    public function show(Pierceur $pierceur): PierceurResource
    {
        $pierceur->load(['user', 'studio', 'media']);
        
        return new PierceurResource($pierceur);
    }

    /**
     * Update the specified pierceur.
     */
    public function update(UpdatePierceurRequest $request, Pierceur $pierceur): PierceurResource
    {
        $this->authorize('update', $pierceur);

        $pierceur->update($request->validated());

        return new PierceurResource($pierceur->fresh());
    }

    /**
     * Update pierceur specialization.
     */
    public function updateSpecialization(UpdateSpecializationRequest $request, Pierceur $pierceur): JsonResponse
    {
        $this->authorize('manageSpecialization', $pierceur);

        $pierceur->update($request->validated());

        return response()->json([
            'message' => 'Spécialisation mise à jour avec succès',
            'specialization' => $pierceur->specialization,
            'specialization_label' => $pierceur->specialization_label
        ]);
    }

    /**
     * Get pierceur statistics.
     */
    public function statistics(Pierceur $pierceur): JsonResponse
    {
        $this->authorize('view', $pierceur);

        $stats = [
            'appointments_this_month' => $pierceur->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->count(),
            'total_clients' => $pierceur->appointments()
                ->distinct('client_id')
                ->count('client_id'),
            'monthly_revenue' => $pierceur->appointments()
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->where('status', 'completed')
                ->sum('total_price'),
            'pending_requests' => $pierceur->bookingRequests()
                ->where('status', 'pending')
                ->count(),
            'portfolio_images_count' => $pierceur->getMedia('portfolio')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get pierceur availability.
     */
    public function availability(Pierceur $pierceur): JsonResponse
    {
        $this->authorize('view', $pierceur);

        $availabilities = $pierceur->availabilities()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        return response()->json($availabilities);
    }

    /**
     * Get pierceur working hours.
     */
    public function workingHours(Pierceur $pierceur): JsonResponse
    {
        $this->authorize('view', $pierceur);

        $workingHours = $pierceur->workingHours()
            ->orderBy('day_of_week')
            ->get();

        return response()->json($workingHours);
    }

    /**
     * Remove the specified pierceur.
     */
    public function destroy(Pierceur $pierceur): JsonResponse
    {
        $this->authorize('delete', $pierceur);

        $pierceur->delete();

        return response()->json([
            'message' => 'Profil pierceur supprimé avec succès'
        ]);
    }
}
