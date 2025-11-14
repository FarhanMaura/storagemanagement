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
                                            Stok Tersedia: {{ $peminjaman->barang->jumlah }} {{ $peminjaman->barang->satuan }}
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
                    @if($isAdmin || $isPetugasPengajuan || $isManajerPersetujuan || $isPetugasBarangKeluar)
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Admin</h3>
                        <div class="flex flex-wrap gap-4">
                            @if($isPetugasPengajuan && $peminjaman->status === 'pending')
                                <a href="{{ route('peminjaman.validate.form', $peminjaman->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Validasi Pengajuan
                                </a>
                            @endif

                            @if($isManajerPersetujuan && $peminjaman->status === 'validated')
                                <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                            onclick="return confirm('Setujui peminjaman ini?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui
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

                            @if($isPetugasBarangKeluar && $peminjaman->status === 'approved')
                                <form action="{{ route('peminjaman.process', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                            onclick="return confirm('Proses barang keluar? Stok akan dikurangi.')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Proses Barang Keluar
                                    </button>
                                </form>
                            @endif

                            @if($isAdmin)
                                <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                            onclick="return confirm('Hapus permanen peminjaman ini?')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus Permanen
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @else
                        <!-- Action Buttons untuk User -->
                        @if($peminjaman->status === 'processed' && $peminjaman->user_id === auth()->id())
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Konfirmasi Penerimaan Barang</h3>
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
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                                <strong>Note:</strong> Klik tombol ini setelah Anda menerima barang dari petugas.
                            </p>
                        </div>
                        @endif

                        @if($peminjaman->status === 'completed' && $peminjaman->user_id === auth()->id())
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Pengembalian</h3>
                            <form action="{{ route('peminjaman.return', $peminjaman->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center"
                                        onclick="return confirm('Konfirmasi pengembalian barang? Pastikan barang sudah dalam kondisi baik.')">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Kembalikan Barang
                                </button>
                            </form>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                                <strong>Note:</strong> Pastikan barang dikembalikan dalam kondisi baik dan lengkap.
                            </p>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @if($isManajerPersetujuan && $peminjaman->status === 'validated')
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tolak Pengajuan Peminjaman</h3>
            <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="catatan_admin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Alasan Penolakan</label>
                    <textarea name="catatan_admin" id="catatan_admin" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Berikan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideRejectForm()" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 font-semibold">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        function showRejectForm() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectForm() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectForm();
            }
        });
    </script>
</x-app-layout>
