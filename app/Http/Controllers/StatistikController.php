<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StatistikController extends Controller
{
    public function index()
    {
        $statistik = $this->getStatistikData();
        $chartData = $this->getChartData();

        return view('statistik.index', [
            'statistik' => $statistik,
            'chartData' => $chartData,
            'title' => 'Data Statistik'
        ]);
    }

    public function getStatistikData()
    {
        // Statistik dasar
        $totalMasuk = Laporan::where('jenis_laporan', 'masuk')->sum('jumlah');
        $totalKeluar = Laporan::where('jenis_laporan', 'keluar')->sum('jumlah');
        $totalLaporan = Laporan::count();
        $totalUsers = User::count();

        // Statistik bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $bulanIniMasuk = Laporan::where('jenis_laporan', 'masuk')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('jumlah');

        $bulanIniKeluar = Laporan::where('jenis_laporan', 'keluar')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('jumlah');

        $bulanIniLaporan = Laporan::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Top 5 barang paling banyak masuk
        $topBarangMasuk = Laporan::where('jenis_laporan', 'masuk')
            ->select('kode_barang', 'nama_barang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kode_barang', 'nama_barang')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Top 5 barang paling banyak keluar
        $topBarangKeluar = Laporan::where('jenis_laporan', 'keluar')
            ->select('kode_barang', 'nama_barang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kode_barang', 'nama_barang')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Aktivitas user
        $topUsers = User::withCount('laporan')
            ->orderBy('laporan_count', 'desc')
            ->take(5)
            ->get();

        // Hari operasional (estimated)
        $firstRecord = Laporan::orderBy('created_at')->first();
        $hariOperasional = $firstRecord ? now()->diffInDays($firstRecord->created_at) : 1;

        return [
            'total_masuk' => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'total_laporan' => $totalLaporan,
            'total_users' => $totalUsers,
            'bulan_ini_masuk' => $bulanIniMasuk,
            'bulan_ini_keluar' => $bulanIniKeluar,
            'bulan_ini_laporan' => $bulanIniLaporan,
            'top_barang_masuk' => $topBarangMasuk,
            'top_barang_keluar' => $topBarangKeluar,
            'top_users' => $topUsers,
            'hari_operasional' => max($hariOperasional, 1),
        ];
    }

    public function getChartData()
    {
        // Data untuk chart 6 bulan terakhir
        $months = [];
        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            $months[] = $monthName;

            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $masuk = Laporan::where('jenis_laporan', 'masuk')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('jumlah');

            $keluar = Laporan::where('jenis_laporan', 'keluar')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('jumlah');

            $dataMasuk[] = $masuk;
            $dataKeluar[] = $keluar;
        }

        // Data untuk pie chart jenis laporan
        $totalMasuk = Laporan::where('jenis_laporan', 'masuk')->count();
        $totalKeluar = Laporan::where('jenis_laporan', 'keluar')->count();

        // Data untuk chart harian (7 hari terakhir)
        $days = [];
        $dailyMasuk = [];
        $dailyKeluar = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayName = $day->format('D');
            $days[] = $dayName;

            $startOfDay = $day->copy()->startOfDay();
            $endOfDay = $day->copy()->endOfDay();

            $masuk = Laporan::where('jenis_laporan', 'masuk')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->sum('jumlah');

            $keluar = Laporan::where('jenis_laporan', 'keluar')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->sum('jumlah');

            $dailyMasuk[] = $masuk;
            $dailyKeluar[] = $keluar;
        }

        return [
            'months' => $months,
            'data_masuk' => $dataMasuk,
            'data_keluar' => $dataKeluar,
            'total_masuk_count' => $totalMasuk,
            'total_keluar_count' => $totalKeluar,
            'days' => $days,
            'daily_masuk' => $dailyMasuk,
            'daily_keluar' => $dailyKeluar,
        ];
    }

    /**
     * Export statistik to PDF
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $statistik = $this->getStatistikData();
        $chartData = $this->getChartData();

        $pdf = PDF::loadView('statistik.pdf', [
            'statistik' => $statistik,
            'chartData' => $chartData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Set options for better PDF rendering
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'dejavu sans'
        ]);

        $fileName = 'statistik-barang-' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    public function apiData()
    {
        $statistik = $this->getStatistikData();
        $chartData = $this->getChartData();

        return response()->json([
            'statistik' => $statistik,
            'chart_data' => $chartData,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Export statistik ke CSV - RAPIH
     */
    public function exportStatistikCSV()
    {
        $statistik = $this->getStatistikData();
        $chartData = $this->getChartData();

        $fileName = 'statistik-sistem-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($statistik, $chartData) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Header Company
            fputcsv($file, ['LAPORAN STATISTIK SISTEM MANAGEMENT STORAGE KERTAS'], ',');
            fputcsv($file, ['PT. PENGELOLA KERTAS'], ',');
            fputcsv($file, ['Dibuat pada: ' . date('d F Y H:i:s')], ',');
            fputcsv($file, [''], ','); // Empty line

            // ===== STATISTIK UTAMA =====
            fputcsv($file, ['STATISTIK UTAMA'], ',');
            fputcsv($file, ['Kategori', 'Jumlah', 'Keterangan'], ',');
            fputcsv($file, ['Total Barang Masuk', number_format($statistik['total_masuk']), 'item'], ',');
            fputcsv($file, ['Total Barang Keluar', number_format($statistik['total_keluar']), 'item'], ',');
            fputcsv($file, ['Total Laporan', number_format($statistik['total_laporan']), 'transaksi'], ',');
            fputcsv($file, ['Total Users', number_format($statistik['total_users']), 'pengguna'], ',');
            fputcsv($file, ['Stok Akhir', number_format($statistik['total_masuk'] - $statistik['total_keluar']), 'item'], ',');
            fputcsv($file, ['Rata-rata Harian', number_format($statistik['total_laporan'] / max($statistik['hari_operasional'], 1), 1), 'transaksi/hari'], ',');
            fputcsv($file, [''], ','); // Empty line

            // ===== AKTIVITAS BULAN INI =====
            fputcsv($file, ['AKTIVITAS BULAN INI'], ',');
            fputcsv($file, ['Kategori', 'Jumlah', 'Periode'], ',');
            fputcsv($file, ['Barang Masuk', number_format($statistik['bulan_ini_masuk']), date('F Y')], ',');
            fputcsv($file, ['Barang Keluar', number_format($statistik['bulan_ini_keluar']), date('F Y')], ',');
            fputcsv($file, ['Laporan Dibuat', number_format($statistik['bulan_ini_laporan']), date('F Y')], ',');
            fputcsv($file, [''], ','); // Empty line

            // ===== TREND 6 BULAN TERAKHIR =====
            fputcsv($file, ['TREND 6 BULAN TERAKHIR'], ',');

            // Header untuk trend
            $trendHeader = ['Bulan'];
            foreach ($chartData['months'] as $month) {
                $trendHeader[] = $month;
            }
            fputcsv($file, $trendHeader, ',');

            // Data masuk
            $masukData = ['Barang Masuk'];
            foreach ($chartData['data_masuk'] as $data) {
                $masukData[] = number_format($data);
            }
            fputcsv($file, $masukData, ',');

            // Data keluar
            $keluarData = ['Barang Keluar'];
            foreach ($chartData['data_keluar'] as $data) {
                $keluarData[] = number_format($data);
            }
            fputcsv($file, $keluarData, ',');
            fputcsv($file, [''], ','); // Empty line

            // ===== TOP 5 BARANG MASUK =====
            fputcsv($file, ['TOP 5 BARANG MASUK TERBANYAK'], ',');
            fputcsv($file, ['Peringkat', 'Kode Barang', 'Nama Barang', 'Total (item)'], ',');
            foreach ($statistik['top_barang_masuk'] as $index => $barang) {
                fputcsv($file, [
                    $index + 1,
                    $barang->kode_barang,
                    $barang->nama_barang,
                    number_format($barang->total)
                ], ',');
            }

            // Jika tidak ada data
            if ($statistik['top_barang_masuk']->isEmpty()) {
                fputcsv($file, ['-', '-', 'Tidak ada data', '0'], ',');
            }
            fputcsv($file, [''], ','); // Empty line

            // ===== TOP 5 BARANG KELUAR =====
            fputcsv($file, ['TOP 5 BARANG KELUAR TERBANYAK'], ',');
            fputcsv($file, ['Peringkat', 'Kode Barang', 'Nama Barang', 'Total (item)'], ',');
            foreach ($statistik['top_barang_keluar'] as $index => $barang) {
                fputcsv($file, [
                    $index + 1,
                    $barang->kode_barang,
                    $barang->nama_barang,
                    number_format($barang->total)
                ], ',');
            }

            // Jika tidak ada data
            if ($statistik['top_barang_keluar']->isEmpty()) {
                fputcsv($file, ['-', '-', 'Tidak ada data', '0'], ',');
            }
            fputcsv($file, [''], ','); // Empty line

            // ===== TOP 5 USER AKTIF =====
            fputcsv($file, ['TOP 5 USER PALING AKTIF'], ',');
            fputcsv($file, ['Peringkat', 'Nama User', 'Email', 'Total Laporan'], ',');
            foreach ($statistik['top_users'] as $index => $user) {
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $user->laporan_count
                ], ',');
            }

            // Jika tidak ada data
            if ($statistik['top_users']->isEmpty()) {
                fputcsv($file, ['-', '-', 'Tidak ada data', '0'], ',');
            }
            fputcsv($file, [''], ','); // Empty line

            // ===== SUMMARY =====
            fputcsv($file, ['RINGKASAN KINERJA SISTEM'], ',');
            fputcsv($file, ['Metrik', 'Nilai', 'Keterangan'], ',');
            fputcsv($file, ['Total Hari Operasional', $statistik['hari_operasional'], 'hari'], ',');
            fputcsv($file, ['Efisiensi Sistem', number_format(($statistik['total_laporan'] / max($statistik['hari_operasional'], 1)), 1), 'transaksi/hari'], ',');
            fputcsv($file, ['Tingkat Aktivitas', number_format(($statistik['bulan_ini_laporan'] / max($statistik['total_laporan'], 1) * 100), 1) . '%', 'bulan ini vs total'], ',');
            fputcsv($file, ['Rasio Masuk/Keluar', number_format(($statistik['total_masuk'] / max($statistik['total_keluar'], 1)), 2), 'ratio inventory'], ',');

            // Footer
            fputcsv($file, [''], ',');
            fputcsv($file, ['Catatan:'], ',');
            fputcsv($file, ['- Data diambil hingga: ' . now()->format('d F Y H:i')], ',');
            fputcsv($file, ['- Sistem Management Storage Kertas v1.0'], ',');
            fputcsv($file, ['- © ' . date('Y') . ' PT. Pengelola Kertas Mentah'], ',');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
