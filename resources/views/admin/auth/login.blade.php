<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - EDULINK LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/40 to-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl shadow-indigo-100/60 border border-slate-200/80 p-8">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 items-center justify-center text-white shadow-lg shadow-indigo-200 mb-4">
                <i class="fa-solid fa-graduation-cap text-3xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">EDULINK LMS</h1>
            <p class="text-xs text-slate-500 mt-1">Instructor & Administrator Portal</p>
        </div>

        @if(session('success'))
        <div class="p-3 mb-4 text-xs font-medium text-emerald-800 bg-emerald-50 rounded-xl border border-emerald-200">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="p-3 mb-4 text-xs font-medium text-rose-800 bg-rose-50 rounded-xl border border-rose-200">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- 1-Click Demo Login for University Project Defense -->
        <div class="mb-6 p-4 rounded-2xl bg-indigo-50/80 border border-indigo-100 text-center">
            <div class="text-xs font-bold text-indigo-900 mb-1">
                <i class="fa-solid fa-wand-magic-sparkles text-indigo-600 me-1"></i> University Presentation Mode
            </div>
            <p class="text-[11px] text-indigo-700/80 mb-3">Instant 1-click access without entering passwords:</p>
            <a href="{{ route('admin.quick-login') }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-200 transition">
                <i class="fa-solid fa-bolt me-2"></i> Instant Admin Login
            </a>
        </div>

        <div class="relative flex py-2 items-center mb-6">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-xs font-medium text-slate-400">or sign in with email</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <!-- Standard Login Form -->
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block mb-1.5 text-xs font-semibold text-slate-700">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@gmail.com') }}" required class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5" placeholder="admin@gmail.com">
                </div>
            </div>

            <div>
                <label for="password" class="block mb-1.5 text-xs font-semibold text-slate-700">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password" required value="password" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="w-4 h-4 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="remember" class="ms-2 text-xs text-slate-600">Remember me</label>
                </div>
            </div>

            <button type="submit" class="w-full text-white bg-slate-900 hover:bg-slate-800 font-semibold rounded-xl text-sm px-5 py-2.5 text-center transition">
                Sign In to Admin
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400">
            EDULINK Platform • Laravel & Flowbite Edition
        </div>
    </div>

</body>
</html>
