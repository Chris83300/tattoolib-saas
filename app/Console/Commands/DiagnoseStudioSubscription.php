<?php

namespace App\Console\Commands;

use App\Models\Studio;
use Illuminate\Console\Command;

class DiagnoseStudioSubscription extends Command
{
    protected $signature = 'inkpik:diagnose-subscription {--studio-id= : ID du studio à diagnostiquer}';
    protected $description = "Diagnostiquer l'état de l'abonnement studio (local + Stripe)";

    public function handle(): int
    {
        $studioId = $this->option('studio-id');
        $studio   = $studioId ? Studio::find($studioId) : Studio::first();

        if (!$studio) {
            $this->error('Aucun studio trouvé.');
            return 1;
        }

        $user = $studio->user;
        $this->info("=== Studio #{$studio->id} — {$studio->name} ===");
        $this->newLine();

        // 1. État local
        $this->info('--- ÉTAT LOCAL ---');
        $this->line('is_subscribed: ' . ($studio->is_subscribed ? 'true' : 'false'));
        $this->line('trial_ends_at: ' . ($studio->trial_ends_at ?? 'NULL'));
        $this->line('hasActiveSubscription(): ' . ($studio->hasActiveSubscription() ? 'true' : 'false'));
        $this->line('canOperate(): ' . ($studio->canOperate() ? 'true' : 'false'));

        if ($user) {
            $this->line("User #{$user->id} ({$user->email})");
            $this->line('  stripe_id: ' . ($user->stripe_id ?? 'NULL'));

            $subs = $user->subscriptions()->get();
            $this->line('  Cashier subscriptions: ' . $subs->count());
            foreach ($subs as $sub) {
                $this->line("    type={$sub->type} stripe_id={$sub->stripe_id} status={$sub->stripe_status} price={$sub->stripe_price}");
                $this->line('      trial_ends_at=' . ($sub->trial_ends_at ?? 'NULL') . ' ends_at=' . ($sub->ends_at ?? 'NULL'));
            }
        } else {
            $this->error('PAS DE USER ASSOCIÉ AU STUDIO !');
            return 1;
        }

        $this->newLine();

        // 2. Config
        $this->info('--- CONFIG ---');
        $priceId = config('inkpik.pricing.studio.stripe_price_id');
        $this->line('STRIPE_PRICE_ID_STUDIO: ' . ($priceId ?: 'VIDE ⚠️'));
        $this->line('CASHIER_CURRENCY: ' . config('cashier.currency'));

        $this->newLine();

        // 3. État Stripe
        $this->info('--- ÉTAT STRIPE ---');
        if (!$user->stripe_id) {
            $this->warn("Pas de stripe_id → pas de customer Stripe. L'utilisateur n'a jamais passé par Stripe Checkout.");
            return 0;
        }

        try {
            $stripe   = new \Stripe\StripeClient(config('cashier.secret'));
            $customer = $stripe->customers->retrieve($user->stripe_id);
            $this->line("Customer: {$customer->id} ({$customer->email})");

            $stripeSubs = $stripe->subscriptions->all(['customer' => $user->stripe_id, 'limit' => 10]);
            if (empty($stripeSubs->data)) {
                $this->warn('AUCUN abonnement trouvé dans Stripe !');
                $this->warn('→ Si la table subscriptions contient des données, ce sont des FANTÔMES.');
            } else {
                foreach ($stripeSubs->data as $ss) {
                    $this->line("  Sub: {$ss->id}");
                    $this->line('    status: ' . $ss->status);
                    $this->line('    plan: ' . ($ss->items->data[0]->price->id ?? '?'));
                    $this->line('    trial_end: ' . ($ss->trial_end ? date('Y-m-d H:i', $ss->trial_end) : 'none'));
                    $this->line('    period_end: ' . date('Y-m-d H:i', $ss->current_period_end));
                }
            }

            // Checkout sessions récentes
            $sessions = $stripe->checkout->sessions->all(['customer' => $user->stripe_id, 'limit' => 3]);
            if (!empty($sessions->data)) {
                $this->newLine();
                $this->info('--- CHECKOUT SESSIONS RÉCENTES ---');
                foreach ($sessions->data as $sess) {
                    $this->line("  {$sess->id} status={$sess->status} payment={$sess->payment_status} sub=" . ($sess->subscription ?? 'NULL'));
                }
            }

            // Vérifier le Price ID
            if ($priceId) {
                try {
                    $price = $stripe->prices->retrieve($priceId);
                    $this->newLine();
                    $this->info('Price ID valide: ' . $price->id . ' — ' . ($price->unit_amount / 100) . '€/' . $price->recurring->interval);
                } catch (\Exception $e) {
                    $this->error('Price ID INVALIDE dans Stripe: ' . $e->getMessage());
                }
            }

            // Recommandations
            $this->newLine();
            $this->info('--- RECOMMANDATIONS ---');
            $localSub    = $user->subscriptions()->first();
            $hasStripeSub = !empty($stripeSubs->data);

            if ($localSub && !$hasStripeSub) {
                $this->error('❌ Abonnement local FANTÔME — supprimer avec:');
                $this->line("   php artisan tinker --execute=\"DB::table('subscriptions')->truncate(); App\\Models\\Studio::query()->update(['is_subscribed' => false]);\"");
            } elseif (!$localSub && $hasStripeSub) {
                $this->warn('⚠️  Abonnement Stripe non synchronisé — lancer:');
                $this->line('   php artisan inkpik:sync-stripe');
            } elseif ($localSub && $hasStripeSub) {
                $this->info('✅ Abonnement cohérent (local + Stripe)');
            } else {
                $this->line("ℹ️  Pas d'abonnement. Aller sur /studio/billing pour s'abonner.");
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->error('Stripe API Error: ' . $e->getMessage());
        }

        return 0;
    }
}
