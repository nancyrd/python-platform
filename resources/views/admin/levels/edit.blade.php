<x-app-layout>
    <x-slot name="header">
        <div class="levels-header-container">
            <div class="flex items-center justify-content-between">
                <div class="flex items-center">
                    <div class="levels-icon-wrapper">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="levels-title">Edit Level: {{ $level->title }}</h2>
                        <p class="levels-subtitle">Modify learning challenge content</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    
    <!-- Back Button Above Container -->
    <div class="add-level-button-container">
        <a href="{{ route('admin.stages.levels.index', $stage) }}" class="btn-create-level">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Levels</span>
        </a>
    </div>
    
    <div class="levels-container">
        <div class="levels-table-container">
            <div class="table-header">
                <h3 class="table-title">Level Configuration</h3>
                <p class="table-description">Edit the level details and content below</p>
            </div>
            
            <div class="form-wrapper">
                <form method="POST" action="{{ route('admin.stages.levels.update', [$stage, $level]) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Index -->
                    <div class="form-group">
                        <label class="form-label">Order / Index</label>
                        <input type="number" name="index" class="form-control" value="{{ old('index', $level->index) }}" required>
                    </div>
                    
                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $level->title) }}" required>
                    </div>
                    
                    <!-- Type -->
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select name="type" id="levelType" class="form-select" required>
                            <option value="drag_drop" {{ $level->type == 'drag_drop' ? 'selected' : '' }}>Drag & Drop</option>
                            <option value="multiple_choice" {{ $level->type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="tf1" {{ $level->type == 'tf1' ? 'selected' : '' }}>True/False</option>
                            <option value="match_pairs" {{ $level->type == 'match_pairs' ? 'selected' : '' }}>Match Pairs</option>
                            <option value="flip_cards" {{ $level->type == 'flip_cards' ? 'selected' : '' }}>Flip Cards</option>
                            <option value="reorder" {{ $level->type == 'reorder' ? 'selected' : '' }}>Reorder Code</option>
                        </select>
                    </div>
                    
                    <!-- Pass Score -->
                    <div class="form-group">
                        <label class="form-label">Pass Score (%)</label>
                        <input type="number" name="pass_score" class="form-control" value="{{ old('pass_score', $level->pass_score) }}" required>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="form-group">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $level->instructions) }}</textarea>
                    </div>
                    
                    <!-- Dynamic Content Based on Level Type -->
                    <div id="dynamic-content"></div>
                    
                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i>
                            <span>Update Level</span>
                        </button>
                        <a href="{{ route('admin.stages.levels.index', $stage) }}" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Store original level data
        const originalLevel = @json($level);
        
        // Dynamic form field rendering based on level type
        document.getElementById('levelType').addEventListener('change', updateContentFields);
        updateContentFields();
        
        function updateContentFields() {
            const levelType = document.getElementById('levelType').value;
            const dynamicContent = document.getElementById('dynamic-content');
            dynamicContent.innerHTML = '';
            
            if (levelType === 'multiple_choice') {
                // Get existing questions or create empty array
                const questions = originalLevel.content && originalLevel.content.questions ? originalLevel.content.questions : [];
                let questionsHtml = '';
                
                if (questions.length > 0) {
                    questions.forEach((question, index) => {
                        const options = Array.isArray(question.options) ? question.options.join('\n') : '';
                        questionsHtml += `
                            <div class="question-item mb-4 p-3 border rounded">
                                <div class="mb-2">
                                    <label class="form-label">Question</label>
                                    <textarea name="content[questions][${index}][question]" class="form-control" rows="2" required>${question.question || ''}</textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Options (one per line)</label>
                                    <textarea name="content[questions][${index}][options]" class="form-control" rows="3" required>${options}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Correct Answer (line number, starting from 0)</label>
                                        <input type="number" name="content[questions][${index}][correct_answer]" class="form-control" min="0" value="${question.correct_answer || 0}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Explanation</label>
                                        <input type="text" name="content[questions][${index}][explanation]" class="form-control" value="${question.explanation || ''}" required>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeQuestion(this)">Remove Question</button>
                            </div>
                        `;
                    });
                } else {
                    // Add empty question form if none exist
                    questionsHtml = `
                        <div class="question-item mb-4 p-3 border rounded">
                            <div class="mb-2">
                                <label class="form-label">Question</label>
                                <textarea name="content[questions][0][question]" class="form-control" rows="2" placeholder="Enter the question here" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Options (one per line)</label>
                                <textarea name="content[questions][0][options]" class="form-control" rows="3" placeholder="Option 1&#10;Option 2&#10;Option 3&#10;Option 4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Correct Answer (line number, starting from 0)</label>
                                    <input type="number" name="content[questions][0][correct_answer]" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Explanation</label>
                                    <input type="text" name="content[questions][0][explanation]" class="form-control" placeholder="Explain why this is the correct answer" required>
                                </div>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeQuestion(this)">Remove Question</button>
                        </div>
                    `;
                }
                
                dynamicContent.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Intro</label>
                        <textarea name="content[intro]" class="form-control" rows="2">${originalLevel.content && originalLevel.content.intro ? originalLevel.content.intro : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Questions</label>
                        <div id="questions-container">
                            ${questionsHtml}
                        </div>
                        <button type="button" class="btn-add mt-2" onclick="addQuestion()">Add Another Question</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hints (one per line)</label>
                        <textarea name="content[hints]" class="form-control" rows="3">${originalLevel.content && originalLevel.content.hints ? originalLevel.content.hints.join('\n') : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" name="content[time_limit]" class="form-control" value="${originalLevel.content && originalLevel.content.time_limit ? originalLevel.content.time_limit : 180}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Hints</label>
                        <input type="number" name="content[max_hints]" class="form-control" value="${originalLevel.content && originalLevel.content.max_hints ? originalLevel.content.max_hints : 3}">
                    </div>
                `;
            } else if (levelType === 'drag_drop') {
                // Get existing categories or create empty array
                const categories = originalLevel.content && originalLevel.content.categories ? originalLevel.content.categories : {};
                let categoriesHtml = '';
                
                if (Object.keys(categories).length > 0) {
                    Object.entries(categories).forEach(([name, items], index) => {
                        const itemsText = Array.isArray(items) ? items.join('\n') : '';
                        categoriesHtml += `
                            <div class="category-item mb-3 p-3 border rounded">
                                <div class="mb-2">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="content[categories][${index}][name]" class="form-control" value="${name}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Items (one per line)</label>
                                    <textarea name="content[categories][${index}][items]" class="form-control" rows="3" required>${itemsText}</textarea>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeCategory(this)">Remove Category</button>
                            </div>
                        `;
                    });
                } else {
                    // Add empty category form if none exist
                    categoriesHtml = `
                        <div class="category-item mb-3 p-3 border rounded">
                            <div class="mb-2">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="content[categories][0][name]" class="form-control" placeholder="e.g., 💻 Programming" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Items (one per line)</label>
                                <textarea name="content[categories][0][items]" class="form-control" rows="3" placeholder="🐍 Python&#10;☕ Java&#10;📋 Excel Macros" required></textarea>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeCategory(this)">Remove Category</button>
                        </div>
                    `;
                }
                
                dynamicContent.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Categories and Items</label>
                        <div id="category-container">
                            ${categoriesHtml}
                        </div>
                        <button type="button" class="btn-add mt-2" onclick="addCategory()">Add Another Category</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hints (one per line)</label>
                        <textarea name="content[hints]" class="form-control" rows="3">${originalLevel.content && originalLevel.content.hints ? originalLevel.content.hints.join('\n') : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" name="content[time_limit]" class="form-control" value="${originalLevel.content && originalLevel.content.time_limit ? originalLevel.content.time_limit : 300}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Hints</label>
                        <input type="number" name="content[max_hints]" class="form-control" value="${originalLevel.content && originalLevel.content.max_hints ? originalLevel.content.max_hints : 4}">
                    </div>
                `;
            } else if (levelType === 'tf1') {
                // Get existing questions or create empty array
                const questions = originalLevel.content && originalLevel.content.questions ? originalLevel.content.questions : [];
                let questionsHtml = '';
                
                if (questions.length > 0) {
                    questions.forEach((question, index) => {
                        questionsHtml += `
                            <div class="tf-question-item mb-4 p-3 border rounded">
                                <div class="mb-2">
                                    <label class="form-label">Code</label>
                                    <textarea name="content[questions][${index}][code]" class="form-control" rows="2" required>${question.code || ''}</textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Statement</label>
                                    <input type="text" name="content[questions][${index}][statement]" class="form-control" value="${question.statement || ''}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Answer</label>
                                    <select name="content[questions][${index}][answer]" class="form-select" required>
                                        <option value="true" ${question.answer === true ? 'selected' : ''}>True</option>
                                        <option value="false" ${question.answer === false ? 'selected' : ''}>False</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Explanation</label>
                                    <input type="text" name="content[questions][${index}][explanation]" class="form-control" value="${question.explanation || ''}" required>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeTFQuestion(this)">Remove Question</button>
                            </div>
                        `;
                    });
                } else {
                    // Add empty question form if none exist
                    questionsHtml = `
                        <div class="tf-question-item mb-4 p-3 border rounded">
                            <div class="mb-2">
                                <label class="form-label">Code</label>
                                <textarea name="content[questions][0][code]" class="form-control" rows="2" placeholder="Enter code snippet here" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Statement</label>
                                <input type="text" name="content[questions][0][statement]" class="form-control" placeholder="Enter statement to evaluate" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Answer</label>
                                <select name="content[questions][0][answer]" class="form-select" required>
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Explanation</label>
                                <input type="text" name="content[questions][0][explanation]" class="form-control" placeholder="Explain why this is true or false" required>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeTFQuestion(this)">Remove Question</button>
                        </div>
                    `;
                }
                
                dynamicContent.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Intro</label>
                        <textarea name="content[intro]" class="form-control" rows="2">${originalLevel.content && originalLevel.content.intro ? originalLevel.content.intro : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">True/False Questions</label>
                        <div id="tf-questions-container">
                            ${questionsHtml}
                        </div>
                        <button type="button" class="btn-add mt-2" onclick="addTFQuestion()">Add Another Question</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hints (one per line)</label>
                        <textarea name="content[hints]" class="form-control" rows="3">${originalLevel.content && originalLevel.content.hints ? originalLevel.content.hints.join('\n') : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" name="content[time_limit]" class="form-control" value="${originalLevel.content && originalLevel.content.time_limit ? originalLevel.content.time_limit : 240}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Hints</label>
                        <input type="number" name="content[max_hints]" class="form-control" value="${originalLevel.content && originalLevel.content.max_hints ? originalLevel.content.max_hints : 3}">
                    </div>
                `;
            } else if (levelType === 'match_pairs') {
                // Get existing pairs or create empty array
                const pairs = originalLevel.content && originalLevel.content.pairs ? originalLevel.content.pairs : [];
                let pairsHtml = '';
                
                if (pairs.length > 0) {
                    pairs.forEach((pair, index) => {
                        pairsHtml += `
                            <div class="pair-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Left Item</label>
                                        <input type="text" name="content[pairs][${index}][left]" class="form-control" value="${pair.left || ''}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Right Item</label>
                                        <input type="text" name="content[pairs][${index}][right]" class="form-control" value="${pair.right || ''}" required>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove" onclick="removePair(this)">Remove Pair</button>
                            </div>
                        `;
                    });
                } else {
                    // Add empty pair form if none exist
                    pairsHtml = `
                        <div class="pair-item mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Left Item</label>
                                    <input type="text" name="content[pairs][0][left]" class="form-control" placeholder="Item to match" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Right Item</label>
                                    <input type="text" name="content[pairs][0][right]" class="form-control" placeholder="Matching item" required>
                                </div>
                            </div>
                            <button type="button" class="btn-remove" onclick="removePair(this)">Remove Pair</button>
                        </div>
                    `;
                }
                
                // Get existing sequences or create empty array
                const sequences = originalLevel.content && originalLevel.content.sequences ? originalLevel.content.sequences : [];
                let sequencesHtml = '';
                
                if (sequences.length > 0) {
                    sequences.forEach((sequence, index) => {
                        const steps = Array.isArray(sequence.steps) ? sequence.steps.join('\n') : '';
                        const correctOrder = Array.isArray(sequence.correct_order) ? sequence.correct_order.join(',') : '';
                        sequencesHtml += `
                            <div class="sequence-item mb-4 p-3 border rounded">
                                <div class="mb-2">
                                    <label class="form-label">Sequence Title</label>
                                    <input type="text" name="content[sequences][${index}][title]" class="form-control" value="${sequence.title || ''}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Steps (one per line)</label>
                                    <textarea name="content[sequences][${index}][steps]" class="form-control" rows="3" required>${steps}</textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Correct Order (comma-separated line numbers, starting from 0)</label>
                                    <input type="text" name="content[sequences][${index}][correct_order]" class="form-control" value="${correctOrder}" required>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeSequence(this)">Remove Sequence</button>
                            </div>
                        `;
                    });
                } else {
                    // Add empty sequence form if none exist
                    sequencesHtml = `
                        <div class="sequence-item mb-4 p-3 border rounded">
                            <div class="mb-2">
                                <label class="form-label">Sequence Title</label>
                                <input type="text" name="content[sequences][0][title]" class="form-control" placeholder="e.g., Process Steps" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Steps (one per line)</label>
                                <textarea name="content[sequences][0][steps]" class="form-control" rows="3" placeholder="Step 1&#10;Step 2&#10;Step 3" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Correct Order (comma-separated line numbers, starting from 0)</label>
                                <input type="text" name="content[sequences][0][correct_order]" class="form-control" placeholder="e.g., 0,1,2" required>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeSequence(this)">Remove Sequence</button>
                        </div>
                    `;
                }
                
                dynamicContent.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Intro</label>
                        <textarea name="content[intro]" class="form-control" rows="2">${originalLevel.content && originalLevel.content.intro ? originalLevel.content.intro : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Matching Pairs</label>
                        <div id="pairs-container">
                            ${pairsHtml}
                        </div>
                        <button type="button" class="btn-add mt-2" onclick="addPair()">Add Another Pair</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sequences</label>
                        <div id="sequences-container">
                            ${sequencesHtml}
                        </div>
                        <button type="button" class="btn-add mt-2" onclick="addSequence()">Add Another Sequence</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hints (one per line)</label>
                        <textarea name="content[hints]" class="form-control" rows="3">${originalLevel.content && originalLevel.content.hints ? originalLevel.content.hints.join('\n') : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" name="content[time_limit]" class="form-control" value="${originalLevel.content && originalLevel.content.time_limit ? originalLevel.content.time_limit : 240}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Hints</label>
                        <input type="number" name="content[max_hints]" class="form-control" value="${originalLevel.content && originalLevel.content.max_hints ? originalLevel.content.max_hints : 3}">
                    </div>
                `;
            } else if (levelType === 'flip_cards') {
                // Get existing cards or create empty array
                const cards = originalLevel.content && originalLevel.content.cards ? originalLevel.content.cards : [];
                let cardsHtml = '';
                
                if (cards.length > 0) {
                    cards.forEach((card, index) => {
                        cardsHtml += `
                            <div class="card-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Front (Question/Prompt)</label>
                                        <textarea name="content[cards][${index}][front]" class="form-control" rows="2" required>${card.front || ''}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Back (Answer/Explanation)</label>
                                        <textarea name="content[cards][${index}][back]" class="form-control" rows="2" required>${card.back || ''}</textarea>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeCard(this)">Remove Card</button>
                            </div>
                        `;
                    });
                } else {
                    // Add empty card form if none exist
                    cardsHtml = `
                        <div class="card-item mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Front (Question/Prompt)</label>
                                    <textarea name="content[cards][0][front]" class="form-control" rows="2" placeholder="Enter front side content" required></textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Back (Answer/Explanation)</label>
                                    <textarea name="content[cards][0][back]" class="form-control" rows="2" placeholder="Enter back side content" required></textarea>
                                </div>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeCard(this)">Remove Card</button>
                        </div>
                    `;
                }
                
                dynamicContent.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Intro</label>
                        <textarea name="content[intro]" class="form-control" rows="2">${originalLevel.content && originalLevel.content.intro ? originalLevel.content.intro : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cards</label>
                        <div id="cards-container">
                            ${cardsHtml}
                        </div>
                        <button type="button" class="btn-add mt-2" onclick="addCard()">Add Another Card</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hints (one per line)</label>
                        <textarea name="content[hints]" class="form-control" rows="3">${originalLevel.content && originalLevel.content.hints ? originalLevel.content.hints.join('\n') : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" name="content[time_limit]" class="form-control" value="${originalLevel.content && originalLevel.content.time_limit ? originalLevel.content.time_limit : 300}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Hints</label>
                        <input type="number" name="content[max_hints]" class="form-control" value="${originalLevel.content && originalLevel.content.max_hints ? originalLevel.content.max_hints : 3}">
                    </div>
                `;
            } else if (levelType === 'reorder') {
                // Get existing lines or create empty string
                const lines = originalLevel.content && originalLevel.content.lines ? originalLevel.content.lines.join('\n') : '';
                
                dynamicContent.innerHTML = `
                    <div class="form-group">
                        <label class="form-label">Code Lines (one per line, in correct order)</label>
                        <textarea name="content[lines]" class="form-control" rows="6" required>${lines}</textarea>
                        <div class="form-text">Enter the lines of code in their correct order. They will be shuffled for the student.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hints (one per line)</label>
                        <textarea name="content[hints]" class="form-control" rows="3">${originalLevel.content && originalLevel.content.hints ? originalLevel.content.hints.join('\n') : ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (seconds)</label>
                        <input type="number" name="content[time_limit]" class="form-control" value="${originalLevel.content && originalLevel.content.time_limit ? originalLevel.content.time_limit : 240}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Hints</label>
                        <input type="number" name="content[max_hints]" class="form-control" value="${originalLevel.content && originalLevel.content.max_hints ? originalLevel.content.max_hints : 4}">
                    </div>
                `;
            }
        }
        
        // Functions for drag_drop
        function addCategory() {
            const container = document.getElementById('category-container');
            const index = container.children.length;
            const newCategory = document.createElement('div');
            newCategory.classList.add('category-item', 'mb-3', 'p-3', 'border', 'rounded');
            newCategory.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="content[categories][${index}][name]" class="form-control" placeholder="e.g., 💻 Programming" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Items (one per line)</label>
                    <textarea name="content[categories][${index}][items]" class="form-control" rows="3" placeholder="🐍 Python&#10;☕ Java&#10;📋 Excel Macros" required></textarea>
                </div>
                <button type="button" class="btn-remove" onclick="removeCategory(this)">Remove Category</button>
            `;
            container.appendChild(newCategory);
        }
        
        function removeCategory(button) {
            const categoryItem = button.closest('.category-item');
            categoryItem.remove();
        }
        
        // Functions for multiple_choice
        function addQuestion() {
            const container = document.getElementById('questions-container');
            const index = container.children.length;
            const newQuestion = document.createElement('div');
            newQuestion.classList.add('question-item', 'mb-4', 'p-3', 'border', 'rounded');
            newQuestion.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Question</label>
                    <textarea name="content[questions][${index}][question]" class="form-control" rows="2" placeholder="Enter the question here" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Options (one per line)</label>
                    <textarea name="content[questions][${index}][options]" class="form-control" rows="3" placeholder="Option 1&#10;Option 2&#10;Option 3&#10;Option 4" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Correct Answer (line number, starting from 0)</label>
                        <input type="number" name="content[questions][${index}][correct_answer]" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Explanation</label>
                        <input type="text" name="content[questions][${index}][explanation]" class="form-control" placeholder="Explain why this is the correct answer" required>
                    </div>
                </div>
                <button type="button" class="btn-remove" onclick="removeQuestion(this)">Remove Question</button>
            `;
            container.appendChild(newQuestion);
        }
        
        function removeQuestion(button) {
            const questionItem = button.closest('.question-item');
            questionItem.remove();
        }
        
        // Functions for tf1
        function addTFQuestion() {
            const container = document.getElementById('tf-questions-container');
            const index = container.children.length;
            const newQuestion = document.createElement('div');
            newQuestion.classList.add('tf-question-item', 'mb-4', 'p-3', 'border', 'rounded');
            newQuestion.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Code</label>
                    <textarea name="content[questions][${index}][code]" class="form-control" rows="2" placeholder="Enter code snippet here" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Statement</label>
                    <input type="text" name="content[questions][${index}][statement]" class="form-control" placeholder="Enter statement to evaluate" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Answer</label>
                    <select name="content[questions][${index}][answer]" class="form-select" required>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Explanation</label>
                    <input type="text" name="content[questions][${index}][explanation]" class="form-control" placeholder="Explain why this is true or false" required>
                </div>
                <button type="button" class="btn-remove" onclick="removeTFQuestion(this)">Remove Question</button>
            `;
            container.appendChild(newQuestion);
        }
        
        function removeTFQuestion(button) {
            const questionItem = button.closest('.tf-question-item');
            questionItem.remove();
        }
        
        // Functions for match_pairs
        function addPair() {
            const container = document.getElementById('pairs-container');
            const index = container.children.length;
            const newPair = document.createElement('div');
            newPair.classList.add('pair-item', 'mb-3', 'p-3', 'border', 'rounded');
            newPair.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Left Item</label>
                        <input type="text" name="content[pairs][${index}][left]" class="form-control" placeholder="Item to match" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Right Item</label>
                        <input type="text" name="content[pairs][${index}][right]" class="form-control" placeholder="Matching item" required>
                    </div>
                </div>
                <button type="button" class="btn-remove" onclick="removePair(this)">Remove Pair</button>
            `;
            container.appendChild(newPair);
        }
        
        function removePair(button) {
            const pairItem = button.closest('.pair-item');
            pairItem.remove();
        }
        
        function addSequence() {
            const container = document.getElementById('sequences-container');
            const index = container.children.length;
            const newSequence = document.createElement('div');
            newSequence.classList.add('sequence-item', 'mb-4', 'p-3', 'border', 'rounded');
            newSequence.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Sequence Title</label>
                    <input type="text" name="content[sequences][${index}][title]" class="form-control" placeholder="e.g., Process Steps" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Steps (one per line)</label>
                    <textarea name="content[sequences][${index}][steps]" class="form-control" rows="3" placeholder="Step 1&#10;Step 2&#10;Step 3" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Correct Order (comma-separated line numbers, starting from 0)</label>
                    <input type="text" name="content[sequences][${index}][correct_order]" class="form-control" placeholder="e.g., 0,1,2" required>
                </div>
                <button type="button" class="btn-remove" onclick="removeSequence(this)">Remove Sequence</button>
            `;
            container.appendChild(newSequence);
        }
        
        function removeSequence(button) {
            const sequenceItem = button.closest('.sequence-item');
            sequenceItem.remove();
        }
        
        // Functions for flip_cards
        function addCard() {
            const container = document.getElementById('cards-container');
            const index = container.children.length;
            const newCard = document.createElement('div');
            newCard.classList.add('card-item', 'mb-3', 'p-3', 'border', 'rounded');
            newCard.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Front (Question/Prompt)</label>
                        <textarea name="content[cards][${index}][front]" class="form-control" rows="2" placeholder="Enter front side content" required></textarea>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Back (Answer/Explanation)</label>
                        <textarea name="content[cards][${index}][back]" class="form-control" rows="2" placeholder="Enter back side content" required></textarea>
                    </div>
                </div>
                <button type="button" class="btn-remove" onclick="removeCard(this)">Remove Card</button>
            `;
            container.appendChild(newCard);
        }
        
        function removeCard(button) {
            const cardItem = button.closest('.card-item');
            cardItem.remove();
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
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
            --gradient-button: linear-gradient(135deg, var(--purple-700) 0%, var(--purple-900) 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        .levels-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .levels-header-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }
        .levels-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .levels-icon-wrapper i {
            font-size: 1.5rem;
            color: #fbbf24;
        }
        .levels-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.025em;
        }
        .levels-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
            font-weight: 400;
        }
        
        /* Add Level Button Container */
        .add-level-button-container {
            display: flex;
            justify-content: flex-start;
            padding: 1rem 2rem 0;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .btn-create-level {
            background: var(--gradient-button);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            border: none;
        }
        .btn-create-level:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            color: white;
        }
        .btn-create-level i {
            color: #fbbf24;
        }
        .levels-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }
        .levels-table-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(139, 92, 246, 0.1);
            overflow: hidden;
        }
        .table-header {
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: var(--purple-50);
        }
        .table-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0 0 0.5rem 0;
        }
        .table-description {
            color: #64748b;
            margin: 0;
            font-size: 0.875rem;
        }
        .form-wrapper {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: var(--purple-900);
            margin-bottom: 0.5rem;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: var(--purple-500);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
        }
        .form-select {
            display: block;
            width: 100%;
            padding: 0.75rem 2.25rem 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23495057' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            appearance: none;
        }
        .form-select:focus {
            border-color: var(--purple-500);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
        }
        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #6c757d;
        }
        .question-item, .category-item, .tf-question-item, .pair-item, .sequence-item, .card-item {
            background: var(--purple-50);
            border: 1px solid var(--purple-200);
        }
        .btn-add, .btn-remove {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .btn-add {
            background: var(--gradient-button);
            color: white;
            border: none;
        }
        .btn-add:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
        }
        .btn-remove {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }
        .btn-remove:hover {
            background: #fecaca;
            color: #991b1b;
            transform: translateY(-1px);
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn-submit, .btn-cancel {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .btn-submit {
            background: var(--gradient-button);
            color: white;
            border: none;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
        }
        .btn-cancel {
            background: #e2e8f0;
            color: #64748b;
            border-color: #cbd5e1;
        }
        .btn-cancel:hover {
            background: #cbd5e1;
            color: #475569;
            transform: translateY(-1px);
        }
        @media (max-width: 768px) {
            .levels-container {
                padding: 1rem;
            }
            .levels-header-container .flex {
                flex-direction: column;
                gap: 1rem;
            }
            .add-level-button-container {
                padding: 1rem 0 0;
                justify-content: center;
            }
            .btn-create-level {
                align-self: stretch;
                justify-content: center;
            }
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</x-app-layout>