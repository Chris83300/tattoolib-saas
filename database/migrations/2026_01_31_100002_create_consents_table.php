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
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('bookable_id'); // Polymorphic
            $table->string('bookable_type'); // 'App\Models\Tattooer', 'App\Models\StudioArtist', 'App\Models\Pierceur'

            // Signature
            $table->text('signature_data'); // Base64 Canvas signature
            $table->timestamp('signed_at');

            // Infos médicales (JSON)
            $table->json('medical_conditions')->nullable(); // ["diabète", "hémophilie"]
            $table->json('allergies')->nullable(); // ["latex", "pénicilline"]
            $table->json('medications')->nullable(); // ["aspirine", "anti-coagulant"]
            $table->boolean('is_pregnant')->default(false);
            $table->boolean('has_skin_conditions')->default(false);

            // Consentement parental (si mineur)
            $table->boolean('is_minor')->default(false);
            $table->text('parent_signature_data')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_relation')->nullable(); // Père, Mère, Tuteur
            // Spatie Media : parent_id_photo (photo pièce d'identité parent)

            // Acceptation CGV
            $table->boolean('accepts_terms')->default(true);
            $table->boolean('accepts_aftercare')->default(true);

            $table->timestamps();

            $table->index(['client_id', 'bookable_type', 'bookable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};
