<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class Stages3Seeder extends Seeder
{
    public function run(): void
    {
        // Get next order - won't disturb existing stages
        $nextOrder = (int) (Stage::max('display_order') ?? 0) + 1;

        // Create stage
        $stage = Stage::firstOrCreate(
            ['slug' => 'input-casting'],
            ['title' => 'Stage 3: Input & Casting', 'display_order' => $nextOrder]
        );
        
        // ---------- PRE ASSESSMENT ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Input & Casting Basics',
                'questions' => json_encode([
                    [
                        'prompt'  => 'What does input() return in Python?',
                        'options' => ['int', 'float', 'str', 'bool'],
                        'correct' => 'str'
                    ],
                    [
                        'prompt'  => 'What does "  hello  ".strip() return?',
                        'options' => ['"  hello  "', '"hello"', '" hello "', '"  hello"'],
                        'correct' => '"hello"'
                    ],
                    [
                        'prompt'  => 'Which function converts "7.5" to a decimal number?',
                        'options' => ['int("7.5")', 'float("7.5")', 'str("7.5")', 'bool("7.5")'],
                        'correct' => 'float("7.5")'
                    ],
                    [
                        'prompt'  => 'What is the safest way to print text and numbers together?',
                        'options' => ['Using + operator', 'Using commas in print()', 'Using str() with +', 'Both 2 and 3'],
                        'correct' => 'Both 2 and 3'
                    ],
                    [
                        'prompt'  => 'What will int("7.0") do?',
                        'options' => ['Return 7', 'Return 7.0', 'Cause an error', 'Return "7"'],
                        'correct' => 'Cause an error'
                    ]
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // ---------- LEVEL 1 (Flip Cards) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 1],
            [
                'type'         => 'flip_cards',
                'title'        => 'Input Essentials',
                'pass_score'   => 70,
                'instructions' => "Flip to learn: input() returns str; use .strip(); when to use int() vs float(); safe print with commas.",
                'content'      => [
                    'intro'      => 'Flip the cards to learn about input handling and casting',
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'hints'      => [
                        'input() always returns a string, even for numbers',
                        'Use .strip() to remove extra spaces from user input',
                        'Use int() for whole numbers, float() for decimals',
                        'Use commas in print() to safely mix text and numbers'
                    ],
                    'cards'      => [
                        [
                            'front' => 'What does input() return?',
                            'back'  => 'Always returns a string (str), even if user enters numbers'
                        ],
                        [
                            'front' => 'How to clean user input?',
                            'back'  => 'Use .strip() to remove leading/trailing spaces: input().strip()'
                        ],
                        [
                            'front' => 'When to use int()?',
                            'back'  => 'For whole numbers: int("7") → 7, int("10") → 10'
                        ],
                        [
                            'front' => 'When to use float()?',
                            'back'  => 'For decimal numbers: float("3.5") → 3.5, float("7.0") → 7.0'
                        ],
                        [
                            'front' => 'int("7.0") will...',
                            'back'  => 'Cause ValueError - use float("7.0") instead'
                        ],
                        [
                            'front' => 'Safe printing technique',
                            'back'  => 'Use commas: print("Age:", age) - automatically adds space'
                        ],
                        [
                            'front' => 'Alternative to commas',
                            'back'  => 'Use str() with +: print("Age: " + str(age))'
                        ],
                        [
                            'front' => 'Typical input workflow',
                            'back'  => '1. Read with input(), 2. Clean with .strip(), 3. Convert with int()/float(), 4. Use the value'
                        ]
                    ],
                ],
            ]
        );

        // ---------- LEVEL 2 (Match Pairs) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 2],
            [
                'type'         => 'match_pairs',
                'title'        => 'Pick the Right Conversion',
                'pass_score'   => 75,
                'instructions' => "Match value → correct cast or order steps for mini-calculator.",
                'content'      => [
                    'intro'      => 'Match the input values with the correct conversion method',
                    'time_limit' => 240,
                    'max_hints'  => 3,
                    'hints'      => [
                        'Numbers with decimals need float()',
                        'Whole numbers without decimals need int()',
                        'Use .strip() when input might have spaces',
                        'Text without numbers stays as string'
                    ],
                    'pairs'      => [
                        [
                            'left'  => '"7"',
                            'right' => 'int("7")'
                        ],
                        [
                            'left'  => '"7.0"',
                            'right' => 'float("7.0")'
                        ],
                        [
                            'left'  => '" 9 "',
                            'right' => 'int("9".strip())'
                        ],
                        [
                            'left'  => '"3.5"',
                            'right' => 'float("3.5")'
                        ],
                        [
                            'left'  => '10',
                            'right' => 'str(10)'
                        ],
                        [
                            'left'  => '"hello"',
                            'right' => 'No conversion needed'
                        ],
                        [
                            'left'  => '"True"',
                            'right' => 'bool("True") but careful: non-empty string is True'
                        ],
                        [
                            'left'  => '""',
                            'right' => 'Check if empty first'
                        ]
                    ],
                    'sequences'  => [
                        [
                            'title' => 'Mini-calculator steps',
                            'steps' => [
                                'Read input with input()',
                                'Clean with .strip()',
                                'Convert to number with int() or float()',
                                'Perform calculation',
                                'Print result'
                            ],
                            'correct_order' => [0, 1, 2, 3, 4]
                        ],
                        [
                            'title' => 'Age verification steps',
                            'steps' => [
                                'Convert to int()',
                                'Check if age >= 18',
                                'Read input with input()',
                                'Print "Adult" or "Minor"',
                                'Clean with .strip()'
                            ],
                            'correct_order' => [2, 4, 0, 1, 3]
                        ]
                    ]
                ],
            ]
        );

        // ---------- LEVEL 3 (True/False) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Safe or Not?',
                'pass_score'   => 80,
                'instructions' => "Decide if the snippet is safely converted/printed. Cast first to avoid crashes.",
                'content'      => [
                    'intro'      => "Determine if each code snippet is safe or will cause an error",
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'hints'      => [
                        "input() returns string - need conversion for math",
                        "int() fails on decimals, use float() instead",
                        "Always clean input with .strip() when needed",
                        "Use commas in print() for safe mixing of types"
                    ],
                    'questions'  => [
                        [
                            'code'        => 'float("3.5") == 3.5',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "float('3.5') correctly converts to 3.5",
                        ],
                        [
                            'code'        => 'int("7.0")',
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "int('7.0') causes ValueError - use float() for decimals",
                        ],
                        [
                            'code'        => 'age = input("Enter age: ")\nprint("Age:", age)',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Safe - using comma in print() handles string conversion",
                        ],
                        [
                            'code'        => 'price = input("Enter price: ")\ntotal = price * 1.1',
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Unsafe - price is string, need float() conversion first",
                        ],
                        [
                            'code'        => 'name = input().strip()\nprint("Hello,", name)',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Safe - using .strip() and comma in print()",
                        ],
                        [
                            'code'        => 'num = " 5 "\nresult = int(num) + 2',
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Unsafe - spaces cause ValueError, need .strip() first",
                        ],
                        [
                            'code'        => 'num = "5"\nresult = int(num.strip()) + 2',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Safe - using .strip() and proper conversion",
                        ],
                        [
                            'code'        => 'value = "3.14"\nprint("Value: " + value)',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Safe - both are strings, + works fine",
                        ],
                        [
                            'code'        => 'value = 3.14\nprint("Value: " + value)',
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Unsafe - cannot concatenate str and float with +",
                        ],
                        [
                            'code'        => 'value = 3.14\nprint("Value:", value)',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Safe - comma in print() handles conversion automatically",
                        ],
                        [
                            'code'        => 'age = input("Age: ")\nif age >= 18:',
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Unsafe - comparing string with number, need int() conversion",
                        ],
                        [
                            'code'        => 'age = int(input("Age: ").strip())\nif age >= 18:',
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Safe - proper input handling with .strip() and int()",
                        ],
                    ],
                ],
            ]
        );

        // ---------- POST ASSESSMENT ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'post'],
            [
                'title'     => 'Post: Input & Casting Mastery',
                'questions' => json_encode([
                    [
                        'prompt'  => 'What is the correct way to get an integer from user input?',
                        'options' => [
                            'int(input())',
                            'int(input().strip())',
                            'input().int()',
                            'str(int(input()))'
                        ],
                        'correct' => 'int(input().strip())'
                    ],
                    [
                        'prompt'  => 'What does " 7 ".strip() return?',
                        'options' => ['" 7 "', '"7"', '7', 'Error'],
                        'correct' => '"7"'
                    ],
                    [
                        'prompt'  => 'Which will cause a ValueError?',
                        'options' => [
                            'int("7")',
                            'float("7.0")', 
                            'int("7.0")',
                            'float("7")'
                        ],
                        'correct' => 'int("7.0")'
                    ],
                    [
                        'prompt'  => 'What is the output of: print("Result:", 5 + 2)',
                        'options' => [
                            'Result:7',
                            'Result: 7',
                            '7',
                            'Result:5+2'
                        ],
                        'correct' => 'Result: 7'
                    ],
                    [
                        'prompt'  => 'How to safely calculate 10% tax on user input?',
                        'options' => [
                            'price = input() * 1.1',
                            'price = float(input()) * 1.1',
                            'price = input().float() * 1.1',
                            'price = int(input()) * 1.1'
                        ],
                        'correct' => 'price = float(input()) * 1.1'
                    ]
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
}