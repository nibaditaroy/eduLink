<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('sort_order')->default(1)->after('lesson_id');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('sort_order')->default(1)->after('lesson_id');
        });

        // Set realistic initial sequential sort_orders for existing quizzes and assignments
        $chapters = DB::table('chapters')->get();
        foreach ($chapters as $chapter) {
            $maxLessonOrder = DB::table('lessons')->where('chapter_id', $chapter->id)->max('sort_order') ?? 0;
            $currentOrder = $maxLessonOrder;

            $quizzes = DB::table('quizzes')->where('chapter_id', $chapter->id)->orderBy('id')->get();
            foreach ($quizzes as $quiz) {
                $currentOrder++;
                DB::table('quizzes')->where('id', $quiz->id)->update(['sort_order' => $currentOrder]);
            }

            $assignments = DB::table('assignments')->where('chapter_id', $chapter->id)->orderBy('id')->get();
            foreach ($assignments as $assignment) {
                $currentOrder++;
                DB::table('assignments')->where('id', $assignment->id)->update(['sort_order' => $currentOrder]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
