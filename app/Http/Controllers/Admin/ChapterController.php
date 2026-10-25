<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function create(Request $request)
    {
        $courseId = $request->query('course_id');
        $course = Course::findOrFail($courseId);
        $nextOrder = Chapter::where('course_id', $courseId)->max('sort_order') + 1;

        return view('admin.chapters.create', compact('course', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        $chapter = Chapter::create($validated);

        return redirect()->route('admin.courses.show', $chapter->course_id)
            ->with('success', "Chapter '{$chapter->title}' created successfully.");
    }

    public function edit($id)
    {
        $chapter = Chapter::with('course')->findOrFail($id);
        return view('admin.chapters.edit', compact('chapter'));
    }

    public function update(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:1',
        ]);

        $chapter->update($validated);

        return redirect()->route('admin.courses.show', $chapter->course_id)
            ->with('success', "Chapter updated successfully.");
    }

    public function destroy($id)
    {
        $chapter = Chapter::findOrFail($id);
        $courseId = $chapter->course_id;
        $chapter->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Chapter deleted successfully.');
    }
}
