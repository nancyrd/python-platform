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
 * STAGE 1: print() & Output
 */
$stage1 = Stage::query()->firstOrCreate(
    ['slug' => 'print-basics'],
    ['title' => 'Stage 1: print() & Output', 'display_order' => 1]
);

// Level 1 — Drag & Drop (Intro to Python)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 1],
    [
        'type'         => 'drag_drop',
        'title'        => 'What is Python? (Drag & Drop)',
        'pass_score'   => 50,
        'instructions' => 'Programming means giving step-by-step instructions to a computer. Python is a beginner-friendly language used for calculations, data, websites, and AI. Drag each item to the correct category.',
        'content'      => [
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
            'hints'      => [
                "🐍 Python is a general-purpose programming language.",
                "💡 Python is great for beginners and students.",
                "📊 Data, AI, and websites are all common Python uses.",
                "🚫 Some items are real-world activities, not programming."
            ],
            'time_limit' => 300,
            'max_hints'  => 4
        ],
    ]
);

// Level 2 — Multiple Choice (print & operators)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 2],
    [
        'type'         => 'multiple_choice',
        'title'        => 'Python Basics: print & simple math',
'instructions' => 'In Python, the command print() shows text or numbers on the screen. 
Put text inside quotes: print("Hello").

You can also use math symbols inside print():

• + (plus) → adds numbers. Example: print(2 + 3) → 5  
• - (minus) → subtracts numbers. Example: print(5 - 2) → 3  
• * (star) → multiplies numbers. Example: print(4 * 2) → 8  
• / (slash) → divides numbers. Example: print(6 / 2) → 3.0  

Tip: You can also use + to join text together. Example: print("Hi " + "there") → Hi there',


        'pass_score'   => 50,
        'content'      => [
            'intro'        => "In Python, use <code>print()</code> to display text or numbers. Join text with <code>+</code>. Do math with <code>+</code> (add), <code>-</code> (subtract), <code>*</code> (multiply), <code>/</code> (divide).",
            'instructions' => "Choose the correct answer for each question.",
            'questions'    => [
                [
                    'question'        => 'How do you print Goodbye?<br><code>print(___)</code>',
                    'options'         => ['"Goodbye"', 'Goodbye', "'Goodbye'"],
                    'correct_answer'  => 0,
                    'explanation'     => 'Strings need quotes: print("Goodbye")'
                ],
                [
                    'question'        => 'Which symbol joins text together?<br><code>print("A" ___ "B")</code>',
                    'options'         => ['+', '-', '0'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use + to concatenate strings.'
                ],
                [
                    'question'        => 'What is the correct way to print a number?<br><code>print(___)</code>',
                    'options'         => ['4', '"number"', 'number'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Numbers are printed without quotes: print(4)'
                ],
                [
                    'question'        => 'How do we add numbers?<br><code>print(2 ___ 3)</code>',
                    'options'         => ['+', '-', '0'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use + for addition.'
                ],
                [
                    'question'        => 'How do we subtract numbers?<br><code>print(5 ___ 2)</code>',
                    'options'         => ['-', '+', '*'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use - for subtraction.'
                ],
                [
                    'question'        => 'How do we multiply numbers?<br><code>print(3 ___ 2)</code>',
                    'options'         => ['*', '+', '-'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use * for multiplication.'
                ],
                [
                    'question'        => 'Which word shows text on the screen?<br><code>___("Hi")</code>',
                    'options'         => ['print', 'say', 'hello'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use the print() function.'
                ],
                [
                    'question'        => 'How do we divide numbers?<br><code>print(6 ___ 2)</code>',
                    'options'         => ['/', '*', '+'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use / for division.'
                ],
            ],
            'hints'       => [
                'Put quotes around text.',
                'Use + to join text or add numbers.',
                'print() shows things on the screen.'
            ],
            'time_limit'  => 180,
            'max_hints'   => 4
        ],
    ]
);

// Level 3 — True/False (mixing strings & numbers)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 3],
    [
        'type'         => 'tf1',
        'title'        => 'print(): numbers + strings (True/False)',
        'pass_score'   => 50,
        'instructions' => 'Sometimes you want to show text and numbers together. 
For example:

print("Age:", 5)          → Age: 5  
print("Score: " + str(10)) → Score: 10  
print(f"Price: ${7}")     → Price: $7  

⚠️ But these crash (TypeError):  
print("Age: " + 5)  
print(2 + "3")

👉 Rules to remember:  
- Numbers can be printed directly: print(7) → 7  
- Text must go in quotes: print("Hello") → Hello  
- + joins text with text only ("Hi" + " there") → Hi there  
- To join a number with text, turn the number into text: str(7)  
- Commas in print() are the easiest way to mix: print("Age:", 7) → Age: 7  
- f-strings are a shortcut: print(f"My age is {7}") → My age is 7',

        'content'      => [
            'intro' => "Decide True or False: does the code really print the statement given, or not?",
            'questions' => [
                // start with very simple confidence builders
                ['code' => 'print("Hello")',                  'statement' => 'This prints Hello',        'answer' => true,  'explanation' => 'Strings in quotes print directly.'],
                ['code' => 'print(10)',                       'statement' => 'This prints 10',           'answer' => true,  'explanation' => 'Numbers can print without quotes.'],

                // text + number crash
                ['code' => 'print("Age: " + 5)',              'statement' => 'This prints Age: 5',      'answer' => false, 'explanation' => 'You cannot join text + number directly. Convert with str().'],

                // fixed version with str()
                ['code' => 'print("Age: " + str(5))',         'statement' => 'This prints Age: 5',      'answer' => true,  'explanation' => 'str(5) makes the number into text.'],

                // comma version
                ['code' => 'print("Age:", 5)',                'statement' => 'This prints Age: 5',      'answer' => true,  'explanation' => 'Comma prints items separated by a space.'],

                // string + string join
                ['code' => 'print("2" + "3")',                'statement' => 'This prints 23',          'answer' => true,  'explanation' => 'Text + text joins together.'],

                // int + str crash
                ['code' => 'print(2 + "3")',                  'statement' => 'This prints 5',           'answer' => false, 'explanation' => 'int + str causes TypeError.'],

                // repeat string
                ['code' => 'print("Ha" * 3)',                 'statement' => 'This prints HaHaHa',      'answer' => true,  'explanation' => 'String * number repeats the string.'],

                // f-string demo
                ['code' => 'print(f"Score: {10}")',           'statement' => 'This prints Score: 10',   'answer' => true,  'explanation' => 'f-strings insert values inside {}.'],

                // variable example
                ['code' => "age = 7\nprint('Age:', age)",     'statement' => 'This prints Age: 7',      'answer' => true,  'explanation' => 'Comma lets you print variables easily.'],

                // variable + string concat
                ['code' => "age = '7'\nprint('Age: ' + age)", 'statement' => 'This prints Age: 7',      'answer' => true,  'explanation' => 'Both parts are text, so + works.'],

                // extra reinforcement real-life
                ['code' => 'print("Price: $" + str(12))',     'statement' => 'This prints Price: $12',  'answer' => true,  'explanation' => 'Convert number with str() when joining.'],
                ['code' => 'print("Price: $", 12)',           'statement' => 'This prints Price: $ 12', 'answer' => true,  'explanation' => 'Comma prints items with a space.'],

                // tricky false
                ['code' => 'print("Hi " + 3)',                'statement' => 'This prints Hi 3',        'answer' => false, 'explanation' => 'TypeError: must convert 3 to str().'],
                ['code' => 'print("2" * "3")',                'statement' => 'This prints 222',         'answer' => false, 'explanation' => 'You cannot multiply two strings.'],

                // more f-string practice
                ['code' => 'print(f"2 + 3 = {2 + 3}")',       'statement' => 'This prints 2 + 3 = 5',   'answer' => true,  'explanation' => 'Expression is evaluated inside {}.'],
                ['code' => 'print("Name:", "Alex")',          'statement' => 'This prints Name: Alex',  'answer' => true,  'explanation' => 'Comma prints both items separated by space.'],
                ['code' => 'print("Name:" + " " + "Alex")',   'statement' => 'This prints Name: Alex',  'answer' => true,  'explanation' => 'Manual space inside quotes works too.']
            ],
            'hints' => [
                'Quotes are for text, numbers print directly.',
                'Use str(number) if you want to join with +.',
                'Commas inside print() are the safest way to mix.',
                'f-strings are shortcuts: f"My age is {7}".'
            ],
            'time_limit' => 240,
            'max_hints'  => 4
        ],
    ]
);


// PRE assessment (aligned to print & types at a gentle level)
Assessment::updateOrCreate(
    ['stage_id' => $stage1->id, 'type' => 'pre'],
    [
        'title'     => 'Pre: print() & Basic Types',
        'questions' => json_encode([
            [
                'prompt'  => 'Which one prints Hello?',
                'options' => ['print(Hello)', 'print("Hello")', 'say("Hello")', 'echo "Hello"'],
                'correct' => 'print("Hello")',
            ],
            [
                'prompt'  => 'Text values in Python are called…',
                'options' => ['int', 'float', 'str', 'bool'],
                'correct' => 'str',
            ],
            [
                'prompt'  => 'Which joins two pieces of text?',
                'options' => ['+', '-', '*', '/'],
                'correct' => '+',
            ],
            [
                'prompt'  => 'What is the result of 3 + 2?',
                'options' => ['3', '5', '32', 'Error'],
                'correct' => '5',
            ],
            [
                'prompt'  => 'Which line is safe?',
                'options' => [
                    '"Age: " + 5',
                    'print("Age:", 5)',
                    '2 + "3"',
                    'print("Hello" "World")'
                ],
                'correct' => 'print("Age:", 5)',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]
);

// POST assessment (aligned with Levels 1–3; gentle → firmer)
Assessment::updateOrCreate(
    ['stage_id' => $stage1->id, 'type' => 'post'],
    [
        'title'     => 'Post: print() & Output Basics',
        'questions' => json_encode([
            // 1) Plain string
            [
                'prompt'  => 'Exact output of: print("Hello")',
                'options' => ['Hello', '"Hello"', 'print("Hello")', 'Error'],
                'correct' => 'Hello',
            ],
            // 2) Plain number
            [
                'prompt'  => 'Exact output of: print(7)',
                'options' => ['7', '"7"', 'int(7)', 'Error'],
                'correct' => '7',
            ],
            // 3) Comma mixing (space awareness)
            [
                'prompt'  => 'Exact output of: print("Age:", 7)',
                'options' => ['Age:7', 'Age: 7', '"Age:", 7', 'Error'],
                'correct' => 'Age: 7',
            ],
            // 4) str() + concatenation
            [
                'prompt'  => 'Exact output of: print("Age: " + str(7))',
                'options' => ['Age: 7', '"Age: " + 7', 'Age:  7', 'Error'],
                'correct' => 'Age: 7',
            ],
            // 5) f-string
            [
                'prompt'  => 'Exact output of: print(f"Score: {10}")',
                'options' => ['Score: 10', '"Score: {10}"', '{10}', 'Error'],
                'correct' => 'Score: 10',
            ],
            // 6) String + string
            [
                'prompt'  => 'Exact output of: print("2" + "3")',
                'options' => ['23', '5', '2 + 3', 'Error'],
                'correct' => '23',
            ],
            // 7) Division result format
            [
                'prompt'  => 'Exact output of: print(6 / 2)',
                'options' => ['3', '3.0', '"3"', 'Error'],
                'correct' => '3.0',
            ],
            // 8) Repeat strings
            [
                'prompt'  => 'Exact output of: print("Ha" * 3)',
                'options' => ['HaHaHa', 'Ha*3', 'Ha Ha Ha', 'Error'],
                'correct' => 'HaHaHa',
            ],
            // 9) Identify the TypeError (str + int)
            [
                'prompt'  => 'Which line causes a TypeError?',
                'options' => [
                    'print("Score:", 10)',
                    'print("Score: " + 10)',
                    'print("10" + "5")',
                    'print(int("2") + 3)'
                ],
                'correct' => 'print("Score: " + 10)',
            ],
            // 10) int() to do arithmetic
            [
                'prompt'  => 'x = "2"; y = 3. Which prints 5?',
                'options' => [
                    'print(x + y)',
                    'print(int(x) + y)',
                    'print(str(x) + str(y))',
                    'print("5")'
                ],
                'correct' => 'print(int(x) + y)',
            ],
            // 11) Comma inserts a space
            [
                'prompt'  => 'Exact output of: print("A", "B")',
                'options' => ['AB', 'A B', '"A", "B"', 'Error'],
                'correct' => 'A B',
            ],
            // 12) Two valid ways, but only one output is asked → exact output for a concat
            [
                'prompt'  => 'Exact output of: print("Price: $" + str(12))',
                'options' => ['Price: $12', 'Price: $ 12', '"Price: $" 12', 'Error'],
                'correct' => 'Price: $12',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]
);


      

        // (Add IO levels later as you build them.)
    }
}
