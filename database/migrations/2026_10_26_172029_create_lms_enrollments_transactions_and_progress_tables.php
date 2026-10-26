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
        // 1. Transactions / Payments (SSLCommerz, bKash, Nagad, Cards)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('payment_method')->default('sslcommerz'); // 'sslcommerz', 'free', 'manual'
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('card_type')->nullable(); // 'BKASH-BKASH', 'NAGAD-NAGAD', 'VISA', 'MASTERCARD'
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 2. Course Enrollments
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['active', 'completed', 'suspended', 'cancelled'])->default('active');
            $table->integer('progress_percent')->default(0); // 0 to 100%
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });

        // 3. Lesson Progress (Checkmarks for completed lessons)
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
        });

        // 4. Quiz Attempts (Exam pass/fail, scores)
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->decimal('score_percent', 5, 2)->default(0.00);
            $table->boolean('is_passed')->default(false);
            $table->json('student_answers')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 5. Assignment Submissions (Homework files, grades, feedback)
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->text('submission_text')->nullable();
            $table->string('submission_file')->nullable();
            $table->enum('status', ['submitted', 'graded', 'resubmit_requested'])->default('submitted');
            $table->integer('marks_obtained')->nullable();
            $table->text('instructor_feedback')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('transactions');
    }
};
