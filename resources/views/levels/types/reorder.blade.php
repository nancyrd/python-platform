<x-app-layout>
@php
    // ---- Normalize Level Content ----
    $content = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $tasks   = $content['tasks'] ?? [];
    $hints   = $content['hints'] ?? [];
    $timeLimit = (int)($content['time_limit'] ?? 240);
    $maxHints  = (int)($content['max_hints']  ?? 3);

    // Fallback safe values
    if (!is_array($tasks))   $tasks = [];
    if (!is_array($hints))   $hints = [];
    if ($timeLimit <= 0)     $timeLimit = 240;
    if ($maxHints < 0)       $maxHints  = 0;

    // Build payload for JS
    $payload = [
        'time_limit' => $timeLimit,
        'max_hints'  => $maxHints,
        'hints'      => array_values($hints),
        'tasks'      => array_values(array_map(function ($t, $i) {
            return [
                'id'             => $i + 1,
                'title'          => (string)($t['title'] ?? 'Untitled Task'),
                'lines'          => array_values($t['lines'] ?? []),
                // solution is array of indices (0-based) that refer to the "lines" array
                'solution'       => array_values($t['solution'] ?? []),
                'correct_output' => (string)($t['correct_output'] ?? ''),
            ];
        }, $tasks, array_keys($tasks))),
    ];
@endphp

<script>
  window.REORDER_DATA = {!! json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
  window.LEVEL_META   = {
    id: {{ (int)$level->id }},
    title: @json($level->title),
    stageTitle: @json($level->stage->title ?? ''),
    index: {{ (int)$level->index }},
    passScore: {{ (int)$level->pass_score }},
  };
</script>

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
            <span class="breadcrumb-item type">Reorder</span>
          </div>
          <h1 class="stage-title">{{ $level->stage->title ?? 'Stage' }}</h1>
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
  --primary-purple:#7c3aed; --secondary-purple:#a855f7; --light-purple:#c084fc; --purple-subtle:#f3e8ff;
  --gray-50:#f8fafc; --gray-100:#f1f5f9; --gray-200:#e2e8f0; --gray-300:#cbd5e1; --gray-400:#94a3b8; --gray-500:#64748b;
  --gray-600:#475569; --gray-700:#334155; --gray-800:#1e293b; --gray-900:#0f172a;
  --success:#10b981; --success-light:#dcfce7; --warning:#f59e0b; --warning-light:#fef3c7; --danger:#ef4444; --danger-light:#fecaca;
  --background:#ffffff; --border:#e2e8f0; --text-primary:#1e293b; --text-secondary:#475569; --text-muted:#64748b;
  --shadow-sm:0 1px 2px rgba(0,0,0,.05); --shadow:0 1px 3px rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
  --shadow-md:0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1); --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
}
body{
  background:linear-gradient(135deg, rgba(124,58,237,.03) 0%, rgba(168,85,247,.02) 50%, rgba(248,250,252,1) 100%);
  color:var(--text-primary);
  font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}
/* Header */
.level-header { background:linear-gradient(135deg, rgba(124,58,237,.05) 0%, rgba(168,85,247,.03) 100%); border-bottom:1px solid var(--border); backdrop-filter:blur(10px);}
.header-container{display:flex; align-items:center; justify-content:space-between; padding:1.5rem 2rem; gap:2rem;}
.header-left{display:flex; align-items:center; gap:1.5rem; flex:1; min-width:0;}
.level-badge{width:4rem; height:4rem; border-radius:1rem; background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-md);}
.level-number{font-weight:900; font-size:1.25rem; color:#fff;}
.level-info{flex:1; min-width:0;}
.breadcrumb{display:flex; align-items:center; gap:.5rem; font-size:.875rem; color:var(--text-muted); margin-bottom:.25rem;}
.breadcrumb-item.type{ text-transform:capitalize; color:var(--primary-purple); font-weight:500;}
.separator{opacity:.6}
.stage-title{font-size:1.5rem; font-weight:700; margin:0; line-height:1.2; color:var(--text-primary);}
.level-title{font-size:1rem; color:var(--text-secondary); margin-top:.25rem;}
.header-right{flex-shrink:0;}
.stats-grid{display:grid; grid-template-columns:repeat(3,1fr); gap:1rem;}
.stat-item{text-align:center; padding:.75rem 1rem; background:#fff; border:1px solid var(--border); border-radius:.75rem; box-shadow:var(--shadow-sm); min-width:5rem;}
.stat-label{font-size:.75rem; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.05em;}
.stat-value{font-size:1.125rem; font-weight:700; color:var(--text-primary); margin-top:.25rem;}

/* Layout helpers */
.full-bleed{width:100vw; margin-left:calc(50% - 50vw); margin-right:calc(50% - 50vw);}
.edge-pad{padding:1.25rem clamp(12px, 3vw, 32px);}
.main-container{max-width:none;}
.card{background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem; box-shadow:var(--shadow-sm);}
.card.accent{border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff);}
.section-title{font-size:1.125rem; font-weight:700; margin:0 0 .75rem 0; color:var(--text-primary);}

/* Task container */
.task-wrap{display:grid; grid-template-columns:1fr; gap:1rem;}
.task-head{display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;}
.task-title{font-weight:800;}
.task-body{display:grid; grid-template-columns:1fr 1fr; gap:1rem;}
@media (max-width: 900px){ .task-body{ grid-template-columns:1fr; } }

.lines-col, .preview-col{background:#fff; border:1px solid var(--border); border-radius:.75rem; padding:1rem;}
.col-title{font-size:.95rem; font-weight:700; margin-bottom:.5rem; color:var(--text-secondary);}

/* Reorder list */
.reorder-list{list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:.5rem; min-height:120px;}
.reorder-item{display:flex; align-items:center; gap:.75rem; background:linear-gradient(180deg,#fff,#fbfbff); border:1px solid var(--border);
  border-radius:.6rem; padding:.6rem .75rem; cursor:grab; box-shadow:var(--shadow-sm);}
.reorder-item.dragging{opacity:.6;}
.handle{width:28px; height:28px; border-radius:.5rem; background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
  display:flex; align-items:center; justify-content:center; color:#fff; font-weight:900; user-select:none;}
.line-code{white-space:pre-wrap; font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace; color:var(--gray-800);}

/* Right column */
.preview-box{background:#fff; border:1px dashed var(--border); border-radius:.6rem; padding:.75rem; min-height:120px;}
.meta-row{display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-top:.5rem;}
.meta-pill{background:#fff; border:1px solid var(--border); padding:.25rem .75rem; border-radius:9999px; font-weight:500; color:var(--text-muted);}

/* Controls */
.controls{display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; margin-top:1rem;}
.btn{display:inline-flex; align-items:center; gap:.5rem; padding:.65rem .9rem; border:none; border-radius:.75rem; font-weight:700; font-size:.9rem; cursor:pointer; transition:all .18s ease; text-decoration:none;}
.btn:disabled{opacity:.5; cursor:not-allowed;}
.btn-primary{background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow);}
.btn-primary:hover:not(:disabled){transform:translateY(-2px); box-shadow:var(--shadow-lg);}
.btn-secondary{background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border);}
.btn-secondary:hover:not(:disabled){background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow);}
.btn-ghost{background:transparent; color:var(--text-secondary); border:1px solid var(--border);}
.btn-ghost:hover:not(:disabled){background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple);}

/* Result */
.result-box{display:none; margin-top:1rem; padding:1rem; border-radius:.75rem; border:1px solid var(--border);}
.result-box.ok{display:block; border-left:6px solid var(--success); background:linear-gradient(135deg,var(--success-light),#fff);}
.result-box.err{display:block; border-left:6px solid var(--danger);  background:linear-gradient(135deg,var(--danger-light), #fff);}

/* Toasts */
.toast-container{position:fixed; top:1rem; right:1rem; display:flex; flex-direction:column; gap:.5rem; z-index:1000;}
.toast{background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:500; min-width:280px; box-shadow:var(--shadow-lg); animation:slideIn .3s ease;}
.toast.ok{border-left:4px solid var(--success); background:linear-gradient(135deg,var(--success-light), #fff);}
.toast.warn{border-left:4px solid var(--warning); background:linear-gradient(135deg,var(--warning-light), #fff);}
.toast.err{border-left:4px solid var(--danger); background:linear-gradient(135deg,var(--danger-light),  #fff);}
@keyframes slideIn{from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)}}

/* Footer meta */
.meta-container{display:flex; justify-content:space-between; align-items:center; background:var(--gray-50); border-top:1px solid var(--border); font-size:.875rem; color:var(--text-muted);}
.meta-left{display:flex; gap:1rem; align-items:center; flex-wrap:wrap;}
</style>

<div class="main-container full-bleed">
  <div class="edge-pad">
    <!-- Instructions -->
    <div class="card accent" style="margin-bottom:1rem;">
      <div class="task-head">
        <div class="section-title">How it works</div>
        <button class="btn btn-ghost" id="toggleInstrux" type="button" aria-expanded="true">
          <i class="fas fa-chevron-up"></i> Collapse
        </button>
      </div>
      <div id="instruxBody" style="white-space:pre-wrap;">
Arrange the code lines into a correct program. Each task shows a snippet broken into lines. Drag to reorder the lines until they represent a valid solution. Then submit. Hints can help, but they reduce your final score slightly.
      </div>
    </div>

    <!-- Task Navigator -->
    <div class="card" style="margin-bottom:1rem;">
      <div class="task-head">
        <div class="items-title"><strong id="taskTracker">Task 1 / 1</strong></div>
        <div style="display:flex; gap:.5rem;">
          <button class="btn btn-secondary" id="btnPrev" type="button"><i class="fas fa-arrow-left"></i> Prev</button>
          <button class="btn btn-secondary" id="btnNext" type="button">Next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>
    </div>

    <!-- Task Body -->
    <div class="card">
      <div class="task-head">
        <div class="task-title" id="taskTitle">Task Title</div>
        <div class="meta-row">
          <span class="meta-pill">Pass score: {{ (int)$level->pass_score }}%</span>
          <span class="meta-pill">Hints used: <span id="hintCount">0</span></span>
        </div>
      </div>

      <div class="task-body">
        <!-- Left: Lines to reorder -->
        <div class="lines-col">
          <div class="col-title">Drag to reorder</div>
          <ul class="reorder-list" id="listBox"><!-- items injected --></ul>
          <div class="controls">
            <button class="btn btn-ghost" id="btnShuffle" type="button"><i class="fas fa-shuffle"></i> Shuffle</button>
            <button class="btn btn-secondary" id="btnResetTask" type="button"><i class="fas fa-rotate-left"></i> Reset Task</button>
            <button class="btn btn-secondary" id="btnHint" type="button"><i class="fas fa-lightbulb"></i> Hint</button>
          </div>
        </div>

        <!-- Right: Preview -->
        <div class="preview-col">
          <div class="col-title">Preview</div>
          <div class="preview-box" id="previewBox" style="font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace; white-space:pre-wrap; color:var(--gray-800);"></div>
          <div class="meta-row">
            <span class="meta-pill">Correct output (example):</span>
            <span id="expectedBox" class="meta-pill" style="max-width:100%; overflow:auto;"></span>
          </div>

          <div id="taskResult" class="result-box"></div>
        </div>
      </div>

      <div class="controls">
        <button class="btn btn-primary" id="btnCheckTask" type="button"><i class="fas fa-check"></i> Check This Task</button>
      </div>
    </div>

    <!-- Whole-level controls -->
    <div class="controls" style="margin-top:1rem;">
      <button class="btn btn-primary" id="btnSubmitAll" type="button"><i class="fas fa-flag-checkered"></i> Submit All Tasks</button>
      <button class="btn btn-ghost" id="btnRestartAll" type="button"><i class="fas fa-rotate-left"></i> Restart Level</button>
    </div>

    <!-- Hidden submit form -->
    <form method="POST" action="{{ route('levels.submit', $level) }}" id="scoreForm" style="display:none;">
      @csrf
      <input type="hidden" name="score" id="finalScore" value="0">
      <input type="hidden" name="answers" id="answersPayload" value="">
    </form>
  </div>
</div>

<!-- Footer meta -->
<div class="meta-container full-bleed edge-pad">
  <div class="meta-left">
    <span class="meta-pill">Stars: <span id="metaStars">0</span></span>
  </div>
  <div>Time left: <span id="timeRemainingFooter">--:--</span></div>
</div>

<!-- Toasts -->
<div class="toast-container" id="toastWrap"></div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
  const data  = window.REORDER_DATA || {tasks:[], hints:[], time_limit:240, max_hints:3};
  const meta  = window.LEVEL_META || {passScore:70};
  const tasks = Array.isArray(data.tasks) ? data.tasks : [];
  const hints = Array.isArray(data.hints) ? data.hints : [];

  // State
  let current = 0;                    // current task index
  let orders  = {};                   // taskId -> array of line indices (current order mapped to original indexes)
  let results = {};                   // taskId -> { ok: boolean, score: 0|1 }
  let hintsUsed = 0;
  let timeRemaining = Number.isFinite(data.time_limit) ? data.time_limit : 240;
  const maxHints = Number.isFinite(data.max_hints) ? data.max_hints : 3;

  // DOM
  const $taskTracker = document.getElementById('taskTracker');
  const $taskTitle   = document.getElementById('taskTitle');
  const $listBox     = document.getElementById('listBox');
  const $previewBox  = document.getElementById('previewBox');
  const $expectedBox = document.getElementById('expectedBox');
  const $taskResult  = document.getElementById('taskResult');

  const $btnPrev     = document.getElementById('btnPrev');
  const $btnNext     = document.getElementById('btnNext');
  const $btnShuffle  = document.getElementById('btnShuffle');
  const $btnResetTask= document.getElementById('btnResetTask');
  const $btnHint     = document.getElementById('btnHint');
  const $btnCheck    = document.getElementById('btnCheckTask');
  const $btnSubmitAll= document.getElementById('btnSubmitAll');
  const $btnRestart  = document.getElementById('btnRestartAll');

  const $statScore   = document.getElementById('statScore');
  const $statStars   = document.getElementById('statStars');
  const $metaStars   = document.getElementById('metaStars');
  const $timerTop    = document.getElementById('timeRemaining');
  const $timerFoot   = document.getElementById('timeRemainingFooter');
  const $hintCount   = document.getElementById('hintCount');
  const $toastWrap   = document.getElementById('toastWrap');

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
  const escapeHtml = (s)=> String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  const fmtTime = (sec)=>{ const m=String(Math.floor(sec/60)).padStart(2,'0'); const s=String(sec%60).padStart(2,'0'); return `${m}:${s}`; };
  const toast = (msg, kind='ok')=>{ const el=document.createElement('div'); el.className=`toast ${kind}`; el.textContent=msg; $toastWrap.appendChild(el); setTimeout(()=>el.remove(),2200); };
  const starsFor = (score)=>{ if(score>=90) return 3; if(score>=70) return 2; if(score>=50) return 1; return 0; };

  // Timer
  function updateTimers(){
    $timerTop.textContent  = fmtTime(timeRemaining);
    $timerFoot.textContent = fmtTime(timeRemaining);
  }
  updateTimers();
  const t = setInterval(()=>{
    timeRemaining--;
    updateTimers();
    if ([60,30,10].includes(timeRemaining)) toast(`${timeRemaining}s left`, 'warn');
    if (timeRemaining<=0){
      clearInterval(t);
      toast('Time up — submitting…', 'warn');
      submitAll();
    }
  }, 1000);

  // Build a shuffled order for a task if not present
  function ensureOrder(task){
    if (orders[task.id]) return;
    const arr = task.lines.map((_, idx) => idx);
    // shuffle
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    orders[task.id] = arr;
  }

  // Render current task
  function renderTask(){
    if (!tasks.length){
      $taskTracker.textContent = 'No tasks';
      $taskTitle.textContent   = 'No tasks available';
      $listBox.innerHTML       = '';
      $previewBox.textContent  = '';
      $expectedBox.textContent = '';
      $taskResult.className    = 'result-box';
      $taskResult.style.display= 'none';
      $btnCheck.disabled = $btnPrev.disabled = $btnNext.disabled = $btnShuffle.disabled = $btnResetTask.disabled = $btnHint.disabled = true;
      return;
    }

    const task = tasks[current];
    ensureOrder(task);

    $taskTracker.textContent = `Task ${current+1} / ${tasks.length}`;
    $taskTitle.textContent   = task.title || `Task ${current+1}`;
    $expectedBox.textContent = task.correct_output || '';

    // Build list items from current order
    $listBox.innerHTML = '';
    orders[task.id].forEach((lineIdx, visualIdx) => {
      const li = document.createElement('li');
      li.className = 'reorder-item';
      li.draggable = true;
      li.dataset.lineIndex = String(lineIdx);
      li.innerHTML = `
        <div class="handle">${visualIdx+1}</div>
        <div class="line-code">${escapeHtml(task.lines[lineIdx])}</div>
      `;
      // drag handlers
      li.addEventListener('dragstart', (e)=>{ li.classList.add('dragging'); e.dataTransfer.setData('text/plain', lineIdx); });
      li.addEventListener('dragend', ()=> li.classList.remove('dragging'));
      $listBox.appendChild(li);
    });

    // List dragover to sort
    $listBox.addEventListener('dragover', (e)=>{
      e.preventDefault();
      const dragging = $listBox.querySelector('.dragging');
      if (!dragging) return;
      // find closest after element
      const y = e.clientY;
      const siblings = [...$listBox.querySelectorAll('.reorder-item:not(.dragging)')];
      let closest = null; let closestOffset = Number.NEGATIVE_INFINITY;
      siblings.forEach(el=>{
        const rect = el.getBoundingClientRect();
        const offset = y - (rect.top + rect.height/2);
        if (offset < 0 && offset > closestOffset){
          closestOffset = offset; closest = el;
        }
      });
      if (!closest) $listBox.appendChild(dragging);
      else $listBox.insertBefore(dragging, closest);
    });

    // Update preview
    updatePreview();

    // Reset result banner
    $taskResult.className = 'result-box';
    $taskResult.style.display = 'none';

    // Prev/Next enable
    $btnPrev.disabled = current === 0;
    $btnNext.disabled = current === tasks.length - 1;
  }

  function readOrderFromDom(){
    const task = tasks[current];
    if (!task) return;
    const newOrder = [];
    $listBox.querySelectorAll('.reorder-item').forEach(li => {
      newOrder.push(Number(li.dataset.lineIndex));
    });
    orders[task.id] = newOrder;
  }

  function updatePreview(){
    const task = tasks[current]; if (!task) return;
    const ord = orders[task.id] || [];
    const lines = ord.map(i => task.lines[i]);
    $previewBox.textContent = lines.join('\n');
    // refresh handle numbering
    const items = $listBox.querySelectorAll('.reorder-item .handle');
    items.forEach((h, i)=> h.textContent = String(i+1));
  }

  function checkTask(){
    readOrderFromDom();
    const task = tasks[current]; if (!task) return;

    const ord = orders[task.id] || [];
    const sol = task.solution || [];

    // Compare sequences
    const ok = ord.length === sol.length && ord.every((v, i) => v === sol[i]);
    results[task.id] = { ok, score: ok ? 1 : 0 };

    $taskResult.textContent = ok ? 'Correct order! Nice.' : 'Not quite. Try again or use a hint.';
    $taskResult.className = 'result-box ' + (ok ? 'ok' : 'err');
    $taskResult.style.display = 'block';
    toast(ok ? 'Task solved!' : 'Order is wrong.', ok ? 'ok' : 'warn');
  }

  function computeFinalScore(){
    const total = tasks.length || 1;
    const solved = Object.values(results).reduce((acc, r)=> acc + (r?.score || 0), 0);
    let pct = Math.round(100 * solved / total);
    // hint penalty: 5% per hint
    pct = Math.max(0, pct - hintsUsed * 5);
    // small time bonus
    pct = Math.min(100, pct + Math.max(0, Math.floor(timeRemaining / 10)));
    return pct;
  }

  function updateScoreUi(pct){
    $statScore.textContent = pct + '%';
    const s = starsFor(pct);
    const starsText = s ? '★'.repeat(s) : '0';
    $statStars.textContent = starsText;
    if ($metaStars) $metaStars.textContent = starsText;
  }

  function submitAll(){
    // If some tasks not checked, auto-check them to lock result
    for (let i=0;i<tasks.length;i++){
      const id = tasks[i].id;
      if (!results[id]){
        // compute against current order (or shuffled if untouched)
        const ord = orders[id] || tasks[i].lines.map((_, idx) => idx);
        const sol = tasks[i].solution || [];
        const ok = ord.length === sol.length && ord.every((v, j) => v === sol[j]);
        results[id] = { ok, score: ok ? 1 : 0 };
      }
    }

    const score = computeFinalScore();
    updateScoreUi(score);
    toast(score >= meta.passScore ? `Great job! Score ${score}%` : `Score ${score}%. Keep practicing!`, score >= meta.passScore ? 'ok' : 'err');

    // Prepare payload
    const answersPayload = {};
    tasks.forEach(t => { answersPayload[t.id] = orders[t.id] || []; });

    document.getElementById('finalScore').value = String(score);
    document.getElementById('answersPayload').value = JSON.stringify(answersPayload);

    // Submit to server
    const form = document.getElementById('scoreForm');
    if (form.requestSubmit) form.requestSubmit(); else form.submit();
  }

  // Buttons
  $btnPrev.addEventListener('click', () => { if (current>0){ current--; renderTask(); } });
  $btnNext.addEventListener('click', () => { if (current<tasks.length-1){ current++; renderTask(); } });
  $btnShuffle.addEventListener('click', () => {
    const task = tasks[current]; if (!task) return;
    const arr = task.lines.map((_, idx) => idx);
    for (let i = arr.length - 1; i > 0; i--) { const j = Math.floor(Math.random() * (i + 1)); [arr[i], arr[j]] = [arr[j], arr[i]]; }
    orders[task.id] = arr;
    renderTask();
  });
  $btnResetTask.addEventListener('click', () => {
    const task = tasks[current]; if (!task) return;
    orders[task.id] = task.lines.map((_, idx) => idx); // reset to original order (not solution)
    renderTask();
    toast('Task order reset.', 'ok');
  });
  $btnHint.addEventListener('click', () => {
    if (hintsUsed >= maxHints) return toast('No more hints available.', 'warn');
    hintsUsed++; $hintCount.textContent = hintsUsed;
    const tip = hints.length ? hints[(hintsUsed-1) % hints.length] : 'Think about variable initialization and update.';
    toast('Hint: ' + tip, 'ok');
  });
  $btnCheck.addEventListener('click', checkTask);
  $btnSubmitAll.addEventListener('click', submitAll);
  $btnRestart.addEventListener('click', () => {
    if (!confirm('Restart the whole level? Your progress on this level will be cleared.')) return;
    for (const t of tasks){
      delete results[t.id];
      delete orders[t.id];
    }
    hintsUsed = 0;
    document.getElementById('hintCount').textContent = '0';
    renderTask();
    toast('Level reset.', 'ok');
  });

  // Keyboard helpers
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft')  { e.preventDefault(); $btnPrev.click(); }
    if (e.key === 'ArrowRight') { e.preventDefault(); $btnNext.click(); }
    if (e.key.toLowerCase() === 'h') { e.preventDefault(); $btnHint.click(); }
    if (e.key.toLowerCase() === 's') { e.preventDefault(); $btnShuffle.click(); }
    if (e.key.toLowerCase() === 'c') { e.preventDefault(); $btnCheck.click(); }
  });

  // Init
  renderTask();
})();
</script>
</x-app-layout>
