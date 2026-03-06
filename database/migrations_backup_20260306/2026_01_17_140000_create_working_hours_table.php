<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();

            // Relations polymorphiques (Tattooer ou StudioArtist)
            $table->morphs('owner');

            // Jour de la semaine (0 = Dimanche, 6 = Samedi)
            $table->tinyInteger('day_of_week');

            // Ouverture
            $table->boolean('is_open')->default(true);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Pause déjeuner
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();

            // ⭐ NOUVEAUX CHAMPS
            $table->integer('slot_duration_minutes')->default(60); // Durée créneau
            $table->integer('buffer_time_minutes')->default(15);   // Temps entre RDV

            $table->timestamps();

            // Index pour performances
            $table->index(['owner_type', 'owner_id', 'is_open']);
            $table->index(['owner_type', 'owner_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('working_hours');
    }
};
