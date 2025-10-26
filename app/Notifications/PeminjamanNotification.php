<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Peminjaman;

class PeminjamanNotification extends Notification
{
    use Queueable;

    public $peminjaman;
    public $type;
    public $actionBy;

    public function __construct(Peminjaman $peminjaman, $type = 'created', $actionBy = null)
    {
        $this->peminjaman = $peminjaman;
        $this->type = $type;
        $this->actionBy = $actionBy ?? auth()->user();
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'type' => $this->type,
            'message' => $this->getMessage(),
            'user_name' => $this->actionBy->name,
            'timestamp' => now()->toDateTimeString(),
            'action_url' => route('peminjaman.show', $this->peminjaman->id),
        ];
    }

    private function getMessage()
    {
        $actions = [
            'created' => "mengajukan peminjaman barang",
            'approved' => "menyetujui peminjaman barang",
            'rejected' => "menolak peminjaman barang",
            'returned' => "mengembalikan barang pinjaman",
        ];

        $action = $actions[$this->type] ?? $actions['created'];

        if ($this->type === 'created') {
            return "{$this->peminjaman->user->name} {$action}: {$this->peminjaman->barang->nama_barang} ({$this->peminjaman->jumlah_pinjam} {$this->peminjaman->barang->satuan})";
        } else {
            return "{$this->actionBy->name} {$action}: {$this->peminjaman->barang->nama_barang} ({$this->peminjaman->jumlah_pinjam} {$this->peminjaman->barang->satuan}) - Oleh: {$this->peminjaman->user->name}";
        }
    }

    public function toArray($notifiable)
    {
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'type' => $this->type,
            'message' => $this->getMessage(),
        ];
    }
}
