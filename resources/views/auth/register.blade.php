@extends('layouts.app')

@section('title', 'Create Student Account - eduLink')

@section('content')
<div class="py-12 lg:py-20 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200/90 shadow-xl p-8 sm:p-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-indigo-600/30 text-xl font-bold">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create Student Account</h1>
            <p class="text-xs text-slate-500 mt-1">Start learning software engineering and interactive coding today</p>
        </div>

        @if(session()->has('url.intended') && Str::contains(session('url.intended'), '/learn/'))
            <div class="mb-5 p-3.5 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs flex items-center gap-2.5">
                <i class="fa-solid fa-circle-play text-indigo-600 text-base flex-shrink-0"></i>
                <div>
                    <span class="font-bold">Create an account to watch your Free Preview lecture!</span>
                    <span class="block text-[11px] text-indigo-700">You will be redirected straight to the classroom right after registering.</span>
                </div>
            </div>
        @endif

        <!-- Registration Form -->
        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                <div class="relative">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="e.g. Kazi Naima Hasan"
                        class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                    <i class="fa-solid fa-user absolute left-3 top-3 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="e.g. student@edulink.test"
                        class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                    <i class="fa-solid fa-envelope absolute left-3 top-3 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Phone Number (bKash / Nagad Notification)</label>
                <div class="relative">
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="e.g. +880 1711 000000"
                        class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                    <i class="fa-solid fa-phone absolute left-3 top-3 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            placeholder="Min 6 characters"
                            class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Confirm</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Repeat password"
                            class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                        <i class="fa-solid fa-shield-check absolute left-3 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition">
                    Create My Student Account
                </button>
            </div>
        </form>

        <!-- Sign In Link -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                Sign In &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
