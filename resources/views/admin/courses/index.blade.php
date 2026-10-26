@extends('admin.layouts.admin')

@section('title', 'All Courses')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Courses Management</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage your curriculum directory, pricing, and nested content.</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
            <i class="fa-solid fa-plus me-2"></i> Add New Course
        </a>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">Course Info</th>
                        <th scope="col" class="px-6 py-4">Category & Level</th>
                        <th scope="col" class="px-6 py-4">Price</th>
                        <th scope="col" class="px-6 py-4">Syllabus Structure</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 flex items-center justify-center">
                                    @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    @else
                                    <i class="fa-solid fa-book-open text-indigo-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('admin.courses.show', $course->id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block">
                                        {{ $course->title }}
                                    </a>
                                    <span class="text-xs text-gray-400">Slug: {{ $course->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-gray-900">{{ $course->category }}</div>
                            <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-semibold uppercase rounded-md bg-slate-100 text-slate-700">
                                {{ $course->level }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($course->isFree())
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Free
                            </span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                ৳{{ number_format($course->price, 2) }} Tk
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3 text-xs">
                                <span class="flex items-center gap-1 text-gray-600">
                                    <i class="fa-solid fa-layer-group text-indigo-500"></i> {{ $course->chapters_count }} Ch.
                                </span>
                                <span class="flex items-center gap-1 text-gray-600">
                                    <i class="fa-solid fa-play text-emerald-500"></i> {{ $course->lessons_count }} Lessons
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($course->is_published)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Published
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Draft
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.courses.show', $course->id) }}" title="Manage Curriculum (Chapters & Lessons)" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <i class="fa-solid fa-sitemap"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course->id) }}" title="Edit Details" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" data-confirm-delete="Are you sure you want to delete course &lt;strong&gt;{{ addslashes($course->title) }}&lt;/strong&gt;? This will permanently delete all its chapters, lessons, quizzes, and assignments." data-confirm-title="Delete Course" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Course" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No courses available. Click "Add New Course" above to begin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $courses->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
