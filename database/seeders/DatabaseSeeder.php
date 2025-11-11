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
            'google2fa_secret' => null,
        ]);

        User::factory()->create([
            'name' => 'User Biasa',
            'email' => 'user@storage.com',
            'password' => bcrypt('password123'),
            'google2fa_secret' => null,
        ]);

        // TAMBAH 3 ROLE ADMIN BARU
        User::factory()->create([
            'name' => 'Petugas Pengajuan',
            'email' => 'admin1@storage.com',
            'password' => bcrypt('password123'),
            'google2fa_secret' => null,
        ]);

        User::factory()->create([
            'name' => 'Manajer Persetujuan',
            'email' => 'admin2@storage.com',
            'password' => bcrypt('password123'),
            'google2fa_secret' => null,
        ]);

        User::factory()->create([
            'name' => 'Petugas Barang Keluar',
            'email' => 'admin3@storage.com',
            'password' => bcrypt('password123'),
            'google2fa_secret' => null,
        ]);
    }
}
