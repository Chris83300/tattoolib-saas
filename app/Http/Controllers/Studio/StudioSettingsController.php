<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Http\Requests\Studio\UpdateStudioSettingsRequest;
use Illuminate\Support\Str;

class StudioSettingsController extends Controller
{
    /**
     * Récupère le studio que l'utilisateur connecté POSSÈDE.
     */
    private function studio(): Studio
    {
        $studio = auth()->user()->studio;
        abort_unless($studio, 403, 'Profil studio non trouvé');
        return $studio;
    }

    public function settings()
    {
        return view('studio.settings', [
            'studio' => $this->studio(),
        ]);
    }

    public function updateSettings(UpdateStudioSettingsRequest $request)
    {
        $studio = $this->studio();

        $validated = $request->validated();

        // En mode studio_managed, pas de commission artiste directe
        if ($validated['payment_mode'] === 'studio_managed') {
            $validated['artist_commission_rate'] = null;
        }

        $validated['slug'] = Str::slug($validated['name']);
        $studio->update($validated);

        if ($request->hasFile('logo')) {
            $studio->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
        if ($request->hasFile('cover')) {
            $studio->addMediaFromRequest('cover')->toMediaCollection('cover');
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $studio->addMedia($photo)->toMediaCollection('photos');
            }
        }

        return back()->with('success', 'Paramètres mis à jour');
    }
}
