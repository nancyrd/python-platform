<x-app-layout>
@php
    // ---------- Safe content extraction ----------
    $content      = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $intro        = $content['intro'] ?? null;
    $instructions = $level->instructions ?? ($content['instructions'] ?? null);
    $pairs        = $content['pairs'] ?? [];
    $hints        = $content['hints'] ?? [];
    $timeLimit    = (int)($content['time_limit'] ?? 240);
    $maxHints     = (int)($content['max_hints']  ?? 3);

    // ---------- Header stats ----------
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;
    $savedStars    = $levelProgress->stars ?? 0;

    // ---------- JS payload ----------
    $payload = [
        'pairs'      => array_values(array_filter($pairs, fn($p) => isset($p['left'], $p['right']))),
        'hints'      => $hints,
        'time_limit' => $timeLimit,
        'max_hints'  => $maxHints,
    ];
@endphp

<script>
  window.LEVEL_DATA = {!! json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
</script>

{{-- Header (same design family) --}}
<x-slot name="header">
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
:root{
    /* Purple palette */
    --primary-purple:#7c3aed; --secondary-purple:#a855f7; --light-purple:#c084fc; --purple-subtle:#f3e8ff;
    /* Grays */
    --gray-50:#f8fafc; --gray-100:#f1f5f9; --gray-200:#e2e8f0; --gray-300:#cbd5e1; --gray-400:#94a3b8;
    --gray-500:#64748b; --gray-600:#475569; --gray-700:#334155; --gray-800:#1e293b; --gray-900:#0f172a;
    /* Semantic */
    --success:#10b981; --success-light:#dcfce7; --warning:#f59e0b; --warning-light:#fef3c7; --danger:#ef4444; --danger-light:#fecaca;
    /* UI */
    --background:#ffffff; --border:#e2e8f0; --text-primary:#1e293b; --text-secondary:#475569; --text-muted:#64748b;
    --shadow-sm:0 1px 2px rgba(0,0,0,.05); --shadow:0 1px 3px rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
    --shadow-md:0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
    --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
}

/* Page backdrop */
body{
    background: linear-gradient(135deg,
        rgba(124,58,237,.03) 0%,
        rgba(168,85,247,.02) 50%,
        rgba(248,250,252,1) 100%);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
}

/* Header (shared) */
.level-header{ background:linear-gradient(135deg, rgba(124,58,237,.05) 0%, rgba(168,85,247,.03) 100%); border-bottom:1px solid var(--border); backdrop-filter: blur(10px); }
.header-container{ display:flex; align-items:center; justify-content:space-between; padding:1.5rem 2rem; gap:2rem; }
.header-left{ display:flex; align-items:center; gap:1.5rem; flex:1; min-width:0; }
.level-badge{ width:4rem; height:4rem; border-radius:1rem; background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-md); }
.level-number{ font-weight:900; font-size:1.25rem; color:#fff; }
.level-info{ flex:1; min-width:0; }
.breadcrumb{ display:flex; align-items:center; gap:.5rem; font-size:.875rem; color:var(--text-muted); margin-bottom:.25rem; }
.breadcrumb-item.type{ text-transform:capitalize; color:var(--primary-purple); font-weight:500; }
.separator{ opacity:.6; }
.stage-title{ font-size:1.5rem; font-weight:700; margin:0; line-height:1.2; color:var(--text-primary); }
.level-title{ font-size:1rem; color:var(--text-secondary); margin-top:.25rem; }
.header-right{ flex-shrink:0; }
.stats-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.stat-item{ text-align:center; padding:.75rem 1rem; background:#fff; border:1px solid var(--border); border-radius:.75rem; box-shadow:var(--shadow-sm); min-width:5rem; }
.stat-label{ font-size:.75rem; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.05em; }
.stat-value{ font-size:1.125rem; font-weight:700; color:var(--text-primary); margin-top:.25rem; }

/* Full-bleed helpers */
.full-bleed{ width:100vw; margin-left:calc(50% - 50vw); margin-right:calc(50% - 50vw); }
.edge-pad{ padding:1.25rem clamp(12px, 3vw, 32px); }

/* Cards / containers */
.card{ background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem 1.5rem; box-shadow:var(--shadow-sm); }
.card.accent{ border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff); }
.section-title{ font-size:1.125rem; font-weight:700; margin:0 0 1rem 0; color:var(--text-primary); }

.items-container{ background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
.items-header{ display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.items-title{ font-size:1.05rem; font-weight:700; }

.progress-container{ flex:1; max-width:260px; }
.progress-bar{ height:.5rem; background:var(--gray-200); border-radius:.25rem; overflow:hidden; }
.progress-fill{ height:100%; width:0%; background:linear-gradient(90deg, var(--primary-purple), var(--secondary-purple)); border-radius:.25rem; transition: width .3s ease; }

/* Match Pairs UI */
.match-grid{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1rem; }
@media (max-width: 900px){ .match-grid{ grid-template-columns:1fr; } }

.column-card{ background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem; box-shadow:var(--shadow-sm); }
.column-title{ text-align:center; font-weight:800; color:var(--primary-purple); margin-bottom:.5rem; }

.list{ display:flex; flex-direction:column; gap:.5rem; }
.chip{ display:block; width:100%; text-align:left; user-select:none; background:var(--gray-50); border:1px solid var(--border); border-radius:.75rem; padding:.8rem 1rem; font-weight:700; color:var(--text-primary); cursor:pointer; transition:transform .12s ease, box-shadow .2s ease, border-color .12s ease; }
.chip:hover{ transform:translateY(-1px); border-color:var(--primary-purple); box-shadow:var(--shadow); }
.chip.selected{ box-shadow:0 0 0 3px rgba(124,58,237,.25) inset; border-color:var(--primary-purple); background:var(--purple-subtle); }
.chip.correct{ box-shadow:0 0 0 3px rgba(16,185,129,.25) inset; border-color:var(--success); background:#fff; }
.chip.locked{ opacity:.8; cursor:default; }

.pair-stats{ display:flex; gap:.75rem; justify-content:center; align-items:center; margin-top:.75rem; flex-wrap:wrap; }
.pill{ background:#fff; border:1px solid var(--border); padding:.35rem .75rem; border-radius:999px; font-weight:600; color:var(--text-secondary); }

/* Controls */
.controls-container{ display:flex; justify-content:center; gap:1rem; margin:1.25rem 0; flex-wrap:wrap; }
.btn{ display:inline-flex; align-items:center; justify-content:center; gap:.5rem; padding:.7rem 1.1rem; border:none; border-radius:.75rem; font-weight:800; font-size:.92rem; cursor:pointer; transition:all .18s ease; text-decoration:none; }
.btn:disabled{ opacity:.5; cursor:not-allowed; }
.btn-primary{ background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
.btn-primary:hover:not(:disabled){ transform:translateY(-2px); box-shadow:var(--shadow-lg); }
.btn-secondary{ background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border); }
.btn-secondary:hover:not(:disabled){ background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow); }
.btn-ghost{ background:transparent; color:var(--text-secondary); border:1px solid var(--border); }
.btn-ghost:hover:not(:disabled){ background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple); }

/* Meta bar */
.meta-container{ display:flex; justify-content:space-between; align-items:center; background:var(--gray-50); border-top:1px solid var(--border); font-size:.875rem; color:var(--text-muted); }

/* Toasts */
.toast-container{ position:fixed; top:1rem; right:1rem; display:flex; flex-direction:column; gap:.5rem; z-index:1000; }
.toast{ background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:600; min-width:280px; box-shadow:var(--shadow-lg); animation:slideIn .3s ease; }
.toast.ok{   border-left:4px solid var(--success);  background:linear-gradient(135deg, var(--success-light), #fff); }
.toast.warn{ border-left:4px solid var(--warning);  background:linear-gradient(135deg, var(--warning-light), #fff); }
.toast.err{  border-left:4px solid var(--danger);   background:linear-gradient(135deg, var(--danger-light), #fff); }
@keyframes slideIn{ from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)} }

/* Responsive */
@media (max-width:768px){
  .header-container{ flex-direction:column; align-items:stretch; gap:1rem; padding:1rem; }
  .edge-pad{ padding:1rem; }
}
</style>

{{-- MAIN (full-bleed) --}}
<div class="full-bleed">

    {{-- Already passed note --}}
    @if($alreadyPassed)
    <div class="edge-pad">
        <div class="card accent" style="margin-bottom:1rem;">
            <div class="section-title" style="color:var(--primary-purple);">Level Completed</div>
            <p class="m-0">
                You’ve already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}.
                You can <a href="{{ route('levels.show', $level) }}?replay=1" style="color:var(--primary-purple); text-decoration:underline;">replay</a> to improve your stars.
            </p>
        </div>
    </div>
    @endif

    {{-- Instructions (top) --}}
    <div class="edge-pad">
        <div class="card accent" id="instructionsCard" style="margin-bottom:1rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <div class="section-title">How to play</div>
                <button class="btn btn-ghost" type="button" id="toggleInstrux" aria-expanded="true">
                    <i class="fas fa-chevron-up"></i> Collapse
                </button>
            </div>
            <div id="instruxBody" style="white-space:pre-wrap;">
                {!! nl2br(e($instructions ?? 'Tap an item on the left, then its matching item on the right. Correct pairs lock. Finish when all pairs are matched.')) !!}
                @if($intro)
                    <div class="mt-2" style="white-space:pre-wrap;">{!! nl2br(e($intro)) !!}</div>
                @endif
            </div>
        </div>

        {{-- Header row with progress --}}
        <div class="items-container">
            <div class="items-header">
                <div class="items-title">Match Pairs</div>
                <div class="progress-container">
                    <div class="progress-bar"><div class="progress-fill" id="progressBar"></div></div>
                </div>
            </div>
        </div>

        {{-- Game grid --}}
        <div class="match-grid">
            <div class="column-card">
                <div class="column-title">Left</div>
                <div class="list" id="leftList"><!-- filled by JS --></div>
            </div>
            <div class="column-card">
                <div class="column-title">Right</div>
                <div class="list" id="rightList"><!-- filled by JS --></div>
            </div>
        </div>

        {{-- Row stats --}}
        <div class="pair-stats">
            <span class="pill">Matched: <strong id="matchedCount">0</strong></span>
            <span class="pill">Remaining: <strong id="remainingCount">0</strong></span>
        </div>

        {{-- Controls --}}
        <div class="controls-container">
            <button class="btn btn-primary"   type="button" id="btnFinish"><i class="fas fa-check"></i> Finish</button>
            <button class="btn btn-secondary" type="button" id="btnHint"><i class="fas fa-lightbulb"></i> Hint</button>
            <button class="btn btn-ghost"     type="button" id="btnReset"><i class="fas fa-rotate-left"></i> Reset</button>
        </div>

        {{-- Hidden submit --}}
        <form method="POST" action="{{ route('levels.submit', $level) }}" id="scoreForm" style="display:none;">
            @csrf
            <input type="hidden" name="score"   id="finalScore"     value="0">
            <input type="hidden" name="answers" id="answersPayload" value="">
        </form>
    </div>
</div>

{{-- Meta bar --}}
<div class="meta-container full-bleed edge-pad">
    <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
        <span class="pill">Pass score: {{ (int)$level->pass_score }}%</span>
        @if(!is_null($savedScore)) <span class="pill">Best: {{ (int)$savedScore }}%</span> @endif
        <span class="pill">Stars: <span id="metaStars">0</span></span>
    </div>
    <div>Tips used: <span id="hintCount">0</span></div>
</div>

{{-- Toasts & Celebration --}}
<div class="toast-container" id="toastWrap"></div>
<div id="celebration" style="position:fixed; inset:0; pointer-events:none; z-index:9999;"></div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
  // ------- Data -------
  const data      = window.LEVEL_DATA || {};
  const PAIRS     = Array.isArray(data.pairs) ? data.pairs.slice() : [];
  const HINTS     = Array.isArray(data.hints) ? data.hints.slice() : [];
  const TIME_LIMIT= Number.isFinite(data.time_limit) ? data.time_limit : 240;
  const MAX_HINTS = Number.isFinite(data.max_hints)  ? data.max_hints  : 3;

  // ------- State -------
  let leftItems = [];   // [{id,text}]
  let rightItems= [];   // [{id,text}]
  let mapping   = {};   // {Lx: Ry} (correct)
  let pickedLeft  = null;
  let pickedRight = null;
  let locked = {};     // {Lx: Ry} confirmed
  let matches = {};    // {Lx: Ry} chosen
  let hintsUsed = 0;
  let timeRemaining = TIME_LIMIT;

  // ------- DOM -------
  const $leftList  = document.getElementById('leftList');
  const $rightList = document.getElementById('rightList');
  const $progress  = document.getElementById('progressBar');
  const $matched   = document.getElementById('matchedCount');
  const $remain    = document.getElementById('remainingCount');
  const $statScore = document.getElementById('statScore');
  const $statStars = document.getElementById('statStars');
  const $metaStars = document.getElementById('metaStars');
  const $timeEl    = document.getElementById('timeRemaining');
  const $toastWrap = document.getElementById('toastWrap');
  const $celebr    = document.getElementById('celebration');
  const $btnFinish = document.getElementById('btnFinish');
  const $btnHint   = document.getElementById('btnHint');
  const $btnReset  = document.getElementById('btnReset');
  const $hintCount = document.getElementById('hintCount');

  // Instructions toggler
  const $toggleInstrux = document.getElementById('toggleInstrux');
  const $instruxBody   = document.getElementById('instruxBody');
  $toggleInstrux.addEventListener('click', () => {
    const hidden = $instruxBody.classList.toggle('d-none');
    $toggleInstrux.innerHTML = hidden
      ? '<i class="fas fa-chevron-down"></i> Expand'
      : '<i class="fas fa-chevron-up"></i> Collapse';
    $toggleInstrux.setAttribute('aria-expanded', String(!hidden));
  });

  // ------- Helpers -------
  const shuffle = a => { for(let i=a.length-1;i>0;i--){ const j=Math.floor(Math.random()*(i+1)); [a[i],a[j]]=[a[j],a[i]]; } return a; };
  const pad = n => String(n).padStart(2,'0');
  const fmt = s => `${pad(Math.floor(s/60))}:${pad(s%60)}`;

  function toast(msg, kind='ok'){
    const el = document.createElement('div');
    el.className = `toast ${kind}`;
    el.textContent = msg;
    $toastWrap.appendChild(el);
    setTimeout(()=>el.remove(), 2200);
  }
  function starsFor(score){
    if(score>=90) return 3; if(score>=70) return 2; if(score>=50) return 1; return 0;
  }
  function updateProgress(){
    const total = leftItems.length;
    const done  = Object.keys(locked).length;
    const pct   = total ? Math.round(100*done/total) : 0;
    $progress.style.width = pct + '%';
    $matched.textContent = done;
    $remain.textContent  = Math.max(0, total - done);
  }
  function updateTime(){
    $timeEl.textContent = fmt(timeRemaining);
  }
  function confetti(){
    for(let i=0;i<80;i++){
      const d = document.createElement('div');
      Object.assign(d.style, {
        position:'absolute', top:'-10px', left: (Math.random()*100)+'vw',
        width:'10px', height:'10px', borderRadius:'2px',
        background: ['#ffd700','#4ade80','#60a5fa','#f472b6','#f59e0b'][Math.floor(Math.random()*5)],
        transform:`rotate(${Math.random()*360}deg)`,
        animation:`fall ${2.6 + Math.random()*1.8}s linear ${Math.random()}s forwards`
      });
      $celebr.appendChild(d);
      setTimeout(()=>d.remove(), 5000);
    }
  }
  const style = document.createElement('style');
  style.textContent = `@keyframes fall{to{transform:translateY(110vh) rotate(720deg); opacity:.2}}`; document.head.appendChild(style);

  // ------- Build ------
  function init(){
    const canonical = PAIRS.map((p, idx) => ({
      left:  { id: 'L'+idx, text: String(p.left)  },
      right: { id: 'R'+idx, text: String(p.right) },
    }));
    leftItems  = canonical.map(c=>c.left);
    rightItems = canonical.map(c=>c.right);
    mapping    = canonical.reduce((m,c)=>{ m[c.left.id] = c.right.id; return m; }, {});
    shuffle(leftItems); shuffle(rightItems);
    renderLists();
    timeRemaining = TIME_LIMIT;
    updateTime(); startTimer();
    updateProgress();
  }

  function renderLists(){
    $leftList.innerHTML = '';
    $rightList.innerHTML = '';
    leftItems.forEach(item=>{
      const b = document.createElement('button');
      b.type='button'; b.className='chip'; b.dataset.id=item.id; b.textContent=item.text;
      b.addEventListener('click', ()=>pickLeft(item.id, b));
      $leftList.appendChild(b);
    });
    rightItems.forEach(item=>{
      const b = document.createElement('button');
      b.type='button'; b.className='chip'; b.dataset.id=item.id; b.textContent=item.text;
      b.addEventListener('click', ()=>pickRight(item.id, b));
      $rightList.appendChild(b);
    });
  }

  function clearSelections(){
    document.querySelectorAll('.chip.selected').forEach(el=>el.classList.remove('selected'));
    pickedLeft = pickedRight = null;
  }

  function lockPair(L, R){
    locked[L] = R;
    matches[L]= R;
    const lEl = $leftList.querySelector(`[data-id="${L}"]`);
    const rEl = $rightList.querySelector(`[data-id="${R}"]`);
    if (lEl){ lEl.classList.add('correct','locked'); lEl.disabled=true; }
    if (rEl){ rEl.classList.add('correct','locked'); rEl.disabled=true; }
    clearSelections(); updateProgress();

    // complete?
    if (Object.keys(locked).length === leftItems.length){
      finish();
    }
  }

  function pickLeft(id, el){
    if (locked[id]) return;
    if (pickedLeft === id){ el.classList.remove('selected'); pickedLeft=null; return; }
    document.querySelectorAll('#leftList .chip').forEach(x=>x.classList.remove('selected'));
    pickedLeft = id; el.classList.add('selected');
    if (pickedRight) tryPair();
  }
  function pickRight(id, el){
    if (Object.values(locked).includes(id)) return;
    if (pickedRight === id){ el.classList.remove('selected'); pickedRight=null; return; }
    document.querySelectorAll('#rightList .chip').forEach(x=>x.classList.remove('selected'));
    pickedRight = id; el.classList.add('selected');
    if (pickedLeft) tryPair();
  }
  function tryPair(){
    const correctR = mapping[pickedLeft];
    if (pickedRight === correctR){
      toast('Nice match!', 'ok');
      lockPair(pickedLeft, pickedRight);
    } else {
      toast('Not a match.', 'warn');
      clearSelections();
    }
  }

  // ------- Timer -------
  let tHandle = null;
  function startTimer(){
    if (tHandle) clearInterval(tHandle);
    tHandle = setInterval(()=>{
      timeRemaining--; updateTime();
      if ([60,30,10].includes(timeRemaining)) toast(`${timeRemaining}s left`, 'warn');
      if (timeRemaining <= 0){
        clearInterval(tHandle);
        toast("Time's up — finishing…", 'warn');
        finish(true);
      }
    }, 1000);
  }

  // ------- Scoring / Submit -------
  function computeScore(){
    const total = Object.keys(mapping).length;
    const correct= Object.keys(locked).length;
    const base = total ? Math.round(100 * correct / total) : 0;
    const penalty = hintsUsed * 5;
    const timeBonus = Math.max(0, Math.floor(timeRemaining / 10));
    return Math.min(100, Math.max(0, base - penalty + timeBonus));
  }

  function finish(timeout=false){
    if (tHandle) clearInterval(tHandle);

    const score = computeScore();
    const stars = starsFor(score);
    $statScore.textContent = score + '%';
    $statStars.textContent = stars ? '★'.repeat(stars) : '0';
    if ($metaStars) $metaStars.textContent = stars ? '★'.repeat(stars) : '0';

    // payload as { leftText: rightText|null }
    const payload = {};
    leftItems.concat().sort((a,b)=>a.id.localeCompare(b.id)).forEach(li=>{
      const rId = matches[li.id] ?? null;
      const rightText = (rightItems.find(r=>r.id===rId)||{}).text ?? null;
      payload[li.text] = rightText;
    });

    document.getElementById('finalScore').value     = score;
    document.getElementById('answersPayload').value = JSON.stringify(payload);

    if (!timeout){
      if (score >= 90){ toast('Perfect Match! 🎉', 'ok'); confetti(); }
      else if (score >= 70){ toast(`Great job — ${score}%`, 'ok'); }
      else { toast(`Keep practicing — ${score}%`, 'warn'); }
    }

    // disable UI
    document.querySelectorAll('.chip').forEach(b=>b.disabled=true);
    $btnFinish.disabled = $btnHint.disabled = $btnReset.disabled = true;

    setTimeout(()=>{
      const form = document.getElementById('scoreForm');
      if (form.requestSubmit) form.requestSubmit(); else form.submit();
    }, 1000);
  }

  // ------- Buttons -------
  document.getElementById('btnFinish').addEventListener('click', ()=>finish(false));
  document.getElementById('btnReset').addEventListener('click', ()=>{
    if (confirm('Reset this level?')) location.reload();
  });
  document.getElementById('btnHint').addEventListener('click', ()=>{
    if (hintsUsed >= MAX_HINTS) return toast('No more hints available.', 'warn');
    hintsUsed++; $hintCount.textContent = hintsUsed;
    const hint = HINTS.length ? HINTS[(hintsUsed-1) % HINTS.length] : 'Look for obvious conceptual pairs first.';
    toast('Hint: ' + hint, 'ok');

    // Nudge one open left chip
    const openLeft = leftItems.filter(li => !locked[li.id]);
    if (openLeft.length){
      const li = openLeft[Math.floor(Math.random()*openLeft.length)];
      const el = $leftList.querySelector(`[data-id="${li.id}"]`);
      if (el){ el.style.boxShadow='0 0 0 3px rgba(124,58,237,.35) inset'; setTimeout(()=>el.style.boxShadow='',1000); }
    }
  });

  // Keyboard helpers
  document.addEventListener('keydown', (e)=>{
    if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); finish(false); }
    if (e.key.toLowerCase() === 'h'){ e.preventDefault(); document.getElementById('btnHint').click(); }
    if (e.key.toLowerCase() === 'r'){ e.preventDefault(); document.getElementById('btnReset').click(); }
  });

  // Init
  init();
})();
</script>
</x-app-layout>
