<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class Stage5LoopsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // STAGE 5: Loops (while / for)
        // ─────────────────────────────────────────────
        $stage5 = Stage::updateOrCreate(
            ['slug' => 'loops-while-for'],
            ['title' => 'Stage 5: Loops (while / for)', 'display_order' => 5]
        );

        // ─────────────────────────────────────────────
        // Level 1 — What Are Loops? (drag_and_drop)
    Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 1],
            [
                'type'         => 'drag_drop',
                'title'        => 'What Are Loops?',
                'pass_score'   => 70,
                'instructions' => "Loops are like magical repeat buttons in programming. They help us do the same thing many times without writing the same code over and over.

Real Life Examples:
- Eating tacos until you're full
- Running laps around a track
- Reading each page in a book

Two Main Types:
- For Loops: When you know exactly how many times to repeat
- While Loops: When you repeat until something happens

Drag each description to the correct loop type.",
                'content' => [
                    'time_limit' => 300,
                    'max_hints' => 3,
                    'categories' => [
                        'For Loops' => [
                            'Eating 5 cookies one by one',
                            'Reading each page in a book',
                            'Putting candles on 12 cupcakes'
                        ],
                        'While Loops' => [
                            'Brushing teeth while timer is running',
                            'Dancing while music is playing',
                            'Playing a game while you have lives left'
                        ]
                    ],
                    'hints' => [
                        'Think about whether you know the exact number of repetitions',
                        'For loops are for counting, while loops are for conditions'
                    ],
                    'examples' => [
                        [
                            'title' => 'For Loop Example',
                            'code' => "for i in range(3):\n    print('Hello')",
                            'explain' => 'Repeats 3 times because range(3) makes 0,1,2.',
                            'expected_output' => "Hello\nHello\nHello"
                        ],
                        [
                            'title' => 'While Loop Example',
                            'code' => "count = 0\nwhile count < 3:\n    print('Hello')\n    count += 1",
                            'explain' => 'Keeps looping while count is less than 3.',
                            'expected_output' => "Hello\nHello\nHello"
                        ]
                    ]
                ]
            ]
        );


        // ─────────────────────────────────────────────
        // Level 2 — For Loops: The Counting Loop (multiple_choice)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 3],
            [
                'type'         => 'multiple_choice',
                'title'        => 'For Loops: The Counting Loop',
                'pass_score'   => 70,
                'instructions' => "For loops are perfect when you know exactly how many times you want to repeat something!
                How For Loops Work:
                • Use the for keyword
                • Need a variable name (like 'i' or 'cookie')
                • Use range() to specify how many times to repeat
                      Real World Examples:
                🧦 Putting on each sock from your drawer (you know how many socks)
                🎂 Putting candles on each cupcake (you know how many cupcakes)
                📝 Grading each paper in a stack (you know how many papers)
                Test your knowledge with these questions!",
                'content'      => [
                    'questions' => [
                        [
                            'question' => 'What does this code do?<br>for i in range(3):<br>&nbsp;&nbsp;print("Hello!")',
                            'options' => ['Prints "Hello!" 3 times', 'Prints "Hello!" 4 times', 'Prints "i" 3 times', 'Causes an error'],
                            'correct_answer' => 0,
                            'explanation' => 'Correct! range(3) creates numbers 0,1,2, so the loop runs 3 times, printing "Hello!" each time.'
                        ],
                        [
                            'question' => 'Which situation is best for a for loop?',
                            'options' => ['Eating until you\'re full', 'Reading each chapter in a 10-chapter book', 'Playing while you have energy', 'Sleeping until morning'],
                            'correct_answer' => 1,
                            'explanation' => 'Right! Reading each chapter in a 10-chapter book is perfect for a for loop because you know exactly how many times to repeat (10 times).'
                        ],
                        [
                            'question' => 'What is the range of numbers created by range(5)?',
                            'options' => ['0,1,2,3,4', '1,2,3,4,5', '0,1,2,3,4,5', '5,6,7,8,9'],
                            'correct_answer' => 0,
                            'explanation' => 'Exactly! range(5) creates numbers starting at 0 and ending at 4 (5 numbers total: 0,1,2,3,4).'
                        ]
                    ]
                ]
            ]
        );

        // ─────────────────────────────────────────────
        // Level 3 — While Loops: The Conditional Loop (tf1 - true/false)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 5],
            [
                'type'         => 'tf1',
                'title'        => 'While Loops: The Conditional Loop',
                'pass_score'   => 70,
                'instructions' => "While loops keep going as long as something is true, like playing until the bell rings!
                How While Loops Work:
                • Use the while keyword
                • Need a condition that must be true to keep looping
                • Will stop when the condition becomes false
                           Real World Examples:
                🚿 Showering while there's still hot water
                🎮 Playing a game while you still have lives
                🎵 Dancing while the music is playing
                Decide whether each statement about while loops is true or false!",
                'content'      => [
                    'questions' => [
                        [
                            'statement' => 'A while loop is best when you know exactly how many times to repeat something.',
                            'correct' => false,
                            'explanation' => 'Actually, while loops are best when you don\'t know exactly how many times to repeat, but want to continue while a condition is true.'
                        ],
                        [
                            'statement' => 'This while loop will print "Running" 5 times: count = 1 while count <= 5:<br>&nbsp;&nbsp;print("Running")<br>&nbsp;&nbsp;count += 1',
                            'correct' => true,
                            'explanation' => 'Correct! The loop starts at 1, increases by 1 each time, and stops when count becomes 6, so it runs exactly 5 times.'
                        ],
                        [
                            'statement' => 'A while loop always runs at least once, even if the condition is false from the beginning.',
                            'correct' => false,
                            'explanation' => 'No! If the condition is false from the start, the while loop won\'t run at all. It checks the condition first.'
                        ],
                        [
                            'statement' => 'While loops are good for situations like "keep eating while you\'re hungry".',
                            'correct' => true,
                            'explanation' => 'Exactly! "Keep eating while you\'re hungry" is a perfect while loop scenario - you don\'t know how many bites, just when to stop.'
                        ],
                        [
                            'statement' => 'This loop will run forever: while True: print("Looping")',
                            'correct' => true,
                            'explanation' => 'Right! "while True" means the condition is always true, so the loop will run forever unless we use "break" to exit.'
                        ]
                    ]
                ]
            ]
        );

        // ─────────────────────────────────────────────
        // Level 4 — The Magical range() Function (drag_and_drop)
        // ─────────────────────────────────────────────
           Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 2],
            [
                'type'         => 'drag_drop',
                'title'        => 'The Magical range() Function',
                'pass_score'   => 70,
                'instructions' => "range() is like a counting machine that helps for loops know how many times to repeat.

Three Ways to Use range():
- range(stop): Counts from 0 to stop-1
- range(start, stop): Counts from start to stop-1
- range(start, stop, step): Counts from start to stop-1, skipping by step

Real World Examples:
- range(8): Putting 8 candles on a cake (0-7)
- range(2, 6): Running laps 2,3,4,5
- range(0, 10, 2): A rabbit hopping by 2s: 0,2,4,6,8

Drag the sequences to the correct range().",
                'content' => [
                    'time_limit' => 300,
                    'max_hints' => 3,
                    'categories' => [
                        'range(5)' => ['0,1,2,3,4'],
                        'range(2,5)' => ['2,3,4'],
                        'range(0,10,2)' => ['0,2,4,6,8'],
                        'range(5,0,-1)' => ['5,4,3,2,1']
                    ],
                    'hints' => [
                        'range(stop) starts at 0 and ends at stop-1',
                        'range(start, stop) excludes the stop',
                        'Negative step means counting backwards'
                    ],
                    'examples' => [
                        [
                            'title' => 'Simple range',
                            'code' => "for i in range(3):\n    print(i)",
                            'explain' => 'Prints 0,1,2.',
                            'expected_output' => "0\n1\n2"
                        ],
                        [ 'title' => 'Basic range(stop)', 'code' => "for i in range(4):\n print(i)", 'explain' => 'Counts 0, 1, 2, 3 (stops before 4).', 'expected_output' => "0\n1\n2\n3" ],
                         [ 'title' => 'Range with start and stop', 'code' => "for i in range(2, 6):\n print(i)", 'explain' => 'Starts at 2, ends before 6 → prints 2,3,4,5.', 'expected_output' => "2\n3\n4\n5" ],
                          [ 'title' => 'Range with step', 'code' => "for i in range(1, 10, 3):\n print(i)", 'explain' => 'Starts at 1, adds step of 3 → prints 1,4,7.', 'expected_output' => "1\n4\n7" ], 
                          [ 'title' => 'Counting down', 'code' => "for x in range(5, 0, -2):\n print(x)", 'explain' => 'Negative step → prints 5,3,1.', 'expected_output' => "5\n3\n1" ],
                    ]
                ]
            ]
        );


        // ─────────────────────────────────────────────
        // Level 5 — For Loop Practice (drag_and_drop)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 4],
            [
                'type'         => 'drag_drop',
                'title'        => 'For Loop Practice',
                'pass_score'   => 70,
                'instructions' => "Practice building for loops. Remember: for [variable] in range(...):",
                'content' => [
                    'time_limit' => 300,
                    'max_hints' => 3,
                    'categories' => [
                        'Print numbers 0 to 4' => ["for i in range(5):", "print(i)"],
                        'Count from 5 to 9' => ["for number in range(5,10):", "print(number)"],
                        'Count even numbers 2 to 10' => ["for even in range(2,11,2):", "print(even)"]
                    ],
                    'hints' => [
                        'Always write for [var] in range(...)',
                        'Indent the code inside the loop'
                    ],
                    'examples' => [
                        [
                            'title' => 'Loop through fruits',
                            'code' => "for fruit in ['apple','banana','cherry']:\n    print(fruit)",
                            'explain' => 'Loops over each item in a list.',
                            'expected_output' => "apple\nbanana\ncherry"
                        ]
                    ]
                ]
            ]
        );


        // ─────────────────────────────────────────────
        // Level 6 — While Loop Practice (multiple_choice)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 6],
            [
                'type'         => 'multiple_choice',
                'title'        => 'While Loop Practice',
                'pass_score'   => 70,
                'instructions' => "Test your understanding of while loops. Remember: while [condition]:<br><br>
                <strong>Key Points:</strong><br>
                • The condition must eventually become false to avoid infinite loops<br>
                • You usually need to update a variable inside the loop<br>
                • While loops check the condition before each iteration<br><br>
                Choose the correct answers to these while loop questions!",
                'content'      => [
                    'questions' => [
                        [
                            'question' => 'How many times will this loop run?<br>count = 3<br>while count > 0:<br>&nbsp;&nbsp;print(count)<br>&nbsp;&nbsp;count -= 1',
                            'options' => ['3 times', '2 times', 'Forever (infinite)', '0 times'],
                            'correct_answer' => 0,
                            'explanation' => 'It starts at 3, then becomes 2, then 1, then 0. When count is 0, the condition is false, so it stops.'   
                        ],
                        [
                            'question' => 'What is missing from this loop?<br>number = 5<br>while number < 10:<br>&nbsp;&nbsp;print(number)',
                            'options' => ['The condition is wrong', 'Need to increase number', 'Need a for loop instead', 'Nothing is missing'],       
                            'correct_answer' => 1,
                            'explanation' => 'The number never changes, so the condition number < 10 will always be true, creating an infinite loop!'  
                        ],
                        [
                            'question' => 'What does this code do?<br>cookies = 5<br>while cookies > 0:<br>&nbsp;&nbsp;print("Yum!")<br>&nbsp;&nbsp;cookies -= 1<br>print("No more cookies!")',
                            'options' => ['Prints "Yum!" 5 times', 'Prints "Yum!" 4 times', 'Prints "Yum!" forever', 'Prints "No more cookies!" 5 times'],
                            'correct_answer' => 0,
                            'explanation' => 'It starts with 5 cookies, prints "Yum!" and decreases cookies until it reaches 0, then prints "No more cookies!"'
                        ]
                    ]
                ]
            ]
        );

        // ─────────────────────────────────────────────
        // Level 7 — Loop Olympics: For vs While (tf1 - true/false)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 7],
            [
                'type'         => 'tf1',
                'title'        => 'Loop Olympics: For vs While',
                'pass_score'   => 70,
                'instructions' => "Decide whether each statement is true or false. Think carefully about when to use for loops vs while loops!<br><br>
                <strong>Remember:</strong><br>
                • Use FOR loops when you know how many times to repeat<br>
                • Use WHILE loops when you want to repeat until a condition changes<br>
                • Both can often solve the same problem, but one is usually more appropriate<br><br>
                Test your knowledge with these true/false questions!",
                'content'      => [
                    'questions' => [
                        [
                            'statement' => 'A for loop is better when you know exactly how many times to repeat something.',
                            'correct' => true,
                            'explanation' => 'Yes! For loops are perfect for counting or going through a known number of items.'
                        ],
                        [
                            'statement' => 'A while loop always needs a counter variable like i = 0.',
                            'correct' => false,
                            'explanation' => 'Not always! While loops can use any condition, not just counters. For example: while raining: bring_umbrella()'
                        ],
                        [
                            'statement' => 'range(5) creates the numbers 0, 1, 2, 3, 4.',
                            'correct' => true,
                            'explanation' => 'Correct! range(stop) starts at 0 and goes up to but not including the stop value.'
                        ],
                        [
                            'statement' => 'A while loop will always run at least once.',
                            'correct' => false,
                            'explanation' => 'Not true! If the condition is false from the beginning, the while loop won\'t run at all.'
                        ],
                        [
                            'statement' => 'for i in range(3): will run exactly 3 times.',
                            'correct' => true,
                            'explanation' => 'Yes! range(3) creates 3 numbers: 0, 1, and 2, so the loop runs 3 times.'
                        ]
                    ]
                ]
            ]
        );

        // ─────────────────────────────────────────────
        // Level 8 — Fix the Loop Errors (reorder)
        // ─────────────────────────────────────────────
      Level::updateOrCreate(
    ['stage_id' => $stage5->id, 'index' => 8],
    [
        'type'       => 'reorder',
        'title'      => 'Fix the Loop Errors',
        'pass_score' => 70,
        'instructions' => "Arrange the code lines in the correct order to make working loops. Watch out for infinite loops!\n\nKey steps for while loops:\n1) Initialize the counter variable\n2) Set the while condition\n3) Do the action inside the loop\n4) Update the counter variable\n\nDrag the lines into the correct order.",
        'content' => [
            'time_limit' => 240,
            'max_hints'  => 3,
            'tasks' => [
                [
                    'title' => 'Countdown from 3 to 1',
                    'lines' => [
                        'while count > 0:',
                        'print(count)',
                        'count = 3',
                        'count -= 1'
                    ],
                    // indices into the "lines" array in the correct order
                    'solution' => [2, 0, 1, 3],
                    'correct_output' => "3\n2\n1"
                ],
                [
                    'title' => 'Print even numbers 2, 4, 6',
                    'lines' => [
                        'for num in range(2, 7, 2):',
                        'print(num)'
                    ],
                    'solution' => [0, 1],
                    'correct_output' => "2\n4\n6"
                ],
                [
                    'title' => 'Ask for password until correct',
                    'lines' => [
                        'while password != "secret123":',
                        'password = input("Enter password: ")',
                        'print("Access granted!")'
                    ],
                    // initialize first, then loop, then success message
                    'solution' => [1, 0, 2],
                    'correct_output' => "Enter password: ...\nAccess granted!"
                ]
            ],
            'hints' => [
                'Always initialize variables before the loop.',
                'Make sure the condition can eventually become false.',
                'Update variables inside the loop to avoid infinite loops.'
            ]
        ]
    ]
);

        // Pre and Post Assessments (unchanged)
        Assessment::updateOrCreate(
            ['stage_id' => $stage5->id, 'type' => 'pre'],
            [
                'title' => 'Pre: Loops (baseline)',
                'questions' => json_encode([
                    [
                        'prompt' => 'What does range(3) produce in a for loop?',
                        'options' => ['0,1,2', '1,2,3', '0,1,2,3', 'Error'],
                        'correct' => '0,1,2',
                    ],
                    [
                        'prompt' => 'Which part avoids infinite loops in while?',
                        'options' => ['Initialization', 'Condition', 'Update', 'Import'],
                        'correct' => 'Update',
                    ],
                    [
                        'prompt' => 'What does break do?',
                        'options' => ['Skips to next iteration', 'Exits loop', 'Restarts loop', 'Errors'],
                        'correct' => 'Exits loop',
                    ],
                    [
                        'prompt' => 'What does continue do?',
                        'options' => ['Exits loop', 'Skips current iteration', 'Ends program', 'No effect'],
                        'correct' => 'Skips current iteration',
                    ],
                    [
                        'prompt' => 'How many times prints in for i in range(2,5): print(i)?',
                        'options' => ['2', '3', '4', 'Error'],
                        'correct' => '3',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        Assessment::updateOrCreate(
            ['stage_id' => $stage5->id, 'type' => 'post'],
            [
                'title' => 'Post: Loops',
                'questions' => json_encode([
                    [
                        'prompt' => "Exact output? for i in range(1,4): print(i*i)",
                        'options' => ['1 4 9', '1 2 3', '1 4', 'Error'],
                        'correct' => '1 4 9',
                    ],
                    [
                        'prompt' => "Will this loop stop? j=5 while j>0: j+=1",
                        'options' => ['Yes', 'No', 'Error', 'Only with break'],
                        'correct' => 'No',
                    ],
                    [
                        'prompt' => "What prints? for x in range(0,5,2): print(x)",
                        'options' => ['0 2 4', '1 3', '0 2 4 6', 'Error'],
                        'correct' => '0 2 4',
                    ],
                    [
                        'prompt' => "Given: i=0 while i<3: print(i) if i==1: break i+=1",
                        'options' => ['0 1', '0 1 2', '0', 'Infinite'],
                        'correct' => '0 1',
                    ],
                    [
                        'prompt' => "Which step is missing? i=0 while i<4: print(i) # ???",
                        'options' => ['i += 1', 'break', 'continue', 'pass'],
                        'correct' => 'i += 1',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
}