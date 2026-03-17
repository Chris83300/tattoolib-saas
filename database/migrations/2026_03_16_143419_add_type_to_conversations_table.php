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
        Schema::table('conversations', function (Blueprint $table) {
            $table->enum('type', ['booking', 'support', 'admin_private'])
                  ->default('booking')
                  ->after('id');

            $table->foreignId('admin_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['admin_user_id']);
            $table->dropColumn(['type', 'admin_user_id']);
        });
    }
};
