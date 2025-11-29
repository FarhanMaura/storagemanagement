<div>
    @if(session('success'))
    <div id="toast-success" class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-500 ease-out">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl border border-green-400 max-w-md backdrop-blur-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-bounce">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="font-bold text-lg mb-1">Berhasil!</h3>
                    <p class="text-sm text-green-50">{{ session('success') }}</p>
                </div>
                <button onclick="closeToast('toast-success')" class="ml-4 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-2 h-1 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white/60 rounded-full animate-progress"></div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="toast-error" class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-500 ease-out">
        <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl border border-red-400 max-w-md backdrop-blur-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="font-bold text-lg mb-1">Oops!</h3>
                    <p class="text-sm text-red-50">{{ session('error') }}</p>
                </div>
                <button onclick="closeToast('toast-error')" class="ml-4 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-2 h-1 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white/60 rounded-full animate-progress"></div>
            </div>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div id="toast-info" class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-500 ease-out">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-6 py-4 rounded-2xl shadow-2xl border border-blue-400 max-w-md backdrop-blur-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="font-bold text-lg mb-1">Informasi</h3>
                    <p class="text-sm text-blue-50">{{ session('info') }}</p>
                </div>
                <button onclick="closeToast('toast-info')" class="ml-4 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-2 h-1 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white/60 rounded-full animate-progress"></div>
            </div>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div id="toast-warning" class="fixed top-4 right-4 z-50 transform translate-x-full transition-all duration-500 ease-out">
        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white px-6 py-4 rounded-2xl shadow-2xl border border-yellow-400 max-w-md backdrop-blur-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-bounce">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="font-bold text-lg mb-1">Perhatian!</h3>
                    <p class="text-sm text-yellow-50">{{ session('warning') }}</p>
                </div>
                <button onclick="closeToast('toast-warning')" class="ml-4 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-2 h-1 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white/60 rounded-full animate-progress"></div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes progress {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        .animate-progress {
            animation: progress 5s linear forwards;
        }
    </style>

    <script>
        // Show toast with slide-in animation
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = ['toast-success', 'toast-error', 'toast-info', 'toast-warning'];
            
            toasts.forEach(toastId => {
                const toast = document.getElementById(toastId);
                if (toast) {
                    // Slide in
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full');
                        toast.classList.add('translate-x-0');
                    }, 100);

                    // Auto dismiss after 5 seconds
                    setTimeout(() => {
                        closeToast(toastId);
                    }, 5000);
                }
            });
        });

        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                
                // Remove from DOM after animation
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }
    </script>
</div>
