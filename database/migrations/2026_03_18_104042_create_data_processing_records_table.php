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
        Schema::create('data_processing_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('purpose');
            $table->string('legal_basis');
            $table->json('data_categories');
            $table->json('data_subjects');
            $table->json('recipients')->nullable();
            $table->boolean('transfers_outside_eu')->default(false);
            $table->string('retention_period');
            $table->text('security_measures')->nullable();
            $table->boolean('requires_dpia')->default(false);
            $table->text('dpia_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_processing_records');
    }
};
