<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studio_artists', function (Blueprint $table) {
            if (!Schema::hasColumn('studio_artists', 'artisan_type')) {
                $table->string('artisan_type')->default('tattooer')->after('user_id');
            }
            if (!Schema::hasColumn('studio_artists', 'role')) {
                $table->string('role')->default('artist')->after('artisan_type');
            }
            if (!Schema::hasColumn('studio_artists', 'invitation_token')) {
                $table->string('invitation_token')->nullable()->unique()->after('role');
            }
            if (!Schema::hasColumn('studio_artists', 'invitation_email')) {
                $table->string('invitation_email')->nullable()->after('invitation_token');
            }
            if (!Schema::hasColumn('studio_artists', 'invited_at')) {
                $table->timestamp('invited_at')->nullable()->after('invitation_email');
            }
            if (!Schema::hasColumn('studio_artists', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->nullable()->after('invited_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('studio_artists', function (Blueprint $table) {
            $table->dropColumn(['artisan_type', 'role', 'invitation_token', 'invitation_email', 'invited_at', 'commission_rate']);
        });
    }
};
