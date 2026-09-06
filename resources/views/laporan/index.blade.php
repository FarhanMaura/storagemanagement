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
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-700 dark:to-indigo-900 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    🏢 Inventaris Barang Kantor Walikota Palembang
                                </h1>
                                <p class="text-blue-100 text-lg">
                                    Daftar data barang masuk dan keluar di lingkungan Kantor Walikota
                                </p>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <div class="mt-4 md:mt-0 flex space-x-3">
                                    <a href="{{ route('laporan.export.form') }}"
                                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-lg hover:bg-white/30 transition-all duration-300 border border-white/30 hover:scale-105 flex items-center font-medium">
                                        <span class="mr-2">📊</span>
                                        Export Data
                                    </a>
                                    <a href="{{ route('laporan.create') }}"
                                       class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition-all duration-300 border border-white hover:scale-105 flex items-center font-semibold">
                                        <span class="mr-2">➕</span>
                                        Tambah Barang
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Barang Masuk -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 p-4 rounded-xl shadow-sm">
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
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900 dark:to-red-800 p-4 rounded-xl shadow-sm">
                                <span class="text-2xl text-red-600 dark:text-red-400">📤</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang Keluar</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_keluar']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Barang -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 p-4 rounded-xl shadow-sm">
                                <span class="text-2xl text-blue-600 dark:text-blue-400">📊</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Data Barang</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total_barang']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
                <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="🔍 Cari kode barang, nama barang, atau keterangan..."
                                class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            >
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('laporan.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 font-semibold">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-xl mb-6 shadow-sm">
                    {{ session('success') }}
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
                                    <span class="text-lg font-bold {{ $item->jenis_laporan === 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $item->jenis_laporan === 'masuk' ? '+' : '-' }}{{ $item->jumlah }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->lokasi }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('laporan.show', $item->id) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 transition duration-150 p-2 rounded-lg hover:bg-blue-50"
                                           title="Detail">
                                            🔍
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('laporan.edit', $item->id) }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 transition duration-150 p-2 rounded-lg hover:bg-green-50"
                                               title="Edit">
                                                ✏️
                                            </a>
                                            <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 transition duration-150 p-2 rounded-lg hover:bg-red-50"
                                                        title="Hapus"
                                                        onclick="return confirm('Yakin hapus data barang ini?')">
                                                    🗑️
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

            <!-- Empty State -->
            @if($laporan->count() == 0)
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📊</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Belum ada data barang</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai dengan menambah inventaris barang baru.</p>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('laporan.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold">
                            Tambah Barang Pertama
                        </a>
                    @endif
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $laporan->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
