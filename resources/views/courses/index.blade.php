@extends('layouts.app')

@section('title', 'Explore All Courses - eduLink')

@section('content')
<div class="bg-slate-100/60 border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Course Marketplace</span>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Explore All Specialized Courses</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Filter by skill level, category, and price in Bangladeshi Taka (৳ BDT). Lifetime access to all enrolled materials.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filters & Search Toolbar -->
    <form action="{{ route('courses.index') }}" method="GET" class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- Search Input -->
        <div class="relative w-full md:w-80">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or topics..." 
                class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-xs"></i>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Categories</option>
                @foreach($allCategories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Level Filter -->
            <select name="level" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Levels</option>
                <option value="beginner" {{ request('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
            </select>

            <!-- Price Filter -->
            <select name="pricing" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">All Prices</option>
                <option value="free" {{ request('pricing') === 'free' ? 'selected' : '' }}>Free Only</option>
                <option value="paid" {{ request('pricing') === 'paid' ? 'selected' : '' }}>Paid Only (৳ BDT)</option>
            </select>

            <!-- Sort By -->
            <select name="sort" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest Tracks</option>
                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Enrolled</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
            </select>

            @if(request()->hasAny(['search', 'category', 'level', 'pricing', 'sort']))
                <a href="{{ route('courses.index') }}" class="px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                <!-- Thumbnail / Header -->
                <div class="relative h-44 bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-800 p-5 flex flex-col justify-between overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-36 h-36 rounded-full bg-indigo-500/20 blur-xl"></div>

                    <div class="flex items-center justify-between relative z-10">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/10 text-white backdrop-blur-md border border-white/20">
                            {{ $course->category }}
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $course->level === 'beginner' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($course->level === 'intermediate' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30') }}">
                            {{ ucfirst($course->level) }}
                        </span>
                    </div>

                    <div class="relative z-10">
                        <h3 class="font-bold text-white text-base line-clamp-2 leading-snug group-hover:text-cyan-200 transition">
                            {{ $course->title }}
                        </h3>
                    </div>
                </div>

                <!-- Course Body -->
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                            {{ $course->description ?? 'Learn hands-on with real projects and interactive code sandboxes.' }}
                        </p>

                        <div class="flex items-center gap-4 text-xs text-slate-500 pb-4 border-b border-slate-100">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-layer-group text-indigo-500"></i> {{ $course->chapters_count }} Chapters
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-play text-emerald-500"></i> {{ $course->lessons_count }} Lessons
                            </span>
                            <span class="flex items-center gap-1.5 ml-auto text-slate-400">
                                <i class="fa-solid fa-user-group"></i> {{ $course->enrollments_count }}
                            </span>
                        </div>
                    </div>

                    <!-- Price & CTA -->
                    <div class="mt-4 pt-2 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Tuition</span>
                            @if($course->isFree())
                                <span class="text-base font-extrabold text-emerald-600">Free</span>
                            @else
                                <span class="text-base font-extrabold text-slate-900">৳{{ number_format($course->price, 0) }} <span class="text-xs font-semibold text-slate-400">Tk</span></span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('courses.show', $course->slug) }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                Syllabus
                            </a>
                            @if(in_array($course->id, $enrolledCourseIds))
                                <a href="{{ route('learn.course', $course->slug) }}" class="px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition flex items-center gap-1">
                                    <i class="fa-solid fa-circle-play text-emerald-600"></i> Continue
                                </a>
                            @elseif($course->isFree())
                                <a href="{{ route('checkout.show', $course->slug) }}" class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                                    Enroll Free
                                </a>
                            @else
                                <a href="{{ route('checkout.show', $course->slug) }}" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/20 transition">
                                    Enroll &bull; ৳{{ number_format($course->price, 0) }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">No courses match your filter</h3>
                <p class="text-xs text-slate-500 mt-1">Try resetting your search query or selecting a different category.</p>
                <a href="{{ route('courses.index') }}" class="inline-block mt-4 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 text-xs font-bold hover:bg-indigo-100 transition">
                    View All Courses
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $courses->links() }}
    </div>
</div>
@endsection
