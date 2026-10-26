<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed application with clean data:
     * - Only 2 users: admin@gmail.com & student@gmail.com
     * - Only 2 beginner courses: Python for Beginners & PHP for Beginners
     * - Each course has 6 structured chapters with lessons, interactive code labs, quizzes, and assignments.
     */
    public function run(): void
    {
        // Disable foreign keys and truncate existing tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lesson_progress')->truncate();
        DB::table('enrollments')->truncate();
        DB::table('transactions')->truncate();
        DB::table('quiz_attempts')->truncate();
        DB::table('assignment_submissions')->truncate();
        DB::table('quiz_questions')->truncate();
        DB::table('quizzes')->truncate();
        DB::table('assignments')->truncate();
        DB::table('lessons')->truncate();
        DB::table('chapters')->truncate();
        DB::table('courses')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // -------------------------------------------------------------
        // 1. Create Only 2 Demo Users: Admin and Student
        // -------------------------------------------------------------
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'phone' => '+8801711000001',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@gmail.com',
            'phone' => '+8801711000002',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        // -------------------------------------------------------------
        // 2. Course 1: Python for Beginners (6 Chapters)
        // -------------------------------------------------------------
        $pythonCourse = Course::create([
            'title' => 'Python Programming for Beginners',
            'slug' => 'python-for-beginners',
            'category' => 'Python & Data Structures',
            'level' => 'beginner',
            'price' => 2500.00,
            'is_published' => true,
            'description' => 'Master the fundamentals of Python 3 from the ground up. Learn clean syntax, control flow, built-in data structures, modular functions, object-oriented programming, and file automation with live interactive code sandboxes.',
            'what_you_will_learn' => [
                'Python 3 fundamentals, dynamic variable types, and arithmetic logic.',
                'Decision control with if-elif-else statements and iterative loops.',
                'Core collections: Lists, Dictionaries, Tuples, and Sets.',
                'Modular programming with functions, parameters, and lambda expressions.',
                'Object-Oriented Programming (OOP): Classes, constructors, and inheritance.',
                'Exception handling and building automated file processing CLI scripts.'
            ]
        ]);

        $pythonCurriculum = [
            [
                'title' => 'Introduction to Python & Syntax',
                'description' => 'Install Python, understand interpreted scripting, and write your first clean Python scripts.',
                'lessons' => [
                    [
                        'title' => 'Python Architecture & Your First Script',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "# Welcome to Python for Beginners!\nplatform_name = 'eduLink LMS'\nlearner_level = 'Beginner'\nprint(f'Hello from {platform_name}! Starting your journey at {learner_level} level.')\n\n# Calculate basic metrics\ncourses_completed = 0\ntarget_courses = 2\nprint(f'Progress: {courses_completed}/{target_courses} tracks.')",
                        'text_content' => "Welcome to Python Programming for Beginners!\n\nPython is an interpreted, high-level, general-purpose programming language known for its clean syntax and readability.\n\nKey Concepts:\n1. Indentation matters: Python uses indentation to define code blocks instead of curly braces.\n2. Dynamic Typing: Variables do not require explicit type declaration.\n3. The print() function writes formatted text directly to stdout.",
                    ],
                    [
                        'title' => 'Variables, Data Types & Console Input',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "item_name = 'Python Essentials Guide'\nprice_bdt = 1250\nquantity = 2\nis_digital = True\n\ntotal_due = price_bdt * quantity\nprint(f'Order for: {item_name}')\nprint(f'Total Due: ৳{total_due} Tk | Digital Delivery: {is_digital}')",
                        'text_content' => "In Python, variables store references to values in memory.\n\nCore Data Types:\n- int: Integer numbers (e.g. 42)\n- float: Floating point numbers with decimals (e.g. 19.99)\n- str: Text characters enclosed in quotes (e.g. 'Dhaka')\n- bool: Boolean truth values (True or False)",
                    ]
                ],
                'quiz' => [
                    'title' => 'Python Basics & Syntax Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'How does Python define blocks of code like functions and loops?',
                            'option_a' => 'Curly braces {}',
                            'option_b' => 'Whitespace indentation',
                            'option_c' => 'Parentheses ()',
                            'option_d' => 'Semicolons ;',
                            'correct_option' => 'b',
                            'explanation' => 'Python relies strictly on whitespace indentation to define code blocks.'
                        ],
                        [
                            'question' => 'Which keyword is used to print output to the terminal in Python 3?',
                            'option_a' => 'echo',
                            'option_b' => 'console.log',
                            'option_c' => 'print',
                            'option_d' => 'System.out.println',
                            'correct_option' => 'c',
                            'explanation' => 'The print() function outputs strings and expressions to stdout.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 1: Temperature & Currency Converter CLI',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(7),
                    'instructions' => "Objective: Build a Python script that:\n1. Converts Celsius to Fahrenheit: F = (C * 9/5) + 32.\n2. Converts USD to Bangladeshi Taka (BDT) using an exchange rate of 1 USD = 120 BDT.\n3. Formats and prints the outputs clearly with 2 decimal places.",
                ]
            ],
            [
                'title' => 'Control Flow & Iterative Logic',
                'description' => 'Master logical decisions with if-elif-else and automate repeated tasks with loops.',
                'lessons' => [
                    [
                        'title' => 'Conditional Branching (if, elif, else)',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "score = 85\n\nif score >= 90:\n    grade = 'A+'\nelif score >= 80:\n    grade = 'A'\nelif score >= 70:\n    grade = 'B'\nelse:\n    grade = 'Pass'\n\nprint(f'Exam Score: {score}% -> Grade: {grade}')",
                        'text_content' => "Conditionals evaluate boolean expressions to branch code execution.\n\nRelational Operators:\n== (equal), != (not equal), < (less than), > (greater than), <=, >=.\nLogical Operators: and, or, not.",
                    ],
                    [
                        'title' => 'For & While Loops with Range',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "# Print multiplication table for 5\nfactor = 5\nprint(f'Multiplication Table for {factor}:')\nfor i in range(1, 11):\n    print(f'{factor} x {i} = {factor * i}')",
                        'text_content' => "Loops allow repeated execution over sequences.\n\n- The for loop iterates over sequences (range, lists, strings).\n- The while loop runs as long as a condition evaluates to True.\n- break stops the loop; continue skips to the next iteration.",
                    ]
                ],
                'quiz' => [
                    'title' => 'Decision Trees & Loops Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'What does range(1, 5) produce in Python?',
                            'option_a' => '[1, 2, 3, 4, 5]',
                            'option_b' => '[1, 2, 3, 4]',
                            'option_c' => '[0, 1, 2, 3, 4]',
                            'option_d' => '[2, 3, 4, 5]',
                            'correct_option' => 'b',
                            'explanation' => 'range(start, stop) includes start but excludes stop.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 2: Prime Number Checker & Factorial Calculator',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(14),
                    'instructions' => "Create a Python script that takes an integer and:\n1. Determines whether it is a prime number.\n2. Computes its factorial using a while loop.\n3. Prints the results with clean status messages.",
                ]
            ],
            [
                'title' => 'Essential Python Data Structures',
                'description' => 'Store and organize structured data using Lists, Tuples, Dictionaries, and Sets.',
                'lessons' => [
                    [
                        'title' => 'Lists, Slicing & Mutability',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "fruits = ['Apple', 'Banana', 'Mango', 'Jackfruit']\nfruits.append('Orange')\n\nprint(f'First 3 items: {fruits[:3]}')\nprint(f'Total count: {len(fruits)}')\n\nfor idx, item in enumerate(fruits, start=1):\n    print(f'{idx}. {item}')",
                        'text_content' => "Lists are ordered, mutable collections of items.\n\nCommon list operations:\n- .append(item): Adds item to end\n- .pop(): Removes and returns last element\n- .sort(): Sorts in-place\n- Slicing: list[start:stop:step]",
                    ],
                    [
                        'title' => 'Dictionaries & Key-Value Lookups',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "student_profile = {\n    'name': 'Kazi Naima',\n    'course': 'Python for Beginners',\n    'enrolled_bdt': 2500,\n    'completed_labs': 3\n}\n\n# Accessing values safely\nprint(f'Student: {student_profile[\"name\"]}')\nprint(f'Enrolled Fee: ৳{student_profile.get(\"enrolled_bdt\")} Tk')",
                        'text_content' => "Dictionaries store key-value pairs with O(1) average lookup time.\n\nKeys must be immutable (strings, numbers, tuples). Values can be any data type.",
                    ]
                ],
                'quiz' => [
                    'title' => 'Collections & Lookups Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which Python collection type is defined with key-value pairs?',
                            'option_a' => 'List',
                            'option_b' => 'Tuple',
                            'option_c' => 'Dictionary',
                            'option_d' => 'Set',
                            'correct_option' => 'c',
                            'explanation' => 'Dictionaries map unique keys to values using curly braces {key: value}.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 3: Student Gradebook & Report Card System',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(21),
                    'instructions' => "Implement a gradebook manager storing a list of student dictionaries.\nEach student has a name and 3 subject marks. Calculate total marks, average percentage, and assign GPA letters.",
                ]
            ],
            [
                'title' => 'Modular Programming with Functions',
                'description' => 'Write clean, reusable functions with parameters, default arguments, and return values.',
                'lessons' => [
                    [
                        'title' => 'Defining Functions & Return Values',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "def calculate_discount(price, discount_percent=10):\n    discount = price * (discount_percent / 100)\n    final_price = price - discount\n    return final_price, discount\n\nprice = 2500\nfinal_cost, saved = calculate_discount(price, 15)\nprint(f'Original: ৳{price} | Final: ৳{final_cost} (Saved ৳{saved})')",
                        'text_content' => "Functions promote code reusability and modularity.\n\nSyntax:\ndef function_name(param1, param2=default):\n    # logic\n    return result",
                    ]
                ],
                'quiz' => [
                    'title' => 'Functions & Scope Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which keyword defines a function in Python?',
                            'option_a' => 'function',
                            'option_b' => 'func',
                            'option_c' => 'def',
                            'option_d' => 'sub',
                            'correct_option' => 'c',
                            'explanation' => 'The def keyword defines a user-created function in Python.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 4: Word Frequency & Text Analyzer',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(28),
                    'instructions' => "Write a Python function analyze_text(paragraph) that counts total words, unique words, and outputs the top 3 most frequent words.",
                ]
            ],
            [
                'title' => 'Object-Oriented Programming (OOP)',
                'description' => 'Structure real-world software systems using Classes, Objects, Methods, and Inheritance.',
                'lessons' => [
                    [
                        'title' => 'Classes, Constructors & Attributes',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "class BankAccount:\n    def __init__(self, owner, balance=0):\n        self.owner = owner\n        self.balance = balance\n\n    def deposit(self, amount):\n        self.balance += amount\n        print(f'Deposited ৳{amount} Tk. New Balance: ৳{self.balance} Tk')\n\nacc = BankAccount('Rahim', 5000)\nacc.deposit(1500)",
                        'text_content' => "OOP models software around objects rather than functions alone.\n\n- The __init__() method is the class constructor.\n- self refers to the instance of the object being manipulated.\n- Encapsulation bundles data and methods together.",
                    ]
                ],
                'quiz' => [
                    'title' => 'OOP Principles Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'What is the role of self in Python class methods?',
                            'option_a' => 'It creates a static variable',
                            'option_b' => 'It refers to the current instance of the class',
                            'option_c' => 'It imports the parent module',
                            'option_d' => 'It is optional and never required',
                            'correct_option' => 'b',
                            'explanation' => 'self represents the instance of the class allowing access to its attributes and methods.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 5: Bank Account & Transaction System',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(35),
                    'instructions' => "Build a BankAccount class with deposit, withdraw, and check_balance methods. Prevent withdrawals if balance is insufficient.",
                ]
            ],
            [
                'title' => 'File Handling, Error Handling & Capstone',
                'description' => 'Prevent crashes with Exception handling and build a complete capstone CLI project.',
                'lessons' => [
                    [
                        'title' => 'Exception Handling with try-except',
                        'video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                        'code_language' => 'python',
                        'code_snippet' => "def safe_divide(a, b):\n    try:\n        return a / b\n    except ZeroDivisionError:\n        return 'Error: Cannot divide by zero'\n\nprint(safe_divide(10, 2))\nprint(safe_divide(10, 0))",
                        'text_content' => "Exceptions handle runtime errors gracefully without terminating the program.\n\nStructure:\ntry:\n    # risky code\nexcept ExceptionType as e:\n    # handle error\nfinally:\n    # always runs",
                    ]
                ],
                'quiz' => [
                    'title' => 'Capstone Readiness Quiz',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which block in Python always executes regardless of whether an exception occurred?',
                            'option_a' => 'try',
                            'option_b' => 'except',
                            'option_c' => 'finally',
                            'option_d' => 'catch',
                            'correct_option' => 'c',
                            'explanation' => 'The finally block always executes to release resources or perform cleanup.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Capstone Project: Personal Expense Tracker CLI',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(42),
                    'instructions' => "Create an end-to-end Python CLI tool allowing users to add expenses (title, category, amount in BDT), calculate total spending, and display category breakdown.",
                ]
            ]
        ];

        foreach ($pythonCurriculum as $cIndex => $cData) {
            $chapter = Chapter::create([
                'course_id' => $pythonCourse->id,
                'title' => $cData['title'],
                'description' => $cData['description'],
                'sort_order' => $cIndex + 1,
            ]);

            foreach ($cData['lessons'] as $lIndex => $lData) {
                Lesson::create([
                    'chapter_id' => $chapter->id,
                    'title' => $lData['title'],
                    'video_url' => $lData['video_url'],
                    'code_language' => $lData['code_language'],
                    'code_snippet' => $lData['code_snippet'],
                    'text_content' => $lData['text_content'],
                    'sort_order' => $lIndex + 1,
                ]);
            }

            if (!empty($cData['quiz'])) {
                $q = Quiz::create([
                    'chapter_id' => $chapter->id,
                    'title' => $cData['quiz']['title'],
                    'time_limit_mins' => $cData['quiz']['time_limit_mins'],
                    'passing_score' => $cData['quiz']['passing_score'],
                    'sort_order' => 1,
                ]);

                foreach ($cData['quiz']['questions'] as $question) {
                    QuizQuestion::create([
                        'quiz_id' => $q->id,
                        'question' => $question['question'],
                        'option_a' => $question['option_a'],
                        'option_b' => $question['option_b'],
                        'option_c' => $question['option_c'],
                        'option_d' => $question['option_d'],
                        'correct_option' => $question['correct_option'],
                        'explanation' => $question['explanation'],
                    ]);
                }
            }

            if (!empty($cData['assignment'])) {
                Assignment::create([
                    'chapter_id' => $chapter->id,
                    'title' => $cData['assignment']['title'],
                    'max_marks' => $cData['assignment']['max_marks'],
                    'deadline' => $cData['assignment']['deadline'],
                    'instructions' => $cData['assignment']['instructions'],
                    'sort_order' => 1,
                ]);
            }
        }

        // -------------------------------------------------------------
        // 3. Course 2: PHP for Beginners (6 Chapters)
        // -------------------------------------------------------------
        $phpCourse = Course::create([
            'title' => 'PHP & Modern Web Development for Beginners',
            'slug' => 'php-for-beginners',
            'category' => 'Web Development & PHP',
            'level' => 'beginner',
            'price' => 3000.00,
            'is_published' => true,
            'description' => 'Learn modern PHP 8+ from zero to building full-stack dynamic web applications. Covers variables, associative arrays, form processing, session security, object-oriented architecture, and PDO database connectivity.',
            'what_you_will_learn' => [
                'Modern PHP 8+ language fundamentals, syntax, and server-side request cycles.',
                'Control flow with match expressions, foreach loops, and custom functions.',
                'Working with indexed, associative, and multidimensional arrays.',
                'Processing HTML forms securely with data validation and password hashing.',
                'Object-Oriented PHP: Classes, namespaces, and visibility modifiers.',
                'Relational database integration using PDO, prepared queries, and CRUD patterns.'
            ]
        ]);

        $phpCurriculum = [
            [
                'title' => 'PHP Basics & Web Execution Model',
                'description' => 'Understand the server-side request-response cycle and write fundamental PHP scripts.',
                'lessons' => [
                    [
                        'title' => 'Modern PHP Architecture & Hello World',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\n\$platform = 'eduLink Bangladesh';\n\$track = 'PHP Web Development';\n\$price_bdt = 3000;\n\necho \"Welcome to {\$platform}!\";\necho \"\\nEnrolled in: {\$track}\";\necho \"\\nCourse Fee: ৳\" . number_format(\$price_bdt, 0) . \" Tk\";\n?>",
                        'text_content' => "PHP (Hypertext Preprocessor) is an open-source, server-side scripting language designed for web development.\n\nKey Concepts:\n1. PHP scripts start with <?php.\n2. Variables always begin with a dollar sign ($).\n3. Statements conclude with a semicolon (;).\n4. Double-quoted strings support variable interpolation.",
                    ],
                    [
                        'title' => 'Variables, Data Types & Concatenation',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\n\$courseName = 'PHP for Beginners';\n\$rating = 4.95;\n\$isPublished = true;\n\necho 'Course: ' . \$courseName . ' | Rating: ' . \$rating;\n?>",
                        'text_content' => "PHP is dynamically typed; data types are inferred automatically.\n\nScalar Types:\n- int: Integer numbers (e.g. 100)\n- float: Floating point numbers (e.g. 99.50)\n- string: Text (e.g. 'Dhaka')\n- bool: Booleans (true or false)\n\nThe dot operator (.) concatenates strings.",
                    ]
                ],
                'quiz' => [
                    'title' => 'PHP Basics & Syntax Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which character must all PHP variables begin with?',
                            'option_a' => '@',
                            'option_b' => '&',
                            'option_c' => '$',
                            'option_d' => '#',
                            'correct_option' => 'c',
                            'explanation' => 'All variables in PHP must begin with the $ dollar sign.'
                        ],
                        [
                            'question' => 'Which operator is used to concatenate two strings in PHP?',
                            'option_a' => '+',
                            'option_b' => '.',
                            'option_c' => '&',
                            'option_d' => '->',
                            'correct_option' => 'b',
                            'explanation' => 'PHP uses the period (.) dot operator to concatenate strings.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 1: Salary & Tax Calculator Script',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(7),
                    'instructions' => "Build a PHP script that calculates a monthly net salary:\n1. Base salary = ৳50,000 Tk.\n2. Add 10% house rent allowance and 5% medical allowance.\n3. Deduct 5% tax and print the final net payout in BDT.",
                ]
            ],
            [
                'title' => 'Control Structures & Loops',
                'description' => 'Master logical decisions with if-else and modern match expressions, and automate iterations with foreach.',
                'lessons' => [
                    [
                        'title' => 'Conditionals & Match Expressions in PHP 8',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\n\$role = 'admin';\n\n\$permission = match(\$role) {\n    'admin' => 'Full Administrative Access',\n    'instructor' => 'Manage Assigned Courses',\n    'student' => 'Access Learning Portal',\n    default => 'Guest Access Only'\n};\n\necho \"Role: {\$role} -> {\$permission}\";\n?>",
                        'text_content' => "Conditionals direct program flow based on conditions.\n\nPHP 8 introduced the match expression:\n- Strict type comparison (===)\n- Returns a value directly\n- Cleaner syntax than traditional switch statements",
                    ]
                ],
                'quiz' => [
                    'title' => 'Control Flow Mastery',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'What is the advantage of match() over switch() in PHP 8?',
                            'option_a' => 'match uses strict type comparison and returns a value directly',
                            'option_b' => 'match does not require a closing brace',
                            'option_c' => 'match only works with integers',
                            'option_d' => 'match is slower but uses less memory',
                            'correct_option' => 'a',
                            'explanation' => 'match() performs strict type checking (===) and returns a value without needing break statements.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 2: Dynamic Multiplication & Pattern Printer',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(14),
                    'instructions' => "Write a PHP script using nested for loops to print a multiplication grid from 1 to 10 with formatted columns.",
                ]
            ],
            [
                'title' => 'Arrays & String Operations',
                'description' => 'Organize datasets with indexed, associative, and multidimensional arrays.',
                'lessons' => [
                    [
                        'title' => 'Indexed & Associative Arrays in PHP',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\n\$courseInfo = [\n    'title' => 'PHP for Beginners',\n    'instructor' => 'Kazi Naima Hasan',\n    'price_bdt' => 3000,\n    'topics' => ['Syntax', 'Arrays', 'OOP', 'PDO']\n];\n\necho \"Course: \" . \$courseInfo['title'];\necho \"\\nInstructor: \" . \$courseInfo['instructor'];\necho \"\\nFirst Topic: \" . \$courseInfo['topics'][0];\n?>",
                        'text_content' => "Arrays in PHP are versatile ordered maps that can function as lists, dictionaries, or stacks.\n\nIndexed arrays use integer keys: [10, 20, 30]\nAssociative arrays use named keys: ['key' => 'value']",
                    ]
                ],
                'quiz' => [
                    'title' => 'PHP Arrays Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which function counts the number of elements in a PHP array?',
                            'option_a' => 'size()',
                            'option_b' => 'count()',
                            'option_c' => 'length()',
                            'option_d' => 'array_size()',
                            'correct_option' => 'b',
                            'explanation' => 'count() returns the number of elements in an array or countable object.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 3: Product Inventory Filtering Engine',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(21),
                    'instructions' => "Create an array of 5 products with id, name, price, and category. Use array_filter to return all items priced under ৳2,000 Tk.",
                ]
            ],
            [
                'title' => 'Form Handling & Web Security',
                'description' => 'Handle GET/POST HTTP submissions, validate input data, and prevent XSS vulnerabilities.',
                'lessons' => [
                    [
                        'title' => 'Handling POST Requests Safely',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\n\$rawEmail = '  student@gmail.com  ';\n\$cleanEmail = filter_var(trim(\$rawEmail), FILTER_VALIDATE_EMAIL);\n\nif (\$cleanEmail) {\n    echo \"Valid email sanitised: {\$cleanEmail}\";\n} else {\n    echo \"Invalid email address provided.\";\n}\n?>",
                        'text_content' => "Never trust user input.\n\nSecurity Essentials:\n1. htmlspecialchars(): Prevents Cross-Site Scripting (XSS).\n2. filter_var(): Validates email formats and numbers.\n3. password_hash(): Generates secure bcrypt hashes for passwords.",
                    ]
                ],
                'quiz' => [
                    'title' => 'Web Security & Forms Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which function is used in PHP to securely hash passwords with bcrypt?',
                            'option_a' => 'md5()',
                            'option_b' => 'sha1()',
                            'option_c' => 'password_hash()',
                            'option_d' => 'crypt_password()',
                            'correct_option' => 'c',
                            'explanation' => 'password_hash() with PASSWORD_DEFAULT creates strong, salted bcrypt hashes.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 4: Secure User Sign-In & Dashboard Prototype',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(28),
                    'instructions' => "Implement a sign-in validation script checking an email and password hash. Start a session with session_start() and store user credentials upon successful verification.",
                ]
            ],
            [
                'title' => 'Object-Oriented PHP (OOP)',
                'description' => 'Write clean, maintainable code using Classes, Methods, Constructors, and Inheritance.',
                'lessons' => [
                    [
                        'title' => 'Classes, Methods & Encapsulation',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\nclass Student {\n    public function __construct(\n        public string \$name,\n        public string \$email,\n        private float \$gpa = 3.8\n    ) {}\n\n    public function getGpa(): float {\n        return \$this->gpa;\n    }\n}\n\n\$student = new Student('Naima', 'student@gmail.com');\necho \"Student: {\$student->name} | GPA: \" . \$student->getGpa();\n?>",
                        'text_content' => "PHP supports full Object-Oriented Programming.\n\nVisibility Modifiers:\n- public: Accessible anywhere\n- protected: Accessible within class and child subclasses\n- private: Accessible only within the declaring class",
                    ]
                ],
                'quiz' => [
                    'title' => 'Object-Oriented PHP Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Which method is the constructor in a PHP class?',
                            'option_a' => '__construct()',
                            'option_b' => 'constructor()',
                            'option_c' => '__init__()',
                            'option_d' => 'Student()',
                            'correct_option' => 'a',
                            'explanation' => '__construct() is the magic method executed when instantiating a new class object.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Lab 5: Shopping Cart & Order Checkout System',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(35),
                    'instructions' => "Create a Cart class with addItem, removeItem, and calculateTotal methods supporting tax calculation in BDT.",
                ]
            ],
            [
                'title' => 'MySQL Database Integration with PDO',
                'description' => 'Connect to MySQL databases securely with PDO, execute prepared queries, and build CRUD apps.',
                'lessons' => [
                    [
                        'title' => 'Connecting with PDO & Prepared Statements',
                        'video_url' => 'https://www.youtube.com/watch?v=OK_JCtrrv-c',
                        'code_language' => 'php',
                        'code_snippet' => "<?php\n// PDO Connection Pattern\n\$dsn = 'mysql:host=localhost;dbname=edulink_db;charset=utf8mb4';\necho \"PDO Data Source Name: {\$dsn}\";\necho \"\\nReady for prepared statements: SELECT * FROM courses WHERE is_published = :published\";\n?>",
                        'text_content' => "PDO (PHP Data Objects) is the standard database abstraction layer in PHP.\n\nPrepared statements separate SQL syntax from incoming user parameters, completely eliminating SQL injection risks.",
                    ]
                ],
                'quiz' => [
                    'title' => 'PDO & Relational Persistence Assessment',
                    'time_limit_mins' => 10,
                    'passing_score' => 70,
                    'questions' => [
                        [
                            'question' => 'Why are prepared statements recommended over raw string SQL concatenation?',
                            'option_a' => 'They run faster and eliminate SQL Injection risks',
                            'option_b' => 'They require less memory',
                            'option_c' => 'They work without database credentials',
                            'option_d' => 'They automatically create database tables',
                            'correct_option' => 'a',
                            'explanation' => 'Prepared statements prevent SQL injection by parameterizing user inputs.'
                        ]
                    ]
                ],
                'assignment' => [
                    'title' => 'Capstone Project: Full CRUD Task Management Web App',
                    'max_marks' => 100,
                    'deadline' => now()->addDays(42),
                    'instructions' => "Design a full-stack Task Management portal in PHP with Create, Read, Update, and Delete operations using PDO.",
                ]
            ]
        ];

        foreach ($phpCurriculum as $cIndex => $cData) {
            $chapter = Chapter::create([
                'course_id' => $phpCourse->id,
                'title' => $cData['title'],
                'description' => $cData['description'],
                'sort_order' => $cIndex + 1,
            ]);

            foreach ($cData['lessons'] as $lIndex => $lData) {
                Lesson::create([
                    'chapter_id' => $chapter->id,
                    'title' => $lData['title'],
                    'video_url' => $lData['video_url'],
                    'code_language' => $lData['code_language'],
                    'code_snippet' => $lData['code_snippet'],
                    'text_content' => $lData['text_content'],
                    'sort_order' => $lIndex + 1,
                ]);
            }

            if (!empty($cData['quiz'])) {
                $q = Quiz::create([
                    'chapter_id' => $chapter->id,
                    'title' => $cData['quiz']['title'],
                    'time_limit_mins' => $cData['quiz']['time_limit_mins'],
                    'passing_score' => $cData['quiz']['passing_score'],
                    'sort_order' => 1,
                ]);

                foreach ($cData['quiz']['questions'] as $question) {
                    QuizQuestion::create([
                        'quiz_id' => $q->id,
                        'question' => $question['question'],
                        'option_a' => $question['option_a'],
                        'option_b' => $question['option_b'],
                        'option_c' => $question['option_c'],
                        'option_d' => $question['option_d'],
                        'correct_option' => $question['correct_option'],
                        'explanation' => $question['explanation'],
                    ]);
                }
            }

            if (!empty($cData['assignment'])) {
                Assignment::create([
                    'chapter_id' => $chapter->id,
                    'title' => $cData['assignment']['title'],
                    'max_marks' => $cData['assignment']['max_marks'],
                    'deadline' => $cData['assignment']['deadline'],
                    'instructions' => $cData['assignment']['instructions'],
                    'sort_order' => 1,
                ]);
            }
        }

        // -------------------------------------------------------------
        // 4. Enroll Student in Python Course (with Progress)
        // Leave PHP Course unenrolled for Free Preview and Purchase test
        // -------------------------------------------------------------
        $enrollment = Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $pythonCourse->id,
            'enrolled_at' => now()->subDays(2),
            'progress_percent' => 35,
            'status' => 'active',
        ]);

        $firstPythonLesson = $pythonCourse->chapters->first()->lessons->first();
        if ($firstPythonLesson) {
            LessonProgress::create([
                'user_id' => $student->id,
                'lesson_id' => $firstPythonLesson->id,
                'is_completed' => true,
                'last_accessed_at' => now(),
            ]);
        }
    }
}
