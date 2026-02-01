<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Standardisation des colonnes datetime de la table appointments
     * pour correspondre au standard de calendar_events
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // 1. Renommer start_time → start_datetime
            $table->renameColumn('start_time', 'start_datetime');

            // 2. Renommer end_time → end_datetime
            $table->renameColumn('end_time', 'end_datetime');

            // 3. Supprimer appointment_date (redondant avec start_datetime)
            $table->dropColumn('appointment_date');

            // 4. Garder duration_minutes comme champ calculé manuellement pour l'instant
            // (peut être supprimé plus tard si on veut utiliser l'accessor)
        });
    }

    /**
     * Rollback : restaurer l'ancienne structure
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Restaurer les anciens noms
            $table->renameColumn('start_datetime', 'start_time');
            $table->renameColumn('end_datetime', 'end_time');

            // Recréer appointment_date
            $table->date('appointment_date')->nullable()->after('booking_request_id');
        });
    }
};
