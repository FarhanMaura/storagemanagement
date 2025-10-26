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
                                <h1 class="text-2xl font-bold text-white mb-2">➕ Buat Laporan Baru</h1>
                                <p class="text-green-100">Tambah data barang masuk atau keluar ke sistem</p>
                            </div>
                            <div class="text-green-200 text-4xl">
                                📝
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('laporan.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Jenis Laporan -->
                            <div>
                                <label for="jenis_laporan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jenis Laporan *</label>
                                <select name="jenis_laporan" id="jenis_laporan" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">Pilih Jenis</option>
                                    <option value="masuk" {{ old('jenis_laporan') == 'masuk' ? 'selected' : '' }}>📥 Barang Masuk</option>
                                    <option value="keluar" {{ old('jenis_laporan') == 'keluar' ? 'selected' : '' }}>📤 Barang Keluar</option>
                                </select>
                                @error('jenis_laporan')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Barang -->
                            <div>
                                <label for="kode_barang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Kode Barang *</label>
                                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Contoh: BKP-001">
                                @error('kode_barang')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Barang -->
                        <div class="mb-6">
                            <label for="nama_barang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Nama Barang *</label>
                            <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Contoh: Kertas Mentah Grade A">
                            @error('nama_barang')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Jumlah -->
                            <div>
                                <label for="jumlah" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jumlah *</label>
                                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}" required min="1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="0">
                                @error('jumlah')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Satuan -->
                            <div>
                                <label for="satuan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Satuan *</label>
                                <div class="relative">
                                    <input type="text" name="satuan" id="satuan" list="satuanList"
                                        value="{{ old('satuan') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        placeholder="Ketik atau pilih satuan..." required>

                                    <datalist id="satuanList">
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan }}">
                                        @endforeach
                                    </datalist>

                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                    💡 Ketik satuan baru atau pilih dari daftar. Satuan baru akan tersimpan otomatis.
                                </p>

                                @error('satuan')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lokasi -->
                            <div>
                                <label for="lokasi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Lokasi *</label>
                                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Contoh: Gudang A, Rak B1">
                                @error('lokasi')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-8">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('laporan.index') }}"
                               class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/30 rounded-2xl border border-blue-200 dark:border-blue-700 p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-400 text-xl">💡</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">Tips Pengisian</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Pastikan kode barang unik dan mudah diidentifikasi</li>
                            <li>• Pilih jenis laporan sesuai dengan transaksi (masuk/keluar)</li>
                            <li>• Gunakan satuan yang konsisten untuk memudahkan tracking</li>
                            <li>• Isi keterangan jika ada informasi tambahan yang penting</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
