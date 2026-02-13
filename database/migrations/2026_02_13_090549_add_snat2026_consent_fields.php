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
        Schema::table('client_consent_forms', function (Blueprint $table) {
            // SECTION IDENTITÉ CLIENT (pré-rempli BDD)
            if (!Schema::hasColumn('client_consent_forms', 'client_full_name')) {
                $table->string('client_full_name', 255)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'client_birth_date')) {
                $table->date('client_birth_date')->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'client_address')) {
                $table->string('client_address', 500)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'client_phone')) {
                $table->string('client_phone', 20)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'client_email')) {
                $table->string('client_email', 255)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'client_id_type')) {
                $table->string('client_id_type', 50)->nullable(); // cni / passeport / titre_sejour
            }
            if (!Schema::hasColumn('client_consent_forms', 'client_id_number')) {
                $table->string('client_id_number', 50)->nullable();
            }

            // SECTION MINEUR
            if (!Schema::hasColumn('client_consent_forms', 'is_minor')) {
                $table->boolean('is_minor')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'parent_name')) {
                $table->string('parent_name', 255)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'parent_relation')) {
                $table->string('parent_relation', 50)->nullable(); // pere / mere / tuteur
            }
            if (!Schema::hasColumn('client_consent_forms', 'parent_id_number')) {
                $table->string('parent_id_number', 50)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'parent_signature_data')) {
                $table->longText('parent_signature_data')->nullable(); // base64 canvas
            }

            // SECTION ACTE (pré-rempli BookingRequest)
            if (!Schema::hasColumn('client_consent_forms', 'act_type')) {
                $table->string('act_type', 50)->default('tatouage');
            }
            if (!Schema::hasColumn('client_consent_forms', 'body_zone')) {
                $table->string('body_zone', 255)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'act_description')) {
                $table->text('act_description')->nullable();
            }

            // SECTION QUESTIONNAIRE MÉDICAL
            if (!Schema::hasColumn('client_consent_forms', 'medical_allergies')) {
                $table->boolean('medical_allergies')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_allergies_detail')) {
                $table->text('medical_allergies_detail')->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_anticoagulant')) {
                $table->boolean('medical_anticoagulant')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_diabetes')) {
                $table->boolean('medical_diabetes')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_cicatrisation')) {
                $table->boolean('medical_cicatrisation')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_skin_disease')) {
                $table->boolean('medical_skin_disease')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_skin_disease_detail')) {
                $table->text('medical_skin_disease_detail')->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_vih_hepatite')) {
                $table->boolean('medical_vih_hepatite')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_pregnant')) {
                $table->boolean('medical_pregnant')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_roaccutane')) {
                $table->boolean('medical_roaccutane')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_cheloide')) {
                $table->boolean('medical_cheloide')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'medical_other')) {
                $table->text('medical_other')->nullable();
            }

            // SECTION CONFIRMATIONS (checkboxes obligatoires, NON pré-cochées)
            if (!Schema::hasColumn('client_consent_forms', 'confirm_medical_sincere')) {
                $table->boolean('confirm_medical_sincere')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'confirm_risks_informed')) {
                $table->boolean('confirm_risks_informed')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'confirm_info_sheet_read')) {
                $table->boolean('confirm_info_sheet_read')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'confirm_aftercare_received')) {
                $table->boolean('confirm_aftercare_received')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'confirm_not_intoxicated')) {
                $table->boolean('confirm_not_intoxicated')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'confirm_over_18_or_authorized')) {
                $table->boolean('confirm_over_18_or_authorized')->default(false);
            }
            if (!Schema::hasColumn('client_consent_forms', 'confirm_rgpd')) {
                $table->boolean('confirm_rgpd')->default(false);
            }

            // SECTION FINANCIÈRE (pré-rempli BookingRequest)
            if (!Schema::hasColumn('client_consent_forms', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'retouche_included')) {
                $table->boolean('retouche_included')->default(false);
            }

            // SECTION IMAGE
            if (!Schema::hasColumn('client_consent_forms', 'image_authorization')) {
                $table->boolean('image_authorization')->nullable(); // true/false/null
            }

            // SECTION SIGNATURE
            if (!Schema::hasColumn('client_consent_forms', 'signature_data')) {
                $table->longText('signature_data')->nullable(); // base64 canvas
            }
            if (!Schema::hasColumn('client_consent_forms', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'signed_ip')) {
                $table->string('signed_ip', 45)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'signed_user_agent')) {
                $table->string('signed_user_agent', 500)->nullable();
            }
            if (!Schema::hasColumn('client_consent_forms', 'handwritten_mention')) {
                $table->string('handwritten_mention', 255)->nullable(); // "Lu et approuvé, bon pour consentement"
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_consent_forms', function (Blueprint $table) {
            // Suppression des colonnes dans l'ordre inverse
            $columns = [
                'handwritten_mention', 'signed_user_agent', 'signed_ip', 'signed_at', 'signature_data',
                'image_authorization', 'retouche_included', 'deposit_amount', 'total_price',
                'confirm_rgpd', 'confirm_over_18_or_authorized', 'confirm_not_intoxicated',
                'confirm_aftercare_received', 'confirm_info_sheet_read', 'confirm_risks_informed',
                'confirm_medical_sincere', 'medical_other', 'medical_cheloide', 'medical_roaccutane',
                'medical_pregnant', 'medical_vih_hepatite', 'medical_skin_disease_detail',
                'medical_skin_disease', 'medical_cicatrisation', 'medical_diabetes',
                'medical_anticoagulant', 'medical_allergies_detail', 'medical_allergies',
                'act_description', 'body_zone', 'act_type', 'parent_signature_data',
                'parent_id_number', 'parent_relation', 'parent_name', 'is_minor',
                'client_id_number', 'client_id_type', 'client_email', 'client_phone',
                'client_address', 'client_birth_date', 'client_full_name'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('client_consent_forms', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
