@extends('admin.layouts.admin')

@section('title', 'Add Lesson - ' . $chapter->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.show', $chapter->course_id) }}" class="hover:text-indigo-600">{{ $chapter->course->title }}</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Add Lesson</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Add Lesson to Chapter {{ $chapter->sort_order }}</h1>
        <p class="text-xs text-gray-500 mt-0.5">Chapter: <strong class="text-indigo-600">{{ $chapter->title }}</strong></p>
    </div>

    <!-- Lesson Form -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.lessons.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">

            <!-- Sort Order & Lesson Title -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <label for="sort_order" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Lesson # *</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextOrder) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div class="md:col-span-3">
                    <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Lesson Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Understanding Loops and Conditionals in PHP" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>
            </div>

            <!-- Tabbed / Sectional Layout for Multimodal Content -->
            
            <!-- 1. Video Lecture URL -->
            <div class="p-4 rounded-2xl bg-rose-50/40 border border-rose-100 space-y-2">
                <label for="video_url" class="block text-xs font-bold text-rose-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-video text-rose-600"></i> Video Lecture (YouTube / Vimeo / Direct URL)
                </label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." class="bg-white border border-rose-200 text-gray-900 text-sm rounded-xl focus:ring-rose-500 focus:border-rose-500 block w-full p-3">
                <p class="text-[11px] text-rose-700/80">Paste a YouTube or Vimeo link. It will automatically embed in the student viewer.</p>
            </div>

            <!-- 2. Text-Based Explanation -->
            <div>
                <label for="text_content" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-align-left text-indigo-500"></i> Theoretical Explanation & Notes
                </label>
                <textarea id="text_content" name="text_content" rows="6" placeholder="Provide structured concept explanations, syntax definitions, and key takeaways..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-mono text-xs">{{ old('text_content') }}</textarea>
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
                            <option value="php" selected>PHP</option>
                            <option value="javascript">JavaScript</option>
                            <option value="python">Python</option>
                            <option value="html">HTML</option>
                            <option value="sql">SQL</option>
                            <option value="css">CSS</option>
                            <option value="cpp">C++</option>
                            <option value="java">Java</option>
                        </select>
                    </div>
                </div>
                <textarea id="code_snippet" name="code_snippet" rows="6" placeholder="// Enter practical runnable code snippet here...
<?php
$students = ['Alice', 'Bob', 'Charlie'];
foreach ($students as $student) {
    echo 'Hello, ' . $student;
}
?>" class="bg-slate-800 border border-slate-700 text-emerald-400 text-xs font-mono rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('code_snippet') }}</textarea>
            </div>

            <!-- 4. PDF Learning Material Upload -->
            <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-2">
                <label for="pdf_file" class="block text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-file-pdf text-amber-600"></i> Supplementary PDF / Lecture Notes
                </label>
                <input class="block w-full text-xs text-gray-900 border border-amber-300 rounded-xl cursor-pointer bg-white focus:outline-none p-2.5" id="pdf_file" name="pdf_file" type="file" accept=".pdf">
                <p class="text-[11px] text-amber-700">Attach university lecture slides, cheat sheets, or lab manuals (PDF up to 10MB).</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $chapter->course_id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Save Lesson & Return to Course <i class="fa-solid fa-check ms-1"></i>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
