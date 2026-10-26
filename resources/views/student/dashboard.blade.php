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
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Student Hub</span>
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
                    <div class="h-32 bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-800 p-4 flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between relative z-10">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/10 text-white border border-white/20">
                                {{ $course->category }}
                            </span>
                            @if($enrollment->status === 'completed' || $enrollment->progress_percent >= 100)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Completed
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                    In Progress
                                </span>
                            @endif
                        </div>

                        <h3 class="font-bold text-white text-sm line-clamp-2 relative z-10 leading-snug group-hover:text-cyan-200 transition">
                            {{ $course->title }}
                        </h3>
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
