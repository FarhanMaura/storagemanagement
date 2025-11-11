<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Pengajuan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Pengajuan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
            </div>

            <!-- Breakdown Stok Barang -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="mr-2">📊</span>
                    Breakdown Stok Barang Tersedia
                </h3>

                @if($stokTersedia->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Lokasi</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Keterangan</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Tanggal Masuk</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($stokTersedia as $stok)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $stok->lokasi }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">{{ $stok->jumlah }} {{ $stok->satuan }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $stok->keterangan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $stok->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">Total Stok</td>
                                    <td class="px-4 py-3 text-sm font-bold text-blue-600 dark:text-blue-400">{{ $totalStok }} {{ $peminjaman->barang->satuan }}</td>
                                    <td colspan="2" class="px-4 py-3 text-sm font-semibold {{ $cukup ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $cukup ? '✅ Stok CUKUP untuk pengajuan' : '❌ Stok TIDAK CUKUP untuk pengajuan' }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-red-400 text-4xl mb-2">❌</div>
                        <p class="text-red-600 dark:text-red-400 font-semibold">Tidak ada stok barang tersedia!</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Barang dengan kode {{ $peminjaman->barang->kode_barang }} tidak ditemukan di inventory.</p>
                    </div>
                @endif
            </div>

            <!-- Form Validasi -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Form Validasi</h3>

                <form action="{{ route('peminjaman.validate.process', $peminjaman->id) }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <!-- Status Validasi -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Status Validasi *
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Barang Tersedia -->
                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="status_validasi" value="tersedia"
                                           class="sr-only peer"
                                           {{ $cukup ? '' : 'disabled' }}
                                           required>
                                    <div class="w-full p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-all duration-200 {{ $cukup ? 'hover:border-green-300 cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center mr-3 transition-all duration-200">
                                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Barang Tersedia</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Ajukan ke manajer untuk persetujuan</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Barang Tidak Tersedia -->
                                <label class="relative flex cursor-pointer">
                                    <input type="radio" name="status_validasi" value="tidak_tersedia"
                                           class="sr-only peer" required>
                                    <div class="w-full p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all duration-200 hover:border-red-300 cursor-pointer">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500 flex items-center justify-center mr-3 transition-all duration-200">
                                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Barang Tidak Tersedia</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Tolak pengajuan ini</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            @if(!$cukup)
                            <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>⚠️ Perhatian:</strong> Stok tidak mencukupi. Hanya dapat memilih "Barang Tidak Tersedia".
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Catatan Validasi -->
                        <div>
                            <label for="catatan_validasi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Validasi
                            </label>
                            <textarea name="catatan_validasi" id="catatan_validasi" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize-none"
                                      placeholder="Berikan catatan tambahan untuk validasi ini..."></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Catatan akan dikirim ke user dan manajer.</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('peminjaman.index') }}"
                               class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 font-semibold">
                                Kembali
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="submitBtn">
                                Proses Validasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle radio button selection
        const radios = document.querySelectorAll('input[name="status_validasi"]');
        const submitBtn = document.getElementById('submitBtn');

        function updateSubmitButton() {
            const selected = document.querySelector('input[name="status_validasi"]:checked');
            submitBtn.disabled = !selected;

            if (selected) {
                if (selected.value === 'tersedia') {
                    submitBtn.textContent = '✅ Ajukan ke Manajer';
                    submitBtn.className = submitBtn.className.replace('bg-blue-600', 'bg-green-600').replace('hover:bg-blue-700', 'hover:bg-green-700');
                } else {
                    submitBtn.textContent = '❌ Tolak Pengajuan';
                    submitBtn.className = submitBtn.className.replace('bg-blue-600', 'bg-red-600').replace('hover:bg-blue-700', 'hover:bg-red-700');
                }
            } else {
                submitBtn.textContent = 'Proses Validasi';
                submitBtn.className = submitBtn.className.replace('bg-green-600 bg-red-600', 'bg-blue-600').replace('hover:bg-green-700 hover:bg-red-700', 'hover:bg-blue-700');
            }
        }

        radios.forEach(radio => {
            radio.addEventListener('change', updateSubmitButton);
        });

        // Initialize button state
        updateSubmitButton();
    </script>
</x-app-layout>
