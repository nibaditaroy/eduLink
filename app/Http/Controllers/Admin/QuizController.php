<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function create(Request $request)
    {
        $chapterId = $request->query('chapter_id');
        $lessonId = $request->query('lesson_id');

        $chapter = Chapter::with('course')->findOrFail($chapterId);
        $lesson = $lessonId ? Lesson::find($lessonId) : null;
        $nextOrder = $chapter->nextItemSortOrder();

        return view('admin.quizzes.create', compact('chapter', 'lesson', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'time_limit_mins' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', "Quiz '{$quiz->title}' created! Now add MCQ questions below.");
    }

    public function show($id)
    {
        $quiz = Quiz::with(['chapter.course', 'lesson', 'questions'])->findOrFail($id);
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = Quiz::with('chapter.course')->findOrFail($id);
        return view('admin.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'time_limit_mins' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $quiz->update($validated);
        $courseId = $quiz->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Quiz '{$quiz->title}' updated successfully.");
    }

    public function storeQuestion(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_option' => 'required|in:a,b,c,d',
            'explanation' => 'nullable|string',
        ]);

        $validated['quiz_id'] = $quiz->id;
        QuizQuestion::create($validated);

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'MCQ Question added successfully.');
    }

    public function destroyQuestion($id)
    {
        $question = QuizQuestion::findOrFail($id);
        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.quizzes.show', $quizId)
            ->with('success', 'Question deleted.');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $courseId = $quiz->chapter->course_id;
        $quiz->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Quiz deleted successfully.');
    }
}
