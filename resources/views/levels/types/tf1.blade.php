<x-app-layout>
@php
    // Safe header stats
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    // Normalize content
    $content      = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $intro        = $content['intro'] ?? null;
    $instructions = $content['instructions'] ?? null;
    $questionsRaw = $content['questions'] ?? [];
    $hints        = $content['hints'] ?? [];
    $timeLimit    = (int)($content['time_limit'] ?? 300);
    $maxHints     = (int)($content['max_hints']  ?? 3);

    // Flatten questions for JS
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
    <!-- Same header design as the other blades -->
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
}

/* Header (shared) */
.level-header { background: linear-gradient(135deg, rgba(124,58,237,.05) 0%, rgba(168,85,247,.03) 100%); border-bottom:1px solid var(--border); backdrop-filter: blur(10px); }
.header-container { display:flex; align-items:center; justify-content:space-between; padding:1.5rem 2rem; gap:2rem; }
.header-left { display:flex; align-items:center; gap:1.5rem; flex:1; min-width:0; }
.level-badge { width:4rem; height:4rem; border-radius:1rem; background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-md); }
.level-number { font-weight:900; font-size:1.25rem; color:#fff; }
.level-info { flex:1; min-width:0; }
.breadcrumb { display:flex; align-items:center; gap:.5rem; font-size:.875rem; color:var(--text-muted); margin-bottom:.25rem; }
.breadcrumb-item.type { text-transform:capitalize; color:var(--primary-purple); font-weight:500; }
.separator{opacity:.6}
.stage-title { font-size:1.5rem; font-weight:700; margin:0; line-height:1.2; color:var(--text-primary); }
.level-title { font-size:1rem; color:var(--text-secondary); margin-top:.25rem; }
.header-right { flex-shrink:0; }
.stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.stat-item { text-align:center; padding:.75rem 1rem; background:#fff; border:1px solid var(--border); border-radius:.75rem; box-shadow:var(--shadow-sm); min-width:5rem; }
.stat-label { font-size:.75rem; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.05em; }
.stat-value { font-size:1.125rem; font-weight:700; color:var(--text-primary); margin-top:.25rem; }

/* Full-bleed helpers */
.full-bleed { width:100vw; margin-left:calc(50% - 50vw); margin-right:calc(50% - 50vw); }
.edge-pad   { padding: 1.25rem clamp(12px, 3vw, 32px); }

/* Containers & cards */
.main-container { max-width:none; }
.card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.5rem; box-shadow:var(--shadow-sm); }
.card.accent { border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff); }
.section-title { font-size:1.125rem; font-weight:700; margin:0 0 1rem 0; color:var(--text-primary); }

/* Progress header */
.items-container { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
.items-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.items-title { font-size:1.05rem; font-weight:700; }
.progress-container { flex:1; max-width:240px; }
.progress-bar { height:.5rem; background:var(--gray-200); border-radius:.25rem; overflow:hidden; }
.progress-fill { height:100%; width:0%; background:linear-gradient(90deg, var(--primary-purple), var(--secondary-purple)); border-radius:.25rem; transition: width .3s ease; }

/* TF Questions */
.tf-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1rem; margin-top:1rem; }
.tf-card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); position:relative; }
.tf-card.correct   { border-color:rgba(16,185,129,.6); box-shadow:0 0 0 3px rgba(16,185,129,.15) inset; }
.tf-card.incorrect { border-color:rgba(239,68,68,.6);  box-shadow:0 0 0 3px rgba(239,68,68,.15) inset; }

.tf-head { display:flex; gap:.75rem; align-items:center; margin-bottom:.5rem; }
.tf-num  { width:2rem; height:2rem; border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-weight:800; color:#fff; background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); }
.tf-text { font-weight:700; color:var(--text-primary); line-height:1.35; }

.tf-code { background:#0f172a; color:#cfeaff; border:1px solid rgba(255,255,255,.08); border-radius:.5rem; padding:.5rem .625rem; margin:.5rem 0 0; white-space:pre-wrap; font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace; }

.tf-actions { display:flex; gap:.5rem; margin-top:.75rem; }
.btn { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; padding:.65rem .9rem; border:none; border-radius:.75rem; font-weight:700; font-size:.9rem; cursor:pointer; transition:all .18s ease; text-decoration:none; }
.btn:disabled{ opacity:.5; cursor:not-allowed; }
.btn-true  { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.btn-false { background:linear-gradient(135deg,#ef4444,#f59e0b); color:#fff; }
.btn-true:hover:not(:disabled), .btn-false:hover:not(:disabled){ transform:translateY(-2px); box-shadow:var(--shadow); }
.btn.selected { outline:3px solid rgba(124,58,237,.35); }

.tf-explain { display:none; margin-top:.5rem; color:var(--text-secondary); border-top:1px dashed var(--border); padding-top:.5rem; }
.tf-card.show-explain .tf-explain { display:block; }

/* Controls */
.controls-container { display:flex; justify-content:center; gap:1rem; margin:1.5rem 0; flex-wrap:wrap; }
.btn-primary { background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
.btn-primary:hover:not(:disabled){ transform:translateY(-2px); box-shadow:var(--shadow-lg); }
.btn-secondary { background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border); }
.btn-secondary:hover:not(:disabled){ background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow); }
.btn-ghost { background:transparent; color:var(--text-secondary); border:1px solid var(--border); }
.btn-ghost:hover:not(:disabled){ background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple); }

/* Meta bar */
.meta-container { display:flex; justify-content:space-between; align-items:center; background:var(--gray-50); border-top:1px solid var(--border); font-size:.875rem; color:var(--text-muted); }
.meta-left { display:flex; gap:1rem; align-items:center; flex-wrap:wrap; }
.meta-pill { background:#fff; border:1px solid var(--border); padding:.25rem .75rem; border-radius:9999px; font-weight:500; }

/* Toasts */
.toast-container { position:fixed; top:1rem; right:1rem; display:flex; flex-direction:column; gap:.5rem; z-index:1000; }
.toast { background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:500; min-width:280px; box-shadow:var(--shadow-lg); animation:slideIn .3s ease; }
.toast.ok   { border-left:4px solid var(--success);  background:linear-gradient(135deg,var(--success-light), #fff); }
.toast.warn { border-left:4px solid var(--warning);  background:linear-gradient(135deg,var(--warning-light), #fff); }
.toast.err  { border-left:4px solid var(--danger);   background:linear-gradient(135deg,var(--danger-light),  #fff); }
@keyframes slideIn{ from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)} }

/* Responsive */
@media (max-width:768px){
  .header-container{flex-direction:column; align-items:stretch; gap:1rem; padding:1rem;}
  .edge-pad{padding:1rem}
}
</style>

<!-- MAIN FULL-BLEED -->
<div class="main-container full-bleed">

    @if($alreadyPassed)
    <div class="edge-pad">
        <div class="card accent" style="margin-bottom: 1rem;">
            <div class="section-title" style="color:var(--primary-purple)">Level Completed</div>
            <p style="margin:0">
                You’ve already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}.
                You can <a href="{{ route('levels.show', $level) }}?replay=1" style="color:var(--primary-purple); text-decoration:underline;">replay</a> to improve your stars.
            </p>
        </div>
    </div>
    @endif

    <!-- INSTRUCTIONS (TOP) -->
    <div class="edge-pad">
        <div class="card accent" id="instructionsCard" style="margin-bottom: 1.25rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <div class="section-title">How to play</div>
                <button class="btn btn-ghost" type="button" id="toggleInstrux" aria-expanded="true">
                    <i class="fas fa-chevron-up"></i> Collapse
                </button>
            </div>
            <div id="instruxBody" style="white-space:pre-wrap;">
                {!! nl2br(e($instructions ?? 'Read the statement (and code if any), then choose TRUE or FALSE.')) !!}
                @if($intro)
                    <div class="mt-2" style="white-space:pre-wrap;">{!! nl2br(e($intro)) !!}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- GAME -->
    <div class="edge-pad">
        <div class="items-container">
            <div class="items-header">
                <div class="items-title">Questions</div>
                <div class="progress-container">
                    <div class="progress-bar"><div class="progress-fill" id="progressBar"></div></div>
                </div>
            </div>
        </div>

        <div class="tf-list" id="tfList"><!-- filled by JS --></div>

        <div class="controls-container">
            <button class="btn btn-primary"   type="button" id="btnCheck"><i class="fas fa-check"></i> Submit Answers</button>
            <button class="btn btn-secondary" type="button" id="btnHint"><i class="fas fa-lightbulb"></i> Hint</button>
            <button class="btn btn-ghost"     type="button" id="btnReset"><i class="fas fa-rotate-left"></i> Reset</button>
        </div>

        <!-- Hidden submit -->
        <form method="POST" action="{{ route('levels.submit', $level) }}" id="scoreForm" style="display:none;">
            @csrf
            <input type="hidden" name="score" id="finalScore" value="0">
            <input type="hidden" name="answers" id="answersPayload" value="">
        </form>
    </div>
</div>

<!-- META BAR -->
<div class="meta-container full-bleed edge-pad">
    <div class="meta-left">
        <span class="meta-pill">Pass score: {{ (int)$level->pass_score }}%</span>
        @if(!is_null($savedScore)) <span class="meta-pill">Best: {{ (int)$savedScore }}%</span> @endif
        <span class="meta-pill">Stars: <span id="metaStars">0</span></span>
    </div>
    <div>Tips used: <span id="hintCount">0</span></div>
</div>

<!-- Toasts -->
<div class="toast-container" id="toastWrap"></div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
  // Data
  const data       = window.LEVEL_DATA || {};
  const questions  = Array.isArray(data.questions) ? data.questions : [];
  const hints      = Array.isArray(data.hints) ? data.hints : [];
  const timeLimit  = Number.isFinite(data.time_limit) ? data.time_limit : 300;
  const maxHints   = Number.isFinite(data.max_hints)  ? data.max_hints  : 3;

  // State
  let answers = {}; // {id: 0|1}
  let hintsUsed = 0;
  let submitted = false;
  let timeRemaining = timeLimit;

  // DOM
  const $tfList     = document.getElementById('tfList');
  const $progress   = document.getElementById('progressBar');
  const $timer      = document.getElementById('timeRemaining');
  const $statScore  = document.getElementById('statScore');
  const $statStars  = document.getElementById('statStars');
  const $metaStars  = document.getElementById('metaStars');
  const $hintCount  = document.getElementById('hintCount');
  const $toastWrap  = document.getElementById('toastWrap');
  const $btnCheck   = document.getElementById('btnCheck');
  const $btnHint    = document.getElementById('btnHint');
  const $btnReset   = document.getElementById('btnReset');

  // Instructions collapse
  const $toggleInstrux = document.getElementById('toggleInstrux');
  const $instruxBody   = document.getElementById('instruxBody');
  $toggleInstrux.addEventListener('click', () => {
    const hidden = $instruxBody.classList.toggle('d-none');
    $toggleInstrux.innerHTML = hidden
      ? '<i class="fas fa-chevron-down"></i> Expand'
      : '<i class="fas fa-chevron-up"></i> Collapse';
    $toggleInstrux.setAttribute('aria-expanded', String(!hidden));
  });

  // Helpers
  function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
  function toast(msg, kind='ok'){ const el=document.createElement('div'); el.className=`toast ${kind}`; el.textContent=msg; $toastWrap.appendChild(el); setTimeout(()=>el.remove(),2200); }
  function starsFor(score){ if(score>=90) return 3; if(score>=70) return 2; if(score>=50) return 1; return 0; }
  function fmtTime(sec){ const m=String(Math.floor(sec/60)).padStart(2,'0'); const s=String(sec%60).padStart(2,'0'); return `${m}:${s}`; }
  function updateProgress(){ const pct = questions.length ? Math.round(100 * Object.keys(answers).length / questions.length) : 0; $progress.style.width = pct + '%'; }

  // Build UI
  function render(){
    $tfList.innerHTML = '';
    questions.forEach((q, i) => {
      const card = document.createElement('div');
      card.className = 'tf-card';
      card.dataset.id = q.id;

      card.innerHTML = `
        <div class="tf-head">
          <div class="tf-num">${i+1}</div>
          <div class="tf-text">${escapeHtml(q.text)}</div>
        </div>
        ${q.code ? `<pre class="tf-code">${escapeHtml(q.code)}</pre>` : ''}
        <div class="tf-actions">
          <button type="button" class="btn btn-true"  data-val="1"><i class="fas fa-check"></i> TRUE</button>
          <button type="button" class="btn btn-false" data-val="0"><i class="fas fa-times"></i> FALSE</button>
        </div>
        <div class="tf-explain"></div>
      `;

      const [bTrue, bFalse] = card.querySelectorAll('.btn');
      [bTrue, bFalse].forEach(btn => btn.addEventListener('click', () => select(q.id, +btn.dataset.val, card)));
      $tfList.appendChild(card);
    });
  }

  function select(id, val, card){
    if (submitted) return;
    answers[id] = val;
    card.querySelectorAll('.btn').forEach(b => b.classList.remove('selected'));
    const btn = card.querySelector(`.btn[data-val="${val}"]`);
    if (btn) btn.classList.add('selected');
    updateProgress();
  }

  // Timer
  $timer.textContent = fmtTime(timeRemaining);
  const t = setInterval(() => {
    timeRemaining--;
    $timer.textContent = fmtTime(timeRemaining);
    if ([60,30,10].includes(timeRemaining)) toast(`${timeRemaining}s left`, 'warn');
    if (timeRemaining <= 0){
      clearInterval(t);
      if (!submitted){ toast('Time up — submitting…', 'warn'); submitNow(); }
    }
  }, 1000);

  // Buttons
  $btnHint.addEventListener('click', () => {
    if (submitted) return;
    if (hintsUsed >= maxHints) return toast('No more hints available.', 'warn');
    hintsUsed++; $hintCount.textContent = hintsUsed;
    const hint = hints.length ? hints[(hintsUsed-1) % hints.length] : 'Read the statement carefully.';
    toast('Hint: ' + hint, 'ok');
  });

  $btnReset.addEventListener('click', () => {
    if (submitted) return;
    if (!confirm('Reset your selections?')) return;
    answers = {};
    document.querySelectorAll('.tf-card .btn').forEach(b => b.classList.remove('selected'));
    document.querySelectorAll('.tf-card').forEach(c => { c.classList.remove('correct','incorrect','show-explain'); c.querySelector('.tf-explain').textContent=''; });
    updateProgress(); toast('Cleared.', 'ok');
  });

  $btnCheck.addEventListener('click', () => { if (!submitted) submitNow(); });

  // Keyboard helpers
  document.addEventListener('keydown', (e) => {
    if (submitted) return;
    if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); submitNow(); }
    if (e.key.toLowerCase() === 'h'){ e.preventDefault(); $btnHint.click(); }
    if (e.key.toLowerCase() === 'r'){ e.preventDefault(); $btnReset.click(); }
  });

  // Grade & submit
  function submitNow(){
    if (Object.keys(answers).length !== questions.length){
      return toast('Answer all questions first.', 'warn');
    }

    submitted = true;
    $btnCheck.disabled = true; $btnHint.disabled = true; $btnReset.disabled = true;
    clearInterval(t);

    let correct = 0;
    questions.forEach(q => {
      const card = document.querySelector(`.tf-card[data-id="${q.id}"]`);
      const chosen = answers[q.id];          // 0|1
      const truth  = q.correct ? 1 : 0;      // 0|1
      const ok = chosen === truth;

      if (ok) correct++; 
      card.classList.add(ok ? 'correct' : 'incorrect');
      const ex = card.querySelector('.tf-explain');
      if (ex){
        ex.textContent = q.explanation || (ok ? 'Correct.' : 'Re-check the statement.');
        card.classList.add('show-explain');
      }
    });

    const rawPct = Math.round(100 * correct / questions.length);
    const hintPenalty = hintsUsed * 5;
    let finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));
    // small time bonus
    finalScore = Math.min(100, finalScore + Math.max(0, Math.floor(timeRemaining / 10)));

    // UI
    $statScore.textContent = finalScore + '%';
    const s = starsFor(finalScore);
    $statStars.textContent = s ? '★'.repeat(s) : '0';
    if ($metaStars) $metaStars.textContent = s ? '★'.repeat(s) : '0';

    // Send
    document.getElementById('finalScore').value = finalScore;
    document.getElementById('answersPayload').value = JSON.stringify(answers);

    const passReq = {{ (int)$level->pass_score }};
    toast(finalScore >= passReq ? `Great job! Score ${finalScore}%` : `Score ${finalScore}%. Keep practicing!`, finalScore >= passReq ? 'ok' : 'err');

    setTimeout(() => {
      const form = document.getElementById('scoreForm');
      if (form.requestSubmit) form.requestSubmit(); else form.submit();
    }, 900);
  }

  // Init
  render();
  updateProgress();
})();
</script>
</x-app-layout>
