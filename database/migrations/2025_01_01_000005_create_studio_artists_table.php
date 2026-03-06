<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studio_artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('artisan_type')->default('tattooer');
            $table->string('role')->default('artist');
            $table->string('invitation_token')->nullable()->unique();
            $table->string('invitation_email')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->string('artist_name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->string('stripe_connect_account_id')->nullable()->unique();
            $table->enum('stripe_connect_status', ['not_connected', 'onboarding', 'active', 'inactive', 'reactivating'])->default('not_connected');
            $table->timestamp('stripe_connect_activated_at')->nullable();
            $table->timestamp('stripe_connect_last_transaction_at')->nullable();
            $table->timestamp('stripe_connect_deactivated_at')->nullable();
            $table->boolean('has_accepted_payment_terms')->default(false);
            $table->timestamp('payment_terms_accepted_at')->nullable();
            $table->boolean('is_decision_maker')->default(false);
            $table->enum('compliance_status', ['non_compliant', 'compliant', 'expiring_soon'])->default('non_compliant');
            $table->timestamp('last_compliance_check_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave', 'deleted'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->date('joined_at');
            $table->date('left_at')->nullable();
            $table->json('working_schedule')->nullable();
            $table->unsignedInteger('total_appointments')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0.00);
            $table->boolean('credentials_managed_by_studio')->default(true);
            $table->boolean('siret_verified')->default(false);
            $table->boolean('stripe_onboarding_complete')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['studio_id', 'user_id']);
            $table->index(['studio_id', 'status']);
            $table->index('compliance_status');
            $table->index('stripe_connect_status');
            $table->index('stripe_connect_last_transaction_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studio_artists');
    }
};
