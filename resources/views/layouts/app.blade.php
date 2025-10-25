<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Sidebar state
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        </script>

        <style>
            .sidebar-transition {
                transition: all 0.3s ease-in-out;
            }

            .main-content-transition {
                transition: margin-left 0.3s ease-in-out;
            }

            /* Mobile sidebar overlay */
            .sidebar-overlay {
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            /* Hamburger animation */
            .hamburger-line {
                transition: all 0.3s ease;
            }

            .hamburger-active .hamburger-line:nth-child(1) {
                transform: rotate(45deg) translate(6px, 6px);
            }

            .hamburger-active .hamburger-line:nth-child(2) {
                opacity: 0;
            }

            .hamburger-active .hamburger-line:nth-child(3) {
                transform: rotate(-45deg) translate(6px, -6px);
            }
        </style>
    </head>
    <body class="font-sans antialiased h-full">
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Mobile Overlay -->
            <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 hidden lg:hidden" onclick="toggleSidebar()"></div>

            <!-- Sidebar -->
            <div id="sidebar" class="w-64 bg-[#17517E] shadow-xl z-50 fixed lg:relative h-full sidebar-transition transform lg:transform-none -translate-x-full lg:translate-x-0">
                @include('layouts.navigation')
            </div>

            <!-- Main Content -->
            <div id="main-content" class="flex-1 flex flex-col overflow-hidden main-content-transition lg:ml-0">
                <!-- Top Header -->
                <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
                    <div class="flex items-center justify-between py-3 px-4 sm:px-6">
                        <!-- Left: Hamburger Menu & Page Title -->
                        <div class="flex items-center space-x-4">
                            <button id="hamburger-btn" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors lg:hidden">
                                <div class="w-6 h-6 relative">
                                    <span class="hamburger-line absolute left-0 top-1 w-6 h-0.5 bg-gray-800 dark:bg-gray-200 rounded"></span>
                                    <span class="hamburger-line absolute left-0 top-3 w-6 h-0.5 bg-gray-800 dark:bg-gray-200 rounded"></span>
                                    <span class="hamburger-line absolute left-0 top-5 w-6 h-0.5 bg-gray-800 dark:bg-gray-200 rounded"></span>
                                </div>
                            </button>

                            <!-- Desktop Toggle Sidebar -->
                            <button id="desktop-toggle-sidebar" class="hidden lg:flex items-center justify-center p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            @isset($header)
                                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                                    {{ $header }}
                                </h2>
                            @endisset
                        </div>

                        <!-- Right: Theme Toggle & User Info -->
                        <div class="flex items-center space-x-4">
                            <!-- Theme Toggle -->
                            <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <svg id="theme-icon-light" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                                </svg>
                                <svg id="theme-icon-dark" class="w-5 h-5 text-gray-800 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </button>

                            <!-- User Avatar for Mobile -->
                            <div class="lg:hidden">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900">
                    <div class="container mx-auto px-4 sm:px-6 py-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script>
            // Theme Toggle
            document.getElementById('theme-toggle').addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    document.getElementById('theme-icon-light').classList.add('hidden');
                    document.getElementById('theme-icon-dark').classList.remove('hidden');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    document.getElementById('theme-icon-light').classList.remove('hidden');
                    document.getElementById('theme-icon-dark').classList.add('hidden');
                }
            });

            // Update theme icon on load
            document.addEventListener('DOMContentLoaded', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.getElementById('theme-icon-light').classList.remove('hidden');
                    document.getElementById('theme-icon-dark').classList.add('hidden');
                } else {
                    document.getElementById('theme-icon-light').classList.add('hidden');
                    document.getElementById('theme-icon-dark').classList.remove('hidden');
                }
            });

            // Sidebar Functions
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const hamburger = document.getElementById('hamburger-btn');
                const mainContent = document.getElementById('main-content');

                if (sidebar.classList.contains('-translate-x-full')) {
                    // Open sidebar
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    hamburger.classList.add('hamburger-active');
                    document.body.classList.add('overflow-hidden');
                } else {
                    // Close sidebar
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    hamburger.classList.remove('hamburger-active');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            function toggleDesktopSidebar() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');

                if (sidebar.classList.contains('lg:w-20')) {
                    // Expand sidebar
                    sidebar.classList.remove('lg:w-20');
                    sidebar.classList.add('lg:w-64');
                    mainContent.classList.remove('lg:ml-0');
                    localStorage.setItem('sidebar-collapsed', 'false');
                } else {
                    // Collapse sidebar
                    sidebar.classList.remove('lg:w-64');
                    sidebar.classList.add('lg:w-20');
                    mainContent.classList.add('lg:ml-0');
                    localStorage.setItem('sidebar-collapsed', 'true');
                }
            }

            // Event Listeners
            document.getElementById('hamburger-btn').addEventListener('click', toggleSidebar);
            document.getElementById('desktop-toggle-sidebar').addEventListener('click', toggleDesktopSidebar);
            document.getElementById('sidebar-overlay').addEventListener('click', toggleSidebar);

            // Initialize sidebar state
            document.addEventListener('DOMContentLoaded', function() {
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    toggleDesktopSidebar();
                }
            });

            // Close sidebar on mobile when clicking nav links
            document.querySelectorAll('#sidebar a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        toggleSidebar();
                    }
                });
            });

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const sidebar = document.getElementById('sidebar');
                    if (!sidebar.classList.contains('-translate-x-full')) {
                        toggleSidebar();
                    }
                }
            });
        </script>
    </body>
</html>
