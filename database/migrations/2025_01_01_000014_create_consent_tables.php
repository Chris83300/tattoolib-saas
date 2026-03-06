<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Note: consents (ancienne table v1) supprimée — remplacée par client_consent_forms (SNAT2026)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tattooer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->unique()->constrained()->cascadeOnDelete();
            // Identité client
            $table->string('full_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('id_document_type')->nullable();
            $table->string('id_document_number')->nullable();
            $table->date('id_document_expiry')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_adult')->default(true);
            $table->date('consent_date')->nullable();
            $table->time('consent_time')->nullable();
            // Santé (ancienne version)
            $table->boolean('has_allergies')->default(false);
            $table->string('allergies_details')->nullable();
            $table->boolean('has_skin_conditions')->default(false);
            $table->string('skin_conditions_details')->nullable();
            $table->boolean('has_blood_disorders')->default(false);
            $table->string('blood_disorders_details')->nullable();
            $table->boolean('has_diabetes')->default(false);
            $table->boolean('has_heart_conditions')->default(false);
            $table->boolean('is_pregnant')->default(false);
            $table->boolean('is_breastfeeding')->default(false);
            $table->boolean('taking_medications')->default(false);
            $table->string('medications_details')->nullable();
            $table->boolean('has_recent_surgery')->default(false);
            $table->string('recent_surgery_details')->nullable();
            $table->boolean('has_existing_tattoos')->default(false);
            $table->string('existing_tattoos_location')->nullable();
            // Consentements (ancienne version)
            $table->boolean('consents_to_tattoo')->default(false);
            $table->boolean('understands_risks')->default(false);
            $table->boolean('understands_aftercare')->default(false);
            $table->boolean('consents_to_photos')->default(false);
            $table->boolean('consents_to_data_processing')->default(false);
            $table->json('id_document_photos')->nullable();
            $table->json('consent_signature')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('status', ['draft', 'signed', 'verified', 'expired'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            // Champs SNAT2026 — identité complémentaire
            $table->string('client_full_name')->nullable();
            $table->date('client_birth_date')->nullable();
            $table->string('client_address', 500)->nullable();
            $table->string('client_phone', 20)->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_id_type', 50)->nullable();
            $table->string('client_id_number', 50)->nullable();
            $table->boolean('is_minor')->default(false);
            $table->string('parent_name')->nullable();
            $table->string('parent_relation', 50)->nullable();
            $table->string('parent_id_number', 50)->nullable();
            $table->longText('parent_signature_data')->nullable();
            // Acte
            $table->string('act_type', 50)->default('tatouage');
            $table->string('body_zone')->nullable();
            $table->text('act_description')->nullable();
            // Santé SNAT2026
            $table->boolean('medical_allergies')->default(false);
            $table->text('medical_allergies_detail')->nullable();
            $table->boolean('medical_anticoagulant')->default(false);
            $table->boolean('medical_diabetes')->default(false);
            $table->boolean('medical_cicatrisation')->default(false);
            $table->boolean('medical_skin_disease')->default(false);
            $table->text('medical_skin_disease_detail')->nullable();
            $table->boolean('medical_vih_hepatite')->default(false);
            $table->boolean('medical_pregnant')->default(false);
            $table->boolean('medical_roaccutane')->default(false);
            $table->boolean('medical_cheloide')->default(false);
            $table->text('medical_other')->nullable();
            // Confirmations SNAT2026
            $table->boolean('confirm_medical_sincere')->default(false);
            $table->boolean('confirm_risks_informed')->default(false);
            $table->boolean('confirm_info_sheet_read')->default(false);
            $table->boolean('confirm_aftercare_received')->default(false);
            $table->boolean('confirm_not_intoxicated')->default(false);
            $table->boolean('confirm_over_18_or_authorized')->default(false);
            $table->boolean('confirm_rgpd')->default(false);
            // Tarification
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->boolean('retouche_included')->default(false);
            $table->tinyInteger('image_authorization')->nullable();
            // Signature
            $table->longText('signature_data')->nullable();
            $table->string('signed_ip', 45)->nullable();
            $table->string('signed_user_agent', 500)->nullable();
            $table->string('handwritten_mention')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'appointment_id']);
            $table->index(['tattooer_id', 'status']);
            $table->index('booking_request_id');
        });

        Schema::create('parental_consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_consent_form_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tattooer_id')->constrained()->cascadeOnDelete();
            $table->string('parent_full_name');
            $table->string('parent_relationship');
            $table->string('parent_id_document_type');
            $table->string('parent_id_document_number');
            $table->date('parent_id_document_expiry');
            $table->string('parent_phone');
            $table->string('parent_email');
            $table->text('parent_address');
            $table->boolean('parent_consents_to_tattoo')->default(false);
            $table->boolean('parent_understands_risks')->default(false);
            $table->boolean('parent_will_supervise_aftercare')->default(false);
            $table->boolean('parent_consents_to_emergency_treatment')->default(false);
            $table->json('parent_id_document_photos')->nullable();
            $table->json('parent_signature')->nullable();
            $table->string('parent_ip_address')->nullable();
            $table->string('parent_user_agent')->nullable();
            $table->enum('status', ['draft', 'signed', 'verified', 'expired'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['client_consent_form_id', 'status']);
        });

        Schema::create('client_care_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tattooer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->text('tattoo_description');
            $table->string('tattoo_location');
            $table->string('tattoo_size');
            $table->text('technique_used')->nullable();
            $table->text('ink_colors_used')->nullable();
            $table->text('allergies')->nullable();
            $table->text('skin_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->boolean('has_diabetes')->default(false);
            $table->boolean('has_blood_disorders')->default(false);
            $table->boolean('is_pregnant')->default(false);
            $table->text('immediate_care_instructions');
            $table->text('products_used');
            $table->text('bandage_type');
            $table->datetime('bandage_removal_time');
            $table->text('washing_instructions');
            $table->text('moisturizing_instructions');
            $table->text('activity_restrictions');
            $table->text('sun_exposure_warnings');
            $table->date('healing_estimated_date');
            $table->date('first_touchup_date')->nullable();
            $table->text('healing_notes')->nullable();
            $table->enum('healing_status', ['in_progress', 'healed', 'complicated', 'touchup_needed'])->default('in_progress');
            $table->json('healing_photos')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'tattooer_id']);
            $table->index('appointment_id');
        });

        // Ajouter la FK traceability_records.client_consent_form_id maintenant que client_consent_forms existe
        Schema::table('traceability_records', function (Blueprint $table) {
            $table->foreign('client_consent_form_id')
                  ->references('id')->on('client_consent_forms')
                  ->cascadeOnDelete();
            $table->index('client_consent_form_id');
        });
    }

    public function down(): void
    {
        Schema::table('traceability_records', function (Blueprint $table) {
            $table->dropForeign(['client_consent_form_id']);
        });

        Schema::dropIfExists('client_care_sheets');
        Schema::dropIfExists('parental_consent_forms');
        Schema::dropIfExists('client_consent_forms');
    }
};
