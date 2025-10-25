<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User admin tanpa 2FA secret (akan setup setelah login pertama)
        User::factory()->create([
            'name' => 'Admin Storage',
            'email' => 'admin@storage.com',
            'password' => bcrypt('password123'),
            'google2fa_secret' => null, // Pastikan null dulu
        ]);

        User::factory()->create([
            'name' => 'User Biasa',
            'email' => 'user@storage.com',
            'password' => bcrypt('password123'),
            'google2fa_secret' => null,
        ]);
    }
}
