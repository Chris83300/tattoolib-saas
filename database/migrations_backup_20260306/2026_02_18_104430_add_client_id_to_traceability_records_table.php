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
        Schema::table('traceability_records', function (Blueprint $table) {
            if (!Schema::hasColumn('traceability_records', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
                $table->index('client_id');
            }
            if (!Schema::hasColumn('traceability_records', 'tattooer_id')) {
                $table->unsignedBigInteger('tattooer_id')->nullable()->after('client_id');
            }
            if (!Schema::hasColumn('traceability_records', 'session_date')) {
                $table->date('session_date')->nullable()->after('tattooer_id');
            }
            if (!Schema::hasColumn('traceability_records', 'tattoo_description')) {
                $table->string('tattoo_description', 500)->nullable();
            }
            if (!Schema::hasColumn('traceability_records', 'body_zone')) {
                $table->string('body_zone', 100)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traceability_records', function (Blueprint $table) {
            if (Schema::hasColumn('traceability_records', 'client_id')) {
                $table->dropIndex(['client_id']);
                $table->dropColumn('client_id');
            }
            if (Schema::hasColumn('traceability_records', 'tattooer_id')) {
                $table->dropColumn('tattooer_id');
            }
            if (Schema::hasColumn('traceability_records', 'session_date')) {
                $table->dropColumn('session_date');
            }
            if (Schema::hasColumn('traceability_records', 'tattoo_description')) {
                $table->dropColumn('tattoo_description');
            }
            if (Schema::hasColumn('traceability_records', 'body_zone')) {
                $table->dropColumn('body_zone');
            }
        });
    }
};
