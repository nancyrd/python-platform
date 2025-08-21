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
            --deep-purple: #1a0636;
            --cosmic-purple: #4a1b6d;
            --space-blue: #162b6f;
            --dark-space: #0a1028;
            --neon-blue: #00b3ff;
            --neon-purple: #b967ff;
            --bright-pink: #ff2a6d;
            --electric-blue: #05d9e8;
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #ffed4a 100%);
        }

        body {
            background: linear-gradient(45deg, var(--deep-purple) 0%, var(--cosmic-purple) 30%, var(--space-blue) 70%, var(--dark-space) 100%);
            min-height: 100vh;
            font-family: 'Orbitron', 'Arial', sans-serif;
            color: white;
        }

        .epic-level-header {
            background: rgba(10, 6, 30, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 3px solid var(--neon-purple);
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
            background: linear-gradient(90deg, transparent, rgba(185, 103, 255, 0.1), transparent);
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
            box-shadow: 0 0 30px rgba(185, 103, 255, 0.6);
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
            50% { transform: scale(1.05); box-shadow: 0 0 40px rgba(185, 103, 255, 0.8); }
        }

        .level-title {
            color: var(--neon-purple);
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
            color: var(--neon-purple);
        }

        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .game-arena {
            background: rgba(26, 6, 54, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            margin: 30px auto;
            padding: 40px;
            max-width: 1200px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: 2px solid rgba(185, 103, 255, 0.3);
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
            background: radial-gradient(circle, rgba(185, 103, 255, 0.05) 0%, transparent 70%);
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
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .challenge-description {
            text-align: center;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }

        .questions-container {
            position: relative;
            z-index: 2;
            margin: 40px 0;
        }

        .question-card {
            background: linear-gradient(135deg, rgba(40, 10, 70, 0.7) 0%, rgba(60, 20, 90, 0.7) 100%);
            border-radius: 25px;
            padding: 30px;
            margin: 25px 0;
            border: 3px solid var(--neon-purple);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(185, 103, 255, 0.2);
        }

        .question-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 179, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .question-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(185, 103, 255, 0.3);
            border-color: var(--electric-blue);
        }

        .question-card:hover::before {
            left: 100%;
        }

        .question-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .question-number {
            width: 50px;
            height: 50px;
            background: var(--gold-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 900;
            color: #333;
            margin-right: 20px;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .question-text {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.6;
            flex-grow: 1;
        }

        .code-display {
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid var(--neon-blue);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            color: #00ff00;
            font-size: 1.1rem;
            line-height: 1.5;
            position: relative;
            overflow: hidden;
        }

        .code-display::before {
            content: '💻 Python Code';
            position: absolute;
            top: 10px;
            right: 15px;
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
        }

        .answer-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 25px;
        }

        .answer-btn {
            flex: 1;
            max-width: 200px;
            padding: 20px 30px;
            border: none;
            border-radius: 20px;
            font-size: 1.3rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .answer-btn.true-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .answer-btn.false-btn {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }

        .answer-btn:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .answer-btn.selected {
            transform: scale(1.1);
            box-shadow: 0 0 30px rgba(255,255,255,0.5);
            border: 3px solid white;
        }

        .answer-btn.correct {
            background: linear-gradient(135deg, #28a745, #20c997);
            animation: correctAnswer 1s ease;
        }

        .answer-btn.incorrect {
            background: linear-gradient(135deg, #dc3545, #6f42c1);
            animation: incorrectAnswer 0.5s ease;
        }

        @keyframes correctAnswer {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); box-shadow: 0 0 50px rgba(40, 167, 69, 0.8); }
            100% { transform: scale(1.1); }
        }

        @keyframes incorrectAnswer {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .answer-btn::before {
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

        .answer-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .controls-section {
            text-align: center;
            margin: 40px 0;
            position: relative;
            z-index: 2;
        }

        .btn-epic {
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
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
            box-shadow: 0 15px 40px rgba(185, 103, 255, 0.5);
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
            background: linear-gradient(45deg, var(--bright-pink), #ff6a00);
            box-shadow: 0 8px 25px rgba(238, 9, 121, 0.3);
        }

        .btn-reset:hover {
            box-shadow: 0 15px 40px rgba(238, 9, 121, 0.5);
        }

        .btn-hint {
            background: linear-gradient(45deg, #f093fb, #f5576c);
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
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
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
            background: linear-gradient(45deg, var(--bright-pink), #ff6a00);
        }

        .feedback-message.warning {
            background: linear-gradient(45deg, #f093fb, #f5576c);
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
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
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
            background: var(--neon-purple);
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

        .question-result {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 2rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .question-card.answered .question-result {
            opacity: 1;
        }

        .mobile-optimized {
            display: none;
        }

        @media (max-width: 768px) {
            .answer-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .answer-btn {
                max-width: none;
                padding: 15px 25px;
                font-size: 1.1rem;
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

            .question-text {
                font-size: 1.1rem;
            }

            .code-display {
                font-size: 1rem;
                padding: 15px;
            }
        }
    </style>

    <div class="game-arena">
        <!-- Challenge Header -->
        <h1 class="challenge-title">🎯 Cosmic True/False Challenge</h1>
        <p class="challenge-description">
            Test your Python knowledge! Read each statement carefully and choose TRUE or FALSE. Are you ready to become a cosmic coding master?
        </p>

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
        </div>

        <!-- Questions Container -->
        <div class="questions-container" id="questionsContainer">
            <!-- Dynamic questions will be generated here -->
        </div>

        <!-- Game Controls -->
        <div class="controls-section">
            <button class="btn-epic" onclick="checkAnswers()" id="checkBtn">
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
        class EpicTrueFalseGame {
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
                this.userAnswers = {};

                // Game data - True/False questions about Python
                this.gameData = {
                    questions: [
                        {
                            id: 1,
                            text: "In Python, you must use print() to show text on the screen.",
                            code: null,
                            correct: true
                        },
                        {
                            id: 2,
                            text: "You can write text in Python without using quotes.",
                            code: null,
                            correct: false
                        },
                        {
                            id: 3,
                            text: "The code print('Hello') will show Hello on the screen.",
                            code: "print('Hello')",
                            correct: true
                        },
                        {
                            id: 4,
                            text: "Python uses $ to start variable names.",
                            code: null,
                            correct: false
                        },
                        {
                            id: 5,
                            text: "The symbol + is used to add two numbers in Python.",
                            code: "result = 5 + 3",
                            correct: true
                        },
                        {
                            id: 6,
                            text: "The code print(2 + 3) will show 23.",
                            code: "print(2 + 3)",
                            correct: false
                        },
                        {
                            id: 7,
                            text: "The code print('2' + '3') will show 23.",
                            code: "print('2' + '3')",
                            correct: true
                        },
                        {
                            id: 8,
                            text: "Variables in Python can store numbers or text.",
                            code: "x = 5\ny = 'Hello'",
                            correct: true
                        },
                        {
                            id: 9,
                            text: "Python is case-sensitive, so Name and name are different variables.",
                            code: "Name = 'John'\nname = 'Jane'",
                            correct: true
                        },
                        {
                            id: 10,
                            text: "The code x = 5 stores the value 5 in the variable x.",
                            code: "x = 5",
                            correct: true
                        }
                    ]
                };

                this.totalQuestions = this.gameData.questions.length;
                this.init();
            }

            init() {
                this.generateQuestions();
                this.setupEventListeners();
                this.startTimer();
                this.updateUI();
                
                // Play epic start sound effect (visual)
                this.showFeedback("🎮 Epic Quest Begins! 🎮", "success", 2000);
            }

            generateQuestions() {
                const questionsContainer = document.getElementById('questionsContainer');
                questionsContainer.innerHTML = '';

                this.gameData.questions.forEach((question, index) => {
                    const questionElement = this.createQuestionElement(question, index);
                    questionsContainer.appendChild(questionElement);
                });
            }

            createQuestionElement(question, index) {
                const div = document.createElement('div');
                div.className = 'question-card';
                div.dataset.questionId = question.id;
                
                const codeSection = question.code ? `
                    <div class="code-display">
                        <pre>${question.code}</pre>
                    </div>
                ` : '';

                div.innerHTML = `
                    <div class="question-result" id="result-${question.id}"></div>
                    <div class="question-header">
                        <div class="question-number">${index + 1}</div>
                        <div class="question-text">${question.text}</div>
                    </div>
                    ${codeSection}
                    <div class="answer-buttons">
                        <button class="answer-btn true-btn" onclick="selectAnswer(${question.id}, true)" data-answer="true">
                            <i class="fas fa-check me-2"></i>
                            TRUE
                        </button>
                        <button class="answer-btn false-btn" onclick="selectAnswer(${question.id}, false)" data-answer="false">
                            <i class="fas fa-times me-2"></i>
                            FALSE
                        </button>
                    </div>
                `;

                return div;
            }

            selectAnswer(questionId, answer) {
                if (this.gameCompleted) return;

                // Remove previous selections for this question
                const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
                const buttons = questionCard.querySelectorAll('.answer-btn');
                buttons.forEach(btn => btn.classList.remove('selected'));

                // Select current answer
                const selectedButton = questionCard.querySelector(`[data-answer="${answer}"]`);
                selectedButton.classList.add('selected');

                // Store user's answer
                this.userAnswers[questionId] = answer;

                // Start game if not started
                if (!this.gameStarted) {
                    this.gameStarted = true;
                    this.showFeedback("🚀 Adventure Started! 🚀", "success", 1500);
                }

                // Create sparkle effect
                this.createSparkles(selectedButton);
                
                // Update progress
                this.updateProgress();

                // Auto-check if all questions answered
                if (Object.keys(this.userAnswers).length === this.totalQuestions) {
                    setTimeout(() => {
                        document.getElementById('checkBtn').style.animation = 'pulse 1s ease-in-out 3';
                        this.showFeedback("🎯 Ready to check your answers! 🎯", "info", 2000);
                    }, 500);
                }
            }

            createSparkles(element) {
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

            setupEventListeners() {
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

            checkAnswers() {
                if (this.gameCompleted) return;
                
                // Check if all questions are answered
                if (Object.keys(this.userAnswers).length !== this.totalQuestions) {
                    this.showFeedback("⚠️ Please answer all questions first! ⚠️", "warning", 2000);
                    return;
                }

                let correct = 0;
                
                // Check each answer and provide visual feedback
                this.gameData.questions.forEach(question => {
                    const userAnswer = this.userAnswers[question.id];
                    const isCorrect = userAnswer === question.correct;
                    const questionCard = document.querySelector(`[data-question-id="${question.id}"]`);
                    const buttons = questionCard.querySelectorAll('.answer-btn');
                    const resultIcon = document.getElementById(`result-${question.id}`);
                    
                    if (isCorrect) {
                        correct++;
                        questionCard.classList.add('answered');
                        resultIcon.innerHTML = '✅';
                        resultIcon.style.opacity = '1';
                        
                        // Highlight correct button
                        const correctButton = questionCard.querySelector(`[data-answer="${question.correct}"]`);
                        correctButton.classList.add('correct');
                    } else {
                        questionCard.classList.add('answered');
                        resultIcon.innerHTML = '❌';
                        resultIcon.style.opacity = '1';
                        
                        // Show incorrect selection
                        const selectedButton = questionCard.querySelector(`[data-answer="${userAnswer}"]`);
                        selectedButton.classList.add('incorrect');
                        
                        // Show correct answer
                        const correctButton = questionCard.querySelector(`[data-answer="${question.correct}"]`);
                        correctButton.classList.add('correct');
                        correctButton.style.border = '3px solid #ffd700';
                    }
                });

                // Calculate score
                this.correctAnswers = correct;
                const percentage = Math.round((correct / this.totalQuestions) * 100);
                this.score = Math.max(0, percentage - (this.hintsUsed * 5)); // Penalty for hints
                
                // Time bonus
                const timeBonus = Math.max(0, Math.floor(this.timeRemaining / 10));
                this.score = Math.min(100, this.score + timeBonus);
                
                this.updateUI();
                
                // Complete the game and submit score
                if (percentage >= 80) {
                    this.showFeedback("🏆 LEGENDARY VICTORY! 🏆", "success", 3000);
                    this.startCelebration();
                } else {
                    this.showFeedback(`🎯 Score: ${this.score}% - ${correct}/${this.totalQuestions} correct!`, 
                                    percentage >= 60 ? "warning" : "error", 3000);
                }
                
                this.completeGame();
            }

            completeGame() {
                this.gameCompleted = true;
                clearInterval(this.timer);
                
                // Always submit score after 3 seconds
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
                    "🐍 Remember: Python uses quotes for text and no quotes for numbers!",
                    "💡 Think about what Python actually does vs what you might expect!",
                    "📚 Variable names in Python are case-sensitive - Name ≠ name!",
                    "🔍 Look carefully at quotes vs no quotes in the code examples!",
                    "⚡ Addition with + works differently for numbers and text!"
                ];
                
                const randomHint = hints[Math.floor(Math.random() * hints.length)];
                this.showFeedback(`💡 Oracle's Wisdom: ${randomHint}`, "info", 4000);
                
                // Highlight unanswered questions
                this.highlightUnansweredQuestions();
            }

            highlightUnansweredQuestions() {
                this.gameData.questions.forEach(question => {
                    if (!this.userAnswers[question.id]) {
                        const questionCard = document.querySelector(`[data-question-id="${question.id}"]`);
                        questionCard.style.animation = 'pulse 1s ease-in-out 3';
                        setTimeout(() => {
                            questionCard.style.animation = '';
                        }, 3000);
                    }
                });
            }

            resetGame() {
                if (confirm('🔄 Are you sure you want to restart your mystical journey?')) {
                    location.reload();
                }
            }

            updateProgress() {
                const answeredQuestions = Object.keys(this.userAnswers).length;
                const progress = (answeredQuestions / this.totalQuestions) * 100;
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
        }

        // Global functions for button clicks
        function selectAnswer(questionId, answer) {
            game.selectAnswer(questionId, answer);
        }

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
            game = new EpicTrueFalseGame();
        });

        // Add dynamic CSS animations
        const dynamicStyles = document.createElement('style');
        dynamicStyles.textContent = `
            @keyframes sparkle {
                0% { opacity: 1; transform: scale(0) rotate(0deg); }
                50% { opacity: 1; transform: scale(1) rotate(180deg); }
                100% { opacity: 0; transform: scale(0) rotate(360deg); }
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1); box-shadow: 0 10px 30px rgba(185, 103, 255, 0.2); }
                50% { transform: scale(1.05); box-shadow: 0 20px 50px rgba(185, 103, 255, 0.4); }
            }

            .question-card.answered {
                border-color: rgba(255, 215, 0, 0.6);
                background: linear-gradient(135deg, rgba(40, 10, 70, 0.9) 0%, rgba(60, 20, 90, 0.9) 100%);
            }

            .answer-btn:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                pointer-events: none;
            }
        `;
        document.head.appendChild(dynamicStyles);
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>