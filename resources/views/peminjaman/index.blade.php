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
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    📦 Management Peminjaman
                                </h1>
                                <p class="text-purple-100 text-lg">
                                    Kelola pengajuan dan persetujuan peminjaman barang
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                @if(!$isAdmin)
                                <a href="{{ route('peminjaman.create') }}"
                                   class="bg-white text-purple-600 px-6 py-3 rounded-lg hover:bg-purple-50 transition-all duration-300 border border-white hover:scale-105 flex items-center font-semibold">
                                    <span class="mr-2">➕</span>
                                    Ajukan Peminjaman
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Pengajuan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                                <span class="text-2xl text-blue-600 dark:text-blue-400">📋</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengajuan</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-xl group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800 transition-colors">
                                <span class="text-2xl text-yellow-600 dark:text-yellow-400">⏳</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Menunggu</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['pending']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disetujui -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:scale-105 group">
                    <div class="flex items-center">
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors">
                            <span class="text-2xl text-green-600 dark:text-green-400">✅</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Disetujui</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['approved']) }}</p>
                        </div>
                    </div>
                </div>
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

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 rounded-xl mb-6 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($peminjaman as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm font-semibold text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                        {{ $item->kode_peminjaman }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $item->barang->nama_barang }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Kode: {{ $item->barang->kode_barang }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            {{ $item->jumlah_pinjam }}
                                        </span>
                                        <span class="ml-1 text-sm text-gray-500 dark:text-gray-400">{{ $item->barang->satuan }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $item->tanggal_pinjam->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">s/d {{ $item->tanggal_kembali->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $item->status_color }}">
                                        {{ $item->status_text }}
                                    </span>
                                    @if($item->catatan_admin)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($item->catatan_admin, 30) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <a href="{{ route('peminjaman.show', $item->id) }}"
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition duration-150 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                           title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <!-- Edit Button (hanya untuk user dan status pending) -->
                                        @if(!$isAdmin && $item->status === 'pending')
                                            <a href="{{ route('peminjaman.edit', $item->id) }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition duration-150 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Delete Button -->
                                        @if(($item->user_id === auth()->id() && $item->status === 'pending') || $isAdmin)
                                            <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition duration-150 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30"
                                                        title="{{ $isAdmin ? 'Hapus' : 'Batalkan' }}"
                                                        onclick="return confirm('Yakin {{ $isAdmin ? 'hapus' : 'batalkan' }} pengajuan peminjaman ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if($isAdmin)
                                            @if($item->status === 'pending')
                                                <form action="{{ route('peminjaman.approve', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition duration-150 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30"
                                                            title="Setujui"
                                                            onclick="return confirm('Setujui peminjaman ini?')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <button onclick="showRejectForm({{ $item->id }})"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition duration-150 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30"
                                                        title="Tolak">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        @else
                                            @if($item->status === 'approved')
                                                <form action="{{ route('peminjaman.return', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition duration-150 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                                            title="Kembalikan"
                                                            onclick="return confirm('Konfirmasi pengembalian barang?')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
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
                {{ $peminjaman->links() }}
            </div>

            <!-- Empty State -->
            @if($peminjaman->count() == 0)
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📋</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Belum ada pengajuan peminjaman</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if($isAdmin)
                            Tidak ada pengajuan peminjaman dari user.
                        @else
                            Mulai dengan mengajukan peminjaman barang pertama Anda.
                        @endif
                    </p>
                    @if(!$isAdmin)
                        <a href="{{ route('peminjaman.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold">
                            Ajukan Peminjaman Pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tolak Pengajuan Peminjaman</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="catatan_admin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Alasan Penolakan</label>
                    <textarea name="catatan_admin" id="catatan_admin" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Berikan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideRejectForm()" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-300 font-semibold">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectForm(peminjamanId) {
            const form = document.getElementById('rejectForm');
            form.action = `/peminjaman/${peminjamanId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectForm() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectForm();
            }
        });
    </script>
</x-app-layout>
