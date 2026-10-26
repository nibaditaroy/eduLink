<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 selection:bg-indigo-500 selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Empower Your Tech Career') - eduLink Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind & FontAwesome CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased bg-slate-50">

    <!-- Global Top Announcement / Currency Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white text-xs py-2 px-4 text-center font-medium flex items-center justify-center gap-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
            <i class="fa-solid fa-bangladeshi-taka-sign mr-1"></i> BDT (৳ Tk)
        </span>
        <span>Bangladesh's Premier Hands-on Tech Academy &bull; Instant bKash, Nagad & Rocket Enrollment!</span>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-indigo-600 transition">edu<span class="text-indigo-600">Link</span></span>
                            <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-400 -mt-1">Learning Lab</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-slate-600">
                        <a href="{{ route('courses.index') }}" class="px-3 py-2 rounded-xl hover:text-indigo-600 hover:bg-indigo-50/60 transition {{ request()->routeIs('courses.*') ? 'text-indigo-600 bg-indigo-50/80' : '' }}">
                            <i class="fa-solid fa-compass mr-1.5 text-xs text-indigo-500"></i> Browse Courses
                        </a>
                        @auth
                            <a href="{{ route('student.dashboard') }}" class="px-3 py-2 rounded-xl hover:text-indigo-600 hover:bg-indigo-50/60 transition {{ request()->routeIs('student.dashboard') ? 'text-indigo-600 bg-indigo-50/80' : '' }}">
                                <i class="fa-solid fa-book-bookmark mr-1.5 text-xs text-indigo-500"></i> My Learning
                            </a>
                        @endauth
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl hover:text-purple-600 hover:bg-purple-50/60 transition text-purple-700 font-bold">
                                <i class="fa-solid fa-shield-halved mr-1.5 text-xs"></i> Admin Dashboard
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Right Side Actions & Auth -->
                <div class="flex items-center gap-3">
                    <!-- Course Search Quick Bar -->
                    <form action="{{ route('courses.index') }}" method="GET" class="hidden sm:block relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses, skills..." 
                            class="w-48 lg:w-64 pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-100/90 border border-slate-200 focus:bg-white focus:w-72 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </form>

                    @guest
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-slate-700 hover:text-indigo-600 hover:bg-slate-100 rounded-xl transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/25 transition">
                                Get Started
                            </a>
                            <!-- 1-Click Demo Testing Button -->
                            <a href="{{ route('quick-demo-login', 'student') }}" title="1-Click Student Demo Login" class="p-2 text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition text-xs font-bold flex items-center gap-1">
                                <i class="fa-solid fa-bolt"></i> Demo
                            </a>
                        </div>
                    @else
                        <!-- User Account Dropdown -->
                        <div class="relative" id="user-menu-wrapper">
                            <button type="button" id="user-menu-btn" class="flex items-center gap-2.5 p-1.5 rounded-2xl hover:bg-slate-100 transition border border-transparent hover:border-slate-200">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-cyan-500 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <div class="text-xs font-bold text-slate-900 leading-tight flex items-center gap-1">
                                        {{ Str::limit(Auth::user()->name, 14) }}
                                        @if(Auth::user()->isAdmin())
                                            <span class="text-[9px] bg-purple-100 text-purple-700 font-extrabold px-1 rounded">ADMIN</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 leading-tight">Student Hub</div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden sm:block"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-menu-content" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-book-bookmark text-indigo-500 w-4"></i> My Enrolled Courses
                                </a>
                                <a href="{{ route('courses.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-compass text-slate-400 w-4"></i> Explore Courses
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-purple-700 hover:bg-purple-50 transition">
                                        <i class="fa-solid fa-shield-halved text-purple-600 w-4"></i> Admin Portal
                                    </a>
                                @endif
                                <div class="border-t border-slate-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash Notification Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-indigo-500 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-indigo-500 hover:text-indigo-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold shadow-sm">
                <div class="flex items-center gap-2 mb-1.5 font-bold text-rose-900">
                    <i class="fa-solid fa-circle-xmark"></i> Please correct the following issues:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] ml-4 text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Platform Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">edu<span class="text-indigo-400">Link</span> LMS</span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                        High-engagement tech learning platform with interactive code execution sandboxes, real-world project assignments, instant assessments, and verifiable graduation certificates.
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-[11px] text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>All prices listed in Bangladeshi Taka (৳ BDT / Tk) &bull; Verified SSL Security</span>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Explore Skills</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('courses.index', ['category' => 'Software Engineering']) }}" class="hover:text-white transition">Software Engineering</a></li>
                        <li><a href="{{ route('courses.index', ['category' => 'Computer Science']) }}" class="hover:text-white transition">Computer Science</a></li>
                        <li><a href="{{ route('courses.index', ['pricing' => 'free']) }}" class="hover:text-white transition">Free Foundations Courses</a></li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-white transition">All Certifications</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-3">Accepted Payments</h4>
                    <div class="flex flex-wrap gap-2 text-slate-300 mb-3">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 font-bold text-[10px] text-pink-400 flex items-center gap-1">
                            <i class="fa-solid fa-wallet"></i> bKash
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 font-bold text-[10px] text-orange-400 flex items-center gap-1">
                            <i class="fa-solid fa-mobile-screen"></i> Nagad
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 font-bold text-[10px] text-purple-400 flex items-center gap-1">
                            <i class="fa-solid fa-rocket"></i> Rocket
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 font-bold text-[10px] text-cyan-400 flex items-center gap-1">
                            <i class="fa-solid fa-credit-card"></i> Cards (Tk)
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-500">Fast digital issuance upon payment confirmation.</p>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} eduLink Platform. Developed for Next-Generation Engineers.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="hover:text-slate-400 transition">Privacy Policy</a>
                    <a href="{{ route('home') }}" class="hover:text-slate-400 transition">Terms of Service</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition">
                        <i class="fa-solid fa-lock text-[10px] mr-1"></i> Admin Login
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- User dropdown click script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('user-menu-btn');
            const menu = document.getElementById('user-menu-content');
            if (btn && menu) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });
                document.addEventListener('click', (e) => {
                    if (!btn.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
