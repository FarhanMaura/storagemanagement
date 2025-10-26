<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Laporan;

class LaporanNotification extends Notification
{
    use Queueable;

    public $laporan;
    public $type;

    public function __construct(Laporan $laporan, $type = 'created')
    {
        $this->laporan = $laporan;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        \Log::info('Creating database notification for user:', [
            'user_id' => $notifiable->id,
            'laporan_id' => $this->laporan->id,
            'type' => $this->type
        ]);

        return [
            'laporan_id' => $this->laporan->id,
            'type' => $this->type,
            'message' => $this->getMessage(),
            'user_name' => $this->laporan->user->name,
            'timestamp' => now()->toDateTimeString(),
            'action_url' => route('laporan.show', $this->laporan->id),
        ];
    }

    private function getMessage()
    {
        $jenis = $this->laporan->jenis_laporan === 'masuk' ? 'masuk' : 'keluar';
        $actions = [
            'created' => "membuat laporan barang {$jenis}",
            'updated' => "memperbarui laporan barang {$jenis}",
            'deleted' => "menghapus laporan barang {$jenis}",
        ];

        $action = $actions[$this->type] ?? $actions['created'];

        return "{$this->laporan->user->name} {$action}: {$this->laporan->nama_barang} ({$this->laporan->jumlah} {$this->laporan->satuan})";
    }

    public function toArray($notifiable)
    {
        return [
            'laporan_id' => $this->laporan->id,
            'type' => $this->type,
            'message' => $this->getMessage(),
        ];
    }
}
