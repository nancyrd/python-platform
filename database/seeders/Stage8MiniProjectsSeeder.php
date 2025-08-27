<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;

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
Think like a recipe: (1) ask the user for inputs, (2) turn those inputs from text into numbers, 
(3) do the math, (4) show the answer.

Important ideas for beginners:
• input("...") always returns text (called a string). Even if the user types 5, you still get "5".
• To do math, you must convert that text to a number using int(...) (for whole numbers) or float(...) (for decimals).
• After you compute, use print(...) to show the result.

Example (complete program):
num1 = int(input("First number: "))
num2 = int(input("Second number: "))
total = num1 + num2
print("Total:", total)

Your task: drag the shuffled lines into the correct order: read → cast (we combine read+cast in one line) → compute → print.',
                'content' => [
                    'intro'        => 'A real program is just a few careful steps in the right order. We will read numbers from the user, add them, and print the result.',
                    'instructions' => 'Drag the blocks to form a valid program. When you think it is correct, check your answer.',
                    // provide slightly different phrasings so replayers see variation
                    'items' => [
                        ['id' => 'read1',   'text' => 'num1 = int(input("First number: "))'],
                        ['id' => 'read2',   'text' => 'num2 = int(input("Second number: "))'],
                        ['id' => 'compute', 'text' => 'total = num1 + num2'],
                        ['id' => 'print',   'text' => 'print("Total:", total)'],
                    ],
                    'correct_order' => ['read1','read2','compute','print'],
                    'hints' => [
                        'input() gives you text. Use int(...) (or float(...)) to make numbers.',
                        'You can only compute after you have both numbers.',
                        'print(...) should be the last step to show the result.'
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
• Add: assign a key with a value (grades["Ali"] = 95).
• Show: loop over key/value pairs and print them.
• Remove: delete a key (del grades["Ali"]) or set a default when missing (get).

Your job: put each code card under the best section: Add, Show, or Remove.

Tip for beginners:
• Lists use numeric positions (indexes). 
• Dicts use keys (like student names).
• Showing data often means looping and printing.
• Removing either uses pop/remove (lists) or del/pop (dicts).',
                'content' => [
                    'categories' => [
                        '➕ Add' => [
                            "tasks.append('buy milk')",           // list add
                            "grades['Ali'] = 95",                 // dict add
                            "items.append(new_item)",             // list add
                            "phonebook['Mina'] = '0123-456-789'", // dict add
                        ],
                        '👀 Show' => [
                            "for t in tasks:\n    print(t)",               // list show
                            "for name,score in grades.items():\n    print(name, score)", // dict show
                            "print('Total tasks:', len(tasks))",          // show meta
                            "print(grades.get('Sara', 'N/A'))",           // dict safe lookup to show
                        ],
                        '🗑 Remove' => [
                            "tasks.pop()",                  // list remove last
                            "tasks.remove('buy milk')",     // list remove by value
                            "del grades['Ali']",            // dict remove by key
                            "grade = grades.pop('Mina', 0)" // dict pop with default
                        ],
                    ],
                    'hints' => [
                        'Add means inserting a new thing (append for lists, assignment for dicts).',
                        'Show usually means a loop that prints items or pairs.',
                        'Remove means taking something out (pop/remove for lists, del/pop for dicts).',
                    ],
                    'time_limit' => 360,
                    'max_hints'  => 4,
                ],
            ]
        );

        // Mini-projects do not include separate pre/post assessments here by design.
        // They are application-focused levels meant to consolidate earlier stages.
    }
}