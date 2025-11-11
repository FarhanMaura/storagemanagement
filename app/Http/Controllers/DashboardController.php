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
            'title' => 'Dashboard Management Storage'
        ];

        // Data khusus untuk setiap role
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
                'selesai' => Peminjaman::where('user_id', $user->id)->where('status', 'completed')->count(),
            ];
        }

        if ($user->isPetugasPengajuan()) {
            $data['pengajuan_pending'] = Peminjaman::where('status', 'pending')
                ->with(['user', 'barang'])
                ->latest()
                ->take(5)
                ->get();
            $data['statistik_petugas'] = [
                'total_pending' => Peminjaman::where('status', 'pending')->count(),
                'total_validated' => Peminjaman::where('status', 'validated')->count(),
                'total_ditolak' => Peminjaman::where('status', 'rejected')->count(),
            ];
        }

        if ($user->isManajerPersetujuan()) {
            $data['pengajuan_validated'] = Peminjaman::where('status', 'validated')
                ->with(['user', 'barang'])
                ->latest()
                ->take(5)
                ->get();
            $data['statistik_manajer'] = [
                'total_validated' => Peminjaman::where('status', 'validated')->count(),
                'total_approved' => Peminjaman::where('status', 'approved')->count(),
                'total_rejected' => Peminjaman::where('status', 'rejected')->count(),
            ];
        }

        if ($user->isPetugasBarangKeluar()) {
            $data['pengajuan_approved'] = Peminjaman::where('status', 'approved')
                ->with(['user', 'barang'])
                ->latest()
                ->take(5)
                ->get();
            $data['stok_barang'] = Laporan::where('jenis_laporan', 'masuk')
                ->where('jumlah', '>', 0)
                ->orderBy('jumlah', 'desc')
                ->take(10)
                ->get();
            $data['statistik_gudang'] = [
                'total_approved' => Peminjaman::where('status', 'approved')->count(),
                'total_completed' => Peminjaman::where('status', 'completed')->count(),
                'stok_tersedia' => Laporan::where('jenis_laporan', 'masuk')->sum('jumlah'),
            ];
        }

        // Data untuk Main Admin (tetap seperti sebelumnya)
        if ($user->isAdmin()) {
            $data['recent_laporans'] = Laporan::with('user')->latest()->take(4)->get();
            $data['total_laporan'] = Laporan::count();
            $data['total_barang_masuk'] = Laporan::where('jenis_laporan', 'masuk')->sum('jumlah');
            $data['total_barang_keluar'] = Laporan::where('jenis_laporan', 'keluar')->sum('jumlah');
            $data['total_user'] = User::count();
        }

        return view('dashboard', $data);
    }
}
