<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['client_care_sheets', 'client_consent_forms', 'traceability_records'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'studio_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->foreignId('studio_id')->nullable()->after('id')->constrained()->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['client_care_sheets', 'client_consent_forms', 'traceability_records'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'studio_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['studio_id']);
                    $t->dropColumn('studio_id');
                });
            }
        }
    }
};
