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
        // Vérifier si la table roles existe déjà
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // client, tattooer, pierceur, studio_owner, studio_artist
                $table->string('slug')->unique(); // Pour URL et programmation
                $table->text('description')->nullable(); // Description du rôle
                $table->json('permissions')->nullable(); // Permissions spécifiques
                $table->boolean('is_active')->default(true); // Activer/désactiver un rôle
                $table->timestamps();

                // Index
                $table->index(['is_active']);
            });

            // Insérer les rôles de base
            \Illuminate\Support\Facades\DB::table('roles')->insert([
                [
                    'name' => 'client',
                    'slug' => 'client',
                    'description' => 'Client final du service',
                    'permissions' => json_encode(['book_appointment', 'manage_profile', 'view_portfolio']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'tattooer',
                    'slug' => 'tattooer',
                    'description' => 'Tatoueur professionnel indépendant',
                    'permissions' => json_encode(['manage_appointments', 'manage_portfolio', 'manage_studio', 'view_clients', 'accept_booking', 'manage_payments']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'pierceur',
                    'slug' => 'pierceur',
                    'description' => 'Professionnel du piercing indépendant',
                    'permissions' => json_encode(['manage_appointments', 'manage_portfolio', 'view_clients', 'accept_booking', 'manage_payments']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'studio_owner',
                    'slug' => 'studio_owner',
                    'description' => 'Propriétaire de salon de tatouage',
                    'permissions' => json_encode(['manage_studio', 'manage_artists', 'manage_appointments', 'manage_portfolio', 'view_clients', 'accept_booking', 'manage_payments', 'manage_finances']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'studio_artist',
                    'slug' => 'studio_artist',
                    'description' => 'Artiste travaillant dans un salon',
                    'permissions' => json_encode(['manage_appointments', 'manage_portfolio', 'view_clients', 'accept_booking']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
