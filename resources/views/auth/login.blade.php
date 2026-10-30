@extends('layouts.app')

@section('title', 'Sign In - eduLink Platform')

@section('content')
    <div class="py-12 lg:py-20 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200/90 shadow-xl p-8 sm:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-indigo-600/30 text-xl font-bold">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h1>
                <p class="text-xs text-slate-500 mt-1">Sign in to your Student or Administrator account</p>
            </div>

            @if(session()->has('url.intended') && Str::contains(session('url.intended'), '/learn/'))
                <div
                    class="mb-5 p-3.5 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-play text-indigo-600 text-base flex-shrink-0"></i>
                    <div>
                        <span class="font-bold">Sign in to watch your Free Preview lecture!</span>
                        <span class="block text-[11px] text-indigo-700">You will be redirected straight to the classroom right
                            after signing in.</span>
                    </div>
                </div>
            @endif

            <!-- Quick 1-Click Demo Login Bar
                        <div class="mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">⚡ 1-Click Demo Login</span>
                            <div class="grid grid-cols-2 gap-2.5 text-xs font-bold">
                                <a href="{{ route('quick-demo-login', 'student') }}" class="p-2.5 rounded-xl bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-indigo-700 transition flex items-center justify-center gap-2 shadow-xs">
                                    <i class="fa-solid fa-user-graduate text-indigo-500 text-sm"></i>
                                    <div class="text-left">
                                        <span class="block text-xs font-bold leading-tight">Student</span>
                                        <span class="block text-[9px] text-slate-400 font-normal leading-tight">student@gmail.com</span>
                                    </div>
                                </a>
                                <a href="{{ route('quick-demo-login', 'admin') }}" class="p-2.5 rounded-xl bg-white hover:bg-purple-50 border border-slate-200 hover:border-purple-300 text-purple-700 transition flex items-center justify-center gap-2 shadow-xs">
                                    <i class="fa-solid fa-user-shield text-purple-500 text-sm"></i>
                                    <div class="text-left">
                                        <span class="block text-xs font-bold leading-tight">Admin</span>
                                        <span class="block text-[9px] text-slate-400 font-normal leading-tight">admin@gmail.com</span>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-2 text-[10px] text-slate-400">
                                Default Password: <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-600">password</code>
                            </div>
                        </div> -->

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="student@gmail.com or admin@gmail.com"
                            class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                        <i class="fa-solid fa-envelope absolute left-3 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full pl-9 pr-3 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs py-1">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Remember me</span>
                    </label>
                    <span class="text-slate-400 text-[11px]">Student & Admin Unified Portal</span>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition">
                    Sign In to Account
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Create Free Account &rarr;
                </a>
            </div>
        </div>
    </div>
@endsection