<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tattooer;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_tattooer_has_free_plan_by_default()
    {
        $tattooer = Tattooer::factory()->create();
        $tattooer->createFreeSubscription();

        $this->assertTrue($tattooer->isOnFreePlan());
        $this->assertEquals(7.00, $tattooer->getCommissionRate());
    }

    /** @test */
    public function free_plan_calculates_7_percent_commission()
    {
        $tattooer = Tattooer::factory()->create();
        $tattooer->createFreeSubscription();

        // 100€ = 10000 centimes
        $commission = $tattooer->calculateCommission(10000);

        $this->assertEquals(700, $commission); // 7€
    }

    /** @test */
    public function pro_plan_has_zero_commission()
    {
        $tattooer = Tattooer::factory()->create();
        $tattooer->upgradeToPro('sub_test123', 'price_test123');

        $this->assertTrue($tattooer->isOnProPlan());
        $this->assertEquals(0.00, $tattooer->getCommissionRate());

        $commission = $tattooer->calculateCommission(10000);
        $this->assertEquals(0, $commission);
    }

    /** @test */
    public function can_check_feature_availability()
    {
        $tattooer = Tattooer::factory()->create();
        $tattooer->createFreeSubscription();

        // FREE a accès
        $this->assertTrue($tattooer->hasFeature('marketplace_profile'));
        $this->assertTrue($tattooer->hasFeature('deposit_payment'));

        // FREE n'a pas accès
        $this->assertFalse($tattooer->hasFeature('client_history'));
        $this->assertFalse($tattooer->hasFeature('zero_commission'));

        // Upgrade PRO
        $tattooer->upgradeToPro('sub_test', 'price_test');

        // PRO a accès à tout
        $this->assertTrue($tattooer->hasFeature('client_history'));
        $this->assertTrue($tattooer->hasFeature('zero_commission'));
    }
}
