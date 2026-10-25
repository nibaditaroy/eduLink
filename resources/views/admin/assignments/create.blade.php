@extends('admin.layouts.admin')

@section('title', 'Create Assignment - ' . $chapter->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.show', $chapter->course_id) }}" class="hover:text-indigo-600">{{ $chapter->course->title }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Create Assignment</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create Practical Assignment</h1>
        <p class="text-xs text-gray-500 mt-0.5">Chapter: <strong class="text-indigo-600">{{ $chapter->title }}</strong></p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.assignments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
            @if($lesson)
            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
            @endif

            <!-- Assignment Title -->
            <div>
                <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Assignment Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Lab Task: Build a Student Grade Calculator in PHP" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
            </div>

            <!-- Max Marks & Deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="max_marks" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Maximum Marks *</label>
                    <input type="number" id="max_marks" name="max_marks" value="{{ old('max_marks', 100) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div>
                    <label for="deadline" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Submission Deadline</label>
                    <input type="datetime-local" id="deadline" name="deadline" value="{{ old('deadline') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Assignment Instructions & Guidelines *</label>
                <textarea id="instructions" name="instructions" rows="6" required placeholder="Write clear problem statement, requirements, input/output formats, and submission criteria..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('instructions') }}</textarea>
            </div>

            <!-- Starter Attachment File -->
            <div>
                <label for="attachment_file" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Starter File / Lab Sheet (Optional)</label>
                <input class="block w-full text-xs text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none p-2.5" id="attachment_file" name="attachment_file" type="file">
                <p class="text-[11px] text-gray-400 mt-1">Attach project template, PDF lab instructions, or starter code (up to 10MB).</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $chapter->course_id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Create Assignment <i class="fa-solid fa-check ms-1"></i>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
