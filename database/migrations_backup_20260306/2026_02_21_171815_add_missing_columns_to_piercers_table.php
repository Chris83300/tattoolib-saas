<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('piercers', function (Blueprint $table) {
            // Conformité (miroir tattooers)
            if (!Schema::hasColumn('piercers', 'is_decision_maker')) {
                $table->boolean('is_decision_maker')->default(false)->after('siret_verified');
            }
            if (!Schema::hasColumn('piercers', 'compliance_status')) {
                $table->string('compliance_status', 50)->nullable()->after('is_decision_maker');
            }
            if (!Schema::hasColumn('piercers', 'last_compliance_check_at')) {
                $table->timestamp('last_compliance_check_at')->nullable()->after('compliance_status');
            }

            // Horaires (miroir tattooers)
            if (!Schema::hasColumn('piercers', 'working_hours')) {
                $table->text('working_hours')->nullable()->after('bio');
            }

            // Aftercare (miroir tattooers)
            if (!Schema::hasColumn('piercers', 'aftercare_sheet')) {
                $table->text('aftercare_sheet')->nullable()->after('working_hours');
            }
            if (!Schema::hasColumn('piercers', 'aftercare_reminder_2h')) {
                $table->boolean('aftercare_reminder_2h')->default(true)->after('aftercare_sheet');
            }
            if (!Schema::hasColumn('piercers', 'aftercare_reminder_7d')) {
                $table->boolean('aftercare_reminder_7d')->default(true)->after('aftercare_reminder_2h');
            }
            if (!Schema::hasColumn('piercers', 'aftercare_reminder_14d')) {
                $table->boolean('aftercare_reminder_14d')->default(true)->after('aftercare_reminder_7d');
            }

            // Colonnes spécifiques piercing
            if (!Schema::hasColumn('piercers', 'pricing_grid')) {
                $table->json('pricing_grid')->nullable()->after('aftercare_reminder_14d');
            }
            if (!Schema::hasColumn('piercers', 'piercing_types')) {
                $table->json('piercing_types')->nullable()->after('pricing_grid');
            }
            if (!Schema::hasColumn('piercers', 'default_appointment_duration')) {
                $table->unsignedInteger('default_appointment_duration')->default(45)->after('piercing_types');
            }
        });
    }

    public function down(): void
    {
        Schema::table('piercers', function (Blueprint $table) {
            $table->dropColumn([
                'is_decision_maker', 'compliance_status', 'last_compliance_check_at',
                'working_hours', 'aftercare_sheet',
                'aftercare_reminder_2h', 'aftercare_reminder_7d', 'aftercare_reminder_14d',
                'pricing_grid', 'piercing_types', 'default_appointment_duration',
            ]);
        });
    }
};
