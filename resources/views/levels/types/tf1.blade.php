<x-app-layout>
@php
    // Header stats (safe defaults)
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;
    $savedStars    = $levelProgress->stars ?? 0;

    // Ensure we always have an array (even if content is stored as JSON text)
    $content      = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $intro        = $content['intro'] ?? null;
    $instructions = $content['instructions'] ?? null;
    $questionsRaw = $content['questions'] ?? [];
    $hints        = $content['hints'] ?? [];
    $timeLimit    = (int)($content['time_limit'] ?? 300);
    $maxHints     = (int)($content['max_hints']  ?? 3);

    // Normalize questions to a simple array the JS can use
    $questions = [];
    foreach ($questionsRaw as $i => $q) {
        $questions[] = [
            'id'          => $i + 1,
            'text'        => $q['statement']   ?? '',
            'code'        => $q['code']        ?? null,
            'correct'     => (bool)($q['answer'] ?? false),
            'explanation' => $q['explanation'] ?? '',
        ];
    }

    // One clean payload for the frontend
    $payload = [
        'questions'  => $questions,
        'hints'      => $hints,
        'time_limit' => $timeLimit,
        'max_hints'  => $maxHints,
    ];
@endphp

{{-- Expose the payload ONCE (before the class) --}}
<script>
  window.LEVEL_DATA = {!! json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
</script>

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
                            Level {{ $level->index }} • {{ strtoupper($level->type) }} Challenge
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

<style>
  :root{
    --deep-purple:#1a0636;--cosmic-purple:#4a1b6d;--space-blue:#162b6f;--dark-space:#0a1028;
    --neon-blue:#00b3ff;--neon-purple:#b967ff;--electric-blue:#05d9e8;--gold-gradient:linear-gradient(135deg,#ffd700 0%,#ffed4a 100%);
  }
  body{background:linear-gradient(45deg,var(--deep-purple),var(--cosmic-purple),var(--space-blue),var(--dark-space));min-height:100vh;font-family:'Orbitron','Arial',sans-serif;color:#fff}
  .epic-level-header{background:rgba(10,6,30,.9);backdrop-filter:blur(20px);border-bottom:3px solid var(--neon-purple);padding:20px 0;position:relative;overflow:hidden}
  .epic-level-header::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(185,103,255,.1),transparent);animation:headerShine 4s ease-in-out infinite}
  @keyframes headerShine{0%{left:-100%}50%,100%{left:100%}}
  .level-badge{width:70px;height:70px;background:var(--gold-gradient);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(185,103,255,.6);animation:levelPulse 2s ease-in-out infinite;position:relative;z-index:2}
  .level-number{font-size:1.8rem;font-weight:900;color:#333;text-shadow:1px 1px 2px rgba(0,0,0,.3)}
  @keyframes levelPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05);box-shadow:0 0 40px rgba(185,103,255,.8)}}
  .level-title{color:var(--neon-purple);font-size:1.8rem;font-weight:900;text-shadow:2px 2px 4px rgba(0,0,0,.5);letter-spacing:1px}
  .level-subtitle{color:rgba(255,255,255,.85)}
  .level-stats{display:flex;align-items:center}
  .stat-item{text-align:center;color:#fff;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:15px;border-radius:15px;border:1px solid rgba(255,255,255,.2);min-width:80px}
  .stat-icon{font-size:1.5rem;margin-bottom:5px}
  .stat-value{font-size:1.2rem;font-weight:900;color:var(--neon-purple)}
  .stat-label{font-size:.8rem;opacity:.8}

  .game-arena{background:rgba(26,6,54,.7);backdrop-filter:blur(20px);border-radius:30px;margin:30px auto;padding:40px;max-width:1200px;box-shadow:0 20px 60px rgba(0,0,0,.3);border:2px solid rgba(185,103,255,.3);position:relative;overflow:hidden}
  .challenge-title{text-align:center;font-size:2.4rem;font-weight:900;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:18px}
  .challenge-description{text-align:center;font-size:1.05rem;color:rgba(255,255,255,.9);margin:8px auto 26px;max-width:900px}
  .progress-bar-container{background:rgba(0,0,0,.1);height:12px;border-radius:10px;overflow:hidden;margin:18px 0}
  .progress-bar{height:100%;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));border-radius:10px;transition:width .8s cubic-bezier(.25,.46,.45,.94);position:relative}
  .progress-bar::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);animation:progressShine 2s ease-in-out infinite}
  @keyframes progressShine{0%{left:-100%}100%{left:100%}}
  .questions-container{margin:24px 0}
  .question-card{background:linear-gradient(135deg,rgba(40,10,70,.7),rgba(60,20,90,.7));border-radius:22px;padding:20px;margin:16px 0;border:3px solid var(--neon-purple);position:relative;overflow:hidden;transition:all .3s;box-shadow:0 10px 30px rgba(185,103,255,.2)}
  .question-card:hover{transform:translateY(-4px);box-shadow:0 20px 50px rgba(185,103,255,.3);border-color:var(--electric-blue)}
  .question-header{display:flex;align-items:center;margin-bottom:12px}
  .question-number{width:46px;height:46px;background:var(--gold-gradient);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;color:#333;margin-right:14px;box-shadow:0 0 18px rgba(255,215,0,.5)}
  .question-text{color:#fff;font-size:1.15rem;font-weight:600;line-height:1.5;flex-grow:1}
  .code-display{background:rgba(0,0,0,.85);border:2px solid var(--neon-blue);border-radius:14px;padding:14px;margin:10px 0 6px;font-family:'Courier New',monospace;color:#00ff00;font-size:1rem}
  .code-display::before{content:'💻 Python Code';position:absolute;top:8px;right:12px;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));color:#fff;padding:4px 10px;border-radius:12px;font-size:.75rem;font-weight:700}
  .answer-buttons{display:flex;gap:14px;justify-content:center;margin-top:12px}
  .answer-btn{flex:1;max-width:200px;padding:14px 20px;border:none;border-radius:18px;font-size:1.05rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;cursor:pointer;transition:all .2s;box-shadow:0 8px 25px rgba(0,0,0,.2)}
  .answer-btn.true-btn{background:linear-gradient(135deg,#28a745,#20c997);color:#fff}
  .answer-btn.false-btn{background:linear-gradient(135deg,#dc3545,#fd7e14);color:#fff}
  .answer-btn:hover{transform:translateY(-5px) scale(1.03)}
  .answer-btn.selected{transform:scale(1.06);box-shadow:0 0 26px rgba(255,255,255,.45);border:3px solid #fff}
  .question-result{position:absolute;top:10px;right:15px;font-size:1.6rem;opacity:0;transition:opacity .3s}
  .question-card.answered .question-result{opacity:1}
  .controls-section{text-align:center;margin:24px 0}
  .btn-epic{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));border:none;color:#fff;padding:14px 28px;border-radius:28px;font-size:1.05rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;transition:all .25s;box-shadow:0 8px 25px rgba(102,126,234,.3);margin:0 8px}
  .btn-epic:hover{transform:translateY(-4px) scale(1.03);box-shadow:0 15px 40px rgba(185,103,255,.5)}
  .btn-reset{background:linear-gradient(45deg,#ff2a6d,#ff6a00)}
  .btn-hint{background:linear-gradient(45deg,#f093fb,#f5576c)}
  .feedback-container{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;pointer-events:none}
  .feedback-message{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));color:#fff;padding:16px 28px;border-radius:22px;font-size:1.05rem;font-weight:800;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.3);animation:feedbackPop 2s ease;margin-bottom:8px}
  .feedback-message.error{background:linear-gradient(45deg,#ff2a6d,#ff6a00)}
  .feedback-message.warning{background:linear-gradient(45deg,#f093fb,#f5576c)}
  @keyframes feedbackPop{0%{transform:scale(0) rotate(180deg);opacity:0}20%{transform:scale(1.2) rotate(0);opacity:1}80%{transform:scale(1);opacity:1}100%{transform:scale(0) rotate(-180deg);opacity:0}}
  .completion-celebration{position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9999}
  .confetti{position:absolute;width:10px;height:10px;background:#b967ff;animation:confettiFall 3s linear infinite}
  @keyframes confettiFall{0%{transform:translateY(-100vh) rotate(0);opacity:1}100%{transform:translateY(100vh) rotate(720deg);opacity:0}}
  @media (max-width:768px){.answer-buttons{flex-direction:column;gap:12px}.answer-btn{max-width:none}}
</style>

<div class="game-arena">
    <h1 class="challenge-title">🎯 Cosmic True/False Challenge</h1>

    @if($intro)
      <p class="challenge-description">{!! nl2br(e($intro)) !!}</p>
    @endif
    @if($instructions)
      <p class="challenge-description" style="opacity:.9">{!! nl2br(e($instructions)) !!}</p>
    @endif

    <div class="progress-bar-container">
        <div class="progress-bar" id="progressBar" style="width:0%"></div>
    </div>

    <div class="questions-container" id="questionsContainer">
      <!-- Filled by JS -->
    </div>

    <div class="controls-section">
      <button class="btn-epic" onclick="checkAnswers()" id="checkBtn">
        <i class="fas fa-magic me-2"></i> Check Answers
      </button>
      <button class="btn-epic btn-hint" onclick="showHint()">
        <i class="fas fa-lightbulb me-2"></i> Hint
      </button>
      <button class="btn-epic btn-reset" onclick="resetGame()">
        <i class="fas fa-redo me-2"></i> Reset
      </button>
    </div>

    {{-- Hidden submit form --}}
    <form method="POST" action="{{ route('levels.submit', $level) }}" id="scoreForm" style="display:none;">
        @csrf
        <input type="hidden" name="score" id="finalScore" value="0">
        <input type="hidden" name="answers" id="answersPayload" value="">
    </form>
</div>

<div class="feedback-container" id="feedbackContainer"></div>
<div class="completion-celebration" id="celebrationContainer"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
class EpicTrueFalseGame {
  constructor() {
    const data = window.LEVEL_DATA || {};
    this.gameData = {
      questions: Array.isArray(data.questions) ? data.questions : [],
      hints: Array.isArray(data.hints) ? data.hints : []
    };
    this.maxScore = 100;
    this.timeLimit = Number.isFinite(data.time_limit) ? data.time_limit : 300;
    this.maxHints  = Number.isFinite(data.max_hints) ? data.max_hints : 3;

    this.score = 0;
    this.timeRemaining = this.timeLimit;
    this.hintsUsed = 0;
    this.gameStarted = false;
    this.gameCompleted = false;
    this.correctAnswers = 0;
    this.totalQuestions = this.gameData.questions.length;
    this.timer = null;

    // { [id]: 1|0 }
    this.userAnswers = {};

    this.init();
  }

  init() {
    this.generateQuestions();
    this.setupKeys();
    this.startTimer();
    this.updateUI();

    if (this.totalQuestions === 0) {
      this.showFeedback("No questions available for this level.", "warning", 3000);
      document.getElementById('checkBtn').disabled = true;
    } else {
      this.showFeedback("🎮 Epic Quest Begins! 🎮", "success", 1500);
    }
  }

  generateQuestions() {
    const container = document.getElementById('questionsContainer');
    container.innerHTML = '';

    this.gameData.questions.forEach((q, idx) => {
      const card = document.createElement('div');
      card.className = 'question-card';
      card.dataset.questionId = q.id;

      const codeHTML = q.code
        ? `<div class="code-display"><pre>${this.escapeHtml(q.code)}</pre></div>`
        : '';

      card.innerHTML = `
        <div class="question-result" id="result-${q.id}"></div>
        <div class="question-header">
          <div class="question-number">${idx + 1}</div>
          <div class="question-text">${this.escapeHtml(q.text)}</div>
        </div>
        ${codeHTML}
        <div class="answer-buttons">
          <button class="answer-btn true-btn" data-answer="1" onclick="selectAnswer(${q.id}, true)">
            <i class="fas fa-check me-2"></i> TRUE
          </button>
          <button class="answer-btn false-btn" data-answer="0" onclick="selectAnswer(${q.id}, false)">
            <i class="fas fa-times me-2"></i> FALSE
          </button>
        </div>
      `;
      container.appendChild(card);
    });
  }

  escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

  selectAnswer(questionId, answerBool) {
    if (this.gameCompleted) return;

    const value = answerBool ? 1 : 0;
    const card = document.querySelector(`[data-question-id="${questionId}"]`);
    const btns = card.querySelectorAll('.answer-btn');
    btns.forEach(b => b.classList.remove('selected'));

    const selectedBtn = card.querySelector(`[data-answer="${value}"]`);
    if (selectedBtn) selectedBtn.classList.add('selected');

    this.userAnswers[questionId] = value;

    if (!this.gameStarted) {
      this.gameStarted = true;
      this.showFeedback("🚀 Adventure Started!", "success", 1200);
    }

    this.updateProgress();
  }

  setupKeys() {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') this.checkAnswers();
      if (e.key === 'h' || e.key === 'H') this.showHint();
      if (e.key === 'r' || e.key === 'R') this.resetGame();
    });
  }

  showHint() {
    if (this.hintsUsed >= this.maxHints) {
      this.showFeedback("🔮 No more hints!", "error", 1600);
      return;
    }
    this.hintsUsed++;

    const hints = (this.gameData.hints && this.gameData.hints.length)
      ? this.gameData.hints
      : ["💡 Read the code and the statement carefully!"];
    const hint = hints[(this.hintsUsed - 1) % hints.length];
    this.showFeedback(`💡 Hint: ${hint}`, "info", 3000);

    // Nudge unanswered
    this.gameData.questions.forEach(q => {
      if (!(q.id in this.userAnswers)) {
        const card = document.querySelector(`[data-question-id="${q.id}"]`);
        card.style.animation = 'pulse 1s ease-in-out 3';
        setTimeout(() => card.style.animation = '', 2000);
      }
    });
  }

  updateProgress() {
    const answered = Object.keys(this.userAnswers).length;
    const pct = this.totalQuestions ? (answered / this.totalQuestions) * 100 : 0;
    document.getElementById('progressBar').style.width = pct + '%';
  }

  checkAnswers() {
    if (this.gameCompleted) return;
    if (Object.keys(this.userAnswers).length !== this.totalQuestions) {
      this.showFeedback("⚠️ Please answer all questions first!", "warning", 1800);
      return;
    }

    let correct = 0;

    this.gameData.questions.forEach(q => {
      const user = this.userAnswers[q.id];         // 1|0
      const correctVal = q.correct ? 1 : 0;        // 1|0
      const isCorrect = user === correctVal;

      const card = document.querySelector(`[data-question-id="${q.id}"]`);
      const resultIcon = document.getElementById(`result-${q.id}`);
      const correctBtn  = card.querySelector(`[data-answer="${correctVal}"]`);
      const selectedBtn = card.querySelector(`[data-answer="${user}"]`);

      card.classList.add('answered');

      if (isCorrect) {
        correct++;
        resultIcon.textContent = '✅';
        if (correctBtn) correctBtn.classList.add('selected'); // keep highlight
      } else {
        resultIcon.textContent = '❌';
        if (selectedBtn) selectedBtn.classList.add('selected');
        if (correctBtn)  { correctBtn.style.border = '3px solid #ffd700'; }
      }

      // Optional: show explanation below (if provided)
      if (q.explanation) {
        let ex = card.querySelector('.explanation');
        if (!ex) {
          ex = document.createElement('div');
          ex.className = 'explanation';
          ex.style.cssText = 'margin-top:8px;color:#e6d7ff;opacity:.95;font-size:.95rem;';
          card.appendChild(ex);
        }
        ex.innerHTML = isCorrect
          ? `<i class="fas fa-check-circle"></i> ${this.escapeHtml(q.explanation)}`
          : `<i class="fas fa-times-circle"></i> ${this.escapeHtml(q.explanation)}`;
      }
    });

    const percentage = Math.round((correct / this.totalQuestions) * 100);
    this.score = Math.max(0, percentage - (this.hintsUsed * 5));
    const timeBonus = Math.max(0, Math.floor(this.timeRemaining / 10));
    this.score = Math.min(100, this.score + timeBonus);

    this.updateUI();

    if (percentage >= 80) {
      this.showFeedback("🏆 LEGENDARY VICTORY! 🏆", "success", 3000);
      this.startCelebration();
    } else {
      this.showFeedback(`🎯 Score: ${this.score}% — ${correct}/${this.totalQuestions} correct`, percentage >= 60 ? "warning" : "error", 3000);
    }

    this.completeGame();
  }

  completeGame() {
    this.gameCompleted = true;
    clearInterval(this.timer);

    // Disable all buttons
    document.querySelectorAll('.answer-btn').forEach(b => b.disabled = true);

    // Submit score + answers map
    setTimeout(() => {
      document.getElementById('finalScore').value = this.score;
      document.getElementById('answersPayload').value = JSON.stringify(this.userAnswers);
      const form = document.getElementById('scoreForm');
      if (form.requestSubmit) form.requestSubmit();
      else form.submit();
    }, 1500);
  }

  startCelebration() {
    const wrap = document.getElementById('celebrationContainer');
    for (let i = 0; i < 80; i++) {
      setTimeout(() => {
        const c = document.createElement('div');
        c.className = 'confetti';
        c.style.left = Math.random() * 100 + 'vw';
        c.style.background = ['#ffd700','#ff6b6b','#4ecdc4','#45b7d1','#f9ca24'][Math.floor(Math.random()*5)];
        c.style.animationDelay = Math.random() * 3 + 's';
        wrap.appendChild(c);
        setTimeout(() => c.remove(), 3200);
      }, i * 25);
    }
  }

  startTimer() {
    this.timer = setInterval(() => {
      this.timeRemaining--;
      this.updateUI();

      if (this.timeRemaining <= 0) {
        clearInterval(this.timer);
        this.showFeedback("⏰ Time's up! Submitting...", "error", 2000);
        setTimeout(() => this.checkAnswers(), 800);
      } else if (this.timeRemaining <= 60 && this.timeRemaining % 15 === 0) {
        this.showFeedback(`⚠️ ${this.timeRemaining}s remaining!`, "warning", 1200);
      }
    }, 1000);
  }

  updateUI() {
    document.getElementById('currentScore').textContent = this.score;
    document.getElementById('starsEarned').textContent = this.getStars();
    const m = Math.floor(this.timeRemaining / 60);
    const s = this.timeRemaining % 60;
    document.getElementById('timeRemaining').textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
  }

  getStars() {
    if (this.score >= 90) return '⭐⭐⭐';
    if (this.score >= 70) return '⭐⭐';
    if (this.score >= 50) return '⭐';
    return '0';
  }

  showFeedback(msg, type="success", dur=1800) {
    const box = document.createElement('div');
    box.className = `feedback-message ${type}`;
    box.textContent = msg;
    const host = document.getElementById('feedbackContainer');
    host.appendChild(box);
    setTimeout(()=>box.remove(), dur);
  }

  resetGame() {
    if (confirm('🔄 Restart this level?')) location.reload();
  }
}

// Inline handlers for buttons
let game;
function selectAnswer(id, val){ game && game.selectAnswer(id, val); }
function checkAnswers(){ game && game.checkAnswers(); }
function showHint(){ game && game.showHint(); }
function resetGame(){ game && game.resetGame(); }

document.addEventListener('DOMContentLoaded', () => { game = new EpicTrueFalseGame(); });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>
