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
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    🏷️ Generator Barcode
                                </h1>
                                <p class="text-purple-100 text-lg">
                                    Buat QR Code dan Barcode untuk manajemen inventory
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0 text-purple-200 text-4xl">
                                📊
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('barcode.generate') }}" method="POST" class="mb-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Pilih Kode Barang -->
                            <div>
                                <label for="kode_barang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Pilih Kode Barang *
                                </label>
                                <select name="kode_barang" id="kode_barang" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">Pilih Kode Barang</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->kode_barang }}">
                                            {{ $item->kode_barang }} - {{ $item->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jenis Barcode -->
                            <div>
                                <label for="jenis_barcode" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Jenis Barcode *
                                </label>
                                <select name="jenis_barcode" id="jenis_barcode" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="keduanya">QR Code + Barcode 1D</option>
                                    <option value="qr">QR Code Saja</option>
                                    <option value="barcode">Barcode 1D Saja</option>
                                </select>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Jumlah Barcode *
                                </label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Masukkan jumlah barang yang ingin dibuatkan barcodenya</p>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-4 px-6 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-lg font-semibold transition-all duration-300 hover:scale-105 flex items-center justify-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Generate & Print Barcode
                        </button>
                    </form>

                    <!-- Informasi -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl border border-blue-200 dark:border-blue-700 p-6">
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-4 flex items-center">
                            <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                            Cara Menggunakan Barcode
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg mr-3">
                                        <span class="text-green-600 dark:text-green-400 text-lg">📱</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-green-800 dark:text-green-200 mb-1">QR Code (Kotak)</h4>
                                        <ul class="text-green-700 dark:text-green-300 space-y-1">
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                                Cocok untuk smartphone
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                                Scan dengan kamera
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                                Menampilkan data lengkap
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-lg mr-3">
                                        <span class="text-purple-600 dark:text-purple-400 text-lg">📠</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-purple-800 dark:text-purple-200 mb-1">Barcode 1D (Garis)</h4>
                                        <ul class="text-purple-700 dark:text-purple-300 space-y-1">
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>
                                                Cocok untuk barcode scanner
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>
                                                Digunakan di gudang
                                            </li>
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>
                                                Label kemasan produk
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-blue-200 dark:bg-blue-800 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Scan barcode akan menampilkan data stok dan riwayat barang
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
