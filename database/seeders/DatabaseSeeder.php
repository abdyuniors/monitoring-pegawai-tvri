<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => 'password',
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Petugas Security',
            'username' => 'security',
            'password' => 'password',
            'role' => UserRole::SECURITY,
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Pimpinan',
            'username' => 'pimpinan',
            'password' => 'password',
            'role' => UserRole::PIMPINAN,
            'is_active' => true,
        ]);

        $this->call([
            WorkScheduleSeeder::class,
        ]);
    }
}