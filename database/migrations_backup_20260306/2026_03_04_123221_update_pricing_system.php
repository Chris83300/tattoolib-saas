<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. is_beta_tester + beta_registered_at sur users
        if (!Schema::hasColumn('users', 'is_beta_tester')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_beta_tester')->default(false)->after('email_verified_at');
                $table->timestamp('beta_registered_at')->nullable()->after('is_beta_tester');
            });
        }

        // 2. trial_ends_at + is_blocked sur tattooers
        Schema::table('tattooers', function (Blueprint $table) {
            if (!Schema::hasColumn('tattooers', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('is_subscribed');
            }
            if (!Schema::hasColumn('tattooers', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('is_subscribed');
            }
        });

        // 3. trial_ends_at + is_blocked sur piercers
        Schema::table('piercers', function (Blueprint $table) {
            if (!Schema::hasColumn('piercers', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('is_subscribed');
            }
            if (!Schema::hasColumn('piercers', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('is_subscribed');
            }
        });

        // 4. is_blocked sur studios
        if (!Schema::hasColumn('studios', 'is_blocked')) {
            Schema::table('studios', function (Blueprint $table) {
                $table->boolean('is_blocked')->default(false)->after('is_active');
            });
        }

        // 5. Convertir anciens plans FREE → STARTER (current_plan)
        // tattooers.current_plan est un ENUM — étendre avant l'UPDATE
        DB::statement("ALTER TABLE tattooers MODIFY current_plan ENUM('free','pro','starter') DEFAULT 'starter'");
        DB::table('tattooers')->where('current_plan', 'free')->update(['current_plan' => 'starter']);
        // Finaliser l'ENUM sans 'free'
        DB::statement("ALTER TABLE tattooers MODIFY current_plan ENUM('starter','pro') DEFAULT 'starter'");

        // piercers.current_plan est varchar — UPDATE direct
        DB::table('piercers')->where('current_plan', 'free')->update(['current_plan' => 'starter']);

        // 6. Mettre à jour plan dans tattooer_subscriptions free → starter
        if (Schema::hasTable('tattooer_subscriptions')) {
            DB::table('tattooer_subscriptions')
                ->where('plan', 'free')
                ->update(['plan' => 'starter', 'commission_rate' => 7.00]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_beta_tester', 'beta_registered_at']);
        });

        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'is_blocked']);
        });

        Schema::table('piercers', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'is_blocked']);
        });

        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });

        DB::table('tattooers')->where('current_plan', 'starter')->update(['current_plan' => 'free']);
        DB::table('piercers')->where('current_plan', 'starter')->update(['current_plan' => 'free']);
    }
};
