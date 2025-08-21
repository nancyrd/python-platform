<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class StageLevelSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * STAGE 1: Variables
         */
        $variables = Stage::query()->firstOrCreate(
            ['slug' => 'variables'],
            ['title' => 'Stage 1: Variables', 'display_order' => 1]
        );
Level::updateOrCreate(
    ['stage_id' => $variables->id, 'index' => 1],
    [
        'type'       => 'drag_drop',
        'title'      => 'What is Python? (Drag & Drop)',
        'pass_score' => 80,
        'content'    => [
            'instructions' => 'Drag each item to the correct category about Python!',
            'categories' => [
                "💻 Programming" => [
                    "🐍 Python",
                    "☕ Java",
                    "📋 Excel Macros"
                ],
                "👩‍🏫 Who Can Use Python?" => [
                    "👨‍🎓 Students",
                    "👩‍🏫 Teachers",
                    "🎨 Artists",
                    "🧑‍💻 Programmers"
                ],
                "📊 What Can Python Do?" => [
                    "🧮 Calculations",
                    "📊 Data Analysis",
                    "🌐 Make Websites",
                    "🤖 AI & Automation"
                ],
                "🚫 Not Related to Python" => [
                    "🥤 Drinking Soda",
                    "🚗 Driving a Car",
                    "🧑‍🍳 Cooking Pasta"
                ]
            ],
            'hints' => [
                "🐍 Python is a programming language for many uses.",
                "💡 Python is great for students and beginners!",
                "📊 Data, AI, and websites—all possible with Python.",
                "🚫 Watch out! Not everything is related to Python."
            ],
            'time_limit' => 300,
            'max_hints' => 4
        ],
    ]
);

        // Level 4
   Level::updateOrCreate(
    ['stage_id' => $variables->id, 'index' => 2], // next index!
    [
        'type'       => 'multiple_choice',
        'title'      => 'Python Basics: Print, Add, Subtract, Multiply, Divide',
        'pass_score' => 70,
        'content'    => [
            'intro' => "In Python, we use <code>print()</code> to display text or numbers on the screen. You can join text using <code>+</code>, do math with <code>+</code> (add), <code>-</code> (subtract), <code>*</code> (multiply), and <code>/</code> (divide). Try the questions below to test your Python basics!",
            'instructions' => "Answer the following questions about Python basics. Choose the correct answer for each blank.",
            'questions' => [
                [
                    'question' => 'How do you print Goodbye?<br><code>print(___)</code>',
                    'options' => ['"Goodbye"', 'Goodbye', "'Goodbye'"],
                    'correct_answer' => 0,
                    'explanation' => 'You must use double quotes: print("Goodbye")'
                ],
                [
                    'question' => 'Which symbol makes text join together?<br><code>print("A" ___ "B")</code>',
                    'options' => ['+', '-', '0'],
                    'correct_answer' => 0,
                    'explanation' => 'Use <code>+</code> to join (concatenate) text.'
                ],
                [
                    'question' => 'What is the correct way to print a number?<br><code>print(___)</code>',
                    'options' => ['4', '"number"', 'number'],
                    'correct_answer' => 0,
                    'explanation' => 'To print a number, just type it without quotes: print(4)'
                ],
                [
                    'question' => 'How do we add numbers?<br><code>print(2 ___ 3)</code>',
                    'options' => ['+', '-', '0'],
                    'correct_answer' => 0,
                    'explanation' => 'Use <code>+</code> to add numbers.'
                ],
                [
                    'question' => 'How do we subtract numbers?<br><code>print(5 ___ 2)</code>',
                    'options' => ['-', '+', '*'],
                    'correct_answer' => 0,
                    'explanation' => 'Use <code>-</code> to subtract numbers.'
                ],
                [
                    'question' => 'How do we multiply numbers?<br><code>print(3 ___ 2)</code>',
                    'options' => ['*', '+', '-'],
                    'correct_answer' => 0,
                    'explanation' => 'Use <code>*</code> to multiply numbers.'
                ],
                [
                    'question' => 'Which word shows text on the screen?<br><code>___("Hi")</code>',
                    'options' => ['print', 'say', 'hello'],
                    'correct_answer' => 0,
                    'explanation' => 'The <code>print()</code> function shows text on the screen.'
                ],
                [
                    'question' => 'How do we divide numbers?<br><code>print(6 ___ 2)</code>',
                    'options' => ['/', '*', '+'],
                    'correct_answer' => 0,
                    'explanation' => 'Use <code>/</code> to divide numbers.'
                ],
            ],
            'hints' => [
                'Remember to use quotes for text in Python.',
                'The plus sign <code>+</code> is used to join or add.',
                'The <code>print()</code> function is how you show things on the screen.'
            ],
            'time_limit' => 180
        ],
    ]
);


        // Level 3
        Level::updateOrCreate(
            ['stage_id' => $variables->id, 'index' => 3],
            [
                'type'       => 'true/false',
                'title'      => 'guess which is true or false',
                'pass_score' => 80,
                'content'    => json_encode([
                    'questions' => [
                        [
                            'code'    => "a = 2\nb = 3.0\nc = str(a) + str(b)\nprint(c)",
                            'options' => ['5.0', '23.0', '2 + 3.0', 'Error'],
                            'correct' => '23.0',
                        ],
                        [
                            'code'    => "x = 'hi'\nprint(x * 3)",
                            'options' => ['hi3', 'hihihi', '3hi', 'Error'],
                            'correct' => 'hihihi',
                        ],
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // Pre assessment
        Assessment::updateOrCreate(
            ['stage_id' => $variables->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Variables',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Type of x after x = "5"?',
                        'options' => ['int', 'str', 'float', 'bool'],
                        'correct' => 'str',
                    ],
                    [
                        'prompt'  => 'Valid variable name?',
                        'options' => ['2total', 'total-amount', 'total_amount', 'class'],
                        'correct' => 'total_amount',
                    ],
                    [
                        'prompt'  => 'Result of y = 3; y = y + 2',
                        'options' => ['3', '5', '2', 'Error'],
                        'correct' => '5',
                    ],
                    [
                        'prompt'  => 'type(True) equals…',
                        'options' => ["<class 'bool'>", "<class 'str'>", "<class 'int'>", 'bool'],
                        'correct' => "<class 'bool'>",
                    ],
                    [
                        'prompt'  => 'int("7") equals…',
                        'options' => ['"7"', '7', '7.0', 'Error'],
                        'correct' => '7',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // Post assessment
        Assessment::updateOrCreate(
            ['stage_id' => $variables->id, 'type' => 'post'],
            [
                'title'     => 'Post: Variables',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Choose the fix: x="10"; y=x+2',
                        'options' => ['y=int(x)+2', 'y=str(x)+2', 'y=x+"2"', 'int+2=y'],
                        'correct' => 'y=int(x)+2',
                    ],
                    [
                        'prompt'  => 'What is a good name for number of students?',
                        'options' => ['2students', 'num-students', 'num_students', 'class'],
                        'correct' => 'num_students',
                    ],
                    [
                        'prompt'  => 'bool("False") is…',
                        'options' => ['True', 'False'],
                        'correct' => 'True',
                    ],
                    [
                        'prompt'  => 'Store 3.14 in pi',
                        'options' => ['pi = "3.14"', 'pi = 3.14', '3.14 = pi', 'float = 3.14'],
                        'correct' => 'pi = 3.14',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        /**
         * STAGE 2: Input / Output (include ONLY ONCE)
         */
        $io = Stage::query()->firstOrCreate(
            ['slug' => 'input-output'],
            ['title' => 'Stage 2: Input / Output', 'display_order' => 2]
        );

        Assessment::updateOrCreate(
            ['stage_id' => $io->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: I/O',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Which prints text to the screen?',
                        'options' => ['input()', 'print()', 'len()'],
                        'correct' => 'print()',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // (Add IO levels later as you build them.)
    }
}
