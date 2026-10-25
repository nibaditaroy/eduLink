<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - EDULINK LMS</title>
    
    <!-- Flowbite & Tailwind via CDN for seamless immediate rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen antialiased flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <span class="sr-only">Open sidebar</span>
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24 items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <span class="self-center text-xl font-bold whitespace-nowrap text-gray-900 tracking-tight">EDULINK</span>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-indigo-600">Admin CMS</span>
                        </div>
                    </a>
                </div>
                
                <div class="flex items-center gap-3">
                    <span class="hidden md:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 me-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        University Demo Mode
                    </span>

                    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-plus me-1.5"></i> New Course
                    </a>

                    <!-- User menu -->
                    <div class="flex items-center ms-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm border border-indigo-200">
                                {{ substr(Auth::user()->name ?? 'Admin', 0, 1) }}
                            </div>
                            <span class="hidden md:inline text-sm font-medium text-gray-700">
                                {{ Auth::user()->name ?? 'Administrator' }}
                            </span>
                            @if(Auth::check())
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Logout" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('admin.quick-login') }}" class="text-xs text-indigo-600 hover:underline">Quick Login</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <ul class="space-y-1.5 font-medium">
                <li>
                    <span class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Main Menu</span>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i class="fa-solid fa-chart-pie w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.index') }}" class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.courses.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i class="fa-solid fa-book-open w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.courses.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Courses & Hierarchy</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.create') }}" class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group transition">
                        <i class="fa-solid fa-folder-plus w-6 text-center text-gray-500 group-hover:text-indigo-600"></i>
                        <span class="ms-3">Create Course</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Content Engine</span>
                </li>
                <li>
                    <div class="px-3 py-2 text-xs text-gray-500 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="font-semibold text-gray-700 mb-1"><i class="fa-solid fa-sitemap me-1 text-indigo-500"></i> Modular Hierarchy:</div>
                        <div class="text-[11px] leading-relaxed text-gray-600">
                            Course &rarr; Chapter &rarr; Lesson &rarr; Quizzes & Assignments
                        </div>
                    </div>
                </li>
            </ul>

            <!-- Bottom Box -->
            <div class="p-3 bg-gradient-to-br from-indigo-50 to-violet-50 rounded-2xl border border-indigo-100">
                <div class="flex items-center gap-2 mb-2">
                    <span class="p-1.5 bg-indigo-600 text-white rounded-lg text-xs">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <span class="text-xs font-bold text-gray-900">Admin Quick Access</span>
                </div>
                <p class="text-[11px] text-gray-600 mb-2">Logged in as Administrator with full CRUD permissions.</p>
                <div class="text-[10px] text-gray-400">EDULINK • University LMS</div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="p-4 sm:ml-64 pt-20 flex-1">
        <div class="max-w-7xl mx-auto">
            
            <!-- Session Alerts -->
            @if(session('success'))
            <div id="alert-success" class="flex items-center p-4 mb-4 text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check text-lg me-3"></i>
                <div class="ms-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-success" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div id="alert-error" class="flex items-center p-4 mb-4 text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation text-lg me-3"></i>
                <div class="ms-3 text-sm font-medium">
                    {{ session('error') }}
                </div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-rose-50 text-rose-500 rounded-lg focus:ring-2 focus:ring-rose-400 p-1.5 hover:bg-rose-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-error" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div id="alert-validation" class="p-4 mb-4 text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
                <div class="flex items-center mb-1">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <span class="font-semibold text-sm">Please check the following errors:</span>
                </div>
                <ul class="mt-1 list-disc list-inside text-xs">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            hljs.highlightAll();
        });
    </script>
    @stack('scripts')
</body>
</html>
