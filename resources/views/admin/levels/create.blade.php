<x-app-layout>
    <x-slot name="header">
        <div class="form-header-container">
            <div class="flex items-center">
                <div class="form-icon-wrapper">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="ml-4">
                    <h2 class="form-title">Add Level to Stage: {{ $stage->title }}</h2>
                    <p class="form-subtitle">Create a new learning challenge for your students</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h3 class="card-title">New Level Configuration</h3>
                <p class="card-description">Configure the basic settings and content for your new learning level</p>
            </div>
            
            <form method="POST" action="{{ route('admin.stages.levels.store', $stage) }}" class="level-form">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Order / Index</label>
                        <input type="number" name="index" class="form-input" value="{{ old('index') }}" required>
                        <div class="form-help">Position in the level sequence</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Pass Score (%)</label>
                        <input type="number" name="pass_score" class="form-input" value="{{ old('pass_score', 70) }}" required>
                        <div class="form-help">Minimum score to pass this level</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Level Title</label>
                    <input type="text" name="title" class="form-input" value="{{ old('title') }}" required>
                    <div class="form-help">Display name that learners will see</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Level Type</label>
                    <select name="type" id="levelType" class="form-select" required>
                        <option value="drag_drop" {{ old('type') == 'drag_drop' ? 'selected' : '' }}>Drag & Drop</option>
                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                        <option value="tf1" {{ old('type') == 'tf1' ? 'selected' : '' }}>True/False</option>
                        <option value="match_pairs" {{ old('type') == 'match_pairs' ? 'selected' : '' }}>Match Pairs</option>
                        <option value="flip_cards" {{ old('type') == 'flip_cards' ? 'selected' : '' }}>Flip Cards</option>
                        <option value="reorder" {{ old('type') == 'reorder' ? 'selected' : '' }}>Reorder Code</option>
                    </select>
                    <div class="form-help">Choose the type of interactive challenge</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Instructions</label>
                    <textarea name="instructions" class="form-textarea" rows="3" placeholder="Write rules ">{{ old('instructions') }}</textarea>
                    <div class="form-help">Clear instructions help students understand the task</div>
                </div>
                
                <div class="content-section">
                    <div class="section-divider">
                        <span class="divider-text">Level Content</span>
                    </div>
                    
                    <!-- Dynamic Content Based on Level Type -->
                    <div id="dynamic-content"></div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        <span>Save Level</span>
                    </button>
                    <a href="{{ route('admin.stages.levels.index', $stage) }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dynamic form field rendering based on level type
        document.getElementById('levelType').addEventListener('change', updateContentFields);
        updateContentFields();

        function updateContentFields() {
            const levelType = document.getElementById('levelType').value;
            const dynamicContent = document.getElementById('dynamic-content');
            dynamicContent.innerHTML = '';
            
            if (levelType === 'multiple_choice') {
                dynamicContent.innerHTML = `
                    <div class="content-group">
                        <div class="form-group">
                            <label class="form-label">Intro</label>
                            <textarea name="content[intro]" class="form-textarea" rows="2" placeholder="Brief introduction to the questions...">{{ old('content[intro]') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Questions (choose MC or Code for each)</label>
                            <div id="questions-container">
                                <!-- Initial question -->
                                ${buildMCQuestionItem(0)}
                            </div>
                            <button type="button" class="btn-add" onclick="addQuestion()">Add Another Question</button>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Time Limit (seconds)</label>
                                <input type="number" name="content[time_limit]" class="form-input" value="{{ old('content[time_limit]') ?? 180 }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Hints</label>
                                <input type="number" name="content[max_hints]" class="form-input" value="{{ old('content[max_hints]') ?? 3 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hints (one per line)</label>
                            <textarea name="content[hints]" class="form-textarea" rows="3" placeholder="Think about...\nConsider that...\nRemember...">{{ old('content[hints]') }}</textarea>
                        </div>
                    </div>
                `;
            } else if (levelType === 'drag_drop') {
                dynamicContent.innerHTML = `
                    <div class="content-group">
                        <div class="form-group">
                            <label class="form-label">Categories and Items</label>
                            <div id="category-container">
                                <!-- Initial category -->
                                <div class="category-item">
                                    <div class="category-header">Category 1</div>
                                    <div class="form-group">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" name="content[categories][0][name]" class="form-input" placeholder="e.g., 💻 Programming" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Items (one per line)</label>
                                        <textarea name="content[categories][0][items]" class="form-textarea" rows="3" placeholder="🐍 Python\n☕ Java\n📋 Excel Macros" required></textarea>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeCategory(this)">Remove Category</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addCategory()">Add Another Category</button>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Time Limit (seconds)</label>
                                <input type="number" name="content[time_limit]" class="form-input" value="{{ old('content[time_limit]') ?? 300 }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Hints</label>
                                <input type="number" name="content[max_hints]" class="form-input" value="{{ old('content[max_hints]') ?? 4 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hints (one per line)</label>
                            <textarea name="content[hints]" class="form-textarea" rows="3" placeholder="Look for similarities...\nGroup related items...\nPay attention to...">{{ old('content[hints]') }}</textarea>
                        </div>
                    </div>
                `;
            } else if (levelType === 'tf1') {
                dynamicContent.innerHTML = `
                    <div class="content-group">
                        <div class="form-group">
                            <label class="form-label">Intro</label>
                            <textarea name="content[intro]" class="form-textarea" rows="2" placeholder="Brief introduction to the statements...">{{ old('content[intro]') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Questions (choose True/False or Code for each)</label>
                            <div id="tf-questions-container">
                                <!-- Initial question -->
                                ${buildTFQuestionItem(0)}
                            </div>
                            <button type="button" class="btn-add" onclick="addTFQuestion()">Add Another Question</button>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Time Limit (seconds)</label>
                                <input type="number" name="content[time_limit]" class="form-input" value="{{ old('content[time_limit]') ?? 240 }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Hints</label>
                                <input type="number" name="content[max_hints]" class="form-input" value="{{ old('content[max_hints]') ?? 3 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hints (one per line)</label>
                            <textarea name="content[hints]" class="form-textarea" rows="3" placeholder="Check the syntax carefully...\nConsider the data types...\nThink about...">{{ old('content[hints]') }}</textarea>
                        </div>
                    </div>
                `;
            } else if (levelType === 'match_pairs') {
                dynamicContent.innerHTML = `
                    <div class="content-group">
                        <div class="form-group">
                            <label class="form-label">Intro</label>
                            <textarea name="content[intro]" class="form-textarea" rows="2" placeholder="Brief introduction to the matching exercise...">{{ old('content[intro]') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Matching Pairs</label>
                            <div id="pairs-container">
                                <!-- Initial pair -->
                                <div class="pair-item">
                                    <div class="question-header">Pair 1</div>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label class="form-label">Left Item</label>
                                            <input type="text" name="content[pairs][0][left]" class="form-input" placeholder="Item to match" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Right Item</label>
                                            <input type="text" name="content[pairs][0][right]" class="form-input" placeholder="Matching item" required>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removePair(this)">Remove Pair</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addPair()">Add Another Pair</button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sequences</label>
                            <div id="sequences-container">
                                <!-- Initial sequence -->
                                <div class="sequence-item">
                                    <div class="question-header">Sequence 1</div>
                                    <div class="form-group">
                                        <label class="form-label">Sequence Title</label>
                                        <input type="text" name="content[sequences][0][title]" class="form-input" placeholder="e.g., Process Steps" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Steps (one per line)</label>
                                        <textarea name="content[sequences][0][steps]" class="form-textarea" rows="3" placeholder="Step 1\nStep 2\nStep 3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Correct Order (comma-separated line numbers, starting from 0)</label>
                                        <input type="text" name="content[sequences][0][correct_order]" class="form-input" placeholder="e.g., 0,1,2" required>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeSequence(this)">Remove Sequence</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addSequence()">Add Another Sequence</button>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Time Limit (seconds)</label>
                                <input type="number" name="content[time_limit]" class="form-input" value="{{ old('content[time_limit]') ?? 240 }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Hints</label>
                                <input type="number" name="content[max_hints]" class="form-input" value="{{ old('content[max_hints]') ?? 3 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hints (one per line)</label>
                            <textarea name="content[hints]" class="form-textarea" rows="3" placeholder="Match similar concepts...\nLook for patterns...\nConsider relationships...">{{ old('content[hints]') }}</textarea>
                        </div>
                    </div>
                `;
            } else if (levelType === 'flip_cards') {
                dynamicContent.innerHTML = `
                    <div class="content-group">
                        <div class="form-group">
                            <label class="form-label">Intro</label>
                            <textarea name="content[intro]" class="form-textarea" rows="2" placeholder="Brief introduction to the flashcards...">{{ old('content[intro]') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cards</label>
                            <div id="cards-container">
                                <!-- Initial card -->
                                <div class="card-item">
                                    <div class="question-header">Card 1</div>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label class="form-label">Front (Question/Prompt)</label>
                                            <textarea name="content[cards][0][front]" class="form-textarea" rows="2" placeholder="Enter front side content" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Back (Answer/Explanation)</label>
                                            <textarea name="content[cards][0][back]" class="form-textarea" rows="2" placeholder="Enter back side content" required></textarea>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeCard(this)">Remove Card</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addCard()">Add Another Card</button>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Time Limit (seconds)</label>
                                <input type="number" name="content[time_limit]" class="form-input" value="{{ old('content[time_limit]') ?? 300 }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Hints</label>
                                <input type="number" name="content[max_hints]" class="form-input" value="{{ old('content[max_hints]') ?? 3 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hints (one per line)</label>
                            <textarea name="content[hints]" class="form-textarea" rows="3" placeholder="Read carefully...\nThink about key concepts...\nConsider definitions...">{{ old('content[hints]') }}</textarea>
                        </div>
                    </div>
                `;
            } else if (levelType === 'reorder') {
                dynamicContent.innerHTML = `
                    <div class="content-group">
                        <div class="form-group">
                            <label class="form-label">Code Lines (one per line, in correct order)</label>
                            <textarea name="content[lines]" class="form-textarea" rows="6" placeholder="i = 0\nwhile i < 4:\n    print(i)\n    i += 1" required>{{ old('content[lines]') }}</textarea>
                            <div class="form-help">Enter the lines of code in their correct order. They will be shuffled for the student.</div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Time Limit (seconds)</label>
                                <input type="number" name="content[time_limit]" class="form-input" value="{{ old('content[time_limit]') ?? 240 }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Hints</label>
                                <input type="number" name="content[max_hints]" class="form-input" value="{{ old('content[max_hints]') ?? 4 }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hints (one per line)</label>
                            <textarea name="content[hints]" class="form-textarea" rows="3" placeholder="Think about the logical flow...\nConsider variable initialization...\nCheck indentation carefully...">{{ old('content[hints]') }}</textarea>
                        </div>
                    </div>
                `;
            }

            // 🔥 Add shared "Examples" section for ALL level types
            dynamicContent.insertAdjacentHTML('beforeend', buildExamplesSection());

            // Ensure correct enabled/disabled state after render
            dynamicContent.querySelectorAll('.qtype').forEach(sel => toggleMCFields(sel));
            dynamicContent.querySelectorAll('.tf-qtype').forEach(sel => toggleTFFields(sel));
        }

        // ============== Builders for question blocks ==============
        function buildMCQuestionItem(index){
            return `
                <div class="question-item" data-index="${index}">
                    <div class="question-header">Question ${index + 1}</div>

                    <div class="form-group">
                        <label class="form-label">Question Type</label>
                        <select name="content[questions][${index}][type]" class="form-select qtype" onchange="toggleMCFields(this)">
                            <option value="mc" selected>Multiple Choice</option>
                            <option value="code">Code (console)</option>
                        </select>
                        <div class="form-help">Choose normal MC or a coding/output question</div>
                    </div>

                    <!-- Multiple-choice fields -->
                    <div class="mc-fields">
                        <div class="form-group">
                            <label class="form-label">Question</label>
                            <textarea name="content[questions][${index}][question]" class="form-textarea" rows="2" placeholder="Enter the question here"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Options (one per line)</label>
                            <textarea name="content[questions][${index}][options]" class="form-textarea" rows="3" placeholder="Option 0\nOption 1\nOption 2\nOption 3"></textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Correct Answer (line number, starting from 0)</label>
                                <input type="number" name="content[questions][${index}][correct_answer]" class="form-input" min="0" value="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Explanation</label>
                                <input type="text" name="content[questions][${index}][explanation]" class="form-input" placeholder="Explain why this is correct">
                            </div>
                        </div>
                    </div>

                    <!-- Code question fields -->
                    <div class="code-fields d-none">
                        <div class="form-group">
                            <label class="form-label">Prompt (what should the student print?)</label>
                            <textarea name="content[questions][${index}][question]" class="form-textarea" rows="2" placeholder="e.g., Write a loop that prints 1 to 5"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Starter Code (optional)</label>
                            <textarea name="content[questions][${index}][starter_code]" class="form-textarea" rows="4" placeholder="# Write your code here\n"></textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Expected Output</label>
                                <textarea name="content[questions][${index}][expected_output]" class="form-textarea" rows="3" placeholder="Exact output to match (\\n for new lines)"></textarea>
                                <div class="form-help">We’ll compare student output to this (trimmed)</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Output Regex (optional)</label>
                                <input type="text" name="content[questions][${index}][output_regex]" class="form-input" placeholder="^\\s*Hello\\s+World\\s*$">
                                <div class="form-help">Use if minor spacing/case variations are OK</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Explanation</label>
                            <input type="text" name="content[questions][${index}][explanation]" class="form-input" placeholder="Explain the intended solution">
                        </div>
                    </div>

                    <button type="button" class="btn-remove" onclick="removeQuestion(this)">Remove Question</button>
                </div>
            `;
        }

        function buildTFQuestionItem(index){
            return `
                <div class="tf-question-item" data-index="${index}">
                    <div class="question-header">Question ${index + 1}</div>

                    <div class="form-group">
                        <label class="form-label">Question Type</label>
                        <select name="content[questions][${index}][type]" class="form-select tf-qtype" onchange="toggleTFFields(this)">
                            <option value="tf" selected>True / False</option>
                            <option value="code">Code (console)</option>
                        </select>
                        <div class="form-help">Pick a True/False snippet or a coding/output question</div>
                    </div>

                    <!-- True/False fields -->
                    <div class="tf-fields">
                        <div class="form-group">
                            <label class="form-label">Code (shown to student)</label>
                            <textarea name="content[questions][${index}][code]" class="form-textarea" rows="3" placeholder="Enter code snippet here"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Statement</label>
                            <input type="text" name="content[questions][${index}][statement]" class="form-input" placeholder="Enter statement to evaluate">
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Answer</label>
                                <select name="content[questions][${index}][answer]" class="form-select">
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Explanation</label>
                                <input type="text" name="content[questions][${index}][explanation]" class="form-input" placeholder="Explain why this is true or false">
                            </div>
                        </div>
                    </div>

                    <!-- Code question fields -->
                    <div class="code-fields d-none">
                        <div class="form-group">
                            <label class="form-label">Prompt (what should the student print?)</label>
                            <textarea name="content[questions][${index}][question]" class="form-textarea" rows="2" placeholder="e.g., Print Yes if x == 7, else No"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Starter Code (optional)</label>
                            <textarea name="content[questions][${index}][starter_code]" class="form-textarea" rows="4" placeholder="# Write your code here\n"></textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Expected Output</label>
                                <textarea name="content[questions][${index}][expected_output]" class="form-textarea" rows="3" placeholder="Exact output to match (\\n for new lines)"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Output Regex (optional)</label>
                                <input type="text" name="content[questions][${index}][output_regex]" class="form-input" placeholder="^\\s*(Yes|YES)\\s*$">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Explanation</label>
                            <input type="text" name="content[questions][${index}][explanation]" class="form-input" placeholder="Explain the intended solution">
                        </div>
                    </div>

                    <button type="button" class="btn-remove" onclick="removeTFQuestion(this)">Remove Question</button>
                </div>
            `;
        }

        // ============== Shared EXAMPLES section ==============
        function buildExamplesSection() {
            return `
              <div class="content-group">
                <div class="section-divider" style="margin-top:0;">
                  <span class="divider-text">Examples (shown on Instructions page)</span>
                </div>

                <p class="form-help" style="margin-top:-.5rem">
                  Add small runnable examples for learners. Each example supports: <b>title</b>, <b>code</b>,
                  <b>explain</b>, <b>expected_output</b>. Stored in <code>content.examples</code>.
                </p>

                <div id="examples-container">
                  ${buildExampleItem(0)}
                </div>

                <button type="button" class="btn-add" onclick="addExample()">Add Example</button>
              </div>
            `;
        }

        function buildExampleItem(index) {
            return `
              <div class="example-item">
                <div class="example-header">Example ${index + 1}</div>

                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="content[examples][${index}][title]" class="form-input"
                           placeholder="e.g., Define, then call">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Expected Output</label>
                    <input type="text" name="content[examples][${index}][expected_output]" class="form-input"
                           placeholder="What should appear when code runs? (e.g., Hi)">
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Code</label>
                  <textarea name="content[examples][${index}][code]" class="form-textarea" rows="4"
                            placeholder="def hello():&#10;    print('Hi')&#10;&#10;hello()"></textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Explain</label>
                  <textarea name="content[examples][${index}][explain]" class="form-textarea" rows="2"
                            placeholder="Short friendly explanation learners will see"></textarea>
                </div>

                <button type="button" class="btn-remove" onclick="removeExample(this)">Remove Example</button>
              </div>
            `;
        }

        function addExample() {
            const container = document.getElementById('examples-container');
            const index = container.querySelectorAll('.example-item').length;
            const wrap = document.createElement('div');
            wrap.innerHTML = buildExampleItem(index);
            container.appendChild(wrap.firstElementChild);
        }

        function removeExample(btn) {
            const container = document.getElementById('examples-container');
            const items = container.querySelectorAll('.example-item');
            const block = btn.closest('.example-item');
            if (items.length > 1) {
                block.remove();
                // Re-index names
                container.querySelectorAll('.example-item').forEach((item, i) => {
                    item.querySelector('.example-header').textContent = 'Example ' + (i + 1);
                    item.querySelectorAll('input, textarea').forEach(el => {
                        el.name = el.name.replace(/content\[examples]\[\d+]/, `content[examples][${i}]`);
                    });
                });
            }
        }

        // ============== Toggles ==============
        function setDisabled(container, disabled){
            if(!container) return;
            container.querySelectorAll('input, textarea, select').forEach(el => {
                el.disabled = !!disabled;
            });
        }

        function toggleMCFields(selectEl){
            const wrapper = selectEl.closest('.question-item');
            const mc = wrapper.querySelector('.mc-fields');
            const code = wrapper.querySelector('.code-fields');
            const isCode = selectEl.value === 'code';

            mc.classList.toggle('d-none', isCode);
            code.classList.toggle('d-none', !isCode);
            setDisabled(mc, isCode);
            setDisabled(code, !isCode);
        }

        function toggleTFFields(selectEl){
            const wrapper = selectEl.closest('.tf-question-item');
            const tf = wrapper.querySelector('.tf-fields');
            const code = wrapper.querySelector('.code-fields');
            const isCode = selectEl.value === 'code';

            tf.classList.toggle('d-none', isCode);
            code.classList.toggle('d-none', !isCode);
            setDisabled(tf, isCode);
            setDisabled(code, !isCode);
        }

        // ============== Drag_drop helpers ==============
        function addCategory() {
            const container = document.getElementById('category-container');
            const index = container.children.length;
            const newCategory = document.createElement('div');
            newCategory.classList.add('category-item');
            newCategory.innerHTML = `
                <div class="category-header">Category ${index + 1}</div>
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="content[categories][${index}][name]" class="form-input" placeholder="e.g., 💻 Programming" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Items (one per line)</label>
                    <textarea name="content[categories][${index}][items]" class="form-textarea" rows="3" placeholder="🐍 Python\n☕ Java\n📋 Excel Macros" required></textarea>
                </div>
                <button type="button" class="btn-remove" onclick="removeCategory(this)">Remove Category</button>
            `;
            container.appendChild(newCategory);
        }
        
        function removeCategory(button) {
            const item = button.closest('.category-item');
            if (document.querySelectorAll('.category-item').length > 1) item.remove();
        }

        // ============== MC helpers ==============
        function addQuestion() {
            const container = document.getElementById('questions-container');
            const index = container.children.length;
            const div = document.createElement('div');
            div.innerHTML = buildMCQuestionItem(index);
            const el = div.firstElementChild;
            container.appendChild(el);
            // initialize disabled/enabled state
            const sel = el.querySelector('.qtype');
            if (sel) toggleMCFields(sel);
        }
        
        function removeQuestion(button) {
            const item = button.closest('.question-item');
            if (document.querySelectorAll('.question-item').length > 1) item.remove();
        }

        // ============== TF helpers ==============
        function addTFQuestion() {
            const container = document.getElementById('tf-questions-container');
            const index = container.children.length;
            const div = document.createElement('div');
            div.innerHTML = buildTFQuestionItem(index);
            const el = div.firstElementChild;
            container.appendChild(el);
            const sel = el.querySelector('.tf-qtype');
            if (sel) toggleTFFields(sel);
        }
        
        function removeTFQuestion(button) {
            const item = button.closest('.tf-question-item');
            if (document.querySelectorAll('.tf-question-item').length > 1) item.remove();
        }
        
        // ============== Match_pairs helpers ==============
        function addPair() {
            const container = document.getElementById('pairs-container');
            const index = container.children.length;
            const newPair = document.createElement('div');
            newPair.classList.add('pair-item');
            newPair.innerHTML = `
                <div class="question-header">Pair ${index + 1}</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Left Item</label>
                        <input type="text" name="content[pairs][${index}][left]" class="form-input" placeholder="Item to match" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Right Item</label>
                        <input type="text" name="content[pairs][${index}][right]" class="form-input" placeholder="Matching item" required>
                    </div>
                </div>
                <button type="button" class="btn-remove" onclick="removePair(this)">Remove Pair</button>
            `;
            container.appendChild(newPair);
        }
        
        function removePair(button) {
            const item = button.closest('.pair-item');
            if (document.querySelectorAll('.pair-item').length > 1) item.remove();
        }
        
        function addSequence() {
            const container = document.getElementById('sequences-container');
            const index = container.children.length;
            const newSequence = document.createElement('div');
            newSequence.classList.add('sequence-item');
            newSequence.innerHTML = `
                <div class="question-header">Sequence ${index + 1}</div>
                <div class="form-group">
                    <label class="form-label">Sequence Title</label>
                    <input type="text" name="content[sequences][${index}][title]" class="form-input" placeholder="e.g., Process Steps" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Steps (one per line)</label>
                    <textarea name="content[sequences][${index}][steps]" class="form-textarea" rows="3" placeholder="Step 1\nStep 2\nStep 3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Correct Order (comma-separated line numbers, starting from 0)</label>
                    <input type="text" name="content[sequences][${index}][correct_order]" class="form-input" placeholder="e.g., 0,1,2" required>
                </div>
                <button type="button" class="btn-remove" onclick="removeSequence(this)">Remove Sequence</button>
            `;
            container.appendChild(newSequence);
        }
        
        function removeSequence(button) {
            const item = button.closest('.sequence-item');
            if (document.querySelectorAll('.sequence-item').length > 1) item.remove();
        }

        // ============== Flip_cards helpers ==============
        function addCard() {
            const container = document.getElementById('cards-container');
            const index = container.children.length;
            const newCard = document.createElement('div');
            newCard.classList.add('card-item');
            newCard.innerHTML = `
                <div class="question-header">Card ${index + 1}</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Front (Question/Prompt)</label>
                        <textarea name="content[cards][${index}][front]" class="form-textarea" rows="2" placeholder="Enter front side content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Back (Answer/Explanation)</label>
                        <textarea name="content[cards][${index}][back]" class="form-textarea" rows="2" placeholder="Enter back side content" required></textarea>
                    </div>
                </div>
                <button type="button" class="btn-remove" onclick="removeCard(this)">Remove Card</button>
            `;
            container.appendChild(newCard);
        }
        
        function removeCard(button) {
            const item = button.closest('.card-item');
            if (document.querySelectorAll('.card-item').length > 1) item.remove();
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        .d-none{display:none !important;}

        :root {
            --purple-50: #faf5ff;
            --purple-100: #f3e8ff;
            --purple-200: #e9d5ff;
            --purple-300: #d8b4fe;
            --purple-400: #c084fc;
            --purple-500: #a855f7;
            --purple-600: #9333ea;
            --purple-700: #7c3aed;
            --purple-800: #6b21a8;
            --purple-900: #581c87;
            --gradient-primary: linear-gradient(135deg, var(--purple-600) 0%, var(--purple-800) 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .form-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .form-header-container::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }
        .form-icon-wrapper { width: 4rem; height: 4rem; background: rgba(255, 255, 255, 0.2); border-radius: 1rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); }
        .form-icon-wrapper i { font-size: 1.5rem; color: #10b981; }
        .form-title { font-size: 1.75rem; font-weight: 700; color: white; margin: 0; letter-spacing: -0.025em; }
        .form-subtitle { font-size: 1rem; color: rgba(255, 255, 255, 0.8); margin: 0.25rem 0 0 0; font-weight: 400; }

        .form-container { padding: 2rem; max-width: 1200px; margin: 0 auto; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: calc(100vh - 200px); }
        .form-card { background: white; border-radius: 1.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(139, 92, 246, 0.1); overflow: hidden; }
        .form-card-header { background: var(--purple-50); padding: 2rem 2rem 1.5rem 2rem; border-bottom: 1px solid var(--purple-200); }
        .card-title { font-size: 1.5rem; font-weight: 700; color: var(--purple-900); margin: 0 0 0.5rem 0; }
        .card-description { color: #64748b; margin: 0; font-size: 1rem; }
        .level-form { padding: 2rem; }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 2rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--purple-900); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-input, .form-textarea, .form-select { width: 100%; padding: 0.875rem 1rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.2s ease; background: white; color: #1a202c; }
        .form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: var(--purple-400); box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1); }
        .form-textarea { resize: vertical; font-family: inherit; }
        .form-help { font-size: 0.875rem; color: #64748b; margin-top: 0.5rem; }

        .content-section { margin-top: 2rem; }
        .section-divider { display: flex; align-items: center; margin: 2rem 0; }
        .section-divider::before, .section-divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        .divider-text { padding: 0 1rem; font-weight: 600; color: var(--purple-700); background: white; }

        .content-group { background: var(--purple-50); border-radius: 1rem; padding: 2rem; border: 1px solid var(--purple-200); }
        .question-item, .category-item, .tf-question-item, .pair-item, .card-item, .sequence-item, .example-item { background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; }
        .question-header, .category-header, .example-header { font-size: 1.125rem; font-weight: 600; color: var(--purple-800); margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; }

        .btn-add, .btn-remove { padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; }
        .btn-add { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .btn-add:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
        .btn-remove { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .btn-remove:hover { background: #fecaca; transform: translateY(-1px); }

        .form-actions { display: flex; gap: 1rem; padding-top: 2rem; border-top: 1px solid #e2e8f0; margin-top: 2rem; }
        .btn-primary, .btn-secondary { padding: 0.875rem 2rem; border-radius: 0.75rem; text-decoration: none; font-size: 1rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-primary { background: var(--gradient-primary); color: white; box-shadow: var(--shadow-lg); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: var(--shadow-xl); color: white; }
        .btn-secondary { background: #f8fafc; color: #64748b; border: 2px solid #e2e8f0; }
        .btn-secondary:hover { background: #f1f5f9; color: #475569; border-color: #cbd5e1; transform: translateY(-1px); }

        @media (max-width: 768px) {
            .form-container { padding: 1rem; }
            .form-card-header, .level-form { padding: 1.5rem; }
            .form-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column; }
            .btn-primary, .btn-secondary { justify-content: center; }
        }
    </style>
</x-app-layout>
