<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter la colonne tattooer_notes manquante
     */
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_requests', 'tattooer_notes')) {
                $table->text('tattooer_notes')->nullable()
                    ->after('description')
                    ->comment('Message personnalisé du tattooer au client');
            }
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            if (Schema::hasColumn('booking_requests', 'tattooer_notes')) {
                $table->dropColumn('tattooer_notes');
            }
        });
    }
};
