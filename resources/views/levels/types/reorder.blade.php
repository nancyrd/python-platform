<x-app-layout>
@php
    // ===============================
    // Safe, precomputed data for Blade
    // ===============================
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    // Content fallbacks
    $timeLimit   = (int)($level->content['time_limit'] ?? 180);
    $hints       = $level->content['hints'] ?? [];
    $lines       = $level->content['lines'] ?? [];
    $uiInstrux   = $level->content['instructions'] ?? 'Drag the lines into the correct order.';
    $passScore   = (int)($level->pass_score ?? 50);

    // Default hints if none supplied
    $defaultHints = [
        "Start with setup (initialization) above the loop.",
        "The condition should reference something that can change.",
        "Place body actions (e.g., print) inside the loop.",
        "An update step prevents infinite loops (e.g., i += 1).",
    ];
    $hintsForJs = !empty($hints) ? $hints : $defaultHints;

    // Preserve the *author's intended* correct order for grading.
    $correctOrder = $lines;

    // Create a shuffled copy for initial display (JS will also safeguard).
    $shuffled = $lines;
    // basic deterministic shuffle keyed by level id for repeatable UX per level
    mt_srand((int)($level->id ?? 0) * 7919);
    for ($i = count($shuffled) - 1; $i > 0; $i--) {
        $j = mt_rand(0, $i);
        [$shuffled[$i], $shuffled[$j]] = [$shuffled[$j], $shuffled[$i]];
    }
    mt_srand();
@endphp

{{-- HEADER — unified purple system (same colors + layout as other stages) --}}
<x-slot name="header">
    <div class="level-header">
        <div class="header-container">
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
                        <span class="breadcrumb-item type">Reorder</span>
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
    /* Professional purple palette (shared) */
    --primary-purple: #7c3aed;
    --secondary-purple: #a855f7;
    --light-purple: #c084fc;
    --purple-subtle: #f3e8ff;

    /* Grays (shared) */
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

    /* Semantic (shared) */
    --success: #10b981;
    --success-light: #dcfce7;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fecaca;

    /* UI (shared) */
    --background: #ffffff;
    --surface: #f8fafc;
    --border: #e2e8f0;
    --text-primary: #1e293b;
    --text-secondary: #475569;
    --text-muted: #64748b;
    --shadow-sm: 0 1px 2px rgba(0,0,0,.05);
    --shadow:    0 1px 3px rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
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
.level-header { background: linear-gradient(135deg, rgba(124,58,237,.05), rgba(168,85,247,.03)); border-bottom:1px solid var(--border); backdrop-filter: blur(10px); }
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
.stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.stat-item { text-align:center; padding:.75rem 1rem; background:#fff; border:1px solid var(--border); border-radius:.75rem; box-shadow:var(--shadow-sm); min-width:5rem; }
.stat-label { font-size:.75rem; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.05em; }
.stat-value { font-size:1.125rem; font-weight:700; color:var(--text-primary); margin-top:.25rem; }

/* Full-bleed helpers */
.full-bleed { width:100vw; margin-left:calc(50% - 50vw); margin-right:calc(50% - 50vw); }
.edge-pad   { padding: 1.25rem clamp(12px, 3vw, 32px); }

/* Cards & sections */
.card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem 1.5rem; box-shadow:var(--shadow-sm); }
.card.accent { border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff); }
.section-title { font-size:1.125rem; font-weight:700; margin:0 0 .75rem 0; }

/* Progress */
.items-container { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
.items-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.items-title { font-size:1.05rem; font-weight:700; }
.progress-container { flex:1; max-width:320px; }
.progress-bar { height:.5rem; background:var(--gray-200); border-radius:.25rem; overflow:hidden; }
.progress-fill { height:100%; width:0%; background:linear-gradient(90deg, var(--primary-purple), var(--secondary-purple)); border-radius:.25rem; transition: width .3s ease; }

/* Reorder board (purple skin) */
.board { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem; box-shadow:var(--shadow-sm); }
.board-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem; }
.board-title { font-weight:800; color:var(--text-primary); }
.board-sub { color:var(--text-secondary); font-size:.92rem; }

.list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:.75rem; }
.item {
  display:flex; gap:.75rem; align-items:center;
  background:#fff;
  border:1px solid var(--border);
  border-radius:.75rem; padding:.75rem .9rem;
  box-shadow:var(--shadow-sm);
}
.item.dragging { opacity:.85; border-color:var(--primary-purple); box-shadow:0 0 0 3px rgba(124,58,237,.15) inset; }
.handle { width:34px; height:34px; border-radius:.5rem; background:var(--gray-100); display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-weight:900; cursor:grab; user-select:none; }
.code { white-space:pre; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace; color:var(--text-primary); }

/* Controls */
.controls-container, .controls { display:flex; justify-content:center; gap:1rem; margin:1.25rem 0 .25rem; flex-wrap:wrap; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.25rem; border:none; border-radius:.75rem; font-weight:700; font-size:.9rem; cursor:pointer; transition:all .18s ease; text-decoration:none; }
.btn:disabled{ opacity:.5; cursor:not-allowed; }
.btn-primary { background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
.btn-secondary { background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border); }
.btn-ghost { background:transparent; color:var(--text-secondary); border:1px solid var(--border); }
.btn-primary:hover:not(:disabled){ transform:translateY(-2px); box-shadow:var(--shadow-lg); }
.btn-secondary:hover:not(:disabled){ background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow); }
.btn-ghost:hover:not(:disabled){ background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple); }

/* Meta bar */
.meta-container { display:flex; justify-content:space-between; align-items:center; background:var(--gray-50); border-top:1px solid var(--border); font-size:.875rem; color:var(--text-muted); }
.meta-left { display:flex; gap:1rem; align-items:center; flex-wrap:wrap; }
.meta-pill { background:#fff; border:1px solid var(--border); padding:.25rem .75rem; border-radius:9999px; font-weight:500; }

/* Toasts */
.toast-wrap, .toast-container { position:fixed; top:1rem; right:1rem; display:flex; flex-direction:column; gap:.5rem; z-index:1000; }
.toast { background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:600; min-width:260px; box-shadow:var(--shadow-lg); animation:slideIn .25s ease; }
.toast.ok    { border-left:4px solid var(--success);  background:linear-gradient(135deg,var(--success-light),#fff); }
.toast.warn  { border-left:4px solid var(--warning);  background:linear-gradient(135deg,var(--warning-light),#fff); }
.toast.err   { border-left:4px solid var(--danger);   background:linear-gradient(135deg,var(--danger-light), #fff); }
@keyframes slideIn{ from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)} }

/* Responsive */
@media (max-width:768px){
  .header-container{flex-direction:column; align-items:stretch; gap:1rem; padding:1rem;}
  .edge-pad{padding:1rem}
}
</style>

<!-- INSTRUCTIONS (TOP) -->
<div class="full-bleed edge-pad">
    @if($alreadyPassed)
        <div class="card accent" style="margin-bottom: 1rem;">
            <div class="section-title" style="color:var(--primary-purple)">Level Completed</div>
            <p class="m-0">
                You’ve already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}.
                You can <a href="{{ route('levels.show', $level) }}?replay=1" style="color:var(--primary-purple); text-decoration:underline;">replay</a> to improve your stars.
            </p>
        </div>
    @endif

    <div class="card accent" id="instructionsCard" style="margin-bottom: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <div class="section-title">Instructions</div>
            <button class="btn btn-ghost" type="button" id="toggleInstrux" aria-expanded="true">
                <i class="fas fa-chevron-up"></i> Collapse
            </button>
        </div>
        <div id="instruxBody" style="white-space:pre-wrap;">{!! nl2br(e($uiInstrux)) !!}</div>
    </div>
</div>

<!-- PROGRESS STRIP -->
<div class="full-bleed edge-pad">
    <div class="items-container">
        <div class="items-header">
            <div class="items-title">Order Progress</div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill" id="progressBar"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- BOARD -->
<div class="full-bleed edge-pad">
    <div class="board">
        <div class="board-header">
            <div class="board-title">Drag to reorder</div>
            <div class="board-sub">Place the lines into the correct sequence.</div>
        </div>

        <ul id="reorderList" class="list">
            @foreach($shuffled as $idx => $line)
                <li class="item" draggable="true" data-line="{{ $line }}">
                    <div class="handle" title="Drag handle">≡</div>
                    <pre class="code">{{ $line }}</pre>
                </li>
            @endforeach
        </ul>

        <form id="reorderForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
            @csrf
            <input type="hidden" name="score"   id="finalScore"  value="0">
            <input type="hidden" name="answers" id="answersData" value="[]">

            <div class="controls">
                <button class="btn btn-primary" type="button" id="btnCheck"><i class="fas fa-check"></i> Submit Order</button>
                <button class="btn btn-secondary" type="button" id="btnHint"><i class="fas fa-lightbulb"></i> Show Hint</button>
                <button class="btn btn-ghost" type="button" id="btnReset"><i class="fas fa-rotate-left"></i> Reset</button>
            </div>

            <div class="meta-container" style="margin-top:1rem; padding:.75rem 1rem;">
                <div class="meta-left">
                    <span class="meta-pill">Pass score: {{ $passScore }}%</span>
                    @if(!is_null($savedScore)) <span class="meta-pill">Best: {{ (int)$savedScore }}%</span> @endif
                    <span class="meta-pill">Stars: <span id="metaStars">0</span></span>
                </div>
                <div>Tips used: <span id="hintCount">0</span></div>
            </div>
        </form>
    </div>
</div>

<!-- Toasts -->
<div class="toast-wrap" id="toastWrap"></div>

<script>
(function(){
    // ---- Precomputed from PHP ----
    const timeLimit    = {{ $timeLimit }};
    const correctOrder = @json($correctOrder);
    const hints        = @json($hintsForJs);
    const passScore    = {{ $passScore }};

    // ---- State ----
    let timeRemaining = timeLimit;
    let hintsUsed = 0;
    let submitted  = false;

    // ---- DOM ----
    const $timer      = document.getElementById('timeRemaining');
    const $statScore  = document.getElementById('statScore');
    const $statStars  = document.getElementById('statStars');
    const $metaStars  = document.getElementById('metaStars');
    const $progress   = document.getElementById('progressBar');
    const $hintCount  = document.getElementById('hintCount');
    const $toastWrap  = document.getElementById('toastWrap');

    const $list       = document.getElementById('reorderList');
    const $btnCheck   = document.getElementById('btnCheck');
    const $btnHint    = document.getElementById('btnHint');
    const $btnReset   = document.getElementById('btnReset');
    const $form       = document.getElementById('reorderForm');
    const $scoreInp   = document.getElementById('finalScore');
    const $ansInp     = document.getElementById('answersData');

    // ---- Helpers ----
    function fmtTime(sec){
        const m = Math.floor(sec/60).toString().padStart(2,'0');
        const s = (sec%60).toString().padStart(2,'0');
        return `${m}:${s}`;
    }
    function toast(msg, kind='ok'){
        const el = document.createElement('div');
        el.className = `toast ${kind}`;
        el.textContent = msg;
        $toastWrap.appendChild(el);
        setTimeout(() => el.remove(), 2200);
    }
    function starsFor(score){
        if (score >= 90) return 3;
        if (score >= 70) return 2;
        if (score >= 50) return 1;
        return 0;
    }
    function getCurrentOrder(){
        return Array.from($list.querySelectorAll('.item')).map(li => li.getAttribute('data-line'));
    }
    function updateProgressBar(){
        const current = getCurrentOrder();
        let correct = 0;
        for (let i=0;i<current.length;i++){
            if (current[i] === (correctOrder[i] ?? null)) correct++;
        }
        const pct = current.length ? Math.round(100 * correct / current.length) : 0;
        $progress.style.width = pct + '%';
    }

    // ---- DnD logic (native HTML5) ----
    let dragEl = null;
    $list.addEventListener('dragstart', (e) => {
        const li = e.target.closest('.item');
        if (!li) return;
        dragEl = li;
        li.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', li.getAttribute('data-line') || ''); } catch(_){}
    });
    $list.addEventListener('dragend', (e) => {
        const li = e.target.closest('.item');
        if (li) li.classList.remove('dragging');
        dragEl = null;
        updateProgressBar();
    });
    $list.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (!dragEl) return;
        const afterEl = getDragAfterElement($list, e.clientY);
        if (afterEl == null){
            $list.appendChild(dragEl);
        } else {
            $list.insertBefore(dragEl, afterEl);
        }
    });
    function getDragAfterElement(container, y){
        const els = [...container.querySelectorAll('.item:not(.dragging)')];
        return els.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset){
                return { offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // ---- Timer ----
    if ($timer) $timer.textContent = fmtTime(timeRemaining);
    const t = setInterval(() => {
        timeRemaining--;
        if ($timer) $timer.textContent = fmtTime(timeRemaining);
        if (timeRemaining === 60 || timeRemaining === 30 || timeRemaining === 10){
            toast(`${timeRemaining}s left`, 'warn');
        }
        if (timeRemaining <= 0){
            clearInterval(t);
            if (!submitted){
                toast('Time up — submitting…', 'warn');
                submitNow();
            }
        }
    }, 1000);

    // ---- Events ----
    $btnHint.addEventListener('click', () => {
        if (submitted) return;
        hintsUsed++;
        if ($hintCount) $hintCount.textContent = hintsUsed;
        const hint = hints[Math.floor(Math.random() * hints.length)] || 'Think about the typical loop order.';
        toast('Hint: ' + hint, 'ok');
    });

    $btnReset.addEventListener('click', () => {
        if (submitted) return;
        if (confirm('Reset the list to the initial shuffled order?')){
            location.reload();
        }
    });

    $btnCheck.addEventListener('click', () => {
        if (submitted) return;
        submitNow();
    });

    // ---- Submit & grade ----
    function submitNow(){
        submitted = true;
        $btnCheck.disabled = true;
        $btnHint.disabled = true;
        $btnReset.disabled = true;
        clearInterval(t);

        const current = getCurrentOrder();

        // Score: percent of lines in the exact correct index.
        let correct = 0;
        for (let i=0;i<current.length;i++){
            if (current[i] === (correctOrder[i] ?? null)) correct++;
        }
        const rawPct = current.length ? Math.round(100 * correct / current.length) : 0;
        const hintPenalty = hintsUsed * 5;
        const finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));

        // Update UI + stars
        if ($statScore) $statScore.textContent = finalScore + '%';
        const stars = starsFor(finalScore);
        const starText = stars ? '★'.repeat(stars) : '0';
        if ($statStars) $statStars.textContent = starText;
        if ($metaStars) $metaStars.textContent = starText;

        // Hidden fields
        $scoreInp.value = finalScore;
        $ansInp.value   = JSON.stringify(current);

        if (finalScore >= passScore){
            toast(`Great job! Score ${finalScore}%`, 'ok');
        } else {
            toast(`Score ${finalScore}%. Keep practicing!`, 'err');
        }

        // Submit after a short delay for UX
        setTimeout(() => {
            if ($form.requestSubmit) $form.requestSubmit();
            else $form.submit();
        }, 900);
    }

    // Keyboard helpers
    document.addEventListener('keydown', (e) => {
        if (submitted) return;
        if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); submitNow(); }
        if (e.key.toLowerCase() === 'h'){ e.preventDefault(); $btnHint.click(); }
        if (e.key.toLowerCase() === 'r'){ e.preventDefault(); $btnReset.click(); }
    });

    // Init progress once
    updateProgressBar();

    // Instructions collapse
    const $toggleInstrux = document.getElementById('toggleInstrux');
    const $instruxBody   = document.getElementById('instruxBody');
    if ($toggleInstrux && $instruxBody){
        $toggleInstrux.addEventListener('click', () => {
            const hidden = $instruxBody.classList.toggle('d-none');
            $toggleInstrux.innerHTML = hidden
                ? '<i class="fas fa-chevron-down"></i> Expand'
                : '<i class="fas fa-chevron-up"></i> Collapse';
            $toggleInstrux.setAttribute('aria-expanded', String(!hidden));
        });
    }
})();
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
