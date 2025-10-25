<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
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
                                    📋 Management Laporan
                                </h1>
                                <p class="text-blue-100 text-lg">
                                    Kelola semua data barang masuk dan keluar dengan mudah
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0 flex space-x-3">
                                <a href="{{ route('laporan.export.form') }}"
                                   class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-lg hover:bg-white/30 transition-all duration-300 border border-white/30 hover:scale-105 flex items-center">
                                    <span class="mr-2">📊</span>
                                    Export Data
                                </a>
                                <a href="{{ route('laporan.create') }}"
                                   class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition-all duration-300 border border-white hover:scale-105 flex items-center font-semibold">
                                    <span class="mr-2">➕</span>
                                    Buat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Barang Masuk -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                                <span class="text-2xl text-green-600 dark:text-green-400">📥</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang Masuk</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_masuk']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Keluar -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl group-hover:bg-red-200 dark:group-hover:bg-red-800 transition-colors">
                                <span class="text-2xl text-red-600 dark:text-red-400">📤</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang Keluar</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_keluar']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                            <span class="text-2xl text-blue-600 dark:text-blue-400">📊</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transaksi</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_barang']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
                <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="🔍 Cari kode barang, nama barang..."
                                class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            >
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('laporan.index') }}" class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 font-semibold">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4 rounded-xl mb-6 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode Barang</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($laporan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $item->jenis_laporan === 'masuk' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $item->jenis_laporan === 'masuk' ? '📥 Masuk' : '📤 Keluar' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm font-semibold text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                        {{ $item->kode_barang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $item->nama_barang }}</div>
                                    @if($item->keterangan)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($item->keterangan, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold {{ $item->jenis_laporan === 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $item->jenis_laporan === 'masuk' ? '+' : '-' }}{{ $item->jumlah }}
                                        </span>
                                        <span class="ml-1 text-sm text-gray-500 dark:text-gray-400">{{ $item->satuan }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->lokasi }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <a href="{{ route('laporan.show', $item->id) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition duration-150 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                           title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if($item->user_id === auth()->id() || auth()->user()->email === 'admin@storage.com')
                                            <a href="{{ route('laporan.edit', $item->id) }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition duration-150 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if(auth()->user()->email === 'admin@storage.com')
                                            <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition duration-150 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30"
                                                        title="Hapus"
                                                        onclick="return confirm('Yakin hapus laporan ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $laporan->links() }}
            </div>

            <!-- Empty State -->
            @if($laporan->count() == 0)
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📊</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Belum ada laporan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai dengan membuat laporan barang masuk atau keluar pertama Anda.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('laporan.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold">
                            Buat Laporan Pertama
                        </a>
                        <button onclick="window.location.reload()" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold">
                            🔄 Refresh
                        </button>
                    </div>
                </div>
            @endif

            <!-- Quick Info -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl border border-blue-200 dark:border-blue-700 p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-400 text-2xl">💡</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">Tips Penggunaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700 dark:text-blue-300">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                Gunakan kolom pencarian untuk mencari laporan
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                Klik <span class="font-semibold">Export Data</span> untuk download
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                Hanya admin yang dapat menghapus laporan
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                User hanya bisa edit laporan mereka sendiri
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh data setiap 2 menit
        setInterval(() => {
            window.location.reload();
        }, 120000);

        // Keyboard shortcut untuk buat laporan baru (Ctrl + N)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route("laporan.create") }}';
            }

            // Keyboard shortcut untuk export (Ctrl + E)
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                window.location.href = '{{ route("laporan.export.form") }}';
            }
        });

        // Show keyboard shortcuts info
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Keyboard Shortcuts:');
            console.log('• Ctrl + N : Buat laporan baru');
            console.log('• Ctrl + E : Export data');
            console.log('• Auto refresh setiap 2 menit');
        });
    </script>
</x-app-layout>
