<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function create(Request $request)
    {
        $chapterId = $request->query('chapter_id');
        $chapter = Chapter::with('course')->findOrFail($chapterId);
        $nextOrder = Lesson::where('chapter_id', $chapterId)->max('sort_order') + 1;

        return view('admin.lessons.create', compact('chapter', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'text_content' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
            'code_snippet' => 'nullable|string',
            'code_language' => 'nullable|string|max:50',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'sort_order' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('learning_materials', 'public');
            $validated['pdf_file'] = $path;
        }

        $lesson = Lesson::create($validated);
        $courseId = $lesson->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Lesson '{$lesson->title}' added successfully.");
    }

    public function show($id)
    {
        $lesson = Lesson::with(['chapter.course', 'quizzes.questions', 'assignments'])->findOrFail($id);
        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit($id)
    {
        $lesson = Lesson::with('chapter.course')->findOrFail($id);
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text_content' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
            'code_snippet' => 'nullable|string',
            'code_language' => 'nullable|string|max:50',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'sort_order' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('pdf_file')) {
            if ($lesson->pdf_file) {
                Storage::disk('public')->delete($lesson->pdf_file);
            }
            $path = $request->file('pdf_file')->store('learning_materials', 'public');
            $validated['pdf_file'] = $path;
        }

        $lesson->update($validated);
        $courseId = $lesson->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Lesson '{$lesson->title}' updated successfully.");
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $courseId = $lesson->chapter->course_id;

        if ($lesson->pdf_file) {
            Storage::disk('public')->delete($lesson->pdf_file);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Lesson deleted successfully.');
    }
}
