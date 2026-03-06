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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bookable_id'); // Polymorphic
            $table->string('bookable_type'); // 'App\Models\Tattooer', 'App\Models\StudioArtist', 'App\Models\Pierceur'

            // Types : appointment, break, vacation, closure
            $table->enum('type', ['appointment', 'break', 'vacation', 'closure']);

            $table->foreignId('appointment_id')->nullable()->constrained()->cascadeOnDelete();

            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            // Récurrence (pour vacances/fermetures)
            $table->boolean('is_recurring')->default(false);
            $table->json('recurrence_rule')->nullable(); // {"freq": "weekly", "days": [0,6]} pour dimanche

            $table->text('notes')->nullable();
            $table->string('color')->default('#D4B59E'); // Couleur calendrier

            $table->timestamps();

            $table->index(['bookable_type', 'bookable_id', 'start_datetime', 'end_datetime'], 'calendar_events_datetime_index');
            $table->index(['type', 'start_datetime'], 'calendar_events_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
