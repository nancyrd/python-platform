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
        'instructions' => "Flip the cards to learn how to handle user input like a pro!\n• The input() function is how Python listens to user. It pauses the program and waits for user to type something on the keyboard. Whatever he types is then given to the program to use. You can think of it as Python asking you a question. You can even put a prompt inside the parentheses,  to tell the user what to enter.\n• Clean it with .strip()\n• Convert with int() or float()\n• Print safely with commas",
        'content'      => [
            'intro'       => "Tap each card to learn with simple, real-life examples!",
            'time_limit'  => 500,
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
                    'time_limit'  => 400,
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
                    'statement'   => "This prints True because '3.5' converts to the float 3.5.",
                    'answer'      => true,
                    'explanation' => "float('3.5') produces 3.5, so the comparison is True.",
                ],
                  [
                    'type'            => 'code',
                    'question'        => "Clean the spaces and print the number.",
                    'starter_code'    => "s = '  12  '\n# your code here\n",
                    'expected_output' => "12",
                    'solution'        => "print(int(s.strip()))",
                    'explanation'     => "strip() removes spaces; int() turns the clean text into a number."
                ],
                [
                    'code'        => "print(int('7.0'))",
                    'statement'   => "This works and prints 7.",
                    'answer'      => false,
                    'explanation' => "int('7.0') raises ValueError. Use float('7.0') or int(float('7.0')).",
                ],
                     [
                    'type'            => 'code',
                    'question'        => "Convert to a float and print.",
                    'starter_code'    => "price_text = '7.5'\n# your code here\n",
                    'expected_output' => "7.5",
                    'solution'        => "print(float(price_text))",
                    'explanation'     => "float('7.5') keeps the decimal part."
                ],
                  [
                    'type'            => 'code',
                    'question'        => "Convert the text to a number, then print it safely with a label Age.",
                    'starter_code'    => "age_txt = '10'\n# print: Age: 10\n",
                    'expected_output' => "Age: 10",
                    'solution'        => "print('Age:', int(age_txt))",
                    'explanation'     => "Use commas in print() to avoid TypeError, and cast the text to int."
                ],
                [
                    'code'        => "age = ' 9 '\nprint('Age:', int(age.strip()))",
                    'statement'   => "This prints Age: 9 safely after stripping and casting.",
                    'answer'      => true,
                    'explanation' => "Remove spaces, cast to int, then print with commas.",
                ],
                [
                    'code'        => "x = '3'\nprint('Sum: ' + (x + 2))",
                    'statement'   => "This prints Sum: 5.",
                    'answer'      => false,
                    'explanation' => "TypeError: cannot add str and int. Cast first (int(x) + 2) or use commas in print.",
                ],
                [
                    'code'        => "price = '7.0'\nprint('Price:', float(price))",
                    'statement'   => "This prints Price: 7.0 after converting to float.",
                    'answer'      => true,
                    'explanation' => "Convert text to float before printing or computing.",
                ],
                [
                    'code'        => "s = ' 12 '\nprint(int(s) + 1)",
                    'statement'   => "This prints 13 even without strip(), because int ignores surrounding spaces.",
                    'answer'      => true,
                    'explanation' => "int() tolerates leading/trailing whitespace in Python, but strip() is still best practice for user input.",
                ],
                [
                    'code'        => "n = 10\nprint('N=' + n)",
                    'statement'   => "This outputs N=10.",
                    'answer'      => false,
                    'explanation' => "TypeError: can’t concatenate str and int. Use 'N=' + str(n) or print('N=', n).",
                ],
                [
                    'code'        => "txt = '5.0'\nprint(int(float(txt)))",
                    'statement'   => "This prints 5 by converting to float then to int.",
                    'answer'      => true,
                    'explanation' => "Two-step cast works: '5.0' → 5.0 → 5.",
                ],
                [
                    'code'        => "v = input()\nprint('V:', v)",
                    'statement'   => "This safely prints whatever the user typed.",
                    'answer'      => true,
                    'explanation' => "input() returns text; printing text is safe.",
                ],
                     [
                    'type'            => 'code',
                    'question'        => "Add the numbers in the texts and print the total.",
                    'starter_code'    => "a = '3'\nb = '5'\n# print: 8\n",
                    'expected_output' => "8",
                    'solution'        => "print(int(a) + int(b))",
                    'explanation'     => "Strings would concatenate ('35'); cast to ints to add."
                ],
                [
                    'code'        => "w = input()\nprint(int(w) + 1)",
                    'statement'   => "This is always safe as-is.",
                    'answer'      => false,
                    'explanation' => "Unsafe without cleaning/validation. Spaces or non-digits will crash unless you do w.strip() and check.",
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
                        'options' => ['9', '11', ' 10 ', 'Error'],
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
