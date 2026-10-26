@extends('admin.layouts.admin')

@section('title', 'Course Enrollments & Popularity Analytics')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Enrollments & Course Popularity</h1>
            <p class="text-xs text-gray-500 mt-0.5">Analyze high vs low demand courses, visual enrollment trends, and manage student course access.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-book-open me-2 text-indigo-600"></i> View All Courses
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-credit-card me-2"></i> Payment Records
            </a>
        </div>
    </div>

    <!-- Quick Aggregate KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <a href="{{ route('admin.enrollments.index') }}" 
           class="bg-white p-4 rounded-2xl border {{ !request('status') && !request('course_id') && !request('date_filter') ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-gray-200 hover:border-gray-300' }} shadow-sm transition block">
            <div class="text-xs text-gray-500 font-medium">Total Enrollments</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</div>
        </a>
        <a href="{{ route('admin.enrollments.index', ['status' => 'active', 'course_id' => request('course_id')]) }}" 
           class="bg-emerald-50/70 p-4 rounded-2xl border {{ request('status') === 'active' ? 'border-emerald-600 ring-2 ring-emerald-600/20' : 'border-emerald-200 hover:border-emerald-300' }} shadow-sm transition block">
            <div class="text-xs text-emerald-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active Learners
            </div>
            <div class="text-2xl font-bold text-emerald-800 mt-1">{{ number_format($stats['active']) }}</div>
        </a>
        <a href="{{ route('admin.enrollments.index', ['status' => 'completed', 'course_id' => request('course_id')]) }}" 
           class="bg-blue-50/70 p-4 rounded-2xl border {{ request('status') === 'completed' ? 'border-blue-600 ring-2 ring-blue-600/20' : 'border-blue-200 hover:border-blue-300' }} shadow-sm transition block">
            <div class="text-xs text-blue-700 font-medium flex items-center gap-1.5">
                <i class="fa-solid fa-graduation-cap text-[10px]"></i> Completed
            </div>
            <div class="text-2xl font-bold text-blue-800 mt-1">{{ number_format($stats['completed']) }}</div>
        </a>
        <a href="{{ route('admin.enrollments.index', ['status' => 'suspended', 'course_id' => request('course_id')]) }}" 
           class="bg-amber-50/70 p-4 rounded-2xl border {{ request('status') === 'suspended' ? 'border-amber-600 ring-2 ring-amber-600/20' : 'border-amber-200 hover:border-amber-300' }} shadow-sm transition block">
            <div class="text-xs text-amber-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Suspended
            </div>
            <div class="text-2xl font-bold text-amber-800 mt-1">{{ number_format($stats['suspended']) }}</div>
        </a>
        <div class="bg-purple-50/70 p-4 rounded-2xl border border-purple-200 shadow-sm col-span-2 md:col-span-1">
            <div class="text-xs text-purple-700 font-medium flex items-center gap-1.5">
                <i class="fa-solid fa-chart-line text-[10px]"></i> Avg Completion
            </div>
            <div class="text-2xl font-bold text-purple-800 mt-1">{{ $stats['avg_progress'] }}%</div>
        </div>
    </div>

    <!-- Visual Graphs & Analytics (ApexCharts Grace) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Graph 1: Enrollment Growth Trend Area Chart (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-area text-indigo-600"></i> Enrollment Velocity & Growth
                    </h2>
                    <p class="text-[11px] text-gray-400">Daily registrations over the last 7 days</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">Daily Trend</span>
            </div>
            <div class="mt-4">
                <div id="enrollment-trend-chart" class="w-full" style="min-height: 250px;"></div>
            </div>
        </div>

        <!-- Graph 2: Enrollment Status Distribution Donut Chart (1 Col) -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <div class="pb-3 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-indigo-600"></i> Status Distribution
                </h2>
                <p class="text-[11px] text-gray-400">Learners breakdown by completion status</p>
            </div>
            <div class="mt-4 flex items-center justify-center">
                <div id="status-donut-chart" class="w-full" style="min-height: 250px;"></div>
            </div>
        </div>

    </div>

    <!-- Course Popularity Comparison (More Enrolled vs Less Enrolled) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Most Enrolled Courses (High Demand) -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-fire"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Most Enrolled Courses</h2>
                        <p class="text-[11px] text-gray-400">Top courses with highest student registrations</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">High Demand</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse($mostEnrolled as $course)
                <div class="p-3 rounded-2xl bg-gray-50/70 border border-gray-100 hover:border-emerald-200 transition">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="font-bold text-xs text-gray-900 truncate max-w-xs" title="{{ $course->title }}">
                            {{ $course->title }}
                        </div>
                        <a href="{{ route('admin.enrollments.index', ['course_id' => $course->id]) }}" 
                           class="text-[11px] font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 px-2.5 py-0.5 rounded-full transition">
                            {{ $course->enrollments_count }} {{ Str::plural('Student', $course->enrollments_count) }}
                        </a>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        @php
                            $maxEnrollment = max($mostEnrolled->max('enrollments_count') ?? 1, 1);
                            $fillPct = ($course->enrollments_count / $maxEnrollment) * 100;
                        @endphp
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-1.5 rounded-full" style="width: {{ $fillPct }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">No course data available yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Least Enrolled Courses (Needs Promotion) -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-simple"></i>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Least Enrolled Courses</h2>
                        <p class="text-[11px] text-gray-400">Courses with low enrollment numbers (opportunity to promote)</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full">Low Demand</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse($leastEnrolled as $course)
                <div class="p-3 rounded-2xl bg-gray-50/70 border border-gray-100 hover:border-amber-200 transition">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="font-bold text-xs text-gray-900 truncate max-w-xs" title="{{ $course->title }}">
                            {{ $course->title }}
                        </div>
                        <a href="{{ route('admin.enrollments.index', ['course_id' => $course->id]) }}" 
                           class="text-[11px] font-bold text-amber-700 bg-amber-100 hover:bg-amber-200 px-2.5 py-0.5 rounded-full transition">
                            {{ $course->enrollments_count }} {{ Str::plural('Student', $course->enrollments_count) }}
                        </a>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ $course->enrollments_count > 0 ? '30%' : '5%' }}"></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">No course data available yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Filters Bar with Date Range -->
    <div class="bg-white p-5 rounded-3xl border border-gray-200 shadow-sm space-y-3">
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="space-y-3">
            
            <!-- Row 1: Search, Course, Status, Sort -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or course..." 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-9 p-2.5">
                </div>

                <!-- Course Dropdown -->
                <div>
                    <select name="course_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                        <option value="">All Courses</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                            {{ Str::limit($c->title, 26) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Dropdown -->
                <div>
                    <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Active</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>🎓 Completed</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>⏸️ Suspended</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <select name="sort" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest Enrolled</option>
                        <option value="progress_desc" {{ request('sort') === 'progress_desc' ? 'selected' : '' }}>Progress: High &rarr; Low</option>
                        <option value="progress_asc" {{ request('sort') === 'progress_asc' ? 'selected' : '' }}>Progress: Low &rarr; High</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Date Filter Presets + Custom Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-4 md:grid-cols-6 gap-3 items-center pt-2 border-t border-gray-100">
                
                <!-- Date Presets -->
                <div class="sm:col-span-2">
                    <select name="date_filter" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer font-medium">
                        <option value="all" {{ request('date_filter', 'all') === 'all' ? 'selected' : '' }}>📅 Date Range: All Time</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ request('date_filter') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ request('date_filter') === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('date_filter') === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_30_days" {{ request('date_filter') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="this_year" {{ request('date_filter') === 'this_year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>

                <!-- Custom Date From -->
                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="Date From"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                </div>

                <!-- Custom Date To -->
                <div>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" title="Date To"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                </div>

                <!-- Submit & Clear Buttons -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                        <i class="fa-solid fa-filter me-1"></i> Apply Filters
                    </button>
                    @if(request('search') || request('course_id') || request('status') || request('sort') || request('date_filter') || request('date_from') || request('date_to'))
                    <a href="{{ route('admin.enrollments.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                    @endif
                </div>

            </div>

        </form>
    </div>

    <!-- Enrollments Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">Student</th>
                        <th scope="col" class="px-6 py-4">Course Enrolled</th>
                        <th scope="col" class="px-6 py-4">Progress %</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4">Enrolled Date</th>
                        <th scope="col" class="px-6 py-4 text-center">Quick Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50/60 transition">
                        
                        <!-- Student -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($enrollment->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $enrollment->user_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block text-xs">
                                        {{ $enrollment->user->name ?? 'Unknown User' }}
                                    </a>
                                    <span class="text-[11px] text-gray-400">{{ $enrollment->user->email ?? '—' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Course Enrolled -->
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.courses.show', $enrollment->course_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block text-xs">
                                {{ $enrollment->course->title ?? 'Course #' . $enrollment->course_id }}
                            </a>
                            <span class="text-[11px] text-gray-400">{{ $enrollment->course->category ?? 'General' }}</span>
                        </td>

                        <!-- Progress Bar -->
                        <td class="px-6 py-4 min-w-[140px]">
                            <div class="flex items-center justify-between text-xs font-semibold mb-1">
                                <span class="text-gray-600 text-[11px]">Completion</span>
                                <span class="text-indigo-600 font-bold text-[11px]">{{ $enrollment->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-600 h-1.5 rounded-full transition-all" style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @if($enrollment->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                            @elseif($enrollment->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fa-solid fa-graduation-cap text-[10px]"></i> Completed
                            </span>
                            @elseif($enrollment->status === 'suspended')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Suspended
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <i class="fa-solid fa-ban text-[10px]"></i> Cancelled
                            </span>
                            @endif
                        </td>

                        <!-- Enrolled Date -->
                        <td class="px-6 py-4 text-xs text-gray-500">
                            {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : $enrollment->created_at->format('M d, Y') }}
                        </td>

                        <!-- Quick Status Change Form -->
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.enrollments.status', $enrollment->id) }}" method="POST" class="inline-flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs py-1 px-2.5 rounded-xl border border-gray-300 bg-white font-medium text-gray-700 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-sm">
                                    <option value="active" {{ $enrollment->status === 'active' ? 'selected' : '' }}>🟢 Active</option>
                                    <option value="completed" {{ $enrollment->status === 'completed' ? 'selected' : '' }}>🎓 Completed</option>
                                    <option value="suspended" {{ $enrollment->status === 'suspended' ? 'selected' : '' }}>⏸️ Suspended</option>
                                    <option value="cancelled" {{ $enrollment->status === 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                                </select>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.show', $enrollment->user_id) }}" title="View Student 360° Report" 
                                   class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition text-xs">
                                    <i class="fa-solid fa-chart-line"></i>
                                </a>
                                <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this course enrollment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remove Enrollment" class="p-2 text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 rounded-lg transition text-xs">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            <div class="font-medium text-gray-900">No enrollments match this filter</div>
                            <p class="text-xs text-gray-500 mt-1">Try resetting your date range or course filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enrollments->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $enrollments->links() }}
        </div>
        @endif
    </div>

</div>

<!-- ApexCharts Script Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Enrollment Trend Area Chart
    const trendOptions = {
        chart: {
            type: 'area',
            height: 250,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif',
        },
        series: [{
            name: 'New Enrollments',
            data: @json($trendCounts),
        }],
        xaxis: {
            categories: @json($trendDates),
            labels: { style: { colors: '#94a3b8', fontSize: '11px' } }
        },
        yaxis: {
            labels: { style: { colors: '#94a3b8', fontSize: '11px' } },
            min: 0,
            forceNiceScale: true,
        },
        colors: ['#6366f1'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9' },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#enrollment-trend-chart"), trendOptions).render();

    // 2. Status Donut Chart
    const statusOptions = {
        chart: {
            type: 'donut',
            height: 250,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
        },
        series: @json($statusChartData['series']),
        labels: @json($statusChartData['labels']),
        colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
        legend: { position: 'bottom', fontSize: '11px' },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '12px',
                            fontWeight: 700,
                            color: '#1e293b'
                        }
                    }
                }
            }
        },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#status-donut-chart"), statusOptions).render();
});
</script>
@endsection
