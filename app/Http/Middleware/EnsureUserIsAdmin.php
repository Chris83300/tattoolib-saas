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
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Pas admin → redirection selon rôle
        if (!Auth::user()->isAdmin()) {
            // Notification d'erreur (couleurs Ink&Pik)
            if ($request->expectsJson()) {
                abort(403, 'Accès réservé aux administrateurs');
            }

            Notification::make()
                ->title('🚫 Accès refusé')
                ->body('Cette section est réservée aux administrateurs de la plateforme.')
                ->danger()
                ->icon('heroicon-o-shield-exclamation')
                ->iconColor('danger') // Rouge #E63946
                ->send();

            $user = Auth::user();

            // Redirection artistes → Profil artiste
            if ($user->role === 'tattooer') {
                $tattooer = $user->tattooer;
                if ($tattooer) {
                    return redirect()->route('tattooer.profile', $tattooer->slug)
                        ->with('success', 'Bienvenue sur votre profil artiste !');
                }
                return redirect('/')->with('info', 'Configurez votre profil artiste.');
            }

            if ($user->role === 'pierceur') {
                $pierceur = $user->pierceur;
                if ($pierceur) {
                    return redirect()->route('pierceur.profile', $pierceur->slug)
                        ->with('success', 'Bienvenue sur votre profil artiste !');
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

        return $next($request);
    }
}
