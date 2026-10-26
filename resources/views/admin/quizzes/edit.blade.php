@extends('admin.layouts.admin')

@section('title', 'Edit Quiz - ' . $quiz->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.show', $quiz->chapter->course_id) }}" class="hover:text-indigo-600">{{ $quiz->chapter->course->title }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Edit Quiz</li>
            </ol>
        </nav>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit MCQ Quiz</h1>
                <p class="text-xs text-gray-500 mt-0.5">Chapter: <strong class="text-indigo-600">{{ $quiz->chapter->title }}</strong></p>
            </div>
            <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="px-3.5 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-xl transition">
                <i class="fa-solid fa-list-check me-1"></i> Manage Questions ({{ $quiz->questions->count() }})
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Quiz Title & Sequence Position -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Quiz Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}" required placeholder="e.g. Assessment: Object-Oriented Principles" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-semibold">
                </div>

                <div>
                    <label for="sort_order" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Position # *</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $quiz->sort_order) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-bold text-center" title="Chronological order in chapter curriculum">
                </div>
            </div>

            <!-- Time Limit & Passing Score -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="time_limit_mins" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Time Limit (Minutes) *</label>
                    <input type="number" id="time_limit_mins" name="time_limit_mins" value="{{ old('time_limit_mins', $quiz->time_limit_mins) }}" required min="1" max="180" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div>
                    <label for="passing_score" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Passing Score (%) *</label>
                    <input type="number" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" required min="1" max="100" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Quiz Instructions</label>
                <textarea id="description" name="description" rows="3" placeholder="Provide test instructions, guidelines, and rules for students..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('description', $quiz->description) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $quiz->chapter->course_id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
