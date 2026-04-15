<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rend la table traceability_records compatible avec les traçabilités standalone
     * (sans rendez-vous associé, créées depuis la fiche client).
     *
     * Problèmes résolus :
     * - appointment_id était NOT NULL + UNIQUE → empêchait les enregistrements sans RDV
     * - procedure_start_time / procedure_end_time étaient NOT NULL
     * - aftercare_products était NOT NULL (json)
     * - Colonnes user_id, needles_used, inks_used manquantes
     */
    public function up(): void
    {
        Schema::table('traceability_records', function (Blueprint $table) {
            // 1. Supprimer la contrainte unique sur appointment_id (si elle existe)
            $indexes = collect(DB::select("SHOW INDEX FROM traceability_records"))
                ->pluck('Key_name')
                ->unique()
                ->values();

            if ($indexes->contains('traceability_records_appointment_id_unique')) {
                $table->dropUnique('traceability_records_appointment_id_unique');
            }

            // 2. Rendre appointment_id nullable (standalone = pas de RDV)
            $table->unsignedBigInteger('appointment_id')->nullable()->change();

            // 3. Rendre les heures nullable
            $table->time('procedure_start_time')->nullable()->change();
            $table->time('procedure_end_time')->nullable()->change();

            // 4. Rendre aftercare_products nullable
            $table->json('aftercare_products')->nullable()->change();

            // 5. Ajouter user_id si absent
            if (!Schema::hasColumn('traceability_records', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // 6. Ajouter needles_used / inks_used si absents
            if (!Schema::hasColumn('traceability_records', 'needles_used')) {
                $table->json('needles_used')->nullable()->after('sterile_equipment');
            }
            if (!Schema::hasColumn('traceability_records', 'inks_used')) {
                $table->json('inks_used')->nullable()->after('needles_used');
            }
        });
    }

    public function down(): void
    {
        Schema::table('traceability_records', function (Blueprint $table) {
            $table->unique('appointment_id');
            $table->unsignedBigInteger('appointment_id')->nullable(false)->change();
            $table->time('procedure_start_time')->nullable(false)->change();
            $table->time('procedure_end_time')->nullable(false)->change();
            $table->json('aftercare_products')->nullable(false)->change();

            if (Schema::hasColumn('traceability_records', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('traceability_records', 'needles_used')) {
                $table->dropColumn('needles_used');
            }
            if (Schema::hasColumn('traceability_records', 'inks_used')) {
                $table->dropColumn('inks_used');
            }
        });
    }
};
