<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // client_consent_form_id FK ajoutée dans create_consent_tables (migration 14)
        Schema::create('traceability_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tattooer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('client_consent_form_id')->nullable(); // FK ajoutée dans consent migration
            $table->date('session_date')->nullable();
            $table->date('procedure_date');
            $table->time('procedure_start_time');
            $table->time('procedure_end_time');
            $table->json('sterile_equipment');
            $table->json('aftercare_products');
            $table->string('room_number')->nullable();
            $table->string('autoclave_batch_number')->nullable();
            $table->date('autoclave_test_date')->nullable();
            $table->json('procedure_photos')->nullable();
            $table->json('workstation_photos')->nullable();
            $table->text('procedure_notes')->nullable();
            $table->text('client_condition_notes')->nullable();
            $table->text('equipment_notes')->nullable();
            $table->boolean('client_verified_photos')->default(false);
            $table->boolean('tattooer_verified_traceability')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->string('tattoo_description', 500)->nullable();
            $table->string('body_zone', 100)->nullable();
            $table->timestamps();

            $table->index(['tattooer_id', 'procedure_date']);
            $table->index('appointment_id');
            $table->index('client_id');
        });

        Schema::create('traceability_needles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traceability_record_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('size');
            $table->integer('quantity')->default(1);
            $table->string('lot_number');
            $table->date('expiration_date');
            $table->string('photo_url')->nullable();
            $table->timestamps();

            $table->index('lot_number');
        });

        Schema::create('traceability_inks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traceability_record_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('color');
            $table->string('lot_number');
            $table->date('expiration_date');
            $table->integer('quantity_ml')->default(0);
            $table->string('photo_url')->nullable();
            $table->timestamps();

            $table->index('lot_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traceability_inks');
        Schema::dropIfExists('traceability_needles');
        Schema::dropIfExists('traceability_records');
    }
};
