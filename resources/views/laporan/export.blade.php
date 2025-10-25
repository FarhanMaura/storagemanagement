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
                        <p class="text-indigo-100 text-lg">Download data laporan dalam format CSV atau Excel</p>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- CSV Export -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 text-center transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="bg-green-100 dark:bg-green-900 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                        <span class="text-2xl text-green-600 dark:text-green-400">📊</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Export ke CSV</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Format file kompatibel dengan Excel dan aplikasi spreadsheet lainnya</p>
                    <a href="{{ route('laporan.export.csv') }}"
                       class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold inline-block">
                        📥 Download CSV
                    </a>
                </div>

                <!-- Excel Export -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 text-center transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="bg-blue-100 dark:bg-blue-900 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                        <span class="text-2xl text-blue-600 dark:text-blue-400">💾</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Export ke Excel</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Format file Excel dengan data terstruktur dan summary</p>
                    <a href="{{ route('laporan.export.excel') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold inline-block">
                        📥 Download Excel
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-2 h-6 bg-purple-500 rounded-full mr-3"></span>
                    Filter Data Export
                </h3>
                <form action="{{ route('laporan.export.csv') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="Kode atau nama barang...">
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tanggal Mulai</label>
                        <input type="date" name="start_date"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tanggal Akhir</label>
                        <input type="date" name="end_date"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <!-- Buttons -->
                    <div class="md:col-span-3 flex justify-center space-x-4 pt-4">
                        <button type="submit" name="format" value="csv"
                            class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold">
                            Export CSV dengan Filter
                        </button>
                        <a href="{{ route('laporan.export.excel') }}"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold">
                            Export Excel dengan Filter
                        </a>
                    </div>
                </form>
            </div>

            <!-- Information -->
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-2xl border border-yellow-200 dark:border-yellow-700 p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="text-yellow-600 dark:text-yellow-400 text-2xl">💡</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3">Informasi Export</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-yellow-700 dark:text-yellow-300">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                CSV: Format universal untuk semua aplikasi
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Excel: Format khusus dengan summary data
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Data lengkap: Semua kolom laporan
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Filter: Batasi data yang diexport
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
</x-app-layout>
