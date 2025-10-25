<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA - Storage Management</title>
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
            from { box-shadow: 0 0 20px rgba(23, 81, 126, 0.4); }
            to { box-shadow: 0 0 35px rgba(23, 81, 126, 0.8); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
        .qr-glow {
            transition: all 0.3s ease;
        }
        .qr-glow:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
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
                <i class="fas fa-shield-alt text-white text-lg sm:text-xl lg:text-2xl"></i>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white break-words">Setup Two-Factor Authentication</h2>
            <p class="mt-1 sm:mt-2 text-white/80 text-xs sm:text-sm">Scan QR code dengan aplikasi authenticator</p>
        </div>

        <!-- QR Code Section -->
        <div class="text-center mb-4 sm:mb-6 relative z-10">
            <div class="glass-effect p-3 sm:p-4 lg:p-6 rounded-xl sm:rounded-2xl inline-block qr-glow border border-white/30 max-w-full">
                <div class="bg-white p-2 sm:p-3 rounded-lg mb-2 sm:mb-3 mx-auto" style="max-width: 200px;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}"
                         alt="QR Code" class="w-full h-auto rounded-lg">
                </div>
                <p class="text-white/70 text-xs sm:text-sm mt-1 sm:mt-2 break-words">
                    <i class="fas fa-qrcode mr-1 sm:mr-2"></i>Scan this QR code
                </p>
            </div>
        </div>

        <!-- OTP Form -->
        <form method="POST" action="{{ route('2fa.setup.verify') }}" class="relative z-10">
            @csrf

            <div class="mb-4 sm:mb-6">
                <label for="code" class="block text-sm font-medium text-white mb-2 sm:mb-3">
                    <i class="fas fa-key mr-1 sm:mr-2"></i>Masukkan Kode OTP 6-digit
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
                        placeholder="000000"
                        autocomplete="one-time-code"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6)"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-mobile-alt text-white/60 text-sm sm:text-lg"></i>
                    </div>
                </div>
                @error('code')
                    <p class="text-yellow-300 text-xs sm:text-sm mt-1 sm:mt-2 flex items-center break-words">
                        <i class="fas fa-exclamation-triangle mr-1 sm:mr-2 flex-shrink-0"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="group relative w-full flex justify-center py-3 sm:py-4 px-4 border border-transparent rounded-lg sm:rounded-xl shadow-lg text-sm font-semibold text-primary bg-white hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
            >
                <span class="absolute left-0 inset-y-0 flex items-center pl-2 sm:pl-3">
                    <i class="fas fa-shield-check text-primary/80 group-hover:text-primary text-sm sm:text-base"></i>
                </span>
                <span class="text-xs sm:text-sm">Verifikasi & Aktifkan 2FA</span>
            </button>
        </form>

        <!-- Info Box -->
        <div class="mt-4 sm:mt-6 p-3 sm:p-4 lg:p-6 glass-effect rounded-xl sm:rounded-2xl border border-white/20 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-start space-y-3 sm:space-y-0 sm:space-x-3">
                <div class="flex-shrink-0 flex justify-center sm:justify-start">
                    <i class="fas fa-info-circle text-white/80 text-base sm:text-lg mt-0.5"></i>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h4 class="font-semibold text-white mb-2 flex items-center justify-center sm:justify-start break-words text-sm sm:text-base">
                        <i class="fas fa-download mr-1 sm:mr-2 flex-shrink-0"></i>Download Authenticator App:
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm">
                        <div class="text-center p-1 sm:p-2 bg-white/10 rounded sm:rounded-lg">
                            <i class="fab fa-google text-white mb-0.5 sm:mb-1 block text-sm sm:text-base"></i>
                            <p class="text-white/80 break-words">Google Authenticator</p>
                        </div>
                        <div class="text-center p-1 sm:p-2 bg-white/10 rounded sm:rounded-lg">
                            <i class="fas fa-mobile text-white mb-0.5 sm:mb-1 block text-sm sm:text-base"></i>
                            <p class="text-white/80 break-words">Authy</p>
                        </div>
                        <div class="text-center p-1 sm:p-2 bg-white/10 rounded sm:rounded-lg">
                            <i class="fab fa-microsoft text-white mb-0.5 sm:mb-1 block text-sm sm:text-base"></i>
                            <p class="text-white/80 break-words">Microsoft Authenticator</p>
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

        // Auto focus and move to next input (simulated)
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                this.blur();
            }
        });
    </script>
</body>
</html>
