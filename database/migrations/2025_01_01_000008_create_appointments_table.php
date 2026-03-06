<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained()->cascadeOnDelete();
            // Polymorphique — bookable_type/bookable_id (tattooer ou piercer)
            $table->string('bookable_type');
            $table->unsignedBigInteger('bookable_id');
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->integer('duration_minutes');
            $table->string('title')->nullable();
            $table->decimal('deposit_amount', 8, 2);
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('remaining_amount', 8, 2)->nullable();
            $table->enum('status', [
                'scheduled', 'confirmed', 'completed', 'cancelled',
                'client_no_show', 'tattooer_no_show', 'disputed',
            ])->default('confirmed');
            $table->timestamp('completed_at')->nullable();
            $table->string('completed_by')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamp('no_show_reported_at')->nullable();
            $table->string('no_show_reason')->nullable();
            $table->timestamp('tattooer_absence_reported_at')->nullable();
            $table->string('tattooer_absence_reason')->nullable();
            $table->timestamp('actual_end_time')->nullable();
            $table->enum('cancelled_by', ['client', 'tattooer', 'admin'])->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->integer('days_before_appointment')->nullable();
            $table->boolean('refunded')->default(false);
            $table->decimal('refund_amount', 8, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->string('stripe_refund_id')->nullable();
            $table->enum('tattooer_confirmation_status', [
                'pending', 'completed', 'client_no_show', 'client_late', 'other_issue',
            ])->nullable();
            $table->text('tattooer_confirmation_note')->nullable();
            $table->timestamp('tattooer_confirmed_at')->nullable();
            $table->boolean('client_reported_issue')->default(false);
            $table->text('client_issue_description')->nullable();
            $table->timestamp('client_reported_at')->nullable();
            $table->boolean('client_dispute_refund')->default(false);
            $table->text('client_dispute_reason')->nullable();
            $table->timestamp('client_dispute_at')->nullable();
            $table->enum('dispute_resolution', ['pending', 'approved', 'rejected', 'partial'])->nullable();
            $table->decimal('dispute_refund_amount', 8, 2)->nullable();
            $table->text('dispute_resolution_note')->nullable();
            $table->timestamp('dispute_resolved_at')->nullable();
            $table->boolean('requires_manual_review')->default(false);
            $table->foreignId('dispute_resolved_by')->nullable()->constrained('users');
            $table->timestamp('care_notification_sent_at')->nullable();
            $table->timestamp('healing_notification_sent_at')->nullable();
            $table->timestamp('review_notification_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bookable_type', 'bookable_id']);
            $table->index(['bookable_type', 'bookable_id', 'start_datetime']);
            $table->index(['client_id', 'start_datetime']);
            $table->index('no_show_reported_at');
            $table->index('tattooer_absence_reported_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
