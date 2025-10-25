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
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    Selamat Datang, {{ auth()->user()->name }}! 👋
                                </h1>
                                <p class="text-blue-100 text-lg">
                                    Sistem Management Storage - Pengelolaan Kertas Perusahaan
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

            <!-- 2FA Status Alert -->
            @auth
                @if(!auth()->user()->is2FAEnabled())
                    <div class="mb-8 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>Keamanan Akun:</strong> 2FA belum diaktifkan.
                                    <a href="{{ route('2fa.setup') }}" class="underline font-semibold">Aktifkan sekarang</a> untuk perlindungan ekstra.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800 dark:text-green-200">
                                    <strong>✅ 2FA Aktif</strong> - Akun Anda terlindungi dengan Two-Factor Authentication
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Statistik Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Laporan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Laporan</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Laporan::count() }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                            <span class="text-2xl text-blue-600 dark:text-blue-400">📊</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+12% dari bulan lalu</span>
                    </div>
                </div>

                <!-- Barang Masuk -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Barang Masuk</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(\App\Models\Laporan::where('jenis_laporan', 'masuk')->sum('jumlah')) }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <span class="text-2xl text-green-600 dark:text-green-400">📥</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>+8% dari bulan lalu</span>
                    </div>
                </div>

                <!-- Barang Keluar -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Barang Keluar</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(\App\Models\Laporan::where('jenis_laporan', 'keluar')->sum('jumlah')) }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl group-hover:bg-red-200 dark:group-hover:bg-red-800 transition-colors">
                            <span class="text-2xl text-red-600 dark:text-red-400">📤</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>-3% dari bulan lalu</span>
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
                    <div class="mt-4 flex items-center text-sm text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Aktif semua</span>
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
                            Quick Actions
                        </h3>
                        <div class="space-y-4">
                            <a href="{{ route('laporan.create') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors group border border-blue-100 dark:border-blue-800">
                                <div class="bg-blue-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">➕</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Buat Laporan Baru</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tambah data barang masuk/keluar</p>
                                </div>
                            </a>

                            <a href="{{ route('laporan.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/30 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors group border border-green-100 dark:border-green-800">
                                <div class="bg-green-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">📋</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Kelola Laporan</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lihat dan edit semua laporan</p>
                                </div>
                            </a>

                            <a href="{{ route('statistik.index') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors group border border-purple-100 dark:border-purple-800">
                                <div class="bg-purple-500 p-3 rounded-lg group-hover:scale-110 transition-transform">
                                    <span class="text-white text-lg">📈</span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">Analisis Statistik</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lihat grafik dan analisis data</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Recent Laporan -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="w-2 h-6 bg-green-500 rounded-full mr-3"></span>
                                    Laporan Terbaru
                                </h3>
                                <a href="{{ route('laporan.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center">
                                    Lihat semua
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="space-y-4">
                                @php
                                    $recentLaporans = \App\Models\Laporan::with('user')->latest()->take(4)->get();
                                @endphp
                                @forelse($recentLaporans as $laporan)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $laporan->jenis_laporan === 'masuk' ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400' }}">
                                            @if($laporan->jenis_laporan === 'masuk')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $laporan->nama_barang }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $laporan->kode_barang }} • {{ $laporan->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $laporan->jumlah }} {{ $laporan->satuan }}</p>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full {{ $laporan->jenis_laporan === 'masuk' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                            {{ $laporan->jenis_laporan === 'masuk' ? 'Masuk' : 'Keluar' }}
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <div class="text-gray-400 text-4xl mb-2">📊</div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada laporan</p>
                                    <a href="{{ route('laporan.create') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm mt-2 inline-block transition-colors">
                                        Buat laporan pertama
                                    </a>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Recent Notifications -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="w-2 h-6 bg-yellow-500 rounded-full mr-3"></span>
                                    Notifikasi Terbaru
                                </h3>
                                <a href="{{ route('notifications.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors flex items-center">
                                    Lihat semua
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="space-y-4">
                                @auth
                                    @php
                                        $recentNotifications = auth()->user()->notifications->take(4);
                                    @endphp
                                    @forelse($recentNotifications as $notification)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group {{ $notification->read_at ? '' : 'border-l-4 border-yellow-500' }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="text-sm {{ $notification->read_at ? 'text-gray-600 dark:text-gray-300' : 'text-gray-900 dark:text-white font-semibold' }}">
                                                    {{ $notification->data['message'] ?? 'Notifikasi sistem' }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                    @if(!$notification->read_at)
                                                        <span class="ml-2 px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Baru</span>
                                                    @endif
                                                </p>
                                            </div>
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-sm ml-2 transition-colors opacity-0 group-hover:opacity-100">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-8">
                                        <div class="text-gray-400 text-4xl mb-2">🔔</div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada notifikasi</p>
                                        <p class="text-xs text-gray-400 mt-1">Notifikasi akan muncul di sini</p>
                                    </div>
                                    @endforelse
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-blue-900/20 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                    Informasi Sistem
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Akun Testing</h4>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Admin</span>
                                <span class="text-sm text-gray-900 dark:text-white">admin@storage.com</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">User</span>
                                <span class="text-sm text-gray-900 dark:text-white">user@storage.com</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Fitur Sistem</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs px-3 py-2 rounded-lg text-center">CRUD Laporan</span>
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs px-3 py-2 rounded-lg text-center">2FA Security</span>
                            <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 text-xs px-3 py-2 rounded-lg text-center">Notifikasi</span>
                            <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs px-3 py-2 rounded-lg text-center">Export CSV</span>
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
        });
    </script>
</x-app-layout>
