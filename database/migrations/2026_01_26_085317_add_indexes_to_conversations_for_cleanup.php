<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->index('expiry_type');
            $table->index('expires_at');
            $table->index('is_expired');
            $table->index(['expiry_type', 'expires_at'], 'expiry_lookup');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex('conversations_expiry_type_index');
            $table->dropIndex('conversations_expires_at_index');
            $table->dropIndex('conversations_is_expired_index');
            $table->dropIndex('expiry_lookup');
        });
    }
};
