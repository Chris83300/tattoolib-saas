<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\Pierceur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function submitClient(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pseudo' => 'required|string|max:30|unique:users,pseudo', // pseudo
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => [
                'required',
                'date',
                'before:' . now()->subYears(16)->format('Y-m-d'), // min 16 ans
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

        // Créer user avec tous les champs
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'pseudo' => $validated['pseudo'], // pseudo du formulaire
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'status' => 'active',
        ]);

        // Créer profil client avec tous les champs
        $client = Client::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'pseudo' => $validated['pseudo'], // pseudo pour affichage
            'phone' => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'email' => $validated['email'],
        ]);

        // Login automatique
        Auth::login($user);

        // Redirection
        return redirect()->route('client.profile');
    }

    public function submitTattooer(Request $request)
    {
        // Debug pour voir si la méthode est appelée
        Log::info('submitTattooer appelé avec: ' . json_encode($request->all()));

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
            ]);

            Log::info('Validation passée: ' . json_encode($validated));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user avec tous les champs
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'] ?? $validated['first_name'] . ' ' . $validated['last_name'], // Prio: pseudo > nom
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'tattooer',
                'status' => 'pending_verification',
            ]);

            // Créer profil tattooer
            $tattooer = Tattooer::create([
                'user_id' => $user->id,
                'siret' => $validated['siret'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'], // nom complet pour affichage
                'slug' => Str::slug($validated['first_name'] . ' ' . $validated['last_name'] . '-' . $validated['city']),
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'current_plan' => 'free',
                'is_subscribed' => false,
                'has_compliance_badge' => false,
            ]);

            Log::info('Tattooer créé: ' . json_encode($tattooer));

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
                'error' => 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer. Erreur: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function submitPierceur(Request $request)
    {
        Log::info('submitPierceur appelé avec: ' . json_encode($request->all()));

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
            ]);

            Log::info('Validation pierceur passée: ' . json_encode($validated));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation pierceur: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user avec tous les champs
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'pseudo' => $validated['pseudo'] ?? $validated['first_name'] . ' ' . $validated['last_name'], // Prio: pseudo > nom
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'pierceur',
                'status' => 'pending_verification',
            ]);

            // Créer profil pierceur
            $pierceur = Pierceur::create([
                'user_id' => $user->id,
                'siret' => $validated['siret'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'], // nom complet pour affichage
                'slug' => Str::slug($validated['first_name'] . ' ' . $validated['last_name'] . '-' . $validated['city']),
                'specialization' => $validated['specialization'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'subscription_plan' => 'free',
                'is_subscribed' => false,
                'has_compliance_badge' => false,
            ]);

            Log::info('Pierceur créé avec succès: ' . $pierceur->id);

            // Login automatique
            Auth::login($user);

            // Redirection vers page "en attente validation"
            return redirect()->route('pierceur.pending-verification');

        } catch (\Exception $e) {
            Log::error('Erreur création pierceur: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.')->withInput();
        }
    }

    public function submitStudio(Request $request)
    {
        $validated = $request->validate([
            'studio_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255', // nom du propriétaire
            'email' => 'required|email|unique:users,email',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'payment_mode' => 'required|in:direct,centralized',
        ]);

        // Séparer first_name et last_name depuis full_name
        $names = explode(' ', $validated['full_name']);
        $firstName = $names[0] ?? '';
        $lastName = $names[1] ?? '';

        // Créer user (gérant) avec tous les champs
        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'pseudo' => $validated['full_name'], // Pseudo = nom complet
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role' => 'studio',
            'status' => 'pending_verification',
        ]);

        // Créer profil studio (temporairement simple)
        // TODO: Créer le modèle Studio

        // Login automatique
        Auth::login($user);

        // Redirection vers page "en attente validation"
        return redirect()->route('studio.pending-verification');
    }
}
