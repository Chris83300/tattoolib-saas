<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // ⭐ NOUVEAU : Ajouter la date du RDV (manquante)
            if (!Schema::hasColumn('appointments', 'appointment_date')) {
                $table->date('appointment_date')->after('client_id');
            }

            // ⭐ NOUVEAU : Source du RDV
            if (!Schema::hasColumn('appointments', 'source')) {
                $table->enum('source', [
                    'platform',
                    'external_walk_in',
                    'external_phone',
                    'external_social'
                ])->default('platform')->after('appointment_date');
            }

            if (!Schema::hasColumn('appointments', 'external_source_notes')) {
                $table->text('external_source_notes')->nullable()->after('source');
            }

            // Corriger les noms de colonnes existants
            if (Schema::hasColumn('appointments', 'opening_time') && !Schema::hasColumn('appointments', 'start_time')) {
                $table->renameColumn('opening_time', 'start_time');
            }
            if (Schema::hasColumn('appointments', 'closing_time') && !Schema::hasColumn('appointments', 'end_time')) {
                $table->renameColumn('closing_time', 'end_time');
            }
        });

        // Mettre à jour les données existantes
        $this->updateAppointmentDates();

        // Ajouter les index séparément pour éviter les erreurs
        $this->addIndexesSafely();
    }

    private function updateAppointmentDates(): void
    {
        // Utiliser les anciens noms de colonnes avant le renommage
        if (Schema::hasColumn('appointments', 'opening_time')) {
            \Illuminate\Support\Facades\DB::statement("
                UPDATE appointments
                SET appointment_date = DATE(opening_time)
                WHERE appointment_date IS NULL
            ");
        } elseif (Schema::hasColumn('appointments', 'start_time')) {
            // Si les colonnes ont déjà été renommées
            \Illuminate\Support\Facades\DB::statement("
                UPDATE appointments
                SET appointment_date = DATE(start_time)
                WHERE appointment_date IS NULL
            ");
        }
    }

    private function addIndexesSafely(): void
    {
        try {
            // Vérifier si l'index existe déjà en essayant de le créer
            \Illuminate\Support\Facades\DB::statement("
                ALTER TABLE appointments
                ADD INDEX appointments_tattooer_id_appointment_date_start_time_index
                (tattooer_id, appointment_date, start_time)
            ");
        } catch (\Exception $e) {
            // L'index existe déjà, ignorer
        }

        try {
            \Illuminate\Support\Facades\DB::statement("
                ALTER TABLE appointments
                ADD INDEX appointments_source_index (source)
            ");
        } catch (\Exception $e) {
            // L'index existe déjà, ignorer
        }
    }

    public function down(): void
    {
        // Supprimer les index avec SQL direct pour éviter les erreurs
        try {
            \Illuminate\Support\Facades\DB::statement("
                DROP INDEX appointments_tattooer_id_appointment_date_start_time_index ON appointments
            ");
        } catch (\Exception $e) {
            // L'index n'existe pas, ignorer
        }

        try {
            \Illuminate\Support\Facades\DB::statement("
                DROP INDEX appointments_source_index ON appointments
            ");
        } catch (\Exception $e) {
            // L'index n'existe pas, ignorer
        }

        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'appointment_date')) {
                $table->dropColumn('appointment_date');
            }
            if (Schema::hasColumn('appointments', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('appointments', 'external_source_notes')) {
                $table->dropColumn('external_source_notes');
            }

            // Restaurer les anciens noms si ils existent
            if (Schema::hasColumn('appointments', 'start_time') && !Schema::hasColumn('appointments', 'opening_time')) {
                $table->renameColumn('start_time', 'opening_time');
            }
            if (Schema::hasColumn('appointments', 'end_time') && !Schema::hasColumn('appointments', 'closing_time')) {
                $table->renameColumn('end_time', 'closing_time');
            }
        });
    }
};
