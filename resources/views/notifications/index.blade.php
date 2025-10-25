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
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                                    🔔 Notifikasi Sistem
                                </h1>
                                <p class="text-blue-100 text-lg">
                                    Semua aktivitas dan pemberitahuan terbaru
                                </p>
                            </div>
                            <div class="mt-4 md:mt-0 text-blue-200 text-4xl">
                                📨
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Header Actions -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Notifikasi Saya</h1>
                        <div class="flex space-x-3">
                            @if($notifications->count() > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition-all duration-300 hover:scale-105 font-semibold text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Tandai Semua Dibaca
                                    </button>
                                </form>
                                <form action="{{ route('notifications.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 font-semibold text-sm flex items-center"
                                            onclick="return confirm('Hapus semua notifikasi?')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus Semua
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($notifications as $notification)
                        <div class="px-6 py-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-300 group {{ $notification->read_at ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-shrink-0 mt-1">
                                            @if(!$notification->read_at)
                                                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                            @else
                                                <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <p class="text-gray-800 dark:text-gray-200 {{ $notification->read_at ? '' : 'font-semibold' }} text-lg leading-relaxed">
                                                {{ $notification->data['message'] ?? 'Notifikasi Sistem' }}
                                            </p>
                                            <div class="flex items-center mt-3 text-sm text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $notification->created_at->format('d F Y H:i') }}
                                                <span class="mx-2">•</span>
                                                {{ $notification->created_at->diffForHumans() }}
                                                @if(!$notification->read_at)
                                                    <span class="ml-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 px-2 py-1 rounded-full text-xs font-semibold flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Baru
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition duration-150 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30"
                                                    title="Tandai sudah dibaca">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <div class="text-gray-400 text-6xl mb-4">🔔</div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Tidak ada notifikasi</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Notifikasi akan muncul di sini ketika ada aktivitas baru di sistem.</p>
                            <div class="flex justify-center space-x-4">
                                <a href="{{ route('dashboard') }}"
                                   class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 font-semibold">
                                    Kembali ke Dashboard
                                </a>
                                <button onclick="window.location.reload()"
                                        class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-300 hover:scale-105 font-semibold">
                                    🔄 Refresh
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>

            <!-- Quick Info -->
            <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-2xl border border-purple-200 dark:border-purple-700 p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-xl">
                            <span class="text-purple-600 dark:text-purple-400 text-xl">💡</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-purple-800 dark:text-purple-200 mb-3">Informasi Notifikasi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-purple-700 dark:text-purple-300">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                Notifikasi baru ditandai dengan titik biru
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                Hover pada notifikasi untuk melihat opsi
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                Tandai sebagai dibaca untuk menghilangkan notif baru
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                                Notifikasi auto-update setiap 30 detik
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh notifications setiap 30 detik
        setInterval(() => {
            window.location.reload();
        }, 30000);

        // Add hover effects to notification items
        document.addEventListener('DOMContentLoaded', function() {
            const notificationItems = document.querySelectorAll('.group');
            notificationItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</x-app-layout>
