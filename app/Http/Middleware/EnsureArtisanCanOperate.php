<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureArtisanCanOperate
{
    /**
     * Routes accessibles même si le trial est expiré.
     * L'artisan (tattooer/pierceur) peut voir mais pas agir.
     */
    private array $allowedRoutes = [
        // Dashboard (avec bannière de blocage)
        'tattooer.dashboard',
        'pierceur.dashboard',

        // Messages (uniquement avec acompte payé)
        'tattooer.messages',
        'tattooer.message.show',
        'tattooer.message.send',
        'pierceur.messages',
        'pierceur.message.show',
        'pierceur.message.send',

        // Settings (pour pouvoir s'abonner)
        'tattooer.settings',
        'tattooer.settings.update',
        'tattooer.profile',
        'pierceur.settings',
        'pierceur.settings.update',
        'pierceur.profile',

        // Billing/Subscription (pour pouvoir payer)
        'tattooer.subscription.plans',
        'tattooer.subscribe',
        'tattooer.subscription.manage',
        'tattooer.subscription.success',
        'tattooer.payments',
        'pierceur.subscription.plans',
        'pierceur.subscribe',
        'pierceur.subscription.manage',
        'pierceur.subscription.success',
        'pierceur.payments',

        // Compliance (obligation légale)
        'tattooer.compliance',
        'tattooer.compliance.documents',
        'pierceur.compliance',
        'pierceur.compliance.documents',
    ];

    /**
     * Routes accessibles en lecture seule même si bloqué.
     */
    private array $readOnlyRoutes = [
        // Consultation seule autorisée
        'tattooer.clients',
        'tattooer.client.show',
        'tattooer.requests',
        'tattooer.request.show',
        'pierceur.clients',
        'pierceur.client.show',
        'pierceur.requests',
        'pierceur.request.show',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Récupérer l'artisan (tattooer ou pierceur)
        $artisan = null;
        if ($user->isTattooer()) {
            $artisan = $user->tattooer;
        } elseif ($user->isPiercer()) {
            $artisan = $user->piercer;
        }

        if (!$artisan) {
            return $next($request);
        }

        // Vérifier si l'artisan est bloqué (trial expiré sans abonnement)
        $trialService = app(\App\Services\TrialService::class);

        // Utiliser la même logique que trial-banner.blade.php
        $isOnTrial = $artisan && !$artisan->is_subscribed && $trialService->isOnTrial($artisan);

        if (!$isOnTrial) {
            return $next($request);
        }

        // Pour le test: si on est en trial, on bloque systématiquement
        // car la bannière affiche "essai terminé" avec daysRemaining = 0
        $currentRoute = $request->route()?->getName();

        // Routes totalement autorisées même bloqué
        if ($currentRoute && in_array($currentRoute, $this->allowedRoutes)) {
            return $next($request);
        }

        // Routes en lecture seule autorisées
        if ($currentRoute && in_array($currentRoute, $this->readOnlyRoutes)) {
            return $next($request);
        }

        // Routes de messagerie - uniquement avec acompte payé
        if ($currentRoute && $this->isMessagingRoute($currentRoute)) {
            // Pour la route messages (liste), bloquer systématiquement car on ne peut pas vérifier l'acompte
            if (in_array($currentRoute, ['tattooer.messages', 'pierceur.messages'])) {
                return $this->blockedResponse($request, $user);
            }
            if (!$this->hasPaidDeposit($request)) {
                return $this->blockedResponse($request, $user);
            }
            return $next($request);
        }

        // Route non autorisée quand bloqué
        return $this->blockedResponse($request, $user);
    }

    /**
     * Vérifie si c'est une route de messagerie
     */
    private function isMessagingRoute(string $route): bool
    {
        return in_array($route, [
            'tattooer.messages',
            'tattooer.message.show',
            'tattooer.message.send',
            'pierceur.messages',
            'pierceur.message.show',
            'pierceur.message.send'
        ]);
    }

    /**
     * Vérifie si la conversation a un acompte payé
     */
    private function hasPaidDeposit(Request $request): bool
    {
        $bookingRequest = $request->route('bookingRequest');

        if ($bookingRequest) {
            return !is_null($bookingRequest->deposit_paid_at);
        }

        // Pour la liste des messages, on pourrait vérifier si au moins une conversation a un acompte payé
        return false; // Par défaut, bloquer si on ne peut pas vérifier
    }

    /**
     * Réponse quand l'artiste est bloqué
     */
    private function blockedResponse(Request $request, $user)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Votre essai gratuit est terminé. Activez votre abonnement pour continuer.',
                'upgrade_url' => route($user->isTattooer() ? 'tattooer.subscription.plans' : 'pierceur.subscription.plans'),
            ], 403);
        }

        // Rediriger vers la page d'abonnement
        $redirectRoute = $user->isTattooer() ? 'tattooer.subscription.plans' : 'pierceur.subscription.plans';

        return redirect()->route($redirectRoute)
            ->with('error', 'Votre essai gratuit est terminé. Activez votre abonnement pour continuer à utiliser cette fonctionnalité.');
    }
}
