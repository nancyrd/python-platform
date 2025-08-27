<?php
 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class Stage6FunctionsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // STAGE 6: Functions (def, parameters, return)
        // ─────────────────────────────────────────────────────────────
        $stage6 = Stage::updateOrCreate(
            ['slug' => 'functions-def-parameters-return'],
            [
                'title'         => 'Stage 6: Functions (def, parameters, return)',
                'display_order' => 6,
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 1 — Meet def (multiple_choice)
        // Goals: def vs call; print vs return; None when no return
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage6->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Meet def', 
                'pass_score'   => 60,
                'instructions' => 
                    '• Use `def` to give a name and start a function—like naming a recipe. ' .
                    '• The indented lines below `def` are the steps of that recipe. ' .
                    '• Use `return value` when you want to send a result back from the function. ' .
                    '• If you don’t use `return`, the function finishes and gives back `None`, even if it `print()`s inside.',
                'content'      => [
                    'intro'        => 
                        'Functions are like recipes you write for the computer: ' .
                        '- `def name():` means “start a recipe called name.” ' .
                        '- Inside, list the steps. ' .
                        '- Use `return value` to hand something back. ' .
                        '- If you skip `return`, the function gives back `None`.',
                    'instructions' => 'Pick what happens when you run the code below.',
                    'questions'    => [
                        [ 
                            'question'       => "php\ndef hello():\n    print('Hi')\nhello()\nWhat shows on screen?",
                            'options'        => ['Hi', 'None', 'Error', 'HiNone'],
                            'correct_answer' => 0,
                            'explanation'    => 
                                'Calling `hello()` runs its steps, which `print("Hi")` → shows Hi.'
                        ],
                        [
                            'question'       => "php\ndef get_five():\n    return 5\nprint(get_five())\nWhat prints?",
                            'options'        => ['5', 'None', 'Error', '"5"'],
                            'correct_answer' => 0,
                            'explanation'    => 
                                '`get_five()` returns the number 5, and `print` shows it.'
                        ],
                        [
                            'question'       => "php\ndef say():\n    print('Hello')\nx = say()\nprint(x)\nWhat prints?",
                            'options'        => ['Hello\nNone', 'Hello', 'None', 'Error'],
                            'correct_answer' => 0,
                            'explanation'    => 
                                '`say()` prints Hello, then returns `None`, so `x` is `None` and `print(x)` shows None.'
                        ],
                        [
                            'question'       => "php\ndef square(n):\n    return n * n\ny = square(3)\nprint(y)\nWhat prints?",
                            'options'        => ['9', '3', 'None', 'Error'],
                            'correct_answer' => 0,
                            'explanation'    => 
                                '`square(3)` returns 9; `print(y)` shows 9.'
                        ],
                        [
                            'question'       => "php\ndef greet(name):\n    return 'Hi, ' + name\ne = greet('Alex')\nprint(e)\nWhat prints?",
                            'options'        => ['Hi, Alex', 'None', 'Error', 'greet'],
                            'correct_answer' => 0,
                            'explanation'    => 
                                '`greet("Alex")` returns the string "Hi, Alex", which is printed.'
                        ],
                        [
                            'question'       => "php\ndef foo():\n    pass\nprint(foo())\nWhat prints?",
                            'options'        => ['None', 'pass', 'Error', '0'],
                            'correct_answer' => 0,
                            'explanation'    => 
                                '`pass` does nothing; with no `return` the function returns `None`.'
                        ],
                    ],
                    'hints'       => [
                        '`def` names your recipe.',
                        '`return` hands back a result.',
                        'No `return` → you get `None`.',
                    ],
                    'time_limit'  => 300,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 2 — Build the function (drag_drop)
        // Goals: Arrange header, body, return; identify parameters
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage6->id, 'index' => 2],
            [
                'type'         => 'drag_drop',
                'title'        => 'Build the function',
                'pass_score'   => 60,
                'instructions' => 
                    'We’re going to assemble a working function from shuffled code lines. ' .
                    '1. Drag the correct `def ...:` header into the “Header” category—this names the function and its parameters. ' .
                    '2. Drag the indented code lines that make up the logic into the “Body” category. ' .
                    '3. Drag the final `return ...` statement into the “Return” category. ' .
                    '4. Anything that does not belong to defining or running the function goes under “Not part of function.” ' .
                    'By the end, you’ll see exactly how a function is structured: header, body, return.',
                'content'      => [
                    'categories' => [
                        '🔖 Header' => [
                            'def add(x, y):',
                            'def greet():',
                            'def square(n):',
                        ],
                        '📝 Body' => [
                            'result = x + y',
                            "print('Hello!')",
                            'n = n * n',
                        ],
                        '🎁 Return' => [
                            'return result',
                            'return n',
                            "return 'Hi!'",
                        ],
                        '❌ Not part of function' => [
                            'import math',
                            'x = 0',
                            'print("Start")',
                        ],
                    ],
                    'hints'      => [
                        'Header starts with `def` and ends with `:`.',
                        'Body is the indented steps under the header.',
                        'Return sends one value back; skip if only printing.',
                    ],
                    'time_limit'  => 300,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 3 — Call vs define (true_false via tf1)
        // Goals: Identify correct calls, arity, return usage
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage6->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Call vs define',
                'pass_score'   => 60,
                 'instructions' => 
                    'Look at each code snippet and the statement about it. ' .
                    'Decide if the statement is True or False: ' .
                    '- Is the function actually called? ' .
                    '- Does it return or print a value? ' .
                    '- Are the correct number of arguments provided? ' .
                    '- Does a missing `return` default to `None`?',
                'content'      => [
                    'questions' => [
                        [
                            'code'        => "def foo():\n    return 1\nfoo()",
                            'statement'   => 'This returns 1 but prints nothing.',
                            'answer'      => true,
                            'explanation' => 
                                '`foo()` returns 1; without `print` you see no output.'
                        ],
                        [
                            'code'        => "def bar(x):\n    print(x)\nbar()",
                            'statement'   => 'This causes an error because x is missing.',
                            'answer'      => true,
                            'explanation' => 
                                'Calling `bar()` without an argument for x → TypeError.'
                        ],
                        [
                            'code'        => "def baz(a, b=2):\n    return a * b\nprint(baz(3))",
                            'statement'   => 'This prints 6.',
                            'answer'      => true,
                            'explanation' => 
                                '`baz(3)` uses default b=2 → returns 6, which is printed.'
                        ],
                        [
                            'code'        => "def f():\n    return\nprint(f())",
                            'statement'   => 'This prints None.',
                            'answer'      => true,
                            'explanation' => 
                                'A bare `return` returns `None`, so `print(f())` shows None.'
                        ],
                        [
                            'code'        => "def hello():\n    print('Hey')\nhello",
                            'statement'   => 'Nothing happens because the function is not called.',
                            'answer'      => true,
                            'explanation' => 
                                'Without `()`, you refer to the function but do not run it.'
                        ],
                        [
                            'code'        => "def haha():\n    return 'ha'\nprint(haha() + haha())",
                            'statement'   => 'This prints hahaha.',
                            'answer'      => false,
                            'explanation' => 
                                '`haha()` returns "ha"; "ha" + "ha" → "haha", then printed once.'
                        ],
                    ],
                    'hints'      => [
                        'Calling needs `()`: name plus parentheses.',
                        '`return` gives back a value; bare return → None.',
                        'Default parameters fill in if you omit an argument.',
                    ],
                    'time_limit'  => 300,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // PRE assessment for Stage 6
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage6->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Functions (baseline)',
                'questions' => [
                    [
                        'prompt'  => 'What does `def` do?',
                        'options' => ['Returns a value', 'Defines a function', 'Calls a function', 'Prints text'],
                        'correct' => 'Defines a function',
                    ],
                    [
                        'prompt'  => 'How do you call a function named `foo`?',
                        'options' => ['foo', 'call foo()', 'foo()', 'def foo()'],
                        'correct' => 'foo()',
                    ],
                    [
                        'prompt'  => 'What prints when you run `def f(): pass\nprint(f())`?',
                        'options' => ['pass', 'None', 'Error', '0'],
                        'correct' => 'None',
                    ],
                    [
                        'prompt'  => 'Which line returns the number 3?',
                        'options' => [
                            'def three(): return 3',
                            'return 3',
                            'print(3)',
                            'three()'
                        ],
                        'correct' => 'def three(): return 3',
                    ],
                    [
                        'prompt'  => 'Default parameters are defined like?',
                        'options' => ['def f(a=1):', 'def f(=1):', 'f(a=1):', 'def f(a):=1'],
                        'correct' => 'def f(a=1):',
                    ],
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // POST assessment for Stage 6
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage6->id, 'type' => 'post'],
            [
                'title'     => 'Post: Functions',
                'questions' => [
                    [
                        'prompt'  => "Exact output?\n\ndef f(x):\n    return x + 2\nprint(f(3))",
                        'options' => ['5', '3', 'None', 'Error'],
                        'correct' => '5',
                    ],
                    [
                        'prompt'  => "What happens if you write `foo` instead of `foo()`?",
                        'options' => ['Calls foo', 'References foo', 'Error', 'Prints foo'],
                        'correct' => 'References foo',
                    ],
                    [
                        'prompt'  => "Given `def g(): print('Go')`, what prints?",
                        'options' => ['Go', 'None', 'Error', 'g'],
                        'correct' => 'Go',
                    ],
                    [
                        'prompt'  => "Which line will set `y` to None?",
                        'options' => [
                            'y = f()',
                            'y = return',
                            'y = None',
                            'return y'
                        ],
                        'correct' => 'y = f()',
                    ],
                    [
                        'prompt'  => "Default parameter demo:\n\ndef h(a, b=3):\n    return a * b\nprint(h(2))",
                        'options' => ['6', '2', 'None', 'Error'],
                        'correct' => '6',
                    ],
                    [
                        'prompt'  => "Which returns nothing?",
                        'options' => [
                            'def a(): return',
                            'def b(): pass',
                            'def c(): print(1)',
                            'All of the above'
                        ],
                        'correct' => 'All of the above',
                    ],
                ],
            ]
        );
    }
}
