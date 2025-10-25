<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\User;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@storage.com')->first();

        $laporanData = [
            [
                'jenis_laporan' => 'masuk',
                'kode_barang' => 'BKP-001',
                'nama_barang' => 'Kertas Mentah Grade A',
                'jumlah' => 100,
                'satuan' => 'kg',
                'lokasi' => 'Gudang A, Rak B1',
                'keterangan' => 'Supplier PT. Paperindo Jaya',
            ],
            [
                'jenis_laporan' => 'masuk',
                'kode_barang' => 'BKP-002',
                'nama_barang' => 'Bubur Kertas Premium',
                'jumlah' => 50,
                'satuan' => 'ton',
                'lokasi' => 'Gudang B, Area Khusus',
                'keterangan' => 'Import dari Brazil',
            ],
            [
                'jenis_laporan' => 'keluar',
                'kode_barang' => 'BKP-001',
                'nama_barang' => 'Kertas Mentah Grade A',
                'jumlah' => 25,
                'satuan' => 'kg',
                'lokasi' => 'Produksi Line 1',
                'keterangan' => 'Untuk proses bleaching',
            ],
            [
                'jenis_laporan' => 'masuk',
                'kode_barang' => 'BKP-003',
                'nama_barang' => 'Serat Kayu Pinus',
                'jumlah' => 200,
                'satuan' => 'kg',
                'lokasi' => 'Gudang C, Rak A2',
                'keterangan' => 'Supplier lokal Kalimantan',
            ],
            [
                'jenis_laporan' => 'keluar',
                'kode_barang' => 'BKP-002',
                'nama_barang' => 'Bubur Kertas Premium',
                'jumlah' => 10,
                'satuan' => 'ton',
                'lokasi' => 'Ekspor ke Jepang',
                'keterangan' => 'Pesanan customer Mitsubishi Paper',
            ],
        ];

        foreach ($laporanData as $data) {
            Laporan::create(array_merge($data, [
                'user_id' => $user->id,
            ]));
        }
    }
}
