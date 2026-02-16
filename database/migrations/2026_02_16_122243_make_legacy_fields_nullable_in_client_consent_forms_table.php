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
            $table->string('address')->nullable()->change();
            $table->date('consent_date')->nullable()->change();
            $table->time('consent_time')->nullable()->change();
            $table->string('allergies_details')->nullable()->change();
            $table->string('skin_conditions_details')->nullable()->change();
            $table->string('blood_disorders_details')->nullable()->change();
            $table->string('medications_details')->nullable()->change();
            $table->string('recent_surgery_details')->nullable()->change();
            $table->string('existing_tattoos_location')->nullable()->change();
            $table->json('id_document_photos')->nullable()->change();
            $table->json('consent_signature')->nullable()->change();
            $table->string('ip_address')->nullable()->change();
            $table->string('user_agent')->nullable()->change();
            $table->string('handwritten_mention')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_consent_forms', function (Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->date('consent_date')->nullable(false)->change();
            $table->time('consent_time')->nullable(false)->change();
            $table->string('allergies_details')->nullable(false)->change();
            $table->string('skin_conditions_details')->nullable(false)->change();
            $table->string('blood_disorders_details')->nullable(false)->change();
            $table->string('medications_details')->nullable(false)->change();
            $table->string('recent_surgery_details')->nullable(false)->change();
            $table->string('existing_tattoos_location')->nullable(false)->change();
            $table->json('id_document_photos')->nullable(false)->change();
            $table->json('consent_signature')->nullable(false)->change();
            $table->string('ip_address')->nullable(false)->change();
            $table->string('user_agent')->nullable(false)->change();
            $table->string('handwritten_mention')->nullable(false)->change();
        });
    }
};
