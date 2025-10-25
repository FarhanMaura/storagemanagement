<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Banner -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    📊 Dashboard Statistik
                                </h1>
                                <p class="text-indigo-100 text-lg">
                                    Analisis lengkap data barang masuk dan keluar
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0 flex space-x-3">
                                <a href="{{ route('statistik.export.pdf') }}" target="_blank"
                                   class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 hover:scale-105 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </a>
                                <a href="{{ route('statistik.export.csv') }}"
                                   class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-indigo-50 transition-all duration-300 border border-white hover:scale-105 flex items-center font-semibold">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Barang Masuk -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Barang Masuk</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_masuk']) }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <span class="text-2xl text-green-600 dark:text-green-400">📥</span>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+{{ number_format($statistik['bulan_ini_masuk']) }} bulan ini</span>
                    </div>
                </div>

                <!-- Total Barang Keluar -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Barang Keluar</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_keluar']) }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl group-hover:bg-red-200 dark:group-hover:bg-red-800 transition-colors">
                            <span class="text-2xl text-red-600 dark:text-red-400">📤</span>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+{{ number_format($statistik['bulan_ini_keluar']) }} bulan ini</span>
                    </div>
                </div>

                <!-- Total Laporan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Laporan</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_laporan']) }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                            <span class="text-2xl text-blue-600 dark:text-blue-400">📋</span>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+{{ number_format($statistik['bulan_ini_laporan']) }} bulan ini</span>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Users</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_users']) }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-xl group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <span class="text-2xl text-purple-600 dark:text-purple-400">👥</span>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Aktif dalam sistem</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Line Chart - 6 Bulan Terakhir -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                        Trend 6 Bulan Terakhir
                    </h3>
                    <div class="h-80">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart - Jenis Laporan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <span class="w-2 h-6 bg-green-500 rounded-full mr-3"></span>
                        Distribusi Jenis Laporan
                    </h3>
                    <div class="h-80">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Daily Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8 transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-2 h-6 bg-purple-500 rounded-full mr-3"></span>
                    Aktivitas 7 Hari Terakhir
                </h3>
                <div class="h-64">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <!-- Top Lists Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Top Barang Masuk -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <span class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-green-600 dark:text-green-400">📥</span>
                        </span>
                        Top 5 Barang Masuk
                    </h3>
                    <div class="space-y-4">
                        @forelse($statistik['top_barang_masuk'] as $index => $barang)
                        <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/30 rounded-xl border border-green-200 dark:border-green-700 transition-all duration-300 hover:scale-105 hover:shadow-md group">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white block">{{ $barang->nama_barang }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $barang->kode_barang }}</span>
                                </div>
                            </div>
                            <span class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                                {{ number_format($barang->total) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📊</div>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data barang masuk</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Barang Keluar -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <span class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-red-600 dark:text-red-400">📤</span>
                        </span>
                        Top 5 Barang Keluar
                    </h3>
                    <div class="space-y-4">
                        @forelse($statistik['top_barang_keluar'] as $index => $barang)
                        <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/30 rounded-xl border border-red-200 dark:border-red-700 transition-all duration-300 hover:scale-105 hover:shadow-md group">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 group-hover:scale-110 transition-transform">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white block">{{ $barang->nama_barang }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $barang->kode_barang }}</span>
                                </div>
                            </div>
                            <span class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                                {{ number_format($barang->total) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-2">📊</div>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data barang keluar</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Users -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8 transition-all duration-300 hover:shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-purple-600 dark:text-purple-400">👥</span>
                    </span>
                    Top 5 User Aktif
                </h3>
                <div class="space-y-4">
                    @forelse($statistik['top_users'] as $index => $user)
                    <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl border border-purple-200 dark:border-purple-700 transition-all duration-300 hover:scale-105 hover:shadow-md group">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 group-hover:scale-110 transition-transform">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white block">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="bg-purple-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                            {{ $user->laporan_count }} laporan
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-2">👥</div>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data user</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-yellow-50 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 rounded-2xl border border-yellow-200 dark:border-yellow-700 p-6 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-xl mr-4">
                            <span class="text-yellow-600 dark:text-yellow-400 text-xl">⚡</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Rata-rata Harian</p>
                            <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                                {{ number_format($statistik['total_laporan'] / max($statistik['hari_operasional'], 1), 1) }}
                            </p>
                            <p class="text-xs text-yellow-700 dark:text-yellow-300">transaksi/hari</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-700 p-6 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-xl mr-4">
                            <span class="text-indigo-600 dark:text-indigo-400 text-xl">📦</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Stok Akhir</p>
                            <p class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">
                                {{ number_format($statistik['total_masuk'] - $statistik['total_keluar']) }}
                            </p>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300">item tersedia</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-teal-100 dark:from-cyan-900/30 dark:to-teal-900/30 rounded-2xl border border-cyan-200 dark:border-cyan-700 p-6 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-cyan-100 dark:bg-cyan-900 p-4 rounded-xl mr-4">
                            <span class="text-cyan-600 dark:text-cyan-400 text-xl">📅</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-cyan-800 dark:text-cyan-200">Hari Operasional</p>
                            <p class="text-2xl font-bold text-cyan-900 dark:text-cyan-100">{{ $statistik['hari_operasional'] }}</p>
                            <p class="text-xs text-cyan-700 dark:text-cyan-300">hari aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Export Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl border border-blue-200 dark:border-blue-700 p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-xl">
                            <span class="text-blue-600 dark:text-blue-400 text-xl">💡</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">Tips Export Data</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700 dark:text-blue-300">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                <strong>Download PDF:</strong> Laporan lengkap untuk print
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                <strong>Download CSV:</strong> Data untuk analisis lanjutan
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                Data Real-time dan auto update
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                Chart diperbarui setiap 2 menit
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data dari controller
        const chartData = @json($chartData);

        // Line Chart - 6 Bulan Terakhir
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: chartData.months,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: chartData.data_masuk,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Barang Keluar',
                        data: chartData.data_keluar,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Pie Chart - Jenis Laporan
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Barang Masuk', 'Barang Keluar'],
                datasets: [{
                    data: [chartData.total_masuk_count, chartData.total_keluar_count],
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '60%'
            }
        });

        // Bar Chart - 7 Hari Terakhir
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: chartData.days,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: chartData.daily_masuk,
                        backgroundColor: '#10B981',
                        borderRadius: 8,
                    },
                    {
                        label: 'Barang Keluar',
                        data: chartData.daily_keluar,
                        backgroundColor: '#EF4444',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Auto refresh data setiap 2 menit
        setInterval(() => {
            fetch('/statistik/api')
                .then(response => response.json())
                .then(data => {
                    console.log('Data statistik diperbarui:', data.timestamp);
                    // Di sini bisa update chart dengan data baru
                })
                .catch(error => console.error('Error fetching data:', error));
        }, 120000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + P untuk PDF
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.open('{{ route("statistik.export.pdf") }}', '_blank');
            }

            // Ctrl + D untuk download CSV
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                window.location.href = '{{ route("statistik.export.csv") }}';
            }
        });

        // Show keyboard shortcuts info
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Keyboard Shortcuts Statistik:');
            console.log('• Ctrl + P : Download PDF');
            console.log('• Ctrl + D : Download CSV');
            console.log('• Auto refresh setiap 2 menit');
        });
    </script>
</x-app-layout>
