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

<x-slot name="header">
    <div class="reorder-header">
        <div class="container-fluid">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="lvl-badge">
                        <span class="lvl-number">{{ $level->index }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="lvl-meta">
                        <div class="lvl-stage">{{ $level->stage->title }}</div>
                        <h2 class="lvl-title">{{ $level->title }}</h2>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="lvl-stats">
                        <div class="stat">
                            <div class="stat-label">Score</div>
                            <div class="stat-value" id="statScore">0%</div>
                        </div>
                        <div class="stat">
                            <div class="stat-label">Stars</div>
                            <div class="stat-value" id="statStars">0</div>
                        </div>
                        <div class="stat">
                            <div class="stat-label">Time</div>
                            <div class="stat-value" id="timeRemaining">--:--</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>

<style>
:root{
  --bg-1:#0a1028;
  --bg-2:#14163b;
  --ink:#e9e7ff;
  --muted:#cfc8ff;
  --accent-1:#00b3ff;
  --accent-2:#b967ff;
  --accent-3:#05d9e8;
  --danger:#ff5a7a;
  --ok:#35d19b;
  --warn:#ffb020;
  --card:#121735;
  --border:rgba(255,255,255,.12);
}

body{ background: radial-gradient(1200px 800px at 20% -10%, rgba(0,179,255,.12), transparent 60%), radial-gradient(1000px 700px at 110% 10%, rgba(185,103,255,.12), transparent 60%), linear-gradient(180deg, var(--bg-1), var(--bg-2)); color:var(--ink); }

/* Header */
.reorder-header{ background: rgba(10,16,40,.85); border-bottom:1px solid var(--border); padding:16px 0; }
.lvl-badge{ width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--accent-2),var(--accent-1)); display:flex;align-items:center;justify-content:center; box-shadow:0 10px 30px rgba(0,0,0,.25); }
.lvl-number{ font-weight:900;font-size:1.35rem;color:#0f0f1a; }
.lvl-meta .lvl-stage{ font-size:.85rem;color:var(--muted); letter-spacing:.02em; }
.lvl-meta .lvl-title{ margin:0;color:#fff;font-weight:800;letter-spacing:.2px; }
.lvl-stats{ display:flex;gap:18px; }
.stat{ min-width:90px;background:rgba(255,255,255,.06);border:1px solid var(--border);padding:10px 14px;border-radius:12px;text-align:center; }
.stat-label{ font-size:.75rem;color:var(--muted); }
.stat-value{ font-size:1.05rem;font-weight:800;color:#fff; }

/* Container */
.wrap{ max-width:1040px;margin:24px auto;padding:0 16px; }

/* Lesson */
.lesson{ background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:16px;padding:18px 18px; }
.lesson h3{ margin:0 0 8px 0;font-size:1.05rem;color:var(--accent-3);font-weight:800; }
.lesson pre{ background:#0d1330;border:1px solid var(--border);border-radius:12px;padding:12px 14px; color:#cfeaff; overflow:auto; margin:10px 0 0 0; }

/* Progress */
.progress-shell{ height:12px;background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:999px;overflow:hidden;margin:18px 0; }
.progress-bar{ height:100%; width:0%; background:linear-gradient(90deg,var(--accent-1),var(--accent-2)); transition:width .4s ease; }

/* Reorder board */
.board{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:14px; }
.board-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
.board-title{ font-weight:800; color:#fff; }
.board-sub{ color:var(--muted); font-size:.9rem; }

.list{ list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:10px; }
.item{
  display:flex; gap:10px; align-items:center;
  background:rgba(255,255,255,.03);
  border:1px solid var(--border);
  border-radius:12px; padding:10px 12px;
}
.item.dragging{ opacity:.7; border-color:var(--accent-1); box-shadow:0 0 0 3px rgba(0,179,255,.2) inset; }
.handle{ width:34px; height:34px; border-radius:8px; background:rgba(255,255,255,.06); display:flex; align-items:center; justify-content:center; color:var(--muted); cursor:grab; user-select:none; }
.code{ white-space:pre; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace; color:#cfeaff; }

/* Controls */
.controls{ display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin:18px 0 6px 0; }
.btn{ border:none; border-radius:12px; padding:12px 18px; font-weight:800; letter-spacing:.2px; color:#0f1020; cursor:pointer; transition: transform .12s ease, box-shadow .2s ease; }
.btn:disabled{ opacity:.6; cursor:not-allowed; }
.btn-primary{ background:linear-gradient(135deg,var(--accent-1),var(--accent-2)); color:#0e1126; }
.btn-secondary{ background:linear-gradient(135deg,#5ad0ff,#a58aff); color:#0e1126; }
.btn-ghost{ background:transparent; color:var(--ink); border:1px solid var(--border); }
.btn:hover{ transform: translateY(-1px); box-shadow: 0 10px 22px rgba(0,0,0,.25); }

/* Toasts */
.toast-wrap{ position:fixed; top:16px; right:16px; display:flex; flex-direction:column; gap:8px; z-index:1000; }
.toast{ background:rgba(10,16,40,.9); border:1px solid var(--border); color:#fff; padding:10px 12px; border-radius:12px; font-weight:700; min-width:220px; }
.toast.ok{ border-color:rgba(53,209,155,.6); }
.toast.warn{ border-color:rgba(255,176,32,.6); }
.toast.err{ border-color:rgba(255,90,122,.6); }

/* Meta */
.meta{ display:flex; justify-content:space-between; gap:10px; margin-top:10px; color:var(--muted); font-size:.9rem; }
.meta .left{ display:flex; gap:10px; align-items:center; }
.meta .pill{ border:1px solid var(--border); padding:4px 8px; border-radius:999px; }
</style>

<div class="wrap">
    @if($alreadyPassed)
        <div class="lesson" style="border-left:4px solid var(--ok);">
            <h3>Level Completed</h3>
            <p class="m-0">You’ve already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}. You can <a href="{{ route('levels.show', $level) }}?replay=1">replay</a> to improve your stars.</p>
        </div>
        <div style="height:12px;"></div>
    @endif

    <div class="lesson">
        <h3>Lesson</h3>
        <div style="white-space:pre-wrap; line-height:1.45;">{!! nl2br(e($level->instructions)) !!}</div>
    </div>

    <div class="progress-shell"><div class="progress-bar" id="progressBar"></div></div>

    <div class="board">
        <div class="board-header">
            <div class="board-title">Drag to reorder</div>
            <div class="board-sub">Place the lines into the correct sequence.</div>
        </div>

        <!-- The draggable list -->
        <ul id="reorderList" class="list">
            @foreach($shuffled as $idx => $line)
                <li class="item" draggable="true" data-line="{{ $line }}">
                    <div class="handle" title="Drag handle">≡</div>
                    <pre class="code">{{ $line }}</pre>
                </li>
            @endforeach
        </ul>
    </div>

    <form id="reorderForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
        @csrf
        <input type="hidden" name="score" id="finalScore" value="0">
        <input type="hidden" name="answers" id="answersData" value="[]">

        <div class="controls">
            <button class="btn btn-primary" type="button" id="btnCheck">Submit Order</button>
            <button class="btn btn-secondary" type="button" id="btnHint">Show Hint</button>
            <button class="btn btn-ghost" type="button" id="btnReset">Reset</button>
        </div>

        <div class="meta">
            <div class="left">
                <span class="pill">Pass score: {{ $passScore }}%</span>
                @if(!is_null($savedScore)) <span class="pill">Best: {{ (int)$savedScore }}%</span> @endif
                <span class="pill">Stars: <span id="metaStars">0</span></span>
            </div>
            <div>Tips used: <span id="hintCount">0</span></div>
        </div>
    </form>
</div>

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
    function updateProgressBar(){
        // For reorder, progress = % of items that are currently at the correct index.
        const current = getCurrentOrder();
        let correct = 0;
        for (let i=0;i<current.length;i++){
            if (current[i] === (correctOrder[i] ?? null)) correct++;
        }
        const pct = current.length ? Math.round(100 * correct / current.length) : 0;
        $progress.style.width = pct + '%';
    }
    function getCurrentOrder(){
        return Array.from($list.querySelectorAll('.item')).map(li => li.getAttribute('data-line'));
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
    $timer.textContent = fmtTime(timeRemaining);
    const t = setInterval(() => {
        timeRemaining--;
        $timer.textContent = fmtTime(timeRemaining);
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
        $hintCount.textContent = hintsUsed;
        const hint = hints[Math.floor(Math.random() * hints.length)] || 'Think about the typical loop order.';
        toast('Hint: ' + hint, 'ok');
    });

    $btnReset.addEventListener('click', () => {
        if (submitted) return;
        if (confirm('Reset the list to the initial shuffled order?')){
            // Reload page (simplest & safest to preserve initial shuffle)
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
        $statScore.textContent = finalScore + '%';
        const stars = starsFor(finalScore);
        $statStars.textContent = '★'.repeat(stars) || '0';
        if ($metaStars) $metaStars.textContent = '★'.repeat(stars) || '0';

        // Hidden fields
        $scoreInp.value = finalScore;
        $ansInp.value   = JSON.stringify(current);

        if (finalScore >= passScore){
            toast(`Great job! Score ${finalScore}%`, 'ok');
        } else {
            toast(`Score ${finalScore}%. Keep practicing!`, 'err');
        }

        // Submit after a short delay so learners see feedback
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
})();
</script>
</x-app-layout>
