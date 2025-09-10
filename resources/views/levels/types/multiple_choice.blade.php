<x-app-layout>
@php
    // ===============================
    // Safe, precomputed data for Blade
    // ===============================
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    // Content fallbacks
    $content      = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $timeLimit    = (int)($content['time_limit'] ?? 180);
    $hints        = $content['hints'] ?? [];
    $questions    = $content['questions'] ?? [];
    $introText    = $content['intro'] ?? '';
    $uiInstrux    = $content['instructions'] ?? 'Choose the best answer for each question.';
    $maxHints     = (int)($content['max_hints'] ?? 3);

    // Default hints
    $defaultHints = [
        "Read the code carefully and watch for small details like spaces and exact output.",
        "Recall Python basics from the lesson above before answering.",
        "Eliminate obviously wrong choices first, then pick the best remaining option.",
        "If two answers seem right, re-check exact wording and number formatting.",
    ];
    $hintsForJs = !empty($hints) ? $hints : $defaultHints;

    // Build answer key & explanations arrays for JS
   // Change this if needed - should match your seeder structure
$answerKeyJs = array_map(fn($q) => $q['correct_answer'] ?? null, $questions);
    // Correct - looking for 'explanation' to match your seeder
    $explanationsJs = array_map(fn($q) => $q['explanation'] ?? '', $questions);

    $payload = [
        'questions'  => $questions,
        'hints'      => $hintsForJs,
        'time_limit' => $timeLimit,
        'max_hints'  => $maxHints,
        'answer_key' => $answerKeyJs,
        'explanations' => $explanationsJs,
    ];
@endphp

<script>
  window.LEVEL_DATA = {!! json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
</script>

<x-slot name="header">
    <!-- PAGE HEADER -->
    <div class="level-header">
        <div class="header-container">
            <!-- Left -->
            <div class="header-left">
                <div class="level-badge">
                    <span class="level-number">{{ $level->index }}</span>
                </div>
                <div class="level-info">
                    <div class="breadcrumb">
                        <span class="breadcrumb-item">Stage {{ $level->stage->index ?? $level->stage_id }}</span>
                        <span class="separator">•</span>
                        <span class="breadcrumb-item">Level {{ $level->index }}</span>
                        <span class="separator">•</span>
                        <span class="breadcrumb-item type">{{ ucfirst($level->type ?? 'challenge') }}</span>
                    </div>
                    <h1 class="stage-title">{{ $level->stage->title }}</h1>
                    <div class="level-title">{{ $level->title }}</div>
                </div>
            </div>

            <!-- Right stats -->
            <div class="header-right">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">Score</div>
                        <div class="stat-value" id="statScore">0%</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Stars</div>
                        <div class="stat-value" id="statStars">0</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Time</div>
                        <div class="stat-value" id="timeRemaining">--:--</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>

<style>
:root {
    /* Purple palette */
    --primary-purple: #7c3aed;
    --secondary-purple: #a855f7;
    --light-purple: #c084fc;
    --purple-subtle: #f3e8ff;

    /* Grays */
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;

    /* Semantic */
    --success: #10b981;
    --success-light: #dcfce7;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fecaca;

    /* UI */
    --background: #ffffff;
    --border: #e2e8f0;
    --text-primary: #1e293b;
    --text-secondary: #475569;
    --text-muted: #64748b;
    --shadow-sm: 0 1px 2px 0 rgba(0,0,0,.05);
    --shadow:    0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
}

body {
    background: linear-gradient(135deg,
        rgba(124,58,237,.03) 0%,
        rgba(168,85,247,.02) 50%,
        rgba(248,250,252,1) 100%);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
}

/* Utility */
.full-bleed{width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);}
.edge-pad{padding:1.25rem clamp(12px,3vw,32px);}
.d-none{display:none !important}

/* ==============================
   HEADER
   ============================== */
.level-header{
  background:linear-gradient(135deg, rgba(124,58,237,.06) 0%, rgba(168,85,247,.04) 100%);
  border-bottom:1px solid var(--border);
  backdrop-filter:blur(10px);
}
.header-container{display:flex;align-items:center;justify-content:space-between;padding:1.5rem 2rem;gap:2rem;}
.header-left{display:flex;align-items:center;gap:1.25rem;flex:1;min-width:0;}
.level-badge{width:4rem;height:4rem;border-radius:1rem;background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow-md);}
.level-number{color:#fff;font-weight:900;font-size:1.25rem;letter-spacing:.5px;}
.level-info{flex:1;min-width:0;}
.breadcrumb{display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--text-muted);margin-bottom:.25rem;flex-wrap:wrap;}
.breadcrumb-item.type{text-transform:capitalize;color:var(--primary-purple);font-weight:600;}
.separator{opacity:.55;}
.stage-title{font-size:1.5rem;font-weight:800;margin:0;line-height:1.2;color:var(--text-primary);}
.level-title{font-size:1rem;color:var(--text-secondary);margin-top:.25rem;}
.header-right{flex-shrink:0;}
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
.stat-item{text-align:center;padding:.75rem 1rem;background:#fff;border:1px solid var(--border);border-radius:.75rem;box-shadow:var(--shadow-sm);min-width:5rem;}
.stat-label{font-size:.75rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;}
.stat-value{font-size:1.125rem;font-weight:800;color:var(--text-primary);margin-top:.15rem;}

/* ==============================
   CARDS & SECTIONS
   ============================== */
.card{background:#fff;border:1px solid var(--border);border-radius:1rem;padding:1.25rem 1.25rem;box-shadow:var(--shadow-sm);}
.card+.card{margin-top:1rem;}
.card.accent{border-left:6px solid var(--primary-purple);background:linear-gradient(180deg,var(--purple-subtle), #fff);}
.section-title{font-size:1.125rem;font-weight:800;margin:0 0 .75rem 0;color:var(--text-primary);}

/* Instructions header row */
.instrux-row{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
.btn-toggle{background:transparent;border:1px solid var(--border);color:var(--text-secondary);padding:.5rem .75rem;border-radius:.65rem;font-weight:700;cursor:pointer;transition:all .15s ease;}
.btn-toggle:hover{background:var(--gray-50);border-color:var(--primary-purple);color:var(--primary-purple);}
.instructions-text{white-space:pre-wrap;color:var(--text-secondary);line-height:1.6;}

/* ==============================
   PROGRESS BAR
   ============================== */
.progress-header{background:#fff;border:1px solid var(--border);border-radius:1rem;padding:1rem 1.25rem;box-shadow:var(--shadow-sm);margin-bottom:1rem;}
.progress-row{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
.progress-title{font-size:1.05rem;font-weight:800;}
.progress-indicator{display:flex;align-items:center;gap:1rem;}
.progress-container{flex:1;max-width:280px;}
.progress-bar{height:.55rem;background:var(--gray-200);border-radius:.35rem;overflow:hidden;box-shadow:inset 0 0 0 1px rgba(0,0,0,.02);}
.progress-fill{height:100%;width:0;background:linear-gradient(90deg,var(--primary-purple),var(--secondary-purple));border-radius:.35rem;transition:width .3s ease;}
.question-counter{font-size:.9rem;color:var(--text-muted);font-weight:600;}

/* ==============================
   QUESTION CARD
   ============================== */
.question-container{max-width:800px;margin:0 auto;}
.question-card{background:#fff;border:1px solid var(--border);border-radius:1rem;padding:2rem;box-shadow:var(--shadow-sm);margin-bottom:1.5rem;}
.question-header{display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.5rem;}
.question-number{width:3rem;height:3rem;border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));font-size:1.25rem;}
.question-text{flex:1;font-size:1.125rem;font-weight:700;color:var(--text-primary);line-height:1.4;}
.question-code{background:#0f172a;color:#cfeaff;border:1px solid rgba(255,255,255,.08);border-radius:.75rem;padding:1rem 1.25rem;margin:1rem 0;white-space:pre-wrap;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;font-size:.95rem;overflow-x:auto;line-height:1.4;}

/* ==============================
   OPTIONS
   ============================== */
.question-options{display:flex;flex-direction:column;gap:.75rem;margin-top:1.5rem;}
.option-item{position:relative;}
.option-item input{position:absolute;inset:0;opacity:0;}
.option-item label{
  display:block;padding:1rem 1.25rem;border:1px solid var(--border);border-radius:.75rem;
  background:var(--gray-50);cursor:pointer;color:var(--text-primary);font-weight:600;
  transition:all .18s ease;font-size:1rem;line-height:1.4;
}
.option-item:hover label{border-color:var(--primary-purple);background:#fff;transform:translateY(-1px);box-shadow:var(--shadow);}
.option-item input:checked + label{border-color:var(--primary-purple);box-shadow:0 0 0 3px rgba(124,58,237,.18) inset;background:#fff;}

/* Navigation buttons */
.nav-controls{display:flex;justify-content:space-between;gap:1rem;margin-top:2rem;}
.btn-nav{background:var(--gray-100);color:var(--text-primary);border:1px solid var(--border);min-width:100px;}

/* ==============================
   BUTTONS
   ============================== */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.75rem 1.25rem;border:none;border-radius:.8rem;font-weight:700;font-size:1rem;cursor:pointer;transition:transform .12s ease, box-shadow .2s ease, filter .12s ease;min-width:120px;}
.btn:disabled{opacity:.55;cursor:not-allowed;}
.btn-primary{background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));color:#fff;box-shadow:var(--shadow);}
.btn-secondary{background:var(--gray-100);color:var(--text-primary);border:1px solid var(--border);}
.btn-warning{background:linear-gradient(135deg,#f59e0b,#fbbf24);color:#fff;}
.btn-danger{background:linear-gradient(135deg,#ef4444,#f43f5e);color:#fff;}
.btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:var(--shadow-lg);}
.btn.selected{outline:3px solid rgba(124,58,237,.35);transform:translateY(-2px);}

/* ==============================
   RESULTS SECTION
   ============================== */
.results-container{max-width:1000px;margin:0 auto;}
.results-header{background:#fff;border:1px solid var(--border);border-radius:1rem;padding:2rem;box-shadow:var(--shadow-sm);margin-bottom:2rem;text-align:center;}
.results-title{font-size:2rem;font-weight:800;margin:0 0 1rem 0;color:var(--text-primary);}
.results-score{font-size:3rem;font-weight:900;margin:1rem 0;color:var(--primary-purple);}
.results-stars{font-size:2rem;margin:1rem 0;color:#fbbf24;}
.results-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-top:2rem;}
.summary-item{text-align:center;padding:1rem;background:var(--gray-50);border-radius:.75rem;}
.summary-value{font-size:1.5rem;font-weight:800;color:var(--text-primary);}
.summary-label{font-size:.875rem;color:var(--text-muted);margin-top:.25rem;}

.results-grid{display:grid;gap:1.5rem;}
.result-card{background:#fff;border:1px solid var(--border);border-radius:1rem;padding:1.5rem;box-shadow:var(--shadow-sm);}
.result-card.correct{border-left:6px solid var(--success);background:linear-gradient(135deg, rgba(16,185,129,.05), #fff);}
.result-card.incorrect{border-left:6px solid var(--danger);background:linear-gradient(135deg, rgba(239,68,68,.05), #fff);}
.result-header{display:flex;gap:1rem;align-items:flex-start;margin-bottom:1rem;}
.result-number{width:2.5rem;height:2.5rem;border-radius:.5rem;display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));}
.result-text{flex:1;font-weight:700;color:var(--text-primary);line-height:1.4;}
.result-options{margin:1rem 0;}
.result-option{padding:.5rem .75rem;margin:.25rem 0;border-radius:.5rem;font-size:.9rem;}
.result-option.chosen{background:var(--primary-purple);color:white;font-weight:600;}
.result-option.correct{background:var(--success);color:white;font-weight:600;}
.result-option.incorrect{background:var(--danger);color:white;font-weight:600;}
.result-option.neutral{background:var(--gray-100);color:var(--text-muted);}
.result-status{display:flex;align-items:center;gap:.5rem;font-weight:700;font-size:.9rem;margin-bottom:1rem;}
.result-status.correct{color:var(--success);}
.result-status.incorrect{color:var(--danger);}
.result-explanation{margin-top:1rem;padding-top:1rem;border-top:1px dashed var(--border);color:var(--text-secondary);line-height:1.6;}

/* ==============================
   CONTROLS ROW
   ============================== */
.controls-container{display:flex;justify-content:center;gap:.8rem;margin:1.5rem 0;flex-wrap:wrap;}

/* ==============================
   META BAR
   ============================== */
.meta-container{display:flex;justify-content:space-between;align-items:center;background:var(--gray-50);border-top:1px solid var(--border);font-size:.9rem;color:var(--text-muted);}
.meta-left{display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;}
.meta-pill{background:#fff;border:1px solid var(--border);padding:.28rem .75rem;border-radius:9999px;font-weight:700;color:var(--text-secondary);}

/* ==============================
   TOASTS
   ============================== */
.toast-container{position:fixed;top:1rem;right:1rem;display:flex;flex-direction:column;gap:.5rem;z-index:1000;}
.toast{background:#fff;border:1px solid var(--border);color:var(--text-primary);padding:1rem 1.1rem;border-radius:.8rem;font-weight:600;min-width:280px;box-shadow:var(--shadow-lg);animation:slideIn .25s ease;}
.toast.ok{border-left:5px solid var(--success);background:linear-gradient(135deg, rgba(16,185,129,.08), #fff);}
.toast.warn{border-left:5px solid var(--warning);background:linear-gradient(135deg, rgba(245,158,11,.08), #fff);}
.toast.err{border-left:5px solid var(--danger);background:linear-gradient(135deg, rgba(239,68,68,.08), #fff);}
@keyframes slideIn{from{opacity:0;transform:translateX(100%)}to{opacity:1;transform:translateX(0)}}

/* ==============================
   RESPONSIVE
   ============================== */
@media (max-width: 992px){
  .header-container{padding:1.25rem;}
}
@media (max-width: 768px){
  .header-container{flex-direction:column;align-items:stretch;gap:1rem;}
  .stats-grid{width:100%;}
  .edge-pad{padding:1rem;}
  .question-card{padding:1.5rem;}
  .question-options{gap:.5rem;}
  .nav-controls{flex-direction:column;}
  .results-summary{grid-template-columns:1fr;}
}
</style>

<!-- MAIN FULL-BLEED -->
<div class="main-container full-bleed">

  @if($alreadyPassed)
  <div class="edge-pad">
    <div class="card accent" style="margin-bottom: 1rem;">
      <div class="section-title" style="color:var(--primary-purple)">Level Completed</div>
      <p style="margin:0">
        You've already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}.
        You can <a href="{{ route('levels.show', $level) }}?replay=1" style="color:var(--primary-purple);text-decoration:underline;">replay</a> to improve your stars.
      </p>
    </div>
  </div>
  @endif

  <!-- INSTRUCTIONS -->
  <div class="edge-pad">
    <div class="card accent" id="instructionsCard" style="margin-bottom: 1.25rem;">
      <div class="instrux-row">
        <div class="section-title">How to play</div>
        <button class="btn-toggle" type="button" id="toggleInstrux" aria-expanded="true">
          Collapse
        </button>
      </div>
      <div id="instruxBody" class="instructions-text">
        {!! nl2br(e($uiInstrux ?? 'Choose the best answer for each question.')) !!}
        @if($introText)
          <div class="mt-2">{!! nl2br(e($introText)) !!}</div>
        @endif
      </div>
    </div>
  </div>

  <!-- PROGRESS HEADER -->
  <div class="edge-pad">
    <div class="progress-header">
      <div class="progress-row">
        <div class="progress-title">Progress</div>
        <div class="progress-indicator">
          <div class="question-counter" id="questionCounter">Question 1 of {{ count($questions) }}</div>
          <div class="progress-container">
            <div class="progress-bar"><div class="progress-fill" id="progressBar"></div></div>
          </div>
        </div>
      </div>
    </div>

    <!-- QUIZ SECTION -->
    <div id="quizSection" class="question-container">
      <!-- Questions will be rendered here by JS -->
    </div>

    <!-- RESULTS SECTION -->
    <div id="resultsSection" class="results-container d-none">
      <div class="results-header">
        <div class="results-title">Quiz Complete!</div>
        <div class="results-score" id="finalScoreDisplay">0%</div>
        <div class="results-stars" id="finalStarsDisplay"></div>
        <div class="results-summary">
          <div class="summary-item">
            <div class="summary-value" id="correctCount">0</div>
            <div class="summary-label">Correct</div>
          </div>
          <div class="summary-item">
            <div class="summary-value" id="incorrectCount">0</div>
            <div class="summary-label">Incorrect</div>
          </div>
          <div class="summary-item">
            <div class="summary-value" id="hintsUsedDisplay">0</div>
            <div class="summary-label">Hints Used</div>
          </div>
          <div class="summary-item">
            <div class="summary-value" id="timeUsedDisplay">0:00</div>
            <div class="summary-label">Time Used</div>
          </div>
        </div>
      </div>
      
      <div class="results-grid" id="resultsGrid">
        <!-- Results will be rendered here by JS -->
      </div>

    

    <!-- SUBMIT FORM -->
    <form method="POST" action="{{ route('levels.submit', $level) }}" id="scoreForm" style="display:none;">
      @csrf
      <input type="hidden" name="score" id="finalScore" value="0">
      <input type="hidden" name="answers" id="answersPayload" value="">
    </form>
  </div>
</div>
<div style="text-align:center;margin-top:2rem;">
  <button type="button" class="btn btn-primary" id="btnBackToStage">
    <i class="fas fa-arrow-left"></i> Back to Stage
  </button>
</div>
</div>
<!-- META BAR -->
<div class="meta-container full-bleed edge-pad">
  <div class="meta-left">
    <span class="meta-pill">Pass score: {{ (int)$level->pass_score }}%</span>
    @if(!is_null($savedScore))
      <span class="meta-pill">Best: {{ (int)$savedScore }}%</span>
    @endif
    <span class="meta-pill">Stars: <span id="metaStars">0</span></span>
    <span class="meta-pill">Tips used: <span id="hintCount">0</span></span>
  </div>
  <div class="meta-right">
    <span class="meta-pill">Press H for hint, Enter to proceed</span>
  </div>
</div>

<!-- TOASTS -->
<div class="toast-container" id="toastWrap"></div>

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
  // -----------------------------
  // Data
  // -----------------------------
  const data       = window.LEVEL_DATA || {};
  const questions  = Array.isArray(data.questions) ? data.questions : [];
  const hints      = Array.isArray(data.hints) ? data.hints : [];
  const timeLimit  = Number.isFinite(data.time_limit) ? data.time_limit : 300;
  const maxHints   = Number.isFinite(data.max_hints)  ? data.max_hints  : 3;
  const answerKey  = Array.isArray(data.answer_key) ? data.answer_key : [];
  const explanations = Array.isArray(data.explanations) ? data.explanations : [];

  // -----------------------------
  // State
  // -----------------------------
  let currentQuestion = 0;
  let answers = {};          // { [id]: selectedIndex }
  let hintsUsed = 0;
  let submitted = false;
  let timeRemaining = timeLimit;
  let startTime = Date.now();

  // -----------------------------
  // DOM
  // -----------------------------
  const $quizSection    = document.getElementById('quizSection');
  const $resultsSection = document.getElementById('resultsSection');
  const $progress       = document.getElementById('progressBar');
  const $questionCounter = document.getElementById('questionCounter');
  const $timer          = document.getElementById('timeRemaining');
  const $statScore      = document.getElementById('statScore');
  const $statStars      = document.getElementById('statStars');
  const $metaStars      = document.getElementById('metaStars');
  const $hintCount      = document.getElementById('hintCount');
  const $toastWrap      = document.getElementById('toastWrap');

  const $toggleInstrux = document.getElementById('toggleInstrux');
  const $instruxBody   = document.getElementById('instruxBody');

  // -----------------------------
  // Helpers
  // -----------------------------
  function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
  function toast(msg, kind='ok'){
    const el = document.createElement('div');
    el.className = `toast ${kind}`;
    el.textContent = msg;
    $toastWrap.appendChild(el);
    setTimeout(()=>el.remove(), 2400);
  }
  function starsFor(score){ if(score>=90) return 3; if(score>=70) return 2; if(score>=50) return 1; return 0; }
  function fmtTime(sec){ const m = String(Math.floor(sec/60)).padStart(2,'0'); const s = String(sec%60).padStart(2,'0'); return `${m}:${s}`; }
  
  function updateProgress(){
    const pct = questions.length ? Math.round(100 * (currentQuestion + 1) / questions.length) : 0;
    $progress.style.width = pct + '%';
    $questionCounter.textContent = `Question ${currentQuestion + 1} of ${questions.length}`;
  }

  function updateStats(){
    const answeredCount = Object.keys(answers).length;
    const pct = questions.length ? Math.round(100 * answeredCount / questions.length) : 0;
    $statScore.textContent = pct + '%';
  }

  // -----------------------------
  // Build question UI
  // -----------------------------
  function renderCurrentQuestion(){
    if(currentQuestion >= questions.length) return;
    
    const q = questions[currentQuestion];
    const isAnswered = answers.hasOwnProperty(currentQuestion);
    const userAnswer = answers[currentQuestion];

    $quizSection.innerHTML = `
      <div class="question-card">
        <div class="question-header">
          <div class="question-number">${currentQuestion + 1}</div>
          <div class="question-text">${escapeHtml(q.question || '')}</div>
        </div>
        ${q.code ? `<pre class="question-code">${escapeHtml(q.code)}</pre>` : ''}
        <div class="question-options">
          ${(q.options || []).map((option, index) => `
            <div class="option-item">
              <input type="radio" id="q${currentQuestion}_${index}" name="q${currentQuestion}" value="${index}" ${userAnswer === index ? 'checked' : ''}>
              <label for="q${currentQuestion}_${index}">${escapeHtml(option)}</label>
            </div>
          `).join('')}
        </div>
        <div class="nav-controls">
          <button type="button" class="btn btn-nav" id="btnPrev" ${currentQuestion === 0 ? 'disabled' : ''}>
            <i class="fas fa-chevron-left"></i> Previous
          </button>
          <div style="display:flex;gap:0.5rem;">
            <button type="button" class="btn btn-warning" id="btnHint">
              <i class="fas fa-lightbulb"></i> Hint
            </button>
            ${isAnswered ? 
              (currentQuestion === questions.length - 1 ? 
                '<button type="button" class="btn btn-primary" id="btnFinish"><i class="fas fa-flag-checkered"></i> Finish level</button>' :
                '<button type="button" class="btn btn-primary" id="btnNext"><i class="fas fa-chevron-right"></i> Next</button>'
              ) : 
              '<button type="button" class="btn btn-secondary" disabled>Choose an answer</button>'
            }
          </div>
        </div>
      </div>
    `;

    // Add event listeners
    const radioButtons = $quizSection.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(radio => {
      radio.addEventListener('change', () => selectAnswer(+radio.value));
    });

    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const btnFinish = document.getElementById('btnFinish');
    const btnHint = document.getElementById('btnHint');

    if(btnPrev) btnPrev.addEventListener('click', () => navigateToQuestion(currentQuestion - 1));
    if(btnNext) btnNext.addEventListener('click', () => navigateToQuestion(currentQuestion + 1));
    if(btnFinish) btnFinish.addEventListener('click', showResults);
    if(btnHint) btnHint.addEventListener('click', showHint);
  }

  function selectAnswer(val){
    if(submitted) return;
    
    answers[currentQuestion] = val;
    updateStats();
    
    // Re-render to show next/finish button
    setTimeout(renderCurrentQuestion, 100);
  }

  function navigateToQuestion(index){
    if(index < 0 || index >= questions.length) return;
    currentQuestion = index;
    renderCurrentQuestion();
    updateProgress();
  }

  function showHint(){
    if(submitted) return;
    if(hintsUsed >= maxHints) return toast('No more hints available.', 'warn');
    
    hintsUsed++;
    $hintCount.textContent = hintsUsed;
    const hint = hints.length ? hints[(hintsUsed-1) % hints.length] : 'Think about the question carefully and consider all options.';
    toast('Hint: ' + hint, 'ok');
  }

  // -----------------------------
  // Results display
  // -----------------------------
function showResults(){
  if(Object.keys(answers).length !== questions.length){
    return toast('Please answer all questions first.', 'warn');
  }

  submitted = true;
  clearInterval(timerInterval);
  
  // Calculate score
  let correct = 0;
  questions.forEach((q, i) => {
    const chosen = answers[i];              // User's selected option index
    const correctAnswer = answerKey[i];     // Correct option index
    if(chosen === correctAnswer) correct++;
  });

  const rawPct = Math.round(100 * correct / questions.length);
  const hintPenalty = hintsUsed * 5;
  let finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));
  finalScore = Math.min(100, finalScore + Math.max(0, Math.floor(timeRemaining / 10)));

  const timeUsed = timeLimit - timeRemaining;
  const stars = starsFor(finalScore);
  const starIcons = stars ? '★'.repeat(stars) : '0';

  // Update header stats
  $statScore.textContent = finalScore + '%';
  $statStars.textContent = starIcons;
  if($metaStars) $metaStars.textContent = starIcons;

  // Show results section
  $quizSection.classList.add('d-none');
  $resultsSection.classList.remove('d-none');

  // Update results header
  document.getElementById('finalScoreDisplay').textContent = finalScore + '%';
  document.getElementById('finalStarsDisplay').textContent = starIcons;
  document.getElementById('correctCount').textContent = correct;
  document.getElementById('incorrectCount').textContent = questions.length - correct;
  document.getElementById('hintsUsedDisplay').textContent = hintsUsed;
  document.getElementById('timeUsedDisplay').textContent = fmtTime(timeUsed);

  // Render individual results
  const resultsGrid = document.getElementById('resultsGrid');
  resultsGrid.innerHTML = '';

  questions.forEach((q, i) => {
    const chosen = answers[i];
    const correctAnswer = answerKey[i];
    const isCorrect = chosen === correctAnswer;

    const resultCard = document.createElement('div');
    resultCard.className = `result-card ${isCorrect ? 'correct' : 'incorrect'}`;
    
    // Build options display
    const optionsHtml = (q.options || []).map((option, optIndex) => {
      let className = 'result-option neutral';
      if(optIndex === chosen && optIndex === correctAnswer) {
        className = 'result-option correct';
      } else if(optIndex === chosen) {
        className = 'result-option incorrect';
      } else if(optIndex === correctAnswer) {
        className = 'result-option correct';
      }
      
      let prefix = '';
      if(optIndex === chosen) prefix = 'Your answer: ';
      if(optIndex === correctAnswer && optIndex !== chosen) prefix = 'Correct answer: ';
      
      return `<div class="${className}">${prefix}${escapeHtml(option)}</div>`;
    }).join('');
    
    resultCard.innerHTML = `
      <div class="result-header">
        <div class="result-number">${i + 1}</div>
        <div class="result-text">${escapeHtml(q.question || '')}</div>
      </div>
      ${q.code ? `<pre class="question-code">${escapeHtml(q.code)}</pre>` : ''}
      <div class="result-status ${isCorrect ? 'correct' : 'incorrect'}">
        <i class="fas fa-${isCorrect ? 'check-circle' : 'times-circle'}"></i>
        ${isCorrect ? 'Correct' : 'Incorrect'}
      </div>
      <div class="result-options">
        ${optionsHtml}
      </div>
      <div class="result-explanation">
        <strong>Explanation:</strong> ${escapeHtml(explanations[i] || (isCorrect ? 'Well done!' : 'Review the question and options carefully.'))}
      </div>
    `;
    
    resultsGrid.appendChild(resultCard);
  });

  // Prepare score data but DON'T auto-submit
  document.getElementById('finalScore').value = finalScore;
  document.getElementById('answersPayload').value = JSON.stringify(answers);

  const passReq = {{ (int)$level->pass_score }};
  toast(finalScore >= passReq ? `Excellent! Score ${finalScore}%` : `Score ${finalScore}%. Keep practicing!`, finalScore >= passReq ? 'ok' : 'err');

  // Submit score to server in background
  //const form = document.getElementById('scoreForm');
  //if(form.requestSubmit) form.requestSubmit(); else form.submit();

  // Add back button event listener
  setTimeout(() => {
    const btnBackToStage = document.getElementById('btnBackToStage');
    if(btnBackToStage){
      btnBackToStage.addEventListener('click', () => {
         const form = document.getElementById('scoreForm');
      if(form.requestSubmit) form.requestSubmit(); else form.submit();
      });
    }
  }, 100);
}

  // -----------------------------
  // Instructions collapse
  // -----------------------------
  $toggleInstrux.addEventListener('click', () => {
    const hidden = $instruxBody.classList.toggle('d-none');
    $toggleInstrux.textContent = hidden ? 'Expand' : 'Collapse';
    $toggleInstrux.setAttribute('aria-expanded', String(!hidden));
  });

  // -----------------------------
  // Timer
  // -----------------------------
  $timer.textContent = fmtTime(timeRemaining);
  const timerInterval = setInterval(() => {
    timeRemaining--;
    $timer.textContent = fmtTime(timeRemaining);
    if([60, 30, 10].includes(timeRemaining)) toast(`${timeRemaining}s remaining`, 'warn');
    if(timeRemaining <= 0){
      clearInterval(timerInterval);
      if(!submitted){ 
        toast('Time up! Submitting your current answers...', 'warn'); 
        showResults(); 
      }
    }
  }, 1000);

  // -----------------------------
  // Keyboard shortcuts
  // -----------------------------
  document.addEventListener('keydown', (e) => {
    if(submitted) return;
    
    if(e.key.toLowerCase() === 'h'){ 
      e.preventDefault(); 
      showHint(); 
    }
    if(e.key === 'Enter'){ 
      e.preventDefault(); 
      const isAnswered = answers.hasOwnProperty(currentQuestion);
      if(isAnswered){
        if(currentQuestion === questions.length - 1){
          showResults();
        } else {
          navigateToQuestion(currentQuestion + 1);
        }
      }
    }
    if(e.key === 'ArrowLeft'){ 
      e.preventDefault(); 
      if(currentQuestion > 0) navigateToQuestion(currentQuestion - 1); 
    }
    if(e.key === 'ArrowRight'){ 
      e.preventDefault(); 
      const isAnswered = answers.hasOwnProperty(currentQuestion);
      if(isAnswered && currentQuestion < questions.length - 1) navigateToQuestion(currentQuestion + 1); 
    }
    // Number keys for quick option selection
    if(e.key >= '1' && e.key <= '9'){ 
      e.preventDefault(); 
      const optionIndex = parseInt(e.key) - 1;
      const q = questions[currentQuestion];
      if(q && q.options && optionIndex < q.options.length) {
        selectAnswer(optionIndex);
      }
    }
  });
const btnBackToStage = document.getElementById('btnBackToStage');
if(btnBackToStage){
  btnBackToStage.addEventListener('click', () => {
    window.location.href = "{{ route('stages.show', $level->stage_id) }}";
  });
}
  // -----------------------------
  // Initialize
  // -----------------------------
  function init(){
    if(questions.length === 0){
      toast('No questions available.', 'err');
      return;
    }
    
    renderCurrentQuestion();
    updateProgress();
    updateStats();
  }

  init();
})();
</script>
</x-app-layout>
      