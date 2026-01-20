<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('studio_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->onDelete('cascade');

            // Stripe
            $table->string('stripe_subscription_id')->unique();
            $table->string('stripe_customer_id');
            $table->string('stripe_price_id')->nullable();

            // Statut
            $table->enum('status', [
                'active', 'trialing', 'past_due', 'canceled', 'unpaid', 'incomplete'
            ])->default('active');

            // Tarification
            $table->decimal('base_price', 8, 2)->default(79.99);
            $table->decimal('price_per_artist', 8, 2)->default(39.99);
            $table->decimal('total_price', 8, 2); // Calculé : 79.99 + ((artists - 1) * 39.99)
            $table->string('currency', 3)->default('EUR');
            $table->string('billing_interval')->default('month');

            // Compteurs artistes
            $table->unsignedInteger('included_artists')->default(1); // 1 inclus dans base
            $table->unsignedInteger('current_artists')->default(1);
            $table->unsignedInteger('additional_artists')->default(0); // current - included

            // Features
            $table->json('features')->nullable();

            // Périodes
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start');
            $table->timestamp('current_period_end');
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            // Index
            $table->index(['studio_id', 'status']);
            $table->index('current_period_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_subscriptions');
    }
};
