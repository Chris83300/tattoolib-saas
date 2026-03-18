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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cgu_version_accepted')->nullable()->after('cgu_accepted_at')
                  ->comment('Version des CGU acceptée, ex: 1.0');
            $table->string('privacy_version_accepted')->nullable()->after('privacy_accepted_at');
            $table->string('consent_ip', 45)->nullable()->after('privacy_version_accepted')
                  ->comment('IP lors de l\'acceptation des CGU (IPv4 ou IPv6)');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cgu_version_accepted', 'privacy_version_accepted', 'consent_ip']);
        });
    }
};
