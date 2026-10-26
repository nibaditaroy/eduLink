@extends('admin.layouts.admin')

@section('title', 'Create Course')

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
                <li class="font-medium text-gray-900">Create Course</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create New Course</h1>
        <p class="text-xs text-gray-500 mt-0.5">First step: Set up course details. Afterwards, you will add chapters and lessons.</p>
    </div>

    <!-- Course Form -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Course Title -->
            <div>
                <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Full-Stack Web Development with Laravel & PHP" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
            </div>

            <!-- Two Columns: Category & Level -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Academic Category *</label>
                    <select id="category" name="category" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        <option value="Computer Science">Computer Science & Engineering</option>
                        <option value="Software Engineering">Software Engineering</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Data Science & AI">Data Science & AI</option>
                        <option value="Cyber Security">Cyber Security</option>
                        <option value="Web Development">Web Development</option>
                    </select>
                </div>

                <div>
                    <label for="level" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Difficulty Level *</label>
                    <select id="level" name="level" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
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
                        <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', '0.00') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-11 p-3" placeholder="0.00">
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Enter 0.00 to make this a Free Course (0 Tk).</p>
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Publishing Status</label>
                    <div class="flex items-center mt-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Publish immediately</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Overview & Objectives</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the course curriculum, learning outcomes, and prerequisites..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('description') }}</textarea>
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
                <p class="text-[11px] text-gray-500 mb-3">Add the specific competencies, architecture patterns, and practical skills students will master in this track.</p>

                <div id="learning-points-container" class="space-y-2">
                    <div class="flex items-center gap-2 learning-point-row">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs flex-shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </span>
                        <input type="text" name="what_you_will_learn[]" placeholder="e.g. Full grasp of modern industry paradigms and relational schemas" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        <button type="button" onclick="this.closest('.learning-point-row').remove()" class="p-2 text-gray-400 hover:text-rose-600 transition">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 learning-point-row">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs flex-shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </span>
                        <input type="text" name="what_you_will_learn[]" placeholder="e.g. In-browser code execution for immediate interactive practice" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        <button type="button" onclick="this.closest('.learning-point-row').remove()" class="p-2 text-gray-400 hover:text-rose-600 transition">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 learning-point-row">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs flex-shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </span>
                        <input type="text" name="what_you_will_learn[]" placeholder="e.g. Timed MCQ quizzes with instant scoring and pass/fail badges" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        <button type="button" onclick="this.closest('.learning-point-row').remove()" class="p-2 text-gray-400 hover:text-rose-600 transition">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 learning-point-row">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs flex-shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </span>
                        <input type="text" name="what_you_will_learn[]" placeholder="e.g. Production-grade lab projects with instructor grading" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        <button type="button" onclick="this.closest('.learning-point-row').remove()" class="p-2 text-gray-400 hover:text-rose-600 transition">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Upload -->
            <div>
                <label for="thumbnail" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Thumbnail</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none p-2.5" id="thumbnail" name="thumbnail" type="file" accept="image/*">
                <p class="mt-1 text-[11px] text-gray-500">Supported formats: JPG, PNG, WEBP (Max 2MB).</p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                    Create Course & Continue to Chapters <i class="fa-solid fa-arrow-right ms-1.5"></i>
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
