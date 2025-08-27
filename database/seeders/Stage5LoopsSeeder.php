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
        // ─────────────────────────────────────────────────────────────
        // STAGE 5: Loops (while / for)
        // ─────────────────────────────────────────────────────────────
        $stage5 = Stage::updateOrCreate(
            ['slug' => 'loops-while-for'],
            ['title' => 'Stage 5: Loops (while / for)', 'display_order' => 5]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 1 — Counting with range() (multiple_choice)
        // Goals: Understand range(stop), range(start, stop), range(start, stop, step)
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Counting with range()',
                'pass_score'   => 50,
                'instructions' => 'In this lesson you’ll learn how Python’s built-in range() works in for loops.
Use range(stop) to count 0..stop-1;
range(start, stop) to count start..stop-1;
and range(start, stop, step) to skip by step.
Examples:
python
for i in range(3):
    print(i)            # prints 0, 1, 2

for i in range(2, 5):
    print(i)            # prints 2, 3, 4

for i in range(1, 10, 3):
    print(i)            # prints 1, 4, 7

Practice predicting what small loops will print.',
                'content'      => [
                    'intro'        => 'Predict the output of each for loop using the correct form of range().',
                    'instructions' => 'Select the exact sequence printed by the code.',
                    'questions'    => [
                        [
                            'question'        => 'What prints?

python
for i in range(4):
    print(i)
',
                            'options'         => ['0 1 2 3', '1 2 3 4', '0 1 2 3 4', '1 2 3'],
                            'correct_answer'  => 0,
                            'explanation'     => 'range(4) yields 0–3.'
                        ],
                        [
                            'question'        => 'What prints?

python
for i in range(2, 6):
    print(i)
',
                            'options'         => ['2 3 4 5', '0 1 2 3 4 5', '2 3 4', '1 2 3 4 5'],
                            'correct_answer'  => 0,
                            'explanation'     => 'range(2,6) yields 2–5.'
                        ],
                        [
                            'question'        => 'What prints?

python
for i in range(1, 10, 4):
    print(i)
',
                            'options'         => ['1 5 9', '1 4 7', '1 5 9 13', '1 4 8'],
                            'correct_answer'  => 0,
                            'explanation'     => 'range(1,10,4) yields 1,5,9.'
                        ],
                        [
                            'question'        => 'What prints?

python
for x in range(5, 0, -2):
    print(x)
',
                            'options'         => ['5 3 1', '5 4 3 2 1', '0', 'Error'],
                            'correct_answer'  => 0,
                            'explanation'     => 'range(5,0,-2) counts down: 5,3,1.'
                        ],
                    ],
                    'hints'       => [
                        'range(stop) always starts at 0.',
                        'The loop stops before reaching the stop value.',
                        'step can be negative to count down.'
                    ],
                    'time_limit'  => 240,
                    'max_hints'   => 4
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 2 — Fix the loop (reorder)
        // Goals: Put init → condition → update → print in correct order
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 2],
            [
                'type'         => 'reorder',
                'title'        => 'Fix the loop',
                'pass_score'   => 50,
                'instructions' => 'In this lesson you’ll rebuild a while loop from shuffled lines.
A proper while loop has four parts in order:
1) Initialization of the counter
2) The while condition
3) The body action (e.g. print)
4) The update to the counter to eventually end the loop

Example target:
python
i = 0
while i < 3:
    print(i)
    i += 1

Drag lines into the correct sequence to avoid infinite loops.',
                'content' => [
                    'lines'      => [
                        'while i < 4:',
                        'i = 0',
                        'print(i)',
                        'i += 1'
                    ],
                    'hints'      => [
                        'Initialization comes before the loop starts.',
                        'The condition must check something that changes.',
                        'Update the counter at the bottom of the loop.'
                    ],
                    'time_limit' => 240,
                    'max_hints'  => 4
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 3 — Will it stop? (true_false via type "tf1")
        // Goals: Decide if loops terminate, understand break/continue basics
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage5->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Will it stop?',
                'pass_score'   => 50,
                'instructions' => 'Predict whether each loop terminates (or prints a given value).
Remember:
• A while loop needs its counter updated (or a break) to end.
• for loops over finite ranges always stop.
• break exits immediately; continue skips to the next iteration.

Practice on off-by-one, infinite vs finite loops, and break/continue usage.',
                'content' => [
                    'questions' => [
                        [
                            'code'        => "i = 0\nwhile i < 3:\n    print(i)\n    # missing update",
                            'statement'   => 'This loop stops after printing 0,1,2',
                            'answer'      => false,
                            'explanation' => 'No update → infinite loop at i=0.'
                        ],
                        [
                            'code'        => "for x in range(2):\n    print(x)",
                            'statement'   => 'This loop terminates normally',
                            'answer'      => true,
                            'explanation' => 'for with range is finite: prints 0,1 then stops.'
                        ],
                        [
                            'code'        => "i = 5\nwhile i > 0:\n    i -= 1\n    if i == 2:\n        break\nprint('Done')",
                            'statement'   => 'This prints Done',
                            'answer'      => true,
                            'explanation' => 'break exits loop when i==2, then prints Done.'
                        ],
                        [
                            'code'        => "for j in range(3):\n    if j == 1:\n        continue\n    print(j)",
                            'statement'   => 'This prints 0,2',
                            'answer'      => true,
                            'explanation' => 'continue skips printing 1.'
                        ],
                        [
                            'code'        => "i = 1\nwhile i <= 3:\n    print(i)\n    i *= 2",
                            'statement'   => 'This stops after printing 1,2,4',
                            'answer'      => true,
                            'explanation' => 'i doubles until >3: 1,2,4 then exit.'
                        ],
                        [
                            'code'        => "i = 0\nwhile i < 3:\n    print(i)\n    i -= 1",
                            'statement'   => 'This is an infinite loop',
                            'answer'      => true,
                            'explanation' => 'i decreases forever: 0,-1,-2... never reaches 3.'
                        ],
                    ],
                    'hints'      => [
                        'Check that counters move toward the exit condition.',
                        'for loops over ranges always end.',
                        'break and continue affect flow differently.'
                    ],
                    'time_limit'  => 240,
                    'max_hints'   => 4
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // PRE / POST assessments for Stage 5
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage5->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Loops (baseline)',
                'questions' => json_encode([
                    [
                        'prompt'  => 'What does range(3) produce in a for loop?',
                        'options' => ['0,1,2', '1,2,3', '0,1,2,3', 'Error'],
                        'correct' => '0,1,2',
                    ],
                    [
                        'prompt'  => 'Which part avoids infinite loops in while?',
                        'options' => ['Initialization', 'Condition', 'Update', 'Import'],
                        'correct' => 'Update',
                    ],
                    [
                        'prompt'  => 'What does break do?',
                        'options' => ['Skips to next iteration', 'Exits loop', 'Restarts loop', 'Errors'],
                        'correct' => 'Exits loop',
                    ],
                    [
                        'prompt'  => 'What does continue do?',
                        'options' => ['Exits loop', 'Skips current iteration', 'Ends program', 'No effect'],
                        'correct' => 'Skips current iteration',
                    ],
                    [
                        'prompt'  => 'How many times prints in for i in range(2,5): print(i)?',
                        'options' => ['2', '3', '4', 'Error'],
                        'correct' => '3',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        Assessment::updateOrCreate(
            ['stage_id' => $stage5->id, 'type' => 'post'],
            [
                'title'     => 'Post: Loops',
                'questions' => json_encode([
                    [
                        'prompt'  => "Exact output?

for i in range(1,4):
    print(i*i)",
                        'options' => ['1 4 9', '1 2 3', '1 4', 'Error'],
                        'correct' => '1 4 9',
                    ],
                    [
                        'prompt'  => "Will this loop stop?

j=5
while j>0:
    j+=1",
                        'options' => ['Yes', 'No', 'Error', 'Only with break'],
                        'correct' => 'No',
                    ],
                    [
                        'prompt'  => "What prints?

for x in range(0,5,2): print(x)",
                        'options' => ['0 2 4', '1 3', '0 2 4 6', 'Error'],
                        'correct' => '0 2 4',
                    ],
                    [
                        'prompt'  => "Given:
i=0
while i<3:
    print(i)
    if i==1:
        break
    i+=1",
                        'options' => ['0 1', '0 1 2', '0', 'Infinite'],
                        'correct' => '0 1',
                    ],
                    [
                        'prompt'  => "Which step is missing?

i=0
while i<4:
    print(i)
    # ???",
                        'options' => ['i += 1', 'break', 'continue', 'pass'],
                        'correct' => 'i += 1',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }
}