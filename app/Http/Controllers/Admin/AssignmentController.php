<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function create(Request $request)
    {
        $chapterId = $request->query('chapter_id');
        $lessonId = $request->query('lesson_id');

        $chapter = Chapter::with('course')->findOrFail($chapterId);
        $lesson = $lessonId ? Lesson::find($lessonId) : null;

        return view('admin.assignments.create', compact('chapter', 'lesson'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'max_marks' => 'required|integer|min:1',
            'deadline' => 'nullable|date',
            'attachment_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment_file')) {
            $path = $request->file('attachment_file')->store('assignments', 'public');
            $validated['attachment_file'] = $path;
        }

        $assignment = Assignment::create($validated);
        $courseId = $assignment->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Assignment '{$assignment->title}' created successfully.");
    }

    public function show($id)
    {
        $assignment = Assignment::with(['chapter.course', 'lesson'])->findOrFail($id);
        return view('admin.assignments.show', compact('assignment'));
    }

    public function edit($id)
    {
        $assignment = Assignment::with('chapter.course')->findOrFail($id);
        return view('admin.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'max_marks' => 'required|integer|min:1',
            'deadline' => 'nullable|date',
            'attachment_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment_file')) {
            if ($assignment->attachment_file) {
                Storage::disk('public')->delete($assignment->attachment_file);
            }
            $path = $request->file('attachment_file')->store('assignments', 'public');
            $validated['attachment_file'] = $path;
        }

        $assignment->update($validated);
        $courseId = $assignment->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Assignment updated successfully.");
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $courseId = $assignment->chapter->course_id;

        if ($assignment->attachment_file) {
            Storage::disk('public')->delete($assignment->attachment_file);
        }

        $assignment->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Assignment deleted successfully.');
    }
}
