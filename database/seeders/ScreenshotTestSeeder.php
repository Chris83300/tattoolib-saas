<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Piercer;
use App\Models\Studio;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ScreenshotTestSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder idempotent : crée uniquement les users manquants
        $emails = User::whereIn('email', [
            'screenshot-admin@test.local',
            'screenshot-tattooer@test.local',
            'screenshot-pierceur@test.local',
            'screenshot-client@test.local',
            'screenshot-studio@test.local',
        ])->pluck('email')->toArray();

        $password = Hash::make('screenshot-test-2026');
        $created = 0;

        // Admin
        if (!in_array('screenshot-admin@test.local', $emails)) {
            $admin = User::create([
                'name' => 'Admin Screenshot', 'first_name' => 'Admin', 'last_name' => 'Screenshot',
                'email' => 'screenshot-admin@test.local', 'password' => $password,
                'role' => 'admin', 'status' => 'active', 'email_verified_at' => now(),
            ]);
            $admin->assignRole('admin');
            $created++;
        }

        // Tattooer PRO
        if (!in_array('screenshot-tattooer@test.local', $emails)) {
            $tUser = User::create([
                'name' => 'Tatoueur Test', 'first_name' => 'Tatoueur', 'last_name' => 'Test',
                'email' => 'screenshot-tattooer@test.local', 'password' => $password,
                'role' => 'tattooer', 'status' => 'active', 'email_verified_at' => now(),
            ]);
            $tUser->assignRole('tattooer');
            Tattooer::create([
                'user_id' => $tUser->id, 'pseudo' => 'InkMaster Test',
                'city' => 'Paris', 'postal_code' => '75001',
                'current_plan' => 'pro', 'is_subscribed' => true, 'is_blocked' => false,
            ]);
            $created++;
        } else {
            $tUser = User::where('email', 'screenshot-tattooer@test.local')->first();
            if ($tUser && !$tUser->tattooer) {
                Tattooer::create([
                    'user_id' => $tUser->id, 'pseudo' => 'InkMaster Test',
                    'city' => 'Paris', 'postal_code' => '75001',
                    'current_plan' => 'pro', 'is_subscribed' => true, 'is_blocked' => false,
                ]);
            }
        }

        // Pierceur STARTER
        if (!in_array('screenshot-pierceur@test.local', $emails)) {
            $pUser = User::create([
                'name' => 'Pierceur Test', 'first_name' => 'Pierceur', 'last_name' => 'Test',
                'email' => 'screenshot-pierceur@test.local', 'password' => $password,
                'role' => 'pierceur', 'status' => 'active', 'email_verified_at' => now(),
            ]);
            $pUser->assignRole('pierceur');
            $created++;
        } else {
            $pUser = User::where('email', 'screenshot-pierceur@test.local')->first();
        }
        if ($pUser && !$pUser->piercer) {
            Piercer::create([
                'user_id' => $pUser->id, 'name' => 'Pierceur Test', 'pseudo' => 'PiercePro Test',
                'siret' => '98765432101234', 'city' => 'Lyon', 'postal_code' => '69001',
                'current_plan' => 'starter', 'is_subscribed' => true, 'is_blocked' => false,
            ]);
        }

        // Client
        if (!in_array('screenshot-client@test.local', $emails)) {
            $cUser = User::create([
                'name' => 'Client Test', 'first_name' => 'Client', 'last_name' => 'Test',
                'email' => 'screenshot-client@test.local', 'password' => $password,
                'role' => 'client', 'status' => 'active', 'email_verified_at' => now(),
            ]);
            $cUser->assignRole('client');
            Client::create(['user_id' => $cUser->id, 'phone' => '0612345678', 'email' => 'screenshot-client@test.local']);
            $created++;
        }

        // Studio Owner
        if (!in_array('screenshot-studio@test.local', $emails)) {
            $sUser = User::create([
                'name' => 'Studio Owner Test', 'first_name' => 'Studio', 'last_name' => 'Owner',
                'email' => 'screenshot-studio@test.local', 'password' => $password,
                'role' => 'studio_owner', 'status' => 'active', 'is_studio_owner' => true, 'email_verified_at' => now(),
            ]);
            $sUser->assignRole('studio_owner');
            Studio::create([
                'user_id' => $sUser->id, 'name' => 'Studio Test Ink', 'slug' => 'studio-test-ink',
                'city' => 'Marseille', 'postal_code' => '13001', 'address' => '1 rue du Test',
                'siret' => '11223344556677', 'is_active' => true, 'payment_mode' => 'artist_direct',
            ]);
            $created++;
        }

        $this->command->info("✅ {$created} users screenshot créés / complétés");
    }
}
