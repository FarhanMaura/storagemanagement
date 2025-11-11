<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Validasi -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="mr-2">🔍</span>
                    Detail Validasi oleh Petugas Pengajuan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Info Pengajuan -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 border-b pb-2">Informasi Pengajuan</h4>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kode Peminjaman</p>
                            <p class="font-mono font-semibold text-gray-900 dark:text-white">{{ $peminjaman->kode_peminjaman }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pemohon</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $peminjaman->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Barang</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $peminjaman->barang->nama_barang }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $peminjaman->barang->kode_barang }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Jumlah Diajukan</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $peminjaman->jumlah_pinjam }} {{ $peminjaman->barang->satuan }}</p>
                        </div>
                    </div>

                    <!-- Info Validasi -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 border-b pb-2">Informasi Validasi</h4>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Divalidasi Oleh</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $peminjaman->validatedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Waktu Validasi</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $peminjaman->validated_at ? $peminjaman->validated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status Validasi</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                ✅ Tervalidasi - Barang Tersedia
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Validasi -->
                @if($peminjaman->catatan_admin)
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">Catatan dari Petugas Pengajuan:</p>
                    <p class="text-blue-700 dark:text-blue-300">{{ $peminjaman->catatan_admin }}</p>
                </div>
                @endif
            </div>

            <!-- Action Buttons untuk Admin 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tindakan Persetujuan</h3>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('peminjaman.index') }}"
                       class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 font-semibold text-center">
                        Kembali ke Daftar
                    </a>

                    <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 font-semibold w-full sm:w-auto text-center"
                                onclick="return confirm('Setujui pengajuan ini?')">
                            ✅ Setujui Pengajuan
                        </button>
                    </form>

                    <button onclick="showRejectForm({{ $peminjaman->id }})"
                            class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 font-semibold w-full sm:w-auto text-center">
                        ❌ Tolak Pengajuan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tolak Pengajuan Peminjaman</h3>
            <form id="rejectForm" method="POST">
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

    <script>
        function showRejectForm(peminjamanId) {
            const form = document.getElementById('rejectForm');
            form.action = `/peminjaman/${peminjamanId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectForm() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectForm();
            }
        });
    </script>
</x-app-layout>
