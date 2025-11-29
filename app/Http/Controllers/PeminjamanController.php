<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Laporan;
use App\Models\User;
use App\Notifications\PeminjamanNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        $user = auth()->user();

        // FILTER OTOMATIS BERDASARKAN ROLE
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isPetugasPengajuan()) {
            $query->where('status', 'pending');
        } elseif ($user->isManajerPersetujuan()) {
            $query->where('status', 'validated');
        } elseif ($user->isPetugasBarangKeluar()) {
            $query->where('status', 'approved');
        }
        // MAIN ADMIN bisa lihat SEMUA data tanpa filter otomatis

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        $baseQuery = Peminjaman::query();
        if ($user->isUser()) {
            $baseQuery->where('user_id', $user->id);
        } elseif ($user->isPetugasPengajuan()) {
            $baseQuery->where('status', 'pending');
        } elseif ($user->isManajerPersetujuan()) {
            $baseQuery->where('status', 'validated');
        } elseif ($user->isPetugasBarangKeluar()) {
            $baseQuery->where('status', 'approved');
        }
        // MAIN ADMIN hitung semua data

        $statistik = [
            'total' => $baseQuery->count(),
            'pending' => $baseQuery->clone()->where('status', 'pending')->count(),
            'validated' => $baseQuery->clone()->where('status', 'validated')->count(),
            'approved' => $baseQuery->clone()->where('status', 'approved')->count(),
            'processed' => $baseQuery->clone()->where('status', 'processed')->count(),
            'completed' => $baseQuery->clone()->where('status', 'completed')->count(),
            'returned' => $baseQuery->clone()->where('status', 'returned')->count(),
            'rejected' => $baseQuery->clone()->where('status', 'rejected')->count(),
        ];

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'statistik' => $statistik,
            'title' => $this->getPageTitle($user),
        ]);
    }

    private function getPageTitle($user)
    {
        if ($user->isUser()) return 'Peminjaman Saya';
        if ($user->isPetugasPengajuan()) return 'Validasi Pengajuan Barang';
        if ($user->isManajerPersetujuan()) return 'Persetujuan Peminjaman';
        if ($user->isPetugasBarangKeluar()) return 'Proses Barang Keluar';
        if ($user->isAdmin()) return 'Management Peminjaman';
        return 'Management Peminjaman';
    }

    public function showValidationForm($id)
    {
        if (!auth()->user()->isPetugasPengajuan() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Petugas Pengajuan atau Main Admin yang dapat memvalidasi.');
        }

        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan pending yang dapat divalidasi.');
        }

        $stokTersedia = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->where('jumlah', '>', 0)
            ->get();

        $totalStok = $stokTersedia->sum('jumlah');
        $cukup = $totalStok >= $peminjaman->jumlah_pinjam;

        return view('peminjaman.validation-form', [
            'peminjaman' => $peminjaman,
            'stokTersedia' => $stokTersedia,
            'totalStok' => $totalStok,
            'cukup' => $cukup,
            'title' => 'Validasi Pengajuan Barang'
        ]);
    }

    public function processValidation(Request $request, $id)
    {
        if (!auth()->user()->isPetugasPengajuan() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Petugas Pengajuan atau Main Admin yang dapat memvalidasi.');
        }

        $request->validate([
            'status_validasi' => 'required|in:tersedia,tidak_tersedia',
            'catatan_validasi' => 'nullable|string|max:500',
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan pending yang dapat divalidasi.');
        }

        $status = $request->status_validasi === 'tersedia' ? 'validated' : 'rejected';
        $catatan = $request->catatan_validasi ?:
            ($request->status_validasi === 'tersedia' ? '✅ Barang tersedia' : '❌ Barang tidak tersedia');

        $peminjaman->update([
            'status' => $status,
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'catatan_admin' => $catatan,
        ]);

        if ($request->status_validasi === 'tersedia') {
            $manajers = User::where('email', 'admin2@storage.com')->orWhere('isManajerPersetujuan', true)->get();
            if ($manajers->isNotEmpty()) {
                Notification::send($manajers, new PeminjamanNotification($peminjaman, 'validated', auth()->user()));
            }
        }

        $peminjaman->user->notify(new PeminjamanNotification($peminjaman, $status, auth()->user()));

        $message = $request->status_validasi === 'tersedia'
            ? 'Pengajuan divalidasi - Barang tersedia dan diteruskan ke manajer'
            : 'Pengajuan ditolak - Barang tidak tersedia';

        return redirect()->route('peminjaman.index')
            ->with('success', $message);
    }

    public function showValidationDetails($id)
    {
        if (!auth()->user()->isManajerPersetujuan() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Manajer Persetujuan atau Main Admin yang dapat melihat detail validasi.');
        }

        $peminjaman = Peminjaman::with(['barang', 'validatedBy'])->findOrFail($id);

        if ($peminjaman->status !== 'validated') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang sudah divalidasi yang dapat dilihat detailnya.');
        }

        return view('peminjaman.validation-details', [
            'peminjaman' => $peminjaman,
            'title' => 'Detail Validasi Pengajuan'
        ]);
    }

    public function approve($id)
    {
        if (!auth()->user()->isManajerPersetujuan() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Manajer Persetujuan atau Main Admin yang dapat menyetujui.');
        }

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'validated') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang sudah divalidasi yang dapat disetujui.');
        }

        $peminjaman->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $petugasGudang = User::where('email', 'admin3@storage.com')->orWhere('isPetugasBarangKeluar', true)->get();
        if ($petugasGudang->isNotEmpty()) {
            Notification::send($petugasGudang, new PeminjamanNotification($peminjaman, 'approved', auth()->user()));
        }
        $peminjaman->user->notify(new PeminjamanNotification($peminjaman, 'approved', auth()->user()));

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan disetujui! Petugas barang keluar akan memproses.');
    }

    public function reject(Request $request, $id)
    {
        if (!auth()->user()->isManajerPersetujuan() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Manajer Persetujuan atau Main Admin yang dapat menolak.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if (!in_array($peminjaman->status, ['pending', 'validated'])) {
            return redirect()->back()->with('error', 'Hanya pengajuan pending atau validated yang dapat ditolak.');
        }

        $peminjaman->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);

        $peminjaman->user->notify(new PeminjamanNotification($peminjaman, 'rejected', auth()->user()));

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function processBarangKeluar($id)
    {
        if (!auth()->user()->isPetugasBarangKeluar() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Petugas Barang Keluar atau Main Admin yang dapat memproses barang.');
        }

        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang disetujui yang dapat diproses.');
        }

        $barangAsli = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->first();

        if (!$barangAsli || $barangAsli->jumlah < $peminjaman->jumlah_pinjam) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi!');
        }

        $barangAsli->jumlah -= $peminjaman->jumlah_pinjam;
        $barangAsli->save();

        $peminjaman->update([
            'status' => 'processed',
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        Laporan::create([
            'jenis_laporan' => 'keluar',
            'kode_barang' => $peminjaman->barang->kode_barang,
            'nama_barang' => $peminjaman->barang->nama_barang,
            'jumlah' => $peminjaman->jumlah_pinjam,
            'satuan' => $peminjaman->barang->satuan,
            'keterangan' => 'PEMINJAMAN: ' . $peminjaman->kode_peminjaman . ' - ' . $peminjaman->keperluan,
            'lokasi' => 'Peminjaman oleh ' . $peminjaman->user->name,
            'user_id' => auth()->id(),
        ]);

        $peminjaman->user->notify(new PeminjamanNotification($peminjaman, 'processed', auth()->user()));

        return redirect()->route('peminjaman.index')
            ->with('success', 'Barang berhasil dikeluarkan dari gudang! Menunggu konfirmasi user.');
    }

    public function completePeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Hanya user yang meminjam yang dapat menyelesaikan peminjaman.');
        }

        if ($peminjaman->status !== 'processed') {
            return redirect()->back()->with('error', 'Hanya peminjaman yang sudah diproses yang dapat diselesaikan.');
        }

        $peminjaman->update([
            'status' => 'completed',
        ]);

        $admins = User::where('email', 'like', '%admin%@storage.com')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanNotification($peminjaman, 'completed', auth()->user()));
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diselesaikan! Terima kasih.');
    }

    public function create()
    {
        if (!auth()->user()->isUser()) {
            abort(403, 'Hanya User yang dapat mengajukan peminjaman.');
        }

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
        if (!auth()->user()->isUser()) {
            abort(403, 'Hanya User yang dapat mengajukan peminjaman.');
        }

        $request->validate([
            'barang_id' => 'required|exists:laporans,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,bmp,gif,svg|max:2048',
        ]);

        $barang = Laporan::findOrFail($request->barang_id);
        if ($barang->jumlah < $request->jumlah_pinjam) {
            return back()->withErrors([
                'jumlah_pinjam' => 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->jumlah
            ])->withInput();
        }

        $kodePeminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);

        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $kodePeminjaman . '.' . $file->getClientOriginalExtension();
            $documentPath = $file->storeAs('documents', $filename, 'public');
        }

        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => $kodePeminjaman,
            'user_id' => auth()->id(),
            'barang_id' => $request->barang_id,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'keperluan' => $request->keperluan,
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        $petugasPengajuan = User::where('email', 'admin1@storage.com')->orWhere('isPetugasPengajuan', true)->get();
        if ($petugasPengajuan->isNotEmpty()) {
            Notification::send($petugasPengajuan, new PeminjamanNotification($peminjaman, 'created'));
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil diajukan! Menunggu validasi petugas.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang', 'validatedBy', 'approvedBy', 'completedBy'])->findOrFail($id);

        if (auth()->user()->isUser() && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();

        return view('peminjaman.show', [
            'peminjaman' => $peminjaman,
            'title' => 'Detail Peminjaman',
            'isAdmin' => $user->isAdmin(),
            'isPetugasPengajuan' => $user->isPetugasPengajuan(),
            'isManajerPersetujuan' => $user->isManajerPersetujuan(),
            'isPetugasBarangKeluar' => $user->isPetugasBarangKeluar(),
            'currentUser' => $user,
        ]);
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

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

        if (auth()->user()->isUser()) {
            if ($peminjaman->user_id !== auth()->id() || $peminjaman->status !== 'pending') {
                abort(403, 'Unauthorized action.');
            }
        }

        $peminjaman->delete();

        $message = auth()->user()->isUser()
            ? 'Pengajuan peminjaman berhasil dibatalkan!'
            : 'Pengajuan peminjaman berhasil dihapus!';

        return redirect()->route('peminjaman.index')
            ->with('success', $message);
    }

    public function return($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Hanya user yang meminjam yang dapat mengembalikan barang.');
        }

        if ($peminjaman->status !== 'completed') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman yang sudah selesai yang dapat dikembalikan.');
        }

        // 1. Cari laporan barang masuk yang sesuai dengan barang yang dipinjam
        $laporanMasuk = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->first();

        if (!$laporanMasuk) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Laporan barang masuk tidak ditemukan.');
        }

        // 2. Cari laporan barang keluar untuk peminjaman ini
        $laporanKeluar = Laporan::where('jenis_laporan', 'keluar')
            ->where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('keterangan', 'like', '%' . $peminjaman->kode_peminjaman . '%')
            ->first();

        // 3. UPDATE laporan barang masuk yang sudah ada (tambah stok)
        $laporanMasuk->update([
            'jumlah' => $laporanMasuk->jumlah + $peminjaman->jumlah_pinjam,
            'keterangan' => $laporanMasuk->keterangan . ' | PENGEMBALIAN: ' . $peminjaman->kode_peminjaman . ' oleh ' . $peminjaman->user->name . ' (' . now()->format('d/m/Y') . ')',
        ]);

        // 4. HAPUS laporan barang keluar
        if ($laporanKeluar) {
            $laporanKeluar->delete();
        }

        // 5. Update status peminjaman
        $peminjaman->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        // 6. Kirim notifikasi
        $admins = User::where('email', 'like', '%admin%@storage.com')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanNotification($peminjaman, 'returned', auth()->user()));
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Barang berhasil dikembalikan! Stok telah ditambahkan ke laporan barang masuk dan laporan barang keluar telah dihapus.');
    }
}
