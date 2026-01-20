<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convertit 4 tables vers polymorphic relations :
     * - booking_requests : tattooer_id → bookable (Tattooer|StudioArtist)
     * - appointments : tattooer_id → bookable (Tattooer|StudioArtist)
     * - availabilities : tattooer_id → owner (Tattooer|StudioArtist)
     * - working_hours : tattooer_id → owner (Tattooer|StudioArtist)
     */
    public function up(): void
    {
        // ========================================
        // 1. BOOKING_REQUESTS
        // ========================================

        Schema::table('booking_requests', function (Blueprint $table) {
            // Ajouter colonne bookable_type AVANT de renommer
            $table->string('bookable_type')->after('client_id')->nullable();
        });

        // Migrer données existantes
        DB::table('booking_requests')->update([
            'bookable_type' => 'App\\Models\\Tattooer'
        ]);

        Schema::table('booking_requests', function (Blueprint $table) {
            // Supprimer foreign key
            $table->dropForeign(['tattooer_id']);

            // Renommer colonne
            $table->renameColumn('tattooer_id', 'bookable_id');

            // Rendre bookable_type NOT NULL
            $table->string('bookable_type')->nullable(false)->change();

            // Ajouter index composite
            $table->index(['bookable_type', 'bookable_id'], 'booking_requests_bookable_index');
        });

        // ========================================
        // 2. APPOINTMENTS
        // ========================================

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('bookable_type')->after('booking_request_id')->nullable();
        });

        DB::table('appointments')->update([
            'bookable_type' => 'App\\Models\\Tattooer'
        ]);

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['tattooer_id']);
            $table->renameColumn('tattooer_id', 'bookable_id');
            $table->string('bookable_type')->nullable(false)->change();
            $table->index(['bookable_type', 'bookable_id'], 'appointments_bookable_index');
        });

        // ========================================
        // 3. AVAILABILITIES
        // ========================================

        Schema::table('availabilities', function (Blueprint $table) {
            $table->string('owner_type')->after('id')->nullable();
        });

        DB::table('availabilities')->update([
            'owner_type' => 'App\\Models\\Tattooer'
        ]);

        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropForeign(['tattooer_id']);
            $table->renameColumn('tattooer_id', 'owner_id');
            $table->string('owner_type')->nullable(false)->change();
            $table->index(['owner_type', 'owner_id'], 'availabilities_owner_index');
        });

        // ========================================
        // 4. WORKING_HOURS
        // ========================================

        Schema::table('working_hours', function (Blueprint $table) {
            $table->string('owner_type')->after('id')->nullable();
        });

        DB::table('working_hours')->update([
            'owner_type' => 'App\\Models\\Tattooer'
        ]);

        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropForeign(['tattooer_id']);
            $table->renameColumn('tattooer_id', 'owner_id');
            $table->string('owner_type')->nullable(false)->change();
            $table->index(['owner_type', 'owner_id'], 'working_hours_owner_index');
        });
    }

    /**
     * Rollback : revenir à tattooer_id
     */
    public function down(): void
    {
        // booking_requests
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropIndex('booking_requests_bookable_index');
            $table->renameColumn('bookable_id', 'tattooer_id');
            $table->dropColumn('bookable_type');
            $table->foreignId('tattooer_id')->constrained('tattooers');
        });

        // appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('appointments_bookable_index');
            $table->renameColumn('bookable_id', 'tattooer_id');
            $table->dropColumn('bookable_type');
            $table->foreignId('tattooer_id')->constrained('tattooers');
        });

        // availabilities
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropIndex('availabilities_owner_index');
            $table->renameColumn('owner_id', 'tattooer_id');
            $table->dropColumn('owner_type');
            $table->foreignId('tattooer_id')->constrained('tattooers');
        });

        // working_hours
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropIndex('working_hours_owner_index');
            $table->renameColumn('owner_id', 'tattooer_id');
            $table->dropColumn('owner_type');
            $table->foreignId('tattooer_id')->constrained('tattooers');
        });
    }
};
