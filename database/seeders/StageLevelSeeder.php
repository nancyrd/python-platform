<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class StageLevelSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * STAGE 1: Variables
         */
        $variables = Stage::query()->firstOrCreate(
            ['slug' => 'variables'],
            ['title' => 'Stage 1: Variables', 'display_order' => 1]
        );

        // Level 1
        Level::updateOrCreate(
            ['stage_id' => $variables->id, 'index' => 1],
            [
                'type'       => 'drag_drop',
                'title'      => 'Build a Greeting',
                'pass_score' => 80,
                'content'    => json_encode([
                    'prompt'        => 'Arrange the blocks to print "Hello, Maya!"',
                    'blocks'        => [
                        'name = "Maya"',
                        'greeting = "Hello, " + name + "!"',
                        'print(greeting)',
                    ],
                    'correct_order' => [0, 1, 2],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // Level 2
        Level::updateOrCreate(
            ['stage_id' => $variables->id, 'index' => 2],
            [
                'type'       => 'fill_blank',
                'title'      => 'Fix the Types',
                'pass_score' => 80,
                'content'    => json_encode([
                    'template' => "x = '7'\ny = ____ (x)\nprint(y + ____)",
                    'answers'  => ['int', '3'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // Level 3
        Level::updateOrCreate(
            ['stage_id' => $variables->id, 'index' => 3],
            [
                'type'       => 'match_output',
                'title'      => 'Predict the Output',
                'pass_score' => 80,
                'content'    => json_encode([
                    'questions' => [
                        [
                            'code'    => "a = 2\nb = 3.0\nc = str(a) + str(b)\nprint(c)",
                            'options' => ['5.0', '23.0', '2 + 3.0', 'Error'],
                            'correct' => '23.0',
                        ],
                        [
                            'code'    => "x = 'hi'\nprint(x * 3)",
                            'options' => ['hi3', 'hihihi', '3hi', 'Error'],
                            'correct' => 'hihihi',
                        ],
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // Pre assessment
        Assessment::updateOrCreate(
            ['stage_id' => $variables->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Variables',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Type of x after x = "5"?',
                        'options' => ['int', 'str', 'float', 'bool'],
                        'correct' => 'str',
                    ],
                    [
                        'prompt'  => 'Valid variable name?',
                        'options' => ['2total', 'total-amount', 'total_amount', 'class'],
                        'correct' => 'total_amount',
                    ],
                    [
                        'prompt'  => 'Result of y = 3; y = y + 2',
                        'options' => ['3', '5', '2', 'Error'],
                        'correct' => '5',
                    ],
                    [
                        'prompt'  => 'type(True) equals…',
                        'options' => ["<class 'bool'>", "<class 'str'>", "<class 'int'>", 'bool'],
                        'correct' => "<class 'bool'>",
                    ],
                    [
                        'prompt'  => 'int("7") equals…',
                        'options' => ['"7"', '7', '7.0', 'Error'],
                        'correct' => '7',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // Post assessment
        Assessment::updateOrCreate(
            ['stage_id' => $variables->id, 'type' => 'post'],
            [
                'title'     => 'Post: Variables',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Choose the fix: x="10"; y=x+2',
                        'options' => ['y=int(x)+2', 'y=str(x)+2', 'y=x+"2"', 'int+2=y'],
                        'correct' => 'y=int(x)+2',
                    ],
                    [
                        'prompt'  => 'What is a good name for number of students?',
                        'options' => ['2students', 'num-students', 'num_students', 'class'],
                        'correct' => 'num_students',
                    ],
                    [
                        'prompt'  => 'bool("False") is…',
                        'options' => ['True', 'False'],
                        'correct' => 'True',
                    ],
                    [
                        'prompt'  => 'Store 3.14 in pi',
                        'options' => ['pi = "3.14"', 'pi = 3.14', '3.14 = pi', 'float = 3.14'],
                        'correct' => 'pi = 3.14',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        /**
         * STAGE 2: Input / Output (include ONLY ONCE)
         */
        $io = Stage::query()->firstOrCreate(
            ['slug' => 'input-output'],
            ['title' => 'Stage 2: Input / Output', 'display_order' => 2]
        );

        Assessment::updateOrCreate(
            ['stage_id' => $io->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: I/O',
                'questions' => json_encode([
                    [
                        'prompt'  => 'Which prints text to the screen?',
                        'options' => ['input()', 'print()', 'len()'],
                        'correct' => 'print()',
                    ],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]
        );

        // (Add IO levels later as you build them.)
    }
}
