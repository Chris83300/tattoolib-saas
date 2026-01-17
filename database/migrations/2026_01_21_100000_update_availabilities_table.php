<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // Ajouter les nouveaux types enum
            $table->enum('type', [
                'available',
                'busy',
                'break',
                'holiday',
                'sick_leave',
                'external_booking', // ⭐ RDV hors plateforme
                'blocked' // ⭐ Bloqué manuellement par tatoueur
            ])->default('available')->change();

            // ⭐ NOUVEAU : Source du blocage
            if (!Schema::hasColumn('availabilities', 'source')) {
                $table->enum('source', [
                    'working_hours',
                    'manual',
                    'appointment',
                    'external'
                ])->default('working_hours')->after('type');
            }

            // ⭐ NOUVEAU : Durée calculée (pour optimisation)
            // Compatible avec SQLite pour les tests
            if (!Schema::hasColumn('availabilities', 'duration_minutes')) {
                if (config('database.default') === 'sqlite') {
                    // SQLite n'a pas TIMESTAMPDIFF, on utilise un simple stockage
                    $table->integer('duration_minutes')->nullable()->after('end_time');
                } else {
                    // MySQL/MariaDB/PostgreSQL - colonne virtuelle calculée
                    $table->integer('duration_minutes')->virtualAs(
                        'TIMESTAMPDIFF(MINUTE, CONCAT(date, " ", start_time), CONCAT(date, " ", end_time))'
                    )->after('end_time');
                }
            }

            // Renommer generated_from_working_hour en source (migration)
            if (Schema::hasColumn('availabilities', 'generated_from_working_hour')) {
                $table->dropColumn('generated_from_working_hour');
            }
        });

        // Mettre à jour les données existantes
        if (config('database.default') === 'sqlite') {
            // SQLite - calcul simple (colonne normale)
            \Illuminate\Support\Facades\DB::statement("
                UPDATE availabilities
                SET duration_minutes = (
                    (strftime('%H', end_time) * 60 + strftime('%M', end_time)) -
                    (strftime('%H', start_time) * 60 + strftime('%M', start_time))
                )
                WHERE duration_minutes IS NULL
            ");
        }
        // Pour MySQL/MariaDB/PostgreSQL, la colonne est virtuelle et se calcule automatiquement
        // Pas besoin de mettre à jour manuellement

        \Illuminate\Support\Facades\DB::statement("
            UPDATE availabilities
            SET source = CASE
                WHEN type IN ('available', 'break') THEN 'working_hours'
                WHEN type = 'busy' AND appointment_id IS NOT NULL THEN 'appointment'
                ELSE 'manual'
            END
        ");
    }

    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            if (Schema::hasColumn('availabilities', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('availabilities', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }

            // Revenir à l'ancien enum
            $table->enum('type', ['available', 'busy', 'break', 'holiday', 'sick_leave'])
                ->default('available')->change();

            // Ajouter l'ancienne colonne si elle n'existe pas
            if (!Schema::hasColumn('availabilities', 'generated_from_working_hour')) {
                $table->boolean('generated_from_working_hour')->default(false);
            }
        });
    }
};
