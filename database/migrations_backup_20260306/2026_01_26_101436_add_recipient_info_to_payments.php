<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // ===========================================
            // QUI A ENCAISSÉ ?
            // ===========================================
            $table->enum('recipient_type', ['artist', 'studio'])
                ->nullable()
                ->after('payment_type')
                ->comment('artist = artiste direct, studio = studio centralisé');

            $table->string('recipient_name')->nullable()
                ->after('recipient_type')
                ->comment('Nom du destinataire pour affichage');

            // Index
            $table->index('recipient_type');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_type',
                'recipient_name',
            ]);
        });
    }
};
