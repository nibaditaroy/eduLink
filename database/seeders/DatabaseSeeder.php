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

        // Lesson 1.1 with Multi-Block Architecture (Multiple Videos, Multiple Code Blocks, Images, Tips, Theory)
        $lesson1_1 = Lesson::updateOrCreate(
            [
                'chapter_id' => $chapter1->id,
                'sort_order' => 1,
            ],
            [
                'title' => 'PHP Syntax, Variables & Superglobals',
                'text_content' => "PHP (Hypertext Preprocessor) is a widely-used open-source, server-side scripting language designed specifically for web development.",
                'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                'code_snippet' => "echo 'Hello World';",
                'code_language' => 'php',
                'content_blocks' => [
                    [
                        'type' => 'text',
                        'title' => '1. Theoretical Foundations & Architecture',
                        'content' => "PHP is a server-side scripting language executed on the web server before the HTML is sent to the client browser.\n\nKey Concepts:\n1. Variables start with the dollar sign ($) followed by the variable name.\n2. PHP is dynamically typed; data types are inferred automatically.\n3. Superglobals like \$_GET, \$_POST, and \$_SERVER provide access to request context.",
                    ],
                    [
                        'type' => 'video',
                        'title' => 'Lecture Video 1: Variables & Dynamic Typing',
                        'url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                    ],
                    [
                        'type' => 'code',
                        'title' => 'Code Block 1: Variable Declaration & String Interpolation',
                        'language' => 'php',
                        'code' => "<?php\n\$courseTitle = 'EDULINK University LMS';\n\$enrolledStudents = 120;\n\$rating = 4.9;\n\necho \"Course: {\$courseTitle} - Enrolled: {\$enrolledStudents}\";\n?>",
                        'output' => "Course: EDULINK University LMS - Enrolled: 120",
                    ],
                    [
                        'type' => 'callout',
                        'title' => 'Best Practice Tip',
                        'style' => 'tip',
                        'content' => 'Always use double quotes (" ") when interpolating variables directly inside strings, or single quotes (\' \') for raw static strings to optimize performance.',
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&q=80',
                        'caption' => 'Figure 1.1: Web Request/Response Cycle between Client Browser and PHP Engine',
                    ],
                    [
                        'type' => 'text',
                        'title' => '2. Superglobals & HTTP Requests',
                        'content' => "Superglobals are built-in variables that are always available in all scopes throughout your PHP application.\nCommon superglobals include:\n- \$_GET: Parameters from query strings\n- \$_POST: Data sent via HTTP POST forms\n- \$_SESSION: Persistent session state",
                    ],
                    [
                        'type' => 'code',
                        'title' => 'Code Block 2: Handling Form Submissions Securely',
                        'language' => 'php',
                        'code' => "<?php\nif (\$_SERVER['REQUEST_METHOD'] === 'POST') {\n    \$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);\n    if (\$email) {\n        echo \"Valid student email: \" . htmlspecialchars(\$email);\n    }\n}\n?>",
                        'output' => "Valid student email: student@university.edu",
                    ],
                    [
                        'type' => 'video',
                        'title' => 'Lecture Video 2: Working with Superglobals and Forms',
                        'url' => 'https://www.youtube.com/watch?v=2eebptXfEvw',
                    ],
                    [
                        'type' => 'callout',
                        'title' => 'Security Notice',
                        'style' => 'warning',
                        'content' => 'Never output raw user input from \$_POST or \$_GET directly without escaping via htmlspecialchars() to prevent Cross-Site Scripting (XSS) vulnerabilities.',
                    ],
                ],
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

        // 3. Demo Students (Active, Suspended, Blocked with LMS Progress & Transactions)
        $student1 = User::firstOrCreate(
            ['email' => 'student.nipa@edulink.test'],
            [
                'name' => 'Nipa Roy',
                'phone' => '+8801711223344',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
            ]
        );

        $student2 = User::firstOrCreate(
            ['email' => 'suspended.user@edulink.test'],
            [
                'name' => 'Tanvir Ahmed',
                'phone' => '+8801822334455',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'suspended',
                'status_reason' => 'Multiple failed quiz submissions flagged for review by course instructor.',
            ]
        );

        $student3 = User::firstOrCreate(
            ['email' => 'blocked.user@edulink.test'],
            [
                'name' => 'Sohag Hossain',
                'phone' => '+8801933445566',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'blocked',
                'status_reason' => 'Fraudulent payment dispute on bKash transaction #TXN-BK99201.',
            ]
        );

        // 4. Enrollments & Transactions for student1
        \App\Models\Enrollment::firstOrCreate(
            [
                'user_id' => $student1->id,
                'course_id' => $course1->id,
            ],
            [
                'status' => 'active',
                'progress_percent' => 50,
                'enrolled_at' => now()->subDays(5),
            ]
        );

        \App\Models\Enrollment::firstOrCreate(
            [
                'user_id' => $student1->id,
                'course_id' => $course2->id,
            ],
            [
                'status' => 'active',
                'progress_percent' => 20,
                'enrolled_at' => now()->subDays(2),
            ]
        );

        \App\Models\Transaction::firstOrCreate(
            [
                'transaction_id' => 'TXN-SSL-981203',
            ],
            [
                'user_id' => $student1->id,
                'course_id' => $course2->id,
                'amount' => 4200.00,
                'currency' => 'BDT',
                'payment_method' => 'sslcommerz',
                'card_type' => 'BKASH-BKASH',
                'status' => 'completed',
                'gateway_response' => ['card_type' => 'BKASH', 'status' => 'VALID'],
                'paid_at' => now()->subDays(2),
            ]
        );

        // 5. Lesson Progress & Quiz Attempts for student1
        \App\Models\LessonProgress::firstOrCreate(
            [
                'user_id' => $student1->id,
                'lesson_id' => $lesson1_1->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now()->subDays(3),
            ]
        );

        \App\Models\QuizAttempt::firstOrCreate(
            [
                'user_id' => $student1->id,
                'quiz_id' => $quiz1->id,
            ],
            [
                'total_questions' => 3,
                'correct_answers' => 3,
                'score_percent' => 100.00,
                'is_passed' => true,
                'completed_at' => now()->subDays(2),
            ]
        );

        \App\Models\AssignmentSubmission::firstOrCreate(
            [
                'user_id' => $student1->id,
                'assignment_id' => 1,
            ],
            [
                'submission_text' => 'Here is my complete student grade calculator solution using modular functions.',
                'marks_obtained' => 95,
                'status' => 'graded',
                'instructor_feedback' => 'Exceptional code structure, excellent comments and error handling!',
                'graded_at' => now()->subDay(),
            ]
        );
    }
}
