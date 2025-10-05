<x-app-layout>
@php
    // ----------------------------------
    // Normalize content coming from DB
    // ----------------------------------
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    $content      = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $intro        = $content['intro'] ?? null;
    $instructions = $content['instructions'] ?? null;
    $questionsRaw = $content['questions'] ?? [];
    $hints        = $content['hints'] ?? [];
    $timeLimit    = (int)($content['time_limit'] ?? 300);
    $maxHints     = (int)($content['max_hints']  ?? 3);

    // Mixed True/False and Code items -> normalize for JS
    $questions = [];
    foreach ($questionsRaw as $i => $q) {
        $type = $q['type'] ?? 'tf'; // default to true/false if not specified
        if ($type === 'code') {
            $questions[] = [
                'id'              => $i + 1,
                'type'            => 'code',
                'question'        => $q['question'] ?? '',
                'starter_code'    => $q['starter_code'] ?? '',
                'expected_output' => $q['expected_output'] ?? '',
                'explanation'     => $q['explanation'] ?? '',
            ];
        } else {
            $questions[] = [
                'id'          => $i + 1,
                'type'        => 'tf',
                'text'        => $q['statement']   ?? '',
                'code'        => $q['code']        ?? null,
                'correct'     => (bool)($q['answer'] ?? false),
                'explanation' => $q['explanation'] ?? '',
            ];
        }
    }

    $payload = [
        'questions'  => $questions,
        'hints'      => $hints,
        'time_limit' => $timeLimit,
        'max_hints'  => $maxHints,
];
@endphp

<script>
  window.LEVEL_DATA = {!! json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
</script>

<x-slot name="header">
  <!-- PAGE HEADER -->
  <div class="level-header">
    <div class="header-container">
      <div class="header-left">
        <div class="level-badge"><span class="level-number">{{ $level->index }}</span></div>
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
/* ==============================
   THEME TOKENS
   ============================== */
:root{
  --primary-purple:#7c3aed;
  --secondary-purple:#a855f7;
  --light-purple:#c084fc;
  --purple-subtle:#f3e8ff;

  --gray-50:#f8fafc;
  --gray-100:#f1f5f9;
  --gray-200:#e2e8f0;
  --gray-300:#cbd5e1;
  --gray-400:#94a3b8;
  --gray-500:#64748b;
  --gray-600:#475569;
  --gray-700:#334155;
  --gray-800:#1e293b;
  --gray-900:#0f172a;

  --success:#10b981;
  --warning:#f59e0b;
  --danger:#ef4444;

  --background:#ffffff;
  --border:#e2e8f0;
  --text-primary:#1e293b;
  --text-secondary:#475569;
  --text-muted:#64748b;

  --shadow-sm:0 1px 2px rgba(0,0,0,.05);
  --shadow:0 1px 3px rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
  --shadow-md:0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
  --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);

  /* Code editor */
  --code-bg: #0f172a;
  --code-fg: #cfeaff;
  --code-border: rgba(255,255,255,.08);
  --code-out-bg: #0b1222;
  --code-out-fg: #e5f0ff;
}

/* Base page background */
body{
  background:linear-gradient(135deg, rgba(124,58,237,.03) 0%, rgba(168,85,247,.02) 50%, rgba(248,250,252,1) 100%);
  color:var(--text-primary);
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI','Roboto','Helvetica Neue',Arial,sans-serif;
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
.question-code{background:var(--code-bg);color:var(--code-fg);border:1px solid var(--code-border);border-radius:.75rem;padding:1rem 1.25rem;margin:1rem 0;white-space:pre-wrap;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;font-size:.95rem;overflow-x:auto;line-height:1.4;}
.question-actions{display:flex;gap:1rem;justify-content:center;margin-top:2rem;flex-wrap:wrap;}

/* ==============================
   BUTTONS
   ============================== */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.75rem 1.25rem;border:none;border-radius:.8rem;font-weight:700;font-size:1rem;cursor:pointer;transition:transform .12s ease, box-shadow .2s ease, filter .12s ease;min-width:120px;}
.btn:disabled{opacity:.55;cursor:not-allowed;}
.btn-true{background:linear-gradient(135deg,#10b981,#34d399);color:#fff;box-shadow:var(--shadow-sm);}
.btn-false{background:linear-gradient(135deg,#ef4444,#f59e0b);color:#fff;box-shadow:var(--shadow-sm);}
.btn-primary{background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));color:#fff;box-shadow:var(--shadow);}
.btn-secondary{background:var(--gray-100);color:var(--text-primary);border:1px solid var(--border);}
.btn-warning{background:linear-gradient(135deg,#f59e0b,#fbbf24);color:#fff;}
.btn-danger{background:linear-gradient(135deg,#ef4444,#f43f5e);color:#fff;}
.btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:var(--shadow-lg);}
.btn.selected{outline:3px solid rgba(124,58,237,.35);transform:translateY(-2px);}

/* Navigation buttons */
.nav-controls{display:flex;justify-content:space-between;gap:1rem;margin-top:2rem;}
.btn-nav{background:var(--gray-100);color:var(--text-primary);border:1px solid var(--border);min-width:100px;}

/* ==============================
   CODE EDITOR (for code questions)
   ============================== */
.code-editor-container{margin-top:1rem;border:1px solid var(--border);border-radius:.75rem;overflow:hidden;background:var(--gray-50);}
.code-editor-header{display:flex;justify-content:space-between;align-items:center;padding:.75rem 1rem;background:var(--gray-100);border-bottom:1px solid var(--border);}
.code-editor-title{font-weight:700;color:var(--text-secondary);font-size:.9rem;}
.code-editor-actions{display:flex;gap:.5rem;}
.code-editor-textarea{width:100%;min-height:160px;padding:1rem;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;font-size:.95rem;line-height:1.5;background:var(--code-bg);color:var(--code-fg);border:none;resize:vertical;outline:none;}

.code-output-container{margin-top:1rem;border:1px solid var(--border);border-radius:.75rem;overflow:hidden;}
.code-output-header{display:flex;justify-content:space-between;align-items:center;padding:.75rem 1rem;background:var(--gray-100);border-bottom:1px solid var(--border);}
.code-output-title{font-weight:700;color:var(--text-secondary);font-size:.9rem;}
.code-output-content{padding:1rem;min-height:110px;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;font-size:.9rem;line-height:1.5;background:var(--code-out-bg);color:var(--code-out-fg);white-space:pre-wrap;overflow-x:auto;}

.code-status{display:inline-block;padding:.25rem .5rem;border-radius:.35rem;font-size:.75rem;font-weight:700;margin-left:.5rem;}
.code-status.success{background:#dcfce7;color:#065f46;}
.code-status.error{background:#fee2e2;color:#991b1b;}

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
.result-status{display:flex;align-items:center;gap:.5rem;font-weight:700;font-size:.9rem;}
.result-status.correct{color:var(--success);}
.result-status.incorrect{color:var(--danger);}
.result-explanation{margin-top:1rem;padding-top:1rem;border-top:1px dashed var(--border);color:var(--text-secondary);line-height:1.6;}

.code-result-container{margin-top:1rem;border:1px solid var(--border);border-radius:.75rem;overflow:hidden;}
.code-result-header{display:flex;justify-content:space-between;align-items:center;padding:.75rem 1rem;background:var(--gray-100);border-bottom:1px solid var(--border);}
.code-result-content{padding:1rem;background:var(--code-bg);color:var(--code-fg);white-space:pre-wrap;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;}
.code-output-result{padding:1rem;background:#0d1528;border-top:1px solid var(--border);color:#eaf2ff;white-space:pre-wrap;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;}

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
   META BAR
   ============================== */
.meta-container{display:flex;justify-content:space-between;align-items:center;background:var(--gray-50);border-top:1px solid var(--border);font-size:.9rem;color:var(--text-muted);}
.meta-left{display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;}
.meta-pill{background:#fff;border:1px solid var(--border);padding:.28rem .75rem;border-radius:9999px;font-weight:700;color:var(--text-secondary);}

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
  .question-actions{flex-direction:column;}
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
        {!! nl2br(e($instructions ?? 'Read each statement (and code if any), choose TRUE or FALSE. Some questions will ask you to type Python code and run it.')) !!}
        
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
      <!-- Questions render here by JS -->
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
      
      <div class="results-grid" id="resultsGrid"></div>

      <div style="text-align:center;margin-top:2rem;">
        <button type="button" class="btn btn-primary" id="btnBackToStage">
          <i class="fas fa-arrow-left"></i> Back to Stage
        </button>
      </div>
    </div>

    <!-- CSRF TOKEN for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<!-- Python Interpreter (Skulpt) -->
<script src="https://cdn.jsdelivr.net/npm/skulpt@1.2.0/dist/skulpt.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/skulpt@1.2.0/dist/skulpt-stdlib.js"></script>

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

  // -----------------------------
  // State
  // -----------------------------
  let currentQuestion = 0;
  // answers:
  //  - for TF: { [id]: 0|1 }
  //  - for CODE: { [id]: { code:string, output:string } }
  let answers = {};
  // Cache per-question last output text for code items
  let codeOutputs = {};
  let hintsUsed = 0;
  let submitted = false;
  let timeRemaining = timeLimit;

  // -----------------------------
  // DOM
  // -----------------------------
  const $quizSection     = document.getElementById('quizSection');
  const $resultsSection  = document.getElementById('resultsSection');
  const $progress        = document.getElementById('progressBar');
  const $questionCounter = document.getElementById('questionCounter');
  const $timer           = document.getElementById('timeRemaining');
  const $statScore       = document.getElementById('statScore');
  const $statStars       = document.getElementById('statStars');
  const $metaStars       = document.getElementById('metaStars');
  const $hintCount       = document.getElementById('hintCount');
  const $toastWrap       = document.getElementById('toastWrap');

  const $toggleInstrux   = document.getElementById('toggleInstrux');
  const $instruxBody     = document.getElementById('instruxBody');

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
  function normalizeOutput(s){ return String(s ?? '').replace(/\r\n/g,'\n').trim(); }

  // Skulpt runner
  function runPython(code, outputEl){
    outputEl.textContent = '';
    Sk.configure({
      output: txt => { outputEl.textContent += txt; },
      read: function(name){
        if (Sk.builtinFiles === undefined || Sk.builtinFiles["files"][name] === undefined) {
          throw "File not found: '" + name + "'";
        }
        return Sk.builtinFiles["files"][name];
      }
    });
    try{
      Sk.importMainWithBody("<stdin>", false, code, true);
      return true;
    }catch(e){
      outputEl.textContent = e.toString();
      return false;
    }
  }

  // -----------------------------
  // Render current question
  // -----------------------------
  function renderCurrentQuestion(){
    if(currentQuestion >= questions.length) return;

    const q = questions[currentQuestion];
    const isTF   = q.type !== 'code';
    const isAns  = answers.hasOwnProperty(q.id);
    const lastOut = codeOutputs[q.id] || '';

    if (isTF){
      // TRUE/FALSE
      const userVal = answers[q.id];
      $quizSection.innerHTML = `
        <div class="question-card">
          <div class="question-header">
            <div class="question-number">${currentQuestion + 1}</div>
            <div class="question-text">${escapeHtml(q.text || '')}</div>
          </div>
          ${q.code ? `<pre class="question-code">${escapeHtml(q.code)}</pre>` : ''}
          <div class="question-actions">
            <button type="button" class="btn btn-true ${userVal === 1 ? 'selected' : ''}" data-val="1">
              <i class="fas fa-check"></i> TRUE
            </button>
            <button type="button" class="btn btn-false ${userVal === 0 ? 'selected' : ''}" data-val="0">
              <i class="fas fa-times"></i> FALSE
            </button>
          </div>
          <div class="nav-controls">
            <button type="button" class="btn btn-nav" id="btnPrev" ${currentQuestion === 0 ? 'disabled' : ''}>
              <i class="fas fa-chevron-left"></i> Previous
            </button>
            <div style="display:flex;gap:.5rem;">
              <button type="button" class="btn btn-warning" id="btnHint"><i class="fas fa-lightbulb"></i> Hint</button>
              <button type="button" class="btn btn-primary" id="btnNext" ${isAns ? '' : 'disabled'}>
                ${currentQuestion === questions.length - 1 ? 'See results' : 'Next'} <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      `;

      const btns = $quizSection.querySelectorAll('.btn-true, .btn-false');
      btns.forEach(b => b.addEventListener('click', () => selectTF(+b.dataset.val)));

      document.getElementById('btnPrev')?.addEventListener('click', ()=> navigateTo(currentQuestion - 1));
      document.getElementById('btnNext')?.addEventListener('click', ()=>{
        if (currentQuestion === questions.length - 1) showResults();
        else navigateTo(currentQuestion + 1);
      });
      document.getElementById('btnHint')?.addEventListener('click', showHint);

    } else {
      // CODE
      const userObj = answers[q.id]; // { code, output } or undefined
      $quizSection.innerHTML = `
        <div class="question-card">
          <div class="question-header">
            <div class="question-number">${currentQuestion + 1}</div>
            <div class="question-text">${escapeHtml(q.question || '')}</div>
          </div>

          <div class="code-editor-container">
            <div class="code-editor-header">
              <div class="code-editor-title">Write your Python code:</div>
              <div class="code-editor-actions">
                <button type="button" class="btn btn-success" id="btnRun"><i class="fas fa-play"></i> Run Code</button>
                <button type="button" class="btn btn-secondary" id="btnReset"><i class="fas fa-undo"></i> Reset</button>
              </div>
            </div>
            <textarea class="code-editor-textarea" id="codeEditor" placeholder="Write your Python code here...">${escapeHtml(userObj?.code ?? q.starter_code ?? '')}</textarea>
          </div>

          <div class="code-output-container">
            <div class="code-output-header">
              <div class="code-output-title">Output:</div>
              <div id="outputStatus"></div>
            </div>
            <div class="code-output-content" id="codeOut">${escapeHtml(lastOut)}</div>
          </div>

          <div class="nav-controls">
            <button type="button" class="btn btn-nav" id="btnPrev" ${currentQuestion === 0 ? 'disabled' : ''}>
              <i class="fas fa-chevron-left"></i> Previous
            </button>
            <div style="display:flex;gap:.5rem;">
              <button type="button" class="btn btn-warning" id="btnHint"><i class="fas fa-lightbulb"></i> Hint</button>
              ${
                isAns
                  ? `<button type="button" class="btn btn-primary" id="btnNext">
                       ${currentQuestion === questions.length - 1 ? 'See results' : 'Next'} <i class="fas fa-chevron-right"></i>
                     </button>`
                  : `<button type="button" class="btn btn-secondary" id="btnSubmitCode"><i class="fas fa-check"></i> Submit Code</button>
                     <button type="button" class="btn btn-primary" id="btnNext" disabled>
                       ${currentQuestion === questions.length - 1 ? 'See results' : 'Next'} <i class="fas fa-chevron-right"></i>
                     </button>`
              }
            </div>
          </div>
        </div>
      `;

      const btnRun   = document.getElementById('btnRun');
      const btnReset = document.getElementById('btnReset');
      const editor   = document.getElementById('codeEditor');
      const outEl    = document.getElementById('codeOut');
      const statusEl = document.getElementById('outputStatus');
      const btnSubmit= document.getElementById('btnSubmitCode');
      const btnNext  = document.getElementById('btnNext');

      function checkBadge(){
        const exp = String(q.expected_output ?? '');
        if (!exp){ statusEl.innerHTML = ''; return; }
        const ok = normalizeOutput(codeOutput.textContent).toLowerCase() === normalizeOutput(expected).toLowerCase();
        statusEl.innerHTML = ok
          ? '<span class="code-status success">Matches expected ✓</span>'
          : '<span class="code-status error">Does not match ✗</span>';
        return ok;
      }

      btnRun?.addEventListener('click', ()=>{
        runPython(editor.value, outEl);
        codeOutputs[q.id] = outEl.textContent;
        checkBadge();
      });
      btnReset?.addEventListener('click', ()=>{
        editor.value = q.starter_code ?? '';
        outEl.textContent = '';
        statusEl.innerHTML = '';
      });
      btnSubmit?.addEventListener('click', ()=>{
        answers[q.id] = { code: editor.value, output: outEl.textContent };
        updateStats();
        if (btnNext) btnNext.disabled = false;
        btnSubmit.style.display = 'none';
        toast('Code submitted. You can proceed.', 'ok');
      });

      document.getElementById('btnPrev')?.addEventListener('click', ()=> navigateTo(currentQuestion - 1));
      btnNext?.addEventListener('click', ()=>{
        if (currentQuestion === questions.length - 1) showResults();
        else navigateTo(currentQuestion + 1);
      });
      document.getElementById('btnHint')?.addEventListener('click', showHint);
    }
  }

  function selectTF(val){
    if(submitted) return;
    const q = questions[currentQuestion];
    answers[q.id] = val;
    updateStats();
    setTimeout(renderCurrentQuestion, 100);
  }

  function navigateTo(index){
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
    const hint = hints.length ? hints[(hintsUsed-1) % hints.length] : 'Think carefully about the statement/code.';
    toast('Hint: ' + hint, 'ok');
  }

  // -----------------------------
  // Results (no auto-submit anywhere)
  // -----------------------------
  function showResults(){
    if(Object.keys(answers).length !== questions.length){
      return toast('Please answer all questions first.', 'warn');
    }

    submitted = true;
    clearInterval(timerInterval);

    // Marking
    let correct = 0;
    questions.forEach(q => {
      if (q.type === 'code'){
        const u = answers[q.id];
        if (u && typeof u.output === 'string'){
          if (normalizeOutput(u.output) === normalizeOutput(q.expected_output || '')) correct++;
        }
      } else {
        const chosen = answers[q.id];          // 0|1
        const truth  = q.correct ? 1 : 0;      // 0|1
        if(chosen === truth) correct++;
      }
    });

 const rawPct = Math.round(100 * correct / questions.length);
const hintPenalty = hintsUsed * 5;
let finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));
// Time bonus is percentage of remaining time, max 3 points
const timeBonus = Math.min(3, (timeRemaining / timeLimit) * 3);
finalScore = Math.min(100, Math.floor(finalScore + timeBonus));

    const stars = starsFor(finalScore);
    const starIcons = stars ? '★'.repeat(stars) : '0';
    const passReq = {{ (int)$level->pass_score }};
    const passed = finalScore >= passReq;

    // Update header stats
    $statScore.textContent = finalScore + '%';
    $statStars.textContent = starIcons;
    if($metaStars) $metaStars.textContent = starIcons;

    // Show results section
    $quizSection.classList.add('d-none');
    $resultsSection.classList.remove('d-none');

    // Header summary
    document.getElementById('finalScoreDisplay').textContent = finalScore + '%';
    document.getElementById('finalStarsDisplay').textContent = starIcons;
    document.getElementById('correctCount').textContent = correct;
    document.getElementById('incorrectCount').textContent = questions.length - correct;
    const timeUsed = (Number.isFinite(timeLimit) ? timeLimit : 0) - timeRemaining;
    document.getElementById('hintsUsedDisplay').textContent = hintsUsed;
    document.getElementById('timeUsedDisplay').textContent = fmtTime(Math.max(0, timeUsed));

    // Per-question results
    const resultsGrid = document.getElementById('resultsGrid');
    resultsGrid.innerHTML = '';
    questions.forEach((q, i) => {
      let card;
      if (q.type === 'code'){
        const u = answers[q.id] || {};
        const ok = normalizeOutput(u.output || '') === normalizeOutput(q.expected_output || '');
        card = document.createElement('div');
        card.className = `result-card ${ok ? 'correct' : 'incorrect'}`;
        card.innerHTML = `
          <div class="result-header">
            <div class="result-number">${i + 1}</div>
            <div class="result-text">${escapeHtml(q.question || '')}</div>
          </div>
          <div class="result-status ${ok ? 'correct' : 'incorrect'}">
            <i class="fas fa-${ok ? 'check-circle' : 'times-circle'}"></i>
            ${ok ? 'Correct' : 'Incorrect'}
          </div>
          <div class="code-result-container">
            <div class="code-result-header"><strong>Your Code</strong></div>
            <div class="code-result-content">${escapeHtml(u.code || '')}</div>
            <div class="code-output-result">Output:\n${escapeHtml(u.output || '')}</div>
          </div>
          <div class="result-explanation">
            <strong>Explanation:</strong> ${escapeHtml(q.explanation || (ok ? 'Well done!' : 'Review the prompt and expected output.'))}
          </div>
        `;
      } else {
        const chosen = answers[q.id];
        const truth  = q.correct ? 1 : 0;
        const ok     = chosen === truth;
        const chosenText = chosen === 1 ? 'TRUE' : 'FALSE';
        const correctText = truth === 1 ? 'TRUE' : 'FALSE';
        card = document.createElement('div');
        card.className = `result-card ${ok ? 'correct' : 'incorrect'}`;
        card.innerHTML = `
          <div class="result-header">
            <div class="result-number">${i + 1}</div>
            <div class="result-text">${escapeHtml(q.text || '')}</div>
          </div>
          ${q.code ? `<pre class="question-code">${escapeHtml(q.code)}</pre>` : ''}
          <div class="result-status ${ok ? 'correct' : 'incorrect'}">
            <i class="fas fa-${ok ? 'check-circle' : 'times-circle'}"></i>
            ${ok ? 'Correct' : 'Incorrect'} — You answered: ${chosenText}${ok ? '' : ` (Correct: ${correctText})`}
          </div>
          <div class="result-explanation">
            <strong>Explanation:</strong> ${escapeHtml(q.explanation || (ok ? 'Nice!' : 'Re-check the code and statement.'))}
          </div>
        `;
      }
      resultsGrid.appendChild(card);
    });

    toast(passed ? `Excellent! Score ${finalScore}%` : `Score ${finalScore}%. Keep practicing!`, passed ? 'ok' : 'err');

    // Persist via AJAX
    saveProgress(finalScore, answers, hintsUsed, timeUsed, stars, questions.length, correct, passed);
  }

  function saveProgress(finalScore, answers, hintsUsed, timeUsed, stars, totalQuestions, correct, passed){
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('score', finalScore);
    formData.append('answers', JSON.stringify(answers));
    formData.append('hints_used', hintsUsed);
    formData.append('time_used', timeUsed);
    formData.append('stars', stars);
    formData.append('total_questions', totalQuestions);
    formData.append('correct_questions', correct);
    formData.append('passed', passed ? 1 : 0);

    fetch('{{ route("levels.submit", $level) }}', {
      method: 'POST',
      body: formData,
      headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
    })
    .then(r => r.ok ? r.json() : r.text().then(t => { throw new Error(`HTTP ${r.status}: ${t}`); }))
    .then(json => {
      if (json?.success) toast('Progress saved successfully!', 'ok');
      else throw new Error(json?.message || 'Unknown error');
    })
    .catch(err => {
      console.error(err);
      toast('Warning: Progress may not have been saved. Please try again.', 'warn');
    });
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
  // Timer (NO auto-submit)
  // -----------------------------
  $timer.textContent = fmtTime(timeRemaining);
  const timerInterval = setInterval(() => {
    timeRemaining--;
    $timer.textContent = fmtTime(Math.max(0, timeRemaining));
    if([60, 30, 10].includes(timeRemaining)) toast(`${timeRemaining}s remaining`, 'warn');
    if(timeRemaining <= 0){
      clearInterval(timerInterval);
      toast('⏰ Time is up. You can still finish, but time bonus is gone.', 'warn');
      // No auto-submit, no forced lock — user may still complete and submit manually.
    }
  }, 1000);

  // -----------------------------
  // Keyboard shortcuts
  // -----------------------------
  document.addEventListener('keydown', (e) => {
    if(submitted) return;

    // H for hint
    //if(e.key.toLowerCase() === 'h'){ e.preventDefault(); showHint(); return; }

    const q = questions[currentQuestion];
    if (!q) return;

    // For TF: Enter -> advance / results
    if(e.key === 'Enter' && q.type !== 'code'){
      e.preventDefault();
      const answered = answers.hasOwnProperty(q.id);
      if(!answered) return;
      if(currentQuestion === questions.length - 1) showResults();
      else navigateTo(currentQuestion + 1);
      return;
    }

    // Arrows
    if(e.key === 'ArrowLeft'){ e.preventDefault(); if(currentQuestion > 0) navigateTo(currentQuestion - 1); }
    if(e.key === 'ArrowRight'){
      e.preventDefault();
      const answered = answers.hasOwnProperty(q.id);
      if(answered && currentQuestion < questions.length - 1) navigateTo(currentQuestion + 1);
    }

    // Quick TF keys
    if(q.type !== 'code'){
      if(e.key === '1' || e.key.toLowerCase() === 't'){ e.preventDefault(); selectTF(1); }
      if(e.key === '0' || e.key.toLowerCase() === 'f'){ e.preventDefault(); selectTF(0); }
    }
  });

  // Back to stage
  document.getElementById('btnBackToStage')?.addEventListener('click', ()=>{
    window.location.href = "{{ route('stages.show', $level->stage_id) }}";
  });

  // -----------------------------
  // Init
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
