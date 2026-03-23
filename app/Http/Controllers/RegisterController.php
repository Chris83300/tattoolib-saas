<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use App\Services\BetaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function submitClient(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pseudo' => 'required|string|max:30|unique:users,pseudo',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => [
                'required',
                'date',
                'before:' . now()->subYears(16)->format('Y-m-d'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{8,}$/' // 1 maj, 1 min, 1 chiffre, 1 spécial
            ],
        ], [
            'birth_date.before' => 'Vous devez avoir au moins 16 ans pour vous inscrire. Revenez quand vous aurez 16 ans ! 😉',
            'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&)',
            'password.confirmed' => 'Fait un effort, tu viens de le taper juste avant ! 😄',
        ]);

        try {
            // Utiliser une transaction pour tout créer ou tout annuler
            DB::beginTransaction();

            // Créer user avec tous les champs
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'password' => Hash::make($validated['password']),
                'role' => 'client',
                'status' => 'active',
            ]);

            // Créer profil client
            $client = Client::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'],
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'email' => $validated['email'],
            ]);

            Log::info('Client créé: ' . json_encode($client));

            // Traçage du consentement RGPD (Art. 7 + Art. 9)
            $user->update([
                'cgu_accepted_at'          => now(),
                'cgu_version_accepted'     => config('app.cgu_version', '1.0'),
                'privacy_accepted_at'      => now(),
                'privacy_version_accepted' => config('app.privacy_version', '1.0'),
                'consent_ip'               => $request->ip(),
            ]);

            // Valider la transaction
            DB::commit();

            // Login automatique
            Auth::login($user);

            // Message de succès
            session()->flash('success', 'Votre compte client a été créé avec succès ! Bienvenue sur Ink&Pik ! 🎨');

            // Redirection
            return redirect()->route('client.profile');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollback();

            // Logger l'erreur pour debug
            Log::error('Erreur création client: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // En cas d'erreur, supprimer le user créé et retourner une erreur
            if (isset($user)) {
                $user->delete();
            }

            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.',
            ])->withInput();
        }
    }

    public function submitTattooer(Request $request)
    {
        // Debug pour voir si la méthode est appelée
        if (app()->environment('local')) {
            Log::debug('submitTattooer appelé', ['fields' => array_keys($request->except(['password', 'password_confirmation'])), 'ip' => $request->ip()]);
        }

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255', // prénom
                'last_name' => 'required|string|max:255', // nom
                'pseudo' => 'nullable|string|max:50|unique:users,pseudo', // pseudo optionnel
                'email' => 'required|email|unique:users,email',
                'siret' => 'required|numeric|digits:14|unique:tattooers,siret',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'phone' => 'nullable|string|max:20',
                'plan' => 'required|in:starter,pro', // Ajout du plan
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{8,}$/' // 1 maj, 1 min, 1 chiffre, 1 spécial
                ],
            ], [
                'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&)',
                'password.confirmed' => 'Fait un effort, tu viens de le taper juste avant ! 😄',
                'siret.numeric' => 'Le SIRET doit contenir uniquement des chiffres.',
                'siret.digits' => 'Le SIRET doit contenir exactement 14 chiffres.',
                'siret.unique' => 'Ce numéro SIRET est déjà utilisé. Veuillez en utiliser un autre.',
                'plan.in' => 'Le plan sélectionné n\'est pas valide.',
            ]);


        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user avec tous les champs
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'], // Nom complet
                'pseudo' => $validated['pseudo'] ?? $validated['first_name'] . ' ' . $validated['last_name'], // Prio: pseudo > nom
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'tattooer',
                'status' => 'pending_verification',
            ]);

            $isBeta = $request->has('beta');

            // Créer profil tattooer
            $tattooer = Tattooer::create([
                'user_id' => $user->id,
                'siret' => $validated['siret'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'] ?? $validated['first_name'] . ' ' . $validated['last_name'], // Prio: pseudo > nom
                'slug' => Str::slug($validated['first_name'] . ' ' . $validated['last_name'] . '-' . $validated['city']),
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'current_plan' => $validated['plan'], // Utiliser le plan sélectionné
                'is_subscribed' => false,
                'has_compliance_badge' => false,
                // Bêta-testeur : pas de trial standard (mois gratuit via beta_expires_at)
                'trial_ends_at' => $isBeta ? null : (
                    $validated['plan'] === 'starter'
                        ? now()->addDays(\App\Enums\SubscriptionPlan::STARTER->trialDays())
                        : now()->addDays(\App\Enums\SubscriptionPlan::PRO->trialDays())
                ),
            ]);

            // Flow bêta : marquer l'utilisateur + notification de bienvenue
            if ($isBeta) {
                $user->update([
                    'is_beta_tester'     => true,
                    'beta_registered_at' => now(),
                    'beta_expires_at'    => now()->addMonth(),
                    'beta_coupon_used'   => BetaService::COUPON_FREE_MONTH,
                ]);
                $user->notify(new \App\Notifications\WelcomeBetaTesterNotification());
                Log::info('[Beta] Tattooer bêta-testeur inscrit', ['user_id' => $user->id, 'beta_expires_at' => now()->addMonth()->toDateString()]);
            }

            Log::info('Tattooer créé: ' . json_encode($tattooer));
            Log::info('Données insérées - first_name: ' . ($tattooer->first_name ?? 'NULL') . ', last_name: ' . ($tattooer->last_name ?? 'NULL') . ', pseudo: ' . ($tattooer->pseudo ?? 'NULL'));

            // Traçage du consentement RGPD
            $user->update([
                'cgu_accepted_at'          => now(),
                'cgu_version_accepted'     => config('app.cgu_version', '1.0'),
                'privacy_accepted_at'      => now(),
                'privacy_version_accepted' => config('app.privacy_version', '1.0'),
                'consent_ip'               => $request->ip(),
            ]);

            // Login automatique
            Auth::login($user);

            // Message de succès
            session()->flash('success', 'Votre compte tatoueur a été créé avec succès ! Votre SIRET a été enregistré et sera vérifié par notre équipe.');

            // Redirection vers page "en attente validation"
            return redirect()->route('tattooer.pending-verification');

        } catch (\Exception $e) {
            // Logger l'erreur pour debug
            Log::error('Erreur création tattooer: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // En cas d'erreur, supprimer le user créé et retourner une erreur
            if (isset($user)) {
                $user->delete();
            }

            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.',
            ])->withInput();
        }
    }

    public function submitPiercer(Request $request)
    {
        if (app()->environment('local')) {
            Log::debug('submitPiercer appelé', ['fields' => array_keys($request->except(['password', 'password_confirmation'])), 'ip' => $request->ip()]);
        }

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255', // prénom
                'last_name' => 'required|string|max:255', // nom
                'pseudo' => 'nullable|string|max:50|unique:users,pseudo', // pseudo optionnel
                'email' => 'required|email|unique:users,email',
                'siret' => 'required|numeric|digits:14|unique:piercers,siret',
                'specialization' => 'required|in:pierceur,bodemodeur,pierceur_bodemodeur',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'phone' => 'nullable|string|max:20',
                'plan' => 'required|in:starter,pro', // Ajout du plan
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{8,}$/' // 1 maj, 1 min, 1 chiffre, 1 spécial
                ],
            ], [
                'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&)',
                'password.confirmed' => 'Fait un effort, tu viens de le taper juste avant ! 😄',
                'siret.numeric' => 'Le SIRET doit contenir uniquement des chiffres.',
                'siret.digits' => 'Le SIRET doit contenir exactement 14 chiffres.',
                'siret.unique' => 'Ce numéro SIRET est déjà utilisé. Veuillez en utiliser un autre.',
                'plan.in' => 'Le plan sélectionné n\'est pas valide.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user avec tous les champs
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'], // Nom complet
                'pseudo' => $validated['pseudo'] ?? $validated['first_name'] . ' ' . $validated['last_name'], // Prio: pseudo > nom
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'pierceur',
                'status' => 'pending_verification',
            ]);

            $isBeta = $request->has('beta');

            // Créer profil Piercer
            $piercer = Piercer::create([
                'user_id' => $user->id,
                'siret' => $validated['siret'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'], // nom complet pour affichage (compatibilité)
                'pseudo' => $validated['pseudo'] ?? $validated['first_name'] . ' ' . $validated['last_name'], // Prio: pseudo > nom
                'slug' => Str::slug($validated['first_name'] . ' ' . $validated['last_name'] . '-' . $validated['city']),
                'specialization' => $validated['specialization'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'current_plan' => $validated['plan'],
                'is_subscribed' => false,
                'has_compliance_badge' => false,
                // Bêta-testeur : pas de trial standard (mois gratuit via beta_expires_at)
                'trial_ends_at' => $isBeta ? null : now()->addDays(14),
            ]);

            Log::info('Piercer créé: ' . json_encode($piercer));

            // Flow bêta : marquer l'utilisateur + notification de bienvenue
            if ($isBeta) {
                $user->update([
                    'is_beta_tester'     => true,
                    'beta_registered_at' => now(),
                    'beta_expires_at'    => now()->addMonth(),
                    'beta_coupon_used'   => BetaService::COUPON_FREE_MONTH,
                ]);
                $user->notify(new \App\Notifications\WelcomeBetaTesterNotification());
                Log::info('[Beta] Pierceur bêta-testeur inscrit', ['user_id' => $user->id, 'beta_expires_at' => now()->addMonth()->toDateString()]);
            }

            // Traçage du consentement RGPD
            $user->update([
                'cgu_accepted_at'          => now(),
                'cgu_version_accepted'     => config('app.cgu_version', '1.0'),
                'privacy_accepted_at'      => now(),
                'privacy_version_accepted' => config('app.privacy_version', '1.0'),
                'consent_ip'               => $request->ip(),
            ]);

            // Login automatique
            Auth::login($user);

            // Message de succès
            session()->flash('success', 'Votre compte Piercer a été créé avec succès ! Votre SIRET a été enregistré et sera vérifié par notre équipe.');

            // Redirection vers page "en attente validation"
            return redirect()->route('pierceur.pending-verification');

        } catch (\Exception $e) {
            // Logger l'erreur pour debug
            Log::error('Erreur création Piercer: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // En cas d'erreur, supprimer le user créé et retourner une erreur
            if (isset($user)) {
                $user->delete();
            }

            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.',
            ])->withInput();
        }
    }

    public function submitStudio(Request $request)
    {
        if (app()->environment('local')) {
            Log::debug('submitStudio appelé', ['fields' => array_keys($request->except(['password', 'password_confirmation'])), 'ip' => $request->ip()]);
        }

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255', // prénom du gérant
                'last_name' => 'required|string|max:255', // nom du gérant
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{8,}$/' // 1 maj, 1 min, 1 chiffre, 1 spécial
                ],
                'studio_name' => 'required|string|max:255',
                'siret' => 'required|numeric|digits:14|unique:studios,siret',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'phone' => 'nullable|string|max:20',
                'payment_mode' => 'required|in:artist_direct,studio_managed',
            ], [
                'password.regex' => 'Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&)',
                'password.confirmed' => 'Fait un effort, tu viens de le taper juste avant ! 😄',
                'siret.numeric' => 'Le SIRET doit contenir uniquement des chiffres.',
                'siret.digits' => 'Le SIRET doit contenir exactement 14 chiffres.',
                'siret.unique' => 'Ce numéro SIRET est déjà utilisé. Veuillez en utiliser un autre.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user avec tous les champs
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'], // Nom complet
                'pseudo' => $validated['first_name'] . ' ' . $validated['last_name'], // Prio: nom > pseudo
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'studio',
                'status' => 'pending_verification',
                'is_studio_owner' => true,
            ]);

            // Créer studio
            $studio = Studio::create([
                'user_id' => $user->id,
                'name' => $validated['studio_name'],
                'siret' => $validated['siret'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'payment_mode' => $validated['payment_mode'],
                'is_active' => false, // doit être activé manuellement par admin
                'trial_ends_at' => now()->addDays(14),
            ]);

            Log::info('Studio créé: ' . json_encode($studio));

            // Traçage du consentement RGPD
            $user->update([
                'cgu_accepted_at'          => now(),
                'cgu_version_accepted'     => config('app.cgu_version', '1.0'),
                'privacy_accepted_at'      => now(),
                'privacy_version_accepted' => config('app.privacy_version', '1.0'),
                'consent_ip'               => $request->ip(),
            ]);

            // Login automatique
            Auth::login($user);

            // Message de succès
            session()->flash('success', 'Votre studio a été créé avec succès ! Votre SIRET a été enregistré et sera vérifié par notre équipe.');

            // Redirection vers page "en attente validation"
            return redirect()->route('studio.pending-verification');

        } catch (\Exception $e) {
            // Logger l'erreur pour debug
            Log::error('Erreur création studio: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // En cas d'erreur, supprimer le user créé et retourner une erreur
            if (isset($user)) {
                $user->delete();
            }

            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la création de votre studio. Veuillez réessayer.',
            ])->withInput();
        }
    }
}
