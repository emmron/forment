<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Formet' }} - Form Backend</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        accent: {
                            50: '#fdf4ff',
                            100: '#fae8ff',
                            200: '#f5d0fe',
                            300: '#f0abfc',
                            400: '#e879f9',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                        }
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(14, 165, 233, 0.15)',
                        'glow-lg': '0 0 40px rgba(14, 165, 233, 0.2)',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }

        /* Smooth transitions */
        * { transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Code blocks */
        pre code { font-family: 'JetBrains Mono', 'Fira Code', monospace; }

        /* Focus styles */
        .focus-ring:focus { outline: none; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3); }

        /* Gradient text */
        .gradient-text { background: linear-gradient(135deg, #0ea5e9 0%, #d946ef 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* Animated gradient border */
        .gradient-border { position: relative; }
        .gradient-border::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px; background: linear-gradient(135deg, #0ea5e9, #d946ef); -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite: xor; mask-composite: exclude; }

        /* Hover lift effect */
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15); }

        /* Pulse animation */
        @keyframes pulse-glow { 0%, 100% { box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.4); } 50% { box-shadow: 0 0 0 8px rgba(14, 165, 233, 0); } }
        .pulse-glow { animation: pulse-glow 2s infinite; }

        /* Slide up animation */
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slideUp { animation: slideUp 0.3s ease-out; }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-slate-100 font-sans antialiased" x-data="{ mobileMenu: false }">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 border-b border-slate-200/50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-10">
                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                            <div class="relative">
                                <div class="w-9 h-9 bg-gradient-to-br from-brand-500 to-accent-500 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/25 group-hover:shadow-brand-500/40 transition-shadow">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <span class="font-bold text-xl text-slate-900">Formet</span>
                        </a>

                        <!-- Desktop Nav -->
                        @auth
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} rounded-lg transition-all">
                                Dashboard
                            </a>
                            <a href="{{ route('forms.index') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('forms.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} rounded-lg transition-all">
                                Forms
                            </a>
                        </div>
                        @endauth
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-full">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-brand-400 to-accent-400 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-slate-700 px-3 py-1.5 hover:bg-slate-100 rounded-lg transition-all">
                                Logout
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 px-4 py-2 hover:bg-slate-100 rounded-lg transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-gradient-to-r from-brand-500 to-brand-600 text-white px-5 py-2.5 rounded-xl hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all">
                            Get Started Free
                        </a>
                        @endauth

                        <!-- Mobile menu button -->
                        @auth
                        <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100">
                            <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            @auth
            <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-1 translate-y-0" class="md:hidden border-t border-slate-200/50 bg-white/95 backdrop-blur-xl">
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-brand-600 bg-brand-50' : 'text-slate-600' }} rounded-lg">Dashboard</a>
                    <a href="{{ route('forms.index') }}" class="block px-4 py-2.5 text-sm font-medium {{ request()->routeIs('forms.*') ? 'text-brand-600 bg-brand-50' : 'text-slate-600' }} rounded-lg">Forms</a>
                </div>
            </div>
            @endauth
        </nav>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            @if (session('status'))
            <div class="mb-6 animate-slideUp">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            </div>
            @endif

            <div class="animate-slideUp">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200/50 bg-white/50 mt-auto">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <div class="w-5 h-5 bg-gradient-to-br from-brand-500 to-accent-500 rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span>Formet - Form Backend as a Service</span>
                    </div>
                    <div class="text-sm text-slate-400">
                        Built with Laravel & Tailwind CSS
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
