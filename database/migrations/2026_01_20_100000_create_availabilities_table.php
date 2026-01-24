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

            // Relations polymorphiques (Tattooer ou StudioArtist)
            $table->morphs('owner');

            // Période de disponibilité
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            // Type de disponibilité
            $table->enum('type', ['available', 'busy', 'break', 'holiday', 'sick_leave', 'external_booking', 'blocked'])
                ->default('available');

            // Source de l'availability
            $table->enum('source', ['manual', 'working_hours', 'booking', 'external'])
                ->default('manual');

            // Informations complémentaires
            $table->text('notes')->nullable();

            // Récurrence
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_pattern')->nullable(); // daily, weekly, monthly
            $table->date('recurring_end_date')->nullable();

            // Réservation associée si busy
            $table->foreignId('appointment_id')->nullable()
                ->constrained()->onDelete('cascade');

            $table->timestamps();

            // Index pour optimisation
            $table->index(['owner_type', 'owner_id', 'date']);
            $table->index(['owner_type', 'owner_id', 'date', 'type']);
            $table->index('appointment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
