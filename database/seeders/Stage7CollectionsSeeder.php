<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Level;
use App\Models\Assessment;

class Stage7CollectionsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────────
        // STAGE 7: Collections (Lists & Dicts)
        // ─────────────────────────────────────────────────────────────
        $stage7 = Stage::updateOrCreate(
            ['slug' => 'collections-lists-dicts'],
            [
                'title'         => 'Stage 7: Collections (Lists & Dicts)',
                'display_order' => 7,
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 1 — List moves (multiple_choice)
        // Goals: indexing, append, len basics
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'List moves',
                'pass_score'   => 60,
                'instructions' => 
                    '• A list holds items in order, starting at index 0. ' .
                    '• Use my_list[index] to get an item. ' .
                    '• Use my_list.append(x) to add x to the end. ' .
                    '• Use len(my_list) to find how many items are in the list.',
                'content'      => [
                    'intro'        => 
                        'Lists let you group many values together. ' .
                        'Imagine a numbered row of boxes. ' .
                        'You can pick any box by its number (index), add new boxes at the end with append(), ' .
                        'and count them all with len().',
                    'instructions' => 'Read the code, then pick what prints or what the length is.',
                    'questions'    => [
                        [
                            'question'       => "python\nfruits = ['apple', 'banana', 'cherry']\nprint(fruits[1])\nWhat prints?",
                            'options'        => ['apple', 'banana', 'cherry', 'IndexError'],
                            'correct_answer' => 1,
                            'explanation'    => 'Index 1 is the second item: "banana".'
                        ],
                        [
                            'question'       => "python\nnums = [1, 2]\nnums.append(3)\nprint(len(nums))\nWhat prints?",
                            'options'        => ['2', '3', '4', 'Error'],
                            'correct_answer' => 1,
                            'explanation'    => 'append(3) makes [1,2,3], so length is 3.'
                        ],
                        [
                            'question'       => "python\nitems = []\nitems.append('x')\nitems.append('y')\nprint(items[2])\nWhat happens?",
                            'options'        => ['x', 'y', 'Error', 'None'],
                            'correct_answer' => 2,
                            'explanation'    => 'There is no index 2 in [\'x\',\'y\'] → IndexError.'
                        ],
                        [
                            'question'       => "python\na = ['a','b','c']\nprint(len(a) - 1)\nWhat prints?",
                            'options'        => ['2', '3', '1', '0'],
                            'correct_answer' => 0,
                            'explanation'    => 'len(a) is 3; 3 − 1 = 2, which is the last valid index.'
                        ],
                    ],
                    'hints'       => [
                        'Indexes start at 0, so the first item is index 0.',
                        'append(x) always adds to the end, increasing length by 1.',
                        'len(list) returns the total number of items, not the max index.',
                    ],
                    'time_limit'  => 240,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 2 — Keys vs Values (drag_drop)
        // Goals: sort list ops, dict ops, not collection ops
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 2],
            [
                'type'         => 'drag_drop',
                'title'        => 'Keys vs Values',
                'pass_score'   => 60,
                'instructions' => 
                    'We will sort code snippets into three boxes: ' .
                    '• List operations: methods or expressions that work on lists. ' .
                    '• Dict operations: methods or expressions that work on dictionaries. ' .
                    '• Not a collection op: things unrelated to lists or dicts.',
                'content'      => [
                    'categories' => [
                        '📋 List ops' => [
                            "fruits.append('apple')",
                            'colors[0]',
                            'len(numbers)',
                            "students.pop()",
                        ],
                        '📖 Dict ops' => [
                            'grades["Ali"]',
                            'data.get("id", 0)',
                            "user['name'] = 'Bob'",
                            'len(info.keys())',
                        ],
                        '🚫 Not a collection op' => [
                            'print("Hello")',
                            'x = 5 + 2',
                            'import math',
                            'def func(): pass',
                        ],
                    ],
                    'hints'      => [
                        'Lists use numeric indexes and methods like append, pop.',
                        'Dicts use keys (strings or numbers) inside brackets, and methods like get.',
                        'Anything that does not read or modify a list or dict goes outside.',
                    ],
                    'time_limit'  => 300,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // Level 3 — Does it exist? (true_false via tf1)
        // Goals: membership tests, safe dict access via get()
        // ─────────────────────────────────────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 3],
            [
                'type'         => 'tf1',
                'title'        => 'Does it exist?',
                'pass_score'   => 60,
                'instructions' => 
                    'For each snippet, decide if the statement is True or False. ' .
                    'Focus on: in tests for membership in a list or dict, ' .
                    'dict.get(key, default) returns a default if key not found.',
                'content'      => [
                    'questions' => [
                        [
                            'code'        => "letters = ['a','b','c']\n# Statement: 'b' in letters",
                            'statement'   => 'This is True',
                            'answer'      => true,
                            'explanation' => '\'b\' is one of the items in the list.'
                        ],
                        [
                            'code'        => "d = {'x':1,'y':2}\n# Statement: 'z' in d",
                            'statement'   => 'This is False',
                            'answer'      => true,
                            'explanation' => "Membership tests dict keys; 'z' is not a key → False."
                        ],
                        [
                            'code'        => "d = {'x':1}\nvalue = d.get('y', 0)\n# Statement: value == 0",
                            'statement'   => 'This is True',
                            'answer'      => true,
                            'explanation' => ".get('y',0) returns 0 when 'y' not found."
                        ],
                        [
                            'code'        => "nums = [1,2,3]\n# Statement: 4 not in nums",
                            'statement'   => 'This is True',
                            'answer'      => true,
                            'explanation' => 'not in is the opposite of in; 4 is not in the list.'
                        ],
                        [
                            'code'        => "pets = ['cat']\n# Statement: pets[1] == 'dog'",
                            'statement'   => 'This is False',
                            'answer'      => true,
                            'explanation' => 'Index 1 does not exist → IndexError, so comparing fails.'
                        ],
                    ],
                    'hints'      => [
                        'in and not in check membership in lists or dict keys.',
                        'Use .get(key, default) to avoid errors when key missing.',
                        'Accessing a list or dict with [] for a missing index/key causes an error.',
                    ],
                    'time_limit'  => 300,
                    'max_hints'   => 3,
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // PRE assessment for Stage 7
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage7->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: Collections (baseline)',
                'questions' => [
                    [
                        'prompt'  => 'Which syntax gets the first item of a list x?',
                        'options' => ['x[0]', 'x.first()', 'x.get(0)', 'x{0}'],
                        'correct' => 'x[0]',
                    ],
                    [
                        'prompt'  => 'Which returns the number of items in a list?',
                        'options' => ['len(x)', 'x.count()', 'x.size', 'x.length()'],
                        'correct' => 'len(x)',
                    ],
                    [
                        'prompt'  => 'How do you safely access dict d key "k" with default  None?',
                        'options' => ['d.get("k")', 'd["k"]', 'd.k', 'get(d, "k")'],
                        'correct' => 'd.get("k")',
                    ],
                ],
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // POST assessment for Stage 7
        // ─────────────────────────────────────────────────────────────
        Assessment::updateOrCreate(
            ['stage_id' => $stage7->id, 'type' => 'post'],
            [
                'title'     => 'Post: Collections',
                'questions' => [
                    [
                        'prompt'  => "Exact output?\n\nlst = [10,20]\nlst.append(30)\nprint(lst[-1])",
                        'options' => ['30', '20', '10', 'Error'],
                        'correct' => '30',
                    ],
                    [
                        'prompt'  => 'What does len({"a":1, "b":2}) return?',
                        'options' => ['2', '1', 'Error', '0'],
                        'correct' => '2',
                    ],
                    [
                        'prompt'  => "Given d = {'x':5}, what is d.get('y', 99)?",
                        'options' => ['99', 'None', 'Error', '0'],
                        'correct' => '99',
                    ],
                ],
            ]
        );
    }
}