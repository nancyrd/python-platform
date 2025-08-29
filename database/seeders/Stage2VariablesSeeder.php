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
        
        // ---------- PRE (revised: removed name-validity; added print-with-name) ----------
Assessment::updateOrCreate(
    ['stage_id' => $stage->id, 'type' => 'pre'],
    [
        'title'     => 'Pre: Variables Foundations',
        'questions' => json_encode([
            [
                'prompt'  => 'Which line correctly stores the number 7 in a variable named age?',
                'options' => ['age = 7', '7 = age', 'age: 7', 'var age = 7'],
                'correct' => 'age = 7'
            ],
            [
                'prompt'  => "What will this print?\nname = \"Mia\"\nprint(\"Hello, \" + name)",
                'options' => ['Hello, name', 'Hello, Mia', '"Hello, " + name', 'Error'],
                'correct' => 'Hello, Mia'
            ],
            [
                'prompt'  => "What will x be after:\nx = 3\nx = x + 2",
                'options' => ['3', '5', '"5"', 'Error'],
                'correct' => '5'
            ],
            [
                'prompt'  => 'What is the type of "42" (with quotes)?',
                'options' => ['int', 'str', 'float', 'bool'],
                'correct' => 'str'
            ],
            [
                'prompt'  => 'Which line safely prints Age: 7?',
                'options' => ['print("Age:" + 7)', 'print("Age:", 7)', 'print("Age:" "7")', 'print(Age: 7)'],
                'correct' => 'print("Age:", 7)'
            ],
            [
                'prompt'  => 'Which converts the text "8" into the number 8?',
                'options' => ['int("8")', 'str(8)', 'float("8")', '"8" + 0'],
                'correct' => 'int("8")'
            ],
            [
                'prompt'  => 'Which one is a boolean literal in Python?',
                'options' => ['"True"', 'True', '"false"', 'yes'],
                'correct' => 'True'
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]
);

 
// ---------- LEVEL 1 (MCQ / easy) ----------
Level::updateOrCreate(
    ['stage_id' => $stage->id, 'index' => 1],
    [
        'type'         => 'multiple_choice',
        'title'        => 'Variables 101 — Easy MCQ',
        'pass_score'   => 60,
        'instructions' => "Mini-lesson (read me first):\n\nA variable is a labeled box. You put a value inside with = and later use the label to get it back.\n\nExamples:\n  age = 7        # store 7\n  age = age + 1  # update to 8\n  print(\"Age:\", age)  # safest way to show text + number\n\nCasting (turn text into numbers when needed):\n  int(\"7\")  → 7\n  float(\"3.5\") → 3.5\n\nTips:\n• Text uses quotes, numbers don’t.\n• Use commas in print() to mix text + numbers safely.\n• + joins text with text; use str(number) if you really want +.\n• str(10) → \"10\"",
        'content'      => [
            'intro'      => 'Pick the best answer for each question.',
            'time_limit' => 180,
            'hints'      => [
                'If it has quotes, it is text (str).',
                'print("Label:", value) adds a space and never crashes.',
                'Use int("7") or float("3.5") before doing math.',
            ],
            'examples'   => [
                [
                    'title' => '1) Store and print a number',
                    'code'  => "age = 7\nprint(age)",
                    'explain' => 'Variables hold values. Printing a number needs no quotes.',
                    'expected_output' => "7",
                ],
                [
                    'title' => '2) Update a variable',
                    'code'  => "count = 3\ncount = count + 2\nprint(count)",
                    'explain' => 'Use the current value on the right to compute a new one.',
                    'expected_output' => "5",
                ],
                [
                    'title' => '3) Safest mix of text + number',
                    'code'  => "price = 12\nprint(\"Price:\", price)",
                    'explain' => 'Commas in print() automatically add a space and avoid TypeError.',
                    'expected_output' => "Price: 12",
                ],
                [
                    'title' => '4) Converting text to number',
                    'code'  => "x = \"8.5\"\nprint(float(x) + 1.5)",
                    'explain' => 'Turn text into a number before doing math.',
                    'expected_output' => "10.0",
                ],
                [
                    'title' => '5) If you really want + with text',
                    'code'  => "score = 10\nprint(\"Score: \" + str(score))",
                    'explain' => 'Convert the number to text with str() to concatenate.',
                    'expected_output' => "Score: 10",
                ],
                [
                    'title' => '6) f-strings shortcut',
                    'code'  => "name = 'Mia'\nprint(f\"Hello, {name}\")",
                    'explain' => 'f-strings let you embed variables inside text.',
                    'expected_output' => "Hello, Mia",
                ],
            ],
            'questions'  => $this->mcqPool(),
        ],
    ]
);


 
     // ---------- LEVEL 2 (Drag & Drop) ----------
Level::updateOrCreate(
    ['stage_id' => $stage->id, 'index' => 2],
    [
        'type'         => 'drag_drop',
        'title'        => 'Sort the Values by Type',
        'pass_score'   => 70,
        'instructions' => "Sorting Game time! 🧺
Put each card where it belongs:
- int  = whole numbers like 3, 0, -12
- float = numbers with a dot like 4.5, 0.0
- str  = text in quotes like \"hi\", \"42\"
- bool = True or False (no quotes)
- Not a value = a bare name (price) or a statement (x = 5)

Tips:
- If it has quotes, it's a string (str).
- If it has a dot and no quotes, it's a float.
- True/False without quotes are booleans.
- Bare words like price are just names until you assign a value.",
        'content'      => [
            'time_limit' => 240,
            'max_hints'  => 3,
            'hints'      => [
                'If it has quotes, it is text (str).',
                'int has no dot; float has a dot.',
                'True/False without quotes are booleans.',
                'Bare words (e.g., price) are names, not values.',
            ],
            'examples'   => [
                [
                    'title' => '1) Check types with type()',
                    'code'  => "print(type(3))\nprint(type(4.5))\nprint(type(\"hi\"))\nprint(type(True))",
                    'explain' => 'type() tells you the data type of a value.',
                    'expected_output' => "<class 'int'>\n<class 'float'>\n<class 'str'>\n<class 'bool'>",
                ],
                [
                    'title' => '2) Quotes change the type',
                    'code'  => "print(type(42))\nprint(type(\"42\"))",
                    'explain' => 'Same digits, different types: number vs text.',
                    'expected_output' => "<class 'int'>\n<class 'str'>",
                ],
                [
                    'title' => '3) Convert before math',
                    'code'  => "x = \"7\"\nprint(int(x) + 3)",
                    'explain' => 'Convert strings to numbers to do arithmetic.',
                    'expected_output' => "10",
                ],
            ],
            'categories'  => [
                '🧮 int (whole numbers)' => ['3', '0', '-12', '42'],
                '➗ float (decimals)'    => ['4.5', '0.0', '7.0', '2.5'],
                '📝 str (text in quotes)' => ['"hi"', '"abc"', '"42"', '"True"', '"7.0"'],
                '✅ bool (True/False)'   => ['True', 'False'],
                '🚫 Not a value'        => ['price', 'car', 'first name', 'x = 5'],
            ],
        ],
    ]
);


 
      // ---------- LEVEL 3 (Match Pairs) ----------
Level::updateOrCreate(
    ['stage_id' => $stage->id, 'index' => 3],
    [
        'type'         => 'match_pairs',
        'title'        => 'Match Pairs: Variables & Types',
        'pass_score'   => 75,
        'instructions' => "Match each item on the left with its correct partner on the right.\nTips:\n- Quotes → string (str)\n- int('7') → 7, float('3.5') → 3.5\n- True/False are booleans (no quotes)\n- Commas in print() safely mix text and numbers",
        'content'      => [
            'intro'       => "Click one card on the left, then the matching card on the right.",
            'time_limit'  => 220,
            'max_hints'   => 3,
            'hints'       => [
                "String digits (e.g., '7') are text until converted.",
                "int has no dot; float has a dot.",
                "Use int()/float() before math with text numbers.",
                "True/False without quotes are booleans.",
            ],
            // Each pair: left => right mapping
            'pairs'       => [
                ['left' => "Type of 3.0",                 'right' => "float"],
                ['left' => "Type of '3'",                  'right' => "str"],
                ['left' => "int('7') + 2",                'right' => "9"],
                ['left' => "'2' + '3'",                   'right' => "23"],
                ['left' => "float('3.5')",                'right' => "3.5"],
                ['left' => "True (no quotes)",            'right' => "bool"],
                ['left' => "print('Age:', 7)",            'right' => "Age: 7"],
                ['left' => "str(10)",                     'right' => "'10'"],
                ['left' => "Type of 6 / 2",               'right' => "float"],
                ['left' => "'Ha' * 3",                    'right' => "HaHaHa"],
                ['left' => "Name vs name (case matters)", 'right' => "different variables"],
                ['left' => "int(True)",                   'right' => "1"],
            ],
            // Optional: examples panel (if your view supports it)
            'examples'   => [
                [
                    'title' => 'Booleans → numbers',
                    'code'  => "print(int(True), int(False))",
                    'explain' => 'True → 1, False → 0.',
                    'expected_output' => "1 0",
                ],
                [
                    'title' => 'Comma join in print()',
                    'code'  => "age = 7\nprint('Age:', age)",
                    'explain' => 'Commas are the safest way to mix text + numbers.',
                    'expected_output' => "Age: 7",
                ],
                [
                    'title' => 'String vs number',
                    'code'  => "print(type('3'), type(3))",
                    'explain' => 'Quotes make text; no quotes is a number.',
                    'expected_output' => "<class 'str'> <class 'int'>",
                ],
            ],
        ],
    ]
);

 
        // ---------- POST ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'post'],
            [
                'title'     => 'Post: Variables Foundations',
                'questions' => json_encode([
                    ['prompt' => 'Fix: x="10"; y = x + 2', 
                     'options' => ['y=int(x)+2','y=str(x)+2','y=x+"2"','2+x'], 
                     'correct' => 'y=int(x)+2'],
                    ['prompt' => 'Store 3.14 in pi',      
                     'options' => ['pi="3.14"','pi = 3.14','3.14 = pi','float = 3.14'], 
                     'correct' => 'pi = 3.14'],
                    ['prompt' => 'bool("False") is…',     
                     'options' => ['True','False'], 
                     'correct' => 'True'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
 
    private function mcqPool(): array
{
    return [
        [
            'question' => "age = 7\nprint(age)\nWhat prints?",
            'options'  => ['7','"7"','age','Error'],
            'correct_answer' => 0,
            'explanation' => 'age stores the number 7; print(age) → 7.',
        ],
        [
            'question' => "name = \"Mia\"\nprint(\"Hello, \" + name)\nWhat prints?",
            'options'  => ['Hello, name','Hello, Mia','\"Hello, \" + name','Error'],
            'correct_answer' => 1,
            'explanation' => 'String + string concatenation prints Hello, Mia.',
        ],
        [
            'question' => "x = 3\nx = x + 2\nprint(x)\nWhat prints?",
            'options'  => ['3','5','\"5\"','Error'],
            'correct_answer' => 1,
            'explanation' => 'x becomes 5 after the update.',
        ],
        [
            'question' => "price = 12\nWhich line prints exactly: Price: $12 ?",
            'options'  => [
                'print("Price: $" + price)',
                'print("Price: $", price)',
                'print("Price: $" + str(price))',
                'print("Price:" + "$" + price)'
            ],
            'correct_answer' => 2,
            'explanation' => 'Join text + text: use str(price). The comma version adds a space (Price: $ 12).',
        ],
        [
            'question' => 'What is the type of x after x = "7"?',
            'options'  => ['int','str','float','bool'],
            'correct_answer' => 1,
            'explanation' => 'Quotes make a string (str).',
        ],
        [
            'question' => 'Which creates a float?',
            'options'  => ['x = 4','x = 4.0','x = "4"','x = True'],
            'correct_answer' => 1,
            'explanation' => 'A decimal point makes it a float.',
        ],
        [
            'question' => 'Which converts the text "8.5" into a number you can add?',
            'options'  => ['int("8.5")','float("8.5")','"8.5"+1','str(8.5)'],
            'correct_answer' => 1,
            'explanation' => 'float("8.5") → 8.5 as a number; int("8.5") raises ValueError.',
        ],
        [
            'question' => 'Choose the valid variable name and assignment:',
            'options'  => ['first name = "Ali"','total-amount = 5','user_name = "Ali"','2items = 3'],
            'correct_answer' => 2,
            'explanation' => 'Use underscores; no spaces/dashes; cannot start with a digit.',
        ],
        [
            'question' => 'Pick the boolean literal:',
            'options'  => ['"True"','True','"False"','"yes"'],
            'correct_answer' => 1,
            'explanation' => 'True/False are boolean keywords (no quotes).',
        ],
        [
            'question' => "x = \"3\"\ny = 2\nprint(int(x) + y)\nWhat prints?",
            'options'  => ['"32"','5','"5"','Error'],
            'correct_answer' => 1,
            'explanation' => 'int("3") → 3; 3 + 2 = 5.',
        ],
    ];
}

}
