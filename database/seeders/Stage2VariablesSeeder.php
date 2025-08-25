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

 
        // ---------- LEVEL 3 (True/False) ----------
Level::updateOrCreate(
    ['stage_id' => $stage->id, 'index' => 3],
    [
        'type'         => 'true/false',
        'title'        => 'True or False: Variables',
        'pass_score'   => 75,
        'instructions' => "Each code block prints either True or False. Your job: pick what it prints.\nTips:\n- Quotes → string (str). type('3') == int is False\n- int('7') → 7, float('3.5') → 3.5, str(10) → '10'\n- Python is case-sensitive: Name ≠ name\n- '2' + '3' → '23'; 'Ha' * 3 → 'HaHaHa'\n- 6/2 is a float → type(6/2) == float",
        'content'      => [
            'intro'      => "Decide if each snippet prints True or False.",
            'time_limit' => 220,
            'max_hints'  => 3,
            'hints'      => [
                "Quotes mean it's a string.",
                "Use int('7') / float('3.5') before math.",
                "Case matters: Name vs name.",
                "print(6/2) gives 3.0 → that's a float.",
            ],
            'questions'  => [
                [
                    'code'        => "x = '5'\nprint(int(x) + 1 == 6)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "int('5') is 5; 5 + 1 == 6 → True.",
                ],
                [
                    'code'        => "Name = 'Sam'\nname = 'Sam'\nprint(Name == name)",
                    'options'     => ['True','False'],
                    'correct'     => 'False',
                    'explanation' => "Case-sensitive: Name and name are different variables.",
                ],
                [
                    'code'        => "x = 3.0\nprint(type(x) == float)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "3.0 is a float.",
                ],
                [
                    'code'        => "print(type('3') == int)",
                    'options'     => ['True','False'],
                    'correct'     => 'False',
                    'explanation' => "'3' has quotes → it's a str, not int.",
                ],
                [
                    'code'        => "print(int('7') + 2 == 9)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "7 + 2 = 9.",
                ],
                [
                    'code'        => "print(float('3.5') == 3.5)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "float('3.5') parses to 3.5.",
                ],
                [
                    'code'        => "x = 10\ny = x\ny = y + 1\nprint(x == 11)",
                    'options'     => ['True','False'],
                    'correct'     => 'False',
                    'explanation' => "x stays 10; only y was changed.",
                ],
                [
                    'code'        => "print('2' + '3' == '23')",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "String + string concatenates.",
                ],
                [
                    'code'        => "print('2' + str(3) == '5')",
                    'options'     => ['True','False'],
                    'correct'     => 'False',
                    'explanation' => "'2' + '3' is '23', not '5'.",
                ],
                [
                    'code'        => "print('Ha' * 3 == 'HaHaHa')",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "String repetition.",
                ],
                [
                    'code'        => "print(type(6 / 2) == float)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "In Python 3, / returns float (3.0).",
                ],
                [
                    'code'        => "print(int(True) == 1)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "True converts to 1.",
                ],
                [
                    'code'        => "print(str(False) == 'False')",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "Boolean to string.",
                ],
                [
                    'code'        => "name = 'Ali'\nprint('Hello, ' + name == 'Hello, Ali')",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "Concatenation matches exactly.",
                ],
                [
                    'code'        => "print(bool('') == False)",
                    'options'     => ['True','False'],
                    'correct'     => 'True',
                    'explanation' => "Empty string is falsy.",
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
