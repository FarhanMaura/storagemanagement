<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Laporan;
use App\Notifications\LaporanNotification;

class SendTestNotification extends Command
{
    protected $signature = 'notification:test {--count=5 : Number of test notifications to send}';
    protected $description = 'Send test notification to all users';

    public function handle()
    {
        $count = $this->option('count');
        $laporans = Laporan::all();

        if ($laporans->isEmpty()) {
            $this->error('Tidak ada data laporan. Buat laporan dulu.');
            return;
        }

        $users = User::all();

        $this->info("Mengirim {$count} notifikasi test ke {$users->count()} users...");

        $types = ['created', 'updated', 'deleted'];

        foreach ($users as $user) {
            for ($i = 0; $i < $count; $i++) {
                $laporan = $laporans->random();
                $type = $types[array_rand($types)];

                $user->notify(new LaporanNotification($laporan, $type));
                $this->line("Notifikasi {$type} terkirim ke: {$user->name}");
            }
        }

        $this->info('Test notifikasi selesai!');
        $this->info('Sekarang buka website dan klik bell icon untuk melihat notifikasi.');
    }
}
