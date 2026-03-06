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
        Schema::table('tattooers', function (Blueprint $table) {
            // Vérifier si la colonne siret existe déjà
            if (!Schema::hasColumn('tattooers', 'siret')) {
                $table->string('siret', 14)->unique()->after('user_id');
            }
            // Vérifier si la colonne has_compliance_badge existe déjà
            if (!Schema::hasColumn('tattooers', 'has_compliance_badge')) {
                $table->boolean('has_compliance_badge')->default(false)->after('is_subscribed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn(['siret', 'has_compliance_badge']);
        });
    }
};
