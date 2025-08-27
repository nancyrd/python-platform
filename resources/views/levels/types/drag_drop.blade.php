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
        "If the line changes a counter (e.g., i += 1), it’s usually an update/action.",
        "If it imports or defines things, it’s typically not part of branching/looping itself.",
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
    <div class="dd-header">
        <div class="container-fluid">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="lvl-badge"><span class="lvl-number">{{ $level->index }}</span></div>
                </div>
                <div class="col">
                    <div class="lvl-meta">
                        <div class="lvl-stage">{{ $level->stage->title }}</div>
                        <h2 class="lvl-title">{{ $level->title }}</h2>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="lvl-stats">
                        <div class="stat"><div class="stat-label">Score</div><div class="stat-value" id="statScore">0%</div></div>
                        <div class="stat"><div class="stat-label">Stars</div><div class="stat-value" id="statStars">0</div></div>
                        <div class="stat"><div class="stat-label">Time</div><div class="stat-value" id="timeRemaining">--:--</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>

<style>
:root{
  --bg-1:#0a1028; --bg-2:#14163b;
  --ink:#e9e7ff; --muted:#cfc8ff;
  --accent-1:#00b3ff; --accent-2:#b967ff; --accent-3:#05d9e8;
  --danger:#ff5a7a; --ok:#35d19b; --warn:#ffb020;
  --card:#121735; --border:rgba(255,255,255,.12);
  --chip:#171b3d; --chip-hover:#1f2450;
}
body{
  background:
    radial-gradient(1200px 800px at 20% -10%, rgba(0,179,255,.12), transparent 60%),
    radial-gradient(1000px 700px at 110% 10%, rgba(185,103,255,.12), transparent 60%),
    linear-gradient(180deg, var(--bg-1), var(--bg-2));
  color:var(--ink);
}

/* Header */
.dd-header{ background: rgba(10,16,40,.85); border-bottom:1px solid var(--border); padding:16px 0; }
.lvl-badge{ width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--accent-2),var(--accent-1)); display:flex;align-items:center;justify-content:center; box-shadow:0 10px 30px rgba(0,0,0,.25); }
.lvl-number{ font-weight:900;font-size:1.35rem;color:#0f0f1a; }
.lvl-meta .lvl-stage{ font-size:.85rem;color:var(--muted); letter-spacing:.02em; }
.lvl-meta .lvl-title{ margin:0;color:#fff;font-weight:800;letter-spacing:.2px; }
.lvl-stats{ display:flex;gap:18px; }
.stat{ min-width:90px;background:rgba(255,255,255,.06);border:1px solid var(--border);padding:10px 14px;border-radius:12px;text-align:center; }
.stat-label{ font-size:.75rem;color:var(--muted); }
.stat-value{ font-size:1.05rem;font-weight:800;color:#fff; }

/* Layout */
.wrap{ max-width:1160px; margin:22px auto; padding:0 16px; }

/* Lesson cards */
.card{
  background:rgba(255,255,255,.04); border:1px solid var(--border); border-radius:16px; padding:16px;
}
.card h3{ margin:0 0 8px 0; font-size:1.05rem; color:var(--accent-3); font-weight:800; }
.card pre{ background:#0d1330;border:1px solid var(--border);border-radius:12px;padding:12px 14px; color:#cfeaff; overflow:auto; margin:10px 0 0 0; }

/* Progress */
.progress-shell{ height:12px;background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:999px;overflow:hidden;margin:18px 0; }
.progress-bar{ height:100%; width:0%; background:linear-gradient(90deg,var(--accent-1),var(--accent-2)); transition:width .4s ease; }

/* Board: top items, bottom categories */
.board{ display:flex; flex-direction:column; gap:16px; }
.source{
  background:var(--card); border:1px solid var(--border); border-radius:14px; padding:12px;
}
.source-head{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px; }
.source-title{ font-weight:800; color:#fff; }
.chips{
  display:flex; flex-wrap:wrap; gap:10px;
}
.chip{
  user-select:none;
  display:inline-flex; align-items:center; gap:8px;
  background:var(--chip); border:1px solid var(--border); color:#e9e7ff;
  padding:10px 12px; border-radius:12px; cursor:grab; transition: background .12s ease, transform .12s ease, border-color .12s ease;
}
.chip:hover{ background:var(--chip-hover); transform: translateY(-1px); border-color:rgba(185,103,255,.45); }
.chip.dragging{ opacity:.75; border-color:var(--accent-1); box-shadow:0 0 0 3px rgba(0,179,255,.2) inset; cursor:grabbing; }
.chip .badge{ display:inline-block; font-size:.75rem; color:#bfe9ff; background:rgba(0,179,255,.12); border:1px solid rgba(0,179,255,.28); padding:2px 6px; border-radius:999px; }

/* Categories grid */
.cats{
  display:grid; gap:14px; grid-template-columns: 1fr;
}
@media(min-width:820px){ .cats{ grid-template-columns: 1fr 1fr; } }
@media(min-width:1140px){ .cats{ grid-template-columns: 1fr 1fr 1fr; } }

.cat{
  background:var(--card); border:1px solid var(--border); border-radius:14px; padding:12px;
  min-height: 120px; display:flex; flex-direction:column; gap:8px;
}
.cat-head{ display:flex; justify-content:space-between; align-items:center; }
.cat-name{ font-weight:800; color:#fff; }
.cat-count{ font-size:.85rem; color:var(--muted); }
.cat-drop{
  min-height:70px; border:1px dashed var(--border); border-radius:12px; padding:10px;
  display:flex; flex-wrap:wrap; gap:10px;
}
.cat-drop.over{ border-color:var(--accent-1); background:rgba(0,179,255,.06); }

/* Controls & meta */
.controls{ display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin:18px 0 6px 0; }
.btn{ border:none; border-radius:12px; padding:12px 18px; font-weight:800; letter-spacing:.2px; color:#0f1020; cursor:pointer; transition: transform .12s ease, box-shadow .2s ease; }
.btn:disabled{ opacity:.6; cursor:not-allowed; }
.btn-primary{ background:linear-gradient(135deg,var(--accent-1),var(--accent-2)); color:#0e1126; }
.btn-secondary{ background:linear-gradient(135deg,#5ad0ff,#a58aff); color:#0e1126; }
.btn-ghost{ background:transparent; color:var(--ink); border:1px solid var(--border); }
.btn:hover{ transform: translateY(-1px); box-shadow: 0 10px 22px rgba(0,0,0,.25); }

.meta{ display:flex; justify-content:space-between; gap:10px; margin-top:10px; color:var(--muted); font-size:.9rem; }
.meta .left{ display:flex; gap:10px; align-items:center; }
.meta .pill{ border:1px solid var(--border); padding:4px 8px; border-radius:999px; }

/* Toasts */
.toast-wrap{ position:fixed; top:16px; right:16px; display:flex; flex-direction:column; gap:8px; z-index:1000; }
.toast{ background:rgba(10,16,40,.9); border:1px solid var(--border); color:#fff; padding:10px 12px; border-radius:12px; font-weight:700; min-width:220px; }
.toast.ok{ border-color:rgba(53,209,155,.6); }
.toast.warn{ border-color:rgba(255,176,32,.6); }
.toast.err{ border-color:rgba(255,90,122,.6); }
</style>

<div class="wrap">
    @if($alreadyPassed)
        <div class="card" style="border-left:4px solid var(--ok);">
            <h3>Level Completed</h3>
            <p class="m-0">You’ve already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}. You can <a href="{{ route('levels.show', $level) }}?replay=1">replay</a> to improve your stars.</p>
        </div>
        <div style="height:12px;"></div>
    @endif

    <div class="card">
        <h3>Lesson</h3>
        <div style="white-space:pre-wrap; line-height:1.45;">{!! nl2br(e($level->instructions)) !!}</div>
        @if($introText)
            <pre class="mt-2" style="white-space:pre-wrap;">{!! e($introText) !!}</pre>
        @endif
    </div>

    <div class="progress-shell"><div class="progress-bar" id="progressBar"></div></div>

    <div class="card">
        <h3>How to answer</h3>
        <div style="white-space:pre-wrap;">{!! nl2br(e($uiInstrux)) !!}</div>
    </div>

    <form id="ddForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
        @csrf
        <input type="hidden" name="score" id="finalScore" value="0">
        <input type="hidden" name="answers" id="answersData" value="{}">

        <!-- Board -->
        <div class="board" style="margin-top:14px;">
            <!-- Top: Items bucket -->
            <div class="source">
                <div class="source-head">
                    <div class="source-title">Items to place</div>
                    <div class="muted" style="color:var(--muted)">Drag each chip into its correct category below.</div>
                </div>
                <div class="chips" id="chipsBucket">
                    @foreach($allItems as $txt)
                        <div class="chip" draggable="true" data-item="{{ $txt }}">
                            <span class="badge">drag</span>
                            <span class="txt">{{ $txt }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom: Categories grid -->
            <div class="cats" id="catsGrid">
                @foreach($categories as $catName => $items)
                    <div class="cat" data-category="{{ $catName }}">
                        <div class="cat-head">
                            <div class="cat-name">{{ $catName }}</div>
                            <div class="cat-count"><span class="count">0</span> placed</div>
                        </div>
                        <div class="cat-drop" data-drop="{{ $catName }}"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="controls">
            <button class="btn btn-primary" type="button" id="btnSubmit">Submit</button>
            <button class="btn btn-secondary" type="button" id="btnHint">Hint</button>
            <button class="btn btn-ghost" type="button" id="btnReset">Reset</button>
        </div>

        <div class="meta">
            <div class="left">
                <span class="pill">Pass score: {{ (int)$level->pass_score }}%</span>
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
  const $catsGrid    = document.getElementById('catsGrid');
  const $btnSubmit   = document.getElementById('btnSubmit');
  const $btnHint     = document.getElementById('btnHint');
  const $btnReset    = document.getElementById('btnReset');
  const $form        = document.getElementById('ddForm');
  const $scoreInp    = document.getElementById('finalScore');
  const $ansInp      = document.getElementById('answersData');

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
  function updateCounts(){
    document.querySelectorAll('.cat').forEach(cat=>{
      const n = cat.querySelectorAll('.cat-drop .chip').length;
      const c = cat.querySelector('.count'); if (c) c.textContent = n;
    });
    const total = document.querySelectorAll('.chip').length;
    const placed = document.querySelectorAll('.cat-drop .chip').length;
    const pct = total ? Math.round(100 * placed / total) : 0;
    $progress.style.width = pct + '%';
  }
  function currentPlacements(){
    // Return { itemText: categoryName | null }
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
    z.addEventListener('dragover', e => {
      e.preventDefault();
      z.classList.add('over');
    });
    z.addEventListener('dragleave', () => z.classList.remove('over'));
    z.addEventListener('drop', e => {
      e.preventDefault();
      z.classList.remove('over');
      if (dragEl){
        z.appendChild(dragEl);
        updateCounts();
      }
    });
  }

  // Make chips draggable
  document.querySelectorAll('.chip').forEach(ch => {
    ch.addEventListener('dragstart', onDragStart);
    ch.addEventListener('dragend', onDragEnd);
  });

  // Make bucket a valid drop zone to “remove” placement
  allowDropZone($chipsBucket);

  // Category drop zones
  document.querySelectorAll('.cat-drop').forEach(allowDropZone);

  // Click-to-place (accessibility / mobile aid): click a chip to toggle a quick chooser menu
  document.addEventListener('click', (e) => {
    const chip = e.target.closest('.chip');
    if (!chip) return;
    // Build a small chooser
    const menu = document.createElement('div');
    menu.style.position='absolute';
    menu.style.zIndex='100';
    menu.style.background='rgba(13,19,48,.98)';
    menu.style.border='1px solid var(--border)';
    menu.style.borderRadius='10px';
    menu.style.padding='8px';
    menu.style.boxShadow='0 10px 22px rgba(0,0,0,.35)';
    menu.style.top = (chip.getBoundingClientRect().bottom + window.scrollY + 6) + 'px';
    menu.style.left = (chip.getBoundingClientRect().left + window.scrollX) + 'px';

    const mkBtn = (label, cb)=>{
      const b = document.createElement('button');
      b.textContent = label;
      b.className = 'btn btn-ghost';
      b.style.display='block';
      b.style.margin='6px 0';
      b.onclick = (ev)=>{ ev.preventDefault(); cb(); document.body.removeChild(menu); };
      return b;
    };
    categories.forEach(catName=>{
      menu.appendChild(mkBtn(`Move to: ${catName}`, ()=>{
        const z = document.querySelector(`.cat-drop[data-drop="${catName.replace(/"/g, '\\"')}"]`);
        if (z) z.appendChild(chip);
        updateCounts();
      }));
    });
    menu.appendChild(mkBtn('Put back (top)', ()=>{
      $chipsBucket.appendChild(chip);
      updateCounts();
    }));

    // Remove any existing menu and place new
    document.querySelectorAll('.__chipMenu').forEach(m=>m.remove());
    menu.classList.add('__chipMenu');
    document.body.appendChild(menu);

    // Auto-remove on outside click
    const onDoc = (ev)=>{
      if (!menu.contains(ev.target)){ try{ document.body.removeChild(menu); }catch(_){}
        document.removeEventListener('click', onDoc);
      }
    };
    setTimeout(()=>document.addEventListener('click', onDoc), 0);
  });

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

  // ---- Hints / Reset / Submit ----
  document.getElementById('btnHint').addEventListener('click', ()=>{
    if (submitted) return;
    if (hintsUsed >= {{ $maxHints }}){
      toast('No more hints available.', 'warn');
      return;
    }
    hintsUsed++;
    document.getElementById('hintCount').textContent = hintsUsed;
    const list = @json($hintsForJs);
    const hint = list[Math.floor(Math.random() * list.length)] || 'Think about what category this line belongs to.';
    toast('Hint: ' + hint, 'ok');
  });

  document.getElementById('btnReset').addEventListener('click', ()=>{
    if (submitted) return;
    if (!confirm('Reset all placements?')) return;
    // Move all chips back to top
    document.querySelectorAll('.cat-drop .chip').forEach(ch => $chipsBucket.appendChild(ch));
    updateCounts();
    toast('Cleared.', 'ok');
  });

  document.getElementById('btnSubmit').addEventListener('click', ()=>{
    if (submitted) return;
    submitNow();
  });

  // ---- Submit & Grade ----
  function submitNow(){
    submitted = true;
    document.getElementById('btnSubmit').disabled = true;
    document.getElementById('btnHint').disabled  = true;
    document.getElementById('btnReset').disabled = true;
    clearInterval(t);

    const placed = currentPlacements(); // {item: category|null}
    // Compute raw correctness: only items placed into exactly the right category count.
    let totalCount = 0, correct = 0;
    for (const item in answerMap){
      totalCount++;
      if (placed[item] && placed[item] === answerMap[item]) correct++;
    }
    const rawPct = totalCount ? Math.round(100 * correct / totalCount) : 0;
    const hintPenalty = hintsUsed * 5;
    const finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));

    // Update stars + UI
    $statScore.textContent = finalScore + '%';
    const stars = starsFor(finalScore);
    $statStars.textContent = '★'.repeat(stars) || '0';
    if ($metaStars) $metaStars.textContent = '★'.repeat(stars) || '0';

    // Fill hidden fields
    $scoreInp.value = finalScore;
    // Save a compact answer record: only placed items with their target category
    // { placements: { "item text": "Category or null" }, total, correct }
    $ansInp.value = JSON.stringify({ placements: placed, total: totalCount, correct });

    // Feedback
    const passReq = {{ (int)$level->pass_score }};
    if (finalScore >= passReq){
      toast(`Great job! Score ${finalScore}%`, 'ok');
    } else {
      toast(`Score ${finalScore}%. Keep practicing!`, 'err');
    }

    // Submit after a short delay so feedback is visible
    setTimeout(()=>{
      if ($form.requestSubmit) $form.requestSubmit();
      else $form.submit();
    }, 900);
  }

  // Keyboard helpers
  document.addEventListener('keydown', (e) => {
    if (submitted) return;
    if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); submitNow(); }
    if (e.key.toLowerCase() === 'h'){ e.preventDefault(); document.getElementById('btnHint').click(); }
    if (e.key.toLowerCase() === 'r'){ e.preventDefault(); document.getElementById('btnReset').click(); }
  });

  // First counts
  updateCounts();
})();
</script>
</x-app-layout>
