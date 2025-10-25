<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                margin: 1cm;
                size: A4;
            }
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: white !important;
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
            .print-break {
                page-break-before: always;
            }
            .stat-card {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Print Header -->
        <div class="no-print mb-8">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl shadow-xl p-6">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $title }}</h1>
                    <p class="text-indigo-100 text-lg">Laporan Statistik Sistem Management Storage Kertas</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8 border-b-2 border-gray-200 pb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h1>
                <p class="text-gray-600 text-lg">PT. PENGELOLA KERTAS - Storage Management System</p>
                <p class="text-sm text-gray-500 mt-2">Dibuat pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
            </div>

            <!-- Statistik Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg">
                    <div class="bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-green-600 text-2xl">📥</span>
                    </div>
                    <p class="text-sm font-semibold text-green-700 mb-2">Total Barang Masuk</p>
                    <p class="text-3xl font-bold text-green-800">{{ number_format($statistik['total_masuk']) }}</p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-pink-100 border border-red-200 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg">
                    <div class="bg-red-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-red-600 text-2xl">📤</span>
                    </div>
                    <p class="text-sm font-semibold text-red-700 mb-2">Total Barang Keluar</p>
                    <p class="text-3xl font-bold text-red-800">{{ number_format($statistik['total_keluar']) }}</p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-cyan-100 border border-blue-200 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg">
                    <div class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 text-2xl">📊</span>
                    </div>
                    <p class="text-sm font-semibold text-blue-700 mb-2">Total Laporan</p>
                    <p class="text-3xl font-bold text-blue-800">{{ number_format($statistik['total_laporan']) }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-violet-100 border border-purple-200 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg">
                    <div class="bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-purple-600 text-2xl">👥</span>
                    </div>
                    <p class="text-sm font-semibold text-purple-700 mb-2">Total User</p>
                    <p class="text-3xl font-bold text-purple-800">{{ number_format($statistik['total_users']) }}</p>
                </div>
            </div>

            <!-- Bulan Ini -->
            <div class="bg-gradient-to-r from-yellow-50 to-amber-100 border border-yellow-200 rounded-xl p-6 mb-8">
                <h3 class="text-xl font-semibold text-yellow-800 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-yellow-500 rounded-full mr-3"></span>
                    📈 Aktivitas Bulan Ini
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-yellow-700 font-semibold">Barang Masuk</p>
                        <p class="text-2xl font-bold text-yellow-800">{{ number_format($statistik['bulan_ini_masuk']) }}</p>
                        <p class="text-xs text-yellow-600">item</p>
                    </div>
                    <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-yellow-700 font-semibold">Barang Keluar</p>
                        <p class="text-2xl font-bold text-yellow-800">{{ number_format($statistik['bulan_ini_keluar']) }}</p>
                        <p class="text-xs text-yellow-600">item</p>
                    </div>
                    <div class="text-center bg-white rounded-lg p-4 shadow-sm">
                        <p class="text-sm text-yellow-700 font-semibold">Laporan</p>
                        <p class="text-2xl font-bold text-yellow-800">{{ number_format($statistik['bulan_ini_laporan']) }}</p>
                        <p class="text-xs text-yellow-600">transaksi</p>
                    </div>
                </div>
            </div>

            <!-- Top Lists -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 print-break">
                <!-- Top Barang Masuk -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-green-700 flex items-center">
                        <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-green-600">🏆</span>
                        </span>
                        Top 5 Barang Masuk
                    </h3>
                    <div class="space-y-3">
                        @forelse($statistik['top_barang_masuk'] as $index => $barang)
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="font-semibold text-green-800 block">{{ $barang->nama_barang }}</span>
                                    <span class="text-xs text-green-600">Kode: {{ $barang->kode_barang }}</span>
                                </div>
                            </div>
                            <span class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                                {{ number_format($barang->total) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <div class="text-gray-400 text-3xl mb-2">📊</div>
                            <p class="text-gray-500">Belum ada data barang masuk</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Barang Keluar -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-red-700 flex items-center">
                        <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-red-600">🏆</span>
                        </span>
                        Top 5 Barang Keluar
                    </h3>
                    <div class="space-y-3">
                        @forelse($statistik['top_barang_keluar'] as $index => $barang)
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="font-semibold text-red-800 block">{{ $barang->nama_barang }}</span>
                                    <span class="text-xs text-red-600">Kode: {{ $barang->kode_barang }}</span>
                                </div>
                            </div>
                            <span class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                                {{ number_format($barang->total) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <div class="text-gray-400 text-3xl mb-2">📊</div>
                            <p class="text-gray-500">Belum ada data barang keluar</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Users -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-purple-700 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-purple-600">👥</span>
                    </span>
                    Top 5 User Aktif
                </h3>
                <div class="space-y-3">
                    @forelse($statistik['top_users'] as $index => $user)
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-200 transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-purple-800 block">{{ $user->name }}</span>
                                    <span class="text-xs text-purple-600">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="bg-purple-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                            {{ $user->laporan_count }} laporan
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="text-gray-400 text-3xl mb-2">👥</div>
                        <p class="text-gray-500">Belum ada data user</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Chart Data Summary -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-xl p-6 mb-8 print-break">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                    📅 Trend 6 Bulan Terakhir
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-500 to-blue-600">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-white">Bulan</th>
                                @foreach($chartData['months'] as $month)
                                <th class="px-4 py-3 text-center text-sm font-semibold text-white">{{ $month }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td class="px-4 py-3 text-sm font-semibold text-green-700 bg-green-50">Barang Masuk</td>
                                @foreach($chartData['data_masuk'] as $data)
                                <td class="px-4 py-3 text-center text-sm text-green-600 bg-green-50 font-semibold">{{ number_format($data) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-semibold text-red-700 bg-red-50">Barang Keluar</td>
                                @foreach($chartData['data_keluar'] as $data)
                                <td class="px-4 py-3 text-center text-sm text-red-600 bg-red-50 font-semibold">{{ number_format($data) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-yellow-50 to-amber-100 border border-yellow-200 rounded-xl p-6 text-center">
                    <div class="bg-yellow-100 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <span class="text-yellow-600 text-xl">⚡</span>
                    </div>
                    <p class="text-sm font-semibold text-yellow-700">Rata-rata Harian</p>
                    <p class="text-2xl font-bold text-yellow-800">
                        {{ number_format($statistik['total_laporan'] / max($statistik['hari_operasional'], 1), 1) }}
                    </p>
                    <p class="text-xs text-yellow-600">transaksi/hari</p>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-blue-100 border border-indigo-200 rounded-xl p-6 text-center">
                    <div class="bg-indigo-100 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <span class="text-indigo-600 text-xl">📦</span>
                    </div>
                    <p class="text-sm font-semibold text-indigo-700">Stok Akhir</p>
                    <p class="text-2xl font-bold text-indigo-800">
                        {{ number_format($statistik['total_masuk'] - $statistik['total_keluar']) }}
                    </p>
                    <p class="text-xs text-indigo-600">item tersedia</p>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-teal-100 border border-cyan-200 rounded-xl p-6 text-center">
                    <div class="bg-cyan-100 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <span class="text-cyan-600 text-xl">📅</span>
                    </div>
                    <p class="text-sm font-semibold text-cyan-700">Hari Operasional</p>
                    <p class="text-2xl font-bold text-cyan-900">{{ $statistik['hari_operasional'] }}</p>
                    <p class="text-xs text-cyan-600">hari aktif</p>
                </div>
            </div>

            <!-- Export Options -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-100 border border-blue-200 rounded-xl p-6 no-print">
                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                    💾 Download Laporan
                </h3>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.print()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        🖨️ Print Laporan
                    </button>
                    <a href="{{ route('statistik.export.csv') }}"
                       class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        📥 Download Data CSV
                    </a>
                    <a href="{{ route('statistik.index') }}"
                       class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        ← Kembali ke Statistik
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t-2 border-gray-200 text-center text-sm text-gray-500">
                <p class="font-semibold">Laporan ini dibuat secara otomatis oleh Sistem Management Storage Kertas</p>
                <p class="mt-1">© {{ date('Y') }} - PT. Pengelolaan Kertas Mentah | All Rights Reserved</p>
            </div>
        </div>
    </div>

    <script>
        // Auto print jika parameter print=true
        if (window.location.search.includes('print=true')) {
            setTimeout(() => {
                window.print();
            }, 1000);
        }

        // Add some interactivity for non-print view
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.bg-gradient-to-br');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
