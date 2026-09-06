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
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 dark:from-indigo-700 dark:to-purple-900 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    📋 Peminjaman Barang Kantor Walikota Palembang
                                </h1>
                                <p class="text-indigo-100 text-lg">
                                    {{ auth()->user()->isAdmin() ? 'Kelola persetujuan & pengembalian peminjaman barang' : 'Ajukan dan pantau status peminjaman barang Anda' }}
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                @if(auth()->user()->isUser())
                                <a href="{{ route('peminjaman.create') }}"
                                   class="bg-white text-indigo-600 px-6 py-3 rounded-lg hover:bg-indigo-50 transition-all duration-300 border border-white hover:scale-105 flex items-center font-semibold">
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
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <!-- Total -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($statistik['total']) }}</p>
                </div>
                <!-- Pending -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
                    <p class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Menunggu</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($statistik['pending']) }}</p>
                </div>
                <!-- Approved -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
                    <p class="text-xs font-medium text-green-600 dark:text-green-400">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($statistik['approved']) }}</p>
                </div>
                <!-- Processed -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
                    <p class="text-xs font-medium text-purple-600 dark:text-purple-400">Diproses</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($statistik['processed']) }}</p>
                </div>
                <!-- Completed / Returned -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
                    <p class="text-xs font-medium text-indigo-600 dark:text-indigo-400">Dikembalikan</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($statistik['returned']) }}</p>
                </div>
                <!-- Rejected -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4">
                    <p class="text-xs font-medium text-red-600 dark:text-red-400">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($statistik['rejected']) }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-xl mb-6 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl mb-6 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode Pinjam</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode Pinjam</th>
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
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-gray-100">
                                    {{ $item->jumlah_pinjam }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $item->tanggal_pinjam->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">s/d {{ $item->tanggal_kembali->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'processed' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                            'completed' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'returned' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
                                        ];
                                        $statusTexts = [
                                            'pending' => 'Menunggu Persetujuan',
                                            'approved' => 'Disetujui',
                                            'processed' => 'Barang Diserahkan',
                                            'completed' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            'returned' => 'Dikembalikan'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusTexts[$item->status] ?? $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <!-- Detail Button -->
                                        <a href="{{ route('peminjaman.show', $item->id) }}" class="text-blue-600 p-2 hover:bg-blue-50 rounded" title="Detail">🔍</a>

                                        <!-- Admin Approve / Reject -->
                                        @if(auth()->user()->isAdmin() && $item->status === 'pending')
                                            <form action="{{ route('peminjaman.approve', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 p-2 hover:bg-green-50 rounded" title="Setujui Peminjaman" onclick="return confirm('Setujui peminjaman ini?')">✅</button>
                                            </form>
                                            <button onclick="showRejectForm({{ $item->id }})" class="text-red-600 p-2 hover:bg-red-50 rounded" title="Tolak Peminjaman">❌</button>
                                        @endif

                                        <!-- Admin Process (Barang Keluar) -->
                                        @if(auth()->user()->isAdmin() && $item->status === 'approved')
                                            <form action="{{ route('peminjaman.process', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-purple-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-purple-700 font-medium" onclick="return confirm('Proses penyerahan barang?')">Serahkan Barang</button>
                                            </form>
                                        @endif

                                        <!-- User / Admin Return Item -->
                                        @if(($item->user_id === auth()->id() || auth()->user()->isAdmin()) && in_array($item->status, ['completed', 'processed']))
                                            <form action="{{ route('peminjaman.return', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-orange-700 font-medium" onclick="return confirm('Kembalikan barang ini ke stok?')">Kembalikan Barang</button>
                                            </form>
                                        @endif

                                        <!-- Delete / Cancel -->
                                        @if(($item->user_id === auth()->id() && $item->status === 'pending') || auth()->user()->isAdmin())
                                            <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 p-2 hover:bg-red-50 rounded" title="Hapus / Batal" onclick="return confirm('Yakin ingin membatalkan/menghapus pengajuan ini?')">🗑️</button>
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
                {{ $peminjaman->links() }}
            </div>

            <!-- Empty State -->
            @if($peminjaman->count() == 0)
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📋</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Belum ada pengajuan peminjaman</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if(auth()->user()->isUser())
                            Mulai dengan mengajukan peminjaman barang pertama Anda.
                        @else
                            Tidak ada pengajuan peminjaman dari user.
                        @endif
                    </p>
                    @if(auth()->user()->isUser())
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
