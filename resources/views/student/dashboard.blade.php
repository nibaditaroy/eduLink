@extends('layouts.app')

@section('title', 'My Learning Dashboard - eduLink')

@section('content')
<div class="bg-slate-100/60 border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                @if(Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-md ring-2 ring-indigo-500/20">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-2xl flex items-center justify-center shadow-md">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">BAUST Student Hub</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                        Welcome, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Track your course progression, quizzes, and lab project submissions.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.show') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 hover:bg-white text-slate-700 text-xs font-bold shadow-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-indigo-600 text-xs"></i> Edit Profile
                </a>
                <a href="{{ route('courses.index') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Browse More Courses
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Learning Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['enrolled_courses'] }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Enrolled Courses</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-emerald-600">{{ $stats['lessons_completed'] }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lessons Finished</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-amber-600">{{ $stats['quizzes_passed'] }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Quizzes Passed</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-file-code"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-cyan-600">{{ $stats['assignments_submitted'] }}</div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Labs Submitted</div>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses Header -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-indigo-600"></i> My Active Curricula
        </h2>
        <span class="text-xs font-semibold text-slate-500">{{ $enrollments->count() }} registered course(s)</span>
    </div>

    <!-- Enrolled Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($enrollments as $enrollment)
            @php $course = $enrollment->course; @endphp
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                <div>
                    <!-- Header Banner -->
                    <div class="h-52 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-5 flex flex-col justify-between relative overflow-hidden">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-slate-900/40"></div>
                        @else
                            <!-- Geometric Dot Matrix Texture -->
                            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 16px 16px;"></div>
                            
                            <!-- Ambient Glow -->
                            <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full {{ Str::contains(strtolower($course->title), 'python') ? 'bg-cyan-500/25' : 'bg-indigo-500/25' }} blur-2xl group-hover:scale-125 transition duration-500"></div>
                            <div class="absolute -left-12 -bottom-12 w-48 h-48 rounded-full {{ Str::contains(strtolower($course->title), 'python') ? 'bg-amber-500/20' : 'bg-rose-500/20' }} blur-2xl"></div>
                        @endif

                        <!-- Top Badges Bar -->
                        <div class="flex items-center justify-between relative z-10">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/10 text-white backdrop-blur-md border border-white/20 shadow-xs">
                                {{ $course->category }}
                            </span>
                            @if($enrollment->status === 'completed' || $enrollment->progress_percent >= 100)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1 shadow-xs">
                                    <i class="fa-solid fa-check"></i> Completed
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 backdrop-blur-sm shadow-xs">
                                    In Progress
                                </span>
                            @endif
                        </div>

                        <!-- Centerpiece Tech Stack Indicator -->
                        <div class="my-auto py-2 flex items-center justify-between relative z-10">
                            @if(!$course->thumbnail_url)
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr {{ Str::contains(strtolower($course->title), 'python') ? 'from-amber-500 to-cyan-400' : 'from-indigo-600 to-rose-500' }} p-0.5 shadow-lg group-hover:scale-110 transition duration-300">
                                <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-lg">
                                    @if(Str::contains(strtolower($course->title), 'python'))
                                        <i class="fa-brands fa-python text-amber-400 text-xl"></i>
                                    @elseif(Str::contains(strtolower($course->title), ['laravel', 'php']))
                                        <i class="fa-brands fa-laravel text-rose-500 text-xl"></i>
                                    @else
                                        <i class="fa-solid fa-code text-cyan-400 text-xl"></i>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="text-right ms-auto">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-900/80 border border-slate-700/80 text-[10px] font-mono text-cyan-300 shadow-xs backdrop-blur-sm">
                                    <i class="fa-solid fa-microchip text-[9px]"></i>
                                    @if(Str::contains(strtolower($course->title), 'python'))
                                        Python 3 &bull; DSA
                                    @elseif(Str::contains(strtolower($course->title), ['laravel', 'php']))
                                        PHP 8.2 &bull; Laravel
                                    @else
                                        Interactive Lab
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Title on Banner -->
                        <div class="relative z-10">
                            <h3 class="font-extrabold text-white text-base line-clamp-2 leading-snug group-hover:text-cyan-200 transition drop-shadow-xs">
                                {{ $course->title }}
                            </h3>
                        </div>
                    </div>

                    <!-- Progress Details -->
                    <div class="p-5">
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1.5">
                                <span>Curriculum Progress</span>
                                <span class="{{ $enrollment->progress_percent >= 100 ? 'text-emerald-600' : 'text-indigo-600' }}">
                                    {{ $enrollment->progress_percent }}%
                                </span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $enrollment->progress_percent >= 100 ? 'bg-emerald-500' : 'bg-indigo-600' }}"
                                     style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-400 py-2 border-t border-slate-100">
                            <span><i class="fa-solid fa-layer-group text-slate-400 mr-1"></i> {{ $course->chapters_count }} Chapters</span>
                            <span><i class="fa-solid fa-play text-slate-400 mr-1"></i> {{ $course->lessons_count }} Lessons</span>
                            <span><i class="fa-solid fa-calendar text-slate-400 mr-1"></i> {{ $enrollment->enrolled_at->format('M d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-5 pt-0">
                    <a href="{{ route('learn.course', $course->slug) }}" class="w-full py-3 px-4 rounded-xl {{ $enrollment->progress_percent >= 100 ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-xs font-bold text-center block shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-play"></i>
                        <span>{{ $enrollment->progress_percent >= 100 ? 'Review Classroom' : 'Resume Learning' }}</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">You haven't enrolled in any courses yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Explore our tech catalog, try free foundation tracks or enroll with bKash/Nagad.</p>
                <a href="{{ route('courses.index') }}" class="inline-block mt-5 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition">
                    Browse Course Catalog
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
