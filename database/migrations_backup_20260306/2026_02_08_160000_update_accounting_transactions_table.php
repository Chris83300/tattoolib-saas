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
        Schema::table('accounting_transactions', function (Blueprint $table) {
            // Ajouter les champs manquants pour la compatibilité avec notre système
            if (!Schema::hasColumn('accounting_transactions', 'booking_request_id')) {
                $table->foreignId('booking_request_id')->constrained('booking_requests')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('accounting_transactions', 'stripe_charge_id')) {
                $table->string('stripe_charge_id')->nullable();
            }

            if (!Schema::hasColumn('accounting_transactions', 'receipt_url')) {
                $table->string('receipt_url')->nullable();
            }

            if (!Schema::hasColumn('accounting_transactions', 'processed_at')) {
                $table->timestamp('processed_at')->nullable();
            }

            // Ajouter les index manquants avec vérification d'existence des colonnes
            if (!Schema::hasColumn('accounting_transactions', 'stripe_payment_intent_id')) {
                // Ne pas créer l'index si la colonne n'existe pas encore
            } else {
                if (!Schema::hasIndex('accounting_transactions', 'accounting_transactions_stripe_payment_intent_id_index')) {
                    $table->index(['stripe_payment_intent_id']);
                }
            }

            if (!Schema::hasColumn('accounting_transactions', 'stripe_session_id')) {
                // Ne pas créer l'index si la colonne n'existe pas encore
            } else {
                if (!Schema::hasIndex('accounting_transactions', 'accounting_transactions_stripe_session_id_index')) {
                    $table->index(['stripe_session_id']);
                }
            }

            if (!Schema::hasColumn('accounting_transactions', 'stripe_charge_id')) {
                // Ne pas créer l'index si la colonne n'existe pas encore
            } else {
                if (!Schema::hasIndex('accounting_transactions', 'accounting_transactions_stripe_charge_id_index')) {
                    $table->index(['stripe_charge_id']);
                }
            }

            if (!Schema::hasColumn('accounting_transactions', 'user_id')) {
                // Ne pas créer l'index si la colonne n'existe pas encore
            } else {
                if (!Schema::hasIndex('accounting_transactions', 'accounting_transactions_user_id_type_index')) {
                    $table->index(['user_id', 'type']);
                }
            }

            if (!Schema::hasColumn('accounting_transactions', 'status')) {
                // Ne pas créer l'index si la colonne n'existe pas encore
            } else {
                if (!Schema::hasIndex('accounting_transactions', 'accounting_transactions_status_index')) {
                    $table->index(['status']);
                }
            }

            if (!Schema::hasColumn('accounting_transactions', 'processed_at')) {
                // Ne pas créer l'index si la colonne n'existe pas encore
            } else {
                if (!Schema::hasIndex('accounting_transactions', 'accounting_transactions_processed_at_index')) {
                    $table->index(['processed_at']);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['booking_request_id', 'type']);
            $table->dropIndex('stripe_payment_intent_id');
            $table->dropIndex('stripe_session_id');
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex('status');
            $table->dropIndex('processed_at');

            // Supprimer les colonnes
            if (Schema::hasColumn('accounting_transactions', 'booking_request_id')) {
                $table->dropForeignId('booking_request_id');
            }

            if (Schema::hasColumn('accounting_transactions', 'stripe_charge_id')) {
                $table->dropColumn('stripe_charge_id');
            }

            if (Schema::hasColumn('accounting_transactions', 'receipt_url')) {
                $table->dropColumn('receipt_url');
            }

            if (Schema::hasColumn('accounting_transactions', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
        });
    }
};
