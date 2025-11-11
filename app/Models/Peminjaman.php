<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'barang_id',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan',
        'status',
        'catatan_admin',
        'validated_by',
        'validated_at',
        'approved_by',
        'approved_at',
        'completed_by',
        'completed_at',
        'returned_at'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'validated_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Laporan::class, 'barang_id');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    // Helper Methods
    public function canBeApproved()
    {
        return $this->status === 'validated' && $this->barang && $this->barang->jumlah >= $this->jumlah_pinjam;
    }

    public function canBeProcessed()
    {
        return $this->status === 'approved' && $this->barang && $this->barang->jumlah >= $this->jumlah_pinjam;
    }

    public function canBeReturned()
    {
        return $this->status === 'completed' && !$this->returned_at;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300',
            'validated' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
            'approved' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
            'completed' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300',
            'rejected' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
            'returned' => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Validasi',
            'validated' => 'Terverifikasi',
            'approved' => 'Disetujui',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'returned' => 'Dikembalikan'
        ];

        return $texts[$this->status] ?? $this->status;
    }

    public function getCurrentStepAttribute()
    {
        $steps = [
            'pending' => 1,
            'validated' => 2,
            'approved' => 3,
            'completed' => 4,
            'returned' => 5,
            'rejected' => 0
        ];

        return $steps[$this->status] ?? 0;
    }

    public function getTotalStepsAttribute()
    {
        return 5; // pending → validated → approved → completed → returned
    }

    public function getProgressPercentageAttribute()
    {
        return ($this->current_step / $this->total_steps) * 100;
    }
}
