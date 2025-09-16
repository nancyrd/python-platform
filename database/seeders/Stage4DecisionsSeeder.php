<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class Stage4DecisionsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // STAGE 4: Decisions (if/elif/else)
        // ─────────────────────────────────────────────────────────────
        $stage4 = Stage::updateOrCreate(
            ['slug' => 'decisions-if-elif-else'],
            ['title' => 'Stage 4: Decisions (if / elif / else)', 'display_order' => 4]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 1 — Yes/No logic basics (multiple_choice)
        // Goals: comparisons, == != > < >= <=, and/or/not; tiny one-branch if
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage4->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Yes/No logic basics',
                'pass_score'   => 50,
                'instructions' => 'In this lesson you’ll learn how to compare values and build simple if-blocks.  
Compare two values with operators:  
  • == checks equality (e.g. x == 5)  
  • != checks inequality (e.g. y != 0)  
  • > / < / >= / <= compare magnitude.  
  
Combine comparisons with logical operators:  
  • and → both must be True  
  • or  → at least one True  
  • not → flips a True/False value  
  
A one-branch if runs its block only when the condition is True. Practice predicting whether tiny if-statements will run.',
                'content'      => [
                    'intro'        => 'True/False logic decides which path runs. Use comparisons then link them with and/or/not.',
                    'instructions' => 'Choose the correct answer for each question.',
                    'questions'    => [
                        [
                            'question'        => 'What does == do?',
                            'options'         => ['Assigns a value', 'Checks equality', 'Checks inequality', 'Combines conditions'],
                            'correct_answer'  => 1,
                            'explanation'     => '== compares two values for equality. (= assigns.)'
                        ],
                        [
                            'question'        => 'Which is True for x = 7?',
                            'options'         => ['x < 0', 'x == 7', 'x != 7', 'x <= 6'],
                            'correct_answer'  => 1,
                            'explanation'     => '7 equals 7.'
                        ],
                        [
                            'question'        => 'For age = 10, will this print?if age >= 10  print("Ten or more")',
                            'options'         => ['Yes, prints "Ten or more"', 'No, it prints nothing', 'Error'],
                            'correct_answer'  => 0,
                            'explanation'     => '10 >= 10 → condition is True.'
                        ],
                        [
                            'question'        => 'Which expression is True when x = 4 and y = 9?',
                            'options'         => [
                                'x > 5 and y > 5',
                                'x > 5 or y > 5',
                                'not(y > 5)',
                                'x == 5 and y == 9'
                            ],
                            'correct_answer'  => 1,
                            'explanation'     => 'y > 5 is True, so (x>5 or y>5) is True.'
                        ],
                        [
                            'question'        => 'Pick the line that converts age (text) to a number for printing:\nprint("Age:", ___(age))',
                            'options'         => ['str', 'int', 'float', 'bool'],
                            'correct_answer'  => 1,
                            'explanation'     => 'Use int(age) if it contains digits like "15".'
                        ],
                        [
                            'question'        => 'Truth table mini: What is the value of (True and not False)?',
                            'options'         => ['True', 'False', 'Error', 'None'],
                            'correct_answer'  => 0,
                            'explanation'     => 'not False → True; True and True → True.'
                        ],
                        [
                            'question'        => 'For score = 55, what prints?\n\nif score >= 60:\n    print("Pass")\nprint("Done")',
                            'options'         => ['Pass\nDone', 'Done', 'Pass', 'Nothing'],
                            'correct_answer'  => 1,
                            'explanation'     => 'score>=60 is False → no "Pass"; always prints "Done".'
                        ],
                    ],
                    'hints'       => [
                        '== compares; != means not equal.',
                        'and needs both True; or needs at least one True; not flips truth.',
                        'if block runs only when its condition is True.'
                    ],
                    'time_limit'  => 240,
                    'max_hints'   => 4 ,
                    'examples'    => [
                [
                    'title'   => 'Age check',
                    'code'    => "age = 10\nif age >= 10:\n    print('Ten or more')\nelse:\n    print('Too young')",
                    'explain' => 'Checks if the age is 10 or more.',
                    'expected_output' => "Ten or more"
                ],
                [
                    'title'   => 'Temperature check',
                    'code'    => "temperature = 25\nif temperature > 30:\n    print('Too hot')\nelse:\n    print('Nice weather')",
                    'explain' => 'Checks if the temperature is greater than 30.',
                    'expected_output' => "Nice weather"
                ],
                [
                    'title'   => 'Number check',
                    'code'    => "x = 4\ny = 10\nif x < y:\n    print('x is less than y')\nelse:\n    print('x is greater than or equal to y')",
                    'explain' => 'Compares if x is less than y.',
                    'expected_output' => "x is less than y"
                ],
            ],
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 2 — Build the branch (drag_drop)
        // We’ll use categories to sort cards into: Conditions / Actions / Not part of if
        // This matches your existing drag_drop blade contract.
        // ─────────────────────────────────────────────────────────────
Level::updateOrCreate(
    ['stage_id' => $stage4->id, 'index' => 2],
    [
        'type'         => 'drag_drop',
        'title'        => 'Build the branch',
        'pass_score'   => 50,
        'instructions' => 'Think of if/else as making decisions. It’s like when you make choices in real life:  
  • If something is true, do one thing;  
  • If not, do something else.  
  
For example, if you’re hungry, you eat food. If not, you drink water.  

In Python, you can make choices using:  
  • if → checks if a condition is true  
  • elif → checks if another condition is true when the first one isn’t  
  • else → runs when none of the conditions are true  
  
Let’s build these branches using conditions and actions in the right place.',
                'content'      => [
                    'categories' => [
                        '🧠 Conditions' => [
                            'x > 10',
                            'age >= 18',
                            'score < 50',
                            'color == "red"',
                            'not is_member'
                        ],
                        '⚙ Actions' => [
                            'print("Too big")',
                            'print("Adult")',
                            'print("Retry")',
                            'print("Stop")',
                            'print("Guest only")'
                        ],
                        '🚫 Not part of if' => [
                            'import math',
                            'x = 0',
                            'print("Hello!")',
                            'def greet(): pass'
                        ]
                    ],
                    
                    'hints'      => [
                        'A condition is something that becomes True/False.',
                        'An action is what runs when its related condition is True.',
                        'Setup code (imports, defs, plain assignments) is not part of the if branching itself.'
                    ],
                      'examples'   => [
          [
              'title'   => 'Voting age check',
              'code'    => "age = 16\nif age >= 18:\n    print('You can vote')\nelse:\n    print('Sorry, too young')",
              'explain' => "Checks if someone is old enough to vote.",
              'expected_output' => "Sorry, too young"
          ],
          [
              'title'   => 'Shopping money check',
              'code'    => "money = 30\nprice = 50\nif money >= price:\n    print('You can buy it!')\nelse:\n    print('Not enough money')",
              'explain' => "Compares money with price before buying.",
              'expected_output' => "Not enough money"
          ],
          [
              'title'   => 'Traffic light',
              'code'    => "color = 'red'\nif color == 'red':\n    print('Stop at red light')\nelif color == 'green':\n    print('Go')\nelse:\n    print('Slow down')",
              'explain' => "A classic everyday if/elif/else example: traffic rules.",
              'expected_output' => "Stop at red light"
          ]
      ],
                    'time_limit'  => 240,
                    'max_hints'   => 4
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 3 — Will it print? (true/false via type "tf1")
        // Given values & conditions, decide if message prints (incl. a tiny nested case)
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage4->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Will it print?',
                'pass_score'   => 50,
                'instructions' => "In this level, you will predict whether a statement will print based on the conditions provided.  
You have learned how to build if/elif/else blocks and handle comparisons, logical operators (and/or/not), and nested conditions.

Now, it's time to test your understanding by:
- Predicting whether a print statement will be executed based on the condition.
- Identifying whether the correct branch (if, elif, else) will run, and why.

Key Points:
- Only one branch in an if/elif/else structure will run. The first True branch is the one that executes.
- Nested ifs inside an if/else block only execute if the outer condition is True.
- Logical operators (and, or, not) may change the outcome based on the combination of conditions.

In each question, you'll decide whether the print statement inside the code will run based on the condition and print the result accordingly.",
                'content'      => [
                    'questions' => [
                        [
                            'code'        => "age = 16\nif age >= 18:\n    print('Adult')",
                            'statement'   => 'This prints Adult',
                            'answer'      => false,
                            'explanation' => '16 >= 18 is False → nothing prints.'
                        ],
                        [
                            'code'        => "x = 12\nif x > 10:\n    print('Too big')",
                            'statement'   => 'This prints Too big',
                            'answer'      => true,
                            'explanation' => '12 > 10 is True.'
                        ],
                        [
                            'code'        => "score = 70\nif score >= 90:\n    print('A')\nelif score >= 60:\n    print('Pass')\nelse:\n    print('Retake')",
                            'statement'   => 'This prints Pass',
                            'answer'      => true,
                            'explanation' => 'First condition False; elif True → prints Pass, skips else.'
                        ],
                        [
                            'code'        => "is_member = False\nif not is_member:\n    print('Guest')",
                            'statement'   => 'This prints Guest',
                            'answer'      => true,
                            'explanation' => 'not False → True.'
                        ],
                        [
                            'code'        => "a,b = 3,7\nif a > 5 and b > 5:\n    print('Both big')",
                            'statement'   => 'This prints Both big',
                            'answer'      => false,
                            'explanation' => 'a>5 is False; and needs both True.'
                        ],
                        [
                            // small nested case
                            'code'        => "temp = 5\nif temp >= 0:\n    if temp < 10:\n        print('Cool')",
                            'statement'   => 'This prints Cool',
                            'answer'      => true,
                            'explanation' => 'Both conditions are True (0<=temp<10).'
                        ],
                    ],
                    'hints'      => [
                        'elif runs only if previous conditions were False.',
                        'Only the first True branch in if/elif/else executes.',
                        'and: both must be True; or: at least one True; not: flips truth.'
                    ],
                    'time_limit'  => 240,
                    'max_hints'   => 4
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        //  PRE / POST assessments for Stage 4
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
    ['stage_id' => $stage4->id, 'type' => 'pre'],
    [
        'title'     => 'Pre: Decisions (baseline)',
        'questions' => [
            [
                'prompt'  => 'Which operator checks equality?',
                'options' => ['=', '==', '!=', '>='],
                'correct' => '==',
            ],
            [
                'prompt'  => 'For x = 3, which is True?',
                'options' => ['x > 3', 'x >= 3', 'x != 3', 'x < 0'],
                'correct' => 'x >= 3',
            ],
            [
                'prompt'  => 'Which condition is True when a=2, b=9?',
                'options' => ['a > 5 and b > 5', 'a > 5 or b > 5', 'not(b > 5)', 'a == 5 and b == 9'],
                'correct' => 'a > 5 or b > 5',
            ],
            [
                'prompt'  => 'What does "!=" mean?',
                'options' => ['Equal to', 'Not equal to', 'Assign value', 'Greater or equal'],
                'correct' => 'Not equal to',
            ],
            [
                'prompt'  => 'Which line is valid Python?',
                'options' => [
                    'if x = 5: print("hi")',
                    'if x == 5: print("hi")',
                    'if (x equal 5): print("hi")',
                    'if x === 5: print("hi")'
                ],
                'correct' => 'if x == 5: print("hi")',
            ],
            [
                'prompt'  => 'With flag=True, what does "if not flag:" do?',
                'options' => ['Runs because flag is True', 'Skips because not True → False', 'Error', 'Always runs'],
                'correct' => 'Skips because not True → False',
            ],
        ],
    ]
);


       Assessment::updateOrCreate(
    ['stage_id' => $stage4->id, 'type' => 'post'],
    [
        'title'     => 'Post: Decisions',
        'questions' => [
            [
                'prompt'  => "Exact output?\n\nx=10\nif x>10:\n    print('A')\nelif x==10:\n    print('B')\nelse:\n    print('C')",
                'options' => ['A', 'B', 'C', 'Nothing'],
                'correct' => 'B',
            ],
            [
                'prompt'  => 'Pick the True statement for y=0:',
                'options' => ['y < 0 and y == 0', 'y < 0 or y == 0', 'not(y == 0)', 'y > 0'],
                'correct' => 'y < 0 or y == 0',
            ],
            [
                'prompt'  => 'Which runs only if NO previous if/elif was True?',
                'options' => ['if', 'elif', 'else', 'pass'],
                'correct' => 'else',
            ],
            [
                'prompt'  => "Given temp=15:\n\nif temp<10:\n    print('Cold')\nelif temp<20:\n    print('Cool')\nelse:\n    print('Warm')",
                'options' => ['Cold', 'Cool', 'Warm', 'Nothing'],
                'correct' => 'Cool',
            ],
            [
                'prompt'  => "x=5\ny=8\nif x>0 and y>0:\n    print('Positive pair')",
                'options' => ['Positive pair', 'Nothing', 'Error', '0'],
                'correct' => 'Positive pair',
            ],
            [
                'prompt'  => "flag=False\nif not flag:\n    print('Off')\nelse:\n    print('On')",
                'options' => ['Off', 'On', 'Nothing', 'Error'],
                'correct' => 'Off',
            ],
            [
                'prompt'  => 'Which one is the correct full chain?',
                'options' => [
                    'if / elif / else',
                    'if / else if / else',
                    'if / then / else',
                    'if / elseif / end'
                ],
                'correct' => 'if / elif / else',
            ],
        ],
    ]
);

    }
}