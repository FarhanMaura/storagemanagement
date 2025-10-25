<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;
use App\Notifications\LaporanNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Laporan::with('user')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $laporan = $query->paginate(10);

        // Statistik total
        $statistik = [
            'total_masuk' => Laporan::where('jenis_laporan', 'masuk')->sum('jumlah'),
            'total_keluar' => Laporan::where('jenis_laporan', 'keluar')->sum('jumlah'),
            'total_barang' => Laporan::count(),
        ];

        // Statistik per satuan
        $statistikSatuan = [
            'masuk' => Laporan::where('jenis_laporan', 'masuk')
                        ->selectRaw('satuan, SUM(jumlah) as total')
                        ->groupBy('satuan')
                        ->pluck('total', 'satuan')
                        ->toArray(),
            'keluar' => Laporan::where('jenis_laporan', 'keluar')
                         ->selectRaw('satuan, SUM(jumlah) as total')
                         ->groupBy('satuan')
                         ->pluck('total', 'satuan')
                         ->toArray(),
        ];

        return view('laporan.index', [
            'laporan' => $laporan,
            'statistik' => $statistik,
            'statistikSatuan' => $statistikSatuan,
            'search' => $search,
            'title' => $search ? 'Hasil Pencarian: ' . $search : 'Laporan Barang'
        ]);
    }

    public function create()
    {
        return view('laporan.create', [
            'title' => 'Buat Laporan Baru'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:masuk,keluar',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'keterangan' => 'nullable|string',
            'lokasi' => 'required|string|max:100',
        ]);

        $laporan = Laporan::create([
            'jenis_laporan' => $request->jenis_laporan,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'keterangan' => $request->keterangan,
            'lokasi' => $request->lokasi,
            'user_id' => auth()->id(),
        ]);

        // Kirim notifikasi ke SEMUA user termasuk pembuat (untuk testing)
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new LaporanNotification($laporan, 'created'));
        }

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);

        return view('laporan.show', [
            'laporan' => $laporan,
            'title' => 'Detail Laporan'
        ]);
    }

    public function edit($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Cek apakah user yang membuat laporan atau admin
        if ($laporan->user_id !== auth()->id() && auth()->user()->email !== 'admin@storage.com') {
            abort(403, 'Unauthorized action.');
        }

        return view('laporan.edit', [
            'laporan' => $laporan,
            'title' => 'Edit Laporan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        // Cek authorization
        if ($laporan->user_id !== auth()->id() && auth()->user()->email !== 'admin@storage.com') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'jenis_laporan' => 'required|in:masuk,keluar',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:20',
            'keterangan' => 'nullable|string',
            'lokasi' => 'required|string|max:100',
        ]);

        $laporan->update([
            'jenis_laporan' => $request->jenis_laporan,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'keterangan' => $request->keterangan,
            'lokasi' => $request->lokasi,
        ]);

        // Kirim notifikasi ke SEMUA user termasuk pembuat (untuk testing)
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new LaporanNotification($laporan, 'updated'));
        }

        return redirect()->route('laporan.show', $laporan->id)
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Hanya admin yang bisa hapus
        if (auth()->user()->email !== 'admin@storage.com') {
            abort(403, 'Hanya admin yang dapat menghapus laporan.');
        }

        // Kirim notifikasi delete sebelum menghapus
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new LaporanNotification($laporan, 'deleted'));
        }

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }

    /**
     * Export laporan to CSV
     */
    public function exportCSV(Request $request)
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Laporan::with('user');

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $laporans = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'laporan-barang-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($laporans) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Header CSV
            fputcsv($file, [
                'NO',
                'TANGGAL',
                'JAM',
                'JENIS LAPORAN',
                'KODE BARANG',
                'NAMA BARANG',
                'JUMLAH',
                'SATUAN',
                'LOKASI',
                'KETERANGAN',
                'DIBUAT OLEH',
                'STATUS'
            ], ';');

            // Data CSV
            $no = 1;
            foreach ($laporans as $laporan) {
                fputcsv($file, [
                    $no++,
                    $laporan->created_at->format('d/m/Y'),
                    $laporan->created_at->format('H:i:s'),
                    $laporan->jenis_laporan === 'masuk' ? 'BARANG MASUK' : 'BARANG KELUAR',
                    $laporan->kode_barang,
                    $laporan->nama_barang,
                    $laporan->jumlah,
                    strtoupper($laporan->satuan),
                    $laporan->lokasi,
                    $laporan->keterangan ?? '-',
                    $laporan->user->name,
                    $laporan->created_at->isToday() ? 'BARU' : 'LAMA'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export laporan to Excel (XLSX) - FIXED dengan library Excel
     */
    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $fileName = 'laporan-barang-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new LaporanExport($search, $startDate, $endDate), $fileName);
    }

    /**
     * Show export form
     */
    public function showExportForm()
    {
        return view('laporan.export', [
            'title' => 'Export Laporan'
        ]);
    }

    /**
     * Get statistics for API
     */
    public function getStatistics()
    {
        $totalMasuk = Laporan::where('jenis_laporan', 'masuk')->sum('jumlah');
        $totalKeluar = Laporan::where('jenis_laporan', 'keluar')->sum('jumlah');
        $totalLaporan = Laporan::count();

        $recentLaporan = Laporan::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'total_masuk' => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'total_laporan' => $totalLaporan,
            'recent_laporan' => $recentLaporan,
        ]);
    }

    /**
     * Get statistics by satuan for API
     */
    public function getStatisticsBySatuan($jenis, $satuan = null)
    {
        $query = Laporan::where('jenis_laporan', $jenis);

        if ($satuan) {
            $query->where('satuan', $satuan);
        }

        $total = $query->sum('jumlah');
        $count = $query->count();

        return response()->json([
            'jenis' => $jenis,
            'satuan' => $satuan ?: 'all',
            'total' => $total,
            'count' => $count,
            'satuan_list' => $satuan ? [] : Laporan::where('jenis_laporan', $jenis)
                ->selectRaw('satuan, SUM(jumlah) as total')
                ->groupBy('satuan')
                ->get()
                ->toArray()
        ]);
    }
}
