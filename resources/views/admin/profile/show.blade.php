@extends('admin.layouts.admin')

@section('title', 'Admin Account & Profile Settings - eduLink')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumbs & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-800 font-semibold">Administrator Profile</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Admin Profile & System Credentials</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Return to Dashboard
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Profile Card, Right Edit Forms -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- Left: Admin Identity Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-7 shadow-xs text-center">
                
                <!-- Avatar Preview -->
                <div class="relative w-24 h-24 mx-auto mb-4">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-3xl object-cover border-4 border-purple-50 shadow-md">
                    @else
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-purple-700 via-indigo-600 to-indigo-800 text-white font-extrabold flex items-center justify-center text-3xl shadow-lg shadow-purple-600/25 border-4 border-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute bottom-0 right-0 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white shadow-xs" title="Root Administrator"></span>
                </div>

                <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 font-medium mt-0.5 truncate">{{ $user->email }}</p>

                <div class="mt-3 flex items-center justify-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase tracking-wider">
                        <i class="fa-solid fa-shield-halved mr-1 text-[9px]"></i> Super Admin
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                    </span>
                </div>

                <!-- Admin Metrics Snapshot -->
                <div class="grid grid-cols-3 gap-2 mt-6 pt-6 border-t border-gray-100 text-center">
                    <div class="p-2.5 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="block text-[9px] uppercase font-bold text-gray-400">Courses</span>
                        <span class="text-base font-extrabold text-gray-900">{{ $stats['total_courses'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="block text-[9px] uppercase font-bold text-gray-400">Students</span>
                        <span class="text-base font-extrabold text-indigo-600">{{ $stats['total_students'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="block text-[9px] uppercase font-bold text-gray-400">Enrollments</span>
                        <span class="text-base font-extrabold text-purple-600">{{ $stats['total_enrollments'] }}</span>
                    </div>
                </div>

                <!-- Account Metadata Details -->
                <div class="mt-6 pt-4 border-t border-gray-100 text-[11px] text-gray-500 space-y-2 text-left">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Security Clearance:</span>
                        <span class="font-bold text-purple-700">Full System Root</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Admin Since:</span>
                        <span class="font-semibold text-gray-700">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Emergency Phone:</span>
                        <span class="font-semibold text-gray-700">{{ $user->phone ?? 'Not set' }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right: Edit Details & Security -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Section 1: Administrator Information Form -->
            <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Administrator Details</h3>
                        <p class="text-xs text-gray-400">Update system administrator display name, emergency phone, and avatar.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Upload & Live Preview -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Admin Profile Avatar</label>
                        <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-2xl bg-gray-50 border border-gray-200">
                            <!-- Live Preview Box -->
                            <div class="relative w-20 h-20 shrink-0">
                                <img id="admin-avatar-preview" 
                                    src="{{ $user->avatar_url ?? 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' rx=\'24\' fill=\'%237c3aed\'/><text x=\'50%25\' y=\'55%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'white\' font-size=\'32\' font-weight=\'bold\'>' . strtoupper(substr($user->name, 0, 1)) . '</text></svg>' }}" 
                                    alt="Avatar Preview" 
                                    class="w-20 h-20 rounded-2xl object-cover border-2 border-white shadow-xs">
                            </div>

                            <!-- Upload Controls -->
                            <div class="space-y-2 text-center sm:text-left flex-grow">
                                <div class="flex flex-wrap items-center gap-3">
                                    <label class="px-3.5 py-2 rounded-xl bg-white hover:bg-gray-100 border border-gray-300 text-xs font-bold text-gray-700 hover:text-purple-700 cursor-pointer transition shadow-2xs flex items-center gap-2">
                                        <i class="fa-solid fa-cloud-arrow-up text-purple-600"></i>
                                        <span>Select New Photo</span>
                                        <input type="file" name="avatar" id="admin-avatar-input" accept="image/*" class="hidden" onchange="previewAdminAvatar(event)">
                                    </label>

                                    @if($user->avatar)
                                        <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 cursor-pointer font-semibold">
                                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                            <span>Remove custom avatar</span>
                                        </label>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-400">Supported formats: PNG, JPG, WebP, SVG (Max 3MB). High resolution square recommended.</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="admin_name" class="block text-xs font-bold text-gray-700 mb-1">Administrator Name</label>
                            <div class="relative">
                                <input type="text" id="admin_name" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none font-medium">
                                <i class="fa-solid fa-user-shield absolute left-3 top-3 text-gray-400 text-xs"></i>
                            </div>
                            @error('name')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_phone" class="block text-xs font-bold text-gray-700 mb-1">Emergency Contact Phone</label>
                            <div class="relative">
                                <input type="text" id="admin_phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 017XXXXXXXX"
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none font-medium">
                                <i class="fa-solid fa-phone absolute left-3 top-3 text-gray-400 text-xs"></i>
                            </div>
                            @error('phone')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Address (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Administrative Email</label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full pl-9 pr-28 py-2.5 text-xs rounded-xl bg-gray-100 border border-gray-300 text-gray-500 cursor-not-allowed font-medium">
                            <i class="fa-solid fa-envelope absolute left-3 top-3 text-gray-400 text-xs"></i>
                            <span class="absolute right-3 top-2.5 text-[10px] font-bold bg-purple-100 text-purple-700 px-2 py-0.5 rounded-md">Master Admin</span>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold shadow-sm transition flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Update Administrator Info</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section 2: Security & Password -->
            <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Change Admin Password</h3>
                        <p class="text-xs text-gray-400">Maintain high platform security by updating your master administration password.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="admin_current_password" class="block text-xs font-bold text-gray-700 mb-1">Current Master Password</label>
                        <div class="relative">
                            <input type="password" id="admin_current_password" name="current_password" required placeholder="••••••••"
                                class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none">
                            <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-400 text-xs"></i>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="admin_password" class="block text-xs font-bold text-gray-700 mb-1">New Master Password</label>
                            <div class="relative">
                                <input type="password" id="admin_password" name="password" required placeholder="Minimum 8 characters"
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none">
                                <i class="fa-solid fa-key absolute left-3 top-3 text-gray-400 text-xs"></i>
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_password_confirmation" class="block text-xs font-bold text-gray-700 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="admin_password_confirmation" name="password_confirmation" required placeholder="Repeat new password"
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-gray-50 border border-gray-300 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none">
                                <i class="fa-solid fa-circle-check absolute left-3 top-3 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold transition shadow-xs flex items-center gap-2">
                            <i class="fa-solid fa-shield-check"></i>
                            <span>Update Admin Password</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>

<script>
    function previewAdminAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('admin-avatar-preview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
