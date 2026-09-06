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
                                            <button type="button" 
                                                    onclick="showReturnModal({{ $item->id }}, '{{ addslashes($item->barang->nama_barang) }}', '{{ $item->barang->kode_barang }}', {{ $item->jumlah_pinjam }})"
                                                    class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-orange-700 font-medium transition-all shadow-sm flex items-center">
                                                <span class="mr-1">🔄</span> Kembalikan
                                            </button>
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

    <!-- Return Modal -->
    <div id="returnModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm hidden transition-opacity duration-200" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="min-h-screen px-3 sm:px-4 py-6 flex items-center justify-center">
            <!-- Modal Card -->
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-3rem)] transform transition-all my-auto">
                
                <!-- Modal Header (Fixed at top) -->
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/50 rounded-xl flex items-center justify-center text-orange-600 dark:text-orange-400 text-lg shadow-sm shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white leading-tight">Pengembalian Barang</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pemeriksaan kondisi fisik barang saat dikembalikan</p>
                        </div>
                    </div>
                    <button type="button" onclick="hideReturnModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Form (Body scrolls if content overflows) -->
                <form id="returnForm" method="POST" class="flex flex-col overflow-hidden m-0 flex-1">
                    @csrf
                    
                    <div class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1">
                        <!-- Info Barang -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-blue-100 dark:border-blue-800">
                            <p class="text-[11px] font-semibold text-blue-800 dark:text-blue-200 uppercase tracking-wider mb-0.5">Barang Yang Dipinjam</p>
                            <p id="modalItemName" class="text-sm sm:text-base font-bold text-gray-900 dark:text-white break-words">-</p>
                            <p class="text-xs sm:text-sm text-blue-600 dark:text-blue-300 font-medium mt-1">
                                Total dipinjam: <span id="modalTotalPinjam" class="font-bold text-blue-700 dark:text-blue-200">0</span> unit
                            </p>
                        </div>

                        <!-- Input Jumlah Rusak -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="modal_jumlah_rusak" class="block text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Jumlah Unit Rusak
                                </label>
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">Opsional</span>
                            </div>
                            <input type="number" name="jumlah_rusak_kembali" id="modal_jumlah_rusak"
                                   value="0" min="0"
                                   placeholder="0"
                                   class="w-full px-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base sm:text-lg font-bold"
                                   oninput="recalculateIndexCondition()">
                            <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Biarkan <strong>0</strong> atau kosong jika semua barang kembali dalam kondisi baik.</p>
                        </div>

                        <!-- Preview Kalkulasi Kondisi -->
                        <div class="grid grid-cols-2 gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl sm:rounded-2xl border border-gray-200 dark:border-gray-600">
                            <div class="text-center p-1 sm:p-2">
                                <span class="text-[10px] sm:text-xs text-green-600 dark:text-green-400 font-semibold block leading-tight">Kondisi Baik (Masuk Stok)</span>
                                <div class="mt-1">
                                    <span id="modalPreviewBaik" class="text-xl sm:text-2xl font-black text-green-600 dark:text-green-400">0</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">unit</span>
                                </div>
                            </div>
                            <div class="text-center p-1 sm:p-2 border-l border-gray-200 dark:border-gray-600">
                                <span class="text-[10px] sm:text-xs text-red-500 dark:text-red-400 font-semibold block leading-tight">Kondisi Rusak (Terkunci)</span>
                                <div class="mt-1">
                                    <span id="modalPreviewRusak" class="text-xl sm:text-2xl font-black text-red-500 dark:text-red-400">0</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">unit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Pengembalian -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="modal_catatan_kembali" class="block text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Catatan Pengembalian
                                </label>
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">Opsional</span>
                            </div>
                            <textarea name="catatan_kembali" id="modal_catatan_kembali" rows="2"
                                      class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs sm:text-sm"
                                      placeholder="Contoh: 1 unit laptop rusak di bagian port charger..."></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer (Fixed at bottom) -->
                    <div class="px-5 sm:px-6 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 shrink-0">
                        <button type="button" onclick="hideReturnModal()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 font-semibold text-sm transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm shadow-md hover:shadow-orange-600/30 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Konfirmasi Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm hidden transition-opacity duration-200">
        <div class="min-h-screen px-3 sm:px-4 py-6 flex items-center justify-center">
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[calc(100vh-2rem)] sm:max-h-[calc(100vh-3rem)] transform transition-all my-auto">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 shrink-0">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tolak Pengajuan Peminjaman</h3>
                    <button type="button" onclick="hideRejectForm()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="rejectForm" method="POST" class="flex flex-col overflow-hidden m-0 flex-1">
                    @csrf
                    <div class="p-5 sm:p-6 overflow-y-auto flex-1">
                        <label for="catatan_admin" class="block text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                            placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                    <div class="px-5 sm:px-6 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 shrink-0">
                        <button type="button" onclick="hideRejectForm()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 font-semibold text-sm transition-colors text-center">
                            Batal
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-md hover:shadow-red-600/30 transition-all text-center">
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTotalPinjam = 0;

        function showReturnModal(peminjamanId, namaBarang, kodeBarang, totalPinjam) {
            currentTotalPinjam = totalPinjam;
            const form = document.getElementById('returnForm');
            form.action = `/peminjaman/${peminjamanId}/return`;
            
            document.getElementById('modalItemName').textContent = `${namaBarang} (${kodeBarang})`;
            document.getElementById('modalTotalPinjam').textContent = totalPinjam;
            
            const rusakInput = document.getElementById('modal_jumlah_rusak');
            rusakInput.value = 0;
            rusakInput.max = totalPinjam;
            
            document.getElementById('modalPreviewBaik').textContent = totalPinjam;
            document.getElementById('modalPreviewRusak').textContent = 0;
            document.getElementById('modal_catatan_kembali').value = '';
            
            const modal = document.getElementById('returnModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideReturnModal() {
            const modal = document.getElementById('returnModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function recalculateIndexCondition() {
            const rusakInput = document.getElementById('modal_jumlah_rusak');
            let rusak = parseInt(rusakInput.value) || 0;
            if (rusak < 0) rusak = 0;
            if (rusak > currentTotalPinjam) {
                rusak = currentTotalPinjam;
                rusakInput.value = currentTotalPinjam;
            }
            const baik = currentTotalPinjam - rusak;
            document.getElementById('modalPreviewBaik').textContent = baik;
            document.getElementById('modalPreviewRusak').textContent = rusak;
        }

        function showRejectForm(peminjamanId) {
            const form = document.getElementById('rejectForm');
            form.action = `/peminjaman/${peminjamanId}/reject`;
            const modal = document.getElementById('rejectModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function hideRejectForm() {
            const modal = document.getElementById('rejectModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const returnModal = document.getElementById('returnModal');
            const rejectModal = document.getElementById('rejectModal');
            if (returnModal && e.target === returnModal.firstElementChild) {
                hideReturnModal();
            }
            if (rejectModal && e.target === rejectModal.firstElementChild) {
                hideRejectForm();
            }
        });

        // Close on ESC
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideReturnModal();
                hideRejectForm();
            }
        });
    </script>
</x-app-layout>
