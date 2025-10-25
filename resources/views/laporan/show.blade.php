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
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-white mb-2">📋 Detail Laporan</h1>
                                <p class="text-purple-100">Informasi lengkap tentang laporan barang</p>
                            </div>
                            <div class="mt-4 md:mt-0 flex space-x-3">
                                <a href="{{ route('laporan.index') }}"
                                   class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-300 border border-white/30">
                                    ← Kembali
                                </a>
                                @if($laporan->user_id === auth()->id() || auth()->user()->email === 'admin@storage.com')
                                    <a href="{{ route('laporan.edit', $laporan->id) }}"
                                       class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50 transition-all duration-300 font-semibold">
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Status Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $laporan->jenis_laporan === 'masuk' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $laporan->jenis_laporan === 'masuk' ? '📥 BARANG MASUK' : '📤 BARANG KELUAR' }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Dibuat: {{ $laporan->created_at->format('d F Y H:i') }}
                            </span>
                        </div>
                        @if(auth()->user()->email === 'admin@storage.com')
                            <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-300 font-semibold text-sm"
                                        onclick="return confirm('Yakin hapus laporan ini?')">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Informasi Utama -->
                        <div class="space-y-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                                    Informasi Barang
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kode Barang</label>
                                        <p class="text-lg font-mono font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-600 px-3 py-2 rounded-lg">
                                            {{ $laporan->kode_barang }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Barang</label>
                                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $laporan->nama_barang }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah</label>
                                        <p class="text-3xl font-bold {{ $laporan->jenis_laporan === 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $laporan->jenis_laporan === 'masuk' ? '+' : '-' }}{{ number_format($laporan->jumlah) }}
                                            <span class="text-lg text-gray-600 dark:text-gray-400">{{ $laporan->satuan }}</span>
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Lokasi</label>
                                        <p class="text-lg text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $laporan->lokasi }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($laporan->keterangan)
                            <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-xl p-6 border border-yellow-200 dark:border-yellow-700">
                                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3 flex items-center">
                                    <span class="w-2 h-6 bg-yellow-500 rounded-full mr-3"></span>
                                    Keterangan
                                </h3>
                                <p class="text-yellow-700 dark:text-yellow-300">{{ $laporan->keterangan }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="space-y-6">
                            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-4 flex items-center">
                                    <span class="w-2 h-6 bg-blue-500 rounded-full mr-3"></span>
                                    Informasi Sistem
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Dibuat Oleh</label>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                                {{ strtoupper(substr($laporan->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-blue-800 dark:text-blue-200">{{ $laporan->user->name }}</p>
                                                <p class="text-sm text-blue-600 dark:text-blue-300">{{ $laporan->user->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Tanggal Dibuat</label>
                                        <p class="text-blue-800 dark:text-blue-200 font-semibold">{{ $laporan->created_at->format('d F Y H:i') }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Terakhir Diupdate</label>
                                        <p class="text-blue-800 dark:text-blue-200 font-semibold">{{ $laporan->updated_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <span class="w-2 h-6 bg-gray-500 rounded-full mr-3"></span>
                                    Timeline Aktivitas
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2 mr-3"></div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Laporan Dibuat</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $laporan->created_at->format('d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @if($laporan->created_at != $laporan->updated_at)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2 mr-3"></div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Laporan Diupdate</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $laporan->updated_at->format('d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
