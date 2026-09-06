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

        $stokLayak = $barangAsli ? $barangAsli->jumlah_baik : 0;
        if (!$barangAsli || $stokLayak < $peminjaman->jumlah_pinjam) {
            return redirect()->back()->with('error', 'Stok barang yang layak pakai tidak mencukupi (Tersedia: ' . $stokLayak . ')!');
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
            'jumlah_rusak' => 0,
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
        $stokLayak = $barang->jumlah_baik;

        if ($stokLayak < $request->jumlah_pinjam) {
            $pesan = $stokLayak <= 0
                ? 'Barang ini tidak dapat dipinjam karena stok layak habis / seluruh unit rusak (Rusak: ' . ($barang->jumlah_rusak ?? 0) . ' unit).'
                : 'Stok barang yang layak pakai tidak mencukupi. Stok layak tersedia: ' . $stokLayak . ' unit (Rusak: ' . ($barang->jumlah_rusak ?? 0) . ' unit).';

            return back()->withErrors([
                'jumlah_pinjam' => $pesan
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
        $stokLayakTersedia = $barang->id == $peminjaman->barang_id
            ? $barang->jumlah_baik + $peminjaman->jumlah_pinjam
            : $barang->jumlah_baik;

        if ($stokLayakTersedia < $request->jumlah_pinjam) {
            $pesan = $stokLayakTersedia <= 0
                ? 'Barang ini tidak dapat dipinjam karena seluruh unit rusak atau stok habis.'
                : 'Stok barang yang layak pakai tidak mencukupi. Stok layak tersedia: ' . $stokLayakTersedia . ' unit (Rusak: ' . ($barang->jumlah_rusak ?? 0) . ' unit).';

            return back()->withErrors([
                'jumlah_pinjam' => $pesan
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

    public function return(Request $request, $id)
    {
        $peminjaman = Peminjaman::with(['barang', 'user'])->findOrFail($id);

        if ($peminjaman->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya peminjam atau Admin yang dapat mengembalikan barang.');
        }

        if (!in_array($peminjaman->status, ['completed', 'processed'])) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya peminjaman aktif yang dapat dikembalikan.');
        }

        $request->validate([
            'jumlah_rusak_kembali' => 'nullable|integer|min:0|max:' . $peminjaman->jumlah_pinjam,
            'catatan_kembali' => 'nullable|string|max:500',
        ], [
            'jumlah_rusak_kembali.max' => 'Jumlah rusak tidak boleh melebihi jumlah pinjam (' . $peminjaman->jumlah_pinjam . ' unit).',
        ]);

        $jumlahRusak = (int) $request->input('jumlah_rusak_kembali', 0);
        $jumlahBaik = max(0, $peminjaman->jumlah_pinjam - $jumlahRusak);

        $laporanMasuk = Laporan::where('kode_barang', $peminjaman->barang->kode_barang)
            ->where('jenis_laporan', 'masuk')
            ->first();

        if ($laporanMasuk) {
            $laporanMasuk->update([
                'jumlah' => $laporanMasuk->jumlah + $peminjaman->jumlah_pinjam,
                'jumlah_rusak' => ($laporanMasuk->jumlah_rusak ?? 0) + $jumlahRusak,
                'keterangan' => ($laporanMasuk->keterangan ? $laporanMasuk->keterangan . ' | ' : '') . 
                    'PENGEMBALIAN: ' . $peminjaman->kode_peminjaman . ' (' . $jumlahBaik . ' Baik, ' . $jumlahRusak . ' Rusak) oleh ' . $peminjaman->user->name . ' (' . now()->format('d/m/Y') . ')',
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
            'jumlah_rusak_kembali' => $jumlahRusak,
            'catatan_kembali' => $request->catatan_kembali,
            'returned_at' => now(),
        ]);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PeminjamanNotification($peminjaman, 'returned', auth()->user()));
        }

        $pesan = 'Barang berhasil dikembalikan! Total: ' . $peminjaman->jumlah_pinjam . ' unit (' . $jumlahBaik . ' kondisi baik, ' . $jumlahRusak . ' kondisi rusak tercatat).';

        return redirect()->route('peminjaman.show', $peminjaman->id)
            ->with('success', $pesan);
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
