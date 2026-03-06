<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Champs de contestation
            $table->boolean('client_dispute_refund')->default(false)->after('client_reported_at');
            $table->text('client_dispute_reason')->nullable()->after('client_dispute_refund');
            $table->timestamp('client_dispute_at')->nullable()->after('client_dispute_reason');

            // Résolution admin
            $table->enum('dispute_resolution', ['pending', 'approved', 'rejected', 'partial'])->nullable()->after('client_dispute_at');
            $table->decimal('dispute_refund_amount', 8, 2)->nullable()->after('dispute_resolution');
            $table->text('dispute_resolution_note')->nullable()->after('dispute_refund_amount');
            $table->timestamp('dispute_resolved_at')->nullable()->after('dispute_resolution_note');
            $table->foreignId('dispute_resolved_by')->nullable()->constrained('users')->after('dispute_resolved_at');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'client_dispute_refund',
                'client_dispute_reason',
                'client_dispute_at',
                'dispute_resolution',
                'dispute_refund_amount',
                'dispute_resolution_note',
                'dispute_resolved_at',
                'dispute_resolved_by',
            ]);
        });
    }
};
