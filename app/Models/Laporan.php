<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'laporans';

    protected $fillable = [
        'jenis_laporan',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'satuan',
        'keterangan',
        'lokasi',
        'user_id'
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk jenis laporan
    public function getJenisLaporanTextAttribute()
    {
        return $this->jenis_laporan === 'masuk' ? 'Barang Masuk' : 'Barang Keluar';
    }

    public function getColorClass()
    {
        return $this->jenis_laporan === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    // Scope untuk filtering
    public function scopeMasuk($query)
    {
        return $query->where('jenis_laporan', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis_laporan', 'keluar');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    // Method untuk mendapatkan stok terkini berdasarkan kode barang
    public static function getStokTerkini($kode_barang)
    {
        $totalMasuk = self::where('kode_barang', $kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->sum('jumlah');

        $totalKeluar = self::where('kode_barang', $kode_barang)
            ->where('jenis_laporan', 'keluar')
            ->sum('jumlah');

        return $totalMasuk - $totalKeluar;
    }

    // Method untuk mendapatkan barang asli (barang masuk terbaru)
    public static function getBarangAsli($kode_barang)
    {
        return self::where('kode_barang', $kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
