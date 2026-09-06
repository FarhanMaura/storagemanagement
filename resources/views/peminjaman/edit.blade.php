<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Banner -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-white mb-2">✏️ Edit Pengajuan Peminjaman</h1>
                                <p class="text-orange-100">Update informasi pengajuan peminjaman</p>
                            </div>
                            <div class="text-orange-200 text-4xl">
                                🔄
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    @if($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 rounded-xl mb-6">
                            <div class="flex">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold">Terjadi kesalahan:</h4>
                                    <ul class="list-disc list-inside mt-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Barang -->
                        <div class="mb-6">
                            <label for="barang_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Barang *</label>
                            <select name="barang_id" id="barang_id" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="" data-stok="0" data-rusak="0">-- Pilih Barang --</option>
                                @foreach($barangTersedia as $barang)
                                    @php
                                        $layak = $barang->id == $peminjaman->barang_id
                                            ? $barang->jumlah_baik + $peminjaman->jumlah_pinjam
                                            : $barang->jumlah_baik;
                                        $rusak = $barang->jumlah_rusak ?? 0;
                                    @endphp
                                    <option value="{{ $barang->id }}"
                                        {{ (old('barang_id', $peminjaman->barang_id) == $barang->id) ? 'selected' : '' }}
                                        data-stok="{{ $layak }}"
                                        data-rusak="{{ $rusak }}"
                                        data-total="{{ $barang->jumlah }}"
                                        {{ $layak <= 0 ? 'disabled' : '' }}
                                        class="{{ $layak <= 0 ? 'text-gray-400 bg-gray-100 dark:bg-gray-800' : '' }}">
                                        {{ $barang->nama_barang }} ({{ $barang->kode_barang }}) 
                                        @if($layak > 0)
                                            — Siap Pinjam: {{ $layak }} unit {{ $rusak > 0 ? '(Rusak: ' . $rusak . ')' : '' }}
                                        @else
                                            — [HABIS / RUSAK SEMUA ({{ $rusak }} Rusak)]
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Jumlah Pinjam -->
                            <div>
                                <label for="jumlah_pinjam" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jumlah Pinjam *</label>
                                <input type="number" name="jumlah_pinjam" id="jumlah_pinjam"
                                    value="{{ old('jumlah_pinjam', $peminjaman->jumlah_pinjam) }}"
                                    required min="1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="0">
                                <small class="text-gray-500 dark:text-gray-400 mt-2 block" id="stok-info">Memuat stok...</small>
                            </div>

                            <!-- Tanggal Pinjam -->
                            <div>
                                <label for="tanggal_pinjam" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tanggal Pinjam *</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                    value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Tanggal Kembali -->
                            <div>
                                <label for="tanggal_kembali" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tanggal Kembali *</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                                    value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali->format('Y-m-d')) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="mb-8">
                            <label for="keperluan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Keperluan *</label>
                            <textarea name="keperluan" id="keperluan" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Jelaskan keperluan peminjaman...">{{ old('keperluan', $peminjaman->keperluan) }}</textarea>
                        </div>

                        <!-- Informasi -->
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-xl p-6 mb-8 border border-yellow-200 dark:border-yellow-700">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <span class="text-yellow-600 dark:text-yellow-400 text-xl">ℹ️</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Informasi</h4>
                                    <div class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                                        <p>• Peminjaman diajukan oleh: <strong>{{ $peminjaman->user->name }}</strong></p>
                                        <p>• Tanggal diajukan: {{ $peminjaman->created_at->format('d F Y H:i') }}</p>
                                        <p>• Stok yang rusak tidak dihitung dalam kuota yang dapat dipinjam.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('peminjaman.show', $peminjaman->id) }}"
                               class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold">
                                Batal
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStokDisplay() {
            const selectEl = document.getElementById('barang_id');
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const stokLayak = selectedOption ? (selectedOption.getAttribute('data-stok') || 0) : 0;
            const stokRusak = selectedOption ? (selectedOption.getAttribute('data-rusak') || 0) : 0;
            const stokInfo = document.getElementById('stok-info');
            const jumlahInput = document.getElementById('jumlah_pinjam');

            if (selectEl.value) {
                if (parseInt(stokLayak) > 0) {
                    stokInfo.innerHTML = '<span class="text-green-600 dark:text-green-400 font-semibold">✓ Siap pinjam: ' + stokLayak + ' unit</span>' + 
                        (parseInt(stokRusak) > 0 ? ' <span class="text-amber-600 dark:text-amber-400">(' + stokRusak + ' rusak & terkunci)</span>' : '');
                } else {
                    stokInfo.innerHTML = '<span class="text-red-600 dark:text-red-400 font-semibold">⚠️ Seluruh unit rusak atau stok habis!</span>';
                }
                jumlahInput.max = stokLayak;
            } else {
                stokInfo.textContent = 'Pilih barang untuk melihat stok siap pinjam';
                jumlahInput.removeAttribute('max');
            }
        }

        document.getElementById('barang_id').addEventListener('change', updateStokDisplay);
        document.addEventListener('DOMContentLoaded', updateStokDisplay);

        document.getElementById('jumlah_pinjam').addEventListener('input', function() {
            const selectEl = document.getElementById('barang_id');
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const stok = selectedOption ? (selectedOption.getAttribute('data-stok') || 0) : 0;
            const jumlah = this.value;

            if (parseInt(jumlah) > parseInt(stok)) {
                this.setCustomValidity('Jumlah tidak boleh melebihi stok barang yang layak pakai (' + stok + ' unit)');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</x-app-layout>
