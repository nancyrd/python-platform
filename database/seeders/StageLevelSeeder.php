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
                 'instructions' => "Programming means giving step-by-step instructions to a computer. Python is a beginner-friendly language used for calculations, data, websites, and AI.

👇 Try these examples: copy each code into the console and press 'Run' to see what happens.

1) What is Python?
Think of it like this: If giving a computer instructions is like writing a recipe, Python is one of the clearest, easiest-to-read recipe books you can use. It's designed to be simple and straightforward, so you can focus on what you want the computer to do, not on confusing technical jargon.

2) Who uses Python?
In everyday terms: It's used by all sorts of people for all sorts of jobs!

A Researcher might use it to analyze data from a study, like figuring out patterns in health trends.
A Bank might use it to detect unusual transactions that could be fraud.
A Website Developer uses it to build the *behind-the-scenes* logic of sites like Instagram or Pinterest.
A Teacher might use it to automatically grade quizzes.

You could use it to automatically rename hundreds of photos or send yourself a daily text message with the weather forecast.

3) What is Python used for?
Websites & Apps: Building the *engine* that powers them (the part you don't see).

Data Analysis: It's like a super-powered Excel. Imagine instantly finding patterns in thousands of sales receipts or survey results.
Automation: Doing boring, repetitive tasks for you. For example, a script could automatically download your favorite memes every morning or organize files on your computer while you sleep.
Artificial Intelligence (AI): Teaching a computer to recognize objects in photos, understand spoken commands, or recommend movies you might like.

4) How can people benefit from learning Python?
Automate Your Life: Stop doing tedious computer tasks manually. Write a small script once and let the computer do the work forever.

Solve Problems: It teaches you a powerful way to break down big problems into small, solvable steps.

Boost Your Career: Even basic knowledge is valuable in many fields like marketing, finance, and science, not just tech.

5) Why is Python so popular?
It's easy to learn and has a huge toolbox. It's like if one brand of kitchen mixer (Python) was not only simple to use but also had attachments (libraries) for making pasta, grinding meat, and juicing oranges. You don't need a different appliance for every job.

6) What are Python’s main strengths and limitations?
Strength: It's a quick and clear way to give instructions. It's perfect for getting ideas working fast.

*Weakness*: It's not always the absolute fastest. For tasks that require millisecond precision (like the code in a car's airbag system), other, more complex languages are used. But for probably 95% of tasks, it's perfectly fast enough.

7) Which industries use Python?
Virtually all of them! Finance for analyzing markets, Healthcare for medical research, Filmmaking for creating special effects (Industrial Light & Magic uses it!), Retail for predicting what products will be popular, and Science for running experiments and calculations.

8) Popular Python tools (frameworks and libraries)
These are the *attachments* for the kitchen mixer:

Django/Flask: Toolkits for building websites.

Pandas: A tool for organizing and analyzing information in tables, like a super-powered Excel.

NumPy: A tool for doing really complex math quickly.

Requests: A tool that lets your program grab information from other websites.

9) How do you get started?
Download Python from its official website (it's free!). It's like installing any other program.

You write your *recipe* (a script) in a simple text editor made for coding.

You tell your computer to *run* the script, and it follows your instructions.

10) How does Python compare to other languages?
vs. C++: Writing in C++ is like building a car engine from scratch for maximum performance. Python is like getting a driver's license to use the car to get somewhere quickly.

vs. JavaScript: JavaScript is primarily for making websites interactive (the parts you click on). Python is more often used for the logic and data processing happening on the server.

11) Real-world things you can build with Python
A program that texts you if it's going to rain so you remember an umbrella.
A program that scans all your old documents and finds a specific receipt from 2018.
A simple website for your family to share recipes.
A program that tells you the average rating of all the movies you've watched this year.

12) How to start learning effectively
Don't just read do! Think of one small, annoying task you do on your computer and search : how to automate [task] with Python. You'll learn by solving a real problem for yourself. Start with tiny, useful projects, not with trying to learn everything at once.
",
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
                    "🎨 Graphic Design",
                    "🔧 Direct Hardware Access", 
                    "🖥️ Operating System Development",
                    "📣 Marketing & Advertising"
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
        
     
        ],
    ]
);
        // Level 2 - Making Python Talk (Multiple Choice)
        Level::updateOrCreate(
            ['stage_id' => $stage1->id, 'index' => 3],
            [
                'type' => 'multiple_choice',
                'title' => 'Making Python Talk',
                'pass_score' => 60,
                'instructions' => 'The print() function is how we make Python show us information. Think of it like Python\'s voice - whatever you put inside print() will appear on the screen.
                To print words, you must surround them with quotation marks, like this: print("Hello").
                
                You can also tell print() to say multiple things at once by placing a comma between them. For example, print("Hello", "World") will output Hello World. The comma automatically adds a space between the two items',
                'content' => [
                    'intro' => 'In Python, we use print() to display messages. Text goes inside quotes, numbers don\'t need quotes.',
                    'time_limit' => 300,
                    'examples' => [
                        [
                            'title' => 'Printing text',
                            'code' => 'print("Hello there!")',
                            'explain' => "Text must be in quotes so Python knows its words, not instructions.",
                            'expected_output' => 'Hello there!'
                        ],
                        [
                            'title' => 'Printing numbers',
                            'code' => 'print(42)',
                            'explain' => 'Numbers dont need quotes because Python recognizes them automatically.',
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
                    'type' => 'code',
                    'question' => 'Print the text: Good Morning',
                    'expected_output' => "Good Morning",
                    'starter_code' => "# Write one line to print Good Morning\n",
                    'solution' => "print(\"Good Morning\")",
                    'explanation' => 'Use print() with quotes around the text.'
                ],
                        [
                            'question' => 'How do you print the number 10?',
                            'options' => ['print("10")', 'print(10)', 'print(ten)', 'show 10'],
                            'correct_answer' => 1,
                            'explanation' => 'Numbers don\'t need quotes'
                        ],
                          [
                    'type' => 'code',
                    'question' => 'Print the number 7',
                    'expected_output' => "7",
                    'starter_code' => "# Print the number 7 (no quotes needed)\n",
                    'solution' => "print(7)",
                    'explanation' => 'Numbers don’t need quotes in print().'
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
                        ],
                         [
                    'type' => 'code',
                    'question' => 'Print: I am 12 years old (using text and a number)',
                    'expected_output' => "I am 12 years old",
                    'starter_code' => "# Mix text and a number with commas\n",
                    'solution' => "print(\"I am\", 12, \"years old\")",
                    'explanation' => 'Separate pieces with commas; print() inserts spaces.'
                ]
                        ],
                        'hints'      => [
                "You can mix words and numbers.Just separate each with a comma.",
                "Words need to wear quotes.",
                "Numbers dont need quotes.",
               
            ],
             'time_limit' => 300,
          
                
                ]
            ]
        );

        // Level 3 - Working with Different Types of Information (Drag & Drop)
        Level::updateOrCreate(
            ['stage_id' => $stage1->id, 'index' => 2],
            [
                'type' => 'drag_drop',
                'title' => 'Types of Information',
                'pass_score' => 70,
                'instructions' => 'Python works with different types of information. Let\'s learn to recognize them!

- Text (like names, messages) - called "strings" - always in quotes
- Whole numbers (like age, count) - called "integers" - no quotes
- Decimal numbers (like price, height) - called "floats" - no quotes
- True/False values - called "booleans" - no quotes',
                'content' => [
                    'time_limit' => 300,
                    'max_hints' => 4,
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
                    'type' => 'code',
                    'question' => 'Print the result of 15 - 4',
                    'expected_output' => "11",
                    'starter_code' => "# Print the result of 15 - 4\n",
                    'solution' => "print(15 - 4)",
                    'explanation' => 'Use - for subtraction.',
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
                    'type' => 'code',
                    'question' => 'Print the result of (10 - 2) * 3',
                    'expected_output' => "24",
                    'starter_code' => "# Use parentheses to change the order\n",
                    'solution' => "print((10 - 2) * 3)",
                    'explanation' => '(10 - 2) = 8, then 8 * 3 = 24.',
                ],
                [
                    'question'        => 'What is 2 to the power of 4 in Python?<br><code>print(2 ___ 4)</code>',
                    'options'         => ['**', '*', '%'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Use ** for powers: print(2 ** 4) gives 16 (2 × 2 × 2 × 2)'
                ],
                   [
                    'type' => 'code',
                    'question' => 'Print the result of 9 / 2 (note the decimal)',
                    'expected_output' => "4.5",
                    'starter_code' => "# Print the result of 9 / 2\n",
                    'solution' => "print(9 / 2)",
                    'explanation' => 'Division / returns a float like 4.5.',
                ],
                   [
                    'type' => 'code',
                    'question' => 'Print the remainder when 14 is divided by 4',
                    'expected_output' => "2",
                    'starter_code' => "# Print the remainder of 14 divided by 4\n",
                    'solution' => "print(14 % 4)",
                    'explanation' => 'Use % to get the remainder.',
                ],
                [
                    'question'        => 'What does print(7 / 2) show in Python?',
                    'options'         => ['3.5', '3', '3.0'],
                    'correct_answer'  => 0,
                    'explanation'     => 'Division in Python gives a decimal (float) result: 7 / 2 = 3.5'
                ],
                         [
                    'type' => 'code',
                    'question' => 'Print the result of 8 + 2',
                    'expected_output' => "10",
                    'starter_code' => "# Print the result of 8 + 2\n",
                    'solution' => "print(8 + 2)",
                    'explanation' => 'Use + to add the numbers.',
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