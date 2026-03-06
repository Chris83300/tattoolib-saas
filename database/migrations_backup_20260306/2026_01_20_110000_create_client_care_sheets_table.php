<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_care_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('tattooer_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');

            // Informations sur le tattoo
            $table->text('tattoo_description');
            $table->string('tattoo_location');
            $table->string('tattoo_size');
            $table->text('technique_used')->nullable();
            $table->text('ink_colors_used')->nullable();

            // Informations médicales importantes
            $table->text('allergies')->nullable();
            $table->text('skin_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->boolean('has_diabetes')->default(false);
            $table->boolean('has_blood_disorders')->default(false);
            $table->boolean('is_pregnant')->default(false);

            // Soins immédiats
            $table->text('immediate_care_instructions');
            $table->text('products_used');
            $table->text('bandage_type');
            $table->dateTime('bandage_removal_time');

            // Instructions de soins
            $table->text('washing_instructions');
            $table->text('moisturizing_instructions');
            $table->text('activity_restrictions');
            $table->text('sun_exposure_warnings');

            // Suivi
            $table->date('healing_estimated_date');
            $table->date('first_touchup_date')->nullable();
            $table->text('healing_notes')->nullable();
            $table->enum('healing_status', ['in_progress', 'healed', 'complicated', 'touchup_needed'])->default('in_progress');

            // Photos du suivi
            $table->json('healing_photos')->nullable(); // URLs des photos à différents stades

            $table->timestamps();

            $table->index(['client_id', 'tattooer_id']);
            $table->index('appointment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_care_sheets');
    }
};
