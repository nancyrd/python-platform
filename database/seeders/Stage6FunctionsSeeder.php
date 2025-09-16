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
        // Audience: absolute beginners (kids/ non-CS)
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
        // Covers EVERYTHING used later:
        // def, call(), parameters, default parameters, print vs return, None,
        // missing-argument error, mixing types caution.
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage6->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Meet def',
                'pass_score'   => 60,
                'instructions' =>
                    // EXPLAIN FROM SCRATCH — simple, concrete, covers all topics used later
                    "Think of a function like a tiny recipe card you write for the computer.\n".
                    "\n".
                    "1) Starting a function (def):\n".
                    "   • We begin with the word def, then the function name, then parentheses (), then a colon :\n".
                    "     Example:\n".
                    "     def hello():\n".
                    "         print('Hi')\n".
                    "\n".
                    "2) The body (the steps):\n".
                    "   • The lines under def are indented. These are the steps of your recipe.\n".
                    "   • Indentation matters in Python; the indented lines belong to the function.\n".
                    "\n".
                    "3) Calling (actually running) the function:\n".
                    "   • You must use parentheses to run it:\n".
                    "     hello()\n".
                    "   • Writing hello without () does nothing; it only points to the recipe card.\n".
                    "\n".
                    "4) Parameters = inputs (like ingredients):\n".
                    "   • Some functions need information:\n".
                    "     def add(a, b):\n".
                    "         return a + b\n".
                    "\n".
                    "   • When you call add, you must pass the needed values:\n".
                    "     add(2, 3)\n".
                    "\n".
                    "5) Default parameters (optional ingredients):\n".
                    "   • You can give a default value so the caller may skip it:\n".
                    "     def baz(a, b=2):\n".
                    "         return a * b\n".
                    "     baz(3)  # uses b=2 automatically\n".
                    "\n".
                    "6) return vs print:\n".
                    "   • print shows text on the screen right now.\n".
                    "   • return gives a value back from the function to whoever called it.\n".
                    "   • If a function has no return statement, it gives back a special value called None.\n".
                    "   • Real life example:
                         🎤 Print = Speaking out loud
Tells someone the answer
Displays it on the screen
Temporary - once said, it's gone
For humans to see

📦 Return = Putting in a box to use later
Gives back the result
Stores it for later use
Permanent - you can keep using it
For computers to process
\n".
                    "\n".
                    "7) Common mistakes we will test:\n".
                    "   • Forgetting parentheses: hello (not called) vs hello() (called)\n".
                    "   • Missing an argument when a function needs one → error\n".
                    "   • Expecting print to change the returned value (it doesn’t). A function can print and still return None.\n".
                    "   • Doing math with text (like '2' + 3) causes an error; convert first: int('2') + 3",
                'content'      => [
                   
                    'examples' => [
                        [
                            'title'   => '1) Define, then call',
                            'code'    =>
                                "def hello():\n".
                                "    print('Hi')\n".
                                "\n".
                                "hello()",
                            'explain' => "def creates the function. hello() runs it and shows Hi.",
                            'expected_output' => "Hi",
                        ],
                        [
                            'title'   => '2) Return gives back a value',
                            'code'    =>
                                "def add(a, b):\n".
                                "    return a + b\n".
                                "\n".
                                "result = add(2, 3)\n".
                                "print(result)",
                            'explain' => "return hands 5 back to the caller. print shows 5.",
                            'expected_output' => "5",
                        ],
                        [
                            'title'   => '3) No return → None',
                            'code'    =>
                                "def say():\n".
                                "    print('Hello')\n".
                                "\n".
                                "x = say()\n".
                                "print(x)",
                            'explain' => "say() prints Hello but returns None, so x is None.",
                            'expected_output' => "Hello\nNone",
                        ],
                        [
                            'title'   => '4) Default parameter',
                            'code'    =>
                                "def baz(a, b=2):\n".
                                "    return a * b\n".
                                "\n".
                                "print(baz(3))",
                            'explain' => "b defaults to 2, so 3*2 = 6.",
                            'expected_output' => "6",
                        ],
                    [
    'title'   => '5) Convert text to number before math',
    'code'    =>
        "def plus_two(x_text):\n".
        "    x = int(x_text)\n".
        "    return x + 2\n".
        "\n".
        "print(plus_two('5'))",
    'explain' => "The parameter x_text suggests it is a string. We pass 5 as text, which is a string in Python. To perform math, we convert it to a number using int(). This is typical when getting input from users, as input() always returns a string. After converting 5 to 5, we add 2.",
    'expected_output' => "7",
]




                    ],
                    'questions'    => [
                        [
                           'question'       => "def hello():\n    print('Hi')\n\nhello()\n\nWhat shows on screen?",
                            'options'        => ['Hi', 'None', 'Error', 'HiNone'],
                            'correct_answer' => 0,
                            'explanation'    => "hello() runs print('Hi').",
                        ],
                        [
                            'question'       => "def get_five():\n    return 5\n\nprint(get_five())\nWhat prints?",
                            'options'        => ['5', 'None', 'Error', '"5"'],
                            'correct_answer' => 0,
                            'explanation'    => "get_five returns 5; print shows 5.",
                        ],
                        [
                            'question'       => "def say():\n    print('Hello')\n\nx = say()\nprint(x)\nWhat prints?",
                            'options'        => ['Hello\nNone', 'Hello', 'None', 'Error'],
                            'correct_answer' => 0,
                            'explanation'    => "say prints Hello, returns None → x is None.",
                        ],
                        [
                            'question'       => "def square(n):\n    return n * n\n\ny = square(3)\nprint(y)\nWhat prints?",
                            'options'        => ['9', '3', 'None', 'Error'],
                            'correct_answer' => 0,
                            'explanation'    => "square(3) returns 9; print shows 9.",
                        ],
                        [
                            'question'       => "def greet(name):\n    return 'Hi, ' + name\n\ne = greet('Alex')\nprint(e)\nWhat prints?",
                            'options'        => ['Hi, Alex', 'None', 'Error', 'greet'],
                            'correct_answer' => 0,
                            'explanation'    => "The returned string is printed.",
                        ],
                        [
                            'question'       => "def foo():\n    pass\n\nprint(foo())\nWhat prints?",
                            'options'        => ['None', 'pass', 'Error', '0'],
                            'correct_answer' => 0,
                            'explanation'    => "No return → None.",
                        ],
                        [
                            'question'       => "def add(a, b):\n    print(a + b)\n\nz = add(2, 3)\nprint(z)\nWhat prints?",
                            'options'        => ["5\nNone", 'None', '5', 'Error'],
                            'correct_answer' => 0,
                            'explanation'    => "add prints 5, returns None → z is None.",
                        ],
                        [
                            'question'       => "We wrote the function three above. What happens if we write three (without parentheses) instead of three()?",
                            'options'        => [
                                'Nothing happens (just a reference)',
                                'The function runs',
                                'You always get an Error',
                                'It prints the word three',
                            ],
                            'correct_answer' => 0,
                            'explanation'    => "No parentheses means no call.",
                        ],
                    ],
                    'hints'       => [
                        "def starts and names your function.",
                        "Indentation shows which lines belong to the function.",
                        "Call with parentheses: name().",
                        "print shows; return gives back a value.",
                        "No return → None.",
                        "Convert text to numbers for math: int('2').",
                    ],
                    'time_limit'  => 360,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 2 — Build the Function (drag_drop)
        // Instructions fully explain the structure used in tasks.
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage6->id, 'index' => 2],
            [
                'type'         => 'drag_drop',
                'title'        => 'Build the Function',
                'pass_score'   => 60,
                'instructions' =>
                    "Your job: place each line where it truly belongs in a function.\n".
                    "\n".
                    "What every function looks like:\n".
                    "• Header: starts with def, has the name and (parameters), ends with a colon :\n".
                    "  Example:\n".
                    "  def area(w, h):\n".
                    "\n".
                    "• Body: the indented steps under the header (the recipe steps). Put calculations or prints here:\n".
                    "  product = w * h\n".
                    "\n".
                    "• Return: the value you hand back to the caller. Usually it is one line at the end:\n".
                    "  return product\n".
                    "\n".
                    "• Not part of function: setup lines that are outside recipes (like imports or top-level prints). They are not indented under def.\n".
                    "\n".
                    "Important notes used in the tasks:\n".
                    "• Indentation means those lines belong to the function.\n".
                    "• A function can print or can return or both — but printing does not replace returning.\n".
                    "• If you don’t return, callers receive None.",
                'content'      => [
                    'categories' => [
                        'Header' => [
                            'def area(w, h):',
                            'def cheer(name):',
                            'def make_lemonade(lemons, sugar):',
                        ],
                        'Body' => [
                            'product = w * h',
                            'message = "Go " + name + "!"',
                            'print(message)',
                            'mix = lemons + sugar',
                        ],
                        'Return' => [
                            'return product',
                            'return mix',
                            'return name',
                        ],
                        'Not part of function' => [
                            'print("Start program")',
                            'w = 3',
                            'import math',
                        ],
                    ],
                    'hints'      => [
                        "Header = def name(parameters):",
                        "Body = indented steps (compute or print).",
                        "Return hands back one value to the caller.",
                        "No return → the function gives back None.",
                    ],
                    'time_limit'  => 300,
                    'max_hints'   => 3,
                    'examples' => [
    [
        'title'   => 'Calculate the area of a rectangle',
        'code'    => "def area(w, h):\n    product = w * h\n    return product\n\nprint(area(5, 3))",
        'explain' => 'This function calculates the area of a rectangle given width and height. The result is returned after multiplication.',
        'expected_output' => "15"
    ],
    [
        'title'   => 'Generate a greeting message',
        'code'    => "def greet(name):\n    message = 'Hello, ' + name + '!'\n    return message\n\nprint(greet('Alice'))",
        'explain' => 'This function creates a greeting message by concatenating the name with Hello, . It then returns the greeting message.',
        'expected_output' => "Hello, Alice!"
    ],
    [
        'title'   => 'Calculate the sum of two numbers',
        'code'    => "def sum_two(a, b):\n    result = a + b\n    return result\n\nprint(sum_two(5, 7))",
        'explain' => 'This function adds two numbers and returns the result.',
        'expected_output' => "12"
    ],
    [
        'title'   => 'Make lemonade from lemons and sugar',
        'code'    => "def make_lemonade(lemons, sugar):\n    mix = lemons + sugar\n    return mix\n\nprint(make_lemonade(5, 3))",
        'explain' => 'This function mixes lemons and sugar to make lemonade and returns the mix.',
        'expected_output' => "8"
    ]
]

                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 3 — Call vs Define (tf1)
        // Instructions cover: calling (), argument count, defaults,
        // print vs return, None, and type mixing note.
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage6->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Call vs Define',
                'pass_score'   => 60,
                'instructions' =>
                    "How to judge each snippet and statement:\n".
                    "\n".
                    "1) Is the function CALLED? You must see parentheses: name().\n".
                    "   • Without (), nothing runs.\n".
                    "\n".
                    "2) Are the right number of arguments passed?\n".
                    "   • If a function needs x, you must provide x when calling.\n".
                    "   • If there is a default value (like b=2), you may skip it.\n".
                    "\n".
                    "3) Does the function return or print?\n".
                    "   • print shows text now.\n".
                    "   • return gives a value back to the caller.\n".
                    "   • No return → the value is None.\n".
                    "\n".
                    "4) Watch out for mixing types during math.\n".
                    "   • '2' + 3 is not allowed; convert first: int('2') + 3.\n".
                    "\n".
                    "Use these rules to decide True or False for each statement.",
                'content'      => [
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'examples' => [
    [
        'title'   => 'Call a function that returns a value',
        'code'    => "def add(a, b):\n    return a + b\n\nprint(add(3, 4))",
        'explain' => 'This example defines a function to add two numbers. The function is called with arguments 3 and 4 and returns 7.',
        'expected_output' => "7"
    ],
    [
        'title'   => 'Calling a function without parentheses',
        'code'    => "def say_hello():\n    print('Hello!')\n\nsay_hello",
        'explain' => 'Here, we call the function say_hello. Without parentheses, the function is not called, so nothing happens.',
        'expected_output' => ""
    ],
    [
        'title'   => 'Function with default argument',
        'code'    => "def greet(name='Guest'):\n    return 'Hello ' + name\n\nprint(greet())",
        'explain' => 'This function has a default argument Guest. Since no argument is passed, the default is used.',
        'expected_output' => "Hello Guest"
    ],
    [
        'title'   => 'Adding two numbers and printing the result',
        'code'    => "def add_and_print(a, b):\n    result = a + b\n    print(result)\n\nadd_and_print(5, 7)",
        'explain' => 'This function adds two numbers and prints the result instead of returning it.',
        'expected_output' => "12"
    ],
    [
        'title'   => 'Return and print inside a function',
        'code'    => "def test_func():\n    return 'Return value'\n    print('This won’t print')\n\nprint(test_func())",
        'explain' => 'Once a return statement is reached, the function exits. Therefore, the print statement below return is never executed.',
        'expected_output' => "Return value"
    ]
    ],

                    'hints'      => [
                        "Call with parentheses: name().",
                        "Missing required argument → error.",
                        "print shows; return gives back a value.",
                        "No return → value is None.",
                        "Defaults fill in when you skip a parameter.",
                        "Convert text before math: int('2').",
                    ],
                    'questions' => [
                        [
                            'code'        => "def foo():\n    return 1\n\nfoo()",
                            'statement'   => "This returns 1 but prints nothing.",
                            'answer'      => true,
                            'explanation' => "There is no print; the call returns 1 quietly.",
                        ],
                        [
                            'code'        => "def bar(x):\n    print(x)\n\nbar()",
                            'statement'   => "This causes an error because x is missing.",
                            'answer'      => true,
                            'explanation' => "bar needs x; calling without it → TypeError.",
                        ],
                        [
                            'code'        => "def baz(a, b=2):\n    return a * b\n\nprint(baz(3))",
                            'statement'   => "This prints 6.",
                            'answer'      => true,
                            'explanation' => "Default b=2, so 3*2 = 6.",
                        ],
                        [
                            'code'        => "def f():\n    return\n\nprint(f())",
                            'statement'   => "This prints None.",
                            'answer'      => true,
                            'explanation' => "A bare return returns None.",
                        ],
                        [
                            'code'        => "def hello():\n    print('Hey')\n\nhello",
                            'statement'   => "Nothing happens because the function is not called.",
                            'answer'      => true,
                            'explanation' => "No parentheses means no call.",
                        ],
                        [
                            'code'        => "def haha():\n    return 'ha'\n\nprint(haha() + haha())",
                            'statement'   => "This prints hahaha.",
                            'answer'      => false,
                            'explanation' => "It prints 'haha' (two 'ha'), not four.",
                        ],
                        [
                            'code'        => "def m(x):\n    print(x)\n\ny = m(5)",
                            'statement'   => "After this, y equals 5.",
                            'answer'      => false,
                            'explanation' => "m prints 5 and returns None → y is None.",
                        ],
                        [
                            'code'        => "def add(a, b):\n    return a + b\n\nadd('2', 3)",
                            'statement'   => "This returns 5.",
                            'answer'      => false,
                            'explanation' => "TypeError: '2' is text. Use int('2') + 3.",
                        ],
                    ],
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // PRE assessment — reformatted code with one statement per line
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage6->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Functions (baseline)',
                'questions' => [
                    [
                        'prompt'  => 'What does def do?',
                        'options' => ['Returns a value', 'Defines a function', 'Calls a function', 'Prints text'],
                        'correct' => 'Defines a function',
                    ],
                    [
                        'prompt'  => 'How do you call a function named foo?',
                        'options' => ['foo', 'call foo()', 'foo()', 'def foo()'],
                        'correct' => 'foo()',
                    ],
                    [
                        'prompt'  =>
                            "What prints when you run:\n\n".
                            "def f():\n".
                            "    pass\n".
                            "\n".
                            "print(f())",
                        'options' => ['pass', 'None', 'Error', '0'],
                        'correct' => 'None',
                    ],
                    [
                        'prompt'  => 'Which line creates a function that returns 3?',
                        'options' => [
                            'def three(): return 3',
                            'return 3',
                            'print(3)',
                            'three()',
                        ],
                        'correct' => 'def three(): return 3',
                    ],
                    [
                        'prompt'  => 'How do you set a default parameter?',
                        'options' => ['def f(a=1):', 'def f(=1):', 'f(a=1):', 'def f(a):=1'],
                        'correct' => 'def f(a=1):',
                    ],
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // POST assessment — same formatting rule for clarity
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage6->id, 'type' => 'post'],
            [
                'title'     => 'Post: Functions',
                'questions' => [
                    [
                        'prompt'  =>
                            "Exact output?\n\n".
                            "def f(x):\n".
                            "    return x + 2\n".
                            "\n".
                            "print(f(3))",
                        'options' => ['5', '3', 'None', 'Error'],
                        'correct' => '5',
                    ],
                    [
                        'prompt'  => "What happens if you write foo instead of foo()?",
                        'options' => ['Calls foo', 'References foo', 'Error', 'Prints foo'],
                        'correct' => 'References foo',
                    ],
                    [
                        'prompt'  =>
                            "Given:\n\n".
                            "def g():\n".
                            "    print('Go')\n".
                            "\n".
                            "What prints when you call g()?",
                        'options' => ['Go', 'None', 'Error', 'g'],
                        'correct' => 'Go',
                    ],
                    [
                        'prompt'  =>
                            "Which code sets y to the value returned by f(), which is None?\n\n".
                            "def f():\n".
                            "    pass",
                        'options' => [
                            'y = f()',
                            'y = return',
                            'y = None',
                            'return y',
                        ],
                        'correct' => 'y = f()',
                    ],
                    [
                        'prompt'  =>
                            "Default parameter demo:\n\n".
                            "def h(a, b=3):\n".
                            "    return a * b\n".
                            "\n".
                            "print(h(2))",
                        'options' => ['6', '2', 'None', 'Error'],
                        'correct' => '6',
                    ],
                    [
                        'prompt'  => "Which returns nothing (i.e., returns None)?",
                        'options' => [
                            'def a(): return',
                            'def b(): pass',
                            'def c(): print(1)',
                            'All of the above',
                        ],
                        'correct' => 'All of the above',
                    ],
                ],
            ]
        );
    }
}
