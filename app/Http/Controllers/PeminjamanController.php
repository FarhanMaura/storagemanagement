<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Laporan;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        // Filter berdasarkan role user
        if (auth()->user()->email !== 'admin@storage.com') {
            $query->where('user_id', auth()->id());
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        $statistik = [
            'total' => Peminjaman::when(auth()->user()->email !== 'admin@storage.com',
                fn($q) => $q->where('user_id', auth()->id()))->count(),
            'pending' => Peminjaman::when(auth()->user()->email !== 'admin@storage.com',
                fn($q) => $q->where('user_id', auth()->id()))->pending()->count(),
            'approved' => Peminjaman::when(auth()->user()->email !== 'admin@storage.com',
                fn($q) => $q->where('user_id', auth()->id()))->approved()->count(),
        ];

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'statistik' => $statistik,
            'title' => 'Pengajuan Peminjaman Barang',
            'isAdmin' => auth()->user()->email === 'admin@storage.com'
        ]);
    }

    public function create()
    {
        // Hanya user biasa yang bisa mengajukan peminjaman
        if (auth()->user()->email === 'admin@storage.com') {
            abort(403, 'Admin tidak dapat mengajukan peminjaman.');
        }

        // Hanya ambil barang dengan jenis 'masuk' dan jumlah > 0
        $barangTersedia = Laporan::where('jenis_laporan', 'masuk')
            ->where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('peminjaman.create', [
            'barangTersedia' => $barangTersedia,
            'title' => 'Ajukan Peminjaman Barang'
        ]);
    }

    public function store(Request $request)
    {
        // Hanya user biasa yang bisa mengajukan peminjaman
        if (auth()->user()->email === 'admin@storage.com') {
            abort(403, 'Admin tidak dapat mengajukan peminjaman.');
        }

        $request->validate([
            'barang_id' => 'required|exists:laporans,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
        ]);

        // Cek stok barang
        $barang = Laporan::findOrFail($request->barang_id);
        if ($barang->jumlah < $request->jumlah_pinjam) {
            return back()->withErrors([
                'jumlah_pinjam' => 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->jumlah
            ])->withInput();
        }

        // Generate kode peminjaman
        $kodePeminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);

        Peminjaman::create([
            'kode_peminjaman' => $kodePeminjaman,
            'user_id' => auth()->id(),
            'barang_id' => $request->barang_id,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keperluan' => $request->keperluan,
            'status' => 'pending',
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang'])->findOrFail($id);

        // Authorization check
        if (auth()->user()->email !== 'admin@storage.com' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('peminjaman.show', [
            'peminjaman' => $peminjaman,
            'title' => 'Detail Peminjaman',
            'isAdmin' => auth()->user()->email === 'admin@storage.com'
        ]);
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        // Hanya user yang membuat dan status pending yang bisa edit
        if ($peminjaman->user_id !== auth()->id() || $peminjaman->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $barangTersedia = Laporan::where('jenis_laporan', 'masuk')
            ->where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('peminjaman.edit', [
            'peminjaman' => $peminjaman,
            'barangTersedia' => $barangTersedia,
            'title' => 'Edit Pengajuan Peminjaman'
        ]);
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Hanya user yang membuat dan status pending yang bisa edit
        if ($peminjaman->user_id !== auth()->id() || $peminjaman->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'barang_id' => 'required|exists:laporans,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
        ]);

        // Cek stok barang (kecuali barang yang sama)
        $barang = Laporan::findOrFail($request->barang_id);
        $stokTersedia = $barang->id == $peminjaman->barang_id
            ? $barang->jumlah + $peminjaman->jumlah_pinjam
            : $barang->jumlah;

        if ($stokTersedia < $request->jumlah_pinjam) {
            return back()->withErrors([
                'jumlah_pinjam' => 'Stok barang tidak mencukupi. Stok tersedia: ' . $stokTersedia
            ])->withInput();
        }

        $peminjaman->update([
            'barang_id' => $request->barang_id,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keperluan' => $request->keperluan,
        ]);

        return redirect()->route('peminjaman.show', $peminjaman->id)
            ->with('success', 'Pengajuan peminjaman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // PERBAIKI AUTHORIZATION: Admin bisa hapus semua status, user hanya bisa hapus yang pending
        if (auth()->user()->email === 'admin@storage.com') {
            // Admin bisa hapus semua peminjaman regardless of status
        } elseif ($peminjaman->user_id !== auth()->id() || $peminjaman->status !== 'pending') {
            // User biasa hanya bisa hapus peminjaman mereka sendiri yang masih pending
            abort(403, 'Unauthorized action.');
        }

        $peminjaman->delete();

        $message = auth()->user()->email === 'admin@storage.com'
            ? 'Pengajuan peminjaman berhasil dihapus!'
            : 'Pengajuan peminjaman berhasil dibatalkan!';

        return redirect()->route('peminjaman.index')
            ->with('success', $message);
    }

    public function approve($id)
    {
        // Hanya admin yang bisa approve
        if (auth()->user()->email !== 'admin@storage.com') {
            abort(403, 'Hanya admin yang dapat menyetujui peminjaman.');
        }

        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if (!$peminjaman->canBeApproved()) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Peminjaman tidak dapat disetujui. Stok tidak mencukupi atau status tidak valid.');
        }

        // CARI BARANG ASLI untuk mengurangi stok
        $barangAsli = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$barangAsli) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Barang asli tidak ditemukan.');
        }

        // Kurangi stok barang asli
        $barangAsli->jumlah -= $peminjaman->jumlah_pinjam;
        $barangAsli->save();

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Buat laporan barang keluar otomatis (hanya untuk tracking)
        Laporan::create([
            'jenis_laporan' => 'keluar',
            'kode_barang' => $peminjaman->barang->kode_barang,
            'nama_barang' => $peminjaman->barang->nama_barang,
            'jumlah' => $peminjaman->jumlah_pinjam,
            'satuan' => $peminjaman->barang->satuan,
            'keterangan' => 'PEMINJAMAN: ' . $peminjaman->kode_peminjaman . ' - ' . $peminjaman->keperluan . ' (Oleh: ' . $peminjaman->user->name . ')',
            'lokasi' => 'Peminjaman oleh ' . $peminjaman->user->name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil disetujui dan stok barang telah dikurangi.');
    }

    public function reject(Request $request, $id)
    {
        // Hanya admin yang bisa reject
        if (auth()->user()->email !== 'admin@storage.com') {
            abort(403, 'Hanya admin yang dapat menolak peminjaman.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function return($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        // Hanya USER yang bersangkutan yang bisa mengembalikan (admin tidak bisa)
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Hanya user yang meminjam yang dapat mengembalikan barang.');
        }

        if ($peminjaman->status !== 'approved') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        // CARI BARANG ASLI (bukan buat record baru)
        $barangAsli = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$barangAsli) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Barang asli tidak ditemukan.');
        }

        // Kembalikan stok barang asli (tambah jumlahnya)
        $barangAsli->jumlah += $peminjaman->jumlah_pinjam;
        $barangAsli->save();

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        // Buat laporan barang masuk otomatis (pengembalian) - TAPI dengan jumlah 0 untuk tracking saja
        Laporan::create([
            'jenis_laporan' => 'masuk',
            'kode_barang' => $peminjaman->barang->kode_barang,
            'nama_barang' => $peminjaman->barang->nama_barang,
            'jumlah' => 0, // Jumlah 0 karena sudah ditambahkan ke barang asli
            'satuan' => $peminjaman->barang->satuan,
            'keterangan' => 'PENGEMBALIAN: ' . $peminjaman->kode_peminjaman . ' - ' . $peminjaman->keperluan,
            'lokasi' => $barangAsli->lokasi,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Barang berhasil dikembalikan dan stok telah dikembalikan ke inventory.');
    }
}
