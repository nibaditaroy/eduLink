<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@edulink.test'],
            [
                'name' => 'Prof. Admin (EDULINK)',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Sample Course 1: Full-Stack Web Development
        $course1 = Course::firstOrCreate(
            ['slug' => 'full-stack-web-development-php-laravel'],
            [
                'title' => 'Full-Stack Web Development with Laravel & PHP',
                'category' => 'Software Engineering',
                'level' => 'beginner',
                'price' => 0.00, // Free
                'is_published' => true,
                'description' => 'A comprehensive university-level course covering PHP modern syntax, relational database modeling with MySQL, and building scalable MVC web platforms using Laravel and Flowbite.',
            ]
        );

        // Chapter 1
        $chapter1 = Chapter::firstOrCreate(
            [
                'course_id' => $course1->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'PHP Fundamentals & Web Architecture',
                'description' => 'Master PHP syntax, data types, control flow, functions, and the request-response lifecycle.',
            ]
        );

        // Lesson 1.1
        Lesson::firstOrCreate(
            [
                'chapter_id' => $chapter1->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'PHP Syntax, Variables & Superglobals',
                'text_content' => "PHP (Hypertext Preprocessor) is a widely-used open-source, server-side scripting language designed specifically for web development.\n\nKey Concepts:\n1. Variables start with the dollar sign ($) followed by the variable name.\n2. PHP is weakly typed; variable types are dynamically evaluated.\n3. Superglobal variables like \$_GET, \$_POST, and \$_SERVER provide access to HTTP requests.\n4. Concatenation is done using the dot (.) operator.",
                'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                'code_snippet' => "<?php
// Declare variables
\$course = 'EDULINK Web Engineering';
\$enrolledStudents = 45;
\$isPublished = true;

// Output with concatenation
echo \"Course: \" . \$course . \" (Enrolled: \" . \$enrolledStudents . \")\";
?>",
                'code_language' => 'php',
            ]
        );

        // Lesson 1.2
        Lesson::firstOrCreate(
            [
                'chapter_id' => $chapter1->id,
                'sort_order' => 2,
            ],
            [
                'title' => 'Control Structures: Conditionals & Iteration',
                'text_content' => "Control structures dictate how instructions are executed based on logical conditions.\n\n1. If-Else statements evaluate boolean expressions.\n2. The switch and match statements provide multi-way branching.\n3. Foreach loops iterate cleanly over arrays and collections.",
                'video_url' => 'https://www.youtube.com/watch?v=2eebptXfEvw',
                'code_snippet' => "<?php
\$scores = [85, 92, 78, 64, 99];
foreach (\$scores as \$score) {
    if (\$score >= 80) {
        echo \"Grade A: \$score\\n\";
    } else {
        echo \"Grade Pass: \$score\\n\";
    }
}
?>",
                'code_language' => 'php',
            ]
        );

        // Quiz for Chapter 1
        $quiz1 = Quiz::firstOrCreate(
            [
                'chapter_id' => $chapter1->id,
                'title' => 'Assessment: PHP Syntax & Control Flow',
            ],
            [
                'description' => 'Test your core understanding of basic PHP syntax, data structures, and conditional operators.',
                'time_limit_mins' => 10,
                'passing_score' => 60,
            ]
        );

        // Quiz Questions
        QuizQuestion::firstOrCreate(
            [
                'quiz_id' => $quiz1->id,
                'question' => 'Which character is used to denote a variable name in PHP?',
            ],
            [
                'option_a' => '&',
                'option_b' => '$',
                'option_c' => '#',
                'option_d' => '@',
                'correct_option' => 'b',
                'explanation' => 'All variable identifiers in PHP must begin with the $ sign.',
            ]
        );

        QuizQuestion::firstOrCreate(
            [
                'quiz_id' => $quiz1->id,
                'question' => 'Which operator is used for string concatenation in PHP?',
            ],
            [
                'option_a' => '+',
                'option_b' => '&',
                'option_c' => '.',
                'option_d' => '||',
                'correct_option' => 'c',
                'explanation' => 'The dot (.) operator concatenates two or more strings in PHP.',
            ]
        );

        QuizQuestion::firstOrCreate(
            [
                'quiz_id' => $quiz1->id,
                'question' => 'Which superglobal variable contains data submitted via HTTP POST?',
            ],
            [
                'option_a' => '$_GET',
                'option_b' => '$_REQUEST_BODY',
                'option_c' => '$_SUBMIT',
                'option_d' => '$_POST',
                'correct_option' => 'd',
                'explanation' => '$_POST is an associative array of variables passed to the current script via the HTTP POST method.',
            ]
        );

        // Assignment for Chapter 1
        Assignment::firstOrCreate(
            [
                'chapter_id' => $chapter1->id,
                'title' => 'Lab Task 1: Building a Student Grade Calculator in PHP',
            ],
            [
                'instructions' => "Objective:\nDevelop a PHP script that accepts an array of student marks and computes:\n1. The individual letter grade for each student (A: >=80, B: >=70, C: >=60, F: <60).\n2. The class average.\n3. The highest and lowest scorer.\n\nDeliverable:\nSubmit a clean .php file implementing the logic with proper functions and comments.",
                'max_marks' => 100,
                'deadline' => now()->addDays(7),
            ]
        );

        // Chapter 2
        $chapter2 = Chapter::firstOrCreate(
            [
                'course_id' => $course1->id,
                'sort_order' => 2,
            ],
            [
                'title' => 'Relational Database Modeling with MySQL',
                'description' => 'Learn database normalization, foreign keys, table joins, and SQL query optimization.',
            ]
        );

        Lesson::firstOrCreate(
            [
                'chapter_id' => $chapter2->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'Designing 3NF Schemas & Foreign Keys',
                'text_content' => "Relational schema design requires adhering to Normal Forms (1NF, 2NF, 3NF) to eliminate data redundancy and prevent update/delete anomalies.\n\nForeign keys enforce referential integrity between parent and child tables (e.g., courses.id -> chapters.course_id).",
                'video_url' => 'https://www.youtube.com/watch?v=7S_tz1z_5bA',
                'code_snippet' => "CREATE TABLE courses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(8,2) DEFAULT 0.00
);

CREATE TABLE chapters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);",
                'code_language' => 'sql',
            ]
        );

        // Sample Course 2: Paid Course
        $course2 = Course::firstOrCreate(
            ['slug' => 'data-structures-algorithms-python'],
            [
                'title' => 'Data Structures & Algorithms in Python',
                'category' => 'Computer Science',
                'level' => 'intermediate',
                'price' => 39.99, // Paid
                'is_published' => true,
                'description' => 'Master time and space complexity, binary search trees, graph traversals, and dynamic programming with Python 3.',
            ]
        );

        $course2Ch1 = Chapter::firstOrCreate(
            [
                'course_id' => $course2->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'Asymptotic Notation & Array Operations',
                'description' => 'Big-O notation, worst-case and amortized time analysis of array lookups and insertions.',
            ]
        );

        Lesson::firstOrCreate(
            [
                'chapter_id' => $course2Ch1->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'Big-O Notation & Amortized Cost',
                'text_content' => "Big-O notation describes the upper bound of an algorithm's growth rate in terms of input size (n). Common complexities include O(1), O(log n), O(n), O(n log n), and O(n^2).",
                'video_url' => 'https://www.youtube.com/watch?v=D6xkbGLQesk',
                'code_snippet' => "def binary_search(arr, target):
    left, right = 0, len(arr) - 1
    while left <= right:
        mid = (left + right) // 2
        if arr[mid] == target:
            return mid
        elif arr[mid] < target:
            left = mid + 1
        else:
            right = mid - 1
    return -1",
                'code_language' => 'python',
            ]
        );
    }
}
