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
 * STAGE 1: print() & Output
 */
$stage1 = Stage::query()->firstOrCreate(
    ['slug' => 'print-basics'],
    ['title' => 'Stage 1: print() & Output', 'display_order' => 1]
);

// Level 1 — Drag & Drop (Intro to Python)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 1],
    [
        'type'         => 'drag_drop',
        'title'        => 'What is Python? (Drag & Drop)',
        'pass_score'   => 50,
        'instructions' => 'Programming means giving step-by-step instructions to a computer. Python is a beginner-friendly language used for calculations, data, websites, and AI.👇 Try these examples: copy each code into the console and press “Run” to see what happens.',
        'content'      => [
            'categories' => [
                "💻 Programming" => [
                    "🐍 Python",
                    "☕ Java",
                    "📋 Excel Macros"
                ],
                "👩‍🏫 Who Can Use Python?" => [
                    "👨‍🎓 Students",
                    "👩‍🏫 Teachers",
                    "🎨 Artists",
                    "🧑‍💻 Programmers"
                ],
                "📊 What Can Python Do?" => [
                    "🧮 Calculations",
                    "📊 Data Analysis",
                    "🌐 Make Websites",
                    "🤖 AI & Automation"
                ],
                "🚫 Not Related to Python" => [
                    "🥤 Drinking Soda",
                    "🚗 Driving a Car",
                    "🧑‍🍳 Cooking Pasta"
                ]
            ],
            'hints'      => [
                "🐍 Python is a general-purpose programming language.",
                "💡 Python is great for beginners and students.",
                "📊 Data, AI, and websites are all common Python uses.",
                "🚫 Some items are real-world activities, not programming."
            ],
            'time_limit' => 300,
            'max_hints'  => 4
        ,
      'examples' => [
    [
        'title' => '1) What is Python?',
        'code' => null,
        'explain' => 'Python is a high-level, general-purpose programming language focused on readability and rapid development. It uses clear, concise syntax and runs on Windows, macOS, and Linux.',
        'expected_output' => null,
    ],
    [
        'title' => '2) Who uses Python?',
        'code' => null,
        'explain' => 'Software engineers, data scientists, machine-learning engineers, DevOps/SRE, QA automation engineers, cybersecurity analysts, researchers, educators, hobbyists, and many enterprises (e.g., tech, finance, healthcare, media).',
        'expected_output' => null,
    ],
    [
        'title' => '3) What is Python used for?',
        'code' => null,
        'explain' => 'Common uses include web backends and APIs (Django, Flask, FastAPI), data analysis (pandas, NumPy), machine learning and AI (scikit-learn, PyTorch, TensorFlow), scripting and automation, DevOps/infra tooling, scientific computing, test automation, and simple desktop tools.',
        'expected_output' => null,
    ],
    [
        'title' => '4) How can people benefit from learning Python?',
        'code' => null,
        'explain' => 'It has a gentle learning curve, huge library ecosystem, and broad job market. You can quickly automate repetitive tasks, prototype ideas fast, and transition between domains (web, data, ML) without switching languages.',
        'expected_output' => null,
    ],
    [
        'title' => '5) Why is Python so popular?',
        'code' => null,
        'explain' => 'Readable syntax, extensive standard library, a massive third-party package index (PyPI), strong community support, and thriving ecosystems for data/ML and web development.',
        'expected_output' => null,
    ],
    [
        'title' => '6) What are Python’s main strengths and limitations?',
        'code' => null,
        'explain' => 'Strengths: readability, productivity, cross-platform support, huge libraries. Limitations: slower than compiled languages in tight loops, the GIL affects some multi-threaded CPU-bound workloads, and it’s not the first choice for mobile UIs or ultra-low-latency systems.',
        'expected_output' => null,
    ],
    [
        'title' => '7) Which industries use Python?',
        'code' => null,
        'explain' => 'Tech, finance and fintech, healthcare/biotech, media/streaming, retail/e-commerce, education/research, gaming tools, logistics, and government. It’s especially strong where data analysis and automation are important.',
        'expected_output' => null,
    ],
    [
        'title' => '8) Popular Python frameworks and libraries',
        'code' => null,
        'explain' => 'Web: Django, Flask, FastAPI. Data: pandas, NumPy. ML/AI: scikit-learn, PyTorch, TensorFlow. Viz: Matplotlib, Plotly, Seaborn. Automation/CLI: Click, Typer. Testing: pytest. Scraping: Requests, BeautifulSoup, Scrapy.',
        'expected_output' => null,
    ],
    [
        'title' => '9) How do you install Python and manage packages?',
        'code' => null,
        'explain' => 'Install from python.org or a distribution (e.g., Anaconda). Verify with “python --version”. Use virtual environments (python -m venv .venv; activate it) and install packages via “pip install package_name”.',
        'expected_output' => null,
    ],
    [
        'title' => '10) How does Python compare to other languages?',
        'code' => null,
        'explain' => 'Compared to C/C++/Rust it trades raw speed for developer speed. Compared to Java/C# it’s more dynamic and concise. Compared to JavaScript it excels in data/ML and server-side scripting, while JS dominates the browser.',
        'expected_output' => null,
    ],
    [
        'title' => '11) Real-world things you can build with Python',
        'code' => null,
        'explain' => 'Data dashboards, recommendation engines, chatbots, ETL pipelines, web APIs, automation scripts (rename files, scrape sites, send reports), simulation tools, and proof-of-concept ML models.',
        'expected_output' => null,
    ],
    [
        'title' => '12) How to start learning effectively',
        'code' => null,
        'explain' => 'Focus on core syntax (variables, types, control flow, functions), then practice small scripts that automate real tasks. Learn a package manager and a framework aligned with your goal (e.g., pandas for data, Django/FastAPI for web).',
        'expected_output' => null,
    ],
],
        ],
    ]
);

// Level 2 — Multiple Choice (print & operators)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 2],
    [
        'type'         => 'multiple_choice',
        'title'        => 'Python Basics: print & simple math',
        'instructions' => 'In Python, the command print() shows text or numbers on the screen. 
Put text inside quotes: print("Hello").

You can also use math symbols inside print():

• + (plus) → adds numbers. Example: print(2 + 3) → 5  
• - (minus) → subtracts numbers. Example: print(5 - 2) → 3  
• * (star) → multiplies numbers. Example: print(4 * 2) → 8  
• / (slash) → divides numbers. Example: print(6 / 2) → 3.0  

Tip: You can also use + to join text together. Example: print("Hi " + "there") → Hi there',

        'pass_score'   => 50,
        'content'      => [
            'intro'        => "In Python, use <code>print()</code> to display text or numbers. Join text with <code>+</code>. Do math with <code>+</code> (add), <code>-</code> (subtract), <code>*</code> (multiply), <code>/</code> (divide).",
            'instructions' => "Choose the correct answer for each question.",
            'questions'    => [
                [
                    'question'        => 'How do you print Goodbye?<br><code>print(___)</code>',
                    'options'         => ['"Goodbye"', 'Goodbye', "'Goodbye'"],
                    'correct_answer'  => 0,
                    'explanation'     => 'Strings need quotes: print("Goodbye")'
                ],
                [
                    'question'        => 'Which symbol joins text together?<br><code>print("A" ___ "B")</code>',
                    'options'         => ['+', '-', '0'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use + to concatenate strings.'
                ],
                [
                    'question'        => 'What is the correct way to print a number?<br><code>print(___)</code>',
                    'options'         => ['4', '"number"', 'number'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Numbers are printed without quotes: print(4)'
                ],
                [
                    'question'        => 'How do we add numbers?<br><code>print(2 ___ 3)</code>',
                    'options'         => ['+', '-', '0'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use + for addition.'
                ],
                [
                    'question'        => 'How do we subtract numbers?<br><code>print(5 ___ 2)</code>',
                    'options'         => ['-', '+', '*'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use - for subtraction.'
                ],
                [
                    'question'        => 'How do we multiply numbers?<br><code>print(3 ___ 2)</code>',
                    'options'         => ['*', '+', '-'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use * for multiplication.'
                ],
                [
                    'question'        => 'Which word shows text on the screen?<br><code>___("Hi")</code>',
                    'options'         => ['print', 'say', 'hello'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use the print() function.'
                ],
                [
                    'question'        => 'How do we divide numbers?<br><code>print(6 ___ 2)</code>',
                    'options'         => ['/', '*', '+'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use / for division.'
                ],
            ],
            'hints'       => [
                'Put quotes around text.',
                'Use + to join text or add numbers.',
                'print() shows things on the screen.'
            ],
            'time_limit'  => 180,
            'max_hints'   => 4,

            // 👇 NEW: Examples (match Level 2 concept)
            'examples' => [
                [
                    'title' => '1) Print a word',
                    'code'  => 'print("Hello")',
                    'explain' => 'Text goes in quotes.',
                    'expected_output' => "Hello",
                ],
                [
                    'title' => '2) Add numbers',
                    'code'  => 'print(2 + 3)',
                    'explain' => 'Use + for addition.',
                    'expected_output' => "5",
                ],
                [
                    'title' => '3) Subtract numbers',
                    'code'  => 'print(5 - 2)',
                    'explain' => 'Use - for subtraction.',
                    'expected_output' => "3",
                ],
                [
                    'title' => '4) Multiply numbers',
                    'code'  => 'print(4 * 2)',
                    'explain' => 'Use * for multiplication.',
                    'expected_output' => "8",
                ],
                [
                    'title' => '5) Divide numbers',
                    'code'  => 'print(6 / 2)',
                    'explain' => 'Division returns a decimal (float).',
                    'expected_output' => "3.0",
                ],
                [
                    'title' => '6) Join text',
                    'code'  => 'print("Hi " + "there")',
                    'explain' => 'Use + to join two strings.',
                    'expected_output' => "Hi there",
                ],
                [
                    'title' => '7) Mix label + math (with comma)',
                    'code'  => 'print("Total:", 4 + 6)',
                    'explain' => 'Comma prints items separated by a space.',
                    'expected_output' => "Total: 10",
                ],
            ],
        ],
    ]
);

// Level 3 — True/False (mixing strings & numbers)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 3],
    [
        'type'         => 'tf1',
        'title'        => 'print(): numbers + strings (True/False)',
        'pass_score'   => 50,
        'instructions' => 'Sometimes you want to show text and numbers together. 
For example:

print("Age:", 5)          → Age: 5  
print("Score: " + str(10)) → Score: 10  
print(f"Price: ${7}")     → Price: $7  

⚠️ But these crash (TypeError):  
print("Age: " + 5)  
print(2 + "3")

👉 Rules to remember:  
- Numbers can be printed directly: print(7) → 7  
- Text must go in quotes: print("Hello") → Hello  
- + joins text with text only ("Hi" + " there") → Hi there  
- To join a number with text, turn the number into text: str(7)  
- Commas in print() are the easiest way to mix: print("Age:", 7) → Age: 7  
- f-strings are a shortcut: print(f"My age is {7}") → My age is 7',

        'content'      => [
            'intro' => "Decide True or False: does the code really print the statement given, or not?",
            'questions' => [
                ['code' => 'print("Hello")',                  'statement' => 'This prints Hello',        'answer' => true,  'explanation' => 'Strings in quotes print directly.'],
                ['code' => 'print(10)',                       'statement' => 'This prints 10',           'answer' => true,  'explanation' => 'Numbers can print without quotes.'],
                ['code' => 'print("Age: " + 5)',              'statement' => 'This prints Age: 5',      'answer' => false, 'explanation' => 'You cannot join text + number directly. Convert with str().'],
                ['code' => 'print("Age: " + str(5))',         'statement' => 'This prints Age: 5',      'answer' => true,  'explanation' => 'str(5) makes the number into text.'],
                ['code' => 'print("Age:", 5)',                'statement' => 'This prints Age: 5',      'answer' => true,  'explanation' => 'Comma prints items separated by a space.'],
                ['code' => 'print("2" + "3")',                'statement' => 'This prints 23',          'answer' => true,  'explanation' => 'Text + text joins together.'],
                ['code' => 'print(2 + "3")',                  'statement' => 'This prints 5',           'answer' => false, 'explanation' => 'int + str causes TypeError.'],
                ['code' => 'print("Ha" * 3)',                 'statement' => 'This prints HaHaHa',      'answer' => true,  'explanation' => 'String * number repeats the string.'],
                ['code' => 'print(f"Score: {10}")',           'statement' => 'This prints Score: 10',   'answer' => true,  'explanation' => 'f-strings insert values inside {}.'],
                ['code' => "age = 7\nprint('Age:', age)",     'statement' => 'This prints Age: 7',      'answer' => true,  'explanation' => 'Comma lets you print variables easily.'],
                ['code' => "age = '7'\nprint('Age: ' + age)", 'statement' => 'This prints Age: 7',      'answer' => true,  'explanation' => 'Both parts are text, so + works.'],
                ['code' => 'print("Price: $" + str(12))',     'statement' => 'This prints Price: $12',  'answer' => true,  'explanation' => 'Convert number with str() when joining.'],
                ['code' => 'print("Price: $", 12)',           'statement' => 'This prints Price: $ 12', 'answer' => true,  'explanation' => 'Comma prints items with a space.'],
                ['code' => 'print("Hi " + 3)',                'statement' => 'This prints Hi 3',        'answer' => false, 'explanation' => 'TypeError: must convert 3 to str().'],
                ['code' => 'print("2" * "3")',                'statement' => 'This prints 222',         'answer' => false, 'explanation' => 'You cannot multiply two strings.'],
                ['code' => 'print(f"2 + 3 = {2 + 3}")',       'statement' => 'This prints 2 + 3 = 5',   'answer' => true,  'explanation' => 'Expression is evaluated inside {}.'],
                ['code' => 'print("Name:", "Alex")',          'statement' => 'This prints Name: Alex',  'answer' => true,  'explanation' => 'Comma prints both items separated by space.'],
                ['code' => 'print("Name:" + " " + "Alex")',   'statement' => 'This prints Name: Alex',  'answer' => true,  'explanation' => 'Manual space inside quotes works too.']
            ],
            'hints' => [
                'Quotes are for text, numbers print directly.',
                'Use str(number) if you want to join with +.',
                'Commas inside print() are the safest way to mix.',
                'f-strings are shortcuts: f"My age is {7}".'
            ],
            'time_limit' => 240,
            'max_hints'  => 4,

            // 👇 NEW: Examples (match Level 3 concept)
            'examples' => [
                [
                    'title' => '1) Comma mixing',
                    'code'  => 'print("Age:", 7)',
                    'explain' => 'Easiest way to mix text + number; a space is added.',
                    'expected_output' => 'Age: 7',
                ],
                [
                    'title' => '2) Concatenation with str()',
                    'code'  => 'print("Age: " + str(7))',
                    'explain' => 'Convert number to text before using +.',
                    'expected_output' => 'Age: 7',
                ],
                [
                    'title' => '3) f-string shortcut',
                    'code'  => 'print(f"Score: {10}")',
                    'explain' => 'Embed the value inside {}.',
                    'expected_output' => 'Score: 10',
                ],
                [
                    'title' => '4) String + string',
                    'code'  => 'print("2" + "3")',
                    'explain' => 'Joining two strings gives 23.',
                    'expected_output' => '23',
                ],
                [
                    'title' => '5) Repeat a string',
                    'code'  => 'print("Ha" * 3)',
                    'explain' => 'String * number repeats the text.',
                    'expected_output' => 'HaHaHa',
                ],
                [
                    'title' => '6) This one crashes (on purpose)',
                    'code'  => 'print("Age: " + 7)',
                    'explain' => 'TypeError: you cannot join text + number without str().',
                    // no expected_output so your "Check" won’t try to match it
                ],
            ],
        ],
    ]
);



// PRE assessment (aligned to print & types at a gentle level)
Assessment::updateOrCreate(
    ['stage_id' => $stage1->id, 'type' => 'pre'],
    [
        'title'     => 'Pre: print() & Basic Types',
        'questions' => json_encode([
            [
                'prompt'  => 'Which one prints Hello?',
                'options' => ['print(Hello)', 'print("Hello")', 'say("Hello")', 'echo "Hello"'],
                'correct' => 'print("Hello")',
            ],
            [
                'prompt'  => 'Text values in Python are called…',
                'options' => ['int', 'float', 'str', 'bool'],
                'correct' => 'str',
            ],
            [
                'prompt'  => 'Which joins two pieces of text?',
                'options' => ['+', '-', '*', '/'],
                'correct' => '+',
            ],
            [
                'prompt'  => 'What is the result of 3 + 2?',
                'options' => ['3', '5', '32', 'Error'],
                'correct' => '5',
            ],
            [
                'prompt'  => 'Which line is safe?',
                'options' => [
                    '"Age: " + 5',
                    'print("Age:", 5)',
                    '2 + "3"',
                    'print("Hello" "World")'
                ],
                'correct' => 'print("Age:", 5)',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]
);

// POST assessment (aligned with Levels 1–3; gentle → firmer)
Assessment::updateOrCreate(
    ['stage_id' => $stage1->id, 'type' => 'post'],
    [
        'title'     => 'Post: print() & Output Basics',
        'questions' => json_encode([
            // 1) Plain string
            [
                'prompt'  => 'Exact output of: print("Hello")',
                'options' => ['Hello', '"Hello"', 'print("Hello")', 'Error'],
                'correct' => 'Hello',
            ],
            // 2) Plain number
            [
                'prompt'  => 'Exact output of: print(7)',
                'options' => ['7', '"7"', 'int(7)', 'Error'],
                'correct' => '7',
            ],
            // 3) Comma mixing (space awareness)
            [
                'prompt'  => 'Exact output of: print("Age:", 7)',
                'options' => ['Age:,7', 'Age: 7', '"Age:", 7', 'Error'],
                'correct' => 'Age: 7',
            ],
            // 4) str() + concatenation
            [
                'prompt'  => 'Exact output of: print("Age: " + str(7))',
                'options' => ['Age: 7', '"Age: " + 7', 'Age:  7', 'Error'],
                'correct' => 'Age: 7',
            ],
            // 5) f-string
            [
                'prompt'  => 'Exact output of: print(f"Score: {10}")',
                'options' => ['Score: 10', '"Score: {10}"', '{10}', 'Error'],
                'correct' => 'Score: 10',
            ],
            // 6) String + string
            [
                'prompt'  => 'Exact output of: print("2" + "3")',
                'options' => ['23', '5', '2 + 3', 'Error'],
                'correct' => '23',
            ],
            // 7) Division result format
            [
                'prompt'  => 'Exact output of: print(6 / 2)',
                'options' => ['3', '3.0', '"3"', 'Error'],
                'correct' => '3.0',
            ],
            // 8) Repeat strings
            [
                'prompt'  => 'Exact output of: print("Ha" * 3)',
                'options' => ['HaHaHa', 'Ha*3', 'Ha Ha Ha', 'Error'],
                'correct' => 'HaHaHa',
            ],
            // 9) Identify the TypeError (str + int)
            [
                'prompt'  => 'Which line causes a TypeError?',
                'options' => [
                    'print("Score:", 10)',
                    'print("Score: " + 10)',
                    'print("10" + "5")',
                    'print(int("2") + 3)'
                ],
                'correct' => 'print("Score: " + 10)',
            ],
            // 10) int() to do arithmetic
            [
                'prompt'  => 'x = "2"; y = 3. Which prints 5?',
                'options' => [
                    'print(x + y)',
                    'print(int(x) + y)',
                    'print(str(x) + str(y))',
                    'print("5")'
                ],
                'correct' => 'print(int(x) + y)',
            ],
            // 11) Comma inserts a space
            [
                'prompt'  => 'Exact output of: print("A", "B")',
                'options' => ['AB', 'A B', '"A", "B"', 'Error'],
                'correct' => 'A B',
            ],
            // 12) Two valid ways, but only one output is asked → exact output for a concat
            [
                'prompt'  => 'Exact output of: print("Price: $" + str(12))',
                'options' => ['Price: $12', 'Price: $ 12', '"Price: $" 12', 'Error'],
                'correct' => 'Price: $12',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]
);


      

        // (Add IO levels later as you build them.)
    }
}
