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
        'content_blocks',
        'sort_order',
    ];

    protected $casts = [
        'content_blocks' => 'array',
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
     * Helper to extract YouTube/Vimeo embed URL if applicable
     */
    public static function getEmbedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        // YouTube regex (supports youtu.be, youtube.com/watch?v=, youtube.com/embed/)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Vimeo regex
        if (preg_match('/(?:vimeo\.com\/)(\d+)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        return $url;
    }

    public function getEmbedVideoUrlAttribute(): ?string
    {
        return self::getEmbedUrl($this->video_url);
    }
}
