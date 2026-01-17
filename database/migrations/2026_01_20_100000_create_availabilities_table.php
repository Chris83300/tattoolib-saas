<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->constrained()->onDelete('cascade');

            // Période de disponibilité
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            // Type de disponibilité
            $table->enum('type', ['available', 'busy', 'break', 'holiday', 'sick_leave'])
                ->default('available');

            // Informations complémentaires
            $table->text('notes')->nullable();

            // Récurrence
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_pattern')->nullable(); // daily, weekly, monthly
            $table->date('recurring_end_date')->nullable();

            // Réservation associée si busy
            $table->foreignId('appointment_id')->nullable()
                ->constrained()->onDelete('cascade');

            // ⭐ NOUVEAU : Tracer si généré depuis WorkingHours
            $table->boolean('generated_from_working_hour')->default(false);

            $table->timestamps();

            // Index pour optimisation
            $table->index(['tattooer_id', 'date']);
            $table->index(['tattooer_id', 'date', 'type']);
            $table->index('appointment_id');

            // ⚠️ SUPPRESSION de la contrainte unique trop stricte
            // On peut avoir plusieurs availabilities le même jour (pause + dispo + busy)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
