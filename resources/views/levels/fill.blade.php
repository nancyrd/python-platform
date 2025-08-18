<x-app-layout>
@php
    // Respect saved progress unless the user explicitly asked to replay
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
                            Level {{ $level->index }} • Fill-in-the-Blank
                            @if($alreadyPassed)
                                <span class="badge bg-success ms-2" style="font-size:.8rem">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="level-stats">
                        <div class="stat-item me-3">
                            <div class="stat-icon">🏆</div>
                            <div class="stat-value" id="currentScore">{{ $alreadyPassed ? ($savedScore ?? 0) : 0 }}</div>
                            <div class="stat-label">Score</div>
                        </div>
                        <div class="stat-item me-3">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-value" id="starsEarned">
                                @if($alreadyPassed)
                                    {!! $savedStars > 0 ? str_repeat('⭐', $savedStars) : '0' !!}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="stat-label">Stars</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">⏱️</div>
                            <div class="stat-value" id="timeRemaining">{{ $alreadyPassed ? '—' : '06:00' }}</div>
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
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --danger-gradient: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gold-gradient: linear-gradient(135deg, #ffd700 0%, #ffed4a 100%);
    }
    body { background: var(--primary-gradient); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .epic-level-header{background:rgba(0,0,0,0.9);backdrop-filter:blur(20px);border-bottom:3px solid #ffd700;padding:20px 0;position:relative;overflow:hidden}
    .epic-level-header::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,215,0,0.1),transparent);animation:headerShine 4s ease-in-out infinite}
    @keyframes headerShine{0%{left:-100%}50%{left:100%}100%{left:100%}}
    .level-badge{width:70px;height:70px;background:var(--gold-gradient);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(255,215,0,.6);animation:levelPulse 2s ease-in-out infinite;position:relative;z-index:2}
    .level-number{font-size:1.8rem;font-weight:900;color:#333;text-shadow:1px 1px 2px rgba(0,0,0,.3)}
    @keyframes levelPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05);box-shadow:0 0 40px rgba(255,215,0,.8)}}
    .level-title{color:#ffd700;font-size:1.8rem;font-weight:900;text-shadow:2px 2px 4px rgba(0,0,0,.5);letter-spacing:1px}
    .level-subtitle{color:rgba(255,255,255,.8);font-size:1rem}
    .level-stats{display:flex;align-items:center}
    .stat-item{text-align:center;color:#fff;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:15px;border-radius:15px;border:1px solid rgba(255,255,255,.2);min-width:80px}
    .stat-icon{font-size:1.5rem;margin-bottom:5px}
    .stat-value{font-size:1.2rem;font-weight:900;color:#ffd700}
    .stat-label{font-size:.8rem;opacity:.8}

    .game-arena{background:rgba(255,255,255,.98);backdrop-filter:blur(20px);border-radius:30px;margin:30px auto;padding:40px;max-width:1100px;box-shadow:0 20px 60px rgba(0,0,0,.3);border:2px solid rgba(255,215,0,.3);position:relative;overflow:hidden}
    .game-arena::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(255,215,0,.05) 0%,transparent 70%);animation:arenaGlow 6s ease-in-out infinite}
    @keyframes arenaGlow{0%,100%{transform:rotate(0)}50%{transform:rotate(180deg)}}
    .challenge-title{text-align:center;font-size:2.2rem;font-weight:900;background:var(--primary-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:15px}
    .challenge-description{text-align:center;font-size:1.05rem;color:#666;margin-bottom:25px}
    .fill-card{background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);border-radius:20px;border:3px dashed #dee2e6;padding:25px;margin-bottom:18px;position:relative;overflow:hidden}
    .fill-card.correct{border-color:#28a745;background:linear-gradient(135deg,#e8f9ee 0%,#ffffff 100%)}
    .fill-card.incorrect{border-color:#dc3545;background:linear-gradient(135deg,#ffe9e9 0%,#ffffff 100%)}
    .q-label{font-weight:800;color:#333;margin-bottom:10px}
    .blank{display:inline-block;min-width:140px;border-bottom:3px solid #764ba2;padding:4px 6px;margin:0 6px;border-radius:6px;background:#fff}
    .blank input{border:none;outline:none;width:100%;font-weight:700}
    .controls-section{text-align:center;margin:30px 0}
    .btn-epic{background:var(--primary-gradient);border:none;color:white;padding:14px 28px;border-radius:30px;font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;transition:all .3s ease;box-shadow:0 8px 25px rgba(102,126,234,.3);position:relative;overflow:hidden;margin:0 8px}
    .btn-epic:hover{transform:translateY(-3px) scale(1.03);box-shadow:0 15px 40px rgba(102,126,234,.5);color:white}
    .btn-reset{background:var(--danger-gradient);box-shadow:0 8px 25px rgba(238,9,121,.3)}
    .btn-hint{background:var(--warning-gradient);box-shadow:0 8px 25px rgba(240,147,251,.3)}
    .progress-bar-container{background:rgba(0,0,0,.08);height:12px;border-radius:10px;overflow:hidden;margin:16px 0}
    .progress-bar{height:100%;background:var(--success-gradient);border-radius:10px;transition:width .8s cubic-bezier(.25,.46,.45,.94);position:relative;overflow:hidden}
    .progress-bar::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);animation:progressShine 2s ease-in-out infinite}
    @keyframes progressShine{0%{left:-100%}100%{left:100%}}

    .feedback-container{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;pointer-events:none}
    .feedback-message{background:var(--success-gradient);color:#fff;padding:18px 30px;border-radius:20px;font-size:1.2rem;font-weight:800;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.3);animation:feedbackPop 2s ease}
    .feedback-message.error{background:var(--danger-gradient)}
    @keyframes feedbackPop{0%{transform:scale(0) rotate(180deg);opacity:0}20%{transform:scale(1.1) rotate(0);opacity:1}80%{transform:scale(1)}100%{transform:scale(0);opacity:0}}

    .completion-celebration{position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9999}
    .confetti{position:absolute;width:10px;height:10px;background:#ffd700;animation:confettiFall 3s linear infinite}
    @keyframes confettiFall{0%{transform:translateY(-100vh) rotate(0);opacity:1}100%{transform:translateY(100vh) rotate(720deg);opacity:0}}
</style>

@if($alreadyPassed)
    {{-- COMPLETED VIEW --}}
    <div class="game-arena" style="text-align:center">
        <h1 class="challenge-title">✅ Level Completed</h1>
        <p class="challenge-description">
            Best score: <strong>{{ $savedScore }}%</strong><br>
            Stars earned:
            @if($savedStars>0)
                {!! str_repeat('⭐', $savedStars) !!}
            @else
                0
            @endif
        </p>

        <div class="controls-section">
            @if($nextLevel && $progress && $nextLevel->index <= $progress->unlocked_to_level)
                <a class="btn-epic" href="{{ route('levels.show', $nextLevel) }}">
                    <i class="fas fa-forward me-2"></i> Next Level
                </a>
            @else
                <a class="btn-epic" href="{{ route('stages.enter', $level->stage) }}">
                    <i class="fas fa-map me-2"></i> Back to Stage Map
                </a>
            @endif

            <a class="btn-epic btn-hint" href="{{ route('dashboard') }}">
                <i class="fas fa-home me-2"></i> Home
            </a>

            <a class="btn-epic btn-reset" href="{{ route('levels.show',$level) }}?replay=1">
                <i class="fas fa-redo me-2"></i> Replay Level
            </a>
        </div>
    </div>
@else
    {{-- FILL-IN-THE-BLANK GAME --}}
    <div class="game-arena">
        <h1 class="challenge-title">✍️ Fill-in-the-Blank: Python Basics</h1>
        <p class="challenge-description">
            Type the missing bits! Small hints appear if you ask the Oracle. You’ve got 6 minutes—good luck!
        </p>

        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
        </div>

        <div id="quizContainer">
            <!-- Q1 -->
            <div class="fill-card" data-answer="World">
                <div class="q-label">Q1. Print “Hello, World”</div>
                <div>Code: <code>print("Hello, <span class="blank"><input type="text" placeholder="..."></span>")</code></div>
            </div>

            <!-- Q2 -->
            <div class="fill-card" data-answer="5">
                <div class="q-label">Q2. Assign number 5 to a variable <code>x</code></div>
                <div>Code: <code>x = <span class="blank"><input type="text" placeholder="..."></span></code></div>
            </div>

            <!-- Q3 -->
            <div class="fill-card" data-answer="int">
                <div class="q-label">Q3. Which built-in converts <code>"42"</code> to a number?</div>
                <div>Code: <code><span class="blank"><input type="text" placeholder="..."></span>("42")</code></div>
            </div>

            <!-- Q4 -->
            <div class="fill-card" data-answer="def">
                <div class="q-label">Q4. Start a function definition (keyword)</div>
                <div>Code: <code><span class="blank"><input type="text" placeholder="..."></span> greet():</code></div>
            </div>

            <!-- Q5 -->
            <div class="fill-card" data-answer="for">
                <div class="q-label">Q5. Loop keyword to iterate a list</div>
                <div>Code: <code><span class="blank"><input type="text" placeholder="..."></span> item in [1,2,3]:</code></div>
            </div>

            <!-- Q6 -->
            <div class="fill-card" data-answer="True">
                <div class="q-label">Q6. Python boolean literal for truth</div>
                <div>Code: <code>is_active = <span class="blank"><input type="text" placeholder="..."></span></code></div>
            </div>

            <!-- Q7 -->
            <div class="fill-card" data-answer="list">
                <div class="q-label">Q7. Built-in type created with <code>[]</code></div>
                <div>Answer: <code>my_items = [1,2,3] → type is <span class="blank"><input type="text" placeholder="..."></span></code></div>
            </div>

            <!-- Q8 -->
            <div class="fill-card" data-answer="import">
                <div class="q-label">Q8. Keyword to bring a module into scope</div>
                <div>Code: <code><span class="blank"><input type="text" placeholder="..."></span> math</code></div>
            </div>
        </div>

        <div class="controls-section">
            <button class="btn-epic" onclick="submitQuiz()">
                <i class="fas fa-check me-2"></i> Submit Answers
            </button>
            <button class="btn-epic btn-hint" onclick="showHint()">
                <i class="fas fa-lightbulb me-2"></i> Oracle's Wisdom
            </button>
            <button class="btn-epic btn-reset" onclick="resetQuiz()">
                <i class="fas fa-redo me-2"></i> Reset
            </button>
        </div>

        <form method="POST" action="{{ route('levels.submit',$level) }}" id="scoreForm" style="display:none;">
            @csrf
            <input type="hidden" name="score" id="finalScore" value="0">
        </form>
    </div>

    <div class="feedback-container" id="feedbackContainer"></div>
    <div class="completion-celebration" id="celebrationContainer"></div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    const alreadyPassed = @json($alreadyPassed);
    if (alreadyPassed) return; // no JS needed if completed (we show the completion panel)

    let timeLimit = 360; // 6 minutes
    let timeRemaining = timeLimit;
    let hintsUsed = 0;
    const maxHints = 3;
    let timer = null;

    const inputs = Array.from(document.querySelectorAll('.fill-card .blank input'));
    inputs.forEach((inp, idx) => inp.addEventListener('input', updateProgress));

    startTimer();
    updateUI();

    function updateProgress(){
        const total = inputs.length;
        const filled = inputs.filter(i => (i.value || '').trim().length > 0).length;
        const pct = total ? Math.round((filled/total)*100) : 0;
        document.getElementById('progressBar').style.width = pct + '%';
    }

    function startTimer(){
        timer = setInterval(() => {
            timeRemaining--;
            updateUI();
            if (timeRemaining <= 0){
                clearInterval(timer);
                showFeedback("⏰ Time's Up! Submitting...", "error", 2500);
                setTimeout(submitQuiz, 800);
            } else if (timeRemaining <= 60 && timeRemaining % 15 === 0) {
                showFeedback(`⚠️ ${timeRemaining}s remaining!`, "warning", 1200);
            }
        }, 1000);
    }

    function updateUI(){
        document.getElementById('currentScore').textContent = 0;
        const m = Math.floor(timeRemaining/60);
        const s = timeRemaining % 60;
        const timeEl = document.getElementById('timeRemaining');
        if (timeEl) timeEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        // stars live value will be adjusted after grading
    }

    window.resetQuiz = function(){
        if (!confirm('Restart this level?')) return;
        inputs.forEach(inp => { inp.value = ''; inp.closest('.fill-card').classList.remove('correct','incorrect'); });
        hintsUsed = 0;
        timeRemaining = timeLimit;
        updateProgress();
        showFeedback('🔄 Reset!', 'info', 800);
    }

    window.showHint = function(){
        if (hintsUsed >= maxHints) { showFeedback('🔮 No more hints!', 'error', 1200); return; }
        hintsUsed++;
        const messages = [
            'Q1: The classic greeting is “World”.',
            'Q2: Just the number: 5',
            'Q3: Convert string digits to number: int(...)',
            'Q4: Function definitions start with “def”.',
            'Q5: Looping keyword is “for”.',
            'Q6: Python’s true literal is “True”.',
            'Q7: [] creates a list.',
            'Q8: Use “import” to bring in modules.'
        ];
        showFeedback('💡 Hint: ' + messages[Math.floor(Math.random()*messages.length)], 'info', 2500);
    }

    window.submitQuiz = function(){
        clearInterval(timer);
        const cards = Array.from(document.querySelectorAll('.fill-card'));
        let correct = 0, total = cards.length;

        cards.forEach(card => {
            const ans = (card.dataset.answer || '').trim();
            const user = (card.querySelector('input').value || '').trim();
            if (equalsCaseInsensitive(ans, user)) {
                correct++; card.classList.add('correct'); card.classList.remove('incorrect');
            } else {
                card.classList.add('incorrect'); card.classList.remove('correct');
            }
        });

        const basePct = total ? Math.round((correct/total)*100) : 0;
        // Hint penalty: -5 per hint (min 0)
        let score = Math.max(0, basePct - (hintsUsed * 5));
        // Small time bonus: +1 per 20s left, max 10
        const timeBonus = Math.min(10, Math.floor(timeRemaining/20));
        score = Math.min(100, score + timeBonus);

        // Stars mapping
        const stars = (score >= 90) ? 3 : (score >= 70) ? 2 : (score >= 50) ? 1 : 0;
        document.getElementById('currentScore').textContent = score;
        document.getElementById('starsEarned').textContent = stars ? '⭐'.repeat(stars) : '0';

        if (basePct >= 80) {
            startCelebration();
            showFeedback(`🏆 Victory! Score ${score}%`, 'success', 2000);
            setTimeout(() => {
                document.getElementById('finalScore').value = score;
                document.getElementById('scoreForm').submit();
            }, 2000);
        } else {
            showFeedback(`🎯 Score ${score}% — ${correct}/${total} correct. Keep going!`, 'error', 2600);
            // Optionally restart timer for another attempt during same visit
            startTimer();
        }
    }

    function equalsCaseInsensitive(a,b){
        return a.toLowerCase() === b.toLowerCase();
    }

    function showFeedback(message, type="success", duration=1800){
        const c = document.getElementById('feedbackContainer');
        const div = document.createElement('div');
        div.className = `feedback-message ${type}`;
        div.textContent = message;
        c.appendChild(div);
        setTimeout(()=>div.remove(), duration);
    }

    function startCelebration(){
        const container = document.getElementById('celebrationContainer');
        for (let i=0;i<80;i++){
            setTimeout(()=>{
                const conf = document.createElement('div');
                conf.className = 'confetti';
                conf.style.left = Math.random()*100 + 'vw';
                conf.style.background = ['#ffd700','#ff6b6b','#4ecdc4','#45b7d1','#f9ca24'][Math.floor(Math.random()*5)];
                conf.style.animationDelay = Math.random()*2 + 's';
                container.appendChild(conf);
                setTimeout(()=>conf.remove(), 3000);
            }, i*20);
        }
    }
})();
</script>

<!-- Font Awesome + Bootstrap CSS (kept at bottom for your original setup style) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>
