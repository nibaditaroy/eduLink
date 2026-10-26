@extends('admin.layouts.admin')

@section('title', 'Edit Assignment - ' . $assignment->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.show', $assignment->chapter->course_id) }}" class="hover:text-indigo-600">{{ $assignment->chapter->course->title }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Edit Assignment</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Assignment: {{ $assignment->title }}</h1>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Assignment Title & Sequence Position -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Assignment Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $assignment->title) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-semibold">
                </div>

                <div>
                    <label for="sort_order" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Position # *</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $assignment->sort_order) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-bold text-center" title="Chronological order in chapter curriculum">
                </div>
            </div>

            <!-- Max Marks & Deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="max_marks" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Maximum Marks *</label>
                    <input type="number" id="max_marks" name="max_marks" value="{{ old('max_marks', $assignment->max_marks) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div>
                    <label for="deadline" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Submission Deadline</label>
                    <input type="datetime-local" id="deadline" name="deadline" value="{{ old('deadline', $assignment->deadline ? $assignment->deadline->format('Y-m-d\TH:i') : '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>
            </div>

            <!-- Instructions -->
            <div>
                <label for="instructions" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Assignment Instructions & Guidelines *</label>
                <textarea id="instructions" name="instructions" rows="6" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('instructions', $assignment->instructions) }}</textarea>
            </div>

            <!-- Starter Attachment File -->
            <div>
                <label for="attachment_file" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Replace Attachment File</label>
                @if($assignment->attachment_file)
                <div class="text-xs text-indigo-700 mb-1">
                    Current file: <a href="{{ asset('storage/' . $assignment->attachment_file) }}" target="_blank" class="underline font-bold">Download existing attachment</a>
                </div>
                @endif
                <input class="block w-full text-xs text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none p-2.5" id="attachment_file" name="attachment_file" type="file">
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $assignment->chapter->course_id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Update Assignment
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
