<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute un tracker JSON pour suivre les modifications PAR dessin.
     *
     * Avant : current_design_modifications_count = compteur GLOBAL
     * Après : design_modifications_tracker = {"1": 0, "2": 1}
     *         = dessin #1 a 0 modifs utilisées, dessin #2 a 1 modif utilisée
     *
     * On NE SUPPRIME PAS les anciennes colonnes pour compatibilité.
     */
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_requests', 'design_modifications_tracker')) {
                $table->json('design_modifications_tracker')->nullable()
                    ->after('designs_sent_count')
                    ->comment('JSON: {"1": 0, "2": 1} = modifs utilisées par dessin');
            }
        });

        // Migrer les données existantes vers le tracker
        \DB::table('booking_requests')
            ->where('designs_sent_count', '>', 0)
            ->orderBy('id')
            ->each(function ($booking) {
                $tracker = [];
                for ($i = 1; $i <= $booking->designs_sent_count; $i++) {
                    // Mettre les modifs existantes sur le dernier dessin
                    $tracker[(string) $i] = ($i === $booking->designs_sent_count)
                        ? $booking->current_design_modifications_count
                        : 0;
                }
                \DB::table('booking_requests')
                    ->where('id', $booking->id)
                    ->update(['design_modifications_tracker' => json_encode($tracker)]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn('design_modifications_tracker');
        });
    }
};
