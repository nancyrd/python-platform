<x-app-layout>
 @php
    // These are only for the Level page
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;
    $savedStars    = $levelProgress->stars ?? 0;
@endphp

    <x-slot name="header">
        <div class="epic-level-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="level-badge-container">
                            <div class="level-badge">
                                <span class="level-number">{{ $level->index }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="level-info">
                            <h2 class="level-title mb-1">{{ $level->stage->title }}</h2>
                            <div class="level-subtitle">
                                <i class="fas fa-gamepad me-2"></i>
                                Level {{ $level->index }} • {{ $level->type }} Challenge
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="level-stats">
                            <div class="stat-item me-3">
                                <div class="stat-icon">🏆</div>
                                <div class="stat-value" id="currentScore">0</div>
                                <div class="stat-label">Score</div>
                            </div>
                            <div class="stat-item me-3">
                                <div class="stat-icon">⭐</div>
                                <div class="stat-value" id="starsEarned">0</div>
                                <div class="stat-label">Stars</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">⏱️</div>
                                <div class="stat-value" id="timeRemaining">05:00</div>
                                <div class="stat-label">Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Epic Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #ffed4a 100%);
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .epic-level-header {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 3px solid #ffd700;
            padding: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .epic-level-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
            animation: headerShine 4s ease-in-out infinite;
        }

        @keyframes headerShine {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        .level-badge-container {
            position: relative;
        }

        .level-badge {
            width: 70px;
            height: 70px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
            animation: levelPulse 2s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }

        .level-number {
            font-size: 1.8rem;
            font-weight: 900;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        @keyframes levelPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 40px rgba(255, 215, 0, 0.8); }
        }

        .level-title {
            color: #ffd700;
            font-size: 1.8rem;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            letter-spacing: 1px;
        }

        .level-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .level-stats {
            display: flex;
            align-items: center;
        }

        .stat-item {
            text-align: center;
            color: white;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-width: 80px;
        }

        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: 900;
            color: #ffd700;
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .game-arena {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            margin: 30px auto;
            padding: 40px;
            max-width: 1200px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: 2px solid rgba(255, 215, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .game-arena::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,215,0,0.05) 0%, transparent 70%);
            animation: arenaGlow 6s ease-in-out infinite;
        }

        @keyframes arenaGlow {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }

        .challenge-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 900;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .challenge-description {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }

        .drag-drop-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin: 40px 0;
            position: relative;
            z-index: 2;
        }

        .draggables-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 30px;
            border: 3px dashed #dee2e6;
            position: relative;
            overflow: hidden;
        }

        .draggables-section::before {
            content: '🎯 DRAG FROM HERE';
            position: absolute;
            top: 15px;
            right: 20px;
            background: var(--primary-gradient);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }

        .draggable-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 3px solid #007bff;
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            cursor: grab;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(0,123,255,0.15);
            position: relative;
            overflow: hidden;
            user-select: none;
        }

        .draggable-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0,123,255,0.1), transparent);
            transition: left 0.5s ease;
        }

        .draggable-item:hover::before {
            left: 100%;
        }

        .draggable-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,123,255,0.25);
            border-color: #0056b3;
        }

        .draggable-item:active {
            cursor: grabbing;
            transform: scale(1.05);
            box-shadow: 0 20px 50px rgba(0,123,255,0.4);
            z-index: 1000;
        }

        .draggable-item.dragging {
            transform: rotate(5deg) scale(1.1);
            opacity: 0.8;
            z-index: 1000;
            box-shadow: 0 25px 60px rgba(0,123,255,0.5);
        }

        .item-icon {
            font-size: 2rem;
            margin-right: 15px;
            vertical-align: middle;
        }

        .item-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            vertical-align: middle;
        }

        .drop-zones-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 20px;
            padding: 30px;
            border: 3px dashed #ffc107;
            position: relative;
            overflow: hidden;
        }

        .drop-zones-section::before {
            content: '🎯 DROP HERE';
            position: absolute;
            top: 15px;
            right: 20px;
            background: var(--warning-gradient);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .drop-zone {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 3px dashed #ffc107;
            border-radius: 15px;
            padding: 25px;
            margin: 15px 0;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .drop-zone.drag-over {
            background: var(--success-gradient);
            border-color: #28a745;
            border-style: solid;
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(40, 167, 69, 0.4);
        }

        .drop-zone.drag-over::before {
            content: '✨ DROP IT! ✨';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,255,255,0.9);
            color: #28a745;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: 1px;
            animation: dropPulse 0.5s ease-in-out infinite alternate;
        }

        @keyframes dropPulse {
            0% { transform: translate(-50%, -50%) scale(1); }
            100% { transform: translate(-50%, -50%) scale(1.1); }
        }

        .drop-zone.correct {
            background: var(--success-gradient);
            border-color: #28a745;
            border-style: solid;
            animation: correctDrop 1s ease;
        }

        @keyframes correctDrop {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); box-shadow: 0 0 50px rgba(40, 167, 69, 0.8); }
            100% { transform: scale(1); }
        }

        .drop-zone.incorrect {
            background: var(--danger-gradient);
            border-color: #dc3545;
            border-style: solid;
            animation: incorrectDrop 0.5s ease;
        }

        @keyframes incorrectDrop {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .drop-zone-placeholder {
            color: #999;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            opacity: 0.7;
        }

        .dropped-item {
            background: var(--success-gradient);
            border: 3px solid #28a745;
            border-radius: 15px;
            padding: 20px;
            color: white;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            animation: itemDrop 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        @keyframes itemDrop {
            0% { transform: scale(0) rotate(180deg); opacity: 0; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        .controls-section {
            text-align: center;
            margin: 40px 0;
            position: relative;
            z-index: 2;
        }

        .btn-epic {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 18px 40px;
            border-radius: 30px;
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
            margin: 0 10px;
        }

        .btn-epic:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-epic::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: all 0.6s ease;
            transform: translate(-50%, -50%);
        }

        .btn-epic:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-reset {
            background: var(--danger-gradient);
            box-shadow: 0 8px 25px rgba(238, 9, 121, 0.3);
        }

        .btn-reset:hover {
            box-shadow: 0 15px 40px rgba(238, 9, 121, 0.5);
        }

        .btn-hint {
            background: var(--warning-gradient);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        .btn-hint:hover {
            box-shadow: 0 15px 40px rgba(240, 147, 251, 0.5);
        }

        .feedback-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10000;
            pointer-events: none;
        }

        .feedback-message {
            background: var(--success-gradient);
            color: white;
            padding: 20px 40px;
            border-radius: 25px;
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: feedbackPop 2s ease;
            position: relative;
            overflow: hidden;
        }

        .feedback-message.error {
            background: var(--danger-gradient);
        }

        .feedback-message::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: feedbackShine 2s ease;
        }

        @keyframes feedbackPop {
            0% { transform: scale(0) rotate(180deg); opacity: 0; }
            20% { transform: scale(1.2) rotate(0deg); opacity: 1; }
            80% { transform: scale(1) rotate(0deg); opacity: 1; }
            100% { transform: scale(0) rotate(-180deg); opacity: 0; }
        }

        @keyframes feedbackShine {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .progress-bar-container {
            background: rgba(0,0,0,0.1);
            height: 12px;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
            position: relative;
            z-index: 2;
        }

        .progress-bar {
            height: 100%;
            background: var(--success-gradient);
            border-radius: 10px;
            transition: width 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: progressShine 2s ease-in-out infinite;
        }

        @keyframes progressShine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .completion-celebration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 9999;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ffd700;
            animation: confettiFall 3s linear infinite;
        }

        @keyframes confettiFall {
            0% { 
                transform: translateY(-100vh) rotate(0deg); 
                opacity: 1; 
            }
            100% { 
                transform: translateY(100vh) rotate(720deg); 
                opacity: 0; 
            }
        }

        .json-debug {
            background: rgba(0,0,0,0.8);
            color: #00ff00;
            font-family: 'Courier New', monospace;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #333;
            position: relative;
            z-index: 2;
        }

        .mobile-optimized {
            display: none;
        }

        @media (max-width: 768px) {
            .drag-drop-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .level-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .stat-item {
                padding: 10px;
                min-width: 60px;
            }
            
            .challenge-title {
                font-size: 2rem;
            }
            
            .mobile-optimized {
                display: block;
            }
            
            .desktop-only {
                display: none;
            }
        }

        .hint-tooltip {
            position: absolute;
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.9rem;
            z-index: 1000;
            max-width: 250px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            animation: tooltipAppear 0.3s ease;
        }

        @keyframes tooltipAppear {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <div class="game-arena">
        <!-- Challenge Header -->
        <h1 class="challenge-title">🎯 Epic Drag & Drop Challenge</h1>
        <p class="challenge-description">
           Show what you know about Python! Drag each item to the right category—are you a true Python beginner wizard?

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
        </div>

        <!-- Main Game Area -->
        <div class="drag-drop-container">
            <!-- Draggables Section -->
            <div class="draggables-section">
                <h3 class="section-title">🐍 Python Clues</h3>
                <div id="draggablesContainer">
                    <!-- Dynamic draggable items will be generated here -->
                </div>
            </div>

            <!-- Drop Zones Section -->
            <div class="drop-zones-section">
                <h3 class="section-title">🎯 Match Here</h3>
                <div id="dropZonesContainer">
                    <!-- Dynamic drop zones will be generated here -->
                </div>
            </div>
        </div>

        <!-- Game Controls -->
        <div class="controls-section">
            <button class="btn-epic" onclick="checkAnswers()">
                <i class="fas fa-magic me-2"></i>
                Cast Spell (Check Answers)
            </button>
            <button class="btn-epic btn-hint" onclick="showHint()">
                <i class="fas fa-lightbulb me-2"></i>
                Oracle's Wisdom
            </button>
            <button class="btn-epic btn-reset" onclick="resetGame()">
                <i class="fas fa-redo me-2"></i>
                Reset Realm
            </button>
        </div>
        <!-- Debug JSON (for development) -
        <div class="json-debug">
            <h4>🔧 Debug Console (Level Content):</h4>
            <pre id="jsonContent">{{ json_encode($level->content, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
        </div>-->

        <!-- Score Submission Form -->
        <form method="POST" action="{{ route('levels.submit',$level) }}" id="scoreForm" style="display: none;">
            @csrf
            <input type="hidden" name="score" id="finalScore" value="0">
        </form>
    </div>

    <!-- Feedback Container -->
    <div class="feedback-container" id="feedbackContainer"></div>

    <!-- Completion Celebration -->
    <div class="completion-celebration" id="celebrationContainer"></div>

    <!-- Epic JavaScript Game Engine -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        class EpicDragDropGame {
            constructor() {
                this.score = 0;
                this.maxScore = 100;
                this.timeLimit = 300; // 5 minutes
                this.timeRemaining = this.timeLimit;
                this.hintsUsed = 0;
                this.maxHints = 3;
                this.gameStarted = false;
                this.gameCompleted = false;
                this.correctAnswers = 0;
                this.totalQuestions = 0;
                this.timer = null;

                // Game data - can be loaded from level content JSON
                this.gameData = {
              categories: {
        "💻 Programming": [
            "🐍 Python",            // CORRECT
            "☕ Java",              // CORRECT
            "📋 Excel Macros"       // CORRECT (bonus, for scripting)
        ],
        "👩‍🏫 Who Can Use Python?": [
            "👨‍🎓 Students",        // CORRECT
            "👩‍🏫 Teachers",        // CORRECT
            "🎨 Artists",           // CORRECT
            "🧑‍💻 Programmers"       // CORRECT
        ],
        "📊 What Can Python Do?": [
            "🧮 Calculations",       // CORRECT
            "📊 Data Analysis",      // CORRECT
            "🌐 Make Websites",      // CORRECT
            "🤖 AI & Automation"     // CORRECT
        ],
        "🚫 Not Related to Python": [
            "🥤 Drinking Soda",      // CORRECT
            "🚗 Driving a Car",      // CORRECT
            "🧑‍🍳 Cooking Pasta"      // CORRECT
        ]
    }
};

                this.init();
            }

            init() {
                this.generateGameElements();
                this.setupEventListeners();
                this.startTimer();
                this.updateUI();
                
                // Play epic start sound effect (visual)
                this.showFeedback("🎮 Epic Quest Begins! 🎮", "success", 2000);
            }

            generateGameElements() {
                const draggablesContainer = document.getElementById('draggablesContainer');
                const dropZonesContainer = document.getElementById('dropZonesContainer');
                
                // Clear containers
                draggablesContainer.innerHTML = '';
                dropZonesContainer.innerHTML = '';

                // Create draggable items
                const allItems = [];
                Object.values(this.gameData.categories).forEach(items => {
                    allItems.push(...items);
                });

                // Shuffle items for better gameplay
                const shuffledItems = this.shuffleArray(allItems);
                this.totalQuestions = shuffledItems.length;

                shuffledItems.forEach((item, index) => {
                    const draggableElement = this.createDraggableElement(item, index);
                    draggablesContainer.appendChild(draggableElement);
                });

                // Create drop zones
                Object.keys(this.gameData.categories).forEach((category, index) => {
                    const dropZoneElement = this.createDropZoneElement(category, index);
                    dropZonesContainer.appendChild(dropZoneElement);
                });
            }

            createDraggableElement(item, index) {
                const div = document.createElement('div');
                div.className = 'draggable-item';
                div.draggable = true;
                div.dataset.item = item;
                div.dataset.index = index;
                div.innerHTML = `
                    <span class="item-icon">${item.split(' ')[0]}</span>
                    <span class="item-text">${item.split(' ').slice(1).join(' ')}</span>
                `;
                return div;
            }

            createDropZoneElement(category, index) {
                const div = document.createElement('div');
                div.className = 'drop-zone';
                div.dataset.category = category;
                div.dataset.index = index;
                div.innerHTML = `
                    <div class="drop-zone-placeholder">
                        <strong>${category}</strong><br>
                        <small>Drop mystical elements here</small>
                    </div>
                `;
                return div;
            }

            setupEventListeners() {
                // Drag and drop event listeners
                document.addEventListener('dragstart', this.handleDragStart.bind(this));
                document.addEventListener('dragover', this.handleDragOver.bind(this));
                document.addEventListener('dragenter', this.handleDragEnter.bind(this));
                document.addEventListener('dragleave', this.handleDragLeave.bind(this));
                document.addEventListener('drop', this.handleDrop.bind(this));
                document.addEventListener('dragend', this.handleDragEnd.bind(this));

                // Touch events for mobile
                this.setupTouchEvents();

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'h' || e.key === 'H') {
                        this.showHint();
                    } else if (e.key === 'r' || e.key === 'R') {
                        this.resetGame();
                    } else if (e.key === 'Enter') {
                        this.checkAnswers();
                    }
                });
            }

            handleDragStart(e) {
                if (!e.target.classList.contains('draggable-item')) return;
                
                e.target.classList.add('dragging');
                e.dataTransfer.setData('text/plain', e.target.dataset.item);
                e.dataTransfer.setData('application/json', JSON.stringify({
                    item: e.target.dataset.item,
                    index: e.target.dataset.index
                }));
                
                // Visual feedback
                this.createDragGhost(e.target);
                
                if (!this.gameStarted) {
                    this.gameStarted = true;
                    this.showFeedback("🚀 Adventure Started! 🚀", "success", 1500);
                }
            }

            handleDragOver(e) {
                e.preventDefault();
                return false;
            }

            handleDragEnter(e) {
                if (e.target.classList.contains('drop-zone') || e.target.closest('.drop-zone')) {
                    const dropZone = e.target.classList.contains('drop-zone') ? e.target : e.target.closest('.drop-zone');
                    dropZone.classList.add('drag-over');
                    
                    // Epic hover effect
                    this.createMagicSparkles(dropZone);
                }
            }

            handleDragLeave(e) {
                if (e.target.classList.contains('drop-zone') || e.target.closest('.drop-zone')) {
                    const dropZone = e.target.classList.contains('drop-zone') ? e.target : e.target.closest('.drop-zone');
                    dropZone.classList.remove('drag-over');
                }
            }

            handleDrop(e) {
                e.preventDefault();
                
                const dropZone = e.target.classList.contains('drop-zone') ? e.target : e.target.closest('.drop-zone');
                if (!dropZone) return;
                
                const draggedItem = e.dataTransfer.getData('text/plain');
                const draggedData = JSON.parse(e.dataTransfer.getData('application/json'));
                
                dropZone.classList.remove('drag-over');
                
                // Remove placeholder
                const placeholder = dropZone.querySelector('.drop-zone-placeholder');
                if (placeholder) placeholder.remove();
                
                // Create dropped item element
                const droppedElement = document.createElement('div');
                droppedElement.className = 'dropped-item';
                droppedElement.dataset.item = draggedItem;
                droppedElement.innerHTML = `
                    <span class="item-icon">${draggedItem.split(' ')[0]}</span>
                    <span class="item-text">${draggedItem.split(' ').slice(1).join(' ')}</span>
                    <button class="btn btn-sm btn-light ms-2" onclick="game.removeDroppedItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                dropZone.appendChild(droppedElement);
                
                // Remove original draggable
                const originalDraggable = document.querySelector(`[data-item="${draggedItem}"]`);
                if (originalDraggable && originalDraggable.classList.contains('draggable-item')) {
                    originalDraggable.remove();
                }
                
                // Epic drop effect
                this.playDropEffect(dropZone);
                this.updateProgress();
                
                // Check if game is complete
                if (document.querySelectorAll('.draggable-item').length === 0) {
                    setTimeout(() => this.checkAnswers(), 1000);
                }
            }

            handleDragEnd(e) {
                e.target.classList.remove('dragging');
                this.removeDragGhost();
            }

            setupTouchEvents() {
                let touchItem = null;
                let touchOffset = { x: 0, y: 0 };
                
                document.addEventListener('touchstart', (e) => {
                    const target = e.target.closest('.draggable-item');
                    if (target) {
                        touchItem = target;
                        const touch = e.touches[0];
                        const rect = target.getBoundingClientRect();
                        touchOffset.x = touch.clientX - rect.left;
                        touchOffset.y = touch.clientY - rect.top;
                        
                        target.classList.add('dragging');
                        e.preventDefault();
                    }
                });
                
                document.addEventListener('touchmove', (e) => {
                    if (touchItem) {
                        const touch = e.touches[0];
                        touchItem.style.position = 'fixed';
                        touchItem.style.left = (touch.clientX - touchOffset.x) + 'px';
                        touchItem.style.top = (touch.clientY - touchOffset.y) + 'px';
                        touchItem.style.zIndex = '1000';
                        touchItem.style.transform = 'rotate(5deg) scale(1.1)';
                        e.preventDefault();
                    }
                });
                
                document.addEventListener('touchend', (e) => {
                    if (touchItem) {
                        const touch = e.changedTouches[0];
                        const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
                        const dropZone = elementBelow?.closest('.drop-zone');
                        
                        if (dropZone) {
                            // Simulate drop
                            const fakeEvent = {
                                preventDefault: () => {},
                                target: dropZone,
                                dataTransfer: {
                                    getData: (type) => {
                                        if (type === 'text/plain') return touchItem.dataset.item;
                                        if (type === 'application/json') return JSON.stringify({
                                            item: touchItem.dataset.item,
                                            index: touchItem.dataset.index
                                        });
                                    }
                                }
                            };
                            this.handleDrop(fakeEvent);
                        }
                        
                        touchItem.style.position = '';
                        touchItem.style.left = '';
                        touchItem.style.top = '';
                        touchItem.style.zIndex = '';
                        touchItem.style.transform = '';
                        touchItem.classList.remove('dragging');
                        touchItem = null;
                    }
                });
            }

            createDragGhost(element) {
                const ghost = element.cloneNode(true);
                ghost.id = 'drag-ghost';
                ghost.style.cssText = `
                    position: fixed;
                    top: -1000px;
                    left: -1000px;
                    pointer-events: none;
                    opacity: 0.8;
                    transform: scale(1.1) rotate(5deg);
                    z-index: 10000;
                `;
                document.body.appendChild(ghost);
            }

            removeDragGhost() {
                const ghost = document.getElementById('drag-ghost');
                if (ghost) ghost.remove();
            }

            createMagicSparkles(element) {
                for (let i = 0; i < 5; i++) {
                    setTimeout(() => {
                        const sparkle = document.createElement('div');
                        sparkle.innerHTML = '✨';
                        sparkle.style.cssText = `
                            position: absolute;
                            top: ${Math.random() * 100}%;
                            left: ${Math.random() * 100}%;
                            font-size: 1.5rem;
                            pointer-events: none;
                            animation: sparkle 1s ease-out forwards;
                            z-index: 1000;
                        `;
                        element.style.position = 'relative';
                        element.appendChild(sparkle);
                        
                        setTimeout(() => sparkle.remove(), 1000);
                    }, i * 100);
                }
            }

            playDropEffect(dropZone) {
                // Epic drop animation
                dropZone.style.animation = 'correctDrop 0.8s ease';
                
                // Create explosion effect
                for (let i = 0; i < 10; i++) {
                    setTimeout(() => {
                        const particle = document.createElement('div');
                        particle.innerHTML = ['⭐', '✨', '💫', '🌟'][Math.floor(Math.random() * 4)];
                        particle.style.cssText = `
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            font-size: 1.2rem;
                            pointer-events: none;
                            animation: explode 1s ease-out forwards;
                            z-index: 1000;
                            --random-x: ${(Math.random() - 0.5) * 200}px;
                            --random-y: ${(Math.random() - 0.5) * 200}px;
                        `;
                        dropZone.style.position = 'relative';
                        dropZone.appendChild(particle);
                        
                        setTimeout(() => particle.remove(), 1000);
                    }, i * 50);
                }
                
                setTimeout(() => {
                    dropZone.style.animation = '';
                }, 800);
            }

            removeDroppedItem(button) {
                const droppedItem = button.closest('.dropped-item');
                const dropZone = droppedItem.closest('.drop-zone');
                const itemData = droppedItem.dataset.item;
                
                // Remove from drop zone
                droppedItem.remove();
                
                // Add back to draggables
                const draggablesContainer = document.getElementById('draggablesContainer');
                const newDraggable = this.createDraggableElement(itemData, Date.now());
                draggablesContainer.appendChild(newDraggable);
                
                // Restore placeholder if drop zone is empty
                if (dropZone.children.length === 0) {
                    const category = dropZone.dataset.category;
                    dropZone.innerHTML = `
                        <div class="drop-zone-placeholder">
                            <strong>${category}</strong><br>
                            <small>Drop mystical elements here</small>
                        </div>
                    `;
                }
                
                this.updateProgress();
                this.showFeedback("🔄 Element Returned to Realm! 🔄", "info", 1000);
            }

           checkAnswers() {
    if (this.gameCompleted) return;
    
    let correct = 0;
    let total = 0;
    
    document.querySelectorAll('.drop-zone').forEach(dropZone => {
        const category = dropZone.dataset.category;
        const droppedItems = dropZone.querySelectorAll('.dropped-item');
        const correctItems = this.gameData.categories[category] || [];
        
        droppedItems.forEach(item => {
            total++;
            const itemText = item.dataset.item;
            
            if (correctItems.includes(itemText)) {
                correct++;
                dropZone.classList.add('correct');
                item.style.background = 'var(--success-gradient)';
            } else {
                dropZone.classList.add('incorrect');
                item.style.background = 'var(--danger-gradient)';
            }
        });
    });
    
    // Calculate score
    this.correctAnswers = correct;
    const percentage = total > 0 ? Math.round((correct / total) * 100) : 0;
    this.score = Math.max(0, percentage - (this.hintsUsed * 5)); // Penalty for hints
    
    // Time bonus
    const timeBonus = Math.max(0, Math.floor(this.timeRemaining / 10));
    this.score = Math.min(100, this.score + timeBonus);
    
    this.updateUI();
    
    // FIXED: Always complete the game and submit score, regardless of percentage
    if (percentage >= 80) {
        this.showFeedback("🏆 LEGENDARY VICTORY! 🏆", "success", 3000);
        this.startCelebration();
          this.completeGame(true); 
    } else {
        this.showFeedback(`🎯 Score: ${this.score}% - ${correct}/${total} correct!`, 
                        percentage >= 60 ? "warning" : "error", 3000);
                        this.completeGame(false); 
    }
    
    // Always submit the score after 3 seconds
    this.gameCompleted = true;
    clearInterval(this.timer);
    setTimeout(() => {
        document.getElementById('finalScore').value = this.score;
        document.getElementById('scoreForm').submit();
    }, 3000);
}

         completeGame(success = true) {
    this.gameCompleted = true;
    clearInterval(this.timer);
    
    if (success) {
        this.showFeedback("🏆 LEGENDARY VICTORY! 🏆", "success", 4000);
        this.startCelebration();
    } else {
        this.showFeedback("⚡ Quest Complete! ⚡", "warning", 3000);
    }
    
    // FIXED: Always submit score, regardless of success
    setTimeout(() => {
        document.getElementById('finalScore').value = this.score;
        document.getElementById('scoreForm').submit();
    }, 3000);
}

            startCelebration() {
                const celebrationContainer = document.getElementById('celebrationContainer');
                
                // Create confetti
                for (let i = 0; i < 100; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.className = 'confetti';
                        confetti.style.left = Math.random() * 100 + 'vw';
                        confetti.style.background = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24'][Math.floor(Math.random() * 5)];
                        confetti.style.animationDelay = Math.random() * 3 + 's';
                        celebrationContainer.appendChild(confetti);
                        
                        setTimeout(() => confetti.remove(), 3000);
                    }, i * 30);
                }
            }

            showHint() {
                if (this.hintsUsed >= this.maxHints) {
                    this.showFeedback("🔮 No more wisdom from the Oracle! 🔮", "error", 2000);
                    return;
                }
                
                this.hintsUsed++;
                
                const hints = [
                      "🐍 Python is a programming language for many uses.",
    "💡 Python is great for students and beginners!",
    "📊 Data, AI, and websites—all possible with Python.",
    "🚫 Watch out! Not everything is related to Python."
                ];
                
                const randomHint = hints[Math.floor(Math.random() * hints.length)];
                this.showFeedback(`💡 Oracle's Wisdom: ${randomHint}`, "info", 3000);
                
                // Highlight related elements
                this.highlightHintElements();
            }

            highlightHintElements() {
                const draggables = document.querySelectorAll('.draggable-item');
                draggables.forEach(item => {
                    item.style.animation = 'pulse 1s ease-in-out 3';
                    setTimeout(() => {
                        item.style.animation = '';
                    }, 3000);
                });
            }

            resetGame() {
                if (confirm('🔄 Are you sure you want to restart your mystical journey?')) {
                    location.reload();
                }
            }

            updateProgress() {
                const totalItems = this.totalQuestions;
                const droppedItems = document.querySelectorAll('.dropped-item').length;
                const progress = totalItems > 0 ? (droppedItems / totalItems) * 100 : 0;
                
                document.getElementById('progressBar').style.width = progress + '%';
            }

            updateUI() {
                document.getElementById('currentScore').textContent = this.score;
                document.getElementById('starsEarned').textContent = this.getStars();
                
                const minutes = Math.floor(this.timeRemaining / 60);
                const seconds = this.timeRemaining % 60;
                document.getElementById('timeRemaining').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            getStars() {
                if (this.score >= 90) return '⭐⭐⭐';
                if (this.score >= 70) return '⭐⭐';
                if (this.score >= 50) return '⭐';
                return '0';
            }

            startTimer() {
                this.timer = setInterval(() => {
                    this.timeRemaining--;
                    this.updateUI();
                    
                    if (this.timeRemaining <= 0) {
                        clearInterval(this.timer);
                        this.showFeedback("⏰ Time's Up! Submitting your quest... ⏰", "error", 3000);
                        setTimeout(() => this.checkAnswers(), 1000);
                    } else if (this.timeRemaining <= 60 && this.timeRemaining % 15 === 0) {
                        this.showFeedback(`⚠️ ${this.timeRemaining} seconds remaining! ⚠️`, "warning", 1500);
                    }
                }, 1000);
            }

            showFeedback(message, type = "success", duration = 2000) {
                const feedbackContainer = document.getElementById('feedbackContainer');
                const feedback = document.createElement('div');
                feedback.className = `feedback-message ${type}`;
                feedback.textContent = message;
                
                feedbackContainer.appendChild(feedback);
                
                setTimeout(() => {
                    feedback.remove();
                }, duration);
            }

            shuffleArray(array) {
                const shuffled = [...array];
                for (let i = shuffled.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
                }
                return shuffled;
            }
        }

        // Global functions for button clicks
        function checkAnswers() {
            game.checkAnswers();
        }

        function showHint() {
            game.showHint();
        }

        function resetGame() {
            game.resetGame();
        }

        // Initialize game when page loads
        let game;
        document.addEventListener('DOMContentLoaded', () => {
            game = new EpicDragDropGame();
        });

        // Add dynamic CSS animations
        const dynamicStyles = document.createElement('style');
        dynamicStyles.textContent = `
            @keyframes sparkle {
                0% { opacity: 1; transform: scale(0) rotate(0deg); }
                50% { opacity: 1; transform: scale(1) rotate(180deg); }
                100% { opacity: 0; transform: scale(0) rotate(360deg); }
            }
            
            @keyframes explode {
                0% { 
                    opacity: 1; 
                    transform: translate(-50%, -50%) scale(0) rotate(0deg); 
                }
                100% { 
                    opacity: 0; 
                    transform: translate(calc(-50% + var(--random-x)), calc(-50% + var(--random-y))) scale(1.5) rotate(360deg); 
                }
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1); box-shadow: 0 8px 25px rgba(0,123,255,0.15); }
                50% { transform: scale(1.05); box-shadow: 0 15px 40px rgba(0,123,255,0.4); }
            }
        `;
        document.head.appendChild(dynamicStyles);
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>