<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;
class Stage8MiniProjectsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // STAGE 8: Mini Projects
        // Goal: Apply all basics in tiny guided builds
        // ─────────────────────────────────────────────────────────────
        $stage8 = Stage::updateOrCreate(
            ['slug' => 'mini-projects'],
            [
                'title'         => 'Stage 8: Mini Projects',
                'display_order' => 8,
            ]
        );
         Assessment::updateOrCreate(
            ['stage_id' => $stage8->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: General Python Basics',
                'questions' => [
                    [
                        'prompt'  => 'If you have a list `nums = [1,2,3]`, which command adds 4 at the end?',
                        'options' => ['nums.add(4)', 'nums.append(4)', 'nums.push(4)', 'nums[4] = 4'],
                        'correct' => 'nums.append(4)',
                    ],
                    [
                        'prompt'  => 'Dictionaries work like a phonebook: you look up by...',
                        'options' => ['Index number', 'Key (like a name)', 'Random order', 'Memory address'],
                        'correct' => 'Key (like a name)',
                    ],
                    [
                        'prompt'  => 'In a `for` loop, what does `for item in items:` do?',
                        'options' => [
                            'Repeats once',
                            'Repeats for every element in the list',
                            'Creates a new dictionary',
                            'Stops immediately'
                        ],
                        'correct' => 'Repeats for every element in the list',
                    ],
                   [
    'prompt'  => 'Which function gives the number of items in a list or dictionary?',
    'options' => ['len()', 'count()', 'size()', 'total()'],
    'correct' => 'len()',
],

                    [
                        'prompt'  => 'You want to write a function that returns the sum of two numbers. Which keyword do you use?',
                        'options' => ['print', 'return', 'sum', 'output'],
                        'correct' => 'return',
                    ],
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // LEVEL 1 — Simple Calculator (reorder)
        // Type: reorder (or match_pairs for inputs)
        // Instructions: “Order steps: read → cast → compute → print.”
        // Practice: Build a 4-line calculator.
        //
        // Blade expectations (suggested contract for "reorder"):
        //   content: {
        //     intro: string (HTML ok),
        //     instructions: string (HTML ok),
        //     items: [{id,text}, ...]  // the draggable blocks (unordered)
        //     correct_order: [ids...]  // expected order by ids
        //     hints: [ ... ],
        //     time_limit: int seconds,
        //     max_hints: int
        //   }
        // Your checker should compare the final arranged id sequence to correct_order.
        // ─────────────────────────────────────────────────────────────
      Level::updateOrCreate(
    ['stage_id' => $stage8->id, 'index' => 1],
    [
        'type'       => 'reorder',
        'title'      => 'Simple Calculator: put steps in order',
        'pass_score' => 60,
        'instructions' => 'We are building a tiny calculator that adds two numbers the user types. 
Think like a recipe: 
(1) ask the user for inputs,
 (2) turn those inputs from text into numbers, 
(3) do the math,
 (4) show the answer.

Important ideas for beginners:
• input("...") always returns text (a string). Even if the user types 5, you still get "5".
• To do math, you must convert that text to a number using int(...) (for whole numbers) or float(...) (for decimals).
• After you compute, use print(...) to show the result.',
        'content' => [
            'intro'        => 'A real program is just a few careful steps in the right order. We will read numbers from the user, add them, and print the result.',
            'instructions' => 'Drag the blocks to form a valid program. When you think it is correct, check your answer.',
            'tasks' => [
    [
        'id'    => 'calc1',
        'title' => 'Add two numbers',
        'lines' => [
            'num1 = int(input("First number: "))',
            'num2 = int(input("Second number: "))',
            'total = num1 + num2',
            'print("Total:", total)',
        ],
        'solution'       => [0,1,2,3],
        'correct_output' => 'Total: 7',
    ],
    [
        'id'    => 'calc2',
        'title' => 'Subtract two numbers',
        'lines' => [
            'num1 = int(input("First number: "))',
            'num2 = int(input("Second number: "))',
            'difference = num1 - num2',
            'print("Difference:", difference)',
        ],
        'solution'       => [0,1,2,3],
        'correct_output' => 'Difference: 2',
    ],
    [
        'id'    => 'calc3',
        'title' => 'Multiply two numbers',
        'lines' => [
            'num1 = int(input("First number: "))',
            'num2 = int(input("Second number: "))',
            'product = num1 * num2',
            'print("Product:", product)',
        ],
        'solution'       => [0,1,2,3],
        'correct_output' => 'Product: 12',
    ],
    [
        'id'    => 'calc4',
        'title' => 'Average of two numbers',
        'lines' => [
            'num1 = float(input("First number: "))',
            'num2 = float(input("Second number: "))',
            'average = (num1 + num2) / 2',
            'print("Average:", average)',
        ],
        'solution'       => [0,1,2,3],
        'correct_output' => 'Average: 5.0',
    ],
],

            'examples' => [
    [
        'title'   => 'Example Run',
        'code'    => <<<PYTHON
num1 = int(input("First number: "))
num2 = int(input("Second number: "))
total = num1 + num2
print("Total:", total)
PYTHON,
        'output'  => 'Total: 7',
        'explain' => 'The program reads two numbers, converts them to integers, adds them, and prints the result.'
    ],
    [
        'title'   => 'Compute average of two numbers',
        'code'    => <<<PYTHON
num1 = float(input("Enter first number: "))
num2 = float(input("Enter second number: "))
average = (num1 + num2) / 2
print("Average:", average)
# User enters: 3.5 and 4.5
PYTHON,
        'output'  => 'Average: 4.0',
        'explanation' => 'This example introduces float conversion and shows a slightly different computation.'
    ]
],


            'hints' => [
                'input() gives you text. Use int(...) (or float(...)) to make numbers.',
                'You can only compute after you have both numbers.',
                'print(...) should be the last step to show the result.',
            ],
            'time_limit' => 300,
            'max_hints'  => 4,
        ],
    ]
);

        // ─────────────────────────────────────────────────────────────
        // LEVEL 2 — Number Guessing (true/false via tf1)
        // Type: true_false
        // Instructions: “Decide what the loop prints/when it stops.”
        // Practice: T/F about higher/lower hints, attempt counter, loop end.
        //
        // Blade expectations (contract used earlier for tf1):
        //   content: {
        //     questions: [
        //       { code: string, statement: string, answer: bool, explanation: string }, ...
        //     ],
        //     hints: [ ... ],
        //     time_limit: int, max_hints: int
        //   }
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage8->id, 'index' => 2],
            [
                'type'       => 'tf1',
                'title'      => 'Number Guessing: will it print / when does it stop?',
                'pass_score' => 60,
                'instructions' => 'In a number guessing game, we usually:
• Choose a secret number.
• Ask the player to guess.
• If the guess is too low, print "Higher".
• If the guess is too high, print "Lower".
• If the guess is correct, say "You got it!" and stop the loop.
• Count how many tries the player needed.

Beginner tips:
• A while loop repeats until its condition becomes False (or until we break out).
• Use break to exit the loop immediately when the player wins.
• Use continue to skip to the next loop round (for example, after printing "Higher").',
                'content' => [
                    'questions' => [
                        [
                            'code'        => "secret = 7\ntries = 0\nwhile True:\n    g = int(input('Guess: '))\n    tries += 1\n    if g < secret:\n        print('Higher')\n    elif g > secret:\n        print('Lower')\n    else:\n        print('You got it in', tries, 'tries!')\n        break",
                            'statement'   => "If the user types 3 then 7, the game prints 'Higher' first, then 'You got it in 2 tries!'.",
                            'answer'      => true,
                            'explanation' => '3 < 7 → "Higher". Next input is 7 → exact match → printed message, then break ends loop.'
                        ],
                        [
                            'code'        => "secret = 12\ntries = 0\nwhile True:\n    g = int(input('Guess: '))\n    tries += 1\n    if g > secret:\n        print('Lower')\n        continue\n    if g < secret:\n        print('Higher')\n        continue\n    print('Correct!')\n    break",
                            'statement'   => "Typing 15, then 9, then 12 will print: Lower, Higher, Correct!",
                            'answer'      => true,
                            'explanation' => '15>12 → Lower; 9<12 → Higher; 12==12 → Correct! then break.'
                        ],
                        [
                            'code'        => "secret = 5\ntries = 0\ng = -1\nwhile g != secret:\n    g = int(input('Guess: '))\n    tries += 1\nprint('Done')",
                            'statement'   => "This loop could run forever even after the user guesses 5.",
                            'answer'      => false,
                            'explanation' => 'The loop condition is g != secret. Once g becomes 5, the condition is False → loop stops. Then it prints "Done".'
                        ],
                        [
                            'code'        => "secret = 2\ntries = 0\nwhile True:\n    g = int(input('Guess: '))\n    if g == secret:\n        print('OK')\n        break\n    print('Nope')",
                            'statement'   => "If the user types: 5, 4, 3, then 2 → it prints Nope three times and then OK.",
                            'answer'      => true,
                            'explanation' => 'Each wrong guess prints "Nope". When g==2, it prints "OK" and breaks.'
                        ],
                        [
                            'code'        => "secret = 10\ntries = 0\nwhile True:\n    g = int(input('Guess: '))\n    tries += 1\n    if g == secret:\n        print('Win in', tries)\n        break",
                            'statement'   => "There is a risk of an infinite loop here if the user never guesses 10.",
                            'answer'      => true,
                            'explanation' => 'Yes. With while True and no other exit, only the correct guess breaks the loop.'
                        ],
                    ],
                    'hints' => [
                        'A while True loop needs a break at some point; otherwise it can run forever.',
                        'Use a counter (tries += 1) to track how many attempts happened.',
                        'continue jumps to the next loop round; break exits the loop completely.',
                    ],
                    'examples' => [
    [
        'title' => 'Guess a number between 1 and 3',
        'code'  => <<<PYTHON
secret = 2
guess = int(input("Guess a number (1-3): "))
if guess < secret:
    print("Higher!")
elif guess > secret:
    print("Lower!")
else:
    print("You got it!")
# User types: 1
PYTHON,
        'output' => "Higher!",
        'explanation' => 'Shows simple if/elif/else logic without a loop. Beginner-friendly example of comparing guesses.'
    ],
    [
        'title' => 'While loop with attempts',
        'code'  => <<<PYTHON
secret = 4
attempts = 0
while True:
    guess = int(input("Guess: "))
    attempts += 1
    if guess == secret:
        print("Correct in", attempts, "tries!")
        break
    elif guess < secret:
        print("Higher")
    else:
        print("Lower")
# User types: 2, 5, 4
PYTHON,
        'output' => "Higher\nLower\nCorrect in 3 tries!",
        'explanation' => 'Illustrates while loop with counter and multiple hints. Shows how loop continues until correct guess.'
    ],
    [
        'title' => 'Immediate correct guess',
        'code'  => <<<PYTHON
secret = 3
guess = int(input("Guess a number: "))
if guess == secret:
    print("You got it!")
else:
    print("Try again!")
# User types: 3
PYTHON,
        'output' => "You got it!",
        'explanation' => 'Shows that the loop or repeated attempts are not always necessary; immediate correct input can be handled.'
    ]
],

                    'time_limit' => 360,
                    'max_hints'  => 4,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // LEVEL 3 — To-Do or Gradebook (drag_drop)
        // Type: drag_drop
        // Instructions: “Place actions into sections: Add / Show / Remove.”
        // Practice: Cards for list/dict operations arranged under the right feature.
        //
        // Blade expectations (contract used earlier for drag_drop):
        //   content: {
        //     categories: { "Add":[...], "Show":[...], "Remove":[...] }  // the correct buckets
        //     hints: [ ... ],
        //     time_limit: int, max_hints: int
        //   }
        // The UI should present all cards together and let the learner drop each into a category.
        // The checker verifies membership against these arrays.
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage8->id, 'index' => 3],
            [
                'type'       => 'drag_drop',
                'title'      => 'To-Do (list) or Gradebook (dict): organize the actions',
                'pass_score' => 60,
                'instructions' => 'We will think like a tiny app designer.

For a To-Do list (using a Python list):
• Add: append a new task.
• Show: loop over tasks and print them.
• Remove: pop/remove by index, or remove by value.

For a Gradebook (using a Python dict mapping student → grade):
• Add: assign a key with a value (grades["Alex"] = 95).
• Show: loop over key/value pairs and print them.
• Remove: delete a key (del grades["Alex"]) or set a default when missing (get).

Your job: put each code card under the best section: Add, Show, or Remove.

Tip for beginners:
• Lists use numeric positions (indexes). 
• Dicts use keys (like student names).
• Showing data often means looping and printing.
• Removing either uses pop/remove (lists) or del/pop (dicts).',
                'content' => [
                   'categories' => [
    '➕ Add' => [
        "tasks.append('call mom')",          // list add
        "grades['Lina'] = 88",               // dict add single
        "shopping_list.extend(['bread', 'eggs'])", // list extend (multiple adds)
        "phonebook['John'] = '987-654-321'", // dict add single
    ],
    '👀 Show' => [
        "print(', '.join(tasks))",                      // show all tasks in one line
        "for name, score in grades.items(): print(f'{name}: {score}')", // dict show with formatting
        "print(f'Total tasks: {len(tasks)}')",         // show meta with f-string
        "print(shopping_list)",                         // simple list display
    ],
    '🗑 Remove' => [
        "tasks.pop(1)",                                // list remove by index
        "tasks.remove('call mom')",                    // list remove by value
        "del grades['Lina']",                           // dict remove by key
        "grade = grades.pop('Omar', 'N/A')",           // dict pop with default
        "shopping_list.clear()",                        // remove all items from list
    ],
],


                    'hints' => [
                        'Add means inserting a new thing (append for lists, assignment for dicts).',
                        'Show usually means a loop that prints items or pairs.',
                        'Remove means taking something out (pop/remove for lists, del/pop for dicts).',
                    ],
                    'examples' => [
    [
        'title' => 'Simple To-Do List',
        'code'  => <<<PYTHON
tasks = []
# Add tasks
tasks.append("Buy milk")
tasks.append("Walk dog")
# Show tasks
for t in tasks:
    print(t)
# Remove a task
tasks.remove("Buy milk")
print("After removal:", tasks)
PYTHON,
        'explain' => 'Shows append to add, for-loop to show, and remove by value. Beginner-friendly sequence.'
    ],
    [
        'title' => 'Simple Gradebook',
        'code'  => <<<PYTHON
grades = {}
# Add students
grades["Alex"] = 95
grades["Sara"] = 88
# Show grades
for name, score in grades.items():
    print(name, score)
# Remove a student
del grades["Alex"]
print("After removal:", grades)
PYTHON,
        'explain' => 'Shows dict assignment to add, items() to show key/value pairs, and del to remove.'
    ],
    [
        'title' => 'Using pop for safe removal',
        'code'  => <<<PYTHON
grades = {"Mina": 72, "John": 85}
# Remove with default
score = grades.pop("Mina", 0)
print("Removed score:", score)
print("Remaining:", grades)
# Output:
# Removed score: 72
# Remaining: {'John': 85}
PYTHON,
        'explain' => 'Demonstrates pop with a default value, preventing errors if key is missing.'
    ],
],

                    'time_limit' => 360,
                    'max_hints'  => 4,
                ],
            ]
        );

        // Mini-projects do not include separate pre/post assessments here by design.
      Assessment::updateOrCreate(
    ['stage_id' => $stage8->id, 'type' => 'post'],
    [
        'title'     => 'Post: Mini Projects',
        'questions' => [
            [
                'prompt'  => "In the calculator project, why do we write int(input(...)) instead of just input(...) ?",
                'options' => [
                    'Because input gives a string, we need a number',
                    'Because int(...) always makes the program faster',
                    'Because input only accepts numbers',
                    'Because print needs int'
                ],
                'correct' => 'Because input gives a string, we need a number',
            ],
            [
                'prompt'  => "In the guessing game, what does break do?",
                'options' => [
                    'Skips one loop round',
                    'Stops the loop immediately',
                    'Repeats the loop',
                    'Ignores wrong guesses'
                ],
                'correct' => 'Stops the loop immediately',
            ],
            [
                'prompt'  => "Which command shows all tasks in a list?",
                'options' => [
                    "print(tasks)",
                    "for t in tasks: print(t)",
                    "tasks.show()",
                    "len(tasks)"
                ],
                'correct' => "for t in tasks: print(t)",
            ],
            [
                'prompt'  => "If you do del grades['Ali'], what happens?",
                'options' => [
                    "Ali's grade is removed from the dictionary",
                    "It deletes all grades",
                    "It throws an error always",
                    "It clears the dictionary"
                ],
                'correct' => "Ali's grade is removed from the dictionary",
            ],
            // New questions
            [
                'prompt'  => "What happens if you use append() on a string in Python?",
                'options' => [
                    "It adds an item to the list",
                    "It throws an error because append() is for lists only",
                    "It appends a string to the original string",
                    "It adds a new element to the dictionary"
                ],
                'correct' => 'It throws an error because append() is for lists only',
            ],
            [
                'prompt'  => "In the guessing game, if the secret number is 5 and the guess is 3, which of these should be printed?",
                'options' => [
                    "'You got it!'",
                    "'Higher'",
                    "'Lower'",
                    "'Guess again'"
                ],
                'correct' => "'Higher'",
            ],
            [
                'prompt'  => "Which of the following methods is used to safely remove a key from a dictionary and avoid an error if the key does not exist?",
                'options' => [
                    "del grades['Alex']",
                    "grades.pop('Alex')",
                    "grades.remove('Alex')",
                    "grades.delete('Alex')"
                ],
                'correct' => "grades.pop('Alex')",
            ],
            [
                'prompt'  => "Which Python method is used to find out the length of a list or dictionary?",
                'options' => [
                    "count()",
                    "size()",
                    "len()",
                    "length()"
                ],
                'correct' => "len()",
            ],
            [
                'prompt'  => "If the list is empty, what will len([]) return?",
                'options' => [
                    "0",
                    "None",
                    "Error",
                    "False"
                ],
                'correct' => "0",
            ],
        ],
    ]
);

    }
}