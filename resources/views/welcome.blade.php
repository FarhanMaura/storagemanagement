<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Inventaris Kantor Walikota Palembang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-900 text-white font-sans antialiased min-h-screen flex flex-col justify-between">
    <!-- Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-2xl shadow-lg">
                🏢
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Kantor Walikota Palembang</h1>
                <p class="text-xs text-gray-400">Sistem Informasi Inventaris & Peminjaman Barang</p>
            </div>
        </div>

        <nav class="flex items-center space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 shadow-md">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-300 shadow-md">
                    Masuk (Login)
                </a>
            @endauth
        </nav>
    </header>

    <!-- Hero Section -->
    <main class="w-full max-w-7xl mx-auto px-6 py-12 flex-1 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="flex-1 space-y-6">
            <div class="inline-flex items-center space-x-2 bg-blue-900/50 border border-blue-500/30 px-4 py-2 rounded-full text-blue-300 text-sm">
                <span>🏛️ Official Web Application</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight">
                Pengelolaan Inventaris & Peminjaman Barang Kantor
            </h1>
            <p class="text-gray-300 text-lg md:text-xl leading-relaxed">
                Platform terpadu untuk pencatatan inventaris barang masuk, barang keluar, serta pengajuan peminjaman barang untuk pegawai di lingkungan Kantor Walikota Palembang.
            </p>
            <div class="pt-4 flex flex-wrap gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl flex items-center">
                        Buka Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl flex items-center">
                        Masuk Ke Aplikasi →
                    </a>
                @endauth
            </div>
        </div>

        <div class="flex-1 w-full max-w-md bg-gray-800/80 border border-gray-700 rounded-3xl p-8 shadow-2xl backdrop-blur-sm">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <span class="mr-2">🔑</span> Akun Akses Sistem
            </h2>
            <div class="space-y-4 text-sm">
                <div class="p-4 bg-gray-900/80 rounded-2xl border border-gray-700">
                    <p class="font-semibold text-blue-400">Admin Utama (Super Admin)</p>
                    <p class="text-gray-300 mt-1">Email: <code class="bg-gray-800 px-2 py-0.5 rounded text-white">admin@palembang.go.id</code></p>
                    <p class="text-gray-300">Password: <code class="bg-gray-800 px-2 py-0.5 rounded text-white">password123</code></p>
                    <p class="text-xs text-gray-400 mt-2">Hak akses: Kelola barang, approve peminjaman, buat laporan PDF/Excel.</p>
                </div>

                <div class="p-4 bg-gray-900/80 rounded-2xl border border-gray-700">
                    <p class="font-semibold text-green-400">Pegawai (User Biasa)</p>
                    <p class="text-gray-300 mt-1">Email: <code class="bg-gray-800 px-2 py-0.5 rounded text-white">user@palembang.go.id</code></p>
                    <p class="text-gray-300">Password: <code class="bg-gray-800 px-2 py-0.5 rounded text-white">password123</code></p>
                    <p class="text-xs text-gray-400 mt-2">Hak akses: Melihat daftar barang, mengajukan peminjaman & pengembalian.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-gray-800 py-6">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Kantor Walikota Palembang. All rights reserved.
        </div>
    </footer>
</body>
</html>
