<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'sort_order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order', 'asc');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('sort_order', 'asc');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Compute next sequential item position across all curriculum items in this chapter
     */
    public function nextItemSortOrder(): int
    {
        $maxLesson = $this->lessons()->max('sort_order') ?? 0;
        $maxQuiz = $this->quizzes()->max('sort_order') ?? 0;
        $maxAssignment = $this->assignments()->max('sort_order') ?? 0;

        return max($maxLesson, $maxQuiz, $maxAssignment) + 1;
    }

    /**
     * Get all lessons, quizzes, and assignments merged into a single sequential timeline
     */
    public function curriculumItems()
    {
        $items = collect();

        foreach ($this->lessons as $lesson) {
            $items->push((object)[
                'type' => 'lesson',
                'id' => $lesson->id,
                'title' => $lesson->title,
                'sort_order' => $lesson->sort_order,
                'model' => $lesson,
            ]);
        }

        foreach ($this->quizzes as $quiz) {
            $items->push((object)[
                'type' => 'quiz',
                'id' => $quiz->id,
                'title' => $quiz->title,
                'sort_order' => $quiz->sort_order,
                'model' => $quiz,
            ]);
        }

        foreach ($this->assignments as $assignment) {
            $items->push((object)[
                'type' => 'assignment',
                'id' => $assignment->id,
                'title' => $assignment->title,
                'sort_order' => $assignment->sort_order,
                'model' => $assignment,
            ]);
        }

        return $items->sortBy('sort_order')->values();
    }
}
