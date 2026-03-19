<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Pas connecté → login
        if (!Auth::check()) {
            // Livewire/AJAX : retourne 401
            if ($request->expectsJson() || $request->is('livewire/*')) {
                abort(401, 'Non authentifié');
            }

            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Pas admin → bloquer
        if (!Auth::user()->isAdmin()) {

            // ✅ CRITIQUE : Autoriser les requêtes Livewire/AJAX si déjà dans /admin
            // (sinon Filament ne peut pas charger les modals/forms)
            if ($request->is('admin/*') && ($request->expectsJson() || $request->is('livewire/*'))) {
                abort(403, 'Accès réservé aux administrateurs');
            }

            // Notification uniquement pour les requêtes normales (non-AJAX)
            if (!$request->expectsJson() && !$request->is('livewire/*')) {
                Notification::make()
                    ->title('🚫 Accès refusé')
                    ->body('Cette section est réservée aux administrateurs de la plateforme.')
                    ->danger()
                    ->icon('heroicon-o-shield-exclamation')
                    ->iconColor('danger')
                    ->send();
            }

            $user = Auth::user();

            // Redirection artistes → Profil artiste
            if ($user->role === 'tattooer') {
                $tattooer = $user->tattooer;
                if ($tattooer) {
                    return redirect()->route('tattooer.dashboard')
                        ->with('success', 'Bienvenue sur votre espace artiste ! 🎨');
                }
                return redirect('/')->with('info', 'Configurez votre profil artiste.');
            }

            if ($user->role === 'pierceur') {
                $piercer = $user->piercer;
                if ($piercer) {
                    return redirect()->route('pierceur.dashboard')
                        ->with('success', 'Bienvenue sur votre espace artiste ! 💉');
                }
                return redirect('/')->with('info', 'Configurez votre profil artiste.');
            }

            // Clients → Homepage
            if ($user->role === 'client') {
                return redirect('/')->with('info', 'Explorez nos artistes talentueux !');
            }

            // Fallback
            return redirect('/');
        }

        // 2FA obligatoire pour les admins (production uniquement)
        $adminUser = Auth::user();
        if (
            app()->environment('production')
            && $adminUser->isAdmin()
            && empty($adminUser->two_factor_confirmed_at)
        ) {
            if ($request->expectsJson() || $request->is('livewire/*')) {
                abort(403, 'Authentification à deux facteurs requise pour les administrateurs');
            }
            return redirect()->route('two-factor.show')
                ->with('warning', 'Vous devez activer l\'authentification à deux facteurs pour accéder au panneau d\'administration.');
        }

        return $next($request);
    }
}
