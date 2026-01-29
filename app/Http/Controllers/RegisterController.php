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
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer user
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'status' => 'active',
        ]);

        // Créer profil client
        Client::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'] ?? null,
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'siret' => 'required|digits:14|unique:tattooers,siret',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Log::info('Validation passée: ' . json_encode($validated));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'tattooer',
                'status' => 'pending_verification',
            ]);

            // Créer profil tattooer
            $tattooer = Tattooer::create([
                'user_id' => $user->id,
                'siret' => $validated['siret'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'] . '-' . $validated['city']),
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
                'name' => 'required|string|max:255',
                'pseudo' => 'nullable|string|max:50|unique:users,pseudo',
                'email' => 'required|email|unique:users,email',
                'siret' => 'required|digits:14|unique:piercers,siret',
                'specialization' => 'required|in:pierceur,bodemodeur,pierceur_bodemodeur',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Log::info('Validation pierceur passée: ' . json_encode($validated));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation pierceur: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Créer user
            $user = User::create([
                'name' => $validated['name'],
                'pseudo' => $validated['pseudo'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pierceur',
                'status' => 'pending_verification',
            ]);

            // Créer profil pierceur
            $pierceur = Pierceur::create([
                'user_id' => $user->id,
                'siret' => $validated['siret'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'] . '-' . $validated['city']),
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'payment_mode' => 'required|in:direct,centralized',
        ]);

        // Créer user (gérant)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
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
