<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing users
        User::truncate();

        // Main Admin
        User::create([
            'name' => 'Admin Storage',
            'email' => 'admin@storage.com',
            'password' => Hash::make('password123'),
            'google2fa_secret' => null,
            'email_verified_at' => now(),
        ]);

        // Regular User
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@storage.com',
            'password' => Hash::make('password123'),
            'google2fa_secret' => null,
            'email_verified_at' => now(),
        ]);

        // Admin 1 - Petugas Pengajuan
        User::create([
            'name' => 'Petugas Pengajuan',
            'email' => 'admin1@storage.com',
            'password' => Hash::make('password123'),
            'google2fa_secret' => null,
            'email_verified_at' => now(),
        ]);

        // Admin 2 - Manajer Persetujuan
        User::create([
            'name' => 'Manajer Persetujuan',
            'email' => 'admin2@storage.com',
            'password' => Hash::make('password123'),
            'google2fa_secret' => null,
            'email_verified_at' => now(),
        ]);

        // Admin 3 - Petugas Barang Keluar
        User::create([
            'name' => 'Petugas Barang Keluar',
            'email' => 'admin3@storage.com',
            'password' => Hash::make('password123'),
            'google2fa_secret' => null,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Users berhasil dibuat:');
        $this->command->info('   👑 Main Admin: admin@storage.com (password123)');
        $this->command->info('   👤 Regular User: user@storage.com (password123)');
        $this->command->info('   🧾 Petugas Pengajuan: admin1@storage.com (password123)');
        $this->command->info('   🧑‍💼 Manajer Persetujuan: admin2@storage.com (password123)');
        $this->command->info('   📦 Petugas Barang Keluar: admin3@storage.com (password123)');
    }
}
