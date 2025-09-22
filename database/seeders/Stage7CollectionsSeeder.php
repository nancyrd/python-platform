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
        $stage7 = Stage::updateOrCreate(
            ['slug' => 'collections-lists-dicts'],
            [
                'title'         => 'Stage 7: Collections (Lists & Dictionaries)',
                'display_order' => 7,
            ]
        );
        
        // ───────────────────────────────
        // Level 1 — Lists Basics
        // ───────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 1],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Meet the List',
                'pass_score'   => 60,
                'instructions' =>
                    "Think of a **list** in Python like a shopping list or a row of lockers:\n\n".
                    "- A list holds many things in order: ['milk','bread','eggs']\n".
                    "- Each thing has a position (called index): index 0 is the first, 1 is the second…\n".
                    "- You can add new things with append() - like adding an item to your shopping cart\n".
                    "- You can count how many items are inside with len() - like counting items in your cart\n".
                    "- Lists can hold anything: numbers, words, or even other lists!\n\n".
                    "Let’s test what you know!",
                'content' => [
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'questions' => [
                        [
                            'question' => "fruits = ['apple','banana','cherry']\nprint(fruits[1])",
                            'options' => ['apple','banana','cherry','Error'],
                            'correct_answer' => 1,
                            'explanation' => "Index 1 = second item, which is 'banana'. Remember: Python starts counting at 0, so index 0 is 'apple', index 1 is 'banana', and index 2 is 'cherry'."
                        ],
                        [
                            'question' => "nums = [1,2]\nnums.append(3)\nprint(len(nums))",
                            'options' => ['2','3','4','Error'],
                            'correct_answer' => 1,
                            'explanation' => "The list starts as [1,2]. After append(3), it becomes [1,2,3]. Now there are 3 items in the list, so len(nums) returns 3. Think of it like adding one more item to your shopping cart and then counting everything."
                        ],
                        [
                            'question' => "shopping = ['milk','bread']\nshopping.append('eggs')\nprint(shopping)",
                            'options' => ["['milk','bread','eggs']","['milk','bread']","['eggs']","Error"],
                            'correct_answer' => 0,
                            'explanation' => "The list starts with ['milk','bread']. When we append('eggs'), we add 'eggs' to the end of the list, making it ['milk','bread','eggs']. It's like adding eggs to your shopping cart - they go at the end!"
                        ],
                        [
                            'question' => "my_list = []\nmy_list.append('hello')\nmy_list.append(123)\nprint(my_list)",
                            'options' => ["['hello','123']","['hello']","[123]","Error"],
                            'correct_answer' => 0,
                            'explanation' => "We start with an empty list []. Then we add 'hello' (a word/string) and 123 (a number). Lists can hold different types of items at the same time, just like a backpack can hold books, pencils, and snacks together!"
                        ]
                    ],
                    'hints' => [
                        "Indexes start at 0, not 1. Think of it like floors in a building - ground floor is 0, first floor is 1, etc.",
                        "append() adds at the end, like putting items at the back of a line.",
                        "len() counts items, like counting how many friends are in your group."
                    ],
                    'examples' => [
                        [
                            'title' => 'Shopping list',
                            'code'  => "shopping = ['milk','bread']\nshopping.append('eggs')\nprint(shopping)",
                            'explain' => "We started with a shopping list containing milk and bread. Then we remembered we need eggs, so we added them to our list. Now our complete shopping list has three items!",
                            'expected_output' => "['milk','bread','eggs']"
                        ],
                        [
                            'title' => 'Mixed list',
                            'code'  => "backpack = ['book', 42, True]\nprint('Items in backpack:', len(backpack))",
                            'explain' => "Lists can hold different types of items: a string ('book'), a number (42), and even a boolean (True). The len() function counts all items regardless of their type.",
                            'expected_output' => "Items in backpack: 3"
                        ],
                        [
                            'title' => 'Accessing by index',
                            'code'  => "rainbow = ['red','orange','yellow','green','blue']\nprint('Third color:', rainbow[2])",
                            'explain' => "The rainbow list has 5 colors. Remember: index 0 is red, index 1 is orange, and index 2 is yellow (the third color).",
                            'expected_output' => "Third color: yellow"
                        ]
                    ]
                ]
            ]
        );
        
        // ───────────────────────────────
        // Level 2 — Play with Lists
        // ───────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 2],
            [
                'type'         => 'drag_drop',
                'title'        => 'Play with a List',
                'pass_score'   => 60,
                'instructions' =>
                    "Now let’s practice. Drag each code into the right box:\n\n".
                    "- **Making a list** (create it) - like writing a new to-do list\n".
                    "- **Adding to a list** (append) - like adding tasks to your to-do list\n".
                    "- **Looking inside** (index or len) - like checking what's on your list or counting tasks\n".
                    "- **Not about lists** - code that does other things\n
                    -Common Methods for Removing Elements:lets say we have this list:my_list = [1, 2, 3, 4, 2]
1. remove() - Remove by value:my_list.remove(2)  # Removes the first occurrence of 2\n
2. pop() - Remove by index (returns the removed element):removed = my_list.pop(1)\n
3. del - Remove by index or slice:del my_list[2] \n
4. clear() - Remove all elements:my_list.clear()\n
",
                'content' => [
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'categories' => [
                        '📝 Make a list' => [
                            "fruits = ['apple','banana']",
                            "nums = []",
                            "todo_list = ['homework','clean room']"
                        ],
                        '➕ Add to a list' => [
                            "fruits.append('orange')",
                            "nums.append(10)",
                            "todo_list.append('play games')"
                        ],
                        '🔍 Look inside' => [
                            "print(fruits[0])",
                            "print(len(nums))",
                            "print(todo_list[1])"
                        ],
                        '🚫 Not about lists' => [
                            "print('hello')",
                            "x = 5+2",
                            "name = 'Alice'"
                        ],
                    ],
                    'hints' => [
                        "[] makes an empty list, [items] makes a list with items.",
                        "append() adds one item to the end of a list.",
                        "len() tells you how many items are in the list.",
                        "list[index] gets the item at that position (starting from 0)."
                    ],
                    'examples' => [
                        [
                            'title' => 'Access a list',
                            'code'  => "colors = ['red','blue','green']\nprint(colors[2])",
                          'explain' => "The colors list has 3 items. Index 0 is 'red', index 1 is 'blue', and index 2 is 'green'. So colors[2] gives us 'green'.",
                            'expected_output' => "green"
                        ],
                        [
                            'title' => 'List operations',
                            'code'  => "scores = [85, 92, 78]\nscores.append(88)\nprint('Average score:', sum(scores)/len(scores))",
                            'explain' => "We start with 3 scores, add a new score (88), then calculate the average by dividing the sum by the count.",
                            'expected_output' => "Average score: 85.75"
                        ],
                       
                        [
                            'title' => 'List of lists',
                            'code'  => "classroom = [['Alice','Bob'], ['Charlie','Dana']]\nprint('First group:', classroom[0])",
                            'explain' => "Lists can even contain other lists! Here, classroom is a list of two groups, each group is a list of names.",
                            'expected_output' => "First group: ['Alice','Bob']"
                        ]
                    ]
                ]
            ]
        );
        
        // ───────────────────────────────
        // Level 3 — Meet Dictionaries
        // ───────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 3],
            [
                'type'         => 'multiple_choice',
                'title'        => 'Meet the Dictionary',
                'pass_score'   => 60,
                'instructions' =>
                    "If a list is like a row of boxes, a **dictionary** is like a **phone book** or **dictionary** where you look up words to find meanings.\n\n".
                    "- It has **keys** (like words in a dictionary) and **values** (like definitions)\n".
                    "- Example: phone = {'Ali':'123','Maya':'555'} - Ali's number is 123, Maya's number is 555\n".
                    "- Look up by key: phone['Maya'] → '555' - like looking up Maya in the phone book\n".
                    "- len(dict) counts how many key-value pairs are in the dictionary\n".
                    "- Keys must be unique, but values can repeat\n\n".
                    "Dictionaries are perfect when you want to associate information!",
                'content' => [
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'questions' => [
                        [
                            'question' => "phone = {'Ali':'123','Sam':'999'}\nprint(phone['Sam'])",
                            'options' => ['Ali','Sam','123','999'],
                            'correct_answer' => 3,
                            'explanation' => "In the phone dictionary, we have two entries: 'Ali' maps to '123' and 'Sam' maps to '999'. When we look up phone['Sam'], we get Sam's phone number, which is '999'. Think of it like looking up 'Sam' in a phone book to find his number."
                        ],
                        [
                            'question' => "prices = {'apple':2, 'banana':3}\nprint(len(prices))",
                            'options' => ['2','3','Error','0'],
                            'correct_answer' => 0,
                            'explanation' => "The prices dictionary has 2 key-value pairs: 'apple':2 and 'banana':3. The len() function counts how many pairs are in the dictionary, not how many total items. It's like counting how many entries are in your address book, not how many phone numbers total."
                        ],
                        [
                            'question' => "student = {'name':'Tom','age':10,'grade':5}\nprint(student['grade'])",
                            'options' => ['Tom','10','5','Error'],
                            'correct_answer' => 2,
                            'explanation' => "In the student dictionary, we have three pieces of information: name (Tom), age (10), and grade (5). When we look up student['grade'], we get Tom's grade level, which is 5. It's like looking up a specific piece of information about Tom."
                        ],
                        [
                            'question' => "book = {'title':'Python','pages':200}\nbook['author'] = 'Alice'\nprint(len(book))",
                            'options' => ['2','3','Error','0'],
                            'correct_answer' => 1,
                            'explanation' => "We start with a dictionary with 2 key-value pairs: 'title':'Python' and 'pages':200. Then we add a new key-value pair: 'author':'Alice'. Now the dictionary has 3 pairs total, so len(book) returns 3. It's like adding a new piece of information to a file."
                        ]
                    ],
                    'hints' => [
                        "Keys are like labels, values are the information attached to those labels.",
                        "Use dict[key] to look up the value for a specific key.",
                        "len(dict) counts how many key-value pairs are in the dictionary."
                    ],
                    'examples' => [
                        [
                            'title' => 'Dictionary phonebook',
                            'code'  => "contacts = {'Alex':'111','Sam':'222'}\nprint(contacts['Alex'])",
                          'explain' => "We created a contacts dictionary with two people. Alex's number is '111' and Sam's number is '222'. When we look up contacts['Alex'], we get Alex's phone number.",
'expected_output' => "111"
                        ],
                        [
                            'title' => 'Student information',
                            'code'  => "student = {'name':'Maya','age':12,'grade':7}\nprint(f\"{student['name']} is in grade {student['grade']}\")",
                            'explain' => "Dictionaries are great for storing related information about something. Here we store Maya's name, age, and grade, then use them in a sentence.",
                            'expected_output' => "Maya is in grade 7"
                        ],
                        [
                            'title' => 'Dictionary with lists',
                            'code'  => "classroom = {'students':['Alice','Bob'], 'teacher':'Ms. Smith'}\nprint('Students:', classroom['students'])",
                            'explain' => "Dictionary values can be any type, even lists! Here, the 'students' key maps to a list of student names.",
                            'expected_output' => "Students: ['Alice','Bob']"
                        ]
                    ]
                ]
            ]
        );
        
        // ───────────────────────────────
        // Level 4 — Practice Dict & List
        // ───────────────────────────────
        Level::updateOrCreate(
            ['stage_id' => $stage7->id, 'index' => 4],
            [
                'type'         => 'tf1',
                'title'        => 'Checking in Lists & Dicts',
                'pass_score'   => 60,
                'instructions' =>
                    "We often need to check if something exists:\n\n".
                    "- 'in' checks if an item is inside a list - like checking if milk is on your shopping list\n".
                    "- 'in' checks if a key is in a dict - like checking if you have a friend's number in your phone\n".
                    "- dict.get(key, default) safely gets values and gives a fallback if the key doesn't exist\n".
                    "- This prevents errors and crashes!",
                'content' => [
                    'time_limit' => 300,
                    'max_hints'  => 3,
                    'questions' => [
                        [
                            'code' => "pets = ['dog','cat']\n'bird' in pets",
                            'statement' => "This is False",
                            'answer' => true,
                            'explanation' => "The pets list contains 'dog' and 'cat', but not 'bird'. So 'bird' in pets returns False. It's like checking if bird is on your list of pets - if it's not there, the answer is False."
                        ],
                        [
                            'code' => "scores = {'Ali':90}\nscores.get('Maya', 0) == 0",
                            'statement' => "This is True",
                            'answer' => true,
                            'explanation' => "The scores dictionary doesn't have a key called 'Maya'. When we use scores.get('Maya', 0), we're saying 'get Maya's score, but if she's not in the dictionary, return 0 instead'. Since Maya isn't there, it returns 0, and 0 == 0 is True."
                        ],
                        [
                            'code' => "fruits = ['apple','banana']\n'apple' in fruits",
                            'statement' => "This is True",
                            'answer' => true,
                            'explanation' => "The fruits list contains 'apple', so 'apple' in fruits returns True. It's like checking if apple is on your shopping list - if it is, the answer is True."
                        ],
                        [
                            'code' => "phone = {'Tom':'123'}\nphone.get('Tom', 'Not found') == 'Not found'",
                            'statement' => "This is False",
                            'answer' => true,
                            'explanation' => "The phone dictionary does have a key called 'Tom' with value '123'. When we use phone.get('Tom', 'Not found'), we find Tom's number (123), not the fallback value. So 123 == 'Not found' is False."
                        ]
                    ],
                    'hints' => [
                        "'in' checks if something exists in a list or dict.",
                        "dict.get(key, default) safely gets values and avoids errors.",
                        "len() counts items in a list or pairs in a dict."
                    ],
                    'examples' => [
                        [
                            'title' => 'Check membership',
                            'code'  => "animals = ['cat','dog','fish']\nprint('dog' in animals)\nprint('bird' in animals)",
                           'explain' => 'We check if "dog" and "bird" are in the animals list. "dog" is there (True), but "bird" is not (False).',
                            'expected_output' => "True\nFalse"
                        ],
                        [
                            'title' => 'Safe dict lookup',
                            'code'  => "ages = {'Ali':20, 'Sam':25}\nprint('Maya\\'s age:', ages.get('Maya', 'Unknown'))",
                            'explain' => "We try to get Maya s age from the ages dictionary. Since Maya is not in the dictionary, get() returns the fallback value Unknown instead of causing an error.",
                            'expected_output' => "Maya's age: Unknown"
                        ],
                        [
                            'title' => 'Check for keys',
                            'code'  => "menu = {'pizza':10, 'burger':8}\nprint('pizza' in menu)\nprint('salad' in menu)",
                            'explain' => "We check if pizza and salad are keys in the menu dictionary. pizza is there (True), but salad is not (False).",
                            'expected_output' => "True\nFalse"
                        ]
                    ]
                ]
            ]
        );
        
        // PRE assessment
        Assessment::updateOrCreate(
            ['stage_id' => $stage7->id, 'type' => 'pre'],
            [
                'title'     => 'Pre: What do you know about lists & dicts?',
                'questions' => [
                    [
                        'prompt' => "In real life, when would you use a list? (Choose the closest)",
                        'options' => ['Shopping list','Phonebook','Calculator','Alarm clock'],
                        'correct' => 'Shopping list',
                    ],
                    [
                        'prompt' => "In real life, when would you use a dictionary?",
                        'options' => ['Phonebook','Shopping cart','A line of people','Calendar date'],
                        'correct' => 'Phonebook',
                    ],
                    [
                        'prompt' => "Do you already know what Python lists or dicts are?",
                        'options' => ['Yes, a little','No, not yet','Only lists','Only dicts'],
                        'correct' => 'No, not yet',
                    ],
                    [
                        'prompt' => "What would you use to store a student's name, age, and grade together?",
                        'options' => ['A list','A dictionary','Two variables','A string'],
                        'correct' => 'A dictionary',
                    ],
                    [
                        'prompt' => "How would you check if 'milk' is in your shopping list?",
                        'options' => ["shopping_list.has('milk')","'milk' in shopping_list","shopping_list.find('milk')","shopping_list.contains('milk')"],
                        'correct' => "'milk' in shopping_list",
                    ]
                ],
            ]
        );
        
        // POST assessment
        Assessment::updateOrCreate(
            ['stage_id' => $stage7->id, 'type' => 'post'],
            [
                'title'     => 'Post: Lists & Dicts',
                'questions' => [
                    [
                        'prompt' => "fruits = ['apple']\nfruits.append('pear')\nprint(len(fruits))",
                        'options' => ['1','2','Error','0'],
                        'correct' => '2',
                    ],
                    [
                        'prompt' => "prices = {'pen':1, 'pencil':2}\nprint(prices['pen'])",
                        'options' => ['1','2','pen','Error'],
                        'correct' => '1',
                    ],
                    [
                        'prompt' => "people = {'Ali':20}\nprint(people.get('Maya', 0))",
                        'options' => ['Error','20','0','Maya'],
                        'correct' => '0',
                    ],
                    [
                        'prompt' => "numbers = [1,2,3,4]\nprint(numbers[2])",
                        'options' => ['2','3','4','Error'],
                        'correct' => '3',
                    ],
                    [
                        'prompt' => "student = {'name':'Tom','age':10}\nprint('name' in student)",
                        'options' => ['True','False','Error','0'],
                        'correct' => 'True',
                    ]
                ],
            ]
        );
    }
}