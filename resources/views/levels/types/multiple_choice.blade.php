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

    // Build answer key & explanations arrays for JS (MC questions only)
    $answerKeyJs    = array_map(fn($q) => $q['correct_answer'] ?? null, $questions);
    $explanationsJs = array_map(fn($q) => $q['explanation'] ?? '', $questions);

    $payload = [
        'questions'    => $questions,
        'hints'        => $hintsForJs,
        'time_limit'   => $timeLimit,
        'max_hints'    => $maxHints,
        'answer_key'   => $answerKeyJs,
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

    /* Code editor colors */
    --code-bg: #0f172a;
    --code-text: #cfeaff;
    --code-border: rgba(255,255,255,.08);
}

* { box-sizing: border-box; }

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
.progress-container{flex:1;max-width:280px;min-width:150px;}
.progress-bar{height:.55rem;background:var(--gray-200);border-radius:.35rem;overflow:hidden;box-shadow:inset 0 0 0 1px rgba(0,0,0,.02);}
.progress-fill{height:100%;width:0;background:linear-gradient(90deg,var(--primary-purple),var(--secondary-purple));border-radius:.35rem;transition:width .3s ease;}
.question-counter{font-size:.9rem;color:var(--text-muted);font-weight:600;}
.question-text {
    white-space: pre-wrap;
}
/* ==============================
   QUESTION CARD
   ============================== */
.question-container{max-width:800px;margin:0 auto;}
.question-card{background:#fff;border:1px solid var(--border);border-radius:1rem;padding:2rem;box-shadow:var(--shadow-sm);margin-bottom:1.5rem;}
.question-header{display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.5rem;}
.question-number{width:3rem;height:3rem;border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));font-size:1.25rem;}
.question-text{flex:1;font-size:1.125rem;font-weight:700;color:var(--text-primary);line-height:1.4;}
.question-code{background:var(--code-bg);color:var(--code-text);border:1px solid var(--code-border);border-radius:.75rem;padding:1rem 1.25rem;margin:1rem 0;white-space:pre-wrap;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;font-size:.95rem;overflow-x:auto;line-height:1.4;}

/* ==============================
   OPTIONS (MC)
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

/* ==============================
   CODE EDITOR
   ============================== */
.code-editor-container {
    margin-top: 1.5rem;
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    overflow: hidden;
    background: var(--gray-50);
}
.code-editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: var(--gray-100);
    border-bottom: 1px solid var(--border);
}
.code-editor-title {
    font-weight: 700;
    color: var(--text-secondary);
    font-size: 0.9rem;
}
.code-editor-actions { display: flex; gap: 0.5rem; }
.code-editor-textarea {
    width: 100%;
    min-height: 150px;
    padding: 1rem;
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
    font-size: 0.95rem;
    line-height: 1.5;
    background: var(--gray-900);
    color: var(--gray-100);
    border: none;
    resize: vertical;
    outline: none;
}

/* CODE OUTPUT */
.code-output-container {
    margin-top: 1rem;
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    overflow: hidden;
}
.code-output-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: var(--gray-100);
    border-bottom: 1px solid var(--border);
}
.code-output-title { font-weight: 700; color: var(--text-secondary); font-size: 0.9rem; }
.code-output-content {
    padding: 1rem;
    min-height: 100px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    background: var(--gray-900);
    color: var(--gray-100);
    white-space: pre-wrap;
    overflow-x: auto;
}
.code-status { display:inline-block;padding:.25rem .5rem;border-radius:.35rem;font-size:.75rem;font-weight:700;margin-left:.5rem; }
.code-status.success { background: var(--success-light); color: var(--success); }
.code-status.error   { background: var(--danger-light);  color: var(--danger); }

/* NAVIGATION */
.nav-controls{display:flex;justify-content:space-between;gap:1rem;margin-top:2rem;flex-wrap:wrap;}
.btn-nav{background:var(--gray-100);color:var(--text-primary);border:1px solid var(--border);min-width:100px;}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.75rem 1.25rem;border:none;border-radius:.8rem;font-weight:700;font-size:1rem;cursor:pointer;transition:transform .12s ease, box-shadow .2s ease, filter .12s ease;min-width:120px;}
.btn:disabled{opacity:.55;cursor:not-allowed;}
.btn-primary{background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));color:#fff;box-shadow:var(--shadow);}
.btn-secondary{background:var(--gray-100);color:var(--text-primary);border:1px solid var(--border);}
.btn-success{background:linear-gradient(135deg,var(--success),#34d399);color:#fff;}
.btn-warning{background:linear-gradient(135deg,#f59e0b,#fbbf24);color:#fff;}
.btn-danger{background:linear-gradient(135deg,#ef4444,#f43f5e);color:#fff;}
.btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:var(--shadow-lg);}
.btn.selected{outline:3px solid rgba(124,58,237,.35);transform:translateY(-2px);}

/* ==============================
   RESULTS
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

/* Code results block */
.code-result-container { margin-top: 1rem; border: 1px solid var(--border); border-radius: .75rem; overflow: hidden; }
.code-result-header { display:flex; justify-content:space-between; align-items:center; padding:.75rem 1rem; background:var(--gray-100); border-bottom:1px solid var(--border); }
.code-result-title { font-weight:700; color:var(--text-secondary); font-size:.9rem; }
.code-result-content { padding:1rem; font-family: ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace; font-size:.9rem; line-height:1.5; background:var(--gray-900); color:var(--gray-100); white-space:pre-wrap; overflow-x:auto; }
.code-output-result { margin-top:0; padding:.75rem 1rem; background:var(--gray-800); border-top:1px solid var(--border); font-family: ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace; font-size:.9rem; line-height:1.5; color:var(--gray-100); white-space:pre-wrap; overflow-x:auto; }

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
.toast{background:#fff;border:1px solid var(--border);color:var(--text-primary);padding:1rem 1.1rem;border-radius:.8rem;font-weight:600;min-width:280px;max-width:calc(100vw - 2rem);box-shadow:var(--shadow-lg);animation:slideIn .25s ease;}
.toast.ok{border-left:5px solid var(--success);background:linear-gradient(135deg, rgba(16,185,129,.08), #fff);}
.toast.warn{border-left:5px solid var(--warning);background:linear-gradient(135deg, rgba(245,158,11,.08), #fff);}
.toast.err{border-left:5px solid var(--danger);background:linear-gradient(135deg, rgba(239,68,68,.08), #fff);}
@keyframes slideIn{from{opacity:0;transform:translateX(100%)}to{opacity:1;transform:translateX(0)}}

/* ==============================
   RESPONSIVE
   ============================== */
@media (max-width: 992px){ .header-container{padding:1.25rem;} }
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
  function escapeAndNl2br(s){
  if(!s) return '';
  return String(s)
    .replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m]))
    .replace(/\\n/g, '<br>');  // Convert literal \n to <br>
}
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
  let answers = {};         // { idx: selectedIndex | {code, output} }
  let codeOutputs = {};     // { idx: output string }
  let hintsUsed = 0;
  let submitted = false;
  let timeRemaining = timeLimit;
  let timeUp = false;       // <- no auto-submit when true

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

  // Normalize console output: unify newlines, trim trailing spaces per line, trim ends
  function normalizeOutput(s){
    if(typeof s !== 'string') return '';
    return s.replace(/\r\n?/g,'\n')
            .split('\n')
            .map(line => line.replace(/[ \t]+$/,''))
            .join('\n')
            .trim();
  }

  // Robust detector for code-style question
  function isCodeQ(q){
    const t = String((q?.type ?? q?.kind ?? q?.question_type ?? '')).toLowerCase();
    if (t.includes('code')) return true;
    const hasOptions = Array.isArray(q?.options) && q.options.length > 0;
    const hasExpected = !!(q?.expected_output || q?.expectedOutput);
    const hasStarter = !!(q?.starter_code || q?.template);
    return !hasOptions && (hasExpected || hasStarter);
  }

  // Python Execution (Skulpt)
  function runPython(code, outEl){
    outEl.textContent = '';
    Sk.execLimit = 1e7; // step budget safety
    Sk.configure({
      output: t => { outEl.textContent += t; },
      read: name => {
        if (!Sk.builtinFiles || !Sk.builtinFiles["files"][name]) throw "File not found: '"+name+"'";
        return Sk.builtinFiles["files"][name];
      }
    });
    try { Sk.importMainWithBody("<stdin>", false, code, true); return true; }
    catch(e){ outEl.textContent = e.toString(); return false; }
  }

  // -----------------------------
  // Build question UI
  // -----------------------------
function renderCurrentQuestion(){
  if(currentQuestion >= questions.length) return;
  
  const q = questions[currentQuestion];
  const isAnswered = answers.hasOwnProperty(currentQuestion);
  const isCodeQuestion = isCodeQ(q);

  if (isCodeQuestion) {
    // Code question
    $quizSection.innerHTML = `
<div class="question-card">
  <div class="question-header">
    <div class="question-number">${currentQuestion + 1}</div>
    <div class="question-text">${escapeAndNl2br(q.question || '')}</div>
  </div>

  <div class="code-editor-container">
    <div class="code-editor-header">
      <div class="code-editor-title">Write your Python code:</div>
      <div class="code-editor-actions">
        <button type="button" class="btn btn-success" id="btnRunCode"><i class="fas fa-play"></i> Run Code</button>
        <button type="button" class="btn btn-secondary" id="btnResetCode"><i class="fas fa-undo"></i> Reset</button>
      </div>
    </div>
    <textarea class="code-editor-textarea" id="codeEditor" placeholder="Write your Python code here...">${escapeHtml(q.starter_code || (answers[currentQuestion]?.code ?? ''))}</textarea>
  </div>

  <div class="code-output-container">
    <div class="code-output-header">
      <div class="code-output-title">Output:</div>
      <div id="outputStatus"></div>
    </div>
    <div class="code-output-content" id="codeOutput">${escapeHtml(codeOutputs[currentQuestion] || '')}</div>
  </div>

  <div class="nav-controls">
    <button type="button" class="btn btn-nav" id="btnPrev" ${currentQuestion === 0 ? 'disabled' : ''}>
      <i class="fas fa-chevron-left"></i> Previous
    </button>
    <div style="display:flex;gap:.5rem;">
      <button type="button" class="btn btn-warning" id="btnHint">
        <i class="fas fa-lightbulb"></i> Hint
      </button>
      ${
        answers.hasOwnProperty(currentQuestion)
          ? `<button type="button" class="btn btn-primary" id="btnNext">
               ${currentQuestion === questions.length - 1 ? 'See results' : 'Next'} <i class="fas fa-chevron-right"></i>
             </button>`
          : `<button type="button" class="btn btn-secondary" id="btnSubmitCode">
               <i class="fas fa-check"></i> Submit Code
             </button>
             <button type="button" class="btn btn-primary" id="btnNext" disabled>
               ${currentQuestion === questions.length - 1 ? 'See results' : 'Next'} <i class="fas fa-chevron-right"></i>
             </button>`
      }
    </div>
  </div>
</div>
`;

    // Code question event wiring
    const btnRunCode   = document.getElementById('btnRunCode');
    const btnResetCode = document.getElementById('btnResetCode');
    const codeEditor   = document.getElementById('codeEditor');
    const codeOutput   = document.getElementById('codeOutput');
    const outputStatus = document.getElementById('outputStatus');
    const btnSubmitCode= document.getElementById('btnSubmitCode');
    const btnPrev      = document.getElementById('btnPrev');
    const btnNext      = document.getElementById('btnNext');
    const btnHint      = document.getElementById('btnHint');

    function checkMatchAndBadge(){
      const expected = String(q.expected_output ?? q.expectedOutput ?? '');
      if(!expected){ outputStatus.innerHTML=''; return false; }
     const ok = normalizeOutput(codeOutput.textContent).toLowerCase() === normalizeOutput(expected).toLowerCase();
      outputStatus.innerHTML = ok
        ? '<span class="code-status success">Matches expected ✓</span>'
        : '<span class="code-status error">Does not match ✗</span>';
      return ok;
    }

    btnRunCode?.addEventListener('click', ()=>{
      runPython(codeEditor.value, codeOutput);
      codeOutputs[currentQuestion] = codeOutput.textContent;
      checkMatchAndBadge();
    });

    btnResetCode?.addEventListener('click', ()=>{
      codeEditor.value = q.starter_code || '';
      codeOutput.textContent = '';
      outputStatus.innerHTML = '';
    });

    btnSubmitCode?.addEventListener('click', ()=>{
      answers[currentQuestion] = { code: codeEditor.value, output: codeOutput.textContent };
      updateStats();
      if (btnNext) btnNext.disabled = false;
      btnSubmitCode.style.display = 'none';
      toast('Code submitted. You can proceed.', 'ok');
    });

    btnPrev?.addEventListener('click', ()=> navigateToQuestion(currentQuestion - 1));
    btnNext?.addEventListener('click', ()=>{
      if (currentQuestion === questions.length - 1) {
        showResults();
      } else {
        navigateToQuestion(currentQuestion + 1);
      }
    });
    btnHint?.addEventListener('click', showHint);

  } else {
    // Multiple-choice question
    $quizSection.innerHTML = `
<div class="question-card">
  <div class="question-header">
    <div class="question-number">${currentQuestion + 1}</div>
    <div class="question-text">${escapeAndNl2br(q.question || '')}</div>
  </div>
  ${q.code ? `<pre class="question-code">${escapeHtml(q.code)}</pre>` : ''}
  <div class="question-options">
    ${(q.options || []).map((option, index) => `
      <div class="option-item">
        <input type="radio" id="q${currentQuestion}_${index}" name="q${currentQuestion}" value="${index}" ${answers[currentQuestion] === index ? 'checked' : ''}>
        <label for="q${currentQuestion}_${index}">${escapeHtml(option)}</label>
      </div>
    `).join('')}
  </div>
  <div class="nav-controls">
    <button type="button" class="btn btn-nav" id="btnPrev" ${currentQuestion === 0 ? 'disabled' : ''}>
      <i class="fas fa-chevron-left"></i> Previous
    </button>
    <div style="display:flex;gap:.5rem;">
      <button type="button" class="btn btn-warning" id="btnHint">
        <i class="fas fa-lightbulb"></i> Hint
      </button>
      <button type="button" class="btn btn-primary" id="btnNext" ${answers.hasOwnProperty(currentQuestion) ? '' : 'disabled'}>
        ${currentQuestion === questions.length - 1 ? 'See results' : 'Next'} <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>
</div>
`;

    // Multiple-choice event wiring
    const radioButtons = $quizSection.querySelectorAll('input[type="radio"]');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const btnHint = document.getElementById('btnHint');

    radioButtons.forEach(r => {
      r.addEventListener('change', () => {
        selectAnswer(+r.value);
        if (btnNext) btnNext.disabled = false;
      });
    });

    btnPrev?.addEventListener('click', ()=> navigateToQuestion(currentQuestion - 1));
    btnNext?.addEventListener('click', ()=>{
      if (currentQuestion === questions.length - 1) {
        showResults();
      } else {
        navigateToQuestion(currentQuestion + 1);
      }
    });
    btnHint?.addEventListener('click', showHint);
  }
}
  function selectAnswer(val){
    if(submitted) return;
    answers[currentQuestion] = val;
    updateStats();
    // Re-render to reveal Next/Finish
    setTimeout(renderCurrentQuestion, 60);
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
  // Results display (no auto-submit anywhere)
  // -----------------------------
  function showResults(){
    // Require all answered
    const missingIdx = questions.findIndex((_,i)=>!answers.hasOwnProperty(i));
    if(missingIdx !== -1){
      toast(`Please answer question ${missingIdx+1} before finishing.`, 'warn');
      navigateToQuestion(missingIdx);
      return;
    }

    submitted = true;

    // Calculate score
    let correct = 0;
    questions.forEach((q, i) => {
      if (isCodeQ(q)) {
        const ua = answers[i];
        const userOut = normalizeOutput(ua?.output ?? '');
        const expOut  = normalizeOutput(String(q.expected_output ?? q.expectedOutput ?? ''));
        if (userOut === expOut) correct++;
      } else {
        const chosen = answers[i];
        const correctAnswer = answerKey[i];
        if(chosen === correctAnswer) correct++;
      }
    });

    const rawPct = Math.round(100 * correct / questions.length);
    const hintPenalty = hintsUsed * 5;
    let finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));
    // Optional small time bonus only if time not expired
    if (!timeUp) finalScore = Math.min(100, finalScore + Math.max(0, Math.floor(timeRemaining / 10)));

    const timeUsed = timeLimit - Math.max(0, timeRemaining);
    const stars = (finalScore >= 90) ? 3 : (finalScore >= 70 ? 2 : (finalScore >= 50 ? 1 : 0));
    const starIcons = stars ? '★'.repeat(stars) : '0';
    const passReq = {{ (int)$level->pass_score }};
    const passed = finalScore >= passReq;

    // Show results view
    $quizSection.classList.add('d-none');
    $resultsSection.classList.remove('d-none');

    document.getElementById('finalScoreDisplay').textContent = finalScore + '%';
    document.getElementById('finalStarsDisplay').textContent = starIcons;
    document.getElementById('correctCount').textContent = correct;
    document.getElementById('incorrectCount').textContent = questions.length - correct;
    document.getElementById('hintsUsedDisplay').textContent = hintsUsed;
    document.getElementById('timeUsedDisplay').textContent = fmtTime(timeUsed);

    // Header stats
    $statScore.textContent = finalScore + '%';
    $statStars.textContent = starIcons;
    if($metaStars) $metaStars.textContent = starIcons;

    const resultsGrid = document.getElementById('resultsGrid');
    resultsGrid.innerHTML = '';

    questions.forEach((q,i)=>{
      const isCode = isCodeQ(q);
      let ok=false, html='';
      if (isCode) {
        const ua = answers[i] || {};
        const userOut = normalizeOutput(ua.output ?? '');
        const expOut  = normalizeOutput(String(q.expected_output ?? q.expectedOutput ?? ''));
        ok = userOut === expOut;
        html = `
          <div class="result-card ${ok?'correct':'incorrect'}">
            <div class="result-header">
              <div class="result-number">${i + 1}</div>
              <div class="question-text">${escapeAndNl2br(q.question || '')}</div>

            </div>
            <div class="result-status ${ok?'correct':'incorrect'}">
              <i class="fas fa-${ok ? 'check-circle' : 'times-circle'}"></i>
              ${ok ? 'Correct' : 'Incorrect'}
            </div>
            <div class="code-result-container">
              <div class="code-result-header">
                <div class="code-result-title">Your Code:</div>
              </div>
              <div class="code-result-content">${escapeHtml(ua.code || '')}</div>
              <div class="code-output-result">Output:\n${escapeHtml(ua.output || '')}</div>
            </div>
            <div class="result-explanation">
              <strong>Explanation:</strong> ${escapeHtml(explanations[i] || (ok ? 'Well done!' : 'Ensure your output exactly matches the required format.'))}
            </div>
          </div>`;
      } else {
        ok = (answers[i] === (answerKey[i] ?? null));
        const optionsHtml = (q.options || []).map((option, optIndex) => {
          let className = 'result-option neutral';
          let prefix = '';
          if (optIndex === answers[i] && optIndex === (answerKey[i] ?? null)) {
            className = 'result-option correct'; prefix = 'Your answer: ';
          } else if (optIndex === answers[i]) {
            className = 'result-option incorrect'; prefix = 'Your answer: ';
          } else if (optIndex === (answerKey[i] ?? null)) {
            className = 'result-option correct'; prefix = 'Correct answer: ';
          }
          return `<div class="${className}">${prefix}${escapeHtml(option)}</div>`;
        }).join('');
        html = `
          <div class="result-card ${ok?'correct':'incorrect'}">
            <div class="result-header">
              <div class="result-number">${i + 1}</div>
              <div class="result-text">${escapeHtml(q.question || '')}</div>
            </div>
            ${q.code ? `<pre class="question-code">${escapeHtml(q.code)}</pre>` : ''}
            <div class="result-status ${ok?'correct':'incorrect'}">
              <i class="fas fa-${ok ? 'check-circle' : 'times-circle'}"></i>
              ${ok ? 'Correct' : 'Incorrect'}
            </div>
            <div class="result-options">${optionsHtml}</div>
            <div class="result-explanation">
              <strong>Explanation:</strong> ${escapeHtml(explanations[i] || (ok ? 'Well done!' : 'Review the question and options carefully.'))}
            </div>
          </div>`;
      }
      const el = document.createElement('div');
      el.innerHTML = html;
      resultsGrid.appendChild(el.firstElementChild);
    });

    toast(passed ? `Excellent! Score ${finalScore}%` : `Score ${finalScore}%. Keep practicing!`, passed ? 'ok' : 'err');

    // Save progress via AJAX (no redirect)
    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    fd.append('score', finalScore);
    fd.append('answers', JSON.stringify(answers));
    fd.append('hints_used', hintsUsed);
    fd.append('time_used', timeUsed);
    fd.append('stars', stars);
    fd.append('total_questions', questions.length);
    fd.append('correct_questions', correct);
    fd.append('passed', passed ? 1 : 0);

    fetch('{{ route("levels.submit", $level) }}', {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.ok ? r.json() : r.text().then(t=>{throw new Error(`HTTP ${r.status}: ${t}`)}))
    .then(d => { if(d.success) toast('Progress saved successfully!', 'ok'); })
    .catch(e => { console.error(e); /* silent to avoid double-messaging */ });
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
  // Timer (NO auto submit)
  // -----------------------------
  $timer.textContent = fmtTime(timeRemaining);
  const timerInterval = setInterval(() => {
    timeRemaining--;
    if (timeRemaining < 0) timeRemaining = 0;
    $timer.textContent = fmtTime(timeRemaining);
    if ([60, 30, 10].includes(timeRemaining)) toast(`${timeRemaining}s remaining`, 'warn');
    if (timeRemaining === 0 && !timeUp) {
      timeUp = true;
      toast("Time's up. You can still click Finish to see your results.", 'warn');
      clearInterval(timerInterval);
    }
  }, 1000);

  // -----------------------------
  // Keyboard shortcuts
  // -----------------------------
  document.addEventListener('keydown', (e) => {
    if(submitted) return;
    if(e.key.toLowerCase() === 'h'){ e.preventDefault(); showHint(); }
    if(e.key === 'ArrowLeft'){ e.preventDefault(); if(currentQuestion > 0) navigateToQuestion(currentQuestion - 1); }
    if(e.key === 'ArrowRight'){
      e.preventDefault();
      const isAnswered = answers.hasOwnProperty(currentQuestion);
      if(isAnswered && currentQuestion < questions.length - 1) navigateToQuestion(currentQuestion + 1);
    }
    // Number keys for quick option selection (only for multiple-choice)
    if(e.key >= '1' && e.key <= '9'){ 
      const q = questions[currentQuestion];
      if(q && !isCodeQ(q) && q.options && (parseInt(e.key) - 1) < q.options.length) {
        e.preventDefault(); selectAnswer(parseInt(e.key) - 1);
      }
    }
    if(e.key === 'Enter'){
      const isAnswered = answers.hasOwnProperty(currentQuestion);
      if(isAnswered && currentQuestion === questions.length - 1){
        e.preventDefault(); showResults();
      }
    }
  });

  // Back to stage button
  document.getElementById('btnBackToStage')?.addEventListener('click', () => {
    window.location.href = "{{ route('stages.show', $level->stage_id) }}";
  });
function escapeAndNl2br(s){
  if(!s) return '';
  return String(s)
    .replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[m]))
    .replace(/\n/g, '<br>');  // Handle actual newlines
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
