<x-app-layout>
@php
    // ===============================
    // Safe, precomputed data for Blade
    // ===============================
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    // Content fallbacks
    $timeLimit  = (int)($level->content['time_limit'] ?? 180);
    $hints      = $level->content['hints'] ?? [];
    $questions  = $level->content['questions'] ?? [];
    $introText  = $level->content['intro'] ?? '';
    $uiInstrux  = $level->content['instructions'] ?? 'Choose the best answer for each question.';

    // Default hints
    $defaultHints = [
        "Read the code carefully and watch for small details like spaces and exact output.",
        "Recall Python basics from the lesson above before answering.",
        "Eliminate obviously wrong choices first, then pick the best remaining option.",
        "If two answers seem right, re-check exact wording and number formatting.",
    ];
    $hintsForJs = !empty($hints) ? $hints : $defaultHints;

    // Build answer key & explanations arrays for JS
    $answerKeyJs = array_map(fn($q) => $q['correct_answer'] ?? null, $questions);
    $explanationsJs = array_map(fn($q) => $q['explanation'] ?? '', $questions);
@endphp

<x-slot name="header">
    <!-- Same header design as drag & drop -->
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
}

/* Header (same as drag & drop) */
.level-header {
    background: linear-gradient(135deg, rgba(124,58,237,.05) 0%, rgba(168,85,247,.03) 100%);
    border-bottom: 1px solid var(--border);
    backdrop-filter: blur(10px);
}
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

/* Questions grid (original) */
.q-list { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1rem; margin-top:1rem; }
.q-card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
.q-head { display:flex; justify-content:space-between; gap:.75rem; margin-bottom:.5rem; }
.q-index { font-weight:800; color:var(--primary-purple); }
.q-text { color:var(--text-primary); margin:.25rem 0 .5rem 0; font-weight:600; line-height:1.35; }
.q-text code, .q-text .block {
    background:#0f172a; color:#cfeaff; border:1px solid rgba(255,255,255,.08);
    border-radius:.5rem; padding:.5rem .625rem; display:block; margin-top:.5rem;
    font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace; white-space:pre-wrap;
}

/* Options */
.q-options { display:flex; flex-direction:column; gap:.5rem; }
.q-option { position:relative; }
.q-option input { position:absolute; inset:0; opacity:0; }
.q-option label {
  display:block; padding:.65rem .8rem; border:1px solid var(--border); border-radius:.75rem;
  background:var(--gray-50); cursor:pointer; color:var(--text-primary); font-weight:600;
  transition: all .18s ease;
}
.q-option:hover label{ border-color:var(--primary-purple); background:#fff; transform: translateY(-1px); box-shadow:var(--shadow); }
.q-option input:checked + label{ border-color:var(--primary-purple); box-shadow:0 0 0 3px rgba(124,58,237,.18) inset; }

/* Result states */
.q-card.correct   { border-color:rgba(16,185,129,.6); box-shadow:0 0 0 3px rgba(16,185,129,.18) inset; }
.q-card.incorrect { border-color:rgba(239,68,68,.6);  box-shadow:0 0 0 3px rgba(239,68,68,.18)  inset; }
.q-explain { margin-top:.5rem; font-size:.95rem; color:var(--text-secondary); border-top:1px dashed var(--border); padding-top:.5rem; display:none; }
.q-card.show-explain .q-explain{ display:block; }

/* Buttons */
.controls-container { display:flex; justify-content:center; gap:1rem; margin:1.5rem 0; flex-wrap:wrap; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.5rem; border:none; border-radius:.75rem; font-weight:700; font-size:.875rem; cursor:pointer; transition:all .2s ease; text-decoration:none; }
.btn:disabled{ opacity:.5; cursor:not-allowed; }
.btn-primary { background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
.btn-primary:hover:not(:disabled){ transform:translateY(-2px); box-shadow:var(--shadow-lg); }
.btn-secondary { background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border); }
.btn-secondary:hover:not(:disabled){ background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow); }
.btn-ghost { background:transparent; color:var(--text-secondary); border:1px solid var(--border); }
.btn-ghost:hover:not(:disabled){ background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple); }

/* Meta bar (full-bleed) */
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

/* ========= ONE-BY-ONE VIEW (minimal additions) ========= */

/* Force single-question view: override grid and hide non-active cards */
.q-list.one-by-one { display:block; }
.q-card { display:none !important; }             /* hide all by default */
.q-card.active { display:block !important; }     /* only active shows */
</style>

<!-- FULL-BLEED MAIN -->
<div class="main-container full-bleed">

    <!-- Completed banner -->
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
                <div class="section-title">How to answer</div>
                <button class="btn btn-ghost" type="button" id="toggleInstrux" aria-expanded="true">
                    <i class="fas fa-chevron-up"></i> Collapse
                </button>
            </div>
            <div id="instruxBody" style="white-space:pre-wrap;">{!! nl2br(e($uiInstrux)) !!}</div>
            @if($introText)
                <div class="mt-2" style="white-space:pre-wrap;">{!! $introText !!}</div>
            @endif
        </div>
    </div>

    <!-- QUIZ -->
    <div class="edge-pad">
        <form id="quizForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
            @csrf
            <input type="hidden" name="score" id="finalScore" value="0">
            <input type="hidden" name="answers" id="answersData" value="[]">

            <!-- Progress header -->
            <div class="items-container">
                <div class="items-header">
                    <div class="items-title">Questions</div>
                    <div class="progress-container">
                        <div class="progress-bar"><div class="progress-fill" id="progressBar"></div></div>
                    </div>
                </div>
            </div>

            <!-- Question list (one-by-one) -->
            <div class="q-list one-by-one">
                @foreach($questions as $i => $q)
                    <div class="q-card {{ $i === 0 ? 'active' : '' }}" data-q="{{ $i }}" {{ $i === 0 ? '' : 'hidden' }}>
                        <div class="q-head">
                            <div class="q-index">Q{{ $i + 1 }}</div>
                        </div>

                        <p class="q-text">
                            {!! $q['question'] !!}
                            @php $qtxt = $q['question']; @endphp
                            @if(is_string($qtxt) && str_starts_with(trim($qtxt), 'What prints?') && str_contains($qtxt, "python"))
                                @php $code = trim(preg_replace('/^.*?python/i', '', $qtxt)); @endphp
                                <span class="block">{{ $code }}</span>
                            @endif
                        </p>

                        <div class="q-options">
                            @foreach(($q['options'] ?? []) as $j => $opt)
                                <div class="q-option">
                                    <input type="radio" id="q{{ $i }}_{{ $j }}" name="q{{ $i }}" value="{{ $j }}">
                                    <label for="q{{ $i }}_{{ $j }}">{{ $opt }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="q-explain" id="exp{{ $i }}"></div>
                    </div>
                @endforeach
            </div>

            <!-- One-by-one nav (new, lightweight) -->
            <div class="controls-container" id="navControls" style="margin-top:1rem;">
                <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                    <span class="meta-pill" id="qCounter">Question 1 of {{ count($questions) }}</span>
                </div>
                <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                    <button type="button" class="btn btn-ghost" id="btnPrev"><i class="fas fa-arrow-left"></i> Previous</button>
                    <button type="button" class="btn btn-primary" id="btnNext">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Original controls (unchanged) -->
            <div class="controls-container">
                <button class="btn btn-primary"   type="button" id="btnCheck"><i class="fas fa-check"></i> Submit Answers</button>
                <button class="btn btn-secondary" type="button" id="btnHint"><i class="fas fa-lightbulb"></i> Show Hint</button>
                <button class="btn btn-ghost"     type="button" id="btnReset"><i class="fas fa-rotate-left"></i> Reset</button>
            </div>
        </form>
    </div>
</div>

<!-- FULL-BLEED META BAR -->
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

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
    // Data from PHP
    const timeLimit    = {{ $timeLimit }};
    const answerKey    = @json($answerKeyJs);
    const explanations = @json($explanationsJs);
    const hints        = @json($hintsForJs);

    // State
    let timeRemaining = timeLimit;
    let hintsUsed = 0;
    let submitted  = false;

    // DOM
    const $timer      = document.getElementById('timeRemaining');
    const $statScore  = document.getElementById('statScore');
    const $statStars  = document.getElementById('statStars');
    const $metaStars  = document.getElementById('metaStars');
    const $progress   = document.getElementById('progressBar');
    const $hintCount  = document.getElementById('hintCount');
    const $toastWrap  = document.getElementById('toastWrap');
    const $btnCheck   = document.getElementById('btnCheck');
    const $btnHint    = document.getElementById('btnHint');
    const $btnReset   = document.getElementById('btnReset');
    const $form       = document.getElementById('quizForm');

    // One-by-one nav
    const $cards = Array.from(document.querySelectorAll('.q-card'));
    const TOTAL = $cards.length;
    let current = 0;
    const $btnPrev   = document.getElementById('btnPrev');
    const $btnNext   = document.getElementById('btnNext');
    const $qCounter  = document.getElementById('qCounter');

    function setCardVisibility(idx){
        current = Math.max(0, Math.min(TOTAL-1, idx));
        $cards.forEach((c, i) => {
            const on = i === current;
            c.classList.toggle('active', on);
            c.hidden = !on;
        });
        if ($qCounter) $qCounter.textContent = `Question ${current+1} of ${TOTAL}`;
        if ($btnPrev)  $btnPrev.disabled = current === 0;
        if ($btnNext)  $btnNext.disabled = current === TOTAL - 1; // keep Next disabled on last; user can still Submit
        // focus first option for accessibility
        const first = $cards[current].querySelector('input[type=radio]');
        if (first) first.focus({preventScroll:true});
        // scroll into view
        $cards[current].scrollIntoView({behavior:'smooth', block:'start'});
    }

    // Instructions collapse
    const $toggleInstrux = document.getElementById('toggleInstrux');
    const $instruxBody   = document.getElementById('instruxBody');
    if ($toggleInstrux) {
        $toggleInstrux.addEventListener('click', () => {
            const hidden = $instruxBody.classList.toggle('d-none');
            $toggleInstrux.innerHTML = hidden
                ? '<i class="fas fa-chevron-down"></i> Expand'
                : '<i class="fas fa-chevron-up"></i> Collapse';
            $toggleInstrux.setAttribute('aria-expanded', String(!hidden));
        });
    }

    // Helpers
    function fmtTime(sec){ const m = String(Math.floor(sec/60)).padStart(2,'0'); const s = String(sec%60).padStart(2,'0'); return `${m}:${s}`; }
    function toast(msg, kind='ok'){ const el=document.createElement('div'); el.className=`toast ${kind}`; el.textContent=msg; $toastWrap.appendChild(el); setTimeout(()=>el.remove(),2200); }
    function starsFor(score){ if(score>=90) return 3; if(score>=70) return 2; if(score>=50) return 1; return 0; }
    function updateProgressBar(){
        const total = TOTAL;
        const answered = document.querySelectorAll('.q-option input:checked').length;
        const pct = total ? Math.round(100 * answered / total) : 0;
        $progress.style.width = pct + '%';
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

    // Events
    document.querySelectorAll('.q-option input').forEach(r => r.addEventListener('change', () => {
        updateProgressBar();
        // Optional auto-advance
        if (current < TOTAL - 1) setTimeout(() => setCardVisibility(current + 1), 180);
    }));

    if ($btnPrev) $btnPrev.addEventListener('click', () => setCardVisibility(current - 1));
    if ($btnNext) $btnNext.addEventListener('click', () => setCardVisibility(current + 1));

    // Hints / Reset / Submit (original)
    $btnHint.addEventListener('click', () => {
        if (submitted) return;
        hintsUsed++; $hintCount.textContent = hintsUsed;
        const hint = hints[Math.floor(Math.random() * hints.length)] || 'Think about the lesson examples.';
        toast('Hint: ' + hint, 'ok');
    });
    $btnReset.addEventListener('click', () => {
        if (submitted) return;
        if (confirm('Reset your selections?')){
            document.querySelectorAll('.q-option input:checked').forEach(i => i.checked = false);
            document.querySelectorAll('.q-card').forEach(c => {
                c.classList.remove('correct','incorrect','show-explain');
                const ex = c.querySelector('.q-explain'); if (ex) ex.textContent = '';
            });
            updateProgressBar(); toast('Cleared.', 'ok');
            setCardVisibility(0);
        }
    });
    $btnCheck.addEventListener('click', () => { if (!submitted) submitNow(); });

    // Grade & submit (original logic)
    function submitNow(){
        submitted = true;
        $btnCheck.disabled = true; $btnHint.disabled = true; $btnReset.disabled = true;
        clearInterval(t);

        const answers = [];
        let correct = 0;

        $cards.forEach((card, i) => {
            const chosen = card.querySelector('input[type=radio]:checked');
            let val = -1, isCorrect = false;
            if (chosen && Number.isFinite(parseInt(chosen.value))){
                val = parseInt(chosen.value);
                if (val === parseInt(answerKey[i])){ isCorrect = true; correct++; }
            }
            answers.push(val);

            card.classList.add(isCorrect ? 'correct' : 'incorrect');
            const ex = card.querySelector('.q-explain');
            if (ex){ ex.textContent = explanations[i] || (isCorrect ? 'Correct.' : 'Check the lesson again.'); card.classList.add('show-explain'); }
        });

        const rawPct = TOTAL ? Math.round((correct / TOTAL) * 100) : 0;
        const hintPenalty = hintsUsed * 5;
        const finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));

        $statScore.textContent = finalScore + '%';
        const starCount = starsFor(finalScore);
        $statStars.textContent = starCount ? '★'.repeat(starCount) : '0';
        if ($metaStars) $metaStars.textContent = starCount ? '★'.repeat(starCount) : '0';

        document.getElementById('finalScore').value = finalScore;
        document.getElementById('answersData').value = JSON.stringify(answers);

        const passReq = {{ (int)$level->pass_score }};
        toast(finalScore >= passReq ? `Great job! Score ${finalScore}%` : `Score ${finalScore}%. Keep practicing!`, finalScore >= passReq ? 'ok' : 'err');

        setTimeout(() => { if ($form.requestSubmit) $form.requestSubmit(); else $form.submit(); }, 900);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (submitted) return;
        if (e.key === 'ArrowLeft') { e.preventDefault(); if ($btnPrev && !$btnPrev.disabled) $btnPrev.click(); }
        if (e.key === 'ArrowRight'){ e.preventDefault(); if ($btnNext && !$btnNext.disabled) $btnNext.click(); }
        if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); submitNow(); }
        if (e.key.toLowerCase() === 'h'){ e.preventDefault(); $btnHint.click(); }
        if (e.key.toLowerCase() === 'r'){ e.preventDefault(); $btnReset.click(); }
    });

    // Init
    function initFirst(){
        // ensure only first is visible at start (no flash of all)
        setCardVisibility(0);
        updateProgressBar();
    }
    initFirst();
})();
</script>
</x-app-layout>
