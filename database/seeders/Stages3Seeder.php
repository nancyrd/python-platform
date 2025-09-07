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
        /**
         * STAGE 3: Input & Casting
         * Goal: Read text with input(), clean (.strip()), convert, then compute & print
         */
        $stage = Stage::query()->firstOrCreate(
            ['slug' => 'input-casting'],
            ['title' => 'Stage 3: Input & Casting', 'display_order' => 3]
        );

        // ---------- LEVEL 1 (Flip Cards — Input Essentials) ----------
   Level::updateOrCreate(
    ['stage_id' => $stage->id, 'index' => 1],
    [
        'type'         => 'flip_cards',
        'title'        => 'Input Essentials Made Easy!',
        'pass_score'   => 60,
        'instructions' => "Flip the cards to learn how to handle user input like a pro!\n• input() always gives text\n• Clean it with .strip()\n• Convert with int() or float()\n• Print safely with commas",
        'content'      => [
            'intro'       => "Tap each card to learn with simple, real-life examples!",
            'time_limit'  => 200,
            'max_hints'   => 3,
            'hints'       => [
                'input() gives text - even if you type numbers!',
                'Use .strip() to remove extra spaces before converting',
                'int() for whole numbers (like 7), float() for decimals (like 7.5)',
                'Print with commas: print(\"Name:\", name) - it adds spaces automatically!'
            ],
            // Cards: front/back quick learning
            'cards'       => [
                [
                    'front' => 'Why .strip()?',
                    'back'  => "Like cleaning dirty coins before using them!\n\"  9  \".strip() → \"9\"\nRemoves extra spaces before converting to number."
                ],
                [
                    'front' => 'int() vs float()',
                    'back'  => "Whole numbers vs decimals!\nint(\"7\") → 7 (like 7 apples)\nfloat(\"7.5\") → 7.5 (like $7.50)\nint(\"7.5\") → ERROR ❌"
                ],
                [
                    'front' => 'Safe Printing',
                    'back'  => "Easy labels with commas!\nprint(\"Age:\", 7) → Age: 7\nprint(\"Price:\", 2.99) → Price: 2.99\nNo crashes, automatic spaces!"
                ],
                [
                    'front' => 'Fixing Number Commas',
                    'back'  => "\"1,234\" ❌ int(\"1,234\")\nFix: Remove commas first!\nint(\"1,234\".replace(\",\", \"\")) → 1234\nLike reading \"1,234\" as \"1234\""
                ],
                [
                    'front' => 'Text + Numbers',
                    'back'  => "Convert numbers to text first!\n\"Age: \" + str(7) → \"Age: 7\"\nOr use: print(\"Age:\", 7) (easier!)"
                ],
            ],
            // Console-ready examples (work without interactive input)
            'examples'    => [
                [
                    'title'   => 'Cleaning Spaces First',
                    'code'    => "age_str = ' 9 '\nclean_age = age_str.strip()\nage = int(clean_age)\nprint('Next year:', age + 1)",
                    'explain' => 'Clean the spaces (like washing fruit) before using!',
                    'expected_output' => "Next year: 10"
                ],
                [
                    'title'   => 'Decimal Numbers',
                    'code'    => "price_str = '7.50'\nprice = float(price_str)\nprint('Two items:', price * 2)",
                    'explain' => 'Use float() for money and measurements!',
                    'expected_output' => "Two items: 15.0"
                ],
                [
                    'title'   => 'Easy Labels',
                    'code'    => "name = 'Mia'\nage = 12\nprint('Student:', name, '| Age:', age)",
                    'explain' => 'Commas add spaces automatically - no glue needed!',
                    'expected_output' => "Student: Mia | Age: 12"
                ],
            ],
        ],
    ]
);
        // ---------- LEVEL 2 (Match Pairs — Pick the Right Conversion / Steps) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 2],
            [
                'type'         => 'match_pairs',
                'title'        => 'Pick the Right Conversion',
                'pass_score'   => 70,
                'instructions' => "Match each value to the correct cast/result.\nBonus: includes typical mini-calculator steps.",
                'content'      => [
                    'intro'       => "Match value → correct cast (or outcome).",
                    'time_limit'  => 240,
                    'max_hints'   => 3,
                    'hints'       => [
                        'If it has a dot, use float().',
                        'If it’s whole-number text, use int().',
                        'Always .strip() before casting when spaces may exist.',
                        'Use str() to join numbers with text.',
                    ],
                    // Primary pairs for the match_pairs view
                    'pairs'       => [
                        ['left' => '"7"',             'right' => 'int("7")'],
                        ['left' => '"7.0"',           'right' => 'float("7.0")'],
                        ['left' => '"  9  "',         'right' => 'int("  9  ".strip())'],
                        ['left' => '10',     'right' => 'str(10)'],
                        ['left' => '"3.5"',           'right' => 'float("3.5")'],
                        ['left' => '"007"',           'right' => 'int("007")'],
                        ['left' => 'Error',     'right' => 'int("12 apples")'], 
                        ['left' => '" 5.0 "',         'right' => 'float(" 5.0 ".strip())'],
                        ['left' => 'price = input()', 'right' => 'price.strip()  (clean before cast)'],
                        ['left' => 'qty = input()', 'right' => 'qty = int(qty.strip())'],
                    ],
                    // Optional: mini-steps (if you later support reorder UI you can use this)
                    'steps_demo'  => [
                        'title' => 'Mini-calculator steps (reference)',
                        'steps' => [
                            '1) read = input()',
                            '2) clean = read.strip()',
                            '3) num = int(clean) or float(clean)',
                            '4) compute using num',
                            '5) print("Result:", value)',
                        ]
                    ],
                    'examples'    => [
                        [
                            'title'   => 'Spaces + dot',
                            'code'    => "txt = '  3.0  '\nnum = float(txt.strip())\nprint('Doubled:', num*2)",
                            'explain' => 'Strip then float() for decimals.',
                            'expected_output' => "Doubled: 6.0"
                        ],
                        [
                            'title'   => 'Whole number text',
                            'code'    => "s = '007'\nprint(int(s))",
                            'explain' => 'int() handles leading zeros.',
                            'expected_output' => "7"
                        ],
                        [
                            'title'   => 'To text with str()',
                            'code'    => "x = 10\nprint('X = ' + str(x))",
                            'explain' => 'Use str() when joining with +.',
                            'expected_output' => "X = 10"
                        ],
                    ],
                ],
            ]
        );

        // ---------- LEVEL 3 (True/False — Safe or not?) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Safe or Not?',
                'pass_score'   => 75,
                'instructions' => "Decide if the snippet is safely converted/printed. Cast first to avoid crashes.",
                'content'      => [
                    'intro'       => "True if it’s safe/correct; False if it errors or is unsafe.",
                    'time_limit'  => 220,
                    'max_hints'   => 3,
                    'hints'       => [
                        "Cast strings before math: int('7'), float('3.5').",
                        "Use .strip() to remove spaces before casting.",
                        "Safe print with commas: print('Age:', n).",
                        "int('7.0') is invalid — use float('7.0') instead.",
                    ],
                    'examples'    => [
                        [
                            'title'   => 'Safe mixing with commas',
                            'code'    => "age = 9\nprint('Age:', age)",
                            'explain' => 'No TypeError; adds a space automatically.',
                            'expected_output' => "Age: 9"
                        ],
                        [
                            'title'   => 'Cast then compute',
                            'code'    => "s = ' 5 '\nn = int(s.strip())\nprint(n + 1)",
                            'explain' => 'Clean → cast → compute.',
                            'expected_output' => "6"
                        ],
                    ],
                    // Each question: code + options True/False + correct + explanation
                    'questions'   => [
                        [
                            'code'        => "print(float('3.5') == 3.5)",
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "float('3.5') produces 3.5 → True.",
                        ],
                        [
                            'code'        => "print(int('7.0'))",
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "int('7.0') raises ValueError; use float().",
                        ],
                        [
                            'code'        => "age = ' 9 '\nprint('Age:', int(age.strip()))",
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Strip then cast → safe print with comma.",
                        ],
                        [
                            'code'        => "x = '3'\nprint('Sum: ' + (x + 2))",
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "TypeError: cannot add str and int directly.",
                        ],
                        [
                            'code'        => "price = '7.0'\nprint('Price:', float(price))",
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Convert to float before printing.",
                        ],
                        [
                            'code'        => "s = ' 12 '\nprint(int(s) + 1)",
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Must strip first; int(' 12 ') is OK in CPython, but teaching best-practice: use s.strip() before casting.",
                        ],
                        [
                            'code'        => "n = 10\nprint('N=' + n)",
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Join with str(n) or use commas.",
                        ],
                        [
                            'code'        => "txt = '5.0'\nprint(int(float(txt)))",
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Two-step cast works: '5.0' → 5.0 → 5.",
                        ],
                        [
                            'code'        => "v = input()\nprint('V:', v)",
                            'options'     => ['True','False'],
                            'correct'     => 'True',
                            'explanation' => "Printing raw input as text is always safe.",
                        ],
                        [
                            'code'        => "w = input()\nprint(int(w) + 1)",
                            'options'     => ['True','False'],
                            'correct'     => 'False',
                            'explanation' => "Unsafe without strip()/validation; may crash on spaces/non-digits.",
                        ],
                    ],
                ],
            ]
        );

        // ---------- PRE assessment ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Input & Casting Basics',
                'questions' => json_encode([
                    [
                        'prompt'  => 'input() returns…',
                        'options' => ['int','float','str','bool'],
                        'correct' => 'str',
                    ],
                    [
                        'prompt'  => 'Best way to remove surrounding spaces:',
                        'options' => ['trim()', '.strip()', '.clean()', 'removeSpaces()'],
                        'correct' => '.strip()',
                    ],
                    [
                        'prompt'  => 'Which safely prints a number with a label?',
                        'options' => [
                            '"Age: " + 7',
                            'print("Age:", 7)',
                            'print("Age: " + 7)',
                            'say("Age:", 7)'
                        ],
                        'correct' => 'print("Age:", 7)',
                    ],
                    [
                        'prompt'  => 'Pick the correct cast for "7.0":',
                        'options' => ['int("7.0")', 'float("7.0")', 'bool("7.0")', 'str(7.0) only'],
                        'correct' => 'float("7.0")',
                    ],
                    [
                        'prompt'  => 'To join text + number with +, use:',
                        'options' => ['str()', 'int()', 'float()', 'join()'],
                        'correct' => 'str()',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // ---------- POST assessment ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'post'],
            [
                'title'     => 'Post: Input, Strip, Cast, Print',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Exact output: s=" 9 "; print(int(s.strip())+1)',
                        'options' => ['9', '10', ' 10 ', 'Error'],
                        'correct' => '10',
                    ],
                    [
                        'prompt'  => 'Which line fails?',
                        'options' => [
                            'float("3.5")',
                            'int("7")',
                            'int("7.0")',
                            'print("Total:", 12)'
                        ],
                        'correct' => 'int("7.0")',
                    ],
                    [
                        'prompt'  => 'Safest pattern after input for a decimal number:',
                        'options' => [
                            'n = input(); n = int(n)',
                            'n = input(); n = float(n.strip())',
                            'n = input(); print("N=" + n+1)',
                            'n = input(); print(int(n)+".0")'
                        ],
                        'correct' => 'n = input(); n = float(n.strip())',
                    ],
                    [
                        'prompt'  => 'Exact output: print("Price:", float("7.0"))',
                        'options' => ['Price: 7', 'Price: 7.0', '"Price:", 7.0', 'Error'],
                        'correct' => 'Price: 7.0',
                    ],
                    [
                        'prompt'  => 'Which is safe for joining text + number?',
                        'options' => [
                            '"X=" + 5',
                            'print("X=", 5)',
                            '"X=" + str(5)',
                            'Both (2) and (3)'
                        ],
                        'correct' => 'Both (2) and (3)',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
}
