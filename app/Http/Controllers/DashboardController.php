<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Laporan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [
            'title' => 'Dashboard Inventaris Kantor Walikota Palembang'
        ];

        if ($user->isUser()) {
            $data['peminjaman_terbaru'] = Peminjaman::where('user_id', $user->id)
                ->with('barang')
                ->latest()
                ->take(5)
                ->get();

            $data['statistik_user'] = [
                'total_pengajuan' => Peminjaman::where('user_id', $user->id)->count(),
                'pending' => Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count(),
                'disetujui' => Peminjaman::where('user_id', $user->id)->where('status', 'approved')->count(),
                'diproses' => Peminjaman::where('user_id', $user->id)->where('status', 'processed')->count(),
                'selesai' => Peminjaman::where('user_id', $user->id)->where('status', 'completed')->count(),
                'dikembalikan' => Peminjaman::where('user_id', $user->id)->where('status', 'returned')->count(),
            ];
        }

        if ($user->isAdmin()) {
            $data['recent_laporans'] = Laporan::with('user')->latest()->take(5)->get();
            $data['peminjaman_pending'] = Peminjaman::where('status', 'pending')->with(['user', 'barang'])->latest()->take(5)->get();
            $data['total_laporan'] = Laporan::count();

            $totalMasuk = Laporan::where('jenis_laporan', 'masuk')->sum('jumlah');
            $totalKeluar = Laporan::where('jenis_laporan', 'keluar')->sum('jumlah');

            $data['total_barang_masuk'] = $totalMasuk;
            $data['total_barang_keluar'] = $totalKeluar;
            $data['stok_aktual'] = $totalMasuk - $totalKeluar;
            $data['total_user'] = User::count();
            $data['total_peminjaman_aktif'] = Peminjaman::whereIn('status', ['pending', 'approved', 'processed'])->count();
        }

        return view('dashboard', $data);
    }
}
