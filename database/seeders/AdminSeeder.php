<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les anciens admins
        User::where('email', 'admin@inkpik.com')->delete();

        // Créer l'admin principal
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@inkpik.com',
            'password' => Hash::make('Admin123!'),
            'role' => 'admin',
            'is_admin' => true,
            'is_active' => true,
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: admin@inkpik.com');
        $this->command->info('🔑 Password: Admin123!');
    }
}
