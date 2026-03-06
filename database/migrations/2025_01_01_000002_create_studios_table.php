<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('FR');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('social_media_links')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('cover_images')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('opening_hours')->nullable();
            $table->json('facilities')->nullable();
            $table->json('settings')->nullable();
            $table->string('siret')->nullable()->unique();
            $table->string('stripe_account_id')->nullable();
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_subscribed')->default(false);
            $table->boolean('stripe_onboarding_complete')->default(false);
            $table->integer('max_artists')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('stripe_customer_id')->nullable()->unique();
            $table->unsignedInteger('total_artists')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->enum('payment_mode', ['artist_direct', 'studio_managed'])->default('artist_direct');
            $table->boolean('uses_accounting_module')->default(false);
            $table->timestamp('payment_mode_changed_at')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_verified']);
            $table->index('city');
            $table->index(['latitude', 'longitude']);
            $table->index('payment_mode');
        });

        // Ajouter la FK users.studio_id → studios maintenant que studios existe
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('studio_id')->references('id')->on('studios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['studio_id']);
        });

        Schema::dropIfExists('studios');
    }
};
