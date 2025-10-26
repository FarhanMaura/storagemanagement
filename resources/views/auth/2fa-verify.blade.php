<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA - Storage Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#17517E',
                    },
                    animation: {
                        'float-slow': 'float 8s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(120deg); }
            66% { transform: translateY(8px) rotate(240deg); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(255, 193, 7, 0.4); }
            to { box-shadow: 0 0 35px rgba(255, 193, 7, 0.8); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes countdown {
            0% { width: 100%; }
            100% { width: 0%; }
        }
        .fade-in { animation: fadeIn 0.8s ease-out; }
        .gradient-bg {
            background: linear-gradient(-45deg, #17517E, #1e6aa8, #2c8bd1, #4ab3f2);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .countdown-bar {
            height: 3px;
            background: linear-gradient(90deg, #10b981, #f59e0b, #ef4444);
            animation: countdown 30s linear infinite;
        }

        /* Responsive text sizes */
        @media (max-width: 640px) {
            .responsive-text {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center py-8 px-3 sm:px-6 lg:px-8 transition-colors duration-300 gradient-bg dark:bg-gray-900">
    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle" class="fixed top-4 right-4 z-50 p-2 sm:p-3 rounded-full glass-effect shadow-lg hover:shadow-xl transition-all duration-300 group">
        <i class="fas fa-moon text-white text-sm sm:text-base group-hover:text-yellow-300 transition-colors"></i>
    </button>

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-full animate-float-slow"></div>
        <div class="absolute top-1/3 right-1/4 w-12 h-12 sm:w-16 sm:h-16 bg-white/5 rounded-lg animate-float-slow" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-16 h-16 sm:w-24 sm:h-24 bg-white/8 rounded-full animate-float-slow" style="animation-delay: -4s;"></div>
    </div>

    <!-- Main Card -->
    <div class="fade-in max-w-lg w-full space-y-4 sm:space-y-6 glass-effect rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-6 lg:p-8 relative overflow-hidden mx-2">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute -top-16 -right-16 w-32 h-32 sm:w-40 sm:h-40 bg-white rounded-full"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 sm:w-40 sm:h-40 bg-white rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="text-center relative z-10">
            <div class="mx-auto h-14 w-14 sm:h-16 sm:w-16 lg:h-20 lg:w-20 bg-white/20 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg mb-3 sm:mb-4 border border-white/30 animate-pulse-glow">
                <i class="fas fa-user-lock text-white text-lg sm:text-xl lg:text-2xl"></i>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white break-words">Verifikasi Two-Factor Authentication</h2>
            <p class="mt-1 sm:mt-2 text-white/80 text-xs sm:text-sm break-words">Buka aplikasi authenticator dan masukkan kode OTP 6-digit</p>
        </div>

        <!-- Countdown Bar -->
        <div class="relative z-10">
            <div class="countdown-bar rounded-full mb-1 sm:mb-2"></div>
            <p class="text-white/60 text-xs text-center">Kode berubah setiap 30 detik</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="glass-effect border border-yellow-400/30 text-yellow-300 px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl mb-3 sm:mb-4 relative z-10 backdrop-blur-sm">
                <div class="flex items-center justify-center sm:justify-start">
                    <i class="fas fa-exclamation-triangle mr-1 sm:mr-2 flex-shrink-0 text-sm"></i>
                    <span class="text-xs sm:text-sm break-words">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- OTP Form -->
        <form method="POST" action="{{ route('2fa.verify.post') }}" class="relative z-10">
            @csrf

            <div class="mb-4 sm:mb-6">
                <label for="code" class="block text-sm font-medium text-white mb-2 sm:mb-3">
                    <i class="fas fa-clock mr-1 sm:mr-2"></i>Kode OTP 6-digit
                </label>
                <div class="relative">
                    <input
                        type="text"
                        name="code"
                        id="code"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required
                        class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 border border-white/30 rounded-lg sm:rounded-xl bg-white/10 text-white placeholder-white/60 focus:ring-2 focus:ring-white focus:border-transparent text-center text-lg sm:text-xl tracking-widest font-mono backdrop-blur-sm transition-all duration-200 text-base"
                        autofocus
                        placeholder="123456"
                        autocomplete="one-time-code"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6)"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-key text-white/60 text-sm sm:text-lg"></i>
                    </div>
                </div>
                @error('code')
                    <p class="text-yellow-300 text-xs sm:text-sm mt-1 sm:mt-2 flex items-center break-words">
                        <i class="fas fa-exclamation-circle mr-1 sm:mr-2 flex-shrink-0"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="group relative w-full flex justify-center py-3 sm:py-4 px-4 border border-transparent rounded-lg sm:rounded-xl shadow-lg text-sm font-semibold text-primary bg-white hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
            >
                <span class="absolute left-0 inset-y-0 flex items-center pl-2 sm:pl-3">
                    <i class="fas fa-check-circle text-primary/80 group-hover:text-primary text-sm sm:text-base"></i>
                </span>
                <span class="text-xs sm:text-sm">Verifikasi & Lanjutkan</span>
            </button>
        </form>

        <!-- User Info -->
        <div class="text-center p-2 sm:p-3 lg:p-4 glass-effect rounded-lg sm:rounded-xl border border-white/20 relative z-10">
            <p class="text-white/70 text-xs sm:text-sm break-words">
                <i class="fas fa-user mr-1 sm:mr-2"></i>Login sebagai:
                <strong class="text-white break-all">{{ Auth::user()->email }}</strong>
            </p>
        </div>

        <!-- Reset 2FA Section -->
@if(Auth::user()->is2FAEnabled())
<div class="mt-6 p-4 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-700" style="position: relative; z-index: 1000;">
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
        <h4 class="font-semibold text-red-800 dark:text-red-300">Kehilangan Akses?</h4>
    </div>
    <p class="text-red-700 dark:text-red-400 text-sm mb-3">
        Jika Anda kehilangan akses ke authenticator app, reset 2FA untuk generate QR code baru.
    </p>
    <form action="{{ route('2fa.reset') }}" method="POST" id="reset2FAForm">
        @csrf
        <button type="submit"
                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-150 text-sm w-full cursor-pointer"
                style="position: relative; z-index: 1001; pointer-events: auto;">
            <i class="fas fa-redo mr-2"></i>Reset 2FA & Generate QR Baru
        </button>
    </form>
</div>

<script>
    // Reset 2FA confirmation - versi lebih aggressive
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('reset2FAForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!confirm('Yakin ingin reset 2FA? Anda harus setup ulang dengan authenticator app.')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });

            // Force button to be clickable
            const button = form.querySelector('button');
            button.style.pointerEvents = 'auto';
            button.style.position = 'relative';
            button.style.zIndex = '1001';
        }
    });
</script>
@endif

        <!-- Instructions -->
        <div class="mt-4 sm:mt-6 p-3 sm:p-4 lg:p-6 glass-effect rounded-xl sm:rounded-2xl border border-white/20 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-start space-y-3 sm:space-y-0 sm:space-x-3">
                <div class="flex-shrink-0 flex justify-center sm:justify-start">
                    <i class="fas fa-lock text-white/80 text-base sm:text-lg mt-0.5"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-white mb-2 sm:mb-3 flex items-center justify-center sm:justify-start text-sm sm:text-base break-words">
                        <i class="fas fa-info-circle mr-1 sm:mr-2 flex-shrink-0"></i>Cara menggunakan:
                    </h4>
                    <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm responsive-text">
                        <div class="flex items-start">
                            <span class="w-5 h-5 sm:w-6 sm:h-6 bg-white/20 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">1</span>
                            <span class="text-white/80 break-words flex-1">Buka aplikasi Google Authenticator/Authy</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-5 h-5 sm:w-6 sm:h-6 bg-white/20 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">2</span>
                            <span class="text-white/80 break-words flex-1">Cari entry "<strong class="text-white break-words">{{ config('app.name') }}</strong>"</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-5 h-5 sm:w-6 sm:h-6 bg-white/20 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">3</span>
                            <span class="text-white/80 break-words flex-1">Masukkan kode 6-digit yang muncul</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-5 h-5 sm:w-6 sm:h-6 bg-white/20 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">4</span>
                            <span class="text-white/80 break-words flex-1">Kode berubah setiap 30 detik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute -bottom-6 -left-6 w-16 h-16 sm:w-20 sm:h-20 bg-white/5 rounded-full blur-xl"></div>
        <div class="absolute -top-6 -right-6 w-16 h-16 sm:w-20 sm:h-20 bg-white/5 rounded-full blur-xl"></div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const icon = darkModeToggle.querySelector('i');

        if (localStorage.getItem('dark-mode') === 'true') {
            document.documentElement.classList.add('dark');
            icon.classList.replace('fa-moon', 'fa-sun');
        }

        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('dark-mode', 'true');
                icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                localStorage.setItem('dark-mode', 'false');
                icon.classList.replace('fa-sun', 'fa-moon');
            }
        });

        // Auto submit when 6 digits entered
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                this.form.submit();
            }
        });

        // Reset countdown bar
        function resetCountdown() {
            const bar = document.querySelector('.countdown-bar');
            bar.style.animation = 'none';
            void bar.offsetWidth; // Trigger reflow
            bar.style.animation = 'countdown 30s linear infinite';
        }

        // Reset countdown every 30 seconds
        setInterval(resetCountdown, 30000);
    </script>
</body>
</html>
