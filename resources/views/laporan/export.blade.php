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
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 text-center">
                        <h1 class="text-3xl font-bold text-white mb-2">📊 Export Data Laporan</h1>
                        <p class="text-indigo-100 text-lg">Download data laporan dalam format CSV atau Excel dengan filter</p>
                    </div>
                </div>
            </div>

            <!-- Quick Export Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- CSV Export All -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 text-center transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="bg-green-100 dark:bg-green-900 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                        <span class="text-2xl text-green-600 dark:text-green-400">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Export Semua Data ke CSV</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Download semua data laporan tanpa filter</p>
                    <a href="{{ route('laporan.export.csv') }}"
                       class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold inline-block">
                        📥 Download CSV (Semua Data)
                    </a>
                </div>

                <!-- Excel Export All -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 text-center transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="bg-blue-100 dark:bg-blue-900 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                        <span class="text-2xl text-blue-600 dark:text-blue-400">💾</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Export Semua Data ke Excel</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Download semua data laporan tanpa filter</p>
                    <a href="{{ route('laporan.export.excel') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold inline-block">
                        📥 Download Excel (Semua Data)
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-2 h-6 bg-purple-500 rounded-full mr-3"></span>
                    Export dengan Filter Spesifik
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Filter data laporan berdasarkan kriteria tertentu. Data akan difilter berdasarkan <strong>tanggal laporan dibuat</strong>.
                </p>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            🔍 Pencarian
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="Kode barang, nama barang...">
                    </div>

                    <!-- Jenis Laporan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            📦 Jenis Laporan
                        </label>
                        <select name="jenis_laporan"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">Semua Jenis</option>
                            <option value="masuk" {{ request('jenis_laporan') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="keluar" {{ request('jenis_laporan') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            📅 Tanggal Mulai
                        </label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <p class="text-xs text-gray-500 mt-2">Data dari tanggal ini dan seterusnya</p>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            📅 Tanggal Akhir
                        </label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <p class="text-xs text-gray-500 mt-2">Data sampai tanggal ini</p>
                    </div>

                    <!-- Buttons -->
                    <div class="md:col-span-2 lg:col-span-4 flex justify-center space-x-4 pt-4">
                        <button type="submit" formaction="{{ route('laporan.export.csv') }}"
                            class="bg-green-600 text-white px-8 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                            <span class="mr-2">📊</span>
                            Export CSV dengan Filter
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export.excel') }}"
                            class="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                            <span class="mr-2">💾</span>
                            Export Excel dengan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Examples Section -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl border border-blue-200 dark:border-blue-700 p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-400 text-2xl">💡</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">Contoh Penggunaan Filter</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700 dark:text-blue-300">
                            <div class="bg-white dark:bg-blue-800/50 p-4 rounded-lg border border-blue-200 dark:border-blue-600">
                                <strong>📅 Filter Tanggal:</strong>
                                <p class="mt-2">Start: 2024-01-01, End: 2024-01-03</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">Hasil: Data dari 1, 2, dan 3 Januari 2024</p>
                            </div>
                            <div class="bg-white dark:bg-blue-800/50 p-4 rounded-lg border border-blue-200 dark:border-blue-600">
                                <strong>📦 Filter Jenis:</strong>
                                <p class="mt-2">Jenis: Barang Masuk</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">Hasil: Hanya data barang masuk</p>
                            </div>
                            <div class="bg-white dark:bg-blue-800/50 p-4 rounded-lg border border-blue-200 dark:border-blue-600">
                                <strong>🔍 Filter Pencarian:</strong>
                                <p class="mt-2">Search: "kertas"</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">Hasil: Data dengan kode/nama barang mengandung "kertas"</p>
                            </div>
                            <div class="bg-white dark:bg-blue-800/50 p-4 rounded-lg border border-blue-200 dark:border-blue-600">
                                <strong>🎯 Filter Kombinasi:</strong>
                                <p class="mt-2">Jenis: Keluar + Tanggal: 1-3 Jan</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">Hasil: Barang keluar antara 1-3 Januari</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl border border-green-200 dark:border-green-700 p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="text-green-600 dark:text-green-400 text-2xl">✅</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">Informasi Export</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-green-700 dark:text-green-300">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                CSV: Format universal untuk semua aplikasi
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                Excel: Format khusus dengan styling dan summary
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                Data lengkap: 12 kolom informasi laporan
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                Filter: Kombinasi pencarian, jenis, dan tanggal
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-8">
                <a href="{{ route('laporan.index') }}"
                   class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold inline-block">
                    ← Kembali ke Laporan
                </a>
            </div>
        </div>
    </div>

    <script>
        // Set default dates for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
            const oneWeekAgoFormatted = oneWeekAgo.toISOString().split('T')[0];

            // Set default end date to today
            const endDateInput = document.querySelector('input[name="end_date"]');
            if (endDateInput && !endDateInput.value) {
                endDateInput.value = today;
            }

            // Set default start date to one week ago
            const startDateInput = document.querySelector('input[name="start_date"]');
            if (startDateInput && !startDateInput.value) {
                startDateInput.value = oneWeekAgoFormatted;
            }
        });
    </script>
</x-app-layout>
