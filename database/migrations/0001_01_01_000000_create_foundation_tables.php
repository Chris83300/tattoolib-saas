<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // users.role_id FK ajoutée dans create_permissions_tables (migration 04)
        // users.studio_id FK ajoutée dans create_studios_table (migration 05)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('pseudo')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedBigInteger('role_id')->nullable()->index(); // FK ajoutée dans permissions migration
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('timezone')->default('Europe/Paris');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_beta_tester')->default(false);
            $table->timestamp('beta_registered_at')->nullable();
            $table->timestamp('cgu_accepted_at')->nullable();
            $table->timestamp('privacy_accepted_at')->nullable();
            $table->string('password');
            $table->string('role')->default('client');
            $table->string('status')->default('active');
            $table->timestamp('banned_at')->nullable();
            $table->string('banned_reason')->nullable();
            $table->timestamp('unbanned_at')->nullable();
            $table->string('unbanned_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->string('suspended_reason')->nullable();
            $table->unsignedBigInteger('studio_id')->nullable(); // FK ajoutée dans studios migration
            $table->boolean('is_studio_owner')->default(false);
            $table->boolean('is_studio_artist')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->rememberToken();
            $table->string('fcm_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            // Cashier columns
            $table->string('stripe_id')->nullable();
            $table->string('pm_name')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->index('stripe_id');
            $table->index(['studio_id', 'is_studio_owner']);
            $table->index('status');
            $table->index('banned_at');
            $table->index('suspended_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
