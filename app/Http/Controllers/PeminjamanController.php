<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Laporan;
use App\Models\User;
use App\Notifications\PeminjamanNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);
        $user = auth()->user();

        // Admin lihat semua, User hanya lihat peminjaman miliknya
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        $baseQuery = Peminjaman::query();
        if ($user->isUser()) {
            $baseQuery->where('user_id', $user->id);
        }

        $statistik = [
            'total' => $baseQuery->count(),
            'pending' => $baseQuery->clone()->where('status', 'pending')->count(),
            'approved' => $baseQuery->clone()->where('status', 'approved')->count(),
            'processed' => $baseQuery->clone()->where('status', 'processed')->count(),
            'completed' => $baseQuery->clone()->where('status', 'completed')->count(),
            'returned' => $baseQuery->clone()->where('status', 'returned')->count(),
            'rejected' => $baseQuery->clone()->where('status', 'rejected')->count(),
        ];

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman,
            'statistik' => $statistik,
            'title' => $user->isAdmin() ? 'Kelola Peminjaman Barang' : 'Peminjaman Saya',
        ]);
    }

    public function approve($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang dapat menyetujui peminjaman.');
        }

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan pending yang dapat disetujui.');
        }

        $peminjaman->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $peminjaman->user->notify(new PeminjamanNotification($peminjaman, 'approved', auth()->user()));

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman disetujui!');
    }

    public function reject(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang dapat menolak peminjaman.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan pending yang dapat ditolak.');
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
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin Utama yang dapat memproses penyerahan barang.');
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
            'keterangan' => 'PEMINJAMAN: ' . $peminjaman->kode_peminjaman . ' - ' . $peminjaman->keperluan,
            'lokasi' => 'Peminjaman oleh ' . $peminjaman->user->name,
            'user_id' => auth()->id(),
        ]);

        $peminjaman->user->notify(new PeminjamanNotification($peminjaman, 'processed', auth()->user()));

        return redirect()->route('peminjaman.index')
            ->with('success', 'Barang berhasil dikeluarkan! Menunggu penerimaan user.');
    }

    public function completePeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak dapat menyelesaikan peminjaman ini.');
        }

        if ($peminjaman->status !== 'processed') {
            return redirect()->back()->with('error', 'Hanya peminjaman diproses yang dapat diselesaikan.');
        }

        $peminjaman->update([
            'status' => 'completed',
        ]);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanNotification($peminjaman, 'completed', auth()->user()));
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diselesaikan.');
    }

    public function create()
    {
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
        $request->validate([
            'barang_id' => 'required|exists:laporans,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,bmp,gif,svg|max:2048',
        ]);

        $barang = Laporan::findOrFail($request->barang_id);
        if ($barang->jumlah < $request->jumlah_pinjam) {
            return back()->withErrors([
                'jumlah_pinjam' => 'Stok barang tidak mencukupi. Stok tersedia: ' . $barang->jumlah
            ])->withInput();
        }

        $kodePeminjaman = 'PINJ-' . date('Ymd') . '-' . str_pad(Peminjaman::count() + 1, 4, '0', STR_PAD_LEFT);

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

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanNotification($peminjaman, 'created'));
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil diajukan! Menunggu persetujuan Admin.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'barang', 'approvedBy', 'completedBy'])->findOrFail($id);

        if ($user = auth()->user()) {
            if ($user->isUser() && $peminjaman->user_id !== $user->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view('peminjaman.show', [
            'peminjaman' => $peminjaman,
            'title' => 'Detail Peminjaman',
            'isAdmin' => auth()->user()->isAdmin(),
            'currentUser' => auth()->user(),
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

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibatalkan/dihapus!');
    }

    public function return($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya peminjam atau Admin yang dapat mengembalikan barang.');
        }

        if (!in_array($peminjaman->status, ['completed', 'processed'])) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman aktif yang dapat dikembalikan.');
        }

        $laporanMasuk = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->first();

        if ($laporanMasuk) {
            $laporanMasuk->update([
                'jumlah' => $laporanMasuk->jumlah + $peminjaman->jumlah_pinjam,
                'keterangan' => $laporanMasuk->keterangan . ' | PENGEMBALIAN: ' . $peminjaman->kode_peminjaman . ' oleh ' . $peminjaman->user->name . ' (' . now()->format('d/m/Y') . ')',
            ]);
        }

        $laporanKeluar = Laporan::where('jenis_laporan', 'keluar')
            ->where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('keterangan', 'like', '%' . $peminjaman->kode_peminjaman . '%')
            ->first();

        if ($laporanKeluar) {
            $laporanKeluar->delete();
        }

        $peminjaman->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanNotification($peminjaman, 'returned', auth()->user()));
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Barang berhasil dikembalikan! Stok barang telah diperbarui.');
    }

    public function downloadDocument($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if (auth()->user()->isUser() && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$peminjaman->document_path || !Storage::disk('public')->exists($peminjaman->document_path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->response($peminjaman->document_path);
    }
}
