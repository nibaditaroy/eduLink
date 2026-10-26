<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignment_id',
        'submission_text',
        'submission_file',
        'status',
        'is_late',
        'submitted_at',
        'marks_obtained',
        'instructor_feedback',
        'graded_at',
    ];

    protected $casts = [
        'is_late' => 'boolean',
        'submitted_at' => 'datetime',
        'marks_obtained' => 'integer',
        'graded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
