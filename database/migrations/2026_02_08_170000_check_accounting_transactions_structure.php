<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Just check the structure - compatible with both MySQL and SQLite
        if (Schema::hasTable('accounting_transactions')) {
            $columns = Schema::getColumnListing('accounting_transactions');

            echo "Existing columns in accounting_transactions table:\n";
            foreach ($columns as $column) {
                echo "- " . $column . "\n";
            }
        } else {
            echo "Table accounting_transactions does not exist yet\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing
    }
};
