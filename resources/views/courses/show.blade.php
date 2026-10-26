@extends('layouts.app')

@section('title', $course->title . ' - eduLink')

@section('content')
<!-- Hero Header -->
<div class="bg-slate-900 text-white py-12 lg:py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            <div class="lg:col-span-2">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        {{ $course->category }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-slate-300 border border-white/20">
                        {{ ucfirst($course->level) }} Level
                    </span>
                    <span class="text-xs text-amber-400 font-bold flex items-center gap-1 ml-2">
                        <i class="fa-solid fa-star"></i> 4.9 ({{ $course->enrollments_count + 18 }} reviews)
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-4 leading-tight">
                    {{ $course->title }}
                </h1>

                <p class="text-sm sm:text-base text-slate-300 leading-relaxed mb-6 font-normal">
                    {{ $course->description ?? 'Learn through an immersive curriculum built with real-world industry standards, in-browser code compilation sandboxes, timed quizzes, and instructor graded lab projects.' }}
                </p>

                <div class="flex flex-wrap items-center gap-6 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-400"></i>
                        <span>{{ $course->chapters->count() }} Structured Chapters</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-play text-emerald-400"></i>
                        <span>{{ $course->lessons_count }} Lessons & Labs</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bangladeshi-taka-sign text-cyan-400"></i>
                        <span>Bangladesh Taka (৳ BDT)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content & Sticky Checkout Card -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
        <!-- Left: Course Details & Syllabus -->
        <div class="lg:col-span-2 space-y-10">
            <!-- Learning Objectives & Highlights -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bullseye text-indigo-600"></i> What You Will Learn & Build
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>Full grasp of modern industry paradigms and relational schemas.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>In-browser code execution for immediate interactive practice.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>Timed MCQ quizzes with instant scoring and pass/fail badges.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                        <span>Production-grade lab projects with instructor grading.</span>
                    </div>
                </div>
            </div>

            <!-- Curriculum Syllabus Breakdown -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-indigo-600"></i> Course Curriculum
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $course->chapters->count() }} Chapters &bull; {{ $course->lessons_count }} Interactive Lessons</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($course->chapters as $index => $chapter)
                        <div class="border border-slate-200 rounded-2xl overflow-hidden">
                            <!-- Chapter Header -->
                            <div class="bg-slate-50 p-4 flex items-center justify-between border-b border-slate-200">
                                <div>
                                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block">Chapter {{ $index + 1 }}</span>
                                    <h3 class="text-sm font-bold text-slate-900">{{ $chapter->title }}</h3>
                                </div>
                                <span class="text-xs font-semibold text-slate-400">
                                    {{ $chapter->lessons->count() }} lessons
                                </span>
                            </div>

                            <!-- Lessons, Quizzes & Assignments under Chapter -->
                            <div class="divide-y divide-slate-100 bg-white">
                                <!-- Lessons -->
                                @foreach($chapter->lessons as $lIndex => $lesson)
                                    <div class="p-3.5 px-4 flex items-center justify-between text-xs hover:bg-slate-50 transition">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-bold">
                                                {{ $lIndex + 1 }}
                                            </span>
                                            <div>
                                                <span class="font-medium text-slate-800">{{ $lesson->title }}</span>
                                                @if($lesson->code_snippet)
                                                    <span class="ml-2 px-1.5 py-0.5 rounded text-[9px] bg-slate-100 text-slate-600 font-mono">Code Lab</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if($isEnrolled)
                                                <a href="{{ route('learn.lesson', [$course->slug, $lesson->id]) }}" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold transition text-[11px]">
                                                    Open Lesson
                                                </a>
                                            @elseif($index === 0 && $lIndex === 0)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    Free Preview
                                                </span>
                                            @else
                                                <i class="fa-solid fa-lock text-slate-300 text-xs"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Quizzes -->
                                @foreach($chapter->quizzes as $quiz)
                                    <div class="p-3.5 px-4 flex items-center justify-between text-xs bg-amber-50/40">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-[10px]">
                                                <i class="fa-solid fa-circle-question"></i>
                                            </span>
                                            <div>
                                                <span class="font-bold text-amber-900">Assessment: {{ $quiz->title }}</span>
                                                <span class="block text-[10px] text-amber-700">{{ $quiz->questions->count() }} Questions &bull; {{ $quiz->time_limit_mins }} Mins</span>
                                            </div>
                                        </div>
                                        @if($isEnrolled)
                                            <a href="{{ route('learn.quiz', [$course->slug, $quiz->id]) }}" class="px-2.5 py-1 rounded-lg bg-amber-200 text-amber-900 font-bold text-[11px] hover:bg-amber-300 transition">
                                                Take Quiz
                                            </a>
                                        @else
                                            <i class="fa-solid fa-lock text-slate-300 text-xs"></i>
                                        @endif
                                    </div>
                                @endforeach

                                <!-- Assignments -->
                                @foreach($chapter->assignments as $assignment)
                                    <div class="p-3.5 px-4 flex items-center justify-between text-xs bg-cyan-50/40">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center text-[10px]">
                                                <i class="fa-solid fa-file-code"></i>
                                            </span>
                                            <div>
                                                <span class="font-bold text-cyan-900">Project: {{ $assignment->title }}</span>
                                                <span class="block text-[10px] text-cyan-700">Max Marks: {{ $assignment->max_marks }} pts</span>
                                            </div>
                                        </div>
                                        @if($isEnrolled)
                                            <a href="{{ route('learn.assignment', [$course->slug, $assignment->id]) }}" class="px-2.5 py-1 rounded-lg bg-cyan-200 text-cyan-900 font-bold text-[11px] hover:bg-cyan-300 transition">
                                                View Brief
                                            </a>
                                        @else
                                            <i class="fa-solid fa-lock text-slate-300 text-xs"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4">Syllabus is being structured by the instructor.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Sticky Purchase / Enrollment Card -->
        <div class="lg:sticky lg:top-24">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden p-6 sm:p-7">
                <!-- Price Header -->
                <div class="mb-6 pb-6 border-b border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Tuition & Enrollment</span>
                    @if($course->isFree())
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-emerald-600">Free Access</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">100% free community track sponsored by eduLink.</p>
                    @else
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-slate-900">৳{{ number_format($course->price, 0) }}</span>
                            <span class="text-sm font-bold text-slate-400">Tk / BDT</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">One-time payment &bull; Full lifetime access</p>
                    @endif
                </div>

                <!-- CTA Button -->
                <div class="mb-6">
                    @if($isEnrolled)
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs mb-3 text-center font-bold">
                            <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> You are enrolled in this course!
                            <div class="mt-1 text-[11px] font-semibold text-emerald-700">Progress: {{ $enrollment->progress_percent ?? 0 }}% completed</div>
                        </div>
                        <a href="{{ route('learn.course', $course->slug) }}" class="w-full py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold text-center block shadow-lg shadow-emerald-600/25 transition">
                            <i class="fa-solid fa-circle-play mr-1.5"></i> Continue Learning
                        </a>
                    @elseif($course->isFree())
                        <form action="{{ route('checkout.free', $course->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold shadow-lg shadow-indigo-600/25 transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-graduation-cap"></i> Enroll for Free Now
                            </button>
                        </form>
                    @else
                        <a href="{{ route('checkout.show', $course->slug) }}" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold text-center block shadow-lg shadow-indigo-600/25 transition">
                            <i class="fa-solid fa-credit-card mr-1.5"></i> Enroll Now &bull; ৳{{ number_format($course->price, 0) }} Tk
                        </a>
                    @endif
                </div>

                <!-- Course Inclusions Checklist -->
                <div class="space-y-3 text-xs text-slate-600 pt-2 border-t border-slate-100">
                    <div class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Track Features</div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-infinity text-indigo-500 w-4 text-center"></i>
                        <span>Full lifetime access to all lessons</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-terminal text-indigo-500 w-4 text-center"></i>
                        <span>Live in-browser code compilation sandbox</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-certificate text-indigo-500 w-4 text-center"></i>
                        <span>Verified completion certificate</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-mobile-screen text-indigo-500 w-4 text-center"></i>
                        <span>Instant bKash, Nagad & Rocket verification</span>
                    </div>
                </div>

                <!-- Payment Partners Badges -->
                <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                    <span class="text-[10px] text-slate-400 font-semibold block mb-2">Secure Bangladeshi Payment Channels</span>
                    <div class="flex items-center justify-center gap-2 text-[10px] font-bold text-slate-500">
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-pink-600">bKash</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-orange-600">Nagad</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-purple-600">Rocket</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-blue-600">Cards</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
