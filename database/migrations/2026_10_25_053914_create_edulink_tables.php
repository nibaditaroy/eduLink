<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Role column on users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('email'); // 'admin', 'student'
            }
        });

        // 2. Courses table
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Computer Science');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->decimal('price', 8, 2)->default(0.00); // 0 = Free
            $table->boolean('is_published')->default(true);
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Chapters table (Under Course)
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(1);
            $table->timestamps();
        });

        // 4. Lessons table (Under Chapter)
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('text_content')->nullable();
            $table->string('video_url')->nullable(); // YouTube / Vimeo / Direct URL
            $table->longText('code_snippet')->nullable();
            $table->string('code_language')->default('php');
            $table->string('pdf_file')->nullable(); // Uploaded learning material
            $table->integer('sort_order')->default(1);
            $table->timestamps();
        });

        // 5. Quizzes table (Under Chapter or Lesson)
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('time_limit_mins')->default(10);
            $table->integer('passing_score')->default(60); // In percent
            $table->timestamps();
        });

        // 6. Quiz Questions (MCQ)
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_option', ['a', 'b', 'c', 'd']);
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        // 7. Assignments table (Under Chapter or Lesson)
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->longText('instructions');
            $table->integer('max_marks')->default(100);
            $table->dateTime('deadline')->nullable();
            $table->string('attachment_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('courses');

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
