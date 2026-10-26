@extends('admin.layouts.admin')

@section('title', 'Assignment Submissions & Grading Hub')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Assignment Submissions & Grading</h1>
            <p class="text-xs text-gray-500 mt-0.5">Review student homework submissions, check late submissions past deadline, and assign marks.</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl shadow-sm transition">
            <i class="fa-solid fa-book-open me-2 text-indigo-600"></i> Course Curriculum
        </a>
    </div>

    <!-- Quick Aggregate KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('admin.assignment-submissions.index') }}" 
           class="bg-white p-4 rounded-2xl border {{ !request('status') && !request('late_only') ? 'border-slate-900 ring-2 ring-slate-900/10' : 'border-gray-200 hover:border-gray-300' }} shadow-sm transition block">
            <div class="text-xs text-gray-500 font-medium">Total Submissions</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</div>
        </a>
        <a href="{{ route('admin.assignment-submissions.index', ['status' => 'submitted']) }}" 
           class="bg-amber-50/70 p-4 rounded-2xl border {{ request('status') === 'submitted' ? 'border-amber-600 ring-2 ring-amber-600/20' : 'border-amber-200 hover:border-amber-300' }} shadow-sm transition block">
            <div class="text-xs text-amber-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Pending Grading
            </div>
            <div class="text-2xl font-bold text-amber-800 mt-1">{{ number_format($stats['pending']) }}</div>
        </a>
        <a href="{{ route('admin.assignment-submissions.index', ['status' => 'graded']) }}" 
           class="bg-emerald-50/70 p-4 rounded-2xl border {{ request('status') === 'graded' ? 'border-emerald-600 ring-2 ring-emerald-600/20' : 'border-emerald-200 hover:border-emerald-300' }} shadow-sm transition block">
            <div class="text-xs text-emerald-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Graded & Evaluated
            </div>
            <div class="text-2xl font-bold text-emerald-800 mt-1">{{ number_format($stats['graded']) }}</div>
        </a>
        <a href="{{ route('admin.assignment-submissions.index', ['late_only' => 1]) }}" 
           class="bg-rose-50/70 p-4 rounded-2xl border {{ request('late_only') ? 'border-rose-600 ring-2 ring-rose-600/20' : 'border-rose-200 hover:border-rose-300' }} shadow-sm transition block">
            <div class="text-xs text-rose-700 font-medium flex items-center gap-1.5">
                <i class="fa-solid fa-clock-rotate-left text-[10px]"></i> Late Submissions
            </div>
            <div class="text-2xl font-bold text-rose-800 mt-1">{{ number_format($stats['late']) }}</div>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.assignment-submissions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center">
            
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or title..." 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-9 p-2.5">
            </div>

            <!-- Course -->
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

            <!-- Assignment -->
            <div>
                <select name="assignment_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                    <option value="">All Assignments</option>
                    @foreach($assignments as $a)
                    <option value="{{ $a->id }}" {{ request('assignment_id') == $a->id ? 'selected' : '' }}>
                        {{ Str::limit($a->title, 26) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>⏳ Pending Review</option>
                    <option value="graded" {{ request('status') === 'graded' ? 'selected' : '' }}>✅ Graded</option>
                    <option value="resubmit_requested" {{ request('status') === 'resubmit_requested' ? 'selected' : '' }}>🔄 Resubmit Requested</option>
                </select>
            </div>

            <!-- Late Only & Submit -->
            <div class="flex items-center gap-2">
                <label class="flex items-center gap-1.5 text-xs text-gray-700 font-medium cursor-pointer flex-shrink-0">
                    <input type="checkbox" name="late_only" value="1" onchange="this.form.submit()" {{ request('late_only') ? 'checked' : '' }}
                        class="w-4 h-4 text-rose-600 bg-gray-100 border-gray-300 rounded focus:ring-rose-500">
                    Late Only
                </label>
                <button type="submit" class="px-3.5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition flex-shrink-0">
                    Filter
                </button>
                @if(request('search') || request('course_id') || request('assignment_id') || request('status') || request('late_only'))
                <a href="{{ route('admin.assignment-submissions.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs flex-shrink-0" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">Student</th>
                        <th scope="col" class="px-6 py-4">Assignment & Course</th>
                        <th scope="col" class="px-6 py-4 text-center">Timeliness</th>
                        <th scope="col" class="px-6 py-4 text-center">Score Awarded</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4">Submitted At</th>
                        <th scope="col" class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($submissions as $sub)
                    <tr class="hover:bg-gray-50/60 transition">
                        
                        <!-- Student -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($sub->user->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $sub->user_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block text-xs">
                                        {{ $sub->user->name ?? 'Unknown Student' }}
                                    </a>
                                    <span class="text-[11px] text-gray-400">{{ $sub->user->email ?? '—' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Assignment & Course -->
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.assignments.show', $sub->assignment_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block text-xs">
                                {{ $sub->assignment->title ?? 'Assignment #' . $sub->assignment_id }}
                            </a>
                            <span class="text-[11px] text-gray-400">
                                {{ $sub->assignment->chapter->course->title ?? 'Course' }}
                            </span>
                        </td>

                        <!-- Timeliness Badge -->
                        <td class="px-6 py-4 text-center">
                            @if($sub->is_late)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                <i class="fa-solid fa-clock-rotate-left text-[10px]"></i> LATE
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-check text-[10px]"></i> ON-TIME
                            </span>
                            @endif
                        </td>

                        <!-- Marks -->
                        <td class="px-6 py-4 text-center">
                            @if($sub->marks_obtained !== null)
                            <span class="font-extrabold text-sm text-gray-900">
                                {{ $sub->marks_obtained }}
                            </span>
                            <span class="text-xs text-gray-400">/ {{ $sub->assignment->max_marks ?? 100 }}</span>
                            @else
                            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                                Not graded
                            </span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @if($sub->status === 'graded')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Graded
                            </span>
                            @elseif($sub->status === 'resubmit_requested')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Resubmit
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                Submitted
                            </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-xs text-gray-500">
                            <div>{{ $sub->submitted_at ? $sub->submitted_at->format('M d, Y') : $sub->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] text-gray-400">{{ $sub->submitted_at ? $sub->submitted_at->format('h:i A') : $sub->created_at->format('h:i A') }}</div>
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.assignments.show', $sub->assignment_id) }}" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Review & Grade
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <div class="font-medium text-gray-900">No submissions found</div>
                            <p class="text-xs text-gray-500 mt-1">Try resetting your filter parameters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
