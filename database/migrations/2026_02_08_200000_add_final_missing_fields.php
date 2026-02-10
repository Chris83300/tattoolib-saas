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
        Schema::table('booking_requests', function (Blueprint $table) {
            // Champs pour les annulations et remboursements
            if (!Schema::hasColumn('booking_requests', 'cancelled_by')) {
                $table->string('cancelled_by')->nullable()->after('overage_reason'); // 'client' | 'tattooer'
            }

            if (!Schema::hasColumn('booking_requests', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            }

            if (!Schema::hasColumn('booking_requests', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            }

            if (!Schema::hasColumn('booking_requests', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('cancelled_at');
            }

            if (!Schema::hasColumn('booking_requests', 'refund_percent')) {
                $table->unsignedTinyInteger('refund_percent')->nullable()->after('refund_amount');
            }

            if (!Schema::hasColumn('booking_requests', 'refund_processed_at')) {
                $table->timestamp('refund_processed_at')->nullable()->after('refund_percent');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('is_active');
            }

            if (!Schema::hasColumn('users', 'banned_at')) {
                $table->timestamp('banned_at')->nullable()->after('status');
            }

            if (!Schema::hasColumn('users', 'banned_reason')) {
                $table->string('banned_reason')->nullable()->after('banned_at');
            }

            if (!Schema::hasColumn('users', 'unbanned_at')) {
                $table->timestamp('unbanned_at')->nullable()->after('banned_reason');
            }

            if (!Schema::hasColumn('users', 'unbanned_reason')) {
                $table->string('unbanned_reason')->nullable()->after('unbanned_at');
            }

            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('unbanned_reason');
            }

            if (!Schema::hasColumn('users', 'suspended_reason')) {
                $table->string('suspended_reason')->nullable()->after('suspended_at');
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'actual_end_time')) {
                $table->timestamp('actual_end_time')->nullable()->after('end_datetime');
            }

            if (!Schema::hasColumn('appointments', 'no_show_reported_at')) {
                $table->timestamp('no_show_reported_at')->nullable()->after('actual_end_time');
            }

            if (!Schema::hasColumn('appointments', 'no_show_reason')) {
                $table->string('no_show_reason')->nullable()->after('no_show_reported_at');
            }

            if (!Schema::hasColumn('appointments', 'tattooer_absence_reported_at')) {
                $table->timestamp('tattooer_absence_reported_at')->nullable()->after('no_show_reason');
            }

            if (!Schema::hasColumn('appointments', 'tattooer_absence_reason')) {
                $table->string('tattooer_absence_reason')->nullable()->after('tattooer_absence_reported_at');
            }
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('accounting_transactions', 'stripe_charge_id')) {
                $table->string('stripe_charge_id')->nullable()->after('stripe_payment_intent_id');
            }

            if (!Schema::hasColumn('accounting_transactions', 'receipt_url')) {
                $table->string('receipt_url')->nullable()->after('stripe_charge_id');
            }

            if (!Schema::hasColumn('accounting_transactions', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('receipt_url');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'no_show_count')) {
                $table->unsignedTinyInteger('no_show_count')->default(0)->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suspended_reason')) {
                $table->dropColumn('suspended_reason');
            }
            if (Schema::hasColumn('users', 'suspended_at')) {
                $table->dropColumn('suspended_at');
            }
            if (Schema::hasColumn('users', 'unbanned_reason')) {
                $table->dropColumn('unbanned_reason');
            }
            if (Schema::hasColumn('users', 'unbanned_at')) {
                $table->dropColumn('unbanned_at');
            }
            if (Schema::hasColumn('users', 'banned_reason')) {
                $table->dropColumn('banned_reason');
            }
            if (Schema::hasColumn('users', 'banned_at')) {
                $table->dropColumn('banned_at');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'tattooer_absence_reason')) {
                $table->dropColumn('tattooer_absence_reason');
            }
            if (Schema::hasColumn('appointments', 'tattooer_absence_reported_at')) {
                $table->dropColumn('tattooer_absence_reported_at');
            }
            if (Schema::hasColumn('appointments', 'no_show_reason')) {
                $table->dropColumn('no_show_reason');
            }
            if (Schema::hasColumn('appointments', 'no_show_reported_at')) {
                $table->dropColumn('no_show_reported_at');
            }
            if (Schema::hasColumn('appointments', 'actual_end_time')) {
                $table->dropColumn('actual_end_time');
            }
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('accounting_transactions', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
            if (Schema::hasColumn('accounting_transactions', 'receipt_url')) {
                $table->dropColumn('receipt_url');
            }
            if (Schema::hasColumn('accounting_transactions', 'stripe_charge_id')) {
                $table->dropColumn('stripe_charge_id');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'no_show_count')) {
                $table->dropColumn('no_show_count');
            }
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            if (Schema::hasColumn('booking_requests', 'refund_processed_at')) {
                $table->dropColumn('refund_processed_at');
            }
            if (Schema::hasColumn('booking_requests', 'refund_percent')) {
                $table->dropColumn('refund_percent');
            }
            if (Schema::hasColumn('booking_requests', 'refund_amount')) {
                $table->dropColumn('refund_amount');
            }
            if (Schema::hasColumn('booking_requests', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
            if (Schema::hasColumn('booking_requests', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }
            if (Schema::hasColumn('booking_requests', 'cancelled_by')) {
                $table->dropColumn('cancelled_by');
            }
        });
    }
};
