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

        return view('laporan.index', [
            'laporan' => $laporan,
            'statistik' => $statistik,
            'search' => $search,
            'title' => $search ? 'Hasil Pencarian: ' . $search : 'Inventaris Barang Kantor Walikota Palembang'
        ]);
    }

    public function create()
    {
        // Hanya Admin yang bisa menambah barang / membuat laporan
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak menambah barang & membuat laporan.');
        }

        return view('laporan.create', [
            'title' => 'Tambah Barang / Buat Laporan Baru'
        ]);
    }

    public function store(Request $request)
    {
        // Hanya Admin yang bisa menyimpan barang / laporan baru
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak menambah barang & membuat laporan.');
        }

        $request->validate([
            'jenis_laporan' => 'required|in:masuk,keluar,',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'lokasi' => 'required|string|max:100',
        ]);

        $laporan = Laporan::create([
            'jenis_laporan' => $request->jenis_laporan,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'lokasi' => $request->lokasi,
            'user_id' => auth()->id(),
        ]);

        $users = User::where('id', '!=', auth()->id())->get();

        try {
            Notification::send($users, new LaporanNotification($laporan, 'created'));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return redirect()->route('laporan.index')
            ->with('success', 'Data barang/laporan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);

        return view('laporan.show', [
            'laporan' => $laporan,
            'title' => 'Detail Barang / Laporan'
        ]);
    }

    public function edit($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak mengubah data barang.');
        }

        $laporan = Laporan::findOrFail($id);

        return view('laporan.edit', [
            'laporan' => $laporan,
            'title' => 'Edit Data Barang'
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak mengubah data barang.');
        }

        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'jenis_laporan' => 'required|in:masuk,keluar',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'lokasi' => 'required|string|max:100',
        ]);

        $laporan->update([
            'jenis_laporan' => $request->jenis_laporan,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'lokasi' => $request->lokasi,
        ]);

        $users = User::where('id', '!=', auth()->id())->get();
        Notification::send($users, new LaporanNotification($laporan, 'updated'));

        return redirect()->route('laporan.show', $laporan->id)
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak menghapus barang.');
        }

        $laporan = Laporan::findOrFail($id);

        $users = User::where('id', '!=', auth()->id())->get();
        Notification::send($users, new LaporanNotification($laporan, 'deleted'));

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Data barang berhasil dihapus!');
    }

    public function exportCSV(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak membuat laporan/export.');
        }

        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisLaporan = $request->get('jenis_laporan');

        $query = Laporan::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($jenisLaporan && in_array($jenisLaporan, ['masuk', 'keluar'])) {
            $query->where('jenis_laporan', $jenisLaporan);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $laporans = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'laporan-barang-walikota-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($laporans, $search, $startDate, $endDate, $jenisLaporan) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            fputcsv($file, ['LAPORAN BARANG KANTOR WALIKOTA PALEMBANG - ' . date('d/m/Y H:i:s')], ';');
            fputcsv($file, ['Filter: ' . $this->getFilterInfo($search, $jenisLaporan, $startDate, $endDate)], ';');
            fputcsv($file, [''], ';');

            fputcsv($file, [
                'NO',
                'TANGGAL LAPORAN',
                'JAM LAPORAN',
                'JENIS LAPORAN',
                'KODE BARANG',
                'NAMA BARANG',
                'JUMLAH',
                'LOKASI',
                'KETERANGAN',
                'DIBUAT OLEH'
            ], ';');

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
                    $laporan->lokasi,
                    $laporan->keterangan ?? '-',
                    $laporan->user->name
                ], ';');
            }

            fputcsv($file, [''], ';');
            fputcsv($file, ['TOTAL DATA: ' . $laporans->count()], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak membuat laporan/export.');
        }

        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $jenisLaporan = $request->get('jenis_laporan');

        $fileName = 'laporan-barang-walikota-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new LaporanExport($search, $jenisLaporan, $startDate, $endDate), $fileName);
    }

    public function showExportForm()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang berhak membuat laporan/export.');
        }

        return view('laporan.export', [
            'title' => 'Export Laporan Barang Kantor Walikota'
        ]);
    }

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
}
