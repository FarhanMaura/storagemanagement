<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-700 dark:to-indigo-900 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    Selamat Datang, {{ auth()->user()->name }}! 👋
                                </h1>
                                <p class="text-blue-100 text-lg">
                                    Sistem Informasi Inventaris & Peminjaman Barang - Kantor Walikota Palembang
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                                    <p class="text-white text-sm font-semibold">Status Sistem</p>
                                    <p class="text-green-200 text-xs">🟢 Semua Sistem Berjalan Normal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Laporan / Barang -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Data Barang</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Laporan::count() }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                            <span class="text-2xl text-blue-600 dark:text-blue-400">📊</span>
                        </div>
                    </div>
                </div>

                <!-- Barang Masuk -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Barang Masuk</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(\App\Models\Laporan::where('jenis_laporan', 'masuk')->sum('jumlah')) }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <span class="text-2xl text-green-600 dark:text-green-400">📥</span>
                        </div>
                    </div>
                </div>

                <!-- Barang Keluar -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Barang Keluar</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(\App\Models\Laporan::where('jenis_laporan', 'keluar')->sum('jumlah')) }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl group-hover:bg-red-200 dark:group-hover:bg-red-800 transition-colors">
                            <span class="text-2xl text-red-600 dark:text-red-400">📤</span>
                        </div>
                    </div>
                </div>

                <!-- Total User -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total User</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-xl group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <span class="text-2xl text-purple-600 dark:text-purple-400">👥</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activities Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                            Menu Utama
                        </h3>
                        <div class="space-y-4">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('laporan.create') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl hover:bg-blue-100 transition-colors group border border-blue-100 dark:border-blue-800">
                                <div class="bg-blue-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">➕</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Tambah Barang Baru</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Input inventaris barang baru</p>
                                </div>
                            </a>
                            @endif

                            <a href="{{ route('laporan.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/30 rounded-xl hover:bg-green-100 transition-colors group border border-green-100 dark:border-green-800">
                                <div class="bg-green-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">📦</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Daftar Inventaris</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lihat semua stok & data barang</p>
                                </div>
                            </a>

                            @if(auth()->user()->isUser())
                            <a href="{{ route('peminjaman.create') }}" class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl hover:bg-indigo-100 transition-colors group border border-indigo-100 dark:border-indigo-800">
                                <div class="bg-indigo-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">📝</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Ajukan Peminjaman</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Pinjam barang kantor walikota</p>
                                </div>
                            </a>
                            @endif

                            <a href="{{ route('peminjaman.index') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl hover:bg-purple-100 transition-colors group border border-purple-100 dark:border-purple-800">
                                <div class="bg-purple-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">🔄</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Data Peminjaman</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Pantau status & pengembalian</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <span class="w-2 h-6 bg-green-500 rounded-full mr-3"></span>
                                Inventaris Barang Terbaru
                            </h3>
                            <a href="{{ route('laporan.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 text-sm font-medium transition-colors">
                                Lihat semua →
                            </a>
                        </div>
                        <div class="space-y-4">
                            @php
                                $recentLaporans = \App\Models\Laporan::latest()->take(5)->get();
                            @endphp
                            @forelse($recentLaporans as $laporan)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                        📦
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $laporan->nama_barang }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Kode: {{ $laporan->kode_barang }} • Lokasi: {{ $laporan->lokasi }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $laporan->jumlah }}</p>
                                    <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        Stok
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm">Belum ada data barang</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-blue-900/20 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                    Informasi Akun Testing (Kantor Walikota Palembang)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Akun Tersedia</h4>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Admin Utama (Super Admin)</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">admin@palembang.go.id</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Pegawai (User Biasa)</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">user@palembang.go.id</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Hak Akses Sistem</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs px-3 py-2 rounded-lg text-center">Kelola Inventaris (Admin)</span>
                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300 text-xs px-3 py-2 rounded-lg text-center">Persetujuan Pinjam (Admin)</span>
                            <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 text-xs px-3 py-2 rounded-lg text-center">Laporan PDF/Excel (Admin)</span>
                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-3 py-2 rounded-lg text-center">Pengajuan Pinjam (User)</span>
                            <span class="bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300 text-xs px-3 py-2 rounded-lg text-center">Pengembalian Barang (User)</span>
                            <span class="bg-teal-100 dark:bg-teal-900 text-teal-800 dark:text-teal-300 text-xs px-3 py-2 rounded-lg text-center">Cek Stok (User)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh notifications every 30 seconds
        setInterval(() => {
            window.location.reload();
        }, 30000);

        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to cards
            const cards = document.querySelectorAll('.bg-white, .bg-gray-50');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add pulse animation to new notifications
            const newNotifications = document.querySelectorAll('.border-l-4.border-yellow-500');
            newNotifications.forEach(notification => {
                notification.style.animation = 'pulse 2s infinite';
            });

            // Add style for pulse animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
                    70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-app-layout>
