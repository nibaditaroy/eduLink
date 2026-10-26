<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'level',
        'price',
        'is_published',
        'thumbnail',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . Str::random(5);
            }
        });
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('sort_order', 'asc');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    public function isFree(): bool
    {
        return (float) $this->price <= 0;
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
