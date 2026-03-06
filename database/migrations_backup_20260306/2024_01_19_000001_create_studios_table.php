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
        Schema::create('studios', function (Blueprint $table) {
            $table->id();

            // Informations de base
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Adresse
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('FR');

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Réseaux sociaux
            $table->json('social_media_links')->nullable();

            // Visuels
            $table->string('logo_url')->nullable();
            $table->json('cover_images')->nullable();

            // Géolocalisation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Configuration
            $table->json('opening_hours')->nullable();
            $table->json('facilities')->nullable();
            $table->json('settings')->nullable();

            // Légal
            $table->string('siret')->nullable()->unique();
            $table->string('vat_number')->nullable();

            // Stripe
            $table->string('stripe_customer_id')->nullable()->unique();

            // Stats
            $table->unsignedInteger('total_artists')->default(1); // 1 inclus dans offre de base

            // Statut
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['is_active', 'is_verified']);
            $table->index('city');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
