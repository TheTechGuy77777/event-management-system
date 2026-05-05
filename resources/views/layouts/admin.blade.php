<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — EventPlug')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        .gold-gradient {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .gold-text {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .glass-gold {
            background: rgba(245, 158, 11, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .glow-gold {
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }

        .btn-gold {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            transform: translateY(-1px);
        }

        .btn-outline-gold {
            border: 1px solid rgba(245, 158, 11, 0.5);
            color: #f59e0b;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background: rgba(245, 158, 11, 0.1);
            border-color: #f59e0b;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 12px;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
        }

        .sidebar-link.active {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .sidebar-icon {
            width: 18px;
            text-align: center;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        #sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }

        #sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar-nav::-webkit-scrollbar-thumb {
            background: #f59e0b44;
            border-radius: 3px;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        ::-webkit-scrollbar-thumb {
            background: #f59e0b;
            border-radius: 3px;
        }

        /* Fix dropdown option visibility */
        select option {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        select {
            color: #d1d5db;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full bg-[#080808] text-white">

    <div class="flex h-full">

        <!-- ===== ADMIN SIDEBAR ===== -->
        <aside id="sidebar"
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 flex flex-col bg-[#0d0d0d] border-r border-white/5 h-screen -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-hidden">

            <!-- Logo -->
            <div class="px-6 py-5 border-b border-white/5 flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div
                        class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center glow-gold group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-bolt text-black text-sm"></i>
                    </div>
                    <span class="font-display text-lg">Event<span class="gold-text">Plug</span></span>
                </a>
                <!-- Admin Badge -->
                <div class="mt-3 glass-gold rounded-lg px-3 py-1.5 inline-flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-amber-400 text-xs"></i>
                    <span class="text-amber-400 text-xs font-medium">Super Admin</span>
                </div>
            </div>

            <!-- Admin Info -->
            <div class="px-4 py-4 border-b border-white/5 flex-shrink-0">
                <div class="glass rounded-2xl p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
                            <span class="text-black font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-amber-400 text-xs">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-4 py-4 pb-10">

                <p class="text-gray-600 text-xs font-medium uppercase tracking-wider px-2 mb-3">Overview</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high sidebar-icon"></i>Dashboard
                </a>

                <p class="text-gray-600 text-xs font-medium uppercase tracking-wider px-2 mb-3 mt-6">Management</p>

                <a href="{{ route('admin.managers') }}"
                    class="sidebar-link {{ request()->routeIs('admin.managers*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users sidebar-icon"></i>Event Managers
                </a>

                <a href="{{ route('admin.events') }}"
                    class="sidebar-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days sidebar-icon"></i>All Events
                </a>

                <a href="{{ route('admin.transactions') }}"
                    class="sidebar-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-transfer sidebar-icon"></i>Transactions
                </a>

                <p class="text-gray-600 text-xs font-medium uppercase tracking-wider px-2 mb-3 mt-6">Settings</p>

                <a href="{{ route('admin.commission') }}"
                    class="sidebar-link {{ request()->routeIs('admin.commission*') ? 'active' : '' }}">
                    <i class="fa-solid fa-percent sidebar-icon"></i>Commission
                </a>

                <a href="{{ route('admin.categories') }}"
                    class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags sidebar-icon"></i>Categories
                </a>

                <a href="{{ route('admin.announcements') }}"
                    class="sidebar-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn sidebar-icon"></i>Announcements
                </a>

                <div class="pt-4 mt-4 border-t border-white/5">
                    <a href="{{ route('home') }}" class="sidebar-link">
                        <i class="fa-solid fa-globe sidebar-icon"></i>View Website
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="sidebar-link w-full text-left hover:text-red-400 hover:bg-red-500/5">
                            <i class="fa-solid fa-arrow-right-from-bracket sidebar-icon"></i>Sign Out
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden" onclick="toggleSidebar()">
        </div>

        <!-- ===== MAIN AREA ===== -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

            <!-- Sticky Topbar -->
            <header class="sticky top-0 z-30 bg-[#080808]/90 backdrop-blur-xl border-b border-white/5 flex-shrink-0">
                <div class="flex items-center justify-between px-6 h-16">
                    <div class="flex items-center gap-4">
                        <button class="lg:hidden text-gray-400 hover:text-white transition-colors"
                            onclick="toggleSidebar()">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                        <div>
                            <h1 class="text-white font-semibold text-base">
                                @yield('page-title', 'Admin Dashboard')
                            </h1>
                            <p class="text-gray-500 text-xs">
                                @yield('page-subtitle', 'Platform overview and controls')
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Admin Badge -->
                        <div class="glass-gold px-3 py-1.5 rounded-xl hidden sm:flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-amber-400 text-xs"></i>
                            <span class="text-amber-400 text-xs font-medium">Admin Panel</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 lg:p-8 pb-32">

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-[-20px]"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-[-20px]"
                        class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-[#1a1a1a] border border-green-500/30 text-green-400 px-5 py-4 rounded-2xl shadow-2xl max-w-sm">
                        <i class="fa-solid fa-circle-check text-lg flex-shrink-0"></i>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button x-on:click="show = false"
                            class="ml-2 text-green-400/50 hover:text-green-400 transition-colors flex-shrink-0">
                            <i class="fa-solid fa-times text-xs"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-[-20px]"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-[-20px]"
                        class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-[#1a1a1a] border border-red-500/30 text-red-400 px-5 py-4 rounded-2xl shadow-2xl max-w-sm">
                        <i class="fa-solid fa-circle-exclamation text-lg flex-shrink-0"></i>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                        <button x-on:click="show = false"
                            class="ml-2 text-red-400/50 hover:text-red-400 transition-colors flex-shrink-0">
                            <i class="fa-solid fa-times text-xs"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>

</html>
