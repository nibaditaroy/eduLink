@extends('admin.layouts.admin')

@section('title', 'Add New User')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition">Users</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-800 font-semibold">Create User</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create User Account</h1>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
            <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Users
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Alex Johnson"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('name') border-rose-500 @enderror">
                @error('name')
                <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email & Phone Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="alex@example.com"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('email') border-rose-500 @enderror">
                    @error('email')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Phone Number (bKash/Nagad/SMS)</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+880 1700 000000"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('phone') border-rose-500 @enderror">
                    @error('phone')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role & Status Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">System Role <span class="text-rose-500">*</span></label>
                    <select id="role" name="role" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student (LMS Learner)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Full Portal Management)</option>
                    </select>
                    @error('role')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Account Access Status <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>🟢 Active (Normal Full Access)</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>🟡 Suspended (Temporarily Paused)</option>
                        <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>🔴 Blocked (Banned from Platform)</option>
                    </select>
                    @error('status')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Reason -->
            <div>
                <label for="status_reason" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Status Reason / Administrative Note</label>
                <textarea id="status_reason" name="status_reason" rows="2" placeholder="Optional note if suspended or blocked (visible to staff)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('status_reason') }}</textarea>
                <p class="mt-1 text-[11px] text-gray-400">Explain why the account was suspended or blocked if applicable.</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Password <span class="text-rose-500">*</span></label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="Minimum 6 characters"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('password') border-rose-500 @enderror">
                @error('password')
                <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-check me-1.5"></i> Save User
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
