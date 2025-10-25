<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class Reset2FASeeder extends Seeder
{
    public function run(): void
    {
        // Reset 2FA untuk user admin
        $admin = User::where('email', 'admin@storage.com')->first();

        if ($admin) {
            $admin->update([
                'google2fa_secret' => null
            ]);
            $this->command->info('2FA berhasil direset untuk admin@storage.com');
        } else {
            $this->command->error('User admin tidak ditemukan!');
        }

        // Reset 2FA untuk semua user (optional)
        // User::query()->update(['google2fa_secret' => null]);
        // $this->command->info('2FA berhasil direset untuk semua user');
    }
}
