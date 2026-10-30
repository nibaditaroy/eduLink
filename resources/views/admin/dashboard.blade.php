@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="relative overflow-hidden p-6 rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 text-white shadow-xl shadow-indigo-900/10">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-indigo-200 backdrop-blur-md mb-2">
                    <i class="fa-solid fa-graduation-cap"></i> BAUST Academic Management System
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Admin & Curriculum Control</h1>
                <p class="text-indigo-200 text-sm mt-1 max-w-xl">
                    Manage BAUST engineering courses, chapters, interactive lessons, quizzes, and practical assignments.
                </p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl backdrop-blur-md shadow-md transition group">
                    <i class="fa-solid fa-globe me-2 text-cyan-300 group-hover:rotate-12 transition"></i> Live View Website
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ms-2 opacity-70"></i>
                </a>
                <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-indigo-900 bg-white hover:bg-indigo-50 rounded-xl shadow-md transition">
                    <i class="fa-solid fa-plus me-2 text-indigo-600"></i> Create New Course
                </a>
            </div>
        </div>
        <div class="absolute -right-8 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Stat 1: Courses -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500">Courses</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_courses'] }}</div>
                <div class="text-[11px] text-gray-400 mt-0.5">Published & Active</div>
            </div>
        </div>

        <!-- Stat 2: Chapters -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500">Chapters</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_chapters'] }}</div>
                <div class="text-[11px] text-gray-400 mt-0.5">Syllabus Units</div>
            </div>
        </div>

        <!-- Stat 3: Lessons -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500">Lessons</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-play"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_lessons'] }}</div>
                <div class="text-[11px] text-gray-400 mt-0.5">Videos, Code & Text</div>
            </div>
        </div>

        <!-- Stat 4: Quizzes -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500">Quizzes</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_quizzes'] }}</div>
                <div class="text-[11px] text-gray-400 mt-0.5">MCQ Tests</div>
            </div>
        </div>

        <!-- Stat 5: Assignments -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500">Assignments</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-file-code"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_assignments'] }}</div>
                <div class="text-[11px] text-gray-400 mt-0.5">Tasks & Projects</div>
            </div>
        </div>

        <!-- Stat 6: Pricing Split -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500">Course Pricing</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
            <div>
                <div class="text-sm font-bold text-gray-900">
                    <span class="text-emerald-600">{{ $stats['free_courses'] }} Free</span> / 
                    <span class="text-indigo-600">{{ $stats['paid_courses'] }} Paid</span>
                </div>
                <div class="text-[11px] text-gray-400 mt-0.5">Tier breakdown</div>
            </div>
        </div>
    </div>

    <!-- Active Courses Section -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Courses & Curriculum Trees</h2>
                <p class="text-xs text-gray-500">Click any course to manage its Chapters, Lessons, Quizzes, and Assignments.</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                View All Courses <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        @if($recentCourses->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">No Courses Created Yet</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">
                Begin by creating your first course. Afterwards, you can add chapters, lessons, quizzes, and assignments.
            </p>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                <i class="fa-solid fa-plus me-1.5"></i> Create First Course
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 p-5">
            @foreach($recentCourses as $course)
            <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl overflow-hidden hover:shadow-md transition duration-200 flex flex-col justify-between">
                <div>
                    <!-- Thumbnail or Banner -->
                    <div class="h-36 bg-gradient-to-r from-slate-800 to-indigo-900 relative overflow-hidden flex items-center justify-center">
                        @if($course->thumbnail_url)
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-center text-white/80 p-4">
                            <i class="fa-solid fa-laptop-code text-3xl mb-2 text-indigo-400"></i>
                            <div class="text-xs font-semibold uppercase tracking-wider">{{ $course->category }}</div>
                        </div>
                        @endif
                        <!-- Badges -->
                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                            @if($course->isFree())
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-sm">
                                Free Course
                            </span>
                            @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white shadow-sm">
                                ৳{{ number_format($course->price, 2) }} Tk
                            </span>
                            @endif
                        </div>
                        <div class="absolute top-2.5 right-2.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase bg-black/50 text-white backdrop-blur-md">
                                {{ $course->level }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-base leading-snug mb-1 line-clamp-1">
                            {{ $course->title }}
                        </h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">
                            {{ $course->description ?? 'No description provided.' }}
                        </p>

                        <!-- Counts -->
                        <div class="grid grid-cols-2 gap-2 text-xs py-2 px-3 bg-white rounded-xl border border-gray-100 mb-2">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-layer-group text-indigo-500"></i>
                                <span><strong>{{ $course->chapters_count }}</strong> Chapters</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-circle-play text-emerald-500"></i>
                                <span><strong>{{ $course->lessons_count }}</strong> Lessons</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-4 pt-0 flex items-center gap-2">
                    <a href="{{ route('admin.courses.show', $course->id) }}" class="flex-1 text-center py-2 px-3 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-sm">
                        <i class="fa-solid fa-sitemap me-1"></i> Manage Curriculum
                    </a>
                    <a href="{{ route('admin.chapters.create', ['course_id' => $course->id]) }}" title="Add Chapter" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-xl transition border border-indigo-200">
                        <i class="fa-solid fa-folder-plus"></i>
                    </a>
                    <a href="{{ route('admin.courses.edit', $course->id) }}" title="Edit Course" class="p-2 text-gray-500 hover:bg-gray-100 rounded-xl transition border border-gray-200">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
