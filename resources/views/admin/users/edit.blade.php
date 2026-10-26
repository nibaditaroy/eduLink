@extends('admin.layouts.admin')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition">Users</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-800 font-semibold">Edit User</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit: {{ $user->name }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.show', $user->id) }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                <i class="fa-solid fa-chart-line me-1.5"></i> View Progress
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('name') border-rose-500 @enderror">
                @error('name')
                <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email & Phone Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('email') border-rose-500 @enderror">
                    @error('email')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+880 1700 000000"
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
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student (LMS Learner)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Full Portal Management)</option>
                    </select>
                    @error('role')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Account Access Status <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>🟢 Active (Normal Full Access)</option>
                        <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>🟡 Suspended (Temporarily Paused)</option>
                        <option value="blocked" {{ old('status', $user->status) === 'blocked' ? 'selected' : '' }}>🔴 Blocked (Banned from Platform)</option>
                    </select>
                    @error('status')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Reason -->
            <div>
                <label for="status_reason" class="block mb-2 text-xs font-bold uppercase tracking-wider text-gray-700">Status Reason / Administrative Note</label>
                <textarea id="status_reason" name="status_reason" rows="2" placeholder="e.g. Under review, payment verification pending, or academic policy violation."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('status_reason', $user->status_reason) }}</textarea>
                <p class="mt-1 text-[11px] text-gray-400">Recorded reason for suspension or block.</p>
            </div>

            <!-- New Password (Optional) -->
            <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-200">
                <label for="password" class="block mb-1 text-xs font-bold uppercase tracking-wider text-gray-700">Reset Password (Optional)</label>
                <input type="password" id="password" name="password" minlength="6" placeholder="Leave empty to keep existing password"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 @error('password') border-rose-500 @enderror">
                <p class="mt-1 text-[11px] text-gray-500">Only fill this if you want to set a new password for this user.</p>
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
                    <i class="fa-solid fa-floppy-disk me-1.5"></i> Update User
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
