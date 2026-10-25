<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'text_content',
        'video_url',
        'code_snippet',
        'code_language',
        'pdf_file',
        'sort_order',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Helper to extract YouTube embed URL if applicable
     */
    public function getEmbedVideoUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        // Check if YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $this->video_url;
    }
}
