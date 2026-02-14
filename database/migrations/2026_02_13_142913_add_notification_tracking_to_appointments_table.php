<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('care_notification_sent_at')->nullable();
            $table->timestamp('healing_notification_sent_at')->nullable();
            $table->timestamp('review_notification_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('care_notification_sent_at');
            $table->dropColumn('healing_notification_sent_at');
            $table->dropColumn('review_notification_sent_at');
        });
    }
};
