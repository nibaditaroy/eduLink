@extends('layouts.app')

@section('title', 'eduLink - Master In-Demand Tech Skills with Interactive Practice')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28 bg-gradient-to-b from-indigo-50/60 via-slate-50 to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-100/80 border border-indigo-200 text-indigo-700 text-xs font-bold mb-6 animate-pulse">
                <i class="fa-solid fa-code text-indigo-600"></i>
                <span>Interactive Live Code Execution Sandbox &bull; Real Projects</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] mb-6">
                Master Modern Tech with <span class="gradient-text">Interactive Code Practice</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto font-normal">
                Structured university-grade engineering courses with interactive code sandboxes, timed MCQ exams, project assignments with instructor feedback, and instant enrollment in <strong class="text-indigo-600 font-semibold">Bangladeshi Taka (৳ BDT)</strong>.
            </p>

            <!-- Search & Quick CTA -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-xl mx-auto mb-10">
                <form action="{{ route('courses.index') }}" method="GET" class="w-full flex items-center shadow-lg shadow-indigo-500/10 rounded-2xl bg-white border border-slate-200 p-1.5">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 ml-3 mr-2 text-sm"></i>
                    <input type="text" name="search" placeholder="What skill do you want to master today?" class="w-full text-xs sm:text-sm bg-transparent outline-none text-slate-800 placeholder-slate-400">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold transition whitespace-nowrap shadow-sm">
                        Explore Courses
                    </button>
                </form>
            </div>

            <!-- Category Pills -->
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
                <span class="text-slate-400 font-semibold mr-1">Popular Topics:</span>
                @foreach($categories as $cat)
                    <a href="{{ route('courses.index', ['category' => $cat->category]) }}" class="px-3 py-1.5 rounded-full bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-700 font-medium transition shadow-2xs">
                        {{ $cat->category }} <span class="text-indigo-500 font-bold ml-0.5">({{ $cat->total }})</span>
                    </a>
                @endforeach
                <a href="{{ route('courses.index', ['pricing' => 'free']) }}" class="px-3 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 font-bold transition shadow-2xs">
                    <i class="fa-solid fa-gift mr-1 text-emerald-600"></i> Free Courses
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Bar -->
<section class="border-y border-slate-200 bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-1">{{ number_format($stats['total_students']) }}+</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Students</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-2xl sm:text-3xl font-extrabold text-indigo-600 mb-1">{{ $stats['total_courses'] }}</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Specialized Tracks</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mb-1">{{ $stats['completion_rate'] }}%</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pass & Completion Rate</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-2xl sm:text-3xl font-extrabold text-amber-500 mb-1">৳ BDT</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">bKash / Nagad Ready</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Hands-on Engineering</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Featured Career Courses</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Enroll in practical curricula designed for top software engineering roles.</p>
            </div>
            <a href="{{ route('courses.index') }}" class="text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition">
                View all {{ $stats['total_courses'] }} courses <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredCourses as $course)
                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                    <!-- Clickable Tech Banner linked to Syllabus -->
                    <a href="{{ route('courses.show', $course->slug) }}" class="relative block h-52 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-5 overflow-hidden group/thumb cursor-pointer">
                        <!-- Geometric Dot Matrix Texture -->
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 16px 16px;"></div>
                        
                        <!-- Ambient Neon Glow -->
                        <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full {{ Str::contains(strtolower($course->title), 'python') ? 'bg-cyan-500/25' : 'bg-indigo-500/25' }} blur-2xl group-hover/thumb:scale-125 transition duration-500"></div>
                        <div class="absolute -left-12 -bottom-12 w-48 h-48 rounded-full {{ Str::contains(strtolower($course->title), 'python') ? 'bg-amber-500/20' : 'bg-rose-500/20' }} blur-2xl"></div>

                        <div class="relative z-10 flex flex-col justify-between h-full">
                            <!-- Badges Bar -->
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/10 text-white backdrop-blur-md border border-white/20 shadow-xs">
                                    {{ $course->category }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $course->level === 'beginner' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($course->level === 'intermediate' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30') }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>

                            <!-- Tech Centerpiece Icon & Stack Indicator -->
                            <div class="my-auto py-2 flex items-center justify-between">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr {{ Str::contains(strtolower($course->title), 'python') ? 'from-amber-500 to-cyan-400' : 'from-indigo-600 to-rose-500' }} p-0.5 shadow-lg group-hover/thumb:scale-110 transition duration-300">
                                    <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-xl">
                                        @if(Str::contains(strtolower($course->title), 'python'))
                                            <i class="fa-brands fa-python text-amber-400 text-2xl"></i>
                                        @elseif(Str::contains(strtolower($course->title), ['laravel', 'php']))
                                            <i class="fa-brands fa-laravel text-rose-500 text-2xl"></i>
                                        @else
                                            <i class="fa-solid fa-code text-cyan-400 text-2xl"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tech Tag Pills -->
                                <div class="text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-900/80 border border-slate-700/80 text-[10px] font-mono text-cyan-300 shadow-xs">
                                        <i class="fa-solid fa-microchip text-[9px]"></i>
                                        @if(Str::contains(strtolower($course->title), 'python'))
                                            Python 3 &bull; DSA
                                        @elseif(Str::contains(strtolower($course->title), ['laravel', 'php']))
                                            PHP 8.2 &bull; Laravel 12
                                        @else
                                            Full Stack Lab
                                        @endif
                                    </span>
                                    <span class="block text-[10px] text-slate-400 font-semibold mt-1">Interactive Sandbox &bull; Verified</span>
                                </div>
                            </div>

                            <!-- Title on banner -->
                            <div>
                                <h3 class="font-bold text-white text-base line-clamp-2 leading-snug group-hover/thumb:text-cyan-300 transition">
                                    {{ $course->title }}
                                </h3>
                            </div>
                        </div>
                    </a>

                    <!-- Course Info -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                {{ $course->description ?? 'Master core principles through interactive lessons, real-world coding exercises, and instructor graded assignments.' }}
                            </p>

                            <div class="flex items-center gap-4 text-xs text-slate-500 pb-4 border-b border-slate-100">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-layer-group text-indigo-500"></i> {{ $course->chapters_count }} Chapters
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-play text-emerald-500"></i> {{ $course->lessons_count }} Lessons
                                </span>
                                <span class="flex items-center gap-1.5 ml-auto text-amber-500 font-bold">
                                    <i class="fa-solid fa-star"></i> 4.9
                                </span>
                            </div>
                        </div>

                        <!-- Price & Action -->
                        <div class="mt-4 pt-2 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Tuition Fee</span>
                                @if($course->isFree())
                                    <span class="text-base font-extrabold text-emerald-600">Free Access</span>
                                @else
                                    <span class="text-base font-extrabold text-slate-900">৳{{ number_format($course->price, 0) }} <span class="text-xs font-semibold text-slate-400">Tk</span></span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('courses.show', $course->slug) }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition flex items-center gap-1">
                                    <i class="fa-solid fa-book-open text-xs"></i>
                                    <span>Syllabus</span>
                                </a>
                                @if(in_array($course->id, $enrolledCourseIds))
                                    <a href="{{ route('learn.course', $course->slug) }}" class="px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-play text-emerald-600"></i> Resume
                                    </a>
                                @elseif($course->isFree())
                                    <a href="{{ route('courses.show', $course->slug) }}" class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                                        Enroll Free
                                    </a>
                                @else
                                    <a href="{{ route('checkout.show', $course->slug) }}" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1">
                                        Enroll Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    <p>No courses published yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Interactive Platform Highlights -->
<section class="py-20 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">How eduLink Works</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Built For Practical Mastery, Not Just Videos</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Everything you need to learn, test your knowledge, write real code, and earn verified credentials.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 relative group hover:bg-indigo-50/40 hover:border-indigo-200 transition">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl mb-6 shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-terminal"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">In-Browser Code Execution</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    Run PHP and algorithms right inside lesson classrooms using our built-in interactive execution sandbox. No local installation required.
                </p>
                <span class="text-xs font-bold text-indigo-600 flex items-center gap-1">
                    Live PHP Sandbox &rarr;
                </span>
            </div>

            <!-- Feature 2 -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 relative group hover:bg-emerald-50/40 hover:border-emerald-200 transition">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl mb-6 shadow-md shadow-emerald-600/20">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Timed Assessments & Quizzes</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    Test your understanding with chapter quizzes. Get instant score percentage, pass/fail certification verification, and detailed answer breakdowns.
                </p>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                    Instant Evaluations &rarr;
                </span>
            </div>

            <!-- Feature 3 -->
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 relative group hover:bg-cyan-50/40 hover:border-cyan-200 transition">
                <div class="w-12 h-12 rounded-2xl bg-cyan-600 text-white flex items-center justify-center text-xl mb-6 shadow-md shadow-cyan-600/20">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Project Assignments & Marks</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                    Submit assignments before deadlines. Instructors grade your code submission and provide actionable feedback directly on your portal.
                </p>
                <span class="text-xs font-bold text-cyan-600 flex items-center gap-1">
                    Verified Reviews &rarr;
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions (FAQ) Section -->
<section class="py-20 bg-slate-50 border-t border-slate-200/80">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-3">
                <i class="fa-solid fa-circle-question text-indigo-500"></i> Got Questions? We Have Answers
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Frequently Asked Questions</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Everything you need to know about hands-on labs, Bangladeshi payment options, course certifications, and enrollment.
            </p>
        </div>

        <!-- FAQ Accordion List -->
        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleFaq(this)" class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-slate-800 hover:text-indigo-600 transition">
                    <span class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                        </span>
                        <span>How do Bangladeshi Taka (৳ BDT) payments work with bKash, Nagad & Rocket?</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-icon text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="faq-content px-6 pb-5 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 hidden">
                    All courses on eduLink are priced transparently in <strong class="text-slate-800">Bangladeshi Taka (৳ BDT)</strong> without hidden exchange or conversion fees. During checkout, select your preferred mobile wallet (<strong class="text-indigo-600">bKash</strong>, <strong class="text-indigo-600">Nagad</strong>, or <strong class="text-indigo-600">Rocket</strong>), submit your transaction ID (TrxID) or mobile number, and your course is instantly unlocked with permanent lifetime access.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleFaq(this)" class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-slate-800 hover:text-indigo-600 transition">
                    <span class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-play"></i>
                        </span>
                        <span>Can I try lessons and practice in the sandbox before purchasing?</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-icon text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="faq-content px-6 pb-5 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 hidden">
                    Yes! Every course on eduLink provides <strong class="text-slate-800">Free Preview Lessons</strong>. You can inspect curriculum chapters, watch video lectures, and test your code directly inside the classroom execution sandbox without paying a single Taka. Just create a free student account to begin.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleFaq(this)" class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-slate-800 hover:text-indigo-600 transition">
                    <span class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-terminal"></i>
                        </span>
                        <span>Do I need to install Python, PHP, or local compilers on my computer?</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-icon text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="faq-content px-6 pb-5 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 hidden">
                    No local installation is required. eduLink features an <strong class="text-slate-800">In-Browser Practice Sandbox</strong> supporting Python 3, PHP 8.5, JavaScript, and live HTML/Tailwind rendering. You write code in the editor, click "Run Code", and receive immediate real-time output (stdout/stderr) right on your screen.
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleFaq(this)" class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-slate-800 hover:text-indigo-600 transition">
                    <span class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-list-check"></i>
                        </span>
                        <span>How do chapter quizzes and lab assignments work?</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-icon text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="faq-content px-6 pb-5 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 hidden">
                    Each chapter includes interactive <strong class="text-slate-800">MCQ Quizzes</strong> with instant automated scoring and explanations. For practical mastery, <strong class="text-slate-800">Lab Assignments</strong> challenge you to solve real problems and submit your solution. Your instructor reviews your submission and provides individual marks and constructive feedback.
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleFaq(this)" class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-slate-800 hover:text-indigo-600 transition">
                    <span class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-certificate"></i>
                        </span>
                        <span>Will I receive a verified certificate upon course completion?</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-icon text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="faq-content px-6 pb-5 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 hidden">
                    Yes! Once you finish all syllabus lessons, pass required quizzes, and submit all assignments, a verifiable graduation certificate is issued directly to your student dashboard. You can download it as a PDF or showcase the credential on your LinkedIn profile and resume.
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="faq-item rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden transition-all duration-200">
                <button type="button" onclick="toggleFaq(this)" class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-bold text-slate-800 hover:text-indigo-600 transition">
                    <span class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-infinity"></i>
                        </span>
                        <span>Is course access lifetime or does it expire after a few months?</span>
                    </span>
                    <i class="fa-solid fa-chevron-down faq-icon text-xs text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="faq-content px-6 pb-5 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 hidden">
                    All enrollments on eduLink include <strong class="text-slate-800">unlimited Lifetime Access</strong>. There are no monthly subscriptions, expirations, or renewal fees. You can learn at your own pace and revisit video lessons and code sandboxes whenever you want.
                </div>
            </div>
        </div>

        <!-- Contact Support Prompt -->
        <div class="mt-10 p-6 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-center flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-left">
                <h4 class="text-xs font-bold text-indigo-950">Still have a question or need payment assistance?</h4>
                <p class="text-[11px] text-indigo-700 mt-0.5">Our Dhaka-based support team is available 24/7 to help you get started.</p>
            </div>
            <a href="mailto:support@edulink.com" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition whitespace-nowrap shadow-xs">
                <i class="fa-solid fa-envelope mr-1.5"></i> Contact Support
            </a>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 bg-slate-900 relative overflow-hidden border-t border-slate-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
            Ready to upgrade your engineering skillset?
        </h2>
        <p class="text-slate-400 text-sm max-w-xl mx-auto mb-8">
            Join thousands of Bangladeshi engineers learning with eduLink. Pay securely via bKash or Nagad and start coding today.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('courses.index') }}" class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 transition">
                Browse All Courses
            </a>
            <a href="{{ route('register') }}" class="px-6 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white text-sm font-bold transition">
                Create Free Account
            </a>
        </div>
    </div>
</section>

<script>
    function toggleFaq(button) {
        const item = button.closest('.faq-item');
        const content = item.querySelector('.faq-content');
        const icon = item.querySelector('.faq-icon');
        const isHidden = content.classList.contains('hidden');

        // Close other FAQ items
        document.querySelectorAll('.faq-item').forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.querySelector('.faq-content').classList.add('hidden');
                otherItem.querySelector('.faq-icon').classList.remove('rotate-180');
                otherItem.classList.remove('border-indigo-300', 'ring-2', 'ring-indigo-500/10');
            }
        });

        if (isHidden) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
            item.classList.add('border-indigo-300', 'ring-2', 'ring-indigo-500/10');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
            item.classList.remove('border-indigo-300', 'ring-2', 'ring-indigo-500/10');
        }
    }

    // Auto-expand first item on load
    document.addEventListener('DOMContentLoaded', () => {
        const firstBtn = document.querySelector('.faq-item button');
        if (firstBtn) toggleFaq(firstBtn);
    });
</script>
@endsection
