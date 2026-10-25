@extends('admin.layouts.admin')

@section('title', 'Edit Lesson - ' . $lesson->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="hover:text-indigo-600">{{ $lesson->chapter->course->title }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Edit Lesson</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Lesson: {{ $lesson->title }}</h1>
        <p class="text-xs text-gray-500 mt-0.5">Chapter {{ $lesson->chapter->sort_order }}: <strong class="text-indigo-600">{{ $lesson->chapter->title }}</strong></p>
    </div>

    <!-- Lesson Form -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Sort Order & Lesson Title -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <label for="sort_order" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Lesson # *</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $lesson->sort_order) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div class="md:col-span-3">
                    <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Lesson Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>
            </div>

            <!-- 1. Video Lecture URL -->
            <div class="p-4 rounded-2xl bg-rose-50/40 border border-rose-100 space-y-2">
                <label for="video_url" class="block text-xs font-bold text-rose-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-video text-rose-600"></i> Video Lecture (YouTube / Vimeo / Direct URL)
                </label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." class="bg-white border border-rose-200 text-gray-900 text-sm rounded-xl focus:ring-rose-500 focus:border-rose-500 block w-full p-3">
            </div>

            <!-- 2. Text-Based Explanation -->
            <div>
                <label for="text_content" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-align-left text-indigo-500"></i> Theoretical Explanation & Notes
                </label>
                <textarea id="text_content" name="text_content" rows="6" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-mono text-xs">{{ old('text_content', $lesson->text_content) }}</textarea>
            </div>

            <!-- 3. Practical Code Snippet & Language -->
            <div class="p-4 rounded-2xl bg-slate-900 text-white space-y-3">
                <div class="flex items-center justify-between">
                    <label for="code_snippet" class="text-xs font-bold uppercase tracking-wider text-indigo-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-code text-indigo-400"></i> Practical Code Example
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400">Language:</span>
                        <select name="code_language" id="code_language" class="bg-slate-800 border border-slate-700 text-indigo-200 text-xs rounded-lg p-1.5 focus:ring-indigo-500">
                            @foreach(['php', 'javascript', 'python', 'html', 'sql', 'css', 'cpp', 'java'] as $lang)
                            <option value="{{ $lang }}" {{ old('code_language', $lesson->code_language) == $lang ? 'selected' : '' }}>{{ strtoupper($lang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <textarea id="code_snippet" name="code_snippet" rows="6" class="bg-slate-800 border border-slate-700 text-emerald-400 text-xs font-mono rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('code_snippet', $lesson->code_snippet) }}</textarea>
            </div>

            <!-- 4. PDF Learning Material Upload -->
            <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-2">
                <label for="pdf_file" class="block text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-file-pdf text-amber-600"></i> Replace PDF Learning Material
                </label>
                @if($lesson->pdf_file)
                <div class="text-xs text-emerald-700 font-medium mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check"></i> Current PDF: <a href="{{ asset('storage/' . $lesson->pdf_file) }}" target="_blank" class="underline font-bold">View attached PDF</a>
                </div>
                @endif
                <input class="block w-full text-xs text-gray-900 border border-amber-300 rounded-xl cursor-pointer bg-white focus:outline-none p-2.5" id="pdf_file" name="pdf_file" type="file" accept=".pdf">
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Update Lesson
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
