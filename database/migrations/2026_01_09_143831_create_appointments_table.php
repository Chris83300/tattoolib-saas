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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->foreignId('booking_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('tattooer_id')->constrained('tattooers')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            // Date/heure
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->date('appointment_date')->nullable();
            $table->integer('duration_minutes');

            // Montants
            $table->decimal('deposit_amount', 8, 2);
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('remaining_amount', 8, 2)->nullable();

            // Statuts
            $table->enum('status', [
                'confirmed',
                'completed',
                'cancelled',
                'client_no_show',
                'tattooer_no_show',
                'disputed'
            ])->default('confirmed');

            // Annulation
            $table->enum('cancelled_by', ['client', 'tattooer', 'admin'])->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->integer('days_before_appointment')->nullable();

            // Remboursement
            $table->boolean('refunded')->default(false);
            $table->decimal('refund_amount', 8, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->string('stripe_refund_id')->nullable();

            // Confirmation post-RDV
            $table->enum('tattooer_confirmation_status', [
                'pending', 'completed', 'client_no_show', 'client_late', 'other_issue'
            ])->nullable();
            $table->text('tattooer_confirmation_note')->nullable();
            $table->timestamp('tattooer_confirmed_at')->nullable();

            // Signalement client
            $table->boolean('client_reported_issue')->default(false);
            $table->text('client_issue_description')->nullable();
            $table->timestamp('client_reported_at')->nullable();


            // Résolution
            $table->boolean('requires_manual_review')->default(false);

            // Index
            $table->index(['tattooer_id', 'start_time']);
            $table->index(['client_id', 'start_time']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
