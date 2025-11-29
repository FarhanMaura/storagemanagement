<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class BarcodeController extends Controller
{
    public function index()
    {
        $barang = Laporan::select('kode_barang', 'nama_barang')
            ->distinct()
            ->orderBy('kode_barang')
            ->get();

        return view('barcode.index', [
            'barang' => $barang,
            'title' => 'Generate Barcode'
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50',
            'jenis_barcode' => 'required|in:qr,barcode,keduanya',
            'quantity' => 'required|integer|min:1',
        ]);

        $barang = Laporan::where('kode_barang', $request->kode_barang)
            ->firstOrFail();

        $quantity = $request->quantity;
        $dns1d = new DNS1D();
        $dns2d = new DNS2D();
        $barcodes = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $code = $barang->kode_barang . '-' . $i;
            $barcode1D = $dns1d->getBarcodePNG($code, 'C128', 3, 60);
            $barcodeQR = $dns2d->getBarcodePNG($code, 'QRCODE', 8, 8);
            $barcodes[] = [
                'code' => $code,
                'barcode1D' => 'data:image/png;base64,' . $barcode1D,
                'barcodeQR' => 'data:image/png;base64,' . $barcodeQR,
            ];
        }

        $data = [
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'total_masuk' => Laporan::where('kode_barang', $request->kode_barang)
                                ->where('jenis_laporan', 'masuk')
                                ->sum('jumlah'),
            'total_keluar' => Laporan::where('kode_barang', $request->kode_barang)
                                ->where('jenis_laporan', 'keluar')
                                ->sum('jumlah'),
            'stok' => Laporan::where('kode_barang', $request->kode_barang)
                        ->where('jenis_laporan', 'masuk')
                        ->sum('jumlah') -
                        Laporan::where('kode_barang', $request->kode_barang)
                        ->where('jenis_laporan', 'keluar')
                        ->sum('jumlah'),
        ];

        return view('barcode.result', [
            'barang' => $barang,
            'data' => $data,
            'barcodes' => $barcodes,
            'jenis_barcode' => $request->jenis_barcode,
            'title' => 'Barcode - ' . $barang->kode_barang,
        ]);
    }

    public function scan($kode_barang)
    {
        $barang = Laporan::where('kode_barang', $kode_barang)->firstOrFail();

        $data = [
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'total_masuk' => Laporan::where('kode_barang', $kode_barang)
                                ->where('jenis_laporan', 'masuk')
                                ->sum('jumlah'),
            'total_keluar' => Laporan::where('kode_barang', $kode_barang)
                                ->where('jenis_laporan', 'keluar')
                                ->sum('jumlah'),
            'stok' => Laporan::where('kode_barang', $kode_barang)
                        ->where('jenis_laporan', 'masuk')
                        ->sum('jumlah') -
                    Laporan::where('kode_barang', $kode_barang)
                        ->where('jenis_laporan', 'keluar')
                        ->sum('jumlah'),
            'riwayat' => Laporan::where('kode_barang', $kode_barang)
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function printLabel($kode_barang, $jenis)
    {
        $barang = Laporan::where('kode_barang', $kode_barang)->firstOrFail();

        $dns1d = new DNS1D();
        $dns2d = new DNS2D();

        // Generate PNG base64 for print - FIXED: remove color array
        $barcode1D = $dns1d->getBarcodePNG($barang->kode_barang, 'C128', 3, 60);
        $barcode1DBase64 = 'data:image/png;base64,' . $barcode1D;

        $barcodeQR = $dns2d->getBarcodePNG($barang->kode_barang, 'QRCODE', 10, 10);
        $barcodeQRBase64 = 'data:image/png;base64,' . $barcodeQR;

        return view('barcode.print', [
            'barang' => $barang,
            'barcode1D' => $barcode1DBase64,
            'barcodeQR' => $barcodeQRBase64,
            'jenis' => $jenis
        ]);
    }

    public function print($kode_barang, Request $request)
    {
        $jenis = $request->get('jenis', 'keduanya');
        $quantity = $request->get('quantity', 1);

        $barang = Laporan::where('kode_barang', $kode_barang)->first();

        if (!$barang) {
            abort(404, 'Barang tidak ditemukan');
        }

        $dns1d = new DNS1D();
        $dns2d = new DNS2D();
        $barcodes = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $code = $barang->kode_barang . '-' . $i;
            
            // Generate barcode
            $barcodeQR = $dns2d->getBarcodePNG(
                url('/scan/' . $barang->kode_barang), // QR still points to main product scan URL
                'QRCODE',
                12,
                12
            );
            $barcodeQR = 'data:image/png;base64,' . $barcodeQR;

            $barcode1D = $dns1d->getBarcodePNG(
                $code, // 1D barcode uses the specific item code
                'C128',
                2,
                60
            );
            $barcode1D = 'data:image/png;base64,' . $barcode1D;

            $barcodes[] = [
                'code' => $code,
                'barcodeQR' => $barcodeQR,
                'barcode1D' => $barcode1D
            ];
        }

        return view('barcode.print', [
            'barang' => $barang,
            'barcodes' => $barcodes,
            'jenis' => $jenis
        ]);
    }
}
