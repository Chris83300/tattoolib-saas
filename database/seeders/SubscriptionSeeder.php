<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tattooer;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Créer abonnement FREE pour tous les tatoueurs existants
        Tattooer::whereDoesntHave('subscription')->each(function ($tattooer) {
            $tattooer->createFreeSubscription();

            $tattooer->update([
                'current_plan' => Subscription::PLAN_FREE,
                'is_subscribed' => false,
            ]);
        });

        $this->command->info('✅ Abonnements FREE créés pour tous les tatoueurs existants');
    }
}
