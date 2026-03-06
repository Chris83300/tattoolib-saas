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
        Schema::create('tattooers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Infos métier
            $table->string('siret')->unique();
            $table->boolean('siret_verified')->default(false);
            $table->string('name')->nullable();
            $table->string('studio_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('email')->nullable();

            //  Stripe Connect
            $table->string('stripe_connect_account_id')->nullable()->unique();
            $table->boolean('stripe_onboarding_complete')->default(false);

            // Réseaux sociaux
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('website')->nullable();

            // Paramètres par défaut
            $table->decimal('minimum_deposit', 8, 2)->default(50.00);
            $table->integer('default_deposit_rate')->default(40);
            $table->integer('default_client_payment_deadline_days')->default(7);
            $table->integer('default_tattooer_design_deadline_days')->default(7);
            $table->integer('default_design_versions_included')->default(3);

            // Délais d'attente (calculés automatiquement)
            $table->integer('weekday_wait_days')->default(0);
            $table->integer('weekend_wait_days')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tattooers');
    }
};
