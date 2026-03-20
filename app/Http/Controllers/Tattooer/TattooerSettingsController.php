<?php

namespace App\Http\Controllers\Tattooer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TattooerSettingsController extends ArtisanBaseController
{
    /**
     * Page des paramètres du tattooer
     */
    public function settings()
    {
        $tattooer = $this->artisan();

        // Charger les relations nécessaires
        $tattooer->load(['media', 'user']);

        // Compteurs pour le layout
        ['pendingCount' => $pendingCount, 'unreadCount' => $unreadCount] = $this->getDashboardCounts($tattooer);

        return view('tattooer.settings', compact('tattooer', 'pendingCount', 'unreadCount'));
    }

    /**
     * Export RGPD — droit à la portabilité (Art. 20 RGPD)
     */
    public function exportGdpr(Request $request)
    {
        $user = $request->user();
        $path = app(\App\Services\GdprExportService::class)->exportUserData($user);

        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $path,
            'mes-donnees-inkpik-' . now()->format('Y-m-d') . '.json'
        );
    }

    /**
     * Mettre à jour les paramètres du tattooer
     */
    public function settingsUpdate(Request $request)
    {
        $tattooer = $this->artisan();

        // Valider les données du formulaire
        $validated = $request->validate([
            // Médias (fichiers)
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'banner' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',

            // Informations personnelles (user)
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pseudo' => 'nullable|string|max:255|unique:users,pseudo,' . $tattooer->user_id,
            'email' => 'required|email|unique:users,email,' . $tattooer->user_id,
            'phone' => 'nullable|string|max:20',

            // Informations professionnelles (tattooer)
            'studio_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',

            // Description et styles / types
            'bio' => 'nullable|string|max:2000',
            'styles' => 'nullable|array',
            'styles.*' => 'string|max:100',
            'custom_styles' => 'nullable|array',
            'custom_style_names' => 'nullable|array',
            'custom_style_names.*' => 'nullable|string|max:100',
            'piercing_types' => 'nullable|array',
            'piercing_types.*' => 'string|max:100',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'minimum_price' => 'nullable|numeric|min:0|max:10000',

            // Délai d'attente
            'wait_time_weeks_min' => 'nullable|integer|min:0|max:52',
            'wait_time_weeks_max' => 'nullable|integer|min:0|max:52',

            // Réseaux sociaux
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',

            // Paramètres de disponibilité
            'is_available' => 'boolean',
            'accepts_new_clients' => 'boolean',

            // Préférences de notification
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // Séparer les styles prédéfinis et personnalisés (tatoueur)
        $styles = array_values(array_filter(
            $validated['styles'] ?? [],
            fn($s) => $s !== 'Autres' && trim($s) !== ''
        ));
        $customStyleNames = array_values(
            array_filter($validated['custom_style_names'] ?? [], fn($s) => trim($s) !== '')
        );
        // Types de piercing (pierceur)
        $piercingTypes = array_values(array_filter(
            $validated['piercing_types'] ?? [],
            fn($t) => trim($t) !== ''
        ));

        // Mettre à jour l'utilisateur
        $tattooer->user->update([
            'first_name' => $validated['first_name'] ?? $tattooer->user->first_name,
            'last_name' => $validated['last_name'] ?? $tattooer->user->last_name,
            'pseudo' => $validated['pseudo'] ?? $tattooer->user->pseudo,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $tattooer->user->phone,
        ]);

        // Mettre à jour le tattooer
        $tattooer->update([
            'first_name' => $validated['first_name'] ?? $tattooer->user->first_name,
            'last_name' => $validated['last_name'] ?? $tattooer->user->last_name,
            'studio_name' => $validated['studio_name'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => !empty($validated['postal_code']) ? $validated['postal_code'] : $tattooer->postal_code,
            'country' => $validated['country'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'styles' => $tattooer->isPiercer() ? ($validated['styles'] ?? []) : $styles,
            'custom_styles' => $tattooer->isPiercer() ? ($validated['custom_style_names'] ?? []) : $customStyleNames,
            'piercing_types' => $tattooer->isPiercer() ? $piercingTypes : ($tattooer->piercing_types ?? []),
            'years_of_experience' => $validated['years_of_experience'] ?? null,
            'minimum_price' => $validated['minimum_price'] ?? null,
            'wait_time_weeks_min' => $validated['wait_time_weeks_min'] ?? null,
            'wait_time_weeks_max' => $validated['wait_time_weeks_max'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'website' => $validated['website'] ?? null,
            'is_available' => $validated['is_available'] ?? false,
            'accepts_new_clients' => $validated['accepts_new_clients'] ?? false,
            'email_notifications' => $validated['email_notifications'] ?? true,
            'sms_notifications' => $validated['sms_notifications'] ?? false,
        ]);

        // Gérer l'upload de l'avatar si présent
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($tattooer->user->hasMedia('avatar')) {
                $tattooer->user->clearMediaCollection('avatar');
            }

            $avatar = $request->file('avatar');
            $tattooer->user->addMedia($avatar)
                ->toMediaCollection('avatar');
        }

        // Gérer l'upload de la bannière si présente
        if ($request->hasFile('banner')) {
            // Supprimer l'ancienne bannière si elle existe
            if ($tattooer->hasMedia('banner')) {
                $tattooer->clearMediaCollection('banner');
            }

            $banner = $request->file('banner');
            $tattooer->addMedia($banner)
                ->toMediaCollection('banner');
        }

        return redirect()->route($this->routePrefix() . '.settings')
            ->with('success', 'Vos paramètres ont été mis à jour avec succès !');
    }

    /**
     * Mettre à jour les horaires du tattooer
     */
    public function settingsUpdateSchedule(Request $request)
    {
        try {
            $tattooer = $this->artisan();

            // Valider les données du formulaire
            $validated = $request->validate([
                'working_hours' => 'nullable|array',
                'working_hours.*.is_open' => 'nullable|string',
                'working_hours.*.open' => 'nullable|string',
                'working_hours.*.close' => 'nullable|string',
                'working_hours.*.break_start' => 'nullable|string',
                'working_hours.*.break_end' => 'nullable|string',
            ]);

            // Nettoyer les horaires existants
            $workingHours = [];

            if (isset($validated['working_hours'])) {
                foreach ($validated['working_hours'] as $day => $dayData) {
                    // Si le jour est fermé, ne garder que le statut fermé
                    if (!isset($dayData['is_open']) || $dayData['is_open'] !== '1') {
                        $workingHours[$day] = [
                            'open' => null,
                            'close' => null,
                            'break_start' => null,
                            'break_end' => null,
                        ];
                    } else {
                        // Si le jour est ouvert, garder les horaires
                        $workingHours[$day] = [
                            'open' => $dayData['open'] ?? null,
                            'close' => $dayData['close'] ?? null,
                            'break_start' => $dayData['break_start'] ?? null,
                            'break_end' => $dayData['break_end'] ?? null,
                        ];
                    }
                }
            }

            // Mettre à jour le tattooer
            $tattooer->update([
                'working_hours' => json_encode($workingHours),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Horaires mis à jour avec succès !'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour horaires: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour des horaires. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Mettre à jour les paramètres de soins aftercare
     */
    public function settingsAftercareUpdate(Request $request)
    {
        $tattooer = $this->artisan();

        // Vérifier que c'est un plan Pro
        if (!$tattooer->isPro()) {
            return redirect()->back()->with('error', 'Cette fonctionnalité est réservée aux plans Pro.');
        }

        $validated = $request->validate([
            'aftercare_sheet' => 'nullable|string|max:2000',
            'aftercare_reminder_2h' => 'boolean',
            'aftercare_reminder_7d' => 'boolean',
            'aftercare_reminder_14d' => 'boolean',
        ]);

        // Convertir les checkboxes en booléens
        $validated['aftercare_reminder_2h'] = $request->has('aftercare_reminder_2h');
        $validated['aftercare_reminder_7d'] = $request->has('aftercare_reminder_7d');
        $validated['aftercare_reminder_14d'] = $request->has('aftercare_reminder_14d');

        $tattooer->update($validated);

        return redirect()->back()->with('success', 'Fiche de soins mise à jour avec succès.');
    }

    /**
     * Mettre à jour la grille tarifaire (pierceur Pro uniquement)
     */
    public function settingsPricingUpdate(Request $request)
    {
        $tattooer = $this->artisan();

        $validated = $request->validate([
            'pricing_grid' => 'nullable|array',
            'pricing_grid.*.type' => 'required|string|max:100',
            'pricing_grid.*.price' => 'required|numeric|min:0',
            'custom_pricing_note' => 'nullable|string|max:500',
        ]);

        $tattooer->update([
            'pricing_grid' => $validated['pricing_grid'] ?? [],
            'custom_pricing_note' => $validated['custom_pricing_note'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Grille tarifaire mise à jour avec succès.');
    }

    /**
     * Mettre à jour le mot de passe
     * Note: Cette méthode n'existe pas dans le TattooerController original,
     * mais est référencée dans les routes. Elle est définie ici pour compatibilité.
     */
    public function settingsUpdatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Mettre à jour les heures de travail
     * Note: Cette méthode n'existe pas dans le TattooerController original,
     * mais est référencée dans les routes. Elle est définie ici pour compatibilité.
     */
    public function updateHours(Request $request)
    {
        return $this->settingsUpdateSchedule($request);
    }
}
