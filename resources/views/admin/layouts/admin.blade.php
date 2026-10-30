<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') - EDULINK LMS</title>

    <!-- Flowbite & Tailwind via CDN for seamless immediate rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Select UI Component */
        .custom-select-wrapper {
            position: relative;
            user-select: none;
        }

        .custom-select-trigger:hover {
            border-color: #818cf8;
        }

        .custom-select-menu {
            position: fixed !important;
            z-index: 999999 !important;
            box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.16), 0 6px 12px -2px rgba(15, 23, 42, 0.08);
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }

        .custom-select-menu::-webkit-scrollbar {
            width: 5px;
        }

        .custom-select-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-select-menu::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen antialiased flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <span class="sr-only">Open sidebar</span>
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24 items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <span
                                class="self-center text-xl font-bold whitespace-nowrap text-gray-900 tracking-tight">BAUST
                                <span class="text-indigo-600">LMS</span></span>
                            <span class="block text-xs font-semibold uppercase tracking-wider text-indigo-600">eduLink
                                Admin</span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-3">

                    <!-- Live View Website Link -->
                    <a href="{{ route('home') }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 rounded-xl shadow-xs transition group"
                        title="Open Live Public Website">
                        <i class="fa-solid fa-globe text-indigo-600 group-hover:scale-110 transition"></i>
                        <span class="hidden sm:inline">Live View Website</span>
                        <span class="sm:hidden">View Site</span>
                        <i
                            class="fa-solid fa-arrow-up-right-from-square text-[9px] text-gray-400 group-hover:text-indigo-600"></i>
                    </a>

                    <!-- Quick Create Dropdown -->
                    <div class="relative">
                        <button type="button" id="quick-create-btn"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition group focus:outline-none">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Create</span>
                            <i class="fa-solid fa-chevron-down text-[9px] ms-0.5 text-indigo-200 group-hover:text-white transition-transform duration-200"
                                id="quick-create-chevron"></i>
                        </button>
                        <div id="quick-create-dropdown"
                            class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 transform origin-top-right transition-all">
                            <div class="px-3.5 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                Quick Actions</div>
                            <a href="{{ route('admin.courses.create') }}"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-book-bookmark text-indigo-500 w-4 text-center"></i>
                                <span class="font-medium">New Course</span>
                            </a>
                            <a href="{{ route('admin.users.create') }}"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                <i class="fa-solid fa-user-plus text-emerald-500 w-4 text-center"></i>
                                <span class="font-medium">Add New User</span>
                            </a>
                            <a href="{{ route('admin.enrollments.index') }}"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                <i class="fa-solid fa-user-graduate text-purple-500 w-4 text-center"></i>
                                <span class="font-medium">Enroll Student</span>
                            </a>
                        </div>
                    </div>

                    <!-- User Account Dropdown -->
                    <div class="relative ms-1">
                        <button type="button" id="user-menu-btn"
                            class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition focus:outline-none group">
                            @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover shadow-xs border border-gray-200">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                            <div class="hidden md:block text-left">
                                <div class="text-xs font-bold text-gray-900 leading-tight">
                                    {{ Auth::user()->name ?? 'Administrator' }}
                                </div>
                                <div class="text-[10px] text-gray-400 capitalize">{{ Auth::user()->role ?? 'Admin' }}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ms-1 transition-transform duration-200 group-hover:text-gray-600"
                                id="user-menu-chevron"></i>
                        </button>

                        <!-- Dropdown Menu Card -->
                        <div id="user-menu-dropdown"
                            class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 transform origin-top-right transition-all">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="text-xs font-bold text-gray-900">{{ Auth::user()->name ?? 'Administrator' }}
                                </div>
                                <div class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email ??
                                    'admin@edulink.test' }}</div>
                                <div
                                    class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>{{ ucfirst(Auth::user()->role ?? 'Admin') }} • Active</span>
                                </div>
                            </div>

                            <div class="py-1 text-xs text-gray-700">
                                <a href="{{ route('admin.profile') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-user-gear text-indigo-500 w-4 text-center"></i>
                                    <span>My Profile & Credentials</span>
                                </a>
                                <a href="{{ route('admin.courses.index') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-book-open text-violet-500 w-4 text-center"></i>
                                    <span>Course Catalog</span>
                                </a>
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-credit-card text-emerald-500 w-4 text-center"></i>
                                    <span>Payments & Revenue (৳)</span>
                                </a>
                                <a href="{{ route('admin.assignment-submissions.index') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-file-signature text-amber-500 w-4 text-center"></i>
                                    <span>Assignment Grading Hub</span>
                                </a>
                            </div>

                            <div class="border-t border-gray-100 pt-1">
                                @if(Auth::check())
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                            <i
                                                class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4 text-center"></i>
                                            <span>Sign Out</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <ul class="space-y-1.5 font-medium">
                <li>
                    <span class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Main Menu</span>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i
                            class="fa-solid fa-chart-pie w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.index') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.courses.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i
                            class="fa-solid fa-book-open w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.courses.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Courses & Hierarchy</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i
                            class="fa-solid fa-users w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">User Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.enrollments.index') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.enrollments.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i
                            class="fa-solid fa-user-graduate w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.enrollments.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Enrollments & Popularity</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i
                            class="fa-solid fa-credit-card w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.transactions.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Payments & Revenue</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.assignment-submissions.index') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group {{ request()->routeIs('admin.assignment-submissions.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }} transition">
                        <i
                            class="fa-solid fa-file-signature w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('admin.assignment-submissions.*') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ms-3">Assignment Grading</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.create') }}"
                        class="flex items-center p-2.5 text-gray-900 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 group transition">
                        <i
                            class="fa-solid fa-folder-plus w-6 text-center text-gray-500 group-hover:text-indigo-600"></i>
                        <span class="ms-3">Create Course</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Content
                        Engine</span>
                </li>
                <li>
                    <div class="px-3 py-2 text-xs text-gray-500 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="font-semibold text-gray-700 mb-1"><i
                                class="fa-solid fa-sitemap me-1 text-indigo-500"></i> Modular Hierarchy:</div>
                        <div class="text-[11px] leading-relaxed text-gray-600">
                            Course &rarr; Chapter &rarr; Lesson & Quizzes & Assignments
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
                <p class="text-[11px] text-gray-600 mb-2.5">Logged in as Administrator with full CRUD permissions.</p>
                <a href="{{ route('home') }}" target="_blank"
                    class="w-full py-2 px-3 rounded-xl bg-white hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 text-xs font-bold shadow-xs transition flex items-center justify-center gap-2 group mb-2">
                    <i class="fa-solid fa-globe group-hover:rotate-12 transition"></i>
                    <span>Live View Website</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-70"></i>
                </a>
                <div class="text-[10px] text-gray-400 text-center">BAUST &bull; eduLink University LMS</div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="p-4 sm:ml-64 pt-20 flex-1">
        <div class="max-w-7xl mx-auto">

            <!-- Session Alerts -->
            @if(session('success'))
                <div id="alert-success"
                    class="flex items-center p-4 mb-4 text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-sm"
                    role="alert">
                    <i class="fa-solid fa-circle-check text-lg me-3"></i>
                    <div class="ms-3 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-200 inline-flex items-center justify-center h-8 w-8"
                        data-dismiss-target="#alert-success" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert-error"
                    class="flex items-center p-4 mb-4 text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm"
                    role="alert">
                    <i class="fa-solid fa-triangle-exclamation text-lg me-3"></i>
                    <div class="ms-3 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-rose-50 text-rose-500 rounded-lg focus:ring-2 focus:ring-rose-400 p-1.5 hover:bg-rose-200 inline-flex items-center justify-center h-8 w-8"
                        data-dismiss-target="#alert-error" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div id="alert-validation"
                    class="p-4 mb-4 text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-sm" role="alert">
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

    <!-- Global Delete Confirmation Modal -->
    <div id="global-delete-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 ease-out"
        role="dialog" aria-modal="true" aria-labelledby="global-delete-modal-title">
        <div id="global-delete-modal-card"
            class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform scale-95 transition-transform duration-200 ease-out">
            <div class="flex items-center gap-4 mb-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 id="global-delete-modal-title" class="text-base font-bold text-gray-900">Confirm Deletion</h3>
                    <p class="text-xs text-gray-400">This action is permanent and cannot be undone.</p>
                </div>
            </div>

            <div class="py-2">
                <p id="global-delete-modal-message"
                    class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    Are you sure you want to permanently delete this item?
                </p>
            </div>

            <div class="mt-5 flex items-center justify-end gap-3">
                <button type="button" id="global-delete-modal-cancel"
                    class="px-4 py-2.5 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl transition shadow-sm">
                    Cancel
                </button>
                <button type="button" id="global-delete-modal-confirm"
                    class="inline-flex items-center px-4 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-md shadow-rose-600/20">
                    <i class="fa-solid fa-trash-can me-1.5"></i> Yes, Delete Permanently
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            hljs.highlightAll();

            // Setup Global Delete Modal
            const deleteModal = document.getElementById('global-delete-modal');
            const deleteModalCard = document.getElementById('global-delete-modal-card');
            const deleteModalTitle = document.getElementById('global-delete-modal-title');
            const deleteModalMessage = document.getElementById('global-delete-modal-message');
            const deleteModalCancel = document.getElementById('global-delete-modal-cancel');
            const deleteModalConfirm = document.getElementById('global-delete-modal-confirm');
            let pendingDeleteForm = null;

            window.openDeleteModal = function (form, title = 'Confirm Deletion', message = null) {
                pendingDeleteForm = form;
                deleteModalTitle.textContent = title;
                if (message) {
                    deleteModalMessage.innerHTML = message;
                } else {
                    deleteModalMessage.textContent = 'Are you sure you want to permanently delete this item?';
                }

                deleteModal.classList.remove('opacity-0', 'pointer-events-none');
                deleteModalCard.classList.remove('scale-95');
                deleteModalCard.classList.add('scale-100');
                document.body.style.overflow = 'hidden';
            };

            function closeDeleteModal() {
                deleteModal.classList.add('opacity-0', 'pointer-events-none');
                deleteModalCard.classList.remove('scale-100');
                deleteModalCard.classList.add('scale-95');
                document.body.style.overflow = '';
                pendingDeleteForm = null;
            }

            deleteModalCancel.addEventListener('click', closeDeleteModal);
            deleteModal.addEventListener('click', (e) => {
                if (e.target === deleteModal) closeDeleteModal();
            });

            deleteModalConfirm.addEventListener('click', () => {
                if (pendingDeleteForm) {
                    deleteModalConfirm.disabled = true;
                    deleteModalConfirm.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Deleting...';
                    pendingDeleteForm.submit();
                }
            });

            // Intercept forms with data-confirm-delete attribute or forms with native confirm in onsubmit
            document.querySelectorAll('form[data-confirm-delete]').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const msg = form.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this record?';
                    const title = form.getAttribute('data-confirm-title') || 'Confirm Deletion';
                    window.openDeleteModal(form, title, msg);
                });
            });

            // Navbar Dropdowns Handler
            function setupDropdown(triggerId, menuId, chevronId = null) {
                const trigger = document.getElementById(triggerId);
                const menu = document.getElementById(menuId);
                const chevron = chevronId ? document.getElementById(chevronId) : null;
                if (!trigger || !menu) return;

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isClosed = menu.classList.contains('hidden');
                    // Close other dropdowns first
                    document.querySelectorAll('#user-menu-dropdown, #quick-create-dropdown').forEach(d => {
                        if (d !== menu) d.classList.add('hidden');
                    });
                    document.querySelectorAll('#user-menu-chevron, #quick-create-chevron').forEach(c => {
                        if (c !== chevron) c.classList.remove('rotate-180');
                    });

                    if (isClosed) {
                        menu.classList.remove('hidden');
                        if (chevron) chevron.classList.add('rotate-180');
                    } else {
                        menu.classList.add('hidden');
                        if (chevron) chevron.classList.remove('rotate-180');
                    }
                });
            }

            setupDropdown('user-menu-btn', 'user-menu-dropdown', 'user-menu-chevron');
            setupDropdown('quick-create-btn', 'quick-create-dropdown', 'quick-create-chevron');

            // Custom Select Component Enhancer
            window.initCustomSelects = function (root = document) {
                root.querySelectorAll('select:not([data-no-custom]):not(.opacity-0):not([data-customized])').forEach(select => {
                    select.setAttribute('data-customized', 'true');
                    select.style.display = 'none';

                    const isBlock = select.classList.contains('w-full') || select.classList.contains('block') || (select.parentElement && select.parentElement.classList.contains('col-span-1'));
                    const isSmall = select.classList.contains('text-xs') || select.classList.contains('py-1');

                    const wrapper = document.createElement('div');
                    wrapper.className = 'custom-select-wrapper ' + (isBlock ? 'w-full' : 'inline-block') + ' relative';

                    const trigger = document.createElement('button');
                    trigger.type = 'button';
                    trigger.className = `custom-select-trigger w-full flex items-center justify-between gap-2 bg-white border border-gray-200 text-gray-800 rounded-xl shadow-xs transition focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer ${isSmall ? 'py-1.5 px-3 text-xs font-semibold' : 'py-2.5 px-3.5 text-xs sm:text-sm font-semibold'
                        }`;

                    const textSpan = document.createElement('span');
                    textSpan.className = 'truncate text-left';

                    const chevron = document.createElement('i');
                    chevron.className = 'fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200 shrink-0';

                    trigger.appendChild(textSpan);
                    trigger.appendChild(chevron);
                    wrapper.appendChild(trigger);

                    const menu = document.createElement('div');
                    menu.className = 'custom-select-menu hidden bg-white rounded-2xl shadow-2xl border border-gray-100 py-1.5 max-h-60 overflow-y-auto transform transition-all duration-150';

                    function updateDisplay() {
                        const current = select.options[select.selectedIndex];
                        textSpan.textContent = current ? current.textContent.trim() : 'Select...';

                        menu.innerHTML = '';
                        Array.from(select.options).forEach((opt, idx) => {
                            const optItem = document.createElement('div');
                            const isSelected = select.selectedIndex === idx;
                            optItem.className = `custom-select-option px-3 py-2 mx-1.5 rounded-xl text-xs flex items-center justify-between cursor-pointer transition select-none ${isSelected ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-700 hover:bg-slate-50 font-medium'
                                }`;

                            const span = document.createElement('span');
                            span.className = 'truncate';
                            span.textContent = opt.textContent.trim();
                            optItem.appendChild(span);

                            if (isSelected) {
                                const check = document.createElement('i');
                                check.className = 'fa-solid fa-check text-indigo-600 text-[10px] shrink-0 ms-2';
                                optItem.appendChild(check);
                            }

                            optItem.addEventListener('click', (e) => {
                                e.stopPropagation();
                                select.selectedIndex = idx;
                                updateDisplay();
                                closeMenu();

                                select.dispatchEvent(new Event('input', { bubbles: true }));
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                                if (typeof select.onchange === 'function') {
                                    select.onchange(new Event('change'));
                                }
                            });

                            menu.appendChild(optItem);
                        });
                    }

                    select._customSync = updateDisplay;
                    updateDisplay();

                    // Append floating menu to body to escape table overflow clipping
                    document.body.appendChild(menu);

                    select.parentNode.insertBefore(wrapper, select);
                    wrapper.appendChild(select);

                    function openMenu() {
                        document.querySelectorAll('.custom-select-menu').forEach(m => {
                            if (m !== menu) m.classList.add('hidden');
                        });
                        document.querySelectorAll('.custom-select-trigger i.fa-chevron-down').forEach(c => {
                            if (c !== chevron) c.classList.remove('rotate-180');
                        });

                        const rect = trigger.getBoundingClientRect();
                        menu.classList.remove('hidden');

                        const targetWidth = Math.max(rect.width, 160);
                        menu.style.width = targetWidth + 'px';

                        // Position horizontally
                        let targetLeft = rect.left;
                        if (targetLeft + targetWidth > window.innerWidth - 12) {
                            targetLeft = Math.max(12, window.innerWidth - targetWidth - 12);
                        }
                        menu.style.left = targetLeft + 'px';

                        // Position vertically (avoid clipping above or below)
                        const menuHeight = menu.offsetHeight || 180;
                        const spaceBelow = window.innerHeight - rect.bottom;
                        const spaceAbove = rect.top;

                        if (spaceBelow < menuHeight + 10 && spaceAbove > menuHeight + 10) {
                            menu.style.top = Math.max(10, rect.top - menuHeight - 6) + 'px';
                            menu.style.transformOrigin = 'bottom';
                        } else {
                            menu.style.top = Math.min(rect.bottom + 6, window.innerHeight - menuHeight - 10) + 'px';
                            menu.style.transformOrigin = 'top';
                        }

                        chevron.classList.add('rotate-180');
                    }

                    function closeMenu() {
                        menu.classList.add('hidden');
                        chevron.classList.remove('rotate-180');
                    }

                    trigger.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (menu.classList.contains('hidden')) {
                            openMenu();
                        } else {
                            closeMenu();
                        }
                    });

                    select.addEventListener('change', () => {
                        updateDisplay();
                    });
                });
            };

            // Initialize custom selects on page load
            window.initCustomSelects();

            // Global Click-away & Escape Listener for Custom Selects and Dropdowns
            document.addEventListener('click', (e) => {
                const dropdowns = [
                    { trigger: 'user-menu-btn', menu: 'user-menu-dropdown', chevron: 'user-menu-chevron' },
                    { trigger: 'quick-create-btn', menu: 'quick-create-dropdown', chevron: 'quick-create-chevron' }
                ];
                dropdowns.forEach(item => {
                    const trig = document.getElementById(item.trigger);
                    const men = document.getElementById(item.menu);
                    const chev = document.getElementById(item.chevron);
                    if (men && !men.contains(e.target) && trig && !trig.contains(e.target)) {
                        men.classList.add('hidden');
                        if (chev) chev.classList.remove('rotate-180');
                    }
                });

                document.querySelectorAll('.custom-select-menu').forEach(m => m.classList.add('hidden'));
                document.querySelectorAll('.custom-select-trigger i.fa-chevron-down').forEach(c => c.classList.remove('rotate-180'));
            });

            // Close custom select menus on scroll to maintain visual integrity
            window.addEventListener('scroll', () => {
                document.querySelectorAll('.custom-select-menu').forEach(m => m.classList.add('hidden'));
                document.querySelectorAll('.custom-select-trigger i.fa-chevron-down').forEach(c => c.classList.remove('rotate-180'));
            }, true);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('#user-menu-dropdown, #quick-create-dropdown, .custom-select-menu').forEach(d => d.classList.add('hidden'));
                    document.querySelectorAll('#user-menu-chevron, #quick-create-chevron, .custom-select-trigger i.fa-chevron-down').forEach(c => c.classList.remove('rotate-180'));
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>