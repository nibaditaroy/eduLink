@extends('admin.layouts.admin')

@section('title', 'Assignment: ' . $assignment->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li><a href="{{ route('admin.courses.show', $assignment->chapter->course_id) }}" class="hover:text-indigo-600">{{ $assignment->chapter->course->title }}</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li class="font-medium text-gray-900">Assignment Details</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $assignment->title }}</h1>
            <p class="text-xs text-gray-500 mt-0.5">Chapter {{ $assignment->chapter->sort_order }}: {{ $assignment->chapter->title }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.assignment-submissions.index', ['assignment_id' => $assignment->id]) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                <i class="fa-solid fa-list-check me-1.5"></i> All Submissions
            </a>
            <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-pen me-1.5 text-gray-500"></i> Edit
            </a>
            <a href="{{ route('admin.courses.show', $assignment->chapter->course_id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Course
            </a>
        </div>
    </div>

    <!-- Metadata & Deadline Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Max Marks -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <span class="text-xs text-gray-500 block">Maximum Marks</span>
                <span class="text-lg font-bold text-gray-900">{{ $assignment->max_marks }} Points</span>
            </div>
        </div>

        <!-- Deadline & Status -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-3 sm:col-span-2">
            <div class="w-10 h-10 rounded-xl {{ $assignment->isPastDeadline() ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid {{ $assignment->isPastDeadline() ? 'fa-triangle-exclamation' : 'fa-calendar-check' }}"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Submission Deadline</span>
                    @if($assignment->deadline)
                        @if($assignment->isPastDeadline())
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-700">
                            DEADLINE EXPIRED (LATE ENFORCED)
                        </span>
                        @else
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">
                            ON-TIME ACCEPTING
                        </span>
                        @endif
                    @endif
                </div>
                <span class="text-sm font-bold text-gray-900">
                    {{ $assignment->deadline ? $assignment->deadline->format('M d, Y \a\t h:i A') : 'No strict deadline set' }}
                </span>
            </div>
        </div>

    </div>

    <!-- Instructions Content -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-4">
        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-file-lines text-indigo-600"></i> Task Requirements & Instructions
        </h2>
        <div class="prose max-w-none text-sm text-gray-800 leading-relaxed whitespace-pre-line bg-gray-50 p-5 rounded-2xl border border-gray-100">
            {{ $assignment->instructions }}
        </div>

        @if($assignment->attachment_file)
        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-700 font-semibold">
                <i class="fa-solid fa-paperclip text-indigo-600 text-sm"></i> Attached Starter Template / Asset:
            </div>
            <a href="{{ asset('storage/' . $assignment->attachment_file) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                <i class="fa-solid fa-download me-1.5"></i> Download Attachment
            </a>
        </div>
        @endif
    </div>

    <!-- Student Submission Section (Under Assignment) -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-indigo-600"></i> Submit Assignment Solution
                </h2>
                <p class="text-xs text-gray-500">Learners submit their completed project or homework here.</p>
            </div>
            @if($assignment->isPastDeadline())
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                <i class="fa-solid fa-clock-rotate-left"></i> Late Submission Mode
            </span>
            @endif
        </div>

        @if($assignment->isPastDeadline())
        <div class="p-3.5 bg-rose-50 rounded-2xl border border-rose-200 text-xs text-rose-800 flex items-start gap-2.5">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5"></i>
            <div>
                <span class="font-bold">Deadline Notice:</span> The deadline was <strong>{{ $assignment->deadline->format('M d, Y h:i A') }}</strong>. Any submissions made now will be automatically tagged and logged as <span class="font-bold underline">LATE</span> for the instructor.
            </div>
        </div>
        @endif

        <form action="{{ route('admin.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Select Student -->
            <div>
                <label for="user_id" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700">Student Account <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                    <option value="">-- Choose Student --</option>
                    @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ old('user_id', Auth::id()) == $st->id ? 'selected' : '' }}>
                        {{ $st->name }} ({{ $st->email }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Written Solution -->
            <div>
                <label for="submission_text" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700">Written Solution / Code Notes</label>
                <textarea id="submission_text" name="submission_text" rows="4" placeholder="Write or paste your answer, code explanation, or project repository link here..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-mono">{{ old('submission_text') }}</textarea>
            </div>

            <!-- File Upload -->
            <div>
                <label for="submission_file" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-gray-700">Upload Homework File (ZIP, PDF, Code, Docs)</label>
                <input type="file" id="submission_file" name="submission_file"
                    class="block w-full text-xs text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none p-2">
                <p class="mt-1 text-[11px] text-gray-400">Maximum file size: 20MB. Accepted formats: zip, rar, pdf, php, py, js, txt, docx.</p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-paper-plane me-1.5"></i> Submit Homework
                </button>
            </div>
        </form>
    </div>

    <!-- Admin Evaluation & Grading Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-gray-100 gap-2">
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-signature text-purple-600"></i> Student Submissions & Grading
                </h2>
                <p class="text-xs text-gray-500">Review homework files, inspect submission timeliness, and award marks.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-700">
                    {{ $assignment->submissions->count() }} Total
                </span>
                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700">
                    {{ $assignment->submissions->where('status', 'graded')->count() }} Graded
                </span>
                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-50 text-rose-700">
                    {{ $assignment->submissions->where('is_late', true)->count() }} Late
                </span>
            </div>
        </div>

        @if($assignment->submissions->count() > 0)
        <div class="space-y-4">
            @foreach($assignment->submissions as $sub)
            <div class="p-5 rounded-2xl border {{ $sub->is_late ? 'border-rose-200 bg-rose-50/20' : 'border-gray-200 bg-gray-50/40' }} space-y-4">
                
                <!-- Submission Header: Student & Timeliness -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-200/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs border border-indigo-200">
                            {{ strtoupper(substr($sub->user->name ?? 'S', 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-bold text-xs text-gray-900">{{ $sub->user->name ?? 'Unknown Student' }}</div>
                            <span class="text-[11px] text-gray-400">{{ $sub->user->email ?? '—' }}</span>
                        </div>
                    </div>

                    <!-- Late / On-time Indicator & Submitted At -->
                    <div class="flex items-center gap-2">
                        @if($sub->is_late)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-300 shadow-sm animate-pulse">
                            <i class="fa-solid fa-clock-rotate-left"></i> SUBMITTED LATE
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-300">
                            <i class="fa-solid fa-check"></i> ON-TIME
                        </span>
                        @endif

                        <span class="text-xs text-gray-500">
                            Submitted: {{ $sub->submitted_at ? $sub->submitted_at->format('M d, Y h:i A') : $sub->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>
                </div>

                <!-- Solution Content & File -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    
                    <!-- Student Note / Code Text -->
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 block mb-1">Student Answer / Notes</span>
                        @if($sub->submission_text)
                        <div class="bg-white p-3 rounded-xl border border-gray-200 font-mono text-xs text-gray-800 leading-relaxed whitespace-pre-line max-h-48 overflow-y-auto">
                            {{ $sub->submission_text }}
                        </div>
                        @else
                        <span class="text-xs text-gray-400 italic">No written solution text provided.</span>
                        @endif
                    </div>

                    <!-- Attached File -->
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 block mb-1">Submitted File Attachment</span>
                        @if($sub->submission_file)
                        <div class="bg-white p-3 rounded-xl border border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2 truncate">
                                <i class="fa-solid fa-file-code text-indigo-600 text-base"></i>
                                <span class="text-xs font-medium text-gray-900 truncate">{{ basename($sub->submission_file) }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $sub->submission_file) }}" target="_blank" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-lg transition flex items-center gap-1">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                        </div>
                        @else
                        <span class="text-xs text-gray-400 italic">No file attachment uploaded.</span>
                        @endif

                        <!-- Current Grade Pill if graded -->
                        @if($sub->marks_obtained !== null)
                        <div class="mt-3 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                            <span class="text-xs font-semibold text-emerald-800">Score Awarded:</span>
                            <span class="text-sm font-extrabold text-emerald-900">{{ $sub->marks_obtained }} / {{ $assignment->max_marks }} Pts</span>
                        </div>
                        @endif
                    </div>

                </div>

                <!-- Inline Grading & Feedback Form -->
                <div class="pt-3 border-t border-gray-200/60 bg-white p-4 rounded-xl border border-gray-200">
                    <form action="{{ route('admin.assignment-submissions.grade', $sub->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        @csrf
                        @method('PATCH')

                        <!-- Marks Input -->
                        <div class="sm:col-span-3">
                            <label class="block mb-1 text-[11px] font-bold uppercase text-gray-700">Marks (Max: {{ $assignment->max_marks }}) <span class="text-rose-500">*</span></label>
                            <input type="number" name="marks_obtained" value="{{ old('marks_obtained', $sub->marks_obtained) }}" min="0" max="{{ $assignment->max_marks }}" required placeholder="0 - {{ $assignment->max_marks }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 font-bold">
                        </div>

                        <!-- Status Selection -->
                        <div class="sm:col-span-3">
                            <label class="block mb-1 text-[11px] font-bold uppercase text-gray-700">Grading Status</label>
                            <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2 font-medium">
                                <option value="graded" {{ $sub->status === 'graded' ? 'selected' : '' }}>✅ Graded</option>
                                <option value="resubmit_requested" {{ $sub->status === 'resubmit_requested' ? 'selected' : '' }}>🔄 Resubmit Requested</option>
                            </select>
                        </div>

                        <!-- Feedback Note -->
                        <div class="sm:col-span-4">
                            <label class="block mb-1 text-[11px] font-bold uppercase text-gray-700">Instructor Feedback / Remarks</label>
                            <input type="text" name="instructor_feedback" value="{{ old('instructor_feedback', $sub->instructor_feedback) }}" placeholder="e.g. Clean solution, good algorithm approach!"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2">
                        </div>

                        <!-- Save Grade Button -->
                        <div class="sm:col-span-2">
                            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                                <i class="fa-solid fa-check me-1"></i> Save Grade
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-file-circle-question text-3xl mb-2 text-gray-300"></i>
            <p class="text-xs">No homework solutions submitted for this assignment yet.</p>
        </div>
        @endif
    </div>

</div>
@endsection
