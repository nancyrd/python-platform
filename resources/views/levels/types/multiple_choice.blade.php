<x-app-layout>
@php
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;
    $savedStars    = $levelProgress->stars ?? 0;
    $timeLimit     = $level->content['time_limit'] ?? 180;
    $flowerEmojis = ['🌸', '🌻', '🌼', '🌷', '💐', '🥀', '🌺', '🌹', '🌱', '🌿'];
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
                            Level {{ $level->index }} • Flower Quiz
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
                            <div class="stat-value" id="timeRemaining">03:00</div>
                            <div class="stat-label">Time</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>

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
 

   /* Title smaller */
.epic-level-header {
  background: rgba(10,6,30,.9);
  backdrop-filter: blur(20px);
  border-bottom: 3px solid var(--neon-purple);
  padding: 20px 0;
  position: relative;
  overflow: hidden;
}
.epic-level-header::before {
  content: '';
  position: absolute;
  top: 0; left: -100%;
  width: 100%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(185,103,255,.1), transparent);
  animation: headerShine 4s ease-in-out infinite;
}
@keyframes headerShine { 0%{left:-100%}50%,100%{left:100%} }

.level-badge {
  width: 70px; height: 70px;
  background: var(--gold-gradient);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 0 30px rgba(185,103,255,.6);
  animation: levelPulse 2s ease-in-out infinite;
  position: relative; z-index: 2;
}
.level-number {
  font-size: 1.8rem;
  font-weight: 900;
  color: #333;
  text-shadow: 1px 1px 2px rgba(0,0,0,.3);
}
@keyframes levelPulse { 0%,100%{transform:scale(1)}50%{transform:scale(1.05);box-shadow:0 0 40px rgba(185,103,255,.8)} }

.level-title {
  color: var(--neon-purple);
  font-size: 1.8rem;
  font-weight: 900;
  text-shadow: 2px 2px 4px rgba(0,0,0,.5);
  letter-spacing: 1px;
}
.level-subtitle { color: rgba(255,255,255,.85); }
.level-stats { display: flex; align-items: center; }
.stat-item {
  text-align: center;
  color: #fff;
  background: rgba(255,255,255,.1);
  backdrop-filter: blur(10px);
  padding: 15px;
  border-radius: 15px;
  border: 1px solid rgba(255,255,255,.2);
  min-width: 80px;
}
.stat-icon { font-size: 1.5rem; margin-bottom: 5px; }
.stat-value { font-size: 1.2rem; font-weight: 900; color: var(--neon-purple); }
.stat-label { font-size: .8rem; opacity: .8; }

    
   .game-arena {
    background: rgba(26, 6, 54, 0.7);
    backdrop-filter: blur(20px);

    /* ✅ remove rounded card look */
    border-radius: 0;
    margin: 0;
    padding: 30px;

    /* ✅ full width/height */
    max-width: 100%;
    width: 100%;
    min-height: calc(100vh - 70px); /* fills page minus header */

    border: none;
    box-shadow: none;
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
    
    .mcq-questions-list {
        display: flex; 
        flex-wrap: wrap; 
        gap: 30px;
        justify-content: center;
        margin-bottom: 22px;
        position: relative;
        z-index: 2;
    }
    
    .mcq-flower-card {
        background: radial-gradient(circle at 70% 40%, rgba(30, 10, 60, 0.8) 60%, rgba(50, 20, 80, 0.8) 100%);
        border-radius: 45% 55% 54% 46%/59% 44% 56% 41%;
        box-shadow: 0 2px 18px rgba(185, 103, 255, 0.3);
        width: 260px; 
        min-height: 210px;
        padding: 28px 20px 14px 20px;
        display: flex; 
        flex-direction: column;
        align-items: center; 
        justify-content: flex-start;
        position: relative; 
        overflow: visible;
        border: 2.2px dashed var(--neon-purple);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }
    
    .mcq-flower-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 30px rgba(185, 103, 255, 0.6);
    }
    
    .mcq-flower-card.correct { 
        border: 2.2px solid #4de37e; 
        box-shadow: 0 6px 23px rgba(77, 227, 126, 0.3);
        animation: correctFlower 1s ease;
    }
    
    .mcq-flower-card.incorrect { 
        border: 2.2px solid #ff686b; 
        box-shadow: 0 6px 23px rgba(255, 104, 107, 0.3);
        animation: incorrectFlower 0.5s ease;
    }

    @keyframes correctFlower {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); box-shadow: 0 0 50px rgba(77, 227, 126, 0.8); }
        100% { transform: scale(1); }
    }

    @keyframes incorrectFlower {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .mcq-flower-emoji {
        font-size: 2.9rem;
        margin-bottom: 4px;
        filter: drop-shadow(0 3px 7px rgba(185, 103, 255, 0.6));
        user-select: none;
        transition: transform 0.3s ease;
    }

    .mcq-flower-card:hover .mcq-flower-emoji {
        transform: scale(1.1) rotate(5deg);
    }
    
    .mcq-question-text {
        font-size: 1.10rem;
        color: #e0b1ff;
        font-weight: bold;
        margin-bottom: 10px;
        text-align: center;
        letter-spacing: 0.2px;
    }
    
    .mcq-options label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #d9c2ff;
        padding: 6px 10px;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .mcq-options label:hover {
        background: rgba(185, 103, 255, 0.2);
        transform: translateX(5px);
    }
    
    .mcq-options input[type="radio"]:checked + label {
        background: linear-gradient(90deg, rgba(185, 103, 255, 0.3), rgba(0, 179, 255, 0.3));
        color: #ffffff;
        font-weight: bold;
        transform: scale(1.02);
        box-shadow: 0 3px 10px rgba(185, 103, 255, 0.2);
    }
    
    .mcq-flower-card .explanation {
        margin-top: 5px; 
        font-size: .99em; 
        color: #c9b6ff; 
        font-weight: 500;
        opacity: .93; 
        text-align: center;
        animation: explanationAppear 0.5s ease;
    }

    @keyframes explanationAppear {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 0.93; transform: translateY(0); }
    }
    
    .bouquet-result {
        display: flex; 
        flex-direction: column; 
        align-items: center;
        margin-bottom: 18px;
        animation: bouquetDrop .9s cubic-bezier(.41,.52,.28,1.2);
        position: relative;
        z-index: 2;
    }
    
    .bouquet-flowers {
        font-size: 2.3rem;
        filter: drop-shadow(0 3px 10px rgba(185, 103, 255, 0.6));
    }
    
    .bouquet-text {
        margin-top: 6px; 
        font-size: 1.12rem; 
        color: #ffffff; 
        font-weight: bold;
        letter-spacing: .7px;
        text-shadow: 0 1px 6px rgba(255, 255, 255, 0.2);
    }

    @keyframes bouquetDrop {
        0% { transform: translateY(-50px) scale(0.8); opacity: 0; }
        60% { transform: translateY(10px) scale(1.1); opacity: 1; }
        100% { transform: translateY(0) scale(1); opacity: 1; }
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
        box-shadow: 0 8px 25px rgba(185, 103, 255, 0.3);
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
        margin-bottom: 10px;
    }

    .feedback-message.error {
        background: linear-gradient(45deg, var(--bright-pink), #ff6a00);
    }

    .feedback-message.warning {
        background: linear-gradient(45deg, #f093fb, #f5576c);
    }

    .feedback-message.info {
        background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
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
        background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
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

    @keyframes sparkle {
        0% { opacity: 1; transform: scale(0) rotate(0deg); }
        50% { opacity: 1; transform: scale(1) rotate(180deg); }
        100% { opacity: 0; transform: scale(0) rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 2px 18px rgba(185, 103, 255, 0.3); }
        50% { transform: scale(1.05); box-shadow: 0 8px 30px rgba(185, 103, 255, 0.8); }
    }

    @keyframes gameRipple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    @media (max-width: 768px) {
        .mcq-questions-list {
            flex-direction: column;
            align-items: center;
        }
        
        .mcq-flower-card {
            width: 100%;
            max-width: 300px;
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
            font-size: 1.8rem;
        }

        .btn-epic {
            padding: 12px 25px;
            font-size: 1rem;
            margin: 5px;
        }
    }
</style>

<!-- Flower Floating Background -->
<div class="floating-flowers" aria-hidden="true">
    @for($i=0; $i<12; $i++)
        <span class="flower" style="left:{{ rand(3,90) }}vw; top:{{ rand(5,85) }}vh; animation-delay:{{ rand(0, 12) }}s;">
            {{ $flowerEmojis[array_rand($flowerEmojis)] }}
        </span>
    @endfor
</div>

<div class="game-arena">
    <h1 class="challenge-title">🌸 Python Flower Quiz 🌻</h1>
    <p class="challenge-description">
        {!! $level->content['intro'] ?? 'Collect beautiful flowers by answering Python questions correctly!' !!}
    </p>

    <div id="bouquetArea"></div>

    <div class="progress-bar-container mb-3">
        <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
    </div>

    <!-- IMPORTANT: novalidate + explicit hidden score/answers -->
    <form id="quizForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
        @csrf
        <input type="hidden" name="score" id="finalScore" value="0">
        <input type="hidden" name="answers" id="answersData" value="[]">

        <div class="mcq-questions-list">
            @foreach($level->content['questions'] as $i => $q)
                <div class="mcq-flower-card" data-question="{{ $i }}">
                    <div class="mcq-flower-emoji">
                        {{ $flowerEmojis[$i % count($flowerEmojis)] }}
                    </div>
                    <div class="mcq-question-text">{!! $q['question'] !!}</div>
                    <div class="mcq-options">
                        @foreach($q['options'] as $j => $option)
                            <input type="radio" name="q{{ $i }}" value="{{ $j }}" id="q{{ $i }}_{{ $j }}" style="display:none;">
                            <label for="q{{ $i }}_{{ $j }}">{{ $option }}</label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="controls-section">
            <button type="button" class="btn-epic" id="btnCheck" onclick="checkAnswers()">
                <i class="fas fa-magic me-2"></i>
                Collect Flowers!
            </button>
            <button type="button" class="btn-epic btn-hint" id="btnHint" onclick="showHint()">
                <i class="fas fa-lightbulb me-2"></i>
                Oracle's Wisdom
            </button>
            <button type="button" class="btn-epic btn-reset" id="btnReset" onclick="resetGame()">
                <i class="fas fa-redo me-2"></i>
                Reset Realm
            </button>
        </div>
    </form>
</div>

<!-- Required containers for game engine -->
<div class="feedback-container" id="feedbackContainer"></div>
<div class="completion-celebration" id="celebrationContainer"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
class EpicFlowerGame {
    constructor() {
        this.score = 0;
        this.maxScore = 100;
        this.timeLimit = {{ $timeLimit }};
        this.timeRemaining = this.timeLimit;
        this.hintsUsed = 0;
        this.maxHints = 3;
        this.gameStarted = false;
        this.gameCompleted = false;
        this.correctCount = 0;
        this.totalQuestions = 0;
        this.timer = null;
        this.submitting = false;

        // Answer key + explanations
        this.answerKey   = @json(array_map(fn($q) => $q['correct_answer'], $level->content['questions'] ?? []));
        this.explanations = @json(array_map(fn($q) => $q['explanation'] ?? '', $level->content['questions'] ?? []));
        this.flowerEmojis = @json($flowerEmojis);
        this.totalQuestions = this.answerKey.length;

        // Hints (fallback to defaults if null)
        this.hints = @json($level->content['hints'] ?? null) || [
            "🌸 Read each question carefully and think about Python basics!",
            "💡 Remember what you learned about Python programming!",
            "🌻 Take your time—good flowers need patience to bloom!",
            "🐍 Python is friendly for beginners—trust your instincts!",
            "📚 Think about what makes Python special as a programming language!"
        ];

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.startTimer();
        this.updateUI();
        this.showFeedback("🎮 Epic Flower Quest Begins! 🎮", "success", 2000);
        this.gameStarted = true;
    }

    setupEventListeners() {
        // progress as user selects
        document.querySelectorAll('.mcq-options input[type=radio]').forEach(radio => {
            radio.addEventListener('change', () => {
                const answered = document.querySelectorAll('.mcq-options input[type=radio]:checked').length;
                this.updateProgress(answered, this.totalQuestions);
            });
        });

        // keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (this.gameCompleted) return;
            if (e.key === 'h' || e.key === 'H') { e.preventDefault(); this.showHint(); }
            else if (e.key === 'r' || e.key === 'R') { e.preventDefault(); this.resetGame(); }
            else if (e.key === 'Enter' && e.ctrlKey) { e.preventDefault(); this.checkAnswers(); }
        });

        // ripple visuals
        document.querySelectorAll('.btn-epic').forEach(btn => {
            btn.addEventListener('click', this.createRippleEffect.bind(this));
        });
    }

    createRippleEffect(e) {
        if (e.currentTarget.disabled) return;
        const button = e.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = (e.clientX ?? (rect.left + rect.width/2)) - rect.left - size/2;
        const y = (e.clientY ?? (rect.top + rect.height/2)) - rect.top - size/2;

        ripple.style.cssText = `
            position:absolute;width:${size}px;height:${size}px;left:${x}px;top:${y}px;
            background:rgba(255,255,255,.3);border-radius:50%;transform:scale(0);
            animation: gameRipple .6s linear;pointer-events:none;z-index:1000;
        `;
        button.style.position = 'relative';
        button.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    }

    startTimer() {
        this.timer = setInterval(() => {
            this.timeRemaining--;
            this.updateUI();
            if (this.timeRemaining <= 0) {
                this.handleTimeUp();
            } else if (this.timeRemaining <= 60 && this.timeRemaining % 15 === 0) {
                this.showFeedback(`⚠️ ${this.timeRemaining} seconds remaining! ⚠️`, "warning", 1500);
            }
        }, 1000);
    }

    handleTimeUp() {
        clearInterval(this.timer);
        this.showFeedback("⏰ Time's Up! Collecting your flowers... ⏰", "error", 1800);
        setTimeout(() => this.checkAnswers(), 800);
    }

    updateUI() {
        document.getElementById('currentScore').textContent = this.score;
        document.getElementById('starsEarned').textContent  = this.getStars();
        const m = Math.floor(this.timeRemaining / 60);
        const s = this.timeRemaining % 60;
        document.getElementById('timeRemaining').textContent = `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    getStars() {
        if (this.score >= 90) return '⭐⭐⭐';
        if (this.score >= 70) return '⭐⭐';
        if (this.score >= 50) return '⭐';
        return '0';
    }

    checkAnswers() {
        if (this.gameCompleted || this.submitting) return;

        let correct = 0, total = 0;
        const answers = [];

        document.querySelectorAll('.mcq-flower-card').forEach((card, i) => {
            total++;
            const selected = card.querySelector('input[type=radio]:checked');
            let isCorrect = false;

            if (selected) {
                const val = parseInt(selected.value);
                answers.push(Number.isFinite(val) ? val : -1);
                if (val === parseInt(this.answerKey[i])) {
                    isCorrect = true; correct++;
                    card.classList.remove('incorrect'); card.classList.add('correct');
                    card.style.background = 'linear-gradient(135deg, rgba(40,167,69,.8) 0%, rgba(56,239,125,.8) 100%)';
                } else {
                    card.classList.remove('correct'); card.classList.add('incorrect');
                    card.style.background = 'linear-gradient(135deg, rgba(220,53,69,.8) 0%, rgba(255,106,0,.8) 100%)';
                }
            } else {
                // IMPORTANT: push -1 (integer), not null — avoids 422 validation traps
                answers.push(-1);
                card.classList.remove('correct'); card.classList.add('incorrect');
                card.style.background = 'linear-gradient(135deg, rgba(220,53,69,.8) 0%, rgba(255,106,0,.8) 100%)';
            }

            // explanation
            const explanation = this.explanations[i] || '';
            let ex = card.querySelector('.explanation');
            if (!ex) {
                ex = document.createElement('div');
                ex.className = 'explanation';
                card.appendChild(ex);
            }
            ex.innerHTML = isCorrect
              ? `<i class='fas fa-check-circle text-success'></i> ${explanation}`
              : `<i class='fas fa-times-circle text-danger'></i> ${explanation}`;
        });

        // score
        const percentage = total > 0 ? Math.round((correct / total) * 100) : 0;
        this.score = Math.max(0, percentage - (this.hintsUsed * 5));
        const timeBonus = Math.max(0, Math.floor(this.timeRemaining / 10));
        this.score = Math.min(100, this.score + timeBonus);

        this.updateUI();
        this.showBouquetResult(correct, total);

        // set hidden inputs
        const form = document.getElementById('quizForm');
        document.getElementById('finalScore').value = this.score;
        document.getElementById('answersData').value = JSON.stringify(answers);

        // feedback
        if (percentage >= 80) {
            this.showFeedback("🏆 LEGENDARY VICTORY! 🏆", "success", 1500);
            this.startCelebration();
        } else {
            this.showFeedback(`🌸 Score: ${this.score}% — ${correct}/${total} flowers collected!`,
                percentage >= 60 ? "warning" : "error", 1500);
        }

        // lock UI & submit
        this.gameCompleted = true;
        this.submitting = true;
        clearInterval(this.timer);

        // disable buttons so the user can’t re-trigger
        ['btnCheck','btnHint','btnReset'].forEach(id => {
            const b = document.getElementById(id);
            if (b) b.disabled = true;
        });

        setTimeout(() => {
            // robust submit (works even if a browser blocks implicit submit)
            if (form.requestSubmit) form.requestSubmit();
            else form.submit();
        }, 1200);
    }

    showBouquetResult(correct, total) {
        let bouquet = "";
        for (let i = 0; i < correct; i++) {
            bouquet += this.flowerEmojis[i % this.flowerEmojis.length];
        }
        const area = document.getElementById('bouquetArea');
        area.innerHTML = `
            <div class="bouquet-result">
                <div class="bouquet-flowers">${bouquet || '🫠'}</div>
                <div class="bouquet-text">
                    You collected <b>${correct}</b> flower${correct == 1 ? '' : 's'}!
                    ${correct == total ? '<br>Perfect bouquet! 💐' : ''}
                </div>
            </div>
        `;
    }

    startCelebration() {
        const wrap = document.getElementById('celebrationContainer');
        for (let i = 0; i < 100; i++) {
            setTimeout(() => {
                const c = document.createElement('div');
                c.className = 'confetti';
                c.style.left = Math.random() * 100 + 'vw';
                c.style.background = ['#ffd700','#ff6b6b','#4ecdc4','#45b7d1','#f9ca24'][Math.floor(Math.random()*5)];
                c.style.animationDelay = Math.random() * 3 + 's';
                wrap.appendChild(c);
                setTimeout(() => c.remove(), 3000);
            }, i * 30);
        }
    }

    showHint() {
        if (this.gameCompleted || this.submitting) return;
        if (this.hintsUsed >= this.maxHints) {
            this.showFeedback("🔮 No more wisdom from the Oracle! 🔮", "error", 1800);
            return;
        }
        this.hintsUsed++;
        const randomHint = this.hints[Math.floor(Math.random() * this.hints.length)];
        this.showFeedback(`💡 Oracle's Wisdom: ${randomHint}`, "info", 2200);
        this.highlightHintElements();
    }

    highlightHintElements() {
        document.querySelectorAll('.mcq-flower-card').forEach(card => {
            card.style.animation = 'pulse 1s ease-in-out 3';
            setTimeout(() => { card.style.animation = ''; }, 3000);
        });
    }

    resetGame() {
        if (this.gameCompleted || this.submitting) return;
        if (confirm('🔄 Are you sure you want to restart your mystical flower journey?')) {
            location.reload();
        }
    }

    updateProgress(completed, total) {
        const bar = document.getElementById('progressBar');
        if (bar && total > 0) bar.style.width = ((completed/total)*100) + '%';
    }

    showFeedback(message, type = "success", duration = 2000) {
        const box = document.createElement('div');
        box.className = `feedback-message ${type}`;
        box.textContent = message;
        document.getElementById('feedbackContainer').appendChild(box);
        setTimeout(() => box.remove(), duration);
    }
}

// Global hooks
function checkAnswers(){ window.game && window.game.checkAnswers(); }
function showHint(){ window.game && window.game.showHint(); }
function resetGame(){ window.game && window.game.resetGame(); }

// Boot
document.addEventListener('DOMContentLoaded', () => {
    window.game = new EpicFlowerGame();
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>