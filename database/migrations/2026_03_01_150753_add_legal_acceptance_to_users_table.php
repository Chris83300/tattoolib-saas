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
            $table->timestamp('cgu_accepted_at')->nullable()->after('email_verified_at');
            $table->timestamp('privacy_accepted_at')->nullable()->after('cgu_accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cgu_accepted_at', 'privacy_accepted_at']);
        });
    }
};
