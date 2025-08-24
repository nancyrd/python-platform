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

        // ---------- PRE ----------
        Assessment::updateOrCreate(
            ['stage_id' => $stage->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Variables Foundations',
                'questions' => json_encode([
                    ['prompt' => 'x = "5" makes x a…',       'options' => ['int','str','float','bool'], 'correct' => 'str'],
                    ['prompt' => 'Valid variable name?',     'options' => ['2items','total-amount','total_amount','class'], 'correct' => 'total_amount'],
                    ['prompt' => 'int("8") equals…',         'options' => ['"8"','8','8.0','Error'], 'correct' => '8'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // ---------- LEVEL 1 (MCQ / easy) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 1],
            [
                'type'       => 'multiple_choice',
                'title'      => 'Variables 101 — Easy MCQ',
                'pass_score' => 60,
                'content'    => [
                    'intro'        => 'Warm up with the basics of variables & types.',
                    'instructions' => 'Pick the best answer.',
                    'time_limit'   => 180,
                    'hints'        => [
                        'Strings need quotes; numbers do not.',
                        'Names can include letters, digits, underscores, but cannot start with a digit.',
                        'Use int("5") to convert a string number to an integer.',
                    ],
                    'questions'    => $this->mcqPool(),
                ],
            ]
        );

        // ---------- LEVEL 2 (Drag & Drop) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 2],
            [
                'type'       => 'drag_drop',
                'title'      => 'Sort the Variables',
                'pass_score' => 70,
                'content'    => [
                    'instructions' => 'Drag each item to the correct bucket.',
                    'time_limit'   => 240,
                    'max_hints'    => 3,
                    'hints'        => [
                        'int = whole numbers, float = decimals, str = text.',
                        'Booleans are True/False.',
                    ],
                    'categories' => [
                        'Numbers (int/float)' => ['3', '0', '4.5', '-12'],
                        'Text (str)'          => ['"hi"', "'abc'", '"42"'],
                        'Booleans'            => ['True', 'False'],
                        'Not a Python value'  => ['car', 'pizza'],
                    ],
                ],
            ]
        );

        // ---------- LEVEL 3 (True/False) ----------
        Level::updateOrCreate(
            ['stage_id' => $stage->id, 'index' => 3],
            [
                'type'       => 'true/false',
                'title'      => 'True or False: Variables',
                'pass_score' => 75,
                'content'    => [
                    'time_limit' => 180,
                    'questions'  => [
                        [
                            'code'    => "x = '5'\nprint(int(x) + 1)",
                            'options' => ['True','False'],
                            'correct' => 'True',
                        ],
                        [
                            'code'    => "Name = 'Sam'\nname = 'Alex'\nprint(Name == name)",
                            'options' => ['True','False'],
                            'correct' => 'False',
                        ],
                        [
                            'code'    => "x = 3.0\nprint(type(x) == float)",
                            'options' => ['True','False'],
                            'correct' => 'True',
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
                    ['prompt' => 'Fix: x="10"; y = x + 2', 'options' => ['y=int(x)+2','y=str(x)+2','y=x+"2"','2+x'], 'correct' => 'y=int(x)+2'],
                    ['prompt' => 'Store 3.14 in pi',      'options' => ['pi="3.14"','pi = 3.14','3.14 = pi','float = 3.14'], 'correct' => 'pi = 3.14'],
                    ['prompt' => 'bool("False") is…',     'options' => ['True','False'], 'correct' => 'True'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    private function mcqPool(): array
    {
        return [
            [
                'question' => 'What is the type of x after x = "7"?',
                'options'  => ['int','str','float','bool'],
                'correct_answer' => 1,
                'explanation' => 'Quotes make a string.',
            ],
            [
                'question' => 'Valid variable name:',
                'options'  => ['2total','total-amount','total_amount','class'],
                'correct_answer' => 2,
                'explanation' => 'Use underscores; cannot start with a digit.',
            ],
            [
                'question' => 'Output of: print(int("5") + 1)',
                'options'  => ['"51"','6','"6"','Error'],
                'correct_answer' => 1,
                'explanation' => 'int("5") converts to number 5.',
            ],
            [
                'question' => 'Which makes a float?',
                'options'  => ['x = 4','x = 4.0','x = "4"','x = True'],
                'correct_answer' => 1,
                'explanation' => 'A decimal point makes it a float.',
            ],
            [
                'question' => 'Pick the boolean literal:',
                'options'  => ['"True"','True','"False"','"yes"'],
                'correct_answer' => 1,
                'explanation' => 'True/False are boolean keywords (no quotes).',
            ],
            [
                'question' => 'Concatenate correctly:',
                'options'  => ['"a" + "b"','"a" - "b"','a + b','"a" * "b"'],
                'correct_answer' => 0,
                'explanation' => 'Use + to join strings.',
            ],
            [
                'question' => 'After x = 3; x = x + 2, x equals…',
                'options'  => ['3','5','"5"','32'],
                'correct_answer' => 1,
                'explanation' => 'x becomes 5.',
            ],
            [
                'question' => 'type(3) equals…',
                'options'  => ["<class 'int'>","<class 'float'>","<class 'str'>","int"],
                'correct_answer' => 0,
                'explanation' => '3 is an int.',
            ],
            [
                'question' => 'Which line errors?',
                'options'  => ['x=5','_name="Ali"','2x=7','is_valid=True'],
                'correct_answer' => 2,
                'explanation' => 'Names can’t start with a digit.',
            ],
            [
                'question' => 'len("abc") returns…',
                'options'  => ['2','3','"3"','Error'],
                'correct_answer' => 1,
                'explanation' => 'Length is 3.',
            ],
        ];
    }
}
