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
        Schema::create('studio_artists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Informations publiques
            $table->string('artist_name');
            $table->string('slug')->unique(); // Pour URL publique
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();

            // Stripe Connect (paiements directs)
            $table->string('stripe_connect_account_id')->nullable()->unique();

            // Statut
            $table->enum('status', ['active', 'inactive', 'on_leave', 'deleted'])->default('active');
            $table->boolean('is_active')->default(true);

            // Dates
            $table->date('joined_at');
            $table->date('left_at')->nullable();

            // Planning
            $table->json('working_schedule')->nullable();

            // Stats (pour dashboard salon)
            $table->unsignedInteger('total_appointments')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);

            // Gestion salon
            $table->boolean('credentials_managed_by_studio')->default(true);

            // ⭐ NOUVEAU : Champs pour compatibilité avec les tests
            $table->boolean('siret_verified')->default(false);
            $table->boolean('stripe_onboarding_complete')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Contraintes
            $table->unique(['studio_id', 'user_id']);
            $table->index(['studio_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_artists');
    }
};
