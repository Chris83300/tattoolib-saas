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
        Schema::table('appointments', function (Blueprint $table) {
            // Seulement les colonnes qui n'existent PAS déjà
            if (!Schema::hasColumn('appointments', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('appointments', 'completed_by')) {
                $table->string('completed_by')->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('appointments', 'completion_notes')) {
                $table->text('completion_notes')->nullable()->after('completed_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('appointments', 'completed_by')) {
                $table->dropColumn('completed_by');
            }
            if (Schema::hasColumn('appointments', 'completion_notes')) {
                $table->dropColumn('completion_notes');
            }
        });
    }
};
