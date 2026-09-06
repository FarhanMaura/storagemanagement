<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema; // Tambahan wajib untuk mengatasi Foreign Key

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan pengecekan Foreign Key
        Schema::disableForeignKeyConstraints();

        // Clear existing users (sekarang aman dieksekusi)
        User::truncate();

        // Super Admin Kantor Walikota Palembang
        User::create([
            'name' => 'Admin Utama Palembang',
            'email' => 'admin@palembang.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Regular User / Pegawai
        User::create([
            'name' => 'Pegawai Kantor Walikota',
            'email' => 'user@palembang.go.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // 2. Nyalakan kembali pengecekan Foreign Key
        Schema::enableForeignKeyConstraints();

        $this->command->info('✅ Users berhasil dibuat:');
        $this->command->info('   👑 Super Admin: admin@palembang.go.id (password123)');
        $this->command->info('   👤 Pegawai: user@palembang.go.id (password123)');
    }
}