@extends('admin.layouts.admin')

@section('title', 'Assignment Details - ' . $assignment->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

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
            <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-pen me-1.5 text-gray-500"></i> Edit
            </a>
            <a href="{{ route('admin.courses.show', $assignment->chapter->course_id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Course
            </a>
        </div>
    </div>

    <!-- Metadata Card -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <span class="text-xs text-gray-500 block">Maximum Marks</span>
                <span class="text-lg font-bold text-gray-900">{{ $assignment->max_marks }} Pts</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <div>
                <span class="text-xs text-gray-500 block">Submission Deadline</span>
                <span class="text-sm font-bold text-gray-900">
                    {{ $assignment->deadline ? $assignment->deadline->format('M d, Y h:i A') : 'No strict deadline' }}
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
                <i class="fa-solid fa-paperclip text-indigo-600 text-sm"></i> Attached Starter Template:
            </div>
            <a href="{{ asset('storage/' . $assignment->attachment_file) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                <i class="fa-solid fa-download me-1.5"></i> Download Attachment
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
