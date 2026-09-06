<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $laporans = Laporan::all();

        foreach ($users as $user) {
            // Buat beberapa notifikasi dummy untuk setiap user
            for ($i = 0; $i < 3; $i++) {
                $laporan = $laporans->random();

                $user->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => 'App\Notifications\LaporanNotification',
                    'data' => [
                        'laporan_id' => $laporan->id,
                        'type' => ['created', 'updated', 'deleted'][rand(0, 2)],
                        'message' => $this->getDummyMessage($laporan),
                        'user_name' => $laporan->user->name,
                        'timestamp' => now()->subDays(rand(0, 7))->toDateTimeString(),
                    ],
                    'read_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                    'created_at' => now()->subDays(rand(0, 7)),
                    'updated_at' => now()->subDays(rand(0, 7)),
                ]);
            }
        }
    }

    private function getDummyMessage($laporan)
    {
        $actions = [
            'created' => 'membuat laporan barang',
            'updated' => 'memperbarui laporan barang',
            'deleted' => 'menghapus laporan barang',
        ];

        $action = $actions[array_rand($actions)];
        $jenis = $laporan->jenis_laporan === 'masuk' ? 'masuk' : 'keluar';

        return "{$laporan->user->name} {$action} {$jenis}: {$laporan->nama_barang} ({$laporan->jumlah} {$laporan->satuan})";
    }
}
