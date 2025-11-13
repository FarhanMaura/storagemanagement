<!-- Sidebar Container -->
<div class="h-full flex flex-col text-white overflow-hidden">
    <!-- Logo / Header Sidebar -->
    <div class="p-4 border-b border-blue-600 flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 transition-all duration-300">
                <div class="w-10 h-10 bg-white text-[#17517E] rounded-lg flex items-center justify-center font-bold text-lg shadow-md">
                    MS
                </div>
                <div class="lg:block hidden transition-opacity duration-300">
                    <h1 class="text-lg font-bold whitespace-nowrap">Management Storage</h1>
                    <p class="text-xs text-blue-200 whitespace-nowrap">Sistem Pengelolaan Kertas</p>
                </div>
            </div>
            <button class="lg:hidden p-1 rounded hover:bg-blue-600 transition-colors" onclick="toggleSidebar()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        <!-- Dashboard - SEMUA ROLE -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
            <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📊</span>
            <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Dashboard</span>
            <span class="lg:hidden ml-2 px-2 py-1 text-xs bg-blue-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                Dashboard
            </span>
        </a>

        <!-- ==================== MENU BERDASARKAN ROLE ==================== -->

        <!-- MAIN ADMIN - Akses semua menu kecuali Laporan Barang (karena sudah ada Laporan) -->
        @auth
            @if(auth()->user()->isAdmin())
            <!-- Laporan -->
            <a href="{{ route('laporan.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('laporan.*') && !request()->routeIs('peminjaman.*') && !request()->routeIs('barcode.*') && !request()->routeIs('statistik.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📋</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Laporan</span>
                <span class="lg:hidden ml-2 px-2 py-1 text-xs bg-blue-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    Laporan
                </span>
            </a>

            <!-- Barcode -->
            <a href="{{ route('barcode.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('barcode.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">🏷️</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Barcode</span>
                <span class="lg:hidden ml-2 px-2 py-1 text-xs bg-blue-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    Barcode
                </span>
            </a>

            <!-- Statistik -->
            <a href="{{ route('statistik.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('statistik.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📈</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Statistik</span>
                <span class="lg:hidden ml-2 px-2 py-1 text-xs bg-blue-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    Statistik
                </span>
            </a>

            <!-- Management Peminjaman (Main Admin bisa akses semua status) -->
            <a href="{{ route('peminjaman.index') }}?status=all"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') && (!request()->has('status') || request('status') == 'all') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📦</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Management Peminjaman</span>
                <span class="lg:hidden ml-2 px-2 py-1 text-xs bg-blue-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                    Management Peminjaman
                </span>
            </a>

            <!-- Sub-menu untuk Peminjaman (Hanya tampil di Main Admin) -->
            <div class="ml-8 space-y-1 border-l-2 border-blue-500 pl-3">
                <!-- Validasi Pengajuan -->
                <a href="{{ route('peminjaman.index') }}?status=pending"
                   class="flex items-center px-3 py-2 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') && request('status') == 'pending' ? 'bg-blue-500 text-white shadow-md' : 'text-blue-200 hover:bg-blue-500 hover:text-white hover:shadow-md' }}">
                    <span class="w-6 h-6 flex items-center justify-center text-sm">🧾</span>
                    <span class="ml-2 text-sm whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Validasi Pengajuan</span>
                </a>

                <!-- Persetujuan Peminjaman -->
                <a href="{{ route('peminjaman.index') }}?status=validated"
                   class="flex items-center px-3 py-2 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') && request('status') == 'validated' ? 'bg-blue-500 text-white shadow-md' : 'text-blue-200 hover:bg-blue-500 hover:text-white hover:shadow-md' }}">
                    <span class="w-6 h-6 flex items-center justify-center text-sm">🧑‍💼</span>
                    <span class="ml-2 text-sm whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Persetujuan Peminjaman</span>
                </a>

                <!-- Proses Barang Keluar -->
                <a href="{{ route('peminjaman.index') }}?status=approved"
                   class="flex items-center px-3 py-2 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') && request('status') == 'approved' ? 'bg-blue-500 text-white shadow-md' : 'text-blue-200 hover:bg-blue-500 hover:text-white hover:shadow-md' }}">
                    <span class="w-6 h-6 flex items-center justify-center text-sm">📦</span>
                    <span class="ml-2 text-sm whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Proses Barang Keluar</span>
                </a>

                <!-- Semua Status -->
                <a href="{{ route('peminjaman.index') }}?status=all"
                   class="flex items-center px-3 py-2 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') && request('status') == 'all' ? 'bg-blue-500 text-white shadow-md' : 'text-blue-200 hover:bg-blue-500 hover:text-white hover:shadow-md' }}">
                    <span class="w-6 h-6 flex items-center justify-center text-sm">👁️</span>
                    <span class="ml-2 text-sm whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Lihat Semua</span>
                </a>
            </div>
            @endif
        @endauth

        <!-- USER REGULAR -->
        @auth
            @if(auth()->user()->isUser() && !auth()->user()->isAdmin())
            <!-- Laporan -->
            <a href="{{ route('laporan.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('laporan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📋</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Laporan</span>
            </a>

            <!-- Barcode -->
            <a href="{{ route('barcode.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('barcode.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">🏷️</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Barcode</span>
            </a>

            <!-- Statistik -->
            <a href="{{ route('statistik.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('statistik.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📈</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Statistik</span>
            </a>

            <!-- Peminjaman Saya -->
            <a href="{{ route('peminjaman.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📦</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Peminjaman Saya</span>
            </a>
            @endif
        @endauth

        <!-- Hanya PETUGAS PENGELOLA PENGAJUAN (Admin1) -->
        @auth
            @if(auth()->user()->isPetugasPengajuan() && !auth()->user()->isAdmin())
            <a href="{{ route('peminjaman.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">🧾</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Validasi Pengajuan</span>
            </a>
            @endif
        @endauth

        <!-- Hanya MANAJER PERSETUJUAN (Admin2) -->
        @auth
            @if(auth()->user()->isManajerPersetujuan() && !auth()->user()->isAdmin())
            <a href="{{ route('peminjaman.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">🧑‍💼</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Persetujuan Peminjaman</span>
            </a>
            @endif
        @endauth

        <!-- Hanya PETUGAS BARANG KELUAR (Admin3) -->
        @auth
            @if(auth()->user()->isPetugasBarangKeluar() && !auth()->user()->isAdmin())
            <a href="{{ route('peminjaman.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('peminjaman.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📦</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Proses Barang Keluar</span>
            </a>

            <a href="{{ route('laporan.index') }}"
               class="flex items-center px-3 py-3 rounded-lg transition duration-200 group {{ request()->routeIs('laporan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-md' }}">
                <span class="w-8 h-8 flex items-center justify-center text-lg transition-all duration-300">📋</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Laporan Barang</span>
            </a>
            @endif
        @endauth
    </nav>

    <!-- Notifications Section - SEMUA ROLE -->
    <div class="px-3 py-4 border-t border-blue-600 flex-shrink-0">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex items-center w-full px-3 py-3 text-blue-100 rounded-lg hover:bg-blue-600 hover:text-white transition duration-200 group">
                <span class="w-8 h-8 flex items-center justify-center text-lg">🔔</span>
                <span class="ml-3 font-medium whitespace-nowrap transition-all duration-300 lg:opacity-100 lg:block">Notifikasi</span>
                @auth
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-auto bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center animate-pulse">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                @endauth
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" x-cloak
                 @click.away="open = false"
                 class="absolute bottom-full left-3 right-3 mb-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-y-auto">

                <!-- Header -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Notifikasi Terbaru</h3>
                        <div class="flex space-x-2">
                            @auth
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded transition duration-150">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            @endauth
                            <button @click="open = false" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded transition duration-150">
                                ✕ Tutup
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @auth
                        @forelse(auth()->user()->notifications->take(5) as $notification)
                            <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 cursor-pointer group {{ $notification->read_at ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500' }}"
                                 onclick="window.location.href='{{ route('notifications.index') }}'">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800 dark:text-white {{ $notification->read_at ? '' : 'font-semibold' }} group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                            {{ $notification->data['message'] ?? 'Notifikasi sistem' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                            @if(!$notification->read_at)
                                                <span class="ml-2 text-green-600 dark:text-green-400 font-medium">• Baru</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="ml-2" onclick="event.stopPropagation()">
                                            @csrf
                                            <button type="submit" class="text-xs text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 bg-green-100 dark:bg-green-900 px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-150">
                                                ✓ Baca
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <div class="text-gray-400 text-4xl mb-2">🔔</div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada notifikasi</p>
                                <p class="text-xs text-gray-400 mt-1">Aktivitas baru akan muncul di sini</p>
                            </div>
                        @endforelse
                    @endauth
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-b-lg">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition duration-150">
                            Lihat semua notifikasi
                        </a>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            @auth
                                {{ auth()->user()->notifications->count() }} total
                            @endauth
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Section - SEMUA ROLE -->
    <div class="p-3 border-t border-blue-600 bg-blue-700 flex-shrink-0">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-10 h-10 bg-white text-blue-700 rounded-full flex items-center justify-center text-sm font-bold shadow-md flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0 transition-all duration-300 lg:opacity-100 lg:block">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-200 truncate">{{ auth()->user()->email }}</p>
                <p class="text-xs text-blue-300 truncate">{{ auth()->user()->getRoleName() }}</p>
            </div>
        </div>

        <div class="space-y-1 transition-all duration-300 lg:opacity-100 lg:block">
            <!-- 2FA Toggle -->
            @auth
                @if(auth()->user()->is2FAEnabled())
                    <form method="POST" action="{{ route('2fa.disable') }}" class="block">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-2 py-2 text-xs text-red-200 hover:bg-blue-600 rounded transition duration-150 group">
                            <span class="w-6 text-center">🔒</span>
                            <span class="ml-2 whitespace-nowrap">Nonaktifkan 2FA</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('2fa.setup') }}" class="flex items-center px-2 py-2 text-xs text-green-200 hover:bg-blue-600 rounded transition duration-150 group">
                        <span class="w-6 text-center">🔓</span>
                        <span class="ml-2 whitespace-nowrap">Aktifkan 2FA</span>
                    </a>
                @endif
            @endauth

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}" class="flex items-center px-2 py-2 text-xs text-blue-200 hover:bg-blue-600 rounded transition duration-150 group">
                <span class="w-6 text-center">👤</span>
                <span class="ml-2 whitespace-nowrap">Profile</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="flex items-center w-full px-2 py-2 text-xs text-blue-200 hover:bg-blue-600 rounded transition duration-150 group">
                    <span class="w-6 text-center">🚪</span>
                    <span class="ml-2 whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    /* Custom scrollbar for sidebar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #17517E;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #2a7bb6;
        border-radius: 2px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #3a8bc6;
    }
</style>
