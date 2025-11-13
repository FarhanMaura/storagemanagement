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
        // SEMUA USER bisa akses laporan (User, Admin1, Admin2, Admin3, Main Admin)
        // Tidak perlu restriction

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

        // Statistik breakdown per satuan - DIPERBAIKI
        $statistikSatuan = [
            'masuk' => Laporan::where('jenis_laporan', 'masuk')
                        ->selectRaw('satuan, SUM(jumlah) as total')
                        ->groupBy('satuan')
                        ->orderBy('total', 'desc')
                        ->get()
                        ->toArray(),
            'keluar' => Laporan::where('jenis_laporan', 'keluar')
                         ->selectRaw('satuan, SUM(jumlah) as total')
                         ->groupBy('satuan')
                         ->orderBy('total', 'desc')
                         ->get()
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
        // SEMUA USER bisa buat laporan
        // Tidak perlu restriction

        // Ambil semua satuan unik dari database untuk dropdown
        $satuans = Laporan::select('satuan')
            ->distinct()
            ->whereNotNull('satuan')
            ->where('satuan', '!=', '')
            ->orderBy('satuan')
            ->pluck('satuan')
            ->toArray();

        // Satuan default yang selalu ada
        $defaultSatuans = ['kg', 'ton', 'bal', 'karung', 'unit', 'lembar'];

        // Gabungkan dan hapus duplikat
        $allSatuans = array_unique(array_merge($defaultSatuans, $satuans));
        sort($allSatuans);

        return view('laporan.create', [
            'title' => 'Buat Laporan Baru',
            'satuans' => $allSatuans
        ]);
    }

    public function store(Request $request)
    {
        // SEMUA USER bisa buat laporan
        // Tidak perlu restriction

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

        // DEBUG: Cek data sebelum kirim notifikasi
        $users = User::where('id', '!=', auth()->id())->get();

        \Log::info('Attempting to send notification to users:', [
            'current_user_id' => auth()->id(),
            'target_users_count' => $users->count(),
            'target_users' => $users->pluck('id')->toArray(),
            'laporan_id' => $laporan->id
        ]);

        try {
            Notification::send($users, new LaporanNotification($laporan, 'created'));

            \Log::info('Notification sent successfully');

            // Cek apakah notifikasi benar-benar dibuat di database
            foreach ($users as $user) {
                $notificationCount = $user->notifications()->count();
                \Log::info("User {$user->id} has {$notificationCount} notifications");
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }

    public function show($id)
    {
        // SEMUA USER bisa lihat detail laporan
        // Tidak perlu restriction

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
        if ($laporan->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua satuan unik dari database untuk dropdown
        $satuans = Laporan::select('satuan')
            ->distinct()
            ->whereNotNull('satuan')
            ->where('satuan', '!=', '')
            ->orderBy('satuan')
            ->pluck('satuan')
            ->toArray();

        // Satuan default yang selalu ada
        $defaultSatuans = ['kg', 'ton', 'bal', 'karung', 'unit', 'lembar'];

        // Gabungkan dan hapus duplikat
        $allSatuans = array_unique(array_merge($defaultSatuans, $satuans));
        sort($allSatuans);

        return view('laporan.edit', [
            'laporan' => $laporan,
            'title' => 'Edit Laporan',
            'satuans' => $allSatuans
        ]);
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        // Cek authorization
        if ($laporan->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
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

        // PERBAIKAN: Kirim notifikasi update
        $users = User::where('id', '!=', auth()->id())->get();
        Notification::send($users, new LaporanNotification($laporan, 'updated'));

        return redirect()->route('laporan.show', $laporan->id)
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);

        // Hanya Main Admin yang bisa hapus laporan
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang dapat menghapus laporan.');
        }

        // PERBAIKAN: Kirim notifikasi delete sebelum menghapus
        $users = User::where('id', '!=', auth()->id())->get();
        Notification::send($users, new LaporanNotification($laporan, 'deleted'));

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }

    /**
     * Export laporan to CSV dengan filter lengkap
     */
    public function exportCSV(Request $request)
    {
        // SEMUA USER bisa export
        // Tidak perlu restriction

        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisLaporan = $request->get('jenis_laporan');

        $query = Laporan::with('user');

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jenis laporan
        if ($jenisLaporan && in_array($jenisLaporan, ['masuk', 'keluar'])) {
            $query->where('jenis_laporan', $jenisLaporan);
        }

        // Filter berdasarkan tanggal CREATED_AT (waktu laporan dibuat)
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

        $callback = function() use ($laporans, $search, $startDate, $endDate, $jenisLaporan) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Header CSV dengan info filter
            fputcsv($file, ['LAPORAN BARANG - ' . date('d/m/Y H:i:s')], ';');
            fputcsv($file, ['Filter: ' . $this->getFilterInfo($search, $jenisLaporan, $startDate, $endDate)], ';');
            fputcsv($file, [''], ';'); // Empty row

            // Header data
            fputcsv($file, [
                'NO',
                'TANGGAL LAPORAN',
                'JAM LAPORAN',
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

            // Summary
            fputcsv($file, [''], ';');
            fputcsv($file, ['TOTAL DATA: ' . $laporans->count()], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export laporan to Excel (XLSX) dengan filter lengkap
     */
    public function exportExcel(Request $request)
    {
        // SEMUA USER bisa export
        // Tidak perlu restriction

        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisLaporan = $request->get('jenis_laporan');

        $fileName = 'laporan-barang-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new LaporanExport($search, $jenisLaporan, $startDate, $endDate), $fileName);
    }

    /**
     * Show export form dengan filter
     */
    public function showExportForm()
    {
        // SEMUA USER bisa export
        // Tidak perlu restriction

        return view('laporan.export', [
            'title' => 'Export Laporan'
        ]);
    }

    /**
     * Helper untuk info filter
     */
    private function getFilterInfo($search, $jenisLaporan, $startDate, $endDate)
    {
        $filters = [];

        if ($search) {
            $filters[] = "Pencarian: {$search}";
        }
        if ($jenisLaporan) {
            $filters[] = "Jenis: " . ($jenisLaporan === 'masuk' ? 'Barang Masuk' : 'Barang Keluar');
        }
        if ($startDate) {
            $filters[] = "Tanggal Mulai: {$startDate}";
        }
        if ($endDate) {
            $filters[] = "Tanggal Akhir: {$endDate}";
        }

        return $filters ? implode(' | ', $filters) : 'Semua Data';
    }

    /**
     * Get statistics for API
     */
    public function getStatistics()
    {
        // SEMUA USER bisa akses API
        // Tidak perlu restriction

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
        // SEMUA USER bisa akses API
        // Tidak perlu restriction

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
