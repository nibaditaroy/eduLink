@extends('admin.layouts.admin')

@section('title', 'Add Chapter - ' . $course->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.show', $course->id) }}" class="hover:text-indigo-600">{{ $course->title }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Add Chapter</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Add Chapter to Course</h1>
        <p class="text-xs text-gray-500 mt-0.5">Course: <strong class="text-indigo-600">{{ $course->title }}</strong></p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.chapters.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <!-- Sort Order & Title -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <label for="sort_order" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Chapter # *</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextOrder) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div class="md:col-span-3">
                    <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Chapter Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Introduction to Object-Oriented Programming" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Chapter Summary / Objectives</label>
                <textarea id="description" name="description" rows="3" placeholder="Briefly describe what students will achieve in this chapter..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('description') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $course->id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Save Chapter & Add Lessons <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
