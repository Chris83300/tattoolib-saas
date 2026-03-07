<?php

namespace App\Console\Commands;

use App\Models\Studio;
use Illuminate\Console\Command;

class FixStudioSubscriptionItems extends Command
{
    protected $signature = 'inkpik:fix-studio-items {--studio-id= : ID du studio à corriger (tous si absent)}';
    protected $description = 'Corriger les items d\'abonnement studio : STUDIO×1 + EXTRA×(N-1) au lieu de STUDIO×N';

    public function handle(): int
    {
        $studioId = $this->option('studio-id');
        $studios  = $studioId ? Studio::where('id', $studioId)->get() : Studio::all();

        if ($studios->isEmpty()) {
            $this->error('Aucun studio trouvé.');
            return 1;
        }

        $studioPriceId = config('inkpik.pricing.studio.stripe_price_id');
        $extraPriceId  = config('inkpik.pricing.studio.stripe_price_id_extra');
        $stripe        = new \Stripe\StripeClient(config('cashier.secret'));

        foreach ($studios as $studio) {
            $this->newLine();
            $this->info("=== Studio #{$studio->id} — {$studio->name} ===");

            $user = $studio->user;
            if (!$user || !$user->subscribed('default')) {
                $this->warn('  Pas d\'abonnement actif — ignoré.');
                continue;
            }

            $sub = $user->subscription('default');
            $this->line("  Sub: {$sub->stripe_id} (qty locale={$sub->quantity})");

            // Récupérer l'abonnement Stripe
            $stripeSub = $stripe->subscriptions->retrieve($sub->stripe_id, ['expand' => ['items']]);

            $this->line('  Items actuels Stripe :');
            foreach ($stripeSub->items->data as $item) {
                $this->line("    {$item->id}: price={$item->price->id} qty={$item->quantity}");
            }

            // Compter les artistes
            $totalArtists = $studio->tattooers()->count() + $studio->piercers()->count();
            $extraNeeded  = max(0, $totalArtists - 1);
            $this->line("  Artistes: {$totalArtists} (1 inclus, {$extraNeeded} supplémentaire(s))");

            // Identifier les items
            $studioItem = null;
            $extraItem  = null;
            foreach ($stripeSub->items->data as $item) {
                if ($item->price->id === $studioPriceId) $studioItem = $item;
                if ($item->price->id === $extraPriceId)  $extraItem  = $item;
            }

            $changed = false;

            // Corriger STUDIO si qty > 1
            if ($studioItem && $studioItem->quantity > 1) {
                $this->warn("  STUDIO qty={$studioItem->quantity} → correction vers 1");
                $stripe->subscriptionItems->update($studioItem->id, ['quantity' => 1]);
                $this->info('  ✅ STUDIO remis à qty=1');
                $changed = true;
            } elseif ($studioItem) {
                $this->line('  STUDIO qty=1 ✓');
            } else {
                $this->error('  Item STUDIO introuvable dans l\'abonnement !');
            }

            // Gérer EXTRA
            if ($extraNeeded > 0) {
                if ($extraItem) {
                    if ($extraItem->quantity !== $extraNeeded) {
                        $stripe->subscriptionItems->update($extraItem->id, ['quantity' => $extraNeeded]);
                        $this->info("  ✅ EXTRA mis à jour: qty={$extraNeeded}");
                        $changed = true;
                    } else {
                        $this->line("  EXTRA qty={$extraNeeded} ✓");
                    }
                } else {
                    $stripe->subscriptionItems->create([
                        'subscription' => $sub->stripe_id,
                        'price'        => $extraPriceId,
                        'quantity'     => $extraNeeded,
                    ]);
                    $this->info("  ✅ EXTRA ajouté: qty={$extraNeeded}");
                    $changed = true;
                }
            } else {
                if ($extraItem) {
                    $stripe->subscriptionItems->delete($extraItem->id, ['proration_behavior' => 'create_prorations']);
                    $this->info('  ✅ EXTRA supprimé (aucun artiste supplémentaire)');
                    $changed = true;
                } else {
                    $this->line('  Pas d\'EXTRA nécessaire ✓');
                }
            }

            // Synchroniser Cashier
            if ($changed) {
                $sub->items()->delete();
                $freshSub = $stripe->subscriptions->retrieve($sub->stripe_id, ['expand' => ['items']]);
                foreach ($freshSub->items->data as $item) {
                    $sub->items()->create([
                        'stripe_id'      => $item->id,
                        'stripe_product' => $item->price->product,
                        'stripe_price'   => $item->price->id,
                        'quantity'       => $item->quantity,
                    ]);
                }

                $this->newLine();
                $this->line('  Items après correction :');
                foreach ($freshSub->items->data as $item) {
                    $this->line("    {$item->id}: price={$item->price->id} qty={$item->quantity}");
                }
            }

            $expectedTotal = 59.99 + ($extraNeeded * 24.99);
            $this->info("  Total attendu: {$expectedTotal}€/mois");
        }

        $this->newLine();
        $this->info('Correction terminée.');
        return 0;
    }
}
