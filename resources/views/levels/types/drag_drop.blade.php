<x-app-layout>
@php
    // ===============================
    // Safe data for Blade
    // ===============================
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    $timeLimit   = (int)($level->content['time_limit'] ?? 180);
    $maxHints    = (int)($level->content['max_hints'] ?? 3);
    $hints       = $level->content['hints'] ?? [];
    $introText   = $level->content['intro'] ?? '';
    $uiInstrux   = $level->content['instructions'] ?? 'Drag each item into the correct category.';
    $categories  = $level->content['categories'] ?? [];

    // Fallback hints
    $defaultHints = [
        "Read each item and think: is it a condition, an action, or setup code?",
        "A good category question: 'Does this run only when a test is true?'",
        "If the line changes a counter (e.g., i += 1), it's usually an update/action.",
        "If it imports or defines things, it's typically not part of branching/looping itself.",
    ];
    $hintsForJs = !empty($hints) ? $hints : $defaultHints;

    // Build a flat answer key: item text -> category name
    $answerMap = [];
    foreach ($categories as $catName => $items) {
        foreach ((array)$items as $txt) {
            $answerMap[$txt] = $catName;
        }
    }

    // Collect all items in one array for the top source bucket (shuffled)
    $allItems = array_keys($answerMap);
    // Shuffle deterministically per level for a stable experience per level id
    mt_srand((int)($level->id ?? 0) * 104729);
    for ($i = count($allItems) - 1; $i > 0; $i--) {
        $j = mt_rand(0, $i);
        [$allItems[$i], $allItems[$j]] = [$allItems[$j], $allItems[$i]];
    }
    mt_srand();
@endphp

<x-slot name="header">
    <div class="level-header">
        <div class="header-container">
            <!-- Left side: Level info -->
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

            <!-- Right side: Stats -->
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
    /* Professional purple-based palette */
    --primary-purple: #7c3aed;
    --secondary-purple: #a855f7;
    --light-purple: #c084fc;
    --purple-subtle: #f3e8ff;

    /* Neutral grays */
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

    /* Semantic colors */
    --success: #10b981;
    --success-light: #dcfce7;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fecaca;
    --info: #3b82f6;
    --info-light: #dbeafe;

    /* UI */
    --background: #ffffff;
    --surface: #f8fafc;
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
    line-height: 1.5;
}

/* Header */
.level-header {
    background: linear-gradient(135deg,
        rgba(124,58,237,.05) 0%,
        rgba(168,85,247,.03) 100%);
    border-bottom: 1px solid var(--border);
    backdrop-filter: blur(10px);
}
.header-container { display:flex; align-items:center; justify-content:space-between; padding:1.5rem 2rem; gap:2rem; }
.header-left { display:flex; align-items:center; gap:1.5rem; flex:1; min-width:0; }
.level-badge { width:4rem; height:4rem; border-radius:1rem; background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-md); flex-shrink:0; }
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

/* Full-bleed layout */
.full-bleed {
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
}
.edge-pad { padding: 1.25rem clamp(12px, 3vw, 32px); }

/* Main */
.main-container { max-width:none; }
.section-title { font-size:1.125rem; font-weight:700; margin:0 0 1rem 0; color:var(--text-primary); }

/* Cards */
.card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.5rem; box-shadow:var(--shadow-sm); }
.card.accent {
    border-left: 6px solid var(--primary-purple);
    background: linear-gradient(180deg, var(--purple-subtle), #fff);
}

/* Game board */
.game-board { display:flex; flex-direction:column; gap:2rem; }

/* Items / progress */
.items-container { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.5rem; box-shadow:var(--shadow-sm); }
.items-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; gap:1rem; flex-wrap:wrap; }
.items-title { font-size:1.125rem; font-weight:600; color:var(--text-primary); }
.progress-container { flex:1; max-width:220px; }
.progress-bar { height:.5rem; background:var(--gray-200); border-radius:.25rem; overflow:hidden; }
.progress-fill { height:100%; width:0%; background:linear-gradient(90deg,var(--primary-purple),var(--secondary-purple)); border-radius:.25rem; transition: width .3s ease; }
.chips-container { display:flex; flex-wrap:wrap; gap:.75rem; min-height:3rem; }
.chip { display:inline-flex; align-items:center; gap:.5rem; background:var(--gray-50); border:1px solid var(--border); color:var(--text-primary); padding:.75rem 1rem; border-radius:.75rem; cursor:grab; user-select:none; transition:all .2s ease; font-weight:500; }
.chip:hover { background:var(--gray-100); border-color:var(--primary-purple); transform:translateY(-1px); box-shadow:var(--shadow); }
.chip.dragging { opacity:.7; background:var(--purple-subtle); border-color:var(--primary-purple); cursor:grabbing; box-shadow:var(--shadow-lg); }
.chip-badge { font-size:.75rem; color:var(--primary-purple); background:rgba(124,58,237,.1); border:1px solid rgba(124,58,237,.2); padding:.125rem .5rem; border-radius:9999px; font-weight:500; }

/* Categories */
.categories-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1.5rem; }
.category-container { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.5rem; box-shadow:var(--shadow-sm); min-height:150px; }
.category-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
.category-name { font-size:1rem; font-weight:600; color:var(--text-primary); }
.category-count { font-size:.875rem; color:var(--text-muted); }
.drop-zone { min-height:100px; border:2px dashed var(--gray-300); border-radius:.75rem; padding:1rem; display:flex; flex-wrap:wrap; gap:.75rem; align-items:flex-start; align-content:flex-start; transition:all .2s ease; }
.drop-zone.drag-over { border-color:var(--primary-purple); background:rgba(124,58,237,.05); box-shadow: inset 0 0 0 1px var(--primary-purple); }
.drop-zone:empty::after { content:'Drop items here'; color:var(--text-muted); font-style:italic; display:flex; align-items:center; justify-content:center; height:100%; width:100%; }

/* Controls */
.controls-container { display:flex; justify-content:center; gap:1rem; margin:2rem 0; flex-wrap:wrap; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.5rem; border:none; border-radius:.75rem; font-weight:600; font-size:.875rem; cursor:pointer; transition:all .2s ease; text-decoration:none; }
.btn:disabled { opacity:.5; cursor:not-allowed; }
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
.toast { background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:500; min-width:300px; box-shadow:var(--shadow-lg); animation:slideIn .3s ease; }
.toast.success{ border-left:4px solid var(--success); background:linear-gradient(135deg,var(--success-light),#fff); }
.toast.warning{ border-left:4px solid var(--warning); background:linear-gradient(135deg,var(--warning-light),#fff); }
.toast.error{   border-left:4px solid var(--danger);  background:linear-gradient(135deg,var(--danger-light), #fff); }
@keyframes slideIn{ from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)} }

/* Small */
@media (max-width:768px){
  .header-container{flex-direction:column; align-items:stretch; gap:1rem; padding:1rem;}
  .stats-grid{gap:.5rem}
  .edge-pad{padding:1rem}
}
</style>

<!-- FULL-BLEED MAIN WRAP -->
<div class="main-container full-bleed">

    <!-- optional “passed” banner -->
    @if($alreadyPassed)
        <div class="edge-pad">
            <div class="card accent" style="margin-bottom: 1rem;">
                <div class="section-title" style="color:var(--primary-purple)">Level Completed</div>
                <p style="margin:0">
                    You've already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}.
                    You can <a href="{{ route('levels.show', $level) }}?replay=1" style="color:var(--primary-purple); text-decoration: underline;">replay</a>
                    to improve your stars.
                </p>
            </div>
        </div>
    @endif

    <!-- INSTRUCTIONS ON TOP -->
    <div class="edge-pad">
        <div class="card accent" id="instructionsCard" style="margin-bottom: 1.25rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <div class="section-title">Instructions</div>
                <button class="btn btn-ghost" type="button" id="toggleInstrux" aria-expanded="true">
                    <i class="fas fa-chevron-up"></i> Collapse
                </button>
            </div>
            <div id="instruxBody" style="white-space: pre-wrap;">{!! nl2br(e($uiInstrux)) !!}</div>
        </div>
    </div>

    <!-- GAME BOARD -->
    <div class="edge-pad">
        <div class="game-board">

            <form id="ddForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
                @csrf
                <input type="hidden" name="score"   id="finalScore"  value="0">
                <input type="hidden" name="answers" id="answersData" value="{}">

                <!-- Items -->
                <div class="items-container">
                    <div class="items-header">
                        <div class="items-title">Items to Place</div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressBar"></div>
                            </div>
                        </div>
                    </div>

                    <div class="chips-container" id="chipsBucket">
                        @foreach($allItems as $txt)
                            <div class="chip" draggable="true" data-item="{{ $txt }}">
                                <span class="chip-badge">drag</span>
                                <span class="chip-text">{{ $txt }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categories -->
                <div class="categories-grid" id="catsGrid">
                    @foreach($categories as $catName => $items)
                        <div class="category-container" data-category="{{ $catName }}">
                            <div class="category-header">
                                <div class="category-name">{{ $catName }}</div>
                                <div class="category-count"><span class="count">0</span> placed</div>
                            </div>
                            <div class="drop-zone" data-drop="{{ $catName }}"></div>
                        </div>
                    @endforeach
                </div>

                <!-- Controls -->
                <div class="controls-container">
                    <button class="btn btn-primary"   type="button" id="btnSubmit"><i class="fas fa-check"></i> Submit Answer</button>
                    <button class="btn btn-secondary" type="button" id="btnHint"><i class="fas fa-lightbulb"></i> Get Hint</button>
                    <button class="btn btn-ghost"     type="button" id="btnReset"><i class="fas fa-rotate-left"></i> Reset All</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FULL-BLEED META BAR -->
<div class="meta-container full-bleed edge-pad">
    <div class="meta-left">
        <span class="meta-pill">Pass score: {{ (int)$level->pass_score }}%</span>
        @if(!is_null($savedScore))
            <span class="meta-pill">Best: {{ (int)$savedScore }}%</span>
        @endif
        <span class="meta-pill">Stars: <span id="metaStars">0</span></span>
    </div>
    <div>Tips used: <span id="hintCount">0</span></div>
</div>

<div class="toast-container" id="toastWrap"></div>

<!-- Icons & minimal dependency -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
    // ---- Data from PHP ----
    const timeLimit  = {{ $timeLimit }};
    const maxHints   = {{ $maxHints }};
    const answerMap  = @json($answerMap); // {item: category}
    const categories = @json(array_keys($categories));

    // ---- State ----
    let timeRemaining = timeLimit;
    let hintsUsed = 0;
    let submitted = false;

    // ---- DOM ----
    const $timer     = document.getElementById('timeRemaining');
    const $statScore = document.getElementById('statScore');
    const $statStars = document.getElementById('statStars');
    const $metaStars = document.getElementById('metaStars');
    const $progress  = document.getElementById('progressBar');
    const $hintCount = document.getElementById('hintCount');
    const $toastWrap = document.getElementById('toastWrap');

    const $chipsBucket = document.getElementById('chipsBucket');
    const $btnSubmit   = document.getElementById('btnSubmit');
    const $btnHint     = document.getElementById('btnHint');
    const $btnReset    = document.getElementById('btnReset');
    const $form        = document.getElementById('ddForm');
    const $scoreInp    = document.getElementById('finalScore');
    const $ansInp      = document.getElementById('answersData');

    // instructions collapse
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

    // ---- Helpers ----
    function fmtTime(sec){
        const m = Math.floor(sec/60).toString().padStart(2,'0');
        const s = (sec%60).toString().padStart(2,'0');
        return `${m}:${s}`;
    }
    function toast(msg, kind='success'){
        const el = document.createElement('div');
        el.className = `toast ${kind}`;
        el.textContent = msg;
        $toastWrap.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    }
    function starsFor(score){
        if (score >= 90) return 3;
        if (score >= 70) return 2;
        if (score >= 50) return 1;
        return 0;
    }
    function updateCounts(){
        document.querySelectorAll('.category-container').forEach(cat=>{
            const n = cat.querySelectorAll('.drop-zone .chip').length;
            const c = cat.querySelector('.count');
            if (c) c.textContent = n;
        });
        const total = document.querySelectorAll('.chip').length;
        const placed = document.querySelectorAll('.drop-zone .chip').length;
        const pct = total ? Math.round(100 * placed / total) : 0;
        $progress.style.width = pct + '%';
    }
    function currentPlacements(){
        const m = {};
        document.querySelectorAll('.chip').forEach(chip => {
            const item = chip.getAttribute('data-item');
            const parent = chip.parentElement;
            if (parent && parent.hasAttribute('data-drop')){
                m[item] = parent.getAttribute('data-drop');
            } else {
                m[item] = null;
            }
        });
        return m;
    }

    // ---- Drag & Drop ----
    let dragEl = null;
    function onDragStart(e){
        const chip = e.target.closest('.chip');
        if (!chip) return;
        dragEl = chip;
        chip.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', chip.getAttribute('data-item') || ''); } catch(_){}
    }
    function onDragEnd(e){
        const chip = e.target.closest('.chip');
        if (chip) chip.classList.remove('dragging');
        dragEl = null;
        updateCounts();
    }
    function allowDropZone(z){
        z.addEventListener('dragover', e => { e.preventDefault(); z.classList.add('drag-over'); });
        z.addEventListener('dragleave', () => z.classList.remove('drag-over'));
        z.addEventListener('drop', e => {
            e.preventDefault();
            z.classList.remove('drag-over');
            if (dragEl){ z.appendChild(dragEl); updateCounts(); }
        });
    }
    document.querySelectorAll('.chip').forEach(ch => {
        ch.addEventListener('dragstart', onDragStart);
        ch.addEventListener('dragend', onDragEnd);
    });
    allowDropZone($chipsBucket);
    document.querySelectorAll('.drop-zone').forEach(allowDropZone);

    // Click-to-place helper
    document.addEventListener('click', (e) => {
        const chip = e.target.closest('.chip');
        if (!chip) return;
        const menu = document.createElement('div');
        Object.assign(menu.style, {
            position:'absolute', zIndex:'1000', background:'#fff',
            border:'1px solid var(--border)', borderRadius:'.75rem',
            padding:'.5rem', boxShadow:'var(--shadow-lg)',
            top:(chip.getBoundingClientRect().bottom + window.scrollY + 6)+'px',
            left:(chip.getBoundingClientRect().left   + window.scrollX)+'px'
        });
        const mkBtn = (label, cb)=>{
            const b = document.createElement('button');
            b.textContent = label;
            b.className = 'btn btn-secondary';
            b.style.cssText = 'display:block;margin:.25rem 0;width:100%;text-align:left;';
            b.onclick = (ev)=>{ ev.preventDefault(); cb(); try{document.body.removeChild(menu)}catch(_){ } };
            return b;
        };
        categories.forEach(catName=>{
            menu.appendChild(mkBtn(`Move to: ${catName}`, ()=>{
                const z = document.querySelector(`.drop-zone[data-drop="${catName.replace(/"/g, '\\"')}"]`);
                if (z) z.appendChild(chip);
                updateCounts();
            }));
        });
        menu.appendChild(mkBtn('Put back (top)', ()=>{ $chipsBucket.appendChild(chip); updateCounts(); }));
        document.querySelectorAll('.__chipMenu').forEach(m=>m.remove());
        menu.classList.add('__chipMenu');
        document.body.appendChild(menu);
        const onDoc = (ev)=>{ if (!menu.contains(ev.target)){ try{document.body.removeChild(menu)}catch(_){ } document.removeEventListener('click', onDoc); } };
        setTimeout(()=>document.addEventListener('click', onDoc),0);
    });

    // ---- Timer ----
    const $timerNode = document.getElementById('timeRemaining');
    if ($timerNode) $timerNode.textContent = fmtTime(timeRemaining);
    const timerInterval = setInterval(() => {
        timeRemaining--;
        if ($timerNode) $timerNode.textContent = fmtTime(timeRemaining);
        if ([60,30,10].includes(timeRemaining)) toast(`${timeRemaining}s left`, 'warning');
        if (timeRemaining <= 0){
            clearInterval(timerInterval);
            if (!submitted){ toast('Time up — submitting…', 'warning'); submitNow(); }
        }
    }, 1000);

    // ---- Hints / Reset / Submit ----
    $btnHint.addEventListener('click', ()=>{
        if (submitted) return;
        if (hintsUsed >= maxHints){ toast('No more hints available.', 'warning'); return; }
        hintsUsed++; $hintCount.textContent = hintsUsed;
        const hintsList = @json($hintsForJs);
        const hint = hintsList[Math.floor(Math.random() * hintsList.length)] || 'Think about what category this line belongs to.';
        toast('Hint: ' + hint, 'success');
    });
    $btnReset.addEventListener('click', ()=>{
        if (submitted) return;
        if (!confirm('Reset all placements?')) return;
        document.querySelectorAll('.drop-zone .chip').forEach(ch => $chipsBucket.appendChild(ch));
        updateCounts(); toast('All items reset.', 'success');
    });
    $btnSubmit.addEventListener('click', ()=>{ if (!submitted) submitNow(); });

    // ---- Submit & Grade ----
    function submitNow(){
        submitted = true;
        $btnSubmit.disabled = true; $btnHint.disabled = true; $btnReset.disabled = true;
        clearInterval(timerInterval);

        const placed = currentPlacements();
        let totalCount = 0, correct = 0;
        for (const item in answerMap){ totalCount++; if (placed[item] && placed[item] === answerMap[item]) correct++; }
        const rawPct = totalCount ? Math.round(100 * correct / totalCount) : 0;
        const hintPenalty = hintsUsed * 5;
        const finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));

        // update UI
        if ($statScore) $statScore.textContent = finalScore + '%';
        const stars = starsFor(finalScore);
        const starDisplay = stars > 0 ? '★'.repeat(stars) : '0';
        if ($statStars) $statStars.textContent = starDisplay;
        if ($metaStars) $metaStars.textContent = starDisplay;

        // hidden inputs
        $scoreInp.value = finalScore;
        $ansInp.value = JSON.stringify({ placements: placed, total: totalCount, correct: correct });

        // feedback
        const passReq = {{ (int)$level->pass_score }};
        if (finalScore >= passReq) toast(`Excellent! Score: ${finalScore}%`, 'success');
        else toast(`Score: ${finalScore}%. Keep practicing!`, 'error');

        // submit after short delay
        setTimeout(()=>{ if ($form.requestSubmit) $form.requestSubmit(); else $form.submit(); }, 1200);
    }

    // keyboard helpers
    document.addEventListener('keydown', (e) => {
        if (submitted) return;
        if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); submitNow(); }
        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey){ e.preventDefault(); $btnHint.click(); }
        if (e.key.toLowerCase() === 'r' && !e.ctrlKey && !e.altKey){ e.preventDefault(); $btnReset.click(); }
    });

    // init
    updateCounts();
})();
</script>
</x-app-layout>
