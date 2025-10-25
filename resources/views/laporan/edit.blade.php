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
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-white mb-2">✏️ Edit Laporan</h1>
                                <p class="text-orange-100">Update informasi laporan barang</p>
                            </div>
                            <div class="text-orange-200 text-4xl">
                                🔄
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

                    <form action="{{ route('laporan.update', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Jenis Laporan -->
                            <div>
                                <label for="jenis_laporan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jenis Laporan *</label>
                                <select name="jenis_laporan" id="jenis_laporan" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="masuk" {{ $laporan->jenis_laporan == 'masuk' ? 'selected' : '' }}>📥 Barang Masuk</option>
                                    <option value="keluar" {{ $laporan->jenis_laporan == 'keluar' ? 'selected' : '' }}>📤 Barang Keluar</option>
                                </select>
                            </div>

                            <!-- Kode Barang -->
                            <div>
                                <label for="kode_barang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Kode Barang *</label>
                                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $laporan->kode_barang) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Contoh: BKP-001">
                            </div>
                        </div>

                        <!-- Nama Barang -->
                        <div class="mb-6">
                            <label for="nama_barang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Nama Barang *</label>
                            <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $laporan->nama_barang) }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Contoh: Kertas Mentah Grade A">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Jumlah -->
                            <div>
                                <label for="jumlah" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jumlah *</label>
                                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $laporan->jumlah) }}" required min="1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="0">
                            </div>

                            <!-- Satuan -->
                            <div>
                                <label for="satuan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Satuan *</label>
                                <select name="satuan" id="satuan" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="kg" {{ $laporan->satuan == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="ton" {{ $laporan->satuan == 'ton' ? 'selected' : '' }}>Ton</option>
                                    <option value="bal" {{ $laporan->satuan == 'bal' ? 'selected' : '' }}>Bal</option>
                                    <option value="karung" {{ $laporan->satuan == 'karung' ? 'selected' : '' }}>Karung</option>
                                    <option value="unit" {{ $laporan->satuan == 'unit' ? 'selected' : '' }}>Unit</option>
                                    <option value="lembar" {{ $laporan->satuan == 'lembar' ? 'selected' : '' }}>Lembar</option>
                                </select>
                            </div>

                            <!-- Lokasi -->
                            <div>
                                <label for="lokasi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Lokasi *</label>
                                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $laporan->lokasi) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="Contoh: Gudang A, Rak B1">
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-8">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Keterangan tambahan...">{{ old('keterangan', $laporan->keterangan) }}</textarea>
                        </div>

                        <!-- Informasi -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-6 mb-8 border border-blue-200 dark:border-blue-700">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="text-blue-600 dark:text-blue-400 text-xl">ℹ️</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">Informasi Laporan</h4>
                                    <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                        <p>• Laporan ini dibuat oleh: <strong>{{ $laporan->user->name }}</strong></p>
                                        <p>• Tanggal dibuat: {{ $laporan->created_at->format('d F Y H:i') }}</p>
                                        <p>• Terakhir diupdate: {{ $laporan->updated_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('laporan.show', $laporan->id) }}"
                               class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
