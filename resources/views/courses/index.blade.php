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
                class="w-full pl-9 pr-8 py-2 text-xs rounded-xl bg-slate-50/90 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-slate-800 placeholder-slate-400 font-medium transition shadow-xs">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            @if(request('search'))
                <a href="{{ route('courses.index', request()->except('search')) }}" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 text-xs transition" title="Clear search">
                    <i class="fa-solid fa-circle-xmark"></i>
                </a>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
            <!-- Category Filter -->
            <x-custom-select 
                name="category" 
                :options="$allCategories" 
                :selected="request('category')" 
                placeholder="All Categories" 
                icon="fa-solid fa-shapes"
                :submitOnChange="true"
            />

            <!-- Level Filter -->
            <x-custom-select 
                name="level" 
                :options="[
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced'
                ]" 
                :selected="request('level')" 
                placeholder="All Levels" 
                icon="fa-solid fa-layer-group"
                :submitOnChange="true"
            />

            <!-- Price Filter -->
            <x-custom-select 
                name="pricing" 
                :options="[
                    'free' => 'Free Only',
                    'paid' => 'Paid Only (৳ BDT)'
                ]" 
                :selected="request('pricing')" 
                placeholder="All Prices" 
                icon="fa-solid fa-tag"
                :submitOnChange="true"
            />

            <!-- Sort By -->
            <x-custom-select 
                name="sort" 
                :options="[
                    'latest' => 'Newest Tracks',
                    'popular' => 'Most Enrolled',
                    'price_low' => 'Price: Low to High',
                    'price_high' => 'Price: High to Low'
                ]" 
                :selected="request('sort', 'latest')" 
                placeholder="Sort By" 
                icon="fa-solid fa-arrow-down-wide-short"
                :submitOnChange="true"
            />

            @if(request()->hasAny(['search', 'category', 'level', 'pricing', 'sort']))
                <a href="{{ route('courses.index') }}" class="px-3.5 py-2 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50/80 hover:bg-rose-100/80 border border-rose-200/80 rounded-xl transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                <!-- Clickable Tech Banner linked to Syllabus -->
                <a href="{{ route('courses.show', $course->slug) }}" class="relative block h-52 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-5 overflow-hidden group/thumb cursor-pointer">
                    <!-- Geometric Dot Matrix Texture -->
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 16px 16px;"></div>
                    
                    <!-- Ambient Glow -->
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
