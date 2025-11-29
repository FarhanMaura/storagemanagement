<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ $title }}
            </h2>
            <div class="flex space-x-3">
                <!-- Ganti tombol print dengan link ke halaman print khusus -->
                <a href="{{ route('barcode.print', ['kode_barang' => $barang->kode_barang, 'jenis' => $jenis_barcode, 'quantity' => count($barcodes)]) }}"
                   target="_blank"
                   class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 no-print transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    🖨️ Print
                </a>
                <a href="{{ route('barcode.index') }}"
                   class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 no-print transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Barang -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8 no-print">
                <h2 class="text-xl font-bold mb-6 flex items-center">
                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                    Informasi Barang
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-xl border border-blue-200 dark:border-blue-700">
                        <label class="block text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">Kode Barang</label>
                        <p class="font-mono text-lg font-bold text-blue-800 dark:text-blue-200">{{ $data['kode_barang'] }}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-xl border border-green-200 dark:border-green-700">
                        <label class="block text-sm font-semibold text-green-700 dark:text-green-300 mb-2">Nama Barang</label>
                        <p class="text-lg font-semibold text-green-800 dark:text-green-200">{{ $data['nama_barang'] }}</p>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/30 p-4 rounded-xl border border-orange-200 dark:border-orange-700">
                        <label class="block text-sm font-semibold text-orange-700 dark:text-orange-300 mb-2">Stok Saat Ini</label>
                        <p class="text-2xl font-bold {{ $data['stok'] > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $data['stok'] }}
                        </p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-xl border border-purple-200 dark:border-purple-700">
                        <label class="block text-sm font-semibold text-purple-700 dark:text-purple-300 mb-2">Total Masuk</label>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $data['total_masuk'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Barcode Results -->
            <div class="space-y-8">
                @if(in_array($jenis_barcode, ['keduanya', 'qr']))
                <!-- QR Code Results -->
                <div class="space-y-8">
                    @foreach($barcodes as $barcode)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 print-break">
                        <h3 class="text-xl font-semibold mb-6 text-center no-print flex items-center justify-center">
                            <span class="w-2 h-6 bg-green-500 rounded-full mr-3"></span>
                            QR Code Label ({{ $barcode['code'] }})
                        </h3>
                        <div class="barcode-label text-center bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 p-8 rounded-2xl border-2 border-green-200 dark:border-green-700">
                            <!-- Company Info -->
                            <div class="mb-6">
                                <h4 class="font-bold text-2xl text-gray-900 dark:text-white" style="font-size: 22pt;">PT. JOKOWIDODO 3PERIODE</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" style="font-size: 11pt;">Storage Management System</p>
                            </div>

                            <!-- QR Code -->
                            <div class="mb-6 flex justify-center">
                                <div class="bg-white p-4 rounded-xl shadow-lg">
                                    <img src="{{ $barcode['barcodeQR'] }}" alt="QR Code {{ $barcode['code'] }}"
                                         class="barcode-image mx-auto" style="width: 220px; height: 220px;">
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="bg-white dark:bg-gray-700 rounded-xl p-4 shadow-sm">
                                <p class="font-semibold text-gray-900 dark:text-white mb-2" style="font-size: 16pt;">{{ $barcode['code'] }}</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3" style="font-size: 11pt;">{{ Str::limit($barang->nama_barang, 35) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" style="font-size: 9pt;">
                                    🔍 Scan untuk informasi lengkap<br>
                                    <strong>{{ url('/scan/' . $barang->kode_barang) }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if(in_array($jenis_barcode, ['keduanya', 'barcode']))
                <!-- Barcode 1D Results -->
                <div class="space-y-8 mt-8">
                    @foreach($barcodes as $barcode)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 print-break">
                        <h3 class="text-xl font-semibold mb-6 text-center no-print flex items-center justify-center">
                            <span class="w-2 h-6 bg-purple-500 rounded-full mr-3"></span>
                            Barcode 1D Label ({{ $barcode['code'] }})
                        </h3>
                        <div class="barcode-label text-center bg-gradient-to-br from-purple-50 to-indigo-100 dark:from-purple-900/20 dark:to-indigo-900/20 p-8 rounded-2xl border-2 border-purple-200 dark:border-purple-700">
                            <!-- Company Info -->
                            <div class="mb-4">
                                <h4 class="font-bold text-xl text-gray-900 dark:text-white" style="font-size: 18pt;">PT. JOKOWIDODO 3PERIODE</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" style="font-size: 9pt;">Inventory Management</p>
                            </div>

                            <!-- Barcode 1D -->
                            <div class="mb-4 flex justify-center">
                                <div class="bg-white p-4 rounded-xl shadow-lg">
                                    <img src="{{ $barcode['barcode1D'] }}" alt="Barcode {{ $barcode['code'] }}"
                                         class="barcode-image mx-auto" style="height: 90px; width: auto;">
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="bg-white dark:bg-gray-700 rounded-xl p-4 shadow-sm">
                                <p class="font-semibold text-gray-900 dark:text-white mb-1" style="font-size: 14pt;">{{ $barcode['code'] }}</p>
                                <p class="text-xs text-gray-700 dark:text-gray-300 mb-2" style="font-size: 10pt;">{{ Str::limit($barang->nama_barang, 28) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" style="font-size: 8pt;">
                                    📠 Scan barcode untuk informasi stok<br>
                                    Gunakan barcode scanner
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Print Instructions -->
            <div class="bg-gradient-to-r from-yellow-50 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 rounded-2xl border border-yellow-200 dark:border-yellow-700 p-6 mt-8 no-print">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-xl">
                            <span class="text-yellow-600 dark:text-yellow-400 text-xl">💡</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3">
                            Petunjuk Print
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-yellow-700 dark:text-yellow-300">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Klik tombol <strong>🖨️ Print</strong> untuk mencetak label
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Gunakan kertas label/sticker untuk hasil terbaik
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Pastikan printer dalam kondisi siap
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></span>
                                Scan barcode untuk melihat informasi barang
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .print-break {
                page-break-after: always;
                margin: 20px 0;
            }
            .barcode-label {
                border: 2px solid #e5e7eb !important;
                background: white !important;
            }
            .barcode-image {
                filter: contrast(1.3) brightness(0.95);
            }
        }
        .barcode-label {
            transition: all 0.3s ease;
        }
        .barcode-label:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        // Preload images untuk print
        window.onload = function() {
            const images = document.querySelectorAll('.barcode-image');
            images.forEach(img => {
                const newImg = new Image();
                newImg.src = img.src;
            });
        };

        // Add hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const labels = document.querySelectorAll('.barcode-label');
            labels.forEach(label => {
                label.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 30px rgba(0,0,0,0.15)';
                });
                label.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
                });
            });
        });
    </script>
</x-app-layout>
