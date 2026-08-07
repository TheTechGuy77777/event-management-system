<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EventPlug — Find & Create Amazing Events')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        ::-webkit-scrollbar-thumb {
            background: #f59e0b;
            border-radius: 3px;
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

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #f59e0b;
        }

        .glow-gold {
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }

        .btn-gold {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gold::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-gold:hover::before {
            left: 100%;
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
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
        }

        /* Fix dropdown option visibility */
        select option {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        select {
            color: #d1d5db;
        }

        /* Flatpickr Dark Theme */
        .flatpickr-calendar {
            background: #1a1a1a !important;
            border: 1px solid rgba(245, 158, 11, 0.2) !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
            font-family: 'Inter', sans-serif !important;
        }

        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #1a1a1a !important;
        }

        .flatpickr-months {
            background: #1a1a1a !important;
            border-radius: 16px 16px 0 0 !important;
            padding: 8px 0 !important;
        }

        .flatpickr-month {
            color: #ffffff !important;
            fill: #ffffff !important;
        }

        .flatpickr-current-month {
            color: #ffffff !important;
            font-size: 14px !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month select,
        .flatpickr-current-month .numInputWrapper input {
            color: #ffffff !important;
            background: transparent !important;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            color: #f59e0b !important;
            fill: #f59e0b !important;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: rgba(245, 158, 11, 0.1) !important;
            border-radius: 8px !important;
        }

        .flatpickr-weekdays {
            background: #1a1a1a !important;
        }

        .flatpickr-weekday {
            color: #f59e0b !important;
            font-size: 11px !important;
            font-weight: 600 !important;
        }

        .flatpickr-days {
            border: none !important;
        }

        .dayContainer {
            border: none !important;
        }

        .flatpickr-day {
            color: #d1d5db !important;
            border-radius: 8px !important;
            font-size: 13px !important;
        }

        .flatpickr-day:hover {
            background: rgba(245, 158, 11, 0.1) !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
            color: #f59e0b !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            border-color: #f59e0b !important;
            color: #000000 !important;
            font-weight: 700 !important;
        }

        .flatpickr-day.inRange {
            background: rgba(245, 158, 11, 0.15) !important;
            border-color: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
            box-shadow: -5px 0 0 rgba(245, 158, 11, 0.15), 5px 0 0 rgba(245, 158, 11, 0.15) !important;
        }

        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            border-color: #f59e0b !important;
            color: #000000 !important;
            font-weight: 700 !important;
        }

        .flatpickr-day.today {
            border-color: rgba(245, 158, 11, 0.5) !important;
            color: #f59e0b !important;
        }

        .flatpickr-day.today:hover {
            background: rgba(245, 158, 11, 0.15) !important;
        }

        .flatpickr-day.flatpickr-disabled {
            color: #4b5563 !important;
        }

        .numInputWrapper span svg path {
            fill: #f59e0b !important;
        }

        .flatpickr-time {
            background: #1a1a1a !important;
            border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        .flatpickr-time input,
        .flatpickr-time .flatpickr-am-pm {
            color: #ffffff !important;
            background: transparent !important;
        }

        .flatpickr-time input:hover,
        .flatpickr-time .flatpickr-am-pm:hover {
            background: rgba(245, 158, 11, 0.1) !important;
        }

        .flatpickr-input {
            cursor: pointer !important;
        }

        .flatpickr-input.active {
            border-color: rgba(245, 158, 11, 0.5) !important;
        }
    </style>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @stack('styles')
</head>

<body class="bg-[#080808] text-white min-h-screen flex flex-col">

    <!-- ===== HEADER ===== -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="main-header">
        <div class="glass border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div
                            class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center glow-gold group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-bolt text-black text-sm font-bold"></i>
                        </div>
                        <span class="font-display text-xl tracking-tight">
                            Chibuzo<span class="gold-text">Connect</span>
                        </span>
                    </a>

                    <!-- Desktop Nav -->
                    <nav class="hidden lg:flex items-center gap-8">
                        <a href="{{ route('home') }}" class="nav-link text-sm text-gray-400 font-medium">Discover</a>
                        <a href="#how-it-works" class="nav-link text-sm text-gray-400 font-medium">How It Works</a>
                        <a href="{{ route('pricing') }}" class="nav-link text-sm text-gray-400 font-medium">Pricing</a>
                        <a href="{{ route('about') }}" class="nav-link text-sm text-gray-400 font-medium">About</a>
                    </nav>

                    <!-- Desktop CTA -->
                    <div class="hidden lg:flex items-center gap-3">
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="/admin/dashboard"
                                    class="btn-outline-gold px-4 py-2 rounded-xl text-sm font-medium">
                                    <i class="fa-solid fa-gauge-high mr-2"></i>Admin Panel
                                </a>
                            @else
                                <a href="/dashboard" class="btn-outline-gold px-4 py-2 rounded-xl text-sm font-medium">
                                    <i class="fa-solid fa-gauge mr-2"></i>Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="btn-outline-gold px-4 py-2 rounded-xl text-sm font-medium">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}"
                                class="btn-gold px-4 py-2 rounded-xl text-sm font-semibold text-black">
                                Create Event <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden text-gray-400 hover:text-white transition-colors" onclick="toggleMenu()">
                        <i class="fa-solid fa-bars text-xl" id="menu-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden lg:hidden border-t border-white/5">
                <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-4">
                    <a href="{{ route('home') }}"
                        class="text-gray-400 hover:text-amber-400 text-sm font-medium transition-colors">Discover</a>
                    <a href="#how-it-works"
                        class="text-gray-400 hover:text-amber-400 text-sm font-medium transition-colors">How It
                        Works</a>
                    <a href="{{ route('pricing') }}"
                        class="text-gray-400 hover:text-amber-400 text-sm font-medium transition-colors">Pricing</a>
                    <a href="{{ route('about') }}"
                        class="text-gray-400 hover:text-amber-400 text-sm font-medium transition-colors">About</a>
                    <div class="flex flex-col gap-2 pt-2 border-t border-white/5">
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="/admin/dashboard"
                                    class="btn-outline-gold px-4 py-2 rounded-xl text-sm font-medium text-center">Admin
                                    Panel</a>
                            @else
                                <a href="/dashboard"
                                    class="btn-outline-gold px-4 py-2 rounded-xl text-sm font-medium text-center">Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="btn-outline-gold px-4 py-2 rounded-xl text-sm font-medium text-center">Sign In</a>
                            <a href="{{ route('register') }}"
                                class="btn-gold px-4 py-2 rounded-xl text-sm font-semibold text-black text-center">Create
                                Event</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 pt-16 lg:pt-20">
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="border-t border-white/5 mt-20">
        <div class="glass">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                    <!-- Brand -->
                    <div class="lg:col-span-2">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-black text-sm"></i>
                            </div>
                            <span class="font-display text-xl">
                                Chibuzo<span class="gold-text">Connect</span>
                            </span>
                        </a>
                        <p class="text-gray-500 text-sm leading-relaxed max-w-sm">
                            The modern event ticketing platform for African creators. Create, sell, and manage events
                            with ease.
                        </p>
                        <div class="flex items-center gap-4 mt-6">
                            <a href="#"
                                class="w-9 h-9 rounded-xl glass flex items-center justify-center text-gray-500 hover:text-amber-400 transition-all duration-300">
                                <i class="fa-brands fa-instagram text-sm"></i>
                            </a>
                            <a href="#"
                                class="w-9 h-9 rounded-xl glass flex items-center justify-center text-gray-500 hover:text-amber-400 transition-all duration-300">
                                <i class="fa-brands fa-x-twitter text-sm"></i>
                            </a>
                            <a href="#"
                                class="w-9 h-9 rounded-xl glass flex items-center justify-center text-gray-500 hover:text-amber-400 transition-all duration-300">
                                <i class="fa-brands fa-facebook text-sm"></i>
                            </a>
                            <a href="#"
                                class="w-9 h-9 rounded-xl glass flex items-center justify-center text-gray-500 hover:text-amber-400 transition-all duration-300">
                                <i class="fa-brands fa-linkedin text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Links -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5">Platform</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('home') }}"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">Discover
                                    Events</a></li>
                            <li><a href="#how-it-works"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">How
                                    It Works</a></li>
                            <li><a href="{{ route('pricing') }}"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">Pricing</a>
                            </li>
                            <li><a href="{{ route('about') }}"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">About
                                    Us</a></li>
                            <li><a href="#"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">Blog</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5">Legal</h4>
                        <ul class="space-y-3">
                            <li><a href="#"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">Terms
                                    & Conditions</a></li>
                            <li><a href="#"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">Privacy
                                    Policy</a></li>
                            <li><a href="#"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">Refund
                                    Policy</a></li>
                            <li>
                                <a href="{{ route('contact') }}"
                                    class="text-gray-500 hover:text-amber-400 text-sm transition-colors duration-200">
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div
                    class="border-t border-white/5 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-gray-600 text-sm">
                        © {{ date('Y') }} ChibuzoConnect. All rights reserved.
                    </p>
                    <div class="flex items-center gap-2 text-gray-600 text-sm">
                        <span>Made with</span>
                        <i class="fa-solid fa-heart text-amber-400 text-xs"></i>
                        <span>for African creators</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            menu.classList.toggle('hidden');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-xmark');
        }

        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('shadow-2xl');
            } else {
                header.classList.remove('shadow-2xl');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Auto-initialize all date inputs with flatpickr
        document.addEventListener('DOMContentLoaded', function() {

            // Date only inputs
            document.querySelectorAll('input[type="date"]').forEach(function(el) {
                flatpickr(el, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'D, d M Y',
                    allowInput: false,
                });
            });

            // Datetime-local inputs (for event start/end)
            document.querySelectorAll('input[type="datetime-local"]').forEach(function(el) {
                flatpickr(el, {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    altInput: true,
                    altFormat: 'D, d M Y at h:i K',
                    allowInput: false,
                    time_24hr: false,
                    minuteIncrement: 15,
                });
            });

        });
    </script>
    @stack('scripts')

    <x-whatsapp-float />

</body>

</html>
