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
        Schema::create('piercers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('siret', 14)->unique(); // SIRET vérifié (obligatoire)
            $table->string('name'); // Nom réel entreprise (pour ARS)
            $table->string('slug')->unique(); // Slug URL profil public
            $table->enum('specialization', ['pierceur', 'bodemodeur', 'pierceur_bodemodeur'])->nullable(); // Spécialisation
            $table->text('bio')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('subscription_plan', 20)->default('free'); // 'free' | 'pro'
            $table->boolean('is_subscribed')->default(false);
            $table->string('stripe_connect_id')->nullable();
            $table->boolean('has_compliance_badge')->default(false);
            $table->timestamp('admin_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piercers');
    }
};
