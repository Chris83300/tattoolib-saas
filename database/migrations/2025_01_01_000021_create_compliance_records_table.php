<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_records', function (Blueprint $table) {
            $table->id();
            // Polymorphique — compliant_type/compliant_id (tattooer, piercer, studio_artist)
            $table->string('compliant_type');
            $table->unsignedBigInteger('compliant_id');
            $table->enum('certification_type', ['hygiene_salubrite', 'certibiocide', 'declaration_ars']);
            $table->string('certificate_number')->nullable();
            $table->string('training_organization')->nullable();
            $table->date('obtained_at');
            $table->date('expires_at')->nullable();
            $table->string('certificate_file_path', 500)->nullable();
            $table->string('ars_proof_file_path', 500)->nullable();
            $table->enum('status', ['valid', 'expiring_soon', 'expired', 'missing', 'pending'])->default('missing');
            $table->string('biocide_type', 50)->nullable();
            $table->boolean('is_decision_maker')->default(false);
            $table->string('ars_region', 100)->nullable();
            $table->string('ars_number', 100)->nullable();
            $table->timestamp('notification_90d_sent_at')->nullable();
            $table->timestamp('notification_30d_sent_at')->nullable();
            $table->timestamp('notification_expired_sent_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['compliant_type', 'compliant_id', 'certification_type'], 'unique_certification_per_artist');
            $table->index(['compliant_type', 'compliant_id'], 'compliant_index');
            $table->index('status');
            $table->index('expires_at');
            $table->index('certification_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_records');
    }
};
