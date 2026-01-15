<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();

            // Informations de base
            $table->string('name');
            $table->text('description')->nullable();

            // Adresse
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('France');

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Réseaux sociaux
            $table->json('social_media_links')->nullable();

            // Logo
            $table->string('logo_url')->nullable();

            // Géolocalisation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Vérification
            $table->boolean('is_verified')->default(false);

            // Horaires globaux du studio (optionnel)
            $table->json('opening_hours')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('city');
            $table->index('postal_code');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
