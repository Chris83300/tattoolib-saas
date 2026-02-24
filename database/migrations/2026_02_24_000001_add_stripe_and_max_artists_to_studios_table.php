<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            if (!Schema::hasColumn('studios', 'stripe_account_id')) {
                $table->string('stripe_account_id')->nullable()->after('siret');
            }
            if (!Schema::hasColumn('studios', 'stripe_onboarding_complete')) {
                $table->boolean('stripe_onboarding_complete')->default(false)->after('stripe_account_id');
            }
            if (!Schema::hasColumn('studios', 'max_artists')) {
                $table->integer('max_artists')->nullable()->after('stripe_onboarding_complete');
            }
        });
    }

    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn(['stripe_account_id', 'stripe_onboarding_complete', 'max_artists']);
        });
    }
};
