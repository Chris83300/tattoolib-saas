<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Table abonnements métier artisans (tattooers + piercers polymorphique)
// Utilisée par App\Models\Subscription (protected $table = 'tattooer_subscriptions')
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tattooer_subscriptions', function (Blueprint $table) {
            $table->id();
            // Polymorphique (Tattooer ou Piercer) — pas de FK SQL
            $table->string('subscribable_type');
            $table->unsignedBigInteger('subscribable_id');
            $table->enum('plan', ['free', 'pro', 'studio'])->default('free');
            $table->enum('status', ['active', 'past_due', 'canceled', 'unpaid'])->default('active');
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_price_id')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->decimal('price_monthly', 8, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->default(7.00);
            $table->json('features')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['subscribable_type', 'subscribable_id']);
            $table->index('status');
            $table->index('plan');
            $table->index('stripe_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tattooer_subscriptions');
    }
};
