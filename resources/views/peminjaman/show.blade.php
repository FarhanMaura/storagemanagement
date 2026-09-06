<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Banner -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-white mb-2">📋 Detail Peminjaman</h1>
                                <p class="text-purple-100">Informasi lengkap pengajuan peminjaman</p>
                            </div>
                            <div class="mt-4 md:mt-0 flex space-x-3">
                                <a href="{{ route('peminjaman.index') }}"
                                   class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-300 border border-white/30">
                                    ← Kembali
                                </a>
                                @if(!$isAdmin && $peminjaman->status === 'pending' && $peminjaman->user_id === auth()->id())
                                    <a href="{{ route('peminjaman.edit', $peminjaman->id) }}"
                                       class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50 transition-all duration-300 font-semibold">
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Status Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $peminjaman->status_color }}">
                                {{ $peminjaman->status_text }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Kode: {{ $peminjaman->kode_peminjaman }}
                            </span>
                        </div>
                        @if(($peminjaman->user_id === auth()->id() && $peminjaman->status === 'pending') || $isAdmin)
                            <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-300 font-semibold text-sm"
                                        onclick="return confirm('Yakin {{ $isAdmin ? 'hapus' : 'batalkan' }} pengajuan peminjaman ini?')">
                                    {{ $isAdmin ? 'Hapus' : 'Batalkan' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($peminjaman->catatan_admin)
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border-b border-yellow-200 dark:border-yellow-700 px-6 py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="text-yellow-600 dark:text-yellow-400">💡</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>Catatan Admin:</strong> {{ $peminjaman->catatan_admin }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Informasi Peminjaman -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                                    Informasi Barang
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Barang yang Dipinjam</label>
                                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $peminjaman->barang->nama_barang }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kode: {{ $peminjaman->barang->kode_barang }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah Pinjam</label>
                                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ $peminjaman->jumlah_pinjam }}
                                            <span class="text-lg text-gray-600 dark:text-gray-400">{{ $peminjaman->barang->satuan }}</span>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Stok Layak Pakai: <span class="font-semibold text-green-600 dark:text-green-400">{{ $peminjaman->barang->jumlah_baik }} unit</span>
                                            @if(($peminjaman->barang->jumlah_rusak ?? 0) > 0)
                                                <span class="text-xs text-red-500 dark:text-red-400 ml-1">({{ $peminjaman->barang->jumlah_rusak }} rusak)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Keperluan -->
                            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3 flex items-center">
                                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                                    Keperluan Peminjaman
                                </h3>
                                <p class="text-blue-700 dark:text-blue-300">{{ $peminjaman->keperluan }}</p>
                            </div>

                            <!-- Dokumen Surat Perintah/Izin -->
                            @if($peminjaman->document_path)
                            <div class="bg-orange-50 dark:bg-orange-900/30 rounded-xl p-6 border border-orange-200 dark:border-orange-700">
                                <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200 mb-3 flex items-center">
                                    <span class="w-2 h-6 bg-orange-500 rounded-full mr-3"></span>
                                    Surat Perintah/Izin
                                </h3>
                                <a href="{{ route('peminjaman.document', $peminjaman->id) }}" target="_blank"
                                   class="inline-flex items-center bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-all duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Informasi Waktu & Status -->
                        <div class="space-y-6">
                            <div class="bg-green-50 dark:bg-green-900/30 rounded-xl p-6 border border-green-200 dark:border-green-700">
                                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-4 flex items-center">
                                    <span class="w-2 h-6 bg-green-500 rounded-full mr-3"></span>
                                    Periode Peminjaman
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Mulai</label>
                                        <p class="text-lg font-semibold text-green-800 dark:text-green-200">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Selesai</label>
                                        <p class="text-lg font-semibold text-green-800 dark:text-green-200">{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-green-700 dark:text-green-300 mb-1">Durasi</label>
                                        <p class="text-lg font-semibold text-green-800 dark:text-green-200">
                                            {{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) }} hari
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                                <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-200 mb-4 flex items-center">
                                    <span class="w-2 h-6 bg-purple-500 rounded-full mr-3"></span>
                                    Informasi Sistem
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Diajukan Oleh</label>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                {{ strtoupper(substr($peminjaman->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-purple-800 dark:text-purple-200">{{ $peminjaman->user->name }}</p>
                                                <p class="text-sm text-purple-600 dark:text-purple-300">{{ $peminjaman->user->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Tanggal Pengajuan</label>
                                        <p class="text-purple-800 dark:text-purple-200 font-semibold">{{ $peminjaman->created_at->format('d F Y H:i') }}</p>
                                    </div>

                                    @if($peminjaman->validated_by && $peminjaman->validatedBy)
                                    <div>
                                        <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Divalidasi Oleh</label>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                {{ strtoupper(substr($peminjaman->validatedBy->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-purple-800 dark:text-purple-200">{{ $peminjaman->validatedBy->name }}</p>
                                                <p class="text-sm text-purple-600 dark:text-purple-300">{{ $peminjaman->validated_at->format('d F Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($peminjaman->approved_by && $peminjaman->approvedBy)
                                    <div>
                                        <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Disetujui Oleh</label>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                {{ strtoupper(substr($peminjaman->approvedBy->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-purple-800 dark:text-purple-200">{{ $peminjaman->approvedBy->name }}</p>
                                                <p class="text-sm text-purple-600 dark:text-purple-300">{{ $peminjaman->approved_at->format('d F Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($peminjaman->completed_by && $peminjaman->completedBy)
                                    <div>
                                        <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Diproses Oleh</label>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                {{ strtoupper(substr($peminjaman->completedBy->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-purple-800 dark:text-purple-200">{{ $peminjaman->completedBy->name }}</p>
                                                <p class="text-sm text-purple-600 dark:text-purple-300">{{ $peminjaman->completed_at->format('d F Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($peminjaman->returned_at)
                                    <div>
                                        <label class="block text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Dikembalikan Pada</label>
                                        <p class="text-purple-800 dark:text-purple-200 font-semibold">{{ $peminjaman->returned_at->format('d F Y H:i') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Peminjaman</h3>
                        <div class="flex flex-wrap gap-4">
                            <!-- Admin: Approve / Reject (status pending) -->
                            @if(auth()->user()->isAdmin() && $peminjaman->status === 'pending')
                                <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center" onclick="return confirm('Setujui peminjaman ini?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui Pengajuan
                                    </button>
                                </form>

                                <button onclick="showRejectForm()"
                                        class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak
                                </button>
                            @endif

                            <!-- Admin: Process / Serahkan Barang (status approved) -->
                            @if(auth()->user()->isAdmin() && $peminjaman->status === 'approved')
                                <form action="{{ route('peminjaman.process', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                            onclick="return confirm('Proses barang keluar? Stok barang akan dikurangi.')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Serahkan Barang (Barang Keluar)
                                    </button>
                                </form>
                            @endif

                            <!-- User: Konfirmasi Penerimaan (status processed) -->
                            @if($peminjaman->user_id === auth()->id() && $peminjaman->status === 'processed')
                                <form action="{{ route('peminjaman.complete', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                            onclick="return confirm('Konfirmasi telah menerima barang?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Konfirmasi Barang Diterima
                                    </button>
                                </form>
                            @endif

                            <!-- User & Admin: Kembalikan Barang (status processed atau completed) -->
                            @if(($peminjaman->user_id === auth()->id() || auth()->user()->isAdmin()) && in_array($peminjaman->status, ['processed', 'completed']))
                                <button type="button" onclick="showReturnModal()"
                                        class="bg-orange-600 text-white px-6 py-3 rounded-xl hover:bg-orange-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Kembalikan Barang
                                </button>
                            @endif

                            <!-- Admin / User delete/cancel (status pending) -->
                            @if(($peminjaman->user_id === auth()->id() && $peminjaman->status === 'pending') || auth()->user()->isAdmin())
                                <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                            onclick="return confirm('Yakin ingin membatalkan/menghapus pengajuan ini?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus Pengajuan
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Status Pengembalian Box jika sudah returned -->
                        @if($peminjaman->status === 'returned')
                        <div class="mt-6 p-5 bg-green-50 dark:bg-green-900/30 rounded-2xl border border-green-200 dark:border-green-700">
                            <h4 class="text-base font-bold text-green-900 dark:text-green-200 flex items-center mb-3">
                                <span class="mr-2">🎉</span> Barang Telah Dikembalikan
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-green-100 dark:border-gray-700">
                                    <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">Total Dikembalikan</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $peminjaman->jumlah_pinjam }} unit</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-green-100 dark:border-gray-700">
                                    <p class="text-green-600 dark:text-green-400 text-xs font-medium">✓ Kondisi Baik / Layak</p>
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $peminjaman->jumlah_baik_kembali }} unit</p>
                                    <span class="text-xs text-gray-400">Kembali ke stok pinjam</span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-green-100 dark:border-gray-700">
                                    <p class="text-red-600 dark:text-red-400 text-xs font-medium">⚠️ Kondisi Rusak</p>
                                    <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $peminjaman->jumlah_rusak_kembali ?? 0 }} unit</p>
                                    <span class="text-xs text-gray-400">Tercatat di inventaris rusak</span>
                                </div>
                            </div>
                            @if($peminjaman->catatan_kembali)
                                <div class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                                    <strong>Catatan Pengembalian:</strong> {{ $peminjaman->catatan_kembali }}
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Modal -->
    @if(($peminjaman->user_id === auth()->id() || auth()->user()->isAdmin()) && in_array($peminjaman->status, ['processed', 'completed']))
    <div id="returnModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm hidden transition-opacity duration-200" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="min-h-screen px-3 sm:px-4 py-6 flex items-center justify-center">
            <!-- Modal Card -->
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-3rem)] transform transition-all my-auto">
                
                <!-- Modal Header (Fixed at top) -->
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/50 rounded-xl flex items-center justify-center text-orange-600 dark:text-orange-400 text-lg shadow-sm shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white leading-tight">Pengembalian Barang</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pemeriksaan kondisi fisik barang saat dikembalikan</p>
                        </div>
                    </div>
                    <button type="button" onclick="hideReturnModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form (Body scrolls if content overflows) -->
                <form action="{{ route('peminjaman.return', $peminjaman->id) }}" method="POST" class="flex flex-col overflow-hidden m-0 flex-1">
                    @csrf
                    
                    <div class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1">
                        <!-- Info Barang -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-blue-100 dark:border-blue-800">
                            <p class="text-[11px] font-semibold text-blue-800 dark:text-blue-200 uppercase tracking-wider mb-0.5">Barang Yang Dipinjam</p>
                            <p class="text-sm sm:text-base font-bold text-gray-900 dark:text-white break-words">{{ $peminjaman->barang->nama_barang }} ({{ $peminjaman->barang->kode_barang }})</p>
                            <p class="text-xs sm:text-sm text-blue-600 dark:text-blue-300 font-medium mt-1">
                                Total dipinjam: <span id="modalTotalPinjam" class="font-bold text-blue-700 dark:text-blue-200">{{ $peminjaman->jumlah_pinjam }}</span> unit
                            </p>
                        </div>

                        <!-- Input Jumlah Rusak -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="jumlah_rusak_kembali" class="block text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Jumlah Unit Rusak
                                </label>
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">Opsional</span>
                            </div>
                            <input type="number" name="jumlah_rusak_kembali" id="jumlah_rusak_kembali"
                                   value="0" min="0" max="{{ $peminjaman->jumlah_pinjam }}"
                                   placeholder="0"
                                   class="w-full px-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base sm:text-lg font-bold"
                                   oninput="calculateReturnCondition({{ $peminjaman->jumlah_pinjam }})">
                            <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Biarkan <strong>0</strong> atau kosong jika semua barang kembali dalam kondisi baik.</p>
                        </div>

                        <!-- Preview Kalkulasi Kondisi -->
                        <div class="grid grid-cols-2 gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl sm:rounded-2xl border border-gray-200 dark:border-gray-600">
                            <div class="text-center p-1 sm:p-2">
                                <span class="text-[10px] sm:text-xs text-green-600 dark:text-green-400 font-semibold block leading-tight">Kondisi Baik (Masuk Stok)</span>
                                <div class="mt-1">
                                    <span id="previewBaik" class="text-xl sm:text-2xl font-black text-green-600 dark:text-green-400">{{ $peminjaman->jumlah_pinjam }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">unit</span>
                                </div>
                            </div>
                            <div class="text-center p-1 sm:p-2 border-l border-gray-200 dark:border-gray-600">
                                <span class="text-[10px] sm:text-xs text-red-500 dark:text-red-400 font-semibold block leading-tight">Kondisi Rusak (Terkunci)</span>
                                <div class="mt-1">
                                    <span id="previewRusak" class="text-xl sm:text-2xl font-black text-red-500 dark:text-red-400">0</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">unit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Pengembalian -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="catatan_kembali" class="block text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Catatan Pengembalian
                                </label>
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">Opsional</span>
                            </div>
                            <textarea name="catatan_kembali" id="catatan_kembali" rows="2"
                                      class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs sm:text-sm"
                                      placeholder="Contoh: 1 unit laptop rusak di bagian port charger..."></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer (Fixed at bottom) -->
                    <div class="px-5 sm:px-6 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 shrink-0">
                        <button type="button" onclick="hideReturnModal()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 font-semibold text-sm transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm shadow-md hover:shadow-orange-600/30 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Konfirmasi Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    @if(auth()->user()->isAdmin() && $peminjaman->status === 'pending')
    <div id="rejectModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm hidden transition-opacity duration-200">
        <div class="min-h-screen px-3 sm:px-4 py-6 flex items-center justify-center">
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-3rem)] transform transition-all my-auto">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 shrink-0">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tolak Pengajuan Peminjaman</h3>
                    <button type="button" onclick="hideRejectForm()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST" class="flex flex-col overflow-hidden m-0 flex-1">
                    @csrf
                    <div class="p-5 sm:p-6 overflow-y-auto flex-1">
                        <label for="catatan_admin" class="block text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                            placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                    <div class="px-5 sm:px-6 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 shrink-0">
                        <button type="button" onclick="hideRejectForm()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 font-semibold text-sm transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-md hover:shadow-red-600/30 transition-all text-center">
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script>
        function showRejectForm() {
            const modal = document.getElementById('rejectModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideRejectForm() {
            const modal = document.getElementById('rejectModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function showReturnModal() {
            const modal = document.getElementById('returnModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideReturnModal() {
            const modal = document.getElementById('returnModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function calculateReturnCondition(total) {
            const rusakInput = document.getElementById('jumlah_rusak_kembali');
            let rusak = parseInt(rusakInput.value) || 0;
            if (rusak < 0) rusak = 0;
            if (rusak > total) {
                rusak = total;
                rusakInput.value = total;
            }
            const baik = total - rusak;
            document.getElementById('previewBaik').textContent = baik;
            document.getElementById('previewRusak').textContent = rusak;
        }

        // Close modal on click outside modal card
        window.addEventListener('click', function(e) {
            const returnModal = document.getElementById('returnModal');
            const rejectModal = document.getElementById('rejectModal');
            if (returnModal && e.target === returnModal.firstElementChild) {
                hideReturnModal();
            }
            if (rejectModal && e.target === rejectModal.firstElementChild) {
                hideRejectForm();
            }
        });

        // Close on ESC
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideReturnModal();
                hideRejectForm();
            }
        });
    </script>
</x-app-layout>
