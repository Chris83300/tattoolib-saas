<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Passe les colonnes médicales sensibles en TEXT (nécessaire pour le chiffrement Laravel).
 * Après migration, les colonnes utiliseront le cast 'encrypted' sur les modèles.
 *
 * ⚠️ IMPORTANT : Avant de migrer les données existantes en production,
 * faire une sauvegarde DB, puis exécuter le script tinker de migration de données.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Table client_consent_forms — données personnelles sensibles et médicales
        Schema::table('client_consent_forms', function (Blueprint $table) {
            $table->text('parent_name')->nullable()->change();
            $table->text('parent_id_number')->nullable()->change();
            $table->text('medical_allergies_detail')->nullable()->change();
            $table->text('medical_skin_disease_detail')->nullable()->change();
            $table->text('signature_data')->nullable()->change();
            $table->text('parent_signature_data')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('client_consent_forms', function (Blueprint $table) {
            $table->string('parent_name', 255)->nullable()->change();
            $table->string('parent_id_number', 255)->nullable()->change();
            $table->string('medical_allergies_detail', 1000)->nullable()->change();
            $table->string('medical_skin_disease_detail', 1000)->nullable()->change();
            $table->text('signature_data')->nullable()->change();
            $table->text('parent_signature_data')->nullable()->change();
        });
    }
};
