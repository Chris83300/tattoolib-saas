<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->string('slug')->unique()->after('bio')->nullable();
        });

        // Générer slugs pour tattooers existants
        DB::table('tattooers')->get()->each(function ($tattooer) {
            $user = DB::table('users')->where('id', $tattooer->user_id)->first();
            if ($user) {
                $slug = Str::slug($user->name . '-' . $tattooer->id);

                DB::table('tattooers')
                    ->where('id', $tattooer->id)
                    ->update(['slug' => $slug]);
            }
        });

        // Rendre NOT NULL après génération
        Schema::table('tattooers', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
