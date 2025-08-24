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

        // Level 2
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
            'time_limit' => 180,
            'max_hints' => 4
        ],
    ]
);


        // Level 3
     // Level 3 — True/False about print() combining numbers & strings
Level::updateOrCreate(
    ['stage_id' => $variables->id, 'index' => 3],
    [
        'type'       => 'tf1',
        'title'      => 'print(): numbers + strings (True/False)',
        'pass_score' => 80,
        'content'    => json_encode([
'intro' => "In Python, there are two kinds of things here:\ntext (like \"Age: \") and numbers (like 5).\n\nTo show them together, use one of these:\n\nprint(\"Age:\", 5) ← easiest (comma lets print mix text + numbers)\n\nprint(\"Age: \" + str(5)) ← turn the number into text with str()\n\nprint(f\"Age: {5}\") ← f-string puts the number inside { }\n\nDo NOT do this (it crashes):\n\"Age: \" + 5\n2 + \"3\"\n\nNow go try the level!!",

            'questions' => [
                [
                    'code'        => "print(5)",
                    'statement'   => "This prints the number 5.",
                    'answer'      => true,
                    'explanation' => "Numbers can be printed directly without quotes."
                ],
                [
                    'code'        => "print(\"Age: \" + 5)",
                    'statement'   => "This prints Age: 5",
                    'answer'      => false,
                    'explanation' => "TypeError: you can’t add str + int. Use str(5) or a comma."
                ],
                [
                    'code'        => "print(\"Age: \" + str(5))",
                    'statement'   => "This prints Age: 5",
                    'answer'      => true,
                    'explanation' => "Convert numbers to strings with str() when concatenating."
                ],
                [
                    'code'        => "print(\"Age:\", 5)",
                    'statement'   => "This prints Age: 5",
                    'answer'      => true,
                    'explanation' => "Using a comma prints items separated by a space."
                ],
                [
                    'code'        => "print(\"2\" + \"3\")",
                    'statement'   => "This prints 23",
                    'answer'      => true,
                    'explanation' => "String + string concatenates text."
                ],
                [
                    'code'        => "print(2 + \"3\")",
                    'statement'   => "This prints 5",
                    'answer'      => false,
                    'explanation' => "TypeError: int + str. Use 2 + int(\"3\") or str(2) + \"3\"."
                ],
                [
                    'code'        => "print(\"2\" * 3)",
                    'statement'   => "This prints 222",
                    'answer'      => true,
                    'explanation' => "String * int repeats the string."
                ],
                [
                    'code'        => "print(f\"Age: {5}\")",
                    'statement'   => "This prints Age: 5",
                    'answer'      => true,
                    'explanation' => "f-strings format values inside {}."
                ],
                [
                    'code'        => "age = 5\nprint(\"Age: \" + str(age))",
                    'statement'   => "This prints Age: 5",
                    'answer'      => true,
                    'explanation' => "Again, convert number to string when concatenating."
                ],
                [
                    'code'        => "age = \"5\"\nprint(\"Age: \" + age)",
                    'statement'   => "This prints Age: 5",
                    'answer'      => true,
                    'explanation' => "Both parts are strings—safe to concatenate."
                ],
            ],
            'hints' => [
                "Use str(number) when joining with text using +.",
                "print(a, b) separates items with a space automatically.",
                "f-strings: f\"Age: {value}\" are an easy way to mix text and numbers."
            ],
            'time_limit' => 180,
            'max_hints'  => 3
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
        'title'     => 'Post: Print & Basic Types',
        'questions' => json_encode([
            [
                'prompt'  => 'What is the exact output of: print("Hello, Python!")',
                'options' => ['Hello, Python!', '"Hello, Python!"', 'print("Hello, Python!")', 'Error'],
                'correct' => 'Hello, Python!',
            ],
            [
                'prompt'  => 'What does this print?   print("Age:", 5)',
                'options' => ['Age:5', 'Age: 5', '"Age:", 5', 'Error'],
                'correct' => 'Age: 5',
            ],
            [
                'prompt'  => 'What does this print?   print("2" + "3")',
                'options' => ['23', '5', '2 + 3', 'Error'],
                'correct' => '23',
            ],
            [
                'prompt'  => 'After x = 3.14, what is type(x)?',
                'options' => ['int', 'float', 'str', 'bool'],
                'correct' => 'float',
            ],
            [
                'prompt'  => 'Which line causes a TypeError?',
                'options' => [
                    'print("Score:", 10)',
                    'print(10 + 5)',
                    'print("10" + "5")',
                    'print("Score: " + 10)'
                ],
                'correct' => 'print("Score: " + 10)',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]
);

        
    }
}
