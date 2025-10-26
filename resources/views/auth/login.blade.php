<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                        'float-medium': 'float 6s ease-in-out infinite',
                        'float-fast': 'float 4s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(23, 81, 126, 0.3); }
            to { box-shadow: 0 0 30px rgba(23, 81, 126, 0.6); }
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
        .notification-bubble {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300 gradient-bg dark:bg-gray-900">
    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle" class="fixed top-6 right-6 z-50 p-3 rounded-full glass-effect shadow-lg hover:shadow-xl transition-all duration-300 group">
        <i class="fas fa-moon text-white group-hover:text-yellow-300 transition-colors"></i>
    </button>

    <!-- Notification Bubble -->
    <div id="notificationBubble" class="notification-bubble hidden">
        <div class="glass-effect rounded-2xl shadow-2xl p-6 max-w-sm border-l-4 border-yellow-400">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-3 h-3 bg-green-400 rounded-full mt-2 animate-pulse"></div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-white mb-1">Welcome Back! 👋</h4>
                    <p class="text-xs text-white/80">Sign in to access your dashboard</p>
                </div>
                <button onclick="hideNotification()" class="flex-shrink-0 text-white/60 hover:text-white">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Shapes -->
        <div class="absolute top-1/4 left-1/4 w-20 h-20 bg-white/10 rounded-full animate-float-slow"></div>
        <div class="absolute top-1/3 right-1/4 w-16 h-16 bg-white/5 rounded-lg animate-float-medium" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-24 h-24 bg-white/8 rounded-full animate-float-fast" style="animation-delay: -4s;"></div>
        <div class="absolute top-1/2 right-1/3 w-12 h-12 bg-white/6 rounded-lg animate-float-slow" style="animation-delay: -1s;"></div>

        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 50px 50px;"></div>
    </div>

    <!-- Main Login Card -->
    <div class="fade-in max-w-md w-full space-y-8 glass-effect rounded-3xl shadow-2xl p-8 relative overflow-hidden">
        <!-- Background Pattern Inside Card -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-white rounded-full"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-white rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="text-center relative z-10">
            <div class="mx-auto h-20 w-20 bg-white/20 rounded-2xl flex items-center justify-center shadow-lg mb-4 border border-white/30 animate-pulse-glow">
                <i class="fas fa-lock-open text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
            <p class="mt-2 text-sm text-white/80">Sign in to your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 p-4 rounded-xl bg-white/20 text-white border border-white/30 backdrop-blur-sm" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6 relative z-10">
            @csrf

            <!-- Email Input -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-white">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-white/60"></i>
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="block w-full pl-10 pr-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                           placeholder="Enter your email" :value="old('email')">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-yellow-300" />
            </div>

            <!-- Password Input -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-white">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-white/60"></i>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="block w-full pl-10 pr-12 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-200 backdrop-blur-sm"
                           placeholder="Enter your password">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="passwordIcon" class="fas fa-eye text-white/60 hover:text-white transition-colors"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-yellow-300" />
            </div>

            <!-- Remember Me & Register -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="w-4 h-4 text-primary bg-white/20 border-white/30 rounded focus:ring-white focus:ring-2 transition duration-200">
                    <span class="ml-2 text-sm text-white/80">{{ __('Remember me') }}</span>
                </label>

                <a class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 border border-white/30 rounded-xl text-sm font-medium text-white transition-all duration-200 transform hover:scale-105 active:scale-95 backdrop-blur-sm"
                   href="{{ route('register') }}">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('Create Account') }}
                </a>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-primary bg-white hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-sign-in-alt text-primary/80 group-hover:text-primary"></i>
                </span>
                {{ __('Sign in') }}
            </button>
        </form>

        <!-- Decorative Elements -->
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
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

        // Password Toggle
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Notification System
        function showNotification() {
            const bubble = document.getElementById('notificationBubble');
            bubble.classList.remove('hidden');
            setTimeout(hideNotification, 5000);
        }

        function hideNotification() {
            const bubble = document.getElementById('notificationBubble');
            bubble.classList.add('opacity-0', 'transform', 'translate-y-10');
            setTimeout(() => {
                bubble.classList.add('hidden');
                bubble.classList.remove('opacity-0', 'transform', 'translate-y-10');
            }, 300);
        }

        // Show notification on page load
        setTimeout(showNotification, 1000);

        // Add floating animation to random elements
        document.addEventListener('DOMContentLoaded', function() {
            const floatingElements = document.querySelectorAll('.absolute.animate-float-slow, .absolute.animate-float-medium, .absolute.animate-float-fast');
            floatingElements.forEach(el => {
                // Randomize animation delay
                const randomDelay = Math.random() * -5;
                el.style.animationDelay = randomDelay + 's';
            });
        });
    </script>
</body>
</html>
