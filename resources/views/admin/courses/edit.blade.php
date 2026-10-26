@extends('admin.layouts.admin')

@section('title', 'Edit Course')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div>
        <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.courses.index') }}" class="hover:text-indigo-600">Courses</a></li>
                <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                <li class="font-medium text-gray-900">Edit Course</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Course: {{ $course->title }}</h1>
    </div>

    <!-- Course Form -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Course Title -->
            <div>
                <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
            </div>

            <!-- Two Columns: Category & Level -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Academic Category *</label>
                    <select id="category" name="category" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        @foreach(['Computer Science', 'Software Engineering', 'Information Technology', 'Data Science & AI', 'Cyber Security', 'Web Development'] as $cat)
                        <option value="{{ $cat }}" {{ (old('category', $course->category) == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="level" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Difficulty Level *</label>
                    <select id="level" name="level" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>
            </div>

            <!-- Price & Publish State -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Price (Tk / ৳ BDT) *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-gray-500 font-bold text-xs">
                            Tk
                        </div>
                        <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $course->price) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-11 p-3">
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Enter 0.00 to make this a Free Course (0 Tk).</p>
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Publishing Status</label>
                    <div class="flex items-center mt-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Published and active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Overview</label>
                <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('description', $course->description) }}</textarea>
            </div>

            <!-- What You Will Learn & Build (Key Learning Outcomes) -->
            <div class="bg-indigo-50/50 p-5 rounded-2xl border border-indigo-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-bullseye text-indigo-600"></i> What You Will Learn & Build
                    </label>
                    <button type="button" onclick="addLearningPoint()" class="px-2.5 py-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-white hover:bg-indigo-50 rounded-lg border border-indigo-200 transition flex items-center gap-1 shadow-2xs">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add Point
                    </button>
                </div>
                <p class="text-[11px] text-gray-500 mb-3">Add or edit the competencies, architecture patterns, and practical skills students will master in this track.</p>

                <div id="learning-points-container" class="space-y-2">
                    @php
                        $points = old('what_you_will_learn', $course->what_you_will_learn ?? [
                            'Full grasp of modern industry paradigms and relational schemas.',
                            'In-browser code execution for immediate interactive practice.',
                            'Timed MCQ quizzes with instant scoring and pass/fail badges.',
                            'Production-grade lab projects with instructor grading.'
                        ]);
                    @endphp

                    @foreach($points as $point)
                        <div class="flex items-center gap-2 learning-point-row">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs flex-shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            <input type="text" name="what_you_will_learn[]" value="{{ $point }}" placeholder="e.g. Master asynchronous patterns and API design" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            <button type="button" onclick="this.closest('.learning-point-row').remove()" class="p-2 text-gray-400 hover:text-rose-600 transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Thumbnail Upload -->
            <div>
                <label for="thumbnail" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Change Thumbnail</label>
                @if($course->thumbnail)
                <div class="mb-2 w-32 h-20 rounded-xl overflow-hidden border border-gray-200">
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail" class="w-full h-full object-cover">
                </div>
                @endif
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none p-2.5" id="thumbnail" name="thumbnail" type="file" accept="image/*">
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.show', $course->id) }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Update Course
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function addLearningPoint(val = '') {
    const container = document.getElementById('learning-points-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 learning-point-row';
    div.innerHTML = `
        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs flex-shrink-0">
            <i class="fa-solid fa-check"></i>
        </span>
        <input type="text" name="what_you_will_learn[]" value="${val}" placeholder="e.g. Master asynchronous patterns and API design" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
        <button type="button" onclick="this.closest('.learning-point-row').remove()" class="p-2 text-gray-400 hover:text-rose-600 transition">
            <i class="fa-solid fa-trash-can text-xs"></i>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endsection
