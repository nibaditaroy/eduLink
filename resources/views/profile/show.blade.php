@extends('layouts.app')

@section('title', 'My Profile & Account Settings - eduLink')

@section('content')
<div class="bg-slate-100/60 border-b border-slate-200 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
                    <span>&bull;</span>
                    <a href="{{ route('student.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                    <span>&bull;</span>
                    <span class="text-slate-800 font-semibold">Profile Settings</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Student Profile & Settings</h1>
            </div>
            <a href="{{ route('student.dashboard') }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 hover:border-indigo-300 transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-book-bookmark text-indigo-500"></i> My Classroom Hub
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Profile Summary & Stats Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 text-center">
                <!-- Avatar Display -->
                <div class="relative w-24 h-24 mx-auto mb-4">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-3xl object-cover border-4 border-indigo-50 shadow-md">
                    @else
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 text-white font-extrabold flex items-center justify-center text-3xl shadow-lg shadow-indigo-500/25 border-4 border-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute bottom-0 right-0 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white shadow-xs" title="Account Active"></span>
                </div>

                <h2 class="text-lg font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h2>
                <p class="text-xs text-slate-400 font-medium mt-0.5 truncate">{{ $user->email }}</p>

                <div class="mt-3 flex items-center justify-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wider">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <i class="fa-solid fa-circle-check mr-1 text-[8px]"></i> Verified
                    </span>
                </div>

                <!-- Learning Statistics Grid -->
                <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-100 text-left">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Enrolled</span>
                        <span class="text-lg font-extrabold text-slate-900">{{ $enrolledCount }} Tracks</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Completed</span>
                        <span class="text-lg font-extrabold text-emerald-600">{{ $completedLessonsCount }} Lessons</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Quizzes</span>
                        <span class="text-lg font-extrabold text-indigo-600">{{ $quizAttemptsCount }} Taken</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-slate-400">Avg. Score</span>
                        <span class="text-lg font-extrabold text-slate-900">{{ $avgScore }}%</span>
                    </div>
                </div>

                <!-- Account Metadata Details -->
                <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-500 space-y-1.5 text-left">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Member Since:</span>
                        <span class="font-semibold text-slate-700">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Phone:</span>
                        <span class="font-semibold text-slate-700">{{ $user->phone ?? 'Not specified' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Profile Information & Security Tabs -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Section 1: Update Profile Details & Avatar -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Personal Information</h3>
                        <p class="text-xs text-slate-400">Update your public profile display name, contact number, and avatar photo.</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Upload & Live Preview -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Profile Avatar Photo</label>
                        <div class="flex flex-col sm:flex-row items-center gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <!-- Live Preview Box -->
                            <div class="relative w-20 h-20 shrink-0">
                                <img id="avatar-preview" 
                                    src="{{ $user->avatar_url ?? 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' rx=\'24\' fill=\'%236366f1\'/><text x=\'50%25\' y=\'55%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'white\' font-size=\'32\' font-weight=\'bold\'>' . strtoupper(substr($user->name, 0, 1)) . '</text></svg>' }}" 
                                    alt="Avatar Preview" 
                                    class="w-20 h-20 rounded-2xl object-cover border-2 border-white shadow-sm">
                            </div>

                            <!-- Upload Input Controls -->
                            <div class="space-y-2 text-center sm:text-left flex-grow">
                                <div class="flex flex-wrap items-center gap-3">
                                    <label class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 hover:text-indigo-600 cursor-pointer transition shadow-xs flex items-center gap-2">
                                        <i class="fa-solid fa-cloud-arrow-up text-indigo-500"></i>
                                        <span>Choose New Photo</span>
                                        <input type="file" name="avatar" id="avatar-file-input" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                                    </label>

                                    @if($user->avatar)
                                        <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 cursor-pointer font-semibold">
                                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <span>Remove custom avatar</span>
                                        </label>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-400">Supported formats: PNG, JPG, WebP, SVG (Max 3MB). Recommended size: 256x256.</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                            <div class="relative">
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none font-medium">
                                <i class="fa-solid fa-user absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                            @error('name')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Phone / Mobile (bKash / Nagad)</label>
                            <div class="relative">
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 017XXXXXXXX"
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none font-medium">
                                <i class="fa-solid fa-phone absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                            @error('phone')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Address (Read-Only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full pl-9 pr-24 py-2.5 text-xs rounded-xl bg-slate-100 border border-slate-200 text-slate-500 cursor-not-allowed font-medium">
                            <i class="fa-solid fa-envelope absolute left-3 top-3 text-slate-400 text-xs"></i>
                            <span class="absolute right-3 top-2.5 text-[10px] font-bold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md">Primary Login</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">To change your registered email, contact platform administrator.</p>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section 2: Change Security Password -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Security & Password</h3>
                        <p class="text-xs text-slate-400">Update your account login password to keep your learning progress secure.</p>
                    </div>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required placeholder="••••••••"
                                class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                            <i class="fa-solid fa-lock absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required placeholder="Minimum 8 characters"
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                                <i class="fa-solid fa-key absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat new password"
                                    class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                                <i class="fa-solid fa-circle-check absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition shadow-xs flex items-center gap-2">
                            <i class="fa-solid fa-key"></i>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
