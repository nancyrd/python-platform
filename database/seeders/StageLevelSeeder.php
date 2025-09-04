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
         * STAGE 1: First Steps with Python
         */
        $stage1 = Stage::query()->firstOrCreate(
            ['slug' => 'first-steps-python'],
            ['title' => 'Stage 1: First Steps with Python', 'display_order' => 1]
        );

        // PRE-ASSESSMENT: Test what they know before starting
        Assessment::updateOrCreate(
            ['stage_id' => $stage1->id, 'type' => 'pre'],
            [
                'title' => 'Pre-Assessment: What do you know about computers?',
                'questions' => json_encode([
                    [
                        'prompt' => 'What is a computer program?',
                        'options' => ['A list of instructions for the computer', 'A type of computer game', 'A computer screen', 'A computer keyboard'],
                        'correct' => 'A list of instructions for the computer'
                    ],
                    [
                        'prompt' => 'How do you think computers understand what to do?',
                        'options' => ['They guess', 'Someone gives them step-by-step instructions', 'They learn by themselves', 'They copy other computers'],
                        'correct' => 'Someone gives them step-by-step instructions'
                    ],
                    [
                        'prompt' => 'What do you think "Python" is in computing?',
                        'options' => ['A snake', 'A programming language', 'A computer brand', 'A website'],
                        'correct' => 'A programming language'
                    ],
                    [
                        'prompt' => 'If you wanted to show the word "Hello" on a computer screen, what would you expect to type?',
                        'options' => ['Hello', '"Hello"', 'say Hello', 'print Hello'],
                        'correct' => '"Hello"'
                    ]
                ], JSON_UNESCAPED_UNICODE)
            ]
        );

        // Level 1 - What is Programming? (Lesson)
        Level::updateOrCreate(
            ['stage_id' => $stage1->id, 'index' => 1],
            [
                'type' => 'drag_drop',
                'title' => 'What is Programming?',
                'pass_score' => 50,
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
        // Level 2 - Making Python Talk (Multiple Choice)
        Level::updateOrCreate(
            ['stage_id' => $stage1->id, 'index' => 2],
            [
                'type' => 'multiple_choice',
                'title' => 'Making Python Talk',
                'pass_score' => 60,
                'instructions' => 'The print() function is how we make Python show us information. Think of it like Python\'s voice - whatever you put inside print() will appear on the screen.',
                'content' => [
                    'intro' => 'In Python, we use print() to display messages. Text goes inside quotes, numbers don\'t need quotes.',
                    'time_limit' => 300,
                    'examples' => [
                        [
                            'title' => 'Printing text',
                            'code' => 'print("Hello there!")',
                            'explain' => 'Text must be in quotes so Python knows it\'s words, not instructions.',
                            'expected_output' => 'Hello there!'
                        ],
                        [
                            'title' => 'Printing numbers',
                            'code' => 'print(42)',
                            'explain' => 'Numbers don\'t need quotes because Python recognizes them automatically.',
                            'expected_output' => '42'
                        ],
                        [
                            'title' => 'Printing multiple things',
                            'code' => 'print("I am", 25, "years old")',
                            'explain' => 'Use commas to separate different items. Python adds spaces between them.',
                            'expected_output' => 'I am 25 years old'
                        ]
                    ],
                    'questions' => [
                        [
                            'question' => 'How do you print the word "Welcome"?',
                            'options' => ['print(Welcome)', 'print("Welcome")', 'say("Welcome")', 'show(Welcome)'],
                            'correct_answer' => 1,
                            'explanation' => 'Text must go in quotes inside print()'
                        ],
                        [
                            'question' => 'How do you print the number 10?',
                            'options' => ['print("10")', 'print(10)', 'print(ten)', 'show 10'],
                            'correct_answer' => 1,
                            'explanation' => 'Numbers don\'t need quotes'
                        ],
                        [
                            'question' => 'What will print("Hello", "World") show?',
                            'options' => ['HelloWorld', 'Hello World', 'Hello, World', '"Hello" "World"'],
                            'correct_answer' => 1,
                            'explanation' => 'Commas in print() add spaces between items'
                        ],
                        [
                            'question' => 'Which is the correct way to print your age?',
                            'options' => ['print("I am 20")', 'print("I am", 20)', 'Both are correct', 'Neither is correct'],
                            'correct_answer' => 2,
                            'explanation' => 'Both work! You can put the whole message in quotes, or separate text and numbers with commas.'
                        ]
                    ]
                ]
            ]
        );

        // Level 3 - Working with Different Types of Information (Drag & Drop)
        Level::updateOrCreate(
            ['stage_id' => $stage1->id, 'index' => 3],
            [
                'type' => 'drag_drop',
                'title' => 'Types of Information',
                'pass_score' => 70,
                'instructions' => 'Python works with different types of information. Let\'s learn to recognize them!

- Text (like names, messages) - called "strings" - always in quotes
- Whole numbers (like age, count) - called "integers" - no quotes
- Decimal numbers (like price, height) - called "floats" - no quotes
- True/False values - called "booleans" - no quotes

Drag each item to its correct category!',
                'content' => [
                    'time_limit' => 300,
                    'max_hints' => 3,
                    'categories' => [
                        'Text (strings)' => ['"Hello"', '"Python"', '"My name"', '"2023"'],
                        'Whole numbers (integers)' => ['42', '7', '0', '100'],
                        'Decimal numbers (floats)' => ['3.14', '2.5', '0.0', '99.9'],
                        'True/False (booleans)' => ['True', 'False']
                    ],
                    'hints' => [
                        'If it has quotes around it, it\'s text (string)',
                        'Whole numbers have no decimal point',
                        'Decimal numbers have a dot in them',
                        'True and False are special words (no quotes needed)'
                    ],
                     'examples' => [
                [
                    'title' => '1) Print a string',
                    'code'  => 'print("Hello Python")',
                    'explain' => 'Text strings must be in quotes.',
                    'expected_output' => "Hello Python",
                ],
                [
                    'title' => '2) Print an integer',
                    'code'  => 'print(42)',
                    'explain' => 'Integers are whole numbers without quotes.',
                    'expected_output' => "42",
                ],
                [
                    'title' => '3) Print a float',
                    'code'  => 'print(3.14)',
                    'explain' => 'Floats are decimal numbers.',
                    'expected_output' => "3.14",
                ],
                [
                    'title' => '4) Print a boolean',
                    'code'  => 'print(True)',
                    'explain' => 'Boolean values are either True or False.',
                    'expected_output' => "True",
                ],
                [
                    'title' => '5) Print multiple items with commas',
                    'code'  => 'print("The value is", 42)',
                    'explain' => 'Use commas to print multiple items. Python adds a space between them.',
                    'expected_output' => "The value is 42",
                ],
                [
                    'title' => '6) Print different data types',
                    'code'  => 'print("Version:", 3.9, "is good")',
                    'explain' => 'You can mix strings, numbers, and other types in one print statement.',
                    'expected_output' => "Version: 3.9 is good",
                ],
                [
                    'title' => '7) Print an empty line',
                    'code'  => 'print()',
                    'explain' => 'Using print() with nothing inside creates a blank line.',
                    'expected_output' => "",
                ],
            ],
        ],
    ]
);


        // Level 4 - Simple Math with Python (Multiple Choice)
        Level::updateOrCreate(
            ['stage_id' => $stage1->id, 'index' => 4],
            [
                'type' => 'multiple_choice',
                'title' => 'Python as Your Calculator',
                'pass_score' => 60,
                'instructions' => 'Python is like a super-powered calculator! You can use these symbols:

- + for adding: 5 + 3 = 8
- - for subtracting: 10 - 4 = 6  
- * for multiplying: 6 * 7 = 42
- / for dividing: 15 / 3 = 5.0

Notice that division always gives a decimal number (even 6/2 becomes 3.0).
• % (percent) → gives the remainder. Example: 7 % 3 = 1 (because 7 divided by 3 is 2 with a remainder of 1)
• ** (double star) → raises to a power. Example: 2 ** 3 = 8 (2 to the power of 3)
You can use these operations in print() to see the results!,',
                'content' => [
                    'time_limit' => 300,
                    'examples' => [
                        [
                            'title' => 'Basic addition',
                            'code' => 'print(10 + 5)',
                            'explain' => 'Python adds the numbers and shows the result.',
                            'expected_output' => '15'
                        ],
                        [
                            'title' => 'Division gives decimals',
                            'code' => 'print(8 / 2)',
                            'explain' => 'Even when dividing evenly, Python gives a decimal result.',
                            'expected_output' => '4.0'
                        ],
                        [
                            'title' => 'Multiple operations',
                            'code' => 'print(2 + 3 * 4)',
                            'explain' => 'Python follows math rules: multiplication before addition. So 3*4=12, then 2+12=14.',
                            'expected_output' => '14'
                        ],
                        [
                    'title' => '5) Remainder',
                    'code'  => 'print(10 % 3)',
                    'explain' => 'Use % to find the remainder after division.',
                    'expected_output' => "1",
                ],
                [
                    'title' => '6) Power',
                    'code'  => 'print(2 ** 4)',
                    'explain' => 'Use ** to raise a number to a power (exponent).',
                    'expected_output' => "16",
                ],
                [
                    'title' => '7) Combining operations',
                    'code'  => 'print(2 + 3 * 4)',
                    'explain' => 'Python follows math rules: multiplication before addition. This equals 14, not 20.',
                    'expected_output' => "14",
                ],
                [
                    'title' => '8) Using parentheses',
                    'code'  => 'print((2 + 3) * 4)',
                    'explain' => 'Use parentheses to change the order: (2 + 3) happens first, then × 4.',
                    'expected_output' => "20",
                ],
            ],

                    
                    'questions' => [
                        [
                            'question' => 'What does print(7 + 3) show?',
                            'options' => ['7 + 3', '10', '73', 'Error'],
                            'correct_answer' => 1,
                            'explanation' => 'Python calculates 7 + 3 = 10'
                        ],
                        [
                            'question' => 'What does print(12 - 5) show?',
                            'options' => ['7', '12 - 5', '17', '125'],
                            'correct_answer' => 0,
                            'explanation' => 'Python calculates 12 - 5 = 7'
                        ],
                        [
                            'question' => 'What does print(6 * 4) show?',
                            'options' => ['10', '24', '64', '6 * 4'],
                            'correct_answer' => 1,
                            'explanation' => 'Python calculates 6 * 4 = 24'
                        ],
                        [
                            'question' => 'What does print(10 / 2) show?',
                            'options' => ['5', '5.0', '10 / 2', '52'],
                            'correct_answer' => 1,
                            'explanation' => 'Division always gives a decimal result: 10 / 2 = 5.0'
                        ],
                         [
                    'question'        => 'What is the remainder of 10 divided by 3?<br><code>print(10 ___ 3)</code>',
                    'options'         => ['%', '/', '*'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use % for remainder: print(10 % 3) gives 1 (because 10 ÷ 3 = 3 with remainder 1)'
                ],
                [
                    'question'        => 'What is 2 to the power of 4 in Python?<br><code>print(2 ___ 4)</code>',
                    'options'         => ['**', '*', '%'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use ** for powers: print(2 ** 4) gives 16 (2 × 2 × 2 × 2)'
                ],
                [
                    'question'        => 'What does print(7 / 2) show in Python?',
                    'options'         => ['3.5', '3', '3.0'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Division in Python gives a decimal (float) result: 7 / 2 = 3.5'
                ],
                [
                    'question'        => 'Which operation would you use to find the remainder of 17 divided by 5?',
                    'options'         => ['17 % 5', '17 / 5', '17 - 5'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use % to find the remainder: 17 % 5 = 2 (because 17 ÷ 5 = 3 with remainder 2)'
                ],
                        [
                            'question' => 'What does print(2 + 3 * 2) show?',
                            'options' => ['10', '8', '12', '7'],
                            'correct_answer' => 1,
                            'explanation' => 'Multiplication first: 3 * 2 = 6, then 2 + 6 = 8'
                        ]
                    ]
                ]
            ]
        );
// Level 5 - Mixing Data Types and Common Errors
// Level 5 - Mixing Data Types and Common Errors (Flip Cards)
// Level 5 - Mixing Data Types and Common Errors (Flip Cards)
// Level 5 - Mixing Data Types and Common Errors (Flip Cards)
Level::updateOrCreate(
    ['stage_id' => $stage1->id, 'index' => 5],
    [
        'type' => 'flip_cards',
        'title' => 'Mixing Data Types & Common Errors',
        'pass_score' => 70,
        'instructions' => "In Python, every piece of information has a *data type* (kind of value).  
The most common ones are:
• **String** → text inside quotes, e.g. \"Hello\"  
• **Integer** → whole numbers, e.g. 25  
• **Float** → decimal numbers, e.g. 19.99  
• **Boolean** → True or False  

⚠️ Problem: Python does not automatically know how to combine different types.  
For example, \"Hello\" + 25 will give an error because text (string) and numbers (integer) are not the same kind of value.  

✅ Solutions:  
1. **Use commas in print()** → Python will automatically convert and add spaces:  
   print(\"Hello\", 25) → Hello 25  

2. **Convert numbers to strings with str()** when you want to join them as text:  
   print(\"Hello\" + str(25)) → Hello25  

3. **Convert strings to numbers with int() or float()** if you want to do math:  
   print(10 - int(\"5\")) → 5  

4. **Use f-strings (modern formatting)** to mix any type safely:  
   name = \"Alice\"; age = 25  
   print(f\"Name: {name}, Age: {age}\") → Name: Alice, Age: 25  

👉 Rule of thumb: Strings are for words, numbers are for math. Always make sure types match before combining them.",
        'content' => [
            'intro' => "Tap each card to reveal the concept and example.",
            'time_limit' => 300,
            'max_hints' => 3,
            'hints' => [
                'Use commas to safely mix different types in print()',
                'Convert numbers to strings with str() before using +',
                'Convert strings to numbers with int() or float() for math',
                'F-strings are the modern way to format strings with variables'
            ],
            'cards' => [
                [
                    'front' => 'Text + Number Error',
                    'back' => '❌ <code>print("Hello" + 25)</code><br><br>
Why error? Because Python cannot join text with a number.<br><br>
✅ Fix 1 (commas): <code>print("Hello", 25)</code> → Hello 25<br>
✅ Fix 2 (convert): <code>print("Hello" + str(25))</code> → Hello25'
                ],
                [
                    'front' => 'Number - Text Error',
                    'back' => '❌ <code>print(10 - "5")</code><br><br>
Why error? Because you cannot subtract a string from a number.<br><br>
✅ Fix: Convert the string to a number → <code>print(10 - int("5"))</code> → 5'
                ],
                [
                    'front' => 'Text / Number Error',
                    'back' => '❌ <code>print("Total" / 2)</code><br><br>
Why error? Because dividing only works with numbers.<br><br>
✅ Fix: Use variables and f-strings:<br><code>total = 10<br>print(f"Total {total/2}")</code> → Total 5.0'
                ],
                [
                    'front' => 'Text Multiplication',
                    'back' => '<code>print("Hi" * 3)</code> ✅<br><br>
This repeats the text 3 times → HiHiHi<br><br>
👉 This is the **only math operation** that works directly with strings.'
                ],
                [
                    'front' => 'Mixing with Commas',
                    'back' => '<code>print("Age:", 25)</code> ✅<br><br>
Output: Age: 25<br><br>
✔ Commas are the easiest and safest way to combine text with numbers.'
                ],
                [
                    'front' => 'String Conversion',
                    'back' => 'Want to join text and numbers with + ?<br><br>
✅ Use str(): <code>print("Score: " + str(10))</code><br>
Output: Score: 10'
                ],
                [
                    'front' => 'Number Conversion',
                    'back' => 'Need to do math with text numbers?<br><br>
✅ Convert with int(): <code>print(5 + int("10"))</code><br>
Output: 15'
                ],
                [
                    'front' => 'F-String Magic',
                    'back' => '✅ <code>print(f"Value: {10 + 5}")</code><br><br>
Output: Value: 15<br><br>
✔ F-strings allow mixing text and code easily inside { }.'
                ],
                [
                    'front' => 'Boolean in String',
                    'back' => '❌ <code>print("Result: " + True)</code><br><br>
Why error? You cannot add text and Boolean.<br><br>
✅ Fix: <code>print("Result:", True)</code> → Result: True'
                ],
                [
                    'front' => 'Float Conversion',
                    'back' => '✅ <code>print("Price: " + str(19.99))</code><br><br>
Output: Price: 19.99<br><br>
✔ Use str() for decimals too.'
                ]
            ],
            'examples' => [
                [
                    'title' => 'Safe mixing with commas',
                    'code' => 'print("Name:", "Alice", "Age:", 25)',
                    'explain' => 'Commas automatically handle different data types and add spaces.',
                    'expected_output' => 'Name: Alice Age: 25'
                ],
                [
                    'title' => 'F-string formatting',
                    'code' => 'name = "Alice"\nage = 25\nprint(f"Name: {name}, Age: {age}")',
                    'explain' => 'F-strings let you embed variables directly in strings.',
                    'expected_output' => 'Name: Alice, Age: 25'
                ],
                [
                    'title' => 'Type conversion',
                    'code' => 'print("Total: " + str(10 + 5))',
                    'explain' => 'Convert the result of math operations to string before concatenation.',
                    'expected_output' => 'Total: 15'
                ]
            ]
        ]
    ]
);

        // POST-ASSESSMENT: Test what they learned
        Assessment::updateOrCreate(
            ['stage_id' => $stage1->id, 'type' => 'post'],
            [
                'title' => 'Post-Assessment: Your First Steps with Python',
                'questions' => json_encode([
                    [
                        'prompt' => 'How do you print "Good morning" in Python?',
                        'options' => ['print(Good morning)', 'print("Good morning")', 'say("Good morning")', 'show Good morning'],
                        'correct' => 'print("Good morning")'
                    ],
                    [
                        'prompt' => 'What will print(25) display?',
                        'options' => ['25', '"25"', 'print(25)', 'twenty-five'],
                        'correct' => '25'
                    ],
                    [
                        'prompt' => 'What will print(8 + 7) display?',
                        'options' => ['8 + 7', '15', '87', 'Error'],
                        'correct' => '15'
                    ],
                    [
                        'prompt' => 'Which of these is a string (text)?',
                        'options' => ['42', '"Hello"', '3.14', 'True'],
                        'correct' => '"Hello"'
                    ],
                    [
                        'prompt' => 'What will print(10 / 5) display?',
                        'options' => ['2', '2.0', '105', '10 / 5'],
                        'correct' => '2.0'
                    ],
                    [
                        'prompt' => 'How do you print both text and a number together?',
                        'options' => ['print("Age" + 25)', 'print("Age", 25)', 'print(Age 25)', 'You cannot do this'],
                        'correct' => 'print("Age", 25)'
                    ]
                ], JSON_UNESCAPED_UNICODE)
            ]
        );
    }
}