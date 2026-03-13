<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct(
        private \Stripe\StripeClient $stripe
    ) {}

    /**
     * Créer un compte Connect pour un utilisateur
     * (Tattooer indépendant OU StudioArtist)
     *
     * @param User $user
     * @return \Stripe\Account
     */
    public function createConnectAccount(User $user): \Stripe\Account
    {
        try {
            // Déterminer le type de compte
            $accountType = $this->determineAccountType($user);

            // Préparer metadata
            $metadata = [
                'user_id' => $user->id,
                'account_type' => $accountType,
            ];

            // Ajouter studio_id si artiste de salon
            if ($user->is_studio_artist && $user->studio_id) {
                $metadata['studio_id'] = $user->studio_id;
                $metadata['studio_name'] = $user->studio->name ?? 'N/A';
            }

            return $this->stripe->accounts->create([
                'type' => 'express',
                'country' => 'FR',
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'individual' => [
                    'email' => $user->email,
                    'first_name' => explode(' ', $user->name)[0] ?? '',
                    'last_name' => explode(' ', $user->name)[1] ?? '',
                ],
                'metadata' => $metadata,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur création compte Connect', [
                'user_id' => $user->id,
                'account_type' => $accountType ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Détermine le type de compte Stripe selon l'utilisateur
     *
     * @param User $user
     * @return string
     */
    private function determineAccountType(User $user): string
    {
        // StudioArtist (rattaché à un salon)
        if ($user->is_studio_artist) {
            return 'studio_artist';
        }

        // Tattooer indépendant
        if ($user->isTattooer()) {
            return 'independent_tattooer';
        }

        // Propriétaire de studio
        if ($user->is_studio_owner) {
            return 'studio_owner';
        }

        // Client ou autre
        return 'client';
    }

    /**
     * Créer un lien d'onboarding pour le compte Connect
     */
    public function createConnectOnboardingLink(string $accountId): string
    {
        try {
            $accountLink = $this->stripe->accountLinks->create([
                'account' => $accountId,
                'refresh_url' => route('studio.artist.stripe.refresh'),
                'return_url' => route('studio.artist.stripe.return'),
                'type' => 'account_onboarding',
            ]);

            return $accountLink->url;

        } catch (\Exception $e) {
            Log::error('Erreur création lien onboarding', [
                'account_id' => $accountId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculer le montant de l'application fee en centimes.
     *
     * - Artiste de studio : utilise studio->artist_commission_rate (0 si null)
     * - Artiste indépendant STARTER : 7%
     * - Artiste indépendant PRO / trial : 0%
     *
     * @param int $amountCents  Montant total en centimes
     * @param mixed $artist     Tattooer|Piercer
     * @param \App\Models\Studio|null $studio  Studio si artiste studio
     */
    public function calculateApplicationFee(int $amountCents, $artist, ?\App\Models\Studio $studio = null): int
    {
        if ($studio) {
            $rate = $studio->artist_commission_rate;
            if (is_null($rate) || $rate <= 0) {
                return 0;
            }
            return (int) round($amountCents * ($rate / 100));
        }

        // Artiste indépendant — délègue au modèle (7% STARTER, 0% PRO/trial)
        return $artist->calculateCommission($amountCents);
    }

    /**
     * Vérifier le statut d'un compte Connect
     */
    public function getConnectAccountStatus(string $accountId): array
    {
        try {
            $account = $this->stripe->accounts->retrieve($accountId, []);

            return [
                'id' => $account->id,
                'status' => $account->charges_enabled ? 'active' : 'pending',
                'charges_enabled' => $account->charges_enabled,
                'payouts_enabled' => $account->payouts_enabled,
                'requirements' => $account->requirements,
            ];

        } catch (\Exception $e) {
            Log::error('Erreur récupération statut compte', [
                'account_id' => $accountId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
