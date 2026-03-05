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
        Schema::table('traceability_records', function (Blueprint $table) {
            // Rendre client_consent_form_id nullable pour permettre les enregistrements sans formulaire de consentement
            $table->foreignId('client_consent_form_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traceability_records', function (Blueprint $table) {
            // Revenir à la contrainte originale (non nullable)
            $table->foreignId('client_consent_form_id')->nullable(false)->change();
        });
    }
};
