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

Loops usually use Counters:
What is a Counter in Loops?
Think of a counter like a tally mark sheet when counting things.
Real Life Example: Counting Apples
Imagine you have a basket of apples and want to count them one by one:

What the Counter Does:
Starts at zero (no apples counted yet)
Adds 1 each time we count an apple
Remembers the total so far
Tells us the final count when done

Like Counting on Your Fingers:

Start with 0 fingers up
Count an apple - put up 1 finger
Count another apple - put up 2 fingers
Keep going until all apples are counted



Why Use Counters:
Instead of trying to remember 'Did I count 47 or 48 apples?', the computer remembers for you perfectly. It's like having a friend who never loses count no matter how many things you're counting.




The counter is just a box that holds a number, and we keep putting bigger numbers in the box as we count more things.



But sometimes we need to put smaller numbers in the box instead - like when we're counting down days until vacation, eating cookies from a jar, or watching our phone battery go from 100% to 0%. In these cases, we start with a big number and keep putting smaller numbers in the box until we reach what we're waiting for. Whether the numbers go up or down, the box always remembers exactly where we are in our counting..



Look at the examples below , to understand the concept of loops, do not stress about the code yet, we will learn that in the next levels.",
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
    'title' => 'Counter Usage',
    'code' => "apples_counted = 0\napples_counted = apples_counted + 1\nprint('First apple counted:', apples_counted)\napples_counted = apples_counted + 1\nprint('Second apple counted:', apples_counted)\napples_counted = apples_counted + 1\nprint('Third apple counted:', apples_counted)\nprint('Total apples:', apples_counted)",
    'explain' => 'Counter starts at 0, then adds 1 each time we count an apple. Shows how the counter keeps track of our total.',
    'expected_output' => "First apple counted: 1\nSecond apple counted: 2\nThird apple counted: 3\nTotal apples: 3"
                        ],
                     [
    'title' => 'Saving Coins',
    'code' => "coins = 0\ncoins = coins + 2\nprint('Found 2 coins! Total:', coins)\ncoins = coins + 3\nprint('Found 3 more! Total:', coins)\ncoins = coins + 1\nprint('Found 1 more! Total:', coins)",
    'explain' => 'Counter increases by different amounts. Start with 0, keep adding coins found.',
    'expected_output' => "Found 2 coins! Total: 2\nFound 3 more! Total: 5\nFound 1 more! Total: 6"
],

[
    'title' => 'Growing Height',
    'code' => "height = 120\nheight = height + 5\nprint('Grew 5cm! Now:', height, 'cm')\nheight = height + 3\nprint('Grew 3cm more! Now:', height, 'cm')",
    'explain' => 'Counter tracks growth. Start at 120cm, add growth each year.',
    'expected_output' => "Grew 5cm! Now: 125 cm\nGrew 3cm more! Now: 128 cm"
],
                     [
    'title' => 'Eating Cookies',
    'code' => "cookies = 10\nprint('Starting with', cookies, 'cookies')\ncookies = cookies - 1\nprint('Ate one! Left:', cookies)\ncookies = cookies - 2\nprint('Ate 2 more! Left:', cookies)",
    'explain' => 'Counter decreases as we eat cookies. Start with 10, subtract what we eat.',
    'expected_output' => "Starting with 10 cookies\nAte one! Left: 9\nAte 2 more! Left: 7"
],


[
    'title' => 'Morning Exercise Routine',
    'code' => "pushups_done = 0\nfor exercise in ['pushup', 'pushup', 'pushup', 'pushup', 'pushup']:\n    pushups_done = pushups_done + 1\n    print('Completed pushup number', pushups_done)\nprint('Workout complete! Total pushups:', pushups_done)",
    'explain' => 'Loop does 5 pushups, counter tracks how many completed. Each loop adds 1 to counter.',
    'expected_output' => "Completed pushup number 1\nCompleted pushup number 2\nCompleted pushup number 3\nCompleted pushup number 4\nCompleted pushup number 5\nWorkout complete! Total pushups: 5"
],

[
    'title' => 'Washing Dishes',
    'code' => "dishes_cleaned = 0\nfor dish in ['plate', 'cup', 'bowl', 'spoon']:\n    dishes_cleaned = dishes_cleaned + 1\n    print('Washed', dish + '. Total clean:', dishes_cleaned)\nprint('Kitchen is clean! Washed', dishes_cleaned, 'dishes')",
    'explain' => 'Loop goes through each dirty dish, counter tracks total cleaned dishes.',
    'expected_output' => "Washed plate. Total clean: 1\nWashed cup. Total clean: 2\nWashed bowl. Total clean: 3\nWashed spoon. Total clean: 4\nKitchen is clean! Washed 4 dishes"
],[
    'title' => 'Waiting for Bus',
    'code' => "minutes_waited = 0\nwhile minutes_waited < 5:\n    minutes_waited = minutes_waited + 1\n    print('Waited', minutes_waited, 'minutes for bus')\nprint('Bus arrived after', minutes_waited, 'minutes!')",
    'explain' => 'Counter tracks how long we wait. While loop continues until we reach 5 minutes.',
    'expected_output' => "Waited 1 minutes for bus\nWaited 2 minutes for bus\nWaited 3 minutes for bus\nWaited 4 minutes for bus\nWaited 5 minutes for bus\nBus arrived after 5 minutes!"
],

[
    'title' => 'Knocking on Door',
    'code' => "knocks = 0\nwhile knocks < 3:\n    knocks = knocks + 1\n    print('Knock number', knocks, '- Hello?')\nprint('Gave up after', knocks, 'knocks')",
    'explain' => 'Counter tracks knocks. While loop keeps knocking until we reach 3 knocks.',
    'expected_output' => "Knock number 1 - Hello?\nKnock number 2 - Hello?\nKnock number 3 - Hello?\nGave up after 3 knocks"
]
                    ]
                ]
            ]
        );


        // ─────────────────────────────────────────────
        // Level 3 — For Loops: The Counting Loop (multiple_choice)
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
                    'question' => "What does this code do?\n\nfor i in range(3):\n    print(\"Hello!\")",
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
                ],
                [
                    'type' => 'code',
                    'question' => "Write a for loop that prints numbers from 1 to 5, each on a new line.",
                    'expected_output' => "1\n2\n3\n4\n5",
                    'starter_code' => "# Write your for loop here\n",
                    'solution' => "for i in range(1, 6):\n    print(i)",
                    'explanation' => 'Use range(1, 6) to generate numbers from 1 to 5. The loop will print each number on a new line.'
                ]
            ],
            'examples'    => [
                [
                    'title'   => 'Simple range example',
                    'code'    => "for i in range(3):\n    print('Hello!')",
                    'explain' => 'This code will print "Hello!" 3 times, since range(3) generates 0, 1, 2.',
                    'expected_output' => "Hello!\nHello!\nHello!"
                ],
                [
                    'title'   => 'Counting from 0 to 4',
                    'code'    => "for i in range(5):\n    print(i)",
                    'explain' => 'This prints numbers from 0 to 4. range(5) generates numbers 0, 1, 2, 3, 4.',
                    'expected_output' => "0\n1\n2\n3\n4"
                ],
                [
                    'title'   => 'Counting in steps of 2',
                    'code'    => "for i in range(0, 10, 2):\n    print(i)",
                    'explain' => 'This code prints numbers from 0 to 8 in steps of 2. range(0, 10, 2) gives: 0, 2, 4, 6, 8.',
                    'expected_output' => "0\n2\n4\n6\n8"
                ],
                [
                    'title'   => 'Counting down from 5',
                    'code'    => "for i in range(5, 0, -1):\n    print(i)",
                    'explain' => 'This code prints numbers from 5 down to 1. The step is -1, counting backwards.',
                    'expected_output' => "5\n4\n3\n2\n1"
                ]
            ]
        ]
    ]
);

        // ─────────────────────────────────────────────
        // Level 5 — While Loops: The Conditional Loop (tf1 - true/false)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 5],
            [
                'type'         => 'tf1',
                'title'        => 'While Loops: The Conditional Loop',
                'pass_score'   => 70,
                 'instructions' => "
How While Loops Work:
- Use the `while` keyword to start the loop.
- Condition: The loop keeps running as long as the condition is True.
- Stop Condition: The loop will stop when the condition becomes False.
  


---

Key Concepts:
- Condition check: The condition is checked before each iteration. If it's false from the beginning, the loop will never run.
- Infinite loop: If the condition never becomes false, the loop will run forever. Always make sure the condition will eventually be false to prevent this.
- Loop control: Inside the loop, you should include logic that eventually makes the condition false, often by updating variables involved in the condition.

---

Real World Examples:
- 🚿 Showering while there's still hot water**: The loop continues while the water is hot.
- 🎮 Playing a game while you still have lives**: The loop keeps running until you run out of lives.
- 🎵 Dancing while the music is playing**: The loop stops when the music stops.

While loops often need counters to control when they stop. Without counters, while loops could run forever!
Why Counters Are Essential?
Prevent infinite loops - Without a counter, the loop might never stop
Track progress - Shows how many times we've done something
Set limits - Stops when we reach our goal


The Pattern:
Initialize counter before the loop
Check counter in the while condition
Update counter inside the loop
Use counter to show progress or make decisions",
                'content'      => [
                    'questions' => [
                        [
                            'statement' => 'A while loop is best when you know exactly how many times to repeat something.',
                            'correct' => false,
                            'explanation' => 'Actually, while loops are best when you don\'t know exactly how many times to repeat, but want to continue while a condition is true.'
                        ],
                        [
                            'code'=> "count = 1\n while count <= 5:\n    print(\"Running\")\n    count += 1",
  'statement' => "This while loop will print 'Running 5 times",
'answer' => true,
    'explanation' => 'Correct! The loop starts at 1, increases by 1 each time, and stops when count becomes 6, so it runs exactly 5 times.'
],

                        [
                            'statement' => 'A while loop always runs at least once, even if the condition is false from the beginning.',
                            'answer' => false,
                            'explanation' => 'No! If the condition is false from the start, the while loop won\'t run at all. It checks the condition first.'
                        ],
      
                        [
                            'statement' => 'While loops are good for situations like "keep eating while you\'re hungry".',
                            'answer' => true,
                            'explanation' => 'Exactly! "Keep eating while you\'re hungry" is a perfect while loop scenario - you don\'t know how many bites, just when to stop.'
                        ],
[
    'type'            => 'code',
    'question'        => "Count down from 5 to 1 and print each number.",
    'starter_code'    => "countdown = 5\n# Use while loop to count down\n# Print each number from 5 to 1\n# Decrease countdown by 1 each time\n",
    'expected_output' => "5\n4\n3\n2\n1",
    'solution'        => "countdown = 5\nwhile countdown >= 1:\n    print(countdown)\n    countdown -= 1",
    'explanation'     => "Start at 5, print the number, then subtract 1. Continue while countdown is 1 or greater."
],
                        [
                            'statement' => 'This loop will run forever: while True: print("Looping")',
                            'answer' => true,
                            'explanation' => 'Right! "while True" means the condition is always true, so the loop will run forever unless we use "break" to exit.'
                        ]
                        ],
                         'examples'    => [
                [
                    'title'   => 'Keep counting down from 5',
                    'code'    => "count = 5\nwhile count > 0:\n    print(count)\n    count -= 1",
                    'explain' => 'This loop will print numbers from 5 down to 1. The loop continues until count is no longer greater than 0.',
                    'expected_output' => "5\n4\n3\n2\n1"
                ],
                [
                    'title'   => 'Keep playing while you have lives left',
                    'code'    => "lives = 3\nwhile lives > 0:\n    print('You have', lives, 'lives left')\n    lives -= 1",
                    'explain' => 'This simulates a game where you keep playing until you run out of lives. The loop runs while lives are greater than 0.',
                    'expected_output' => "You have 3 lives left\nYou have 2 lives left\nYou have 1 lives left"
                ],
                
                [
                    'title'   => 'Counting down with a delay',
                    'code'    => "import time\ncount = 3\nwhile count > 0:\n    print(count)\n    time.sleep(1)\n    count -= 1",
                    'explain' => 'This loop counts down from 3 to 1, with a 1-second delay between each print.',
                    'expected_output' => "3\n2\n1"
                ],
                [
                    'title'   => 'Running a loop until condition is met',
                    'code'    => "password = ''\nwhile password != 'secret':\n    password = input('Enter password: ')\nprint('Access granted!')",
                    'explain' => 'This loop keeps asking for the password until the user enters "secret". The loop continues until the condition is satisfied.',
                    'expected_output' => "Enter password: ...\nAccess granted!" // Simulated input
                ],
            ],
                ]
            ]
        );

        // ─────────────────────────────────────────────
        // Level 2 — The Magical range() Function (drag_and_drop)
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

• Use Range Instead of Writing Each Number",
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
                        [ 'title' => 'Basic range(stop)', 'code' => "for i in range(4):\n    print(i)", 'explain' => 'Counts 0, 1, 2, 3 (stops before 4).', 'expected_output' => "0\n1\n2\n3" ],
                         [ 'title' => 'Range with start and stop', 'code' => "for i in range(2, 6):\n    print(i)", 'explain' => 'Starts at 2, ends before 6 → prints 2,3,4,5.', 'expected_output' => "2\n3\n4\n5" ],
                          [ 'title' => 'Range with step', 'code' => "for i in range(1, 10, 3):\n    print(i)", 'explain' => 'Starts at 1, adds step of 3 → prints 1,4,7.', 'expected_output' => "1\n4\n7" ], 
                          [ 'title' => 'Counting down', 'code' => "for x in range(5, 0, -2):\n    print(x)", 'explain' => 'Negative step → prints 5,3,1.', 'expected_output' => "5\n3\n1" ],
                    ]
                ]
            ]
        );


        // ─────────────────────────────────────────────
        // Level 4 — For Loop Practice (drag_and_drop)
        // ─────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 4],
            [
                'type'         => 'drag_drop',
                'title'        => 'For Loop Practice',
                'pass_score'   => 70,
                'instructions' => "For Loops is used with Lists also: 
                    -With range() - You get numbers: 0, 1, 2, 3, 4...
                    -With lists - You get actual things you care about: 




Think of a for loop with a list like going through your shopping bag item by item.
Real Life Example: Checking Your Groceries
You have a shopping bag with specific items, and you want to check each one.
Moreover you can use for loops with lists of words, names, or any items you want to process one by one.

There is also something called nested loops - which is a loop inside another loop. This is useful for things like grids or tables where you have rows and columns.",
                'content' => [
                    'time_limit' => 300,
                    'max_hints' => 3,
                    'categories' => [
                        'Print numbers 0 to 4' => ["for i in range(5):", "print(i)"],
                        'Count from 5 to 9' => ["for number in range(5,10):", "print(number)"],
                        'Count even numbers 2 to 10' => ["for even in range(2,11,2):", "print(even)"],
                        'Count to 2, twice' => ["for time in range(2):", "  for num in range(3):", "    print(num)"]
                    ],
                    'hints' => [
                        'Always write for [var] in range(...)',
                        'Indent the code inside the loop',
                        'Start with the outer loop first, then ask "what do I want to repeat inside each step?"'
                    ],
                     'examples' => [
                [
                    'title' => 'Loop through fruits',
                    'code' => "for fruit in ['apple','banana','cherry']:\n    print(fruit)",
                    'explain' => 'Loops over each item in a list and prints it.',
                    'expected_output' => "apple\nbanana\ncherry"
                ],
                [
                    'title' => 'Sum numbers 1 to 10',
                    'code' => "total = 0\nfor num in range(1, 11):\n    total += num\nprint(total)",
                    'explain' => 'Uses a for loop to sum the numbers from 1 to 10.',
                    'expected_output' => "55"
                ],
                [
                    'title' => 'Print squares of numbers 1 to 5',
                    'code' => "for i in range(1, 6):\n    print(i * i)",
                    'explain' => 'Prints the square of each number from 1 to 5.',
                    'expected_output' => "1\n4\n9\n16\n25"
                ],
                [
                    'title' => 'Iterate through a string',
                    'code' => "for char in 'hello':\n    print(char)",
                    'explain' => 'Loops through each character in the string hello.',
                    'expected_output' => "h\ne\nl\nl\no"
                ],
                [
                    'title' => 'Nested loops for multiplication table',
                    'code' => "for i in range(1, 4):\n    for j in range(1, 4):\n        print(i, 'x', j, '=', i * j)",
                    'explain' => 'Nested loops to print the multiplication table from 1x1 to 3x3.',
                    'expected_output' => "1 x 1 = 1\n1 x 2 = 2\n1 x 3 = 3\n2 x 1 = 2\n2 x 2 = 4\n2 x 3 = 6\n3 x 1 = 3\n3 x 2 = 6\n3 x 3 = 9"
                ],
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
                   'question' => "How many times will this loop run?\ncount = 3\nwhile count > 0:\n    print(count)\n    count += 1",
                    'options' => ['3 times', '4 times', 'Forever (infinite)', '0 times'],
                    'correct_answer' => 2,  // Infinite loop since count is increasing
                    'explanation' => 'This loop will run infinitely because the count is increasing each time and never meets the condition to stop.'
                ],
                [
                    'question' => "What is missing from this loop?\nnumber = 5\nwhile number < 10:\n    print(number)",
                    'options' => ['The condition is wrong', 'Need to increase number', 'Need a for loop instead', 'Nothing is missing'],       
                    'correct_answer' => 1,
                    'explanation' => 'The number never changes, so the condition number < 10 will always be true, creating an infinite loop! You need to increment number inside the loop.'
                ],
                [
                   'question' => "What does this code do?\ncookies = 5\nwhile cookies > 0:\n    print(\"Yum!\")\n    cookies -= 1\nprint(\"No more cookies!\")",
                    'options' => ['Prints "Yum!" 5 times', 'Prints "Yum!" 4 times', 'Prints "Yum!" forever', 'Prints "No more cookies!" 5 times'],
                    'correct_answer' => 0,
                    'explanation' => 'It starts with 5 cookies, prints "Yum!" and decreases cookies until it reaches 0, then prints "No more cookies!"'
                ],
                [
                   'question' => "What happens when the condition is always false?\ncount = 5\nwhile count < 0:\n    print(count)\n    count -= 1",
                    'options' => ['The loop runs infinitely', 'The loop doesn’t run at all', 'The loop runs once', 'The loop runs 5 times'],
                    'correct_answer' => 1,
                    'explanation' => 'Since the condition is always false (count < 0), the loop will never run.'
                ],
                [
                   'question' => "What is the output of this loop with an infinite condition?\ncount = 1\nwhile True:\n    print(count)",
                    'options' => ['The loop will run forever', 'The loop will run once', 'It will cause an error', 'The loop will print 1'],
                    'correct_answer' => 0,
                    'explanation' => 'The condition is always true, so the loop will continue running indefinitely until manually stopped or broken.'
                ]
            ],
            // Added examples for practical use
            'examples'    => [
                [
                    'title'   => 'Count up from 1 to 5',
                    'code'    => "count = 1\nwhile count <= 5:\n    print(count)\n    count += 1",
                    'explain' => 'This loop starts at 1 and increments by 1 each time, printing numbers from 1 to 5.',
                    'expected_output' => "1\n2\n3\n4\n5"
                ],
                [
                    'title'   => 'Ask for password until correct',
                    'code'    => "password = ''\nwhile password != 'secret':\n    password = input('Enter password: ')\nprint('Access granted!')",
                    'explain' => 'This loop keeps asking for the password until the user enters "secret". The loop continues until the condition is satisfied.',
                    'expected_output' => "Enter password: ...\nAccess granted!" // Simulated input
                ],
                [
                    'title'   => 'Counting down from 10',
                    'code'    => "count = 10\nwhile count > 0:\n    print(count)\n    count -= 1",
                    'explain' => 'This loop prints numbers starting from 10 and counts down to 1.',
                    'expected_output' => "10\n9\n8\n7\n6\n5\n4\n3\n2\n1"
                ],
                [
                    'title'   => 'Stop when a condition is met',
                    'code'    => "counter = 0\nwhile counter < 5:\n    print('Counting:', counter)\n    counter += 1",
                    'explain' => 'This loop will print the counter until it reaches 5, then stop.',
                    'expected_output' => "Counting: 0\nCounting: 1\nCounting: 2\nCounting: 3\nCounting: 4"
                ]
            ],
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
        'instructions' => "Decide whether each statement is true or false. Think carefully about when to use for loops vs while loops!\n\n
        Key Points:
        • Use FOR loops when you know how many times to repeat
        • Use WHILE loops when you want to repeat until a condition changes
        • Both can often solve the same problem, but one is usually more appropriate
        \n\nTest your knowledge with these true/false questions!",
        'content'      => [
            'questions' => [
                [
                    'statement' => 'For loops are best when iterating over a list of items.',
                    'answer' => true,
                    'explanation' => 'Yes! For loops are perfect when you know exactly how many items you have or are iterating over a list.'
                ],
                [
                    'statement' => 'While loops are ideal for reading input until the user types "quit".',
                    'answer' => true,
                    'explanation' => 'Correct! You don’t know in advance how many times the loop will run; it continues while the condition is true.'
                ],
                [
                    'statement' => 'You can always replace a while loop with a for loop and it will work the same.',
                    'answer' => false,
                    'explanation' => 'Not always! While loops are better when the number of repetitions depends on a changing condition rather than a fixed count.'
                ],
                [
                    'statement' => 'A for loop with range(5) will iterate 5 times.',
                    'answer' => true,
                    'explanation' => 'Yes! range(5) produces 0,1,2,3,4 which is 5 iterations.'
                ],
                [
                    'statement' => 'While loops are never used with counters.',
                    'answer' => false,
                    'explanation' => 'Incorrect! While loops can use counters if needed, but they can also use any condition.'
                ],
                [
                    'statement' => 'A while loop can continue indefinitely if the condition never becomes false.',
                    'answer' => true,
                    'explanation' => 'Correct! This is called an infinite loop, which happens when the condition always evaluates to True.'
                ],
                [
                    'statement' => 'For loops are more readable when counting a specific number of repetitions.',
                    'answer' => true,
                    'explanation' => 'Yes! Using for loops with range() clearly shows how many times the loop will run.'
                ]
            ],
            // Practical examples to run
            'examples' => [
                [
                    'title'   => 'Sum numbers using for loop',
                    'code'    => "total = 0\nfor i in range(1, 6):\n    total += i\nprint(total)",
                    'explain' => 'This for loop sums numbers from 1 to 5. It is perfect for a known range.',
                    'expected_output' => "15"
                ],
               [
    'title'   => 'Continue playing until you lose',
    'code'    => "lives = 3\nwhile lives > 0:\n    print('You have', lives, 'lives left')\n    lives -= 1\nprint('Game over!')",
    'explain' => 'This loop continues while the player has lives. Once lives reach 0, the loop stops and prints "Game over!"',
    'expected_output' => "You have 3 lives left\nYou have 2 lives left\nYou have 1 lives left\nGame over!"
]
,
                [
                    'title'   => 'Print even numbers using for loop',
                    'code'    => "for i in range(2, 11, 2):\n    print(i)",
                    'explain' => 'For loop iterates over even numbers from 2 to 10.',
                    'expected_output' => "2\n4\n6\n8\n10"
                ],
                [
                    'title'   => 'Countdown using while loop',
                    'code'    => "count = 5\nwhile count > 0:\n    print(count)\n    count -= 1",
                    'explain' => 'While loop counts down from 5 to 1. The loop stops when the condition becomes false.',
                    'expected_output' => "5\n4\n3\n2\n1"
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
            'examples' => [
    [
        'title'   => 'Calculate the sum of numbers 1 through 5',
        'code'    => "sum = 0\nfor i in range(1, 6):\n    sum += i\nprint(sum)",
        'explain' => 'This loop adds the numbers from 1 to 5 and prints the sum.',
        'expected_output' => "15"
    ],
    [
        'title'   => 'Print "Running" until a counter reaches 5',
        'code'    => "counter = 0\nwhile counter < 5:\n    print('Running')\n    counter += 1",
        'explain' => 'This loop prints Running until the counter reaches 5.',
        'expected_output' => "Running\nRunning\nRunning\nRunning\nRunning"
    ],
    [
        'title'   => 'Sum numbers until the user types "exit"',
        'code'    => "total = 0\nwhile True:\n    number = input('Enter a number: ')\n    if number == 'exit':\n        break\n    total += int(number)\nprint('Total:', total)",
        'explain' => 'This loop continuously asks for a number, adding it to the total until exit is entered.',
        'expected_output' => "Enter a number: 5\nTotal: 5\nEnter a number: 10\nTotal: 15\nEnter a number: exit\nTotal: 15"
    ]
    ],

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
    'title' => 'Count sheep to fall asleep',
    'lines' => [
        'sheep = 1',
        'print("Sheep " + str(sheep))',
        'sheep += 1',
        'while sheep <= 3:'
    ],
    'solution' => [0, 3, 1, 2],
    'correct_output' => "Sheep 1\nSheep 2\nSheep 3"
],

[
    'title' => 'Simple for loop with friends',
    'lines' => [
        'friends = ["Ali", "Sara", "Tom"]',
        'for friend in friends:',
        'print("Hello " + friend)'
    ],
    'solution' => [0, 1, 2],
    'correct_output' => "Hello Ali\nHello Sara\nHello Tom"
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
                        'prompt' => "Exact output?\nfor i in range(1,4):\n    print(i*i)",
                        'options' => ['1 4 9', '1 2 3', '1 4', 'Error'],
                        'correct' => '1 4 9',
                    ],
                    [
                        'prompt' => "Will this loop stop?\nj=5\nwhile j>0:\n    j+=1",
                        'options' => ['Yes', 'No', 'Error', 'Only with break'],
                        'correct' => 'No',
                    ],
                    [
                        'prompt' => "What prints?\nfor x in range(0,5,2):\n    print(x)",
                        'options' => ['0 2 4', '1 3', '0 2 4 6', 'Error'],
                        'correct' => '0 2 4',
                    ],
                    [
                        'prompt' => "Given:\ni = 0\nwhile i < 3:\n    print(i)\n    if i == 1:\n        break\n    i += 1",
                        'options' => ['0 1', '0 1 2', '0', 'Infinite'],
                        'correct' => '0 1',
                    ],
                    [
                        'prompt' => "Which step is missing?\ni=0\nwhile i<4:\n    print(i)\n    # ???",
                        'options' => ['i += 1', 'break', 'continue', 'pass'],
                        'correct' => 'i += 1',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
}