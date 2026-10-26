<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'lesson_id',
        'title',
        'sort_order',
        'instructions',
        'max_marks',
        'deadline',
        'attachment_file',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
