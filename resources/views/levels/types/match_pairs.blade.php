<x-app-layout>
@php
    // Safe content extraction
    $content      = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $intro        = $content['intro'] ?? null;
    $instructions = $level->instructions ?? ($content['instructions'] ?? null);
    $pairs        = $content['pairs'] ?? [];
    $hints        = $content['hints'] ?? [];
    $timeLimit    = (int)($content['time_limit'] ?? 240);
    $maxHints     = (int)($content['max_hints']  ?? 3);

    // Header stats
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;
    $savedStars    = $levelProgress->stars ?? 0;

    // Normalize payload for JS
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

{{-- HEADER (same style as your True/False page) --}}
<x-slot name="header">
    <div class="epic-level-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="level-badge">
                        <span class="level-number">{{ $level->index }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="level-info">
                        <h2 class="level-title mb-1">{{ $level->stage->title }}</h2>
                        <div class="level-subtitle">
                            <i class="fas fa-link me-2"></i>
                            Level {{ $level->index }} • Match Pairs
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="level-stats">
                        <div class="stat-item me-3">
                            <div class="stat-icon">🏆</div>
                            <div class="stat-value" id="currentScore">0</div>
                            <div class="stat-label">Score</div>
                        </div>
                        <div class="stat-item me-3">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-value" id="starsEarned">0</div>
                            <div class="stat-label">Stars</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">⏱️</div>
                            <div class="stat-value" id="timeRemaining">--:--</div>
                            <div class="stat-label">Time</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>

<style>
  :root{
    --deep-purple:#1a0636;--cosmic-purple:#4a1b6d;--space-blue:#162b6f;--dark-space:#0a1028;
    --neon-blue:#00b3ff;--neon-purple:#b967ff;--electric-blue:#05d9e8;--gold-gradient:linear-gradient(135deg,#ffd700 0%,#ffed4a 100%);
    --border:rgba(255,255,255,.16);
  }
  body{background:linear-gradient(45deg,var(--deep-purple),var(--cosmic-purple),var(--space-blue),var(--dark-space));min-height:100vh;font-family:'Orbitron','Arial',sans-serif;color:#fff}
  .epic-level-header{background:rgba(10,6,30,.9);backdrop-filter:blur(20px);border-bottom:3px solid var(--neon-purple);padding:20px 0;position:relative;overflow:hidden}
  .epic-level-header::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(185,103,255,.1),transparent);animation:headerShine 4s ease-in-out infinite}
  @keyframes headerShine{0%{left:-100%}50%,100%{left:100%}}
  .level-badge{width:70px;height:70px;background:var(--gold-gradient);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(185,103,255,.6);animation:levelPulse 2s ease-in-out infinite;position:relative;z-index:2}
  .level-number{font-size:1.8rem;font-weight:900;color:#333;text-shadow:1px 1px 2px rgba(0,0,0,.3)}
  @keyframes levelPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05);box-shadow:0 0 40px rgba(185,103,255,.8)}}
  .level-title{color:var(--neon-purple);font-size:1.8rem;font-weight:900;text-shadow:2px 2px 4px rgba(0,0,0,.5);letter-spacing:1px}
  .level-subtitle{color:rgba(255,255,255,.85)}
  .level-stats{display:flex;align-items:center}
  .stat-item{text-align:center;color:#fff;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:15px;border-radius:15px;border:1px solid rgba(255,255,255,.2);min-width:80px}
  .stat-icon{font-size:1.5rem;margin-bottom:5px}
  .stat-value{font-size:1.2rem;font-weight:900;color:var(--neon-purple)}
  .stat-label{font-size:.8rem;opacity:.8}

  .arena{background:rgba(26,6,54,.7);backdrop-filter:blur(20px);border-radius:30px;margin:30px auto;padding:28px;max-width:1200px;box-shadow:0 20px 60px rgba(0,0,0,.3);border:2px solid rgba(185,103,255,.3);position:relative;overflow:hidden}
  .challenge-title{text-align:center;font-size:2.2rem;font-weight:900;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px}
  .challenge-description{text-align:center;font-size:1.02rem;color:rgba(255,255,255,.9);margin:6px auto 20px;max-width:900px}
  .progress-bar-container{background:rgba(0,0,0,.1);height:12px;border-radius:10px;overflow:hidden;margin:10px 0 18px}
  .progress-bar{height:100%;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));border-radius:10px;transition:width .8s cubic-bezier(.25,.46,.45,.94);position:relative}
  .progress-bar::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);animation:progressShine 2s ease-in-out infinite}
  @keyframes progressShine{0%{left:-100%}100%{left:100%}}

  /* Match grid */
  .match-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
  @media (max-width: 900px){ .match-grid{grid-template-columns:1fr} }
  .col-title{font-weight:900;color:#bfe3ff;margin-bottom:8px;text-align:center}
  .card-col{background:rgba(255,255,255,.05);border:1px solid var(--border);border-radius:18px;padding:12px}
  .list{display:flex;flex-direction:column;gap:10px}
  .chip{user-select:none;background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:12px;padding:12px 14px;font-weight:800;letter-spacing:.25px;color:#fff;cursor:pointer;transition:transform .12s ease, box-shadow .2s ease, border-color .12s ease}
  .chip:hover{transform:translateY(-2px);border-color:rgba(185,103,255,.5)}
  .chip.selected{box-shadow:0 0 0 3px rgba(0,179,255,.25) inset;border-color:#00b3ff}
  .chip.correct{box-shadow:0 0 0 3px rgba(53,209,155,.25) inset;border-color:#35d19b}
  .chip.locked{opacity:.8;cursor:default}
  .pair-row{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:8px}
  .pair-badge{font-size:.8rem;border:1px solid var(--border);padding:4px 8px;border-radius:999px;color:#e6dcff;background:rgba(255,255,255,.06)}

  .controls{text-align:center;margin:22px 0 6px}
  .btn-epic{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));border:none;color:#fff;padding:12px 22px;border-radius:24px;font-size:1rem;font-weight:800;letter-spacing:.6px;transition:all .25s;box-shadow:0 8px 25px rgba(102,126,234,.3);margin:0 8px}
  .btn-epic:hover{transform:translateY(-3px)}
  .btn-reset{background:linear-gradient(45deg,#ff2a6d,#ff6a00)}
  .btn-hint{background:linear-gradient(45deg,#f093fb,#f5576c)}

  .feedback-container{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;pointer-events:none}
  .feedback-message{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));color:#fff;padding:12px 20px;border-radius:18px;font-weight:800;box-shadow:0 10px 40px rgba(0,0,0,.3);animation:feedbackPop 2s ease;margin-bottom:8px}
  .feedback-message.error{background:linear-gradient(45deg,#ff2a6d,#ff6a00)}
  .feedback-message.warning{background:linear-gradient(45deg,#f093fb,#f5576c)}
  @keyframes feedbackPop{0%{transform:scale(0) rotate(180deg);opacity:0}20%{transform:scale(1.2) rotate(0);opacity:1}80%{transform:scale(1);opacity:1}100%{transform:scale(0) rotate(-180deg);opacity:0}}

  .completion-celebration{position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9999}
  .confetti{position:absolute;width:10px;height:10px;background:#b967ff;animation:confettiFall 3s linear infinite}
  @keyframes confettiFall{0%{transform:translateY(-100vh) rotate(0);opacity:1}100%{transform:translateY(100vh) rotate(720deg);opacity:0}}
</style>

<div class="arena">
  <h1 class="challenge-title">🧩 Match the Pairs</h1>
  @if($intro)
    <p class="challenge-description">{!! nl2br(e($intro)) !!}</p>
  @endif
  @if($instructions)
    <p class="challenge-description" style="opacity:.9">{!! nl2br(e($instructions)) !!}</p>
  @endif

  <div class="progress-bar-container">
      <div class="progress-bar" id="progressBar" style="width:0%"></div>
  </div>

  <div class="match-grid" id="matchGrid">
    <div class="card-col">
      <div class="col-title">Left</div>
      <div class="list" id="leftList"><!-- filled by JS --></div>
    </div>
    <div class="card-col">
      <div class="col-title">Right</div>
      <div class="list" id="rightList"><!-- filled by JS --></div>
    </div>
  </div>

  <div class="pair-row" id="pairRow">
    <span class="pair-badge">Matched: <span id="matchedCount">0</span></span>
    <span class="pair-badge">Remaining: <span id="remainingCount">0</span></span>
  </div>

  <div class="controls">
    <button class="btn-epic" id="btnCheck"><i class="fas fa-magic me-2"></i>Finish</button>
    <button class="btn-epic btn-hint" id="btnHint"><i class="fas fa-lightbulb me-2"></i>Hint</button>
    <button class="btn-epic btn-reset" id="btnReset"><i class="fas fa-redo me-2"></i>Reset</button>
  </div>

  {{-- Hidden form --}}
  <form method="POST" action="{{ route('levels.submit', $level) }}" id="scoreForm" style="display:none;">
      @csrf
      <input type="hidden" name="score" id="finalScore" value="0">
      <input type="hidden" name="answers" id="answersPayload" value="">
  </form>
</div>

<div class="feedback-container" id="feedbackContainer"></div>
<div class="completion-celebration" id="celebrationContainer"></div>

<script>
(function(){
  const data = window.LEVEL_DATA || {};
  const PAIRS = Array.isArray(data.pairs) ? data.pairs.slice() : [];
  const HINTS = Array.isArray(data.hints) ? data.hints.slice() : [];
  const TIME_LIMIT = Number.isFinite(data.time_limit) ? data.time_limit : 240;
  const MAX_HINTS  = Number.isFinite(data.max_hints) ? data.max_hints : 3;

  // State
  let leftItems = [];   // [{id, text}]
  let rightItems = [];  // [{id, text}]
  let mapping   = {};   // {leftId -> rightId (correct)}
  let pickedLeft = null;
  let pickedRight= null;
  let matches = {};     // {leftId: rightId} chosen by user
  let locked  = {};     // leftId locked after correct
  let timeRemaining = TIME_LIMIT;
  let hintsUsed = 0;
  let timer = null;

  // DOM
  const $leftList  = document.getElementById('leftList');
  const $rightList = document.getElementById('rightList');
  const $progress  = document.getElementById('progressBar');
  const $matched   = document.getElementById('matchedCount');
  const $remain    = document.getElementById('remainingCount');
  const $feedback  = document.getElementById('feedbackContainer');

  // Header DOM
  const $scoreEl = document.getElementById('currentScore');
  const $starsEl = document.getElementById('starsEarned');
  const $timeEl  = document.getElementById('timeRemaining');

  const $btnCheck= document.getElementById('btnCheck');
  const $btnHint = document.getElementById('btnHint');
  const $btnReset= document.getElementById('btnReset');

  // Helpers
  const shuffle = a => { for(let i=a.length-1;i>0;i--){ const j=Math.floor(Math.random()*(i+1)); [a[i],a[j]]=[a[j],a[i]]; } return a; };
  const pad = n => String(n).padStart(2,'0');
  function setProgress(){
    const total = leftItems.length;
    const done = Object.keys(locked).length;
    const pct = total ? Math.round(100*done/total) : 0;
    $progress.style.width = pct + '%';
    $matched.textContent = done;
    $remain.textContent = Math.max(0, total - done);
  }
  function starsFor(score){
    if(score>=90) return '⭐⭐⭐'; if(score>=70) return '⭐⭐'; if(score>=50) return '⭐'; return '0';
  }
  function showFeedback(msg, type="success", dur=1600){
    const el = document.createElement('div');
    el.className = `feedback-message ${type}`;
    el.textContent = msg;
    $feedback.appendChild(el);
    setTimeout(()=>el.remove(), dur);
  }
  function confetti(){
    const wrap = document.getElementById('celebrationContainer');
    for (let i=0;i<70;i++){
      setTimeout(()=>{
        const c = document.createElement('div');
        c.className = 'confetti';
        c.style.left = Math.random()*100 + 'vw';
        c.style.background = ['#ffd700','#ff6b6b','#4ecdc4','#45b7d1','#f9ca24'][Math.floor(Math.random()*5)];
        c.style.animationDelay = Math.random()*3 + 's';
        wrap.appendChild(c);
        setTimeout(()=>c.remove(),3200);
      }, i*25);
    }
  }

  // Build lists
  function init(){
    // give each pair stable ids
    const canonical = PAIRS.map((p,idx)=>({
      left:  { id: 'L'+idx, text: String(p.left)  },
      right: { id: 'R'+idx, text: String(p.right) },
    }));
    leftItems  = canonical.map(c=>c.left);
    rightItems = canonical.map(c=>c.right);
    mapping    = canonical.reduce((m,c)=>{ m[c.left.id]=c.right.id; return m; }, {});
    shuffle(leftItems);
    shuffle(rightItems);
    render();
    setProgress();
    startTimer();
    updateHeader();
  }

  function render(){
    $leftList.innerHTML = '';
    $rightList.innerHTML = '';
    leftItems.forEach(item=>{
      const div = document.createElement('button');
      div.type='button';
      div.className = 'chip';
      div.dataset.id = item.id;
      div.textContent = item.text;
      div.addEventListener('click', ()=>onPickLeft(item.id, div));
      $leftList.appendChild(div);
    });
    rightItems.forEach(item=>{
      const div = document.createElement('button');
      div.type='button';
      div.className = 'chip';
      div.dataset.id = item.id;
      div.textContent = item.text;
      div.addEventListener('click', ()=>onPickRight(item.id, div));
      $rightList.appendChild(div);
    });
  }

  function clearSelections(){
    document.querySelectorAll('.chip.selected').forEach(el=>el.classList.remove('selected'));
    pickedLeft = pickedRight = null;
  }

  function lockPair(leftId, rightId){
    locked[leftId] = rightId;
    matches[leftId] = rightId;

    const leftEl  = $leftList.querySelector(`[data-id="${leftId}"]`);
    const rightEl = $rightList.querySelector(`[data-id="${rightId}"]`);
    if (leftEl){ leftEl.classList.add('correct','locked'); leftEl.disabled=true; }
    if (rightEl){ rightEl.classList.add('correct','locked'); rightEl.disabled=true; }

    clearSelections();
    setProgress();

    // done?
    if (Object.keys(locked).length === leftItems.length){
      finish();
    }
  }

  function onPickLeft(id, el){
    if (locked[id]) return;
    // toggle
    if (pickedLeft === id){ el.classList.remove('selected'); pickedLeft=null; return; }
    document.querySelectorAll('#leftList .chip').forEach(b=>b.classList.remove('selected'));
    pickedLeft = id;
    el.classList.add('selected');

    // If right already chosen, try pair
    if (pickedRight) tryPair();
  }

  function onPickRight(id, el){
    // If this right is already used, ignore
    if (Object.values(locked).includes(id)) return;

    if (pickedRight === id){ el.classList.remove('selected'); pickedRight=null; return; }
    document.querySelectorAll('#rightList .chip').forEach(b=>b.classList.remove('selected'));
    pickedRight = id;
    el.classList.add('selected');

    if (pickedLeft) tryPair();
  }

  function tryPair(){
    const correctRight = mapping[pickedLeft];
    const leftEl  = $leftList.querySelector(`[data-id="${pickedLeft}"]`);
    const rightEl = $rightList.querySelector(`[data-id="${pickedRight}"]`);

    if (pickedRight === correctRight){
      showFeedback('✅ Correct match!', 'success', 900);
      lockPair(pickedLeft, pickedRight);
    } else {
      showFeedback('❌ Not a match', 'error', 900);
      // brief shake
      [leftEl,rightEl].forEach(el=>{
        if(!el) return;
        el.style.transform='translateY(-2px)';
        setTimeout(()=>el.style.transform='',140);
      });
      clearSelections();
    }
  }

  // Timer & header
  function startTimer(){
    timeRemaining = TIME_LIMIT;
    $timeEl.textContent = fmt(timeRemaining);
    timer = setInterval(()=>{
      timeRemaining--;
      $timeEl.textContent = fmt(timeRemaining);
      if (timeRemaining<=0){
        clearInterval(timer);
        showFeedback("⏰ Time's up! Submitting...", 'warning', 1600);
        finish(true);
      }
    },1000);
  }
  function fmt(s){ const m=Math.floor(s/60), r=s%60; return `${pad(m)}:${pad(r)}`; }

  function updateHeader(score=0){
    $scoreEl.textContent = score;
    $starsEl.textContent = starsFor(score);
  }

  function computeScore(){
    const total = Object.keys(mapping).length;
    const correct = Object.keys(locked).length;
    const base = total ? Math.round(100*correct/total) : 0;
    const hintPenalty = hintsUsed * 5;
    const timeBonus = Math.max(0, Math.floor(timeRemaining/10));
    return Math.min(100, Math.max(0, base - hintPenalty + timeBonus));
  }

  function finish(timeout=false){
    clearInterval(timer);
    const score = computeScore();
    updateHeader(score);
    // answers payload as {leftText: rightTextChosen|null}
    const payload = {};
    leftItems.concat().sort((a,b)=>a.id.localeCompare(b.id)).forEach(li=>{
      const rId = matches[li.id] ?? null;
      const rightText = (rightItems.find(r=>r.id===rId)||{}).text ?? null;
      payload[li.text] = rightText;
    });

    // Submit
    document.getElementById('finalScore').value = score;
    document.getElementById('answersPayload').value = JSON.stringify(payload);

    // UX feedback
    if (!timeout){
      if (score>=90) { showFeedback("🏆 PERFECT MATCH!", "success", 2000); confetti(); }
      else if (score>=70){ showFeedback(`🎯 Score: ${score}%`, "success", 1800); }
      else { showFeedback(`Keep practicing — Score: ${score}%`, "warning", 1800); }
    }

    setTimeout(()=>{
      const form = document.getElementById('scoreForm');
      if (form.requestSubmit) form.requestSubmit(); else form.submit();
    }, 1200);

    // disable all buttons
    document.querySelectorAll('.chip').forEach(b=>b.disabled=true);
    $btnCheck.disabled = $btnHint.disabled = $btnReset.disabled = true;
  }

  // Buttons
  $btnCheck.addEventListener('click', ()=>finish(false));
  $btnReset.addEventListener('click', ()=>location.reload());
  $btnHint.addEventListener('click', ()=>{
    if (hintsUsed >= MAX_HINTS){ showFeedback("🔮 No more hints!", "error", 1200); return; }
    hintsUsed++;
    const hint = HINTS[(hintsUsed-1) % (HINTS.length || 1)] || "Think about types and conversions.";
    showFeedback(`💡 Hint: ${hint}`, "warning", 2400);
    // nudge one random unmatched left chip
    const open = leftItems.filter(li=>!locked[li.id]);
    if (open.length){
      const li = open[Math.floor(Math.random()*open.length)];
      const el = $leftList.querySelector(`[data-id="${li.id}"]`);
      if (el){ el.style.boxShadow='0 0 0 3px rgba(255,255,255,.25) inset'; setTimeout(()=>el.style.boxShadow='',1000); }
    }
  });

  // Start
  init();
})();
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
