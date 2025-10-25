<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Banner -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-white mb-2">➕ Ajukan Peminjaman</h1>
                                <p class="text-green-100">Isi form untuk mengajukan peminjaman barang</p>
                            </div>
                            <div class="text-green-200 text-4xl">
                                📦
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    @if($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 rounded-xl mb-6">
                            <div class="flex">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold">Terjadi kesalahan:</h4>
                                    <ul class="list-disc list-inside mt-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf

                        <!-- Barang -->
                        <div class="mb-6">
                            <label for="barang_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Barang *</label>
                            <select name="barang_id" id="barang_id" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Pilih Barang</option>
                                @foreach($barangTersedia as $barang)
                                    <option value="{{ $barang->id }}"
                                        {{ old('barang_id') == $barang->id ? 'selected' : '' }}
                                        data-stok="{{ $barang->jumlah }}">
                                        {{ $barang->nama_barang }} ({{ $barang->kode_barang }}) - Stok: {{ $barang->jumlah }} {{ $barang->satuan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Jumlah Pinjam -->
                            <div>
                                <label for="jumlah_pinjam" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jumlah Pinjam *</label>
                                <input type="number" name="jumlah_pinjam" id="jumlah_pinjam"
                                    value="{{ old('jumlah_pinjam') }}"
                                    required min="1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="0">
                                <small class="text-gray-500 dark:text-gray-400 mt-2 block" id="stok-info">Pilih barang untuk melihat stok</small>
                            </div>

                            <!-- Tanggal Pinjam -->
                            <div>
                                <label for="tanggal_pinjam" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tanggal Pinjam *</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                    value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Tanggal Kembali -->
                            <div>
                                <label for="tanggal_kembali" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tanggal Kembali *</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                                    value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="mb-8">
                            <label for="keperluan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Keperluan *</label>
                            <textarea name="keperluan" id="keperluan" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Jelaskan keperluan peminjaman...">{{ old('keperluan') }}</textarea>
                        </div>

                        <!-- Informasi -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-6 mb-8 border border-blue-200 dark:border-blue-700">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="text-blue-600 dark:text-blue-400 text-xl">ℹ️</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">Informasi</h4>
                                    <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                        <p>• Pengajuan akan ditinjau oleh admin</p>
                                        <p>• Pastikan barang tersedia sebelum mengajukan</p>
                                        <p>• Isi keperluan dengan jelas dan lengkap</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('peminjaman.index') }}"
                               class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update stok info ketika barang dipilih
        document.getElementById('barang_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok');
            document.getElementById('stok-info').textContent = 'Stok tersedia: ' + stok;

            // Set max value untuk input jumlah
            const jumlahInput = document.getElementById('jumlah_pinjam');
            jumlahInput.max = stok;
        });

        // Validasi jumlah tidak melebihi stok
        document.getElementById('jumlah_pinjam').addEventListener('input', function() {
            const selectedOption = document.getElementById('barang_id').options[document.getElementById('barang_id').selectedIndex];
            const stok = selectedOption ? selectedOption.getAttribute('data-stok') : 0;
            const jumlah = this.value;

            if (parseInt(jumlah) > parseInt(stok)) {
                this.setCustomValidity('Jumlah tidak boleh melebihi stok tersedia');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</x-app-layout>
