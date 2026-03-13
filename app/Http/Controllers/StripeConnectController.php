<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\Piercer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeConnectController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // TATTOOER / PIERCER INDÉPENDANT (routes avec {artist} ID)
    // ─────────────────────────────────────────────────────────

    /**
     * Retour après onboarding Stripe Connect pour un artiste indépendant.
     * URL : GET /stripe/connect/return/{artist}
     * Stripe redirige ici après que l'artiste a complété le formulaire.
     */
    public function returnFromOnboarding(Request $request, int $artist)
    {
        $artisan = $this->resolveArtist($request, $artist);

        if (!$artisan) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil artiste introuvable ou non autorisé.');
        }

        if (!$artisan->stripe_connect_account_id) {
            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('error', 'Aucun compte Stripe Connect trouvé. Veuillez recommencer.');
        }

        // Vérifier le statut du compte côté Stripe
        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $account = $stripe->accounts->retrieve($artisan->stripe_connect_account_id);

            if ($account->charges_enabled) {
                // Onboarding complet → activer
                $artisan->update(['stripe_onboarding_complete' => true]);
                $artisan->activateStripeConnect();

                Log::info('Stripe Connect onboarding complété', [
                    'artist_id'   => $artisan->id,
                    'artist_type' => get_class($artisan),
                    'account_id'  => $artisan->stripe_connect_account_id,
                ]);

                return redirect()->route($artisan->routePrefix() . '.settings')
                    ->with('success', '✅ Votre compte Stripe Connect est actif ! Vous pouvez recevoir des paiements.');
            } else {
                // Onboarding incomplet
                $requirements = $account->requirements?->currently_due ?? [];
                $message = count($requirements) > 0
                    ? '⏳ Votre compte est en cours de vérification. Stripe vous enverra un email si des documents sont nécessaires.'
                    : '⏳ Votre compte Stripe est en cours d\'activation. Revenez vérifier dans quelques heures.';

                return redirect()->route($artisan->routePrefix() . '.settings')
                    ->with('info', $message);
            }
        } catch (\Exception $e) {
            Log::error('Erreur vérification statut Stripe Connect', [
                'artist_id' => $artisan->id,
                'error'     => $e->getMessage(),
            ]);

            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('info', '⏳ Configuration en cours. Votre compte Stripe sera vérifié sous peu.');
        }
    }

    /**
     * Rafraîchir le lien d'onboarding Stripe Connect (lien expiré ou incomplet).
     * URL : GET /stripe/connect/refresh/{artist}
     * Stripe redirige ici si le lien a expiré ou si l'artiste quitte sans terminer.
     */
    public function refreshOnboarding(Request $request, int $artist)
    {
        $artisan = $this->resolveArtist($request, $artist);

        if (!$artisan) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil artiste introuvable ou non autorisé.');
        }

        try {
            $newLink = $artisan->generateStripeConnectLink();
            return redirect($newLink);
        } catch (\Exception $e) {
            Log::error('Erreur régénération lien Stripe Connect', [
                'artist_id' => $artisan->id,
                'error'     => $e->getMessage(),
            ]);

            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('error', 'Impossible de générer le lien Stripe. Veuillez réessayer.');
        }
    }

    // ─────────────────────────────────────────────────────────
    // STUDIO ARTIST (routes sans paramètre, depuis session auth)
    // ─────────────────────────────────────────────────────────

    /**
     * Retour après onboarding Stripe Connect pour un artiste de studio.
     * URL : GET /studio/artist/stripe/return
     */
    public function studioArtistReturn(Request $request)
    {
        $user = $request->user();
        $artisan = $user?->artisan();

        if (!$artisan) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil artiste introuvable.');
        }

        if (!$artisan->stripe_connect_account_id) {
            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('error', 'Aucun compte Stripe Connect trouvé. Veuillez recommencer.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $account = $stripe->accounts->retrieve($artisan->stripe_connect_account_id);

            if ($account->charges_enabled) {
                $artisan->update(['stripe_onboarding_complete' => true]);
                $artisan->activateStripeConnect();

                return redirect()->route($artisan->routePrefix() . '.settings')
                    ->with('success', '✅ Compte Stripe Connect actif ! Vous pouvez recevoir des paiements.');
            }

            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('info', '⏳ Vérification en cours. Vous recevrez un email de Stripe si nécessaire.');
        } catch (\Exception $e) {
            Log::error('Erreur Stripe Connect retour studio artist', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('info', '⏳ Configuration en cours. Votre compte sera vérifié sous peu.');
        }
    }

    /**
     * Rafraîchir le lien d'onboarding pour un artiste de studio.
     * URL : GET /studio/artist/stripe/refresh
     */
    public function studioArtistRefresh(Request $request)
    {
        $user = $request->user();
        $artisan = $user?->artisan();

        if (!$artisan) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil artiste introuvable.');
        }

        try {
            $newLink = $artisan->generateStripeConnectLink();
            return redirect($newLink);
        } catch (\Exception $e) {
            Log::error('Erreur régénération lien studio artist Stripe', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()->route($artisan->routePrefix() . '.settings')
                ->with('error', 'Impossible de générer le lien Stripe. Veuillez réessayer.');
        }
    }

    // ─────────────────────────────────────────────────────────
    // STUDIO OWNER (propriétaire de studio)
    // ─────────────────────────────────────────────────────────

    /**
     * Retour après onboarding Stripe Connect pour le propriétaire de studio.
     * URL : GET /studio/stripe/return
     */
    public function studioOwnerReturn(Request $request)
    {
        $studio = $request->user()?->studio;

        if (!$studio) {
            return redirect()->route('studio.dashboard')
                ->with('error', 'Studio introuvable.');
        }

        if (!$studio->stripe_account_id) {
            return redirect()->route('studio.settings', ['tab' => 'paiement'])
                ->with('error', 'Aucun compte Stripe Connect trouvé. Veuillez recommencer.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $account = $stripe->accounts->retrieve($studio->stripe_account_id);

            if ($account->charges_enabled) {
                $studio->update(['stripe_onboarding_complete' => true]);

                Log::info('Stripe Connect Studio onboarding complété', [
                    'studio_id'  => $studio->id,
                    'account_id' => $studio->stripe_account_id,
                ]);

                return redirect()->route('studio.settings', ['tab' => 'paiement'])
                    ->with('success', '✅ Compte Stripe du studio actif ! Vous pouvez encaisser les paiements clients.');
            }

            $requirements = $account->requirements?->currently_due ?? [];
            $message = count($requirements) > 0
                ? '⏳ Votre compte studio est en cours de vérification. Stripe vous contactera si des documents sont nécessaires.'
                : '⏳ Votre compte Stripe studio est en cours d\'activation. Revenez vérifier dans quelques heures.';

            return redirect()->route('studio.settings', ['tab' => 'paiement'])
                ->with('info', $message);
        } catch (\Exception $e) {
            Log::error('Erreur vérification Stripe Connect Studio', [
                'studio_id' => $studio->id,
                'error'     => $e->getMessage(),
            ]);

            return redirect()->route('studio.settings', ['tab' => 'paiement'])
                ->with('info', '⏳ Configuration en cours. Votre compte sera vérifié sous peu.');
        }
    }

    /**
     * Rafraîchir le lien d'onboarding Stripe Connect pour le studio.
     * URL : GET /studio/stripe/refresh
     */
    public function studioOwnerRefresh(Request $request)
    {
        $studio = $request->user()?->studio;

        if (!$studio || !$studio->stripe_account_id) {
            return redirect()->route('studio.settings', ['tab' => 'paiement'])
                ->with('error', 'Studio introuvable ou aucun compte Stripe configuré.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $accountLink = $stripe->accountLinks->create([
                'account'     => $studio->stripe_account_id,
                'refresh_url' => route('studio.stripe.refresh'),
                'return_url'  => route('studio.stripe.return'),
                'type'        => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        } catch (\Exception $e) {
            Log::error('Erreur régénération lien Stripe Connect Studio', [
                'studio_id' => $studio->id,
                'error'     => $e->getMessage(),
            ]);

            return redirect()->route('studio.settings', ['tab' => 'paiement'])
                ->with('error', 'Impossible de générer le lien Stripe. Veuillez réessayer.');
        }
    }

    // ─────────────────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────────────────

    /**
     * Résout l'artiste (Tattooer ou Piercer) depuis l'ID de route
     * et vérifie qu'il appartient à l'utilisateur connecté.
     */
    private function resolveArtist(Request $request, int $artistId): Tattooer|Piercer|null
    {
        $user = $request->user();

        if (!$user) {
            return null;
        }

        // Vérifier que l'artiste est bien celui de l'utilisateur connecté
        if ($user->tattooer?->id === $artistId) {
            return $user->tattooer;
        }

        if ($user->piercer?->id === $artistId) {
            return $user->piercer;
        }

        return null;
    }
}
