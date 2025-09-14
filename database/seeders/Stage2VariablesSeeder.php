<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;
 
class Stage2VariablesSeeder extends Seeder
{
    public function run(): void
    {
        // safe next order – won’t disturb existing stages
        $nextOrder = (int) (Stage::max('display_order') ?? 0) + 1;

        // unique slug so we never collide with Stage 1
        $stage = Stage::firstOrCreate(
            ['slug' => 'variables-foundations'],
            ['title' => 'Stage 2: Variables (Foundations)', 'display_order' => $nextOrder]
        );
        
        // ---------- PRE ASSESSMENT ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Variables Foundations',
                'questions' => json_encode([
                    [
                        'prompt' => 'If you wanted to remember someone\'s name for later use, what would you do?',
                        'options' => ['Write it down', 'Memorize it', 'Tell someone else', 'All of the above'],
                        'correct' => 'All of the above'
                    ],
                    [
                        'prompt' => 'What does "age = 25" mean in programming?',
                        'options' => ['Age equals 25', 'Store 25 in a container called age', 'Age is not 25', 'Compare age to 25'],
                        'correct' => 'Store 25 in a container called age'
                    ],
                    [
                        'prompt' => 'Can you change what\'s stored in a variable?',
                        'options' => ['No, once set it stays the same', 'Yes, you can update it anytime', 'Only if you use special commands', 'It depends on the programming language'],
                        'correct' => 'Yes, you can update it anytime'
                    ],
                    [
                        'prompt' => 'What would you expect name = "Sarah" to do?',
                        'options' => ['Print Sarah', 'Store "Sarah" in name', 'Compare name to Sarah', 'Delete Sarah'],
                        'correct' => 'Store "Sarah" in name'
                    ]
                ], JSON_UNESCAPED_UNICODE)
            ]
        );

        // ---------- LEVEL 1 (Basic Variables) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Level 1: What are Variables?',
                'pass_score'   => 60,
                'instructions' => "📦 VARIABLES ARE LIKE LABELED BOXES 📦\n\nThink of variables as containers with labels where you can store information to use later.\n\n• VARIABLE NAME = The label on the box (like 'age' or 'name')\n• VALUE = What you put inside the box (like 25 or \"Alice\")\n• ASSIGNMENT = Putting something in the box using the = sign\n\nExamples:\nname = \"Alice\"     # Put \"Alice\" in the name box\nage = 25           # Put 25 in the age box\n\nYou can change what's in the box anytime:\nscore = 5          # First put 5 in score\nscore = 8          # Now replace it with 8\n\nPrinting variables shows what's inside:\nprint(name)        # Shows: Alice\nprint(age)         # Shows: 25",
                'content'      => [
                    'intro'      => 'Let\'s start with the basics - understanding what variables are and how they work.',
                    'time_limit' => 180,
                    'hints'      => [
                        'Variables are like labeled storage boxes',
                        'Use = to put values into variables',
                        'Variable names should be descriptive',
                        'You can change variable values anytime'
                    ],
                    'examples'   => [
                        [
                            'title' => 'Storing a name',
                            'code'  => "name = \"Alice\"\nprint(name)",
                            'explain' => 'We create a variable called name and put \"Alice\" inside it',
                            'expected_output' => "Alice"
                        ],
                        [
                            'title' => 'Storing a number',
                            'code'  => "age = 25\nprint(age)",
                            'explain' => 'We create a variable called age and put 25 inside it',
                            'expected_output' => "25"
                        ],
                        [
                            'title' => 'Changing variable values',
                            'code'  => "score = 5\nprint(\"First score:\", score)\nscore = 8\nprint(\"New score:\", score)",
                            'explain' => 'We can change what\'s stored in a variable',
                            'expected_output' => "First score: 5\nNew score: 8"
                        ]
                    ],
                    'questions'  => $this->level1Questions(),
                ]
            ]
        );

        // ---------- LEVEL 2 (Using Variables) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 2],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Level 2: Using Variables in Calculations',
                'pass_score'   => 70,
                'instructions' => "🧮 USING VARIABLES IN CALCULATIONS 🧮\n\nNow let's use variables to do math and combine information!\n\nMATH OPERATIONS:\n+ Addition       - Subtraction\n* Multiplication / Division\n\nExamples:\nprice = 10\ntax = 2\ntotal = price + tax    # 10 + 2 = 12\n\nCOMBINING TEXT:\nname = \"Alice\"\ngreeting = \"Hello, \" + name   # \"Hello, Alice\"\n\nUPDATING VARIABLES:\ncount = 5\ncount = count + 1       # count becomes 6\ncount += 1              # shortcut for same thing\n\nPRINTING WITH VARIABLES:\nprint(\"Total:\", total)  # Shows: Total: 12\nprint(greeting)         # Shows: Hello, Alice",
                'content'      => [
                    'intro'      => 'Now let\'s use variables to perform calculations and combine information!',
                    'time_limit' => 200,
                    'hints'      => [
                        'Use variables in math calculations',
                        'Combine text using +',
                        'You can update variables using their current value',
                        'Print can show both text and variables'
                    ],
                    'examples'   => [
                        [
                            'title' => 'Simple calculation',
                            'code'  => "apples = 5\noranges = 3\ntotal_fruit = apples + oranges\nprint(\"Total fruit:\", total_fruit)",
                            'explain' => 'We can use variables in math operations',
                            'expected_output' => "Total fruit: 8"
                        ],
                        [
                            'title' => 'Combining text',
                            'code'  => "first_name = \"John\"\nlast_name = \"Doe\"\nfull_name = first_name + \" \" + last_name\nprint(full_name)",
                            'explain' => 'We can combine text variables with +',
                            'expected_output' => "John Doe"
                        ],
                        [
                            'title' => 'Updating variables',
                            'code'  => "bank_balance = 100\ndeposit = 50\nbank_balance = bank_balance + deposit\nprint(\"New balance:\", bank_balance)",
                            'explain' => 'We can update variables using their current value',
                            'expected_output' => "New balance: 150"
                        ]
                    ],
                    'questions'  => $this->level2Questions(),
                ]
            ]
        );

        // ---------- LEVEL 3 (Variable Rules & Best Practices) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 3],
            [
                'type'         => 'drag_drop',
                'title'        => 'Level 3: Variable Rules & Organization',
                'pass_score'   => 75,
                'instructions' => "📝 VARIABLE RULES & BEST PRACTICES 📝\n\nVARIABLE NAMING RULES:\n• Must start with a letter or underscore\n• Can contain letters, numbers, and underscores\n• Cannot use spaces or special characters\n• Case matters (age ≠ Age ≠ AGE)\n\nGOOD VS BAD NAMES:\n✅ Good: user_name, total_score, item_count\n❌ Bad: user name, total-score, 2ndPlace\n\nDESCRIPTIVE NAMES:\nUse names that describe what the variable stores:\n✅ student_age instead of ✅ sa\n✅ shopping_cart_total instead of ✅ sct\n\nCONSTANTS (values that don't change):\nUse ALL_CAPS for constants:\nTAX_RATE = 0.08\nMAX_SCORE = 100",
                'content'      => [
                    'time_limit' => 240,
                    'max_hints'  => 3,
                    'hints'      => [
                        'No spaces: use underscores (_) instead',
                'First character cannot be a number',
                'Hyphens (-) are not allowed',
                'Keywords like return/for/if are not allowed',
                    ],
                   'examples'   => [
    [
        'title'           => 'Valid names — run this',
        'code'            => "student_age = 18\nuser_name = \"Maya\"\n_count = 3\nMAX_SCORE = 100\nprint(student_age, user_name, _count, MAX_SCORE)",
        'explain'         => 'All names follow rules: start with a letter/underscore; only letters, numbers, underscores; ALL_CAPS for constants.',
        'expected_output' => "18 Maya 3 100",
    ],
    [
        'title'           => 'Invalid names — these will error',
        'code'            => "# Uncomment one line at a time to see the error:\n# 2age = 18\n# first name = \"Sam\"\n# total-score = 50\n# return = 1\n# class = \"A\"\n# full\$name = 10",
        'explain'         => 'Each line breaks a rule: starts with a digit, contains a space, uses a hyphen, or is a keyword/special char.',
        'expected_output' => "SyntaxError",
    ],
],

            'categories' => [
                '✅ Valid Variable Names' => [
                    'user_name',
                    'total2',
                    '_count',
                    'Age',          // valid but different from age
                    'MAX_SCORE',
                    'is_logged_in',
                ],
                '❌ Invalid Variable Names' => [
                    '2age',         // starts with a digit
                    'first name',   // contains space
                    'total-score',  // hyphen not allowed
                    'return',       // keyword
                    'class',        // keyword
                    'full$name',    // $ not allowed
                ],
            ],
                ]
            ]
        );

         // ---------- LEVEL 4 (True/False with Code Snippets) ----------
Level::updateOrCreate(
    ['stage_id' => $stage->id, 'index' => 4],
    [
        'type'         => 'tf1',
        'title'        => 'Variable Truths & Myths',
        'pass_score'   => 70,
        'instructions' => 'Look at each code snippet and the statement about it. ' .
                         'Decide if the statement is True or False: ' .
                         '- Can variables change their values? ' .
                         '- Do variable names have rules? ' .
                         '- Can you use variables before creating them? ' .
                         '- What happens when you combine different types?',
        'content'      => [
            'examples' => [
                [
                    'title'           => 'Changing values',
                    'code'            => "x = 5\nx = 7\nprint(x)",
                    'explain'         => "Variables can be updated: this prints 7.",
                    'expected_output' => "7",
                ],
                [
                    'title'           => 'Case sensitivity',
                    'code'            => "name = \"Ali\"\nName = \"Sara\"\nprint(name)",
                    'explain'         => "name and Name are different. This prints Ali.",
                    'expected_output' => "Ali",
                ],
                [
                    'title'           => 'Invalid name',
                    'code'            => "first name = \"Sam\"",
                    'explain'         => "This will cause an error because spaces are not allowed in variable names.",
                    'expected_output' => "SyntaxError",
                ],
            ],
            'questions' => [
                [
                    'code'        => "age = 25\nage = 30\nprint(age)",
                    'statement'   => 'This will print 30 because you can change variable values.',
                    'answer'      => true,
                    'explanation' => '✅ Like replacing what\'s in a labeled jar - first you put "25" in the "age" jar, then you replace it with "30". When you look, you see the new value!'
                ],
                [
                    'code'        => "first name = \"Sam\"",
                    'statement'   => 'This will cause an error because of the space in the variable name.',
                    'answer'      => true,
                    'explanation' => '✅ Variable names are like text messages - no spaces allowed! You need to use underscores instead: first_name = "Sam"'
                ],
                [
                    'code'        => "print(score)\nscore = 100",
                    'statement'   => 'This will print 100 because the variable is used after being created.',
                    'answer'      => false,
                    'explanation' => '❌ This is like trying to read a recipe before you\'ve written it down! You must create the variable (put something in the box) before you can use it.'
                ],
                [
                    'code'        => "apples = \"5\"\noranges = 3\ntotal = apples + oranges",
                    'statement'   => 'This will combine them to make "53".',
                    'answer'      => false,
                    'explanation' => '❌ Actually, this causes an error! It\'s like trying to add "5 apples" + 3 oranges - they\'re different types. You need to convert first: total = int(apples) + oranges'
                ],
                [
                    'code'        => "TAX_RATE = 0.08\nTAX_RATE = 0.09",
                    'statement'   => 'You can change constant values even though they use ALL_CAPS.',
                    'answer'      => true,
                    'explanation' => '✅ ALL_CAPS is just a convention (like a red STOP sign), not an actual rule. Python will let you change it, but it\'s not recommended - like changing the rules mid-game!'
                ],
                [
                    'code'        => "name = \"Lisa\"\nName = \"John\"\nprint(name)",
                    'statement'   => 'This will print "Lisa" because variable names are case-sensitive.',
                    'answer'      => true,
                    'explanation' => '✅ name and Name are as different as "coffee" and "COFFEE" - the computer sees them as completely separate variables!'
                ],
                [
                    'code'        => "x = 10\ny = x\nx = 20\nprint(y)",
                    'statement'   => 'This will print 20 because y is connected to x.',
                    'answer'      => false,
                    'explanation' => '❌ When you do y = x, it\'s like taking a photo of what\'s in the x box at that moment. Changing x later doesn\'t change the photo! y stays 10.'
                ],
                [
                    'code'        => "count = 5\ncount = count + 1\nprint(count)",
                    'statement'   => 'This is a valid way to increase a variable\'s value.',
                    'answer'      => true,
                    'explanation' => '✅ This is like having a piggy bank: First you have $5, then you add $1 more. Now your total is $6!'
                ],
            ],
            'hints'      => [
                'Variables must be created before use',
                'Variable names cannot contain spaces',
                'Case matters in variable names',
                'You can change variable values anytime',
                'Text and numbers are different types'
            ],
            'time_limit'  => 300,
            'max_hints'   => 3,
        ],
    ]
);
        // ---------- POST ASSESSMENT ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'post'],
            [
                'title'     => 'Post: Variables Foundations',
                'questions' => json_encode([
                    [
                        'prompt' => 'Which is the best variable name for storing a person\'s age?',
                        'options' => ['a', 'x', 'age', 'person_age_in_years'],
                        'correct' => 'person_age_in_years'
                    ],
                    [
                        'prompt' => 'What does this code do: total = price + tax',
                        'options' => ['Compares price and tax', 'Stores price in total', 'Adds price and tax, stores result in total', 'Prints the total'],
                        'correct' => 'Adds price and tax, stores result in total'
                    ],
                    [
                        'prompt' => 'Which variable name is invalid?',
                        'options' => ['user_name', 'totalScore', '2nd_place', 'first_name'],
                        'correct' => '2nd_place'
                    ],
                    [
                        'prompt' => 'How do you update a variable using its current value?',
                        'options' => ['score = 5', 'score = new', 'score = score + 1', 'update score to 6'],
                        'correct' => 'score = score + 1'
                    ]
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    private function level1Questions(): array
    {
        return [
            [
                'question' => "What is a variable in programming?",
                'options'  => ['A mathematical equation', 'A labeled container for storing data', 'A type of computer hardware', 'A programming language'],
                'correct_answer' => 1,
                'explanation' => 'A variable is like a labeled box where you can store information to use later.',
            ],
            [
                'question' => "name = \"Bob\"\nprint(name)\nWhat will this code display?",
                'options'  => ['name', '"Bob"', 'Bob', 'Error'],
                'correct_answer' => 2,
                'explanation' => 'The variable name contains \"Bob\", so print(name) shows Bob',
            ],
            [
                'question' => "Which line correctly creates a variable?",
                'options'  => ['variable age = 25', 'age == 25', 'age = 25', '25 = age'],
                'correct_answer' => 2,
                'explanation' => 'Use = to assign values: variable_name = value',
            ],
            [
                'question' => "score = 10\nscore = 15\nprint(score)\nWhat will this display?",
                'options'  => ['10', '15', '25', 'Error'],
                'correct_answer' => 1,
                'explanation' => 'The variable score is updated from 10 to 15',
            ],
            [
                'question' => "Why do we use variables in programming?",
                'options'  => ['To make code look complicated', 'To store and reuse data', 'Only for mathematical calculations', 'To create error messages'],
                'correct_answer' => 1,
                'explanation' => 'Variables help us store information that we can use multiple times in our program',
            ]
        ];
    }

    private function level2Questions(): array
    {
        return [
            [
                'question' => "apples = 3\noranges = 4\ntotal = apples + oranges\nprint(total)\nWhat will this display?",
                'options'  => ['7', '34', '3+4', 'Error'],
                'correct_answer' => 0,
                'explanation' => '3 + 4 = 7, which is stored in total',
            ],
            [
                'question' => "first = \"Hello\"\nsecond = \"World\"\nmessage = first + \" \" + second\nprint(message)\nWhat will this display?",
                'options'  => ['HelloWorld', 'Hello World', 'first second', 'Error'],
                'correct_answer' => 1,
                'explanation' => 'The + operator combines text: \"Hello\" + \" \" + \"World\" = \"Hello World\"',
            ],
            [
                'question' => "count = 5\ncount = count + 2\nprint(count)\nWhat will this display?",
                'options'  => ['5', '7', '52', 'Error'],
                'correct_answer' => 1,
                'explanation' => 'count becomes 5 + 2 = 7',
            ],
            [
                'question' => "Which operation would calculate the total cost of 5 items costing $10 each?",
                'options'  => ['total = 5', 'total = 10', 'total = 5 + 10', 'total = 5 * 10'],
                'correct_answer' => 3,
                'explanation' => '5 items × $10 each = $50 total',
            ],
            [
                'question' => "price = 20\ntax = 4\ntotal = price + tax\nprint(\"Total: $\" + str(total))\nWhat will this display?",
                'options'  => ['Total: $24', 'Total: $20+4', 'Total: $204', 'Error'],
                'correct_answer' => 0,
                'explanation' => 'price + tax = 24, and str() converts it to text for combining',
            ]
        ];
    }
}