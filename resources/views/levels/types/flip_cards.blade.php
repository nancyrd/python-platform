<x-app-layout>
@php
    // ===============================
    // Safe, precomputed data for Blade
    // ===============================
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;

    // Content fallbacks
    $content     = is_array($level->content) ? $level->content : (json_decode($level->content ?? '[]', true) ?: []);
    $timeLimit   = (int)($content['time_limit'] ?? 240);
    $maxHints    = (int)($content['max_hints']  ?? 3);
    $hints       = $content['hints'] ?? [];
    $introText   = $content['intro'] ?? '';
    $uiInstrux   = $content['instructions'] ?? "Flip each card to learn the key idea on the back. Flip them all to complete the lesson.";
    $cards       = $content['cards'] ?? [];  // [{ front, back, title? }, ...]
    $examples    = $content['examples'] ?? [];

    // Normalize cards
    $deck = [];
    foreach ($cards as $i => $c) {
        if (!isset($c['front']) || !isset($c['back'])) continue;
        $deck[] = [
            'id'    => $i+1,
            'title' => $c['title'] ?? null,
            'front' => $c['front'],
            'back'  => $c['back'],
        ];
    }

    // Default hints if none
    $defaultHints = [
        'input() always returns text (str). Convert before math.',
        'Use .strip() to remove spaces before casting.',
        'Use int("7") for whole numbers, float("7.0") for decimals.',
        'Use commas in print() to safely mix text and numbers.',
    ];
    $hintsForJs = !empty($hints) ? $hints : $defaultHints;
@endphp

<script>
  window.FLIP_DATA = {
    time_limit: {{ $timeLimit }},
    max_hints:  {{ $maxHints }},
    hints:  {!! json_encode($hintsForJs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!},
    cards:  {!! json_encode($deck,       JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}
  };
</script>

<x-slot name="header">
    <!-- Same header design you used in the MCQ & match views -->
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
                        <div class="stat-label">Viewed</div>
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
/* Palette + shared UI (same as your MCQ/match headers) */
:root {
    --primary-purple: #7c3aed; --secondary-purple: #a855f7; --light-purple: #c084fc; --purple-subtle: #f3e8ff;
    --gray-50:#f8fafc; --gray-100:#f1f5f9; --gray-200:#e2e8f0; --gray-300:#cbd5e1; --gray-400:#94a3b8; --gray-500:#64748b;
    --gray-600:#475569; --gray-700:#334155; --gray-800:#1e293b; --gray-900:#0f172a;
    --success:#10b981; --success-light:#dcfce7; --warning:#f59e0b; --warning-light:#fef3c7; --danger:#ef4444; --danger-light:#fecaca;
    --background:#ffffff; --border:#e2e8f0; --text-primary:#1e293b; --text-secondary:#475569; --text-muted:#64748b;
    --shadow-sm:0 1px 2px 0 rgba(0,0,0,.05);
    --shadow:0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
    --shadow-md:0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
    --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
}

body {
    background: linear-gradient(135deg, rgba(124,58,237,.03) 0%, rgba(168,85,247,.02) 50%, rgba(248,250,252,1) 100%);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
}

/* Header */
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

/* Helpers */
.full-bleed { width:100vw; margin-left:calc(50% - 50vw); margin-right:calc(50% - 50vw); }
.edge-pad   { padding: 1.25rem clamp(12px, 3vw, 32px); }
.card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem; box-shadow:var(--shadow-sm); }
.card.accent { border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff); }
.section-title { font-size:1.125rem; font-weight:700; margin:0 0 1rem 0; color:var(--text-primary); }

/* Progress header */
.items-container { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
.items-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.items-title { font-size:1.05rem; font-weight:700; }
.progress-container { flex:1; max-width:260px; }
.progress-bar { height:.5rem; background:var(--gray-200); border-radius:.25rem; overflow:hidden; }
.progress-fill { height:100%; width:0%; background:linear-gradient(90deg, var(--primary-purple), var(--secondary-purple)); border-radius:.25rem; transition: width .3s ease; }

/* FLIP CARDS GRID — improved visuals */
.deck-grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
  gap:1.1rem;
}

.flip-card {
  perspective:1200px;
  position:relative;
  border-radius:1rem;
}

.flip-inner {
  position:relative;
  width:100%;
  height:240px;
  transform-style:preserve-3d;
  transition: transform .6s cubic-bezier(.25,.8,.25,1), box-shadow .3s ease;
  border-radius:1rem;
  box-shadow:var(--shadow);
}

.flip-card:hover .flip-inner{ box-shadow:var(--shadow-md); }
.flip-card.flipped .flip-inner { transform: rotateY(180deg); }

.face {
  position:absolute; inset:0;
  display:flex; flex-direction:column; gap:.6rem;
  background:linear-gradient(180deg,#fff, #fafbff);
  border:1px solid var(--border);
  border-radius:1rem; padding:1rem 1.1rem 0.9rem;
  box-shadow:var(--shadow-sm);
  backface-visibility:hidden;
}

/* Subtle pattern + gloss */
.face::after{
  content:""; position:absolute; inset:0;
  background:
    radial-gradient(240px 120px at 90% -10%, rgba(124,58,237,.08), transparent 60%),
    linear-gradient(180deg, rgba(248,250,252,.0), rgba(124,58,237,.05));
  border-radius:inherit; pointer-events:none;
}

.back {
  transform: rotateY(180deg);
  background:linear-gradient(180deg, var(--purple-subtle), #fff);
}

/* Card header */
.card-head {
  display:flex; align-items:center; justify-content:space-between; gap:.5rem;
}
.card-title {
  display:flex; align-items:center; gap:.5rem;
  font-size:1rem; font-weight:700; color:var(--text-primary);
}
.card-icon {
  width:28px; height:28px; border-radius:.6rem;
  display:inline-flex; align-items:center; justify-content:center;
  background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));
  color:#fff; font-size:.9rem; box-shadow:var(--shadow-sm);
}

/* Status chip */
.status-chip {
  display:inline-flex; align-items:center; gap:.4rem;
  font-size:.75rem; padding:.25rem .55rem; border-radius:9999px;
  border:1px solid var(--border); color:var(--text-muted); background:#fff;
}
.status-chip .dot {
  width:.5rem; height:.5rem; border-radius:50%; background:var(--gray-300);
}
.flip-card.flipped .status-chip .dot{ background:var(--success); }

/* Body content */
.face .body {
  color:var(--text-secondary);
  white-space:pre-wrap; line-height:1.35;
}
.face .body code, .face .body pre {
  background:var(--gray-900); color:#cfeaff;
  border:1px solid rgba(255,255,255,.08);
  border-radius:.6rem; padding:.6rem .7rem; margin-top:.25rem;
  font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace; white-space:pre-wrap;
}

/* Footer row */
.card-foot {
  margin-top:auto; display:flex; align-items:center; justify-content:space-between; gap:.5rem;
}
.flip-hint {
  font-size:.8rem; color:var(--text-muted);
}
.tag-pill {
  font-size:.72rem; padding:.22rem .5rem; border-radius:9999px;
  background:#fff; border:1px solid var(--border); color:var(--primary-purple);
  font-weight:600;
}

/* Hover micro-interaction */
.flip-card:hover .face { border-color:#e7e9f2; }
.flip-card:hover .card-icon { transform:translateY(-1px); }

/* “Completed” ribbon (appears when flipped once) */
.ribbon {
  position:absolute; top:.6rem; left:-.45rem; z-index:2;
  background:linear-gradient(135deg, var(--success), #2dd4bf);
  color:#fff; font-size:.72rem; font-weight:800;
  padding:.3rem .6rem; border-top-right-radius:.5rem; border-bottom-right-radius:.5rem;
  box-shadow:var(--shadow-sm); opacity:0; transform:translateX(-6px);
  transition:opacity .25s ease, transform .25s ease;
}
.flip-card.viewed .ribbon{ opacity:1; transform:none; }

/* Small entrance animation */
.flip-card { animation:cardIn .25s ease both; }
@keyframes cardIn {
  from { opacity:0; transform:translateY(6px) scale(.98); }
  to   { opacity:1; transform:translateY(0)  scale(1); }
}


/* Buttons */
.controls-container { display:flex; justify-content:center; gap:1rem; margin:1.25rem 0; flex-wrap:wrap; }
.btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.25rem; border:none; border-radius:.75rem; font-weight:700; font-size:.875rem; cursor:pointer; transition:all .2s ease; text-decoration:none; }
.btn:disabled{ opacity:.5; cursor:not-allowed; }
.btn-primary { background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
.btn-primary:hover:not(:disabled){ transform:translateY(-2px); box-shadow:var(--shadow-lg); }
.btn-secondary { background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border); }
.btn-secondary:hover:not(:disabled){ background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow); }
.btn-ghost { background:transparent; color:var(--text-secondary); border:1px solid var(--border); }
.btn-ghost:hover:not(:disabled){ background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple); }

/* Meta + Toast */
.meta-container { display:flex; justify-content:space-between; align-items:center; background:var(--gray-50); border-top:1px solid var(--border); font-size:.875rem; color:var(--text-muted); }
.meta-left { display:flex; gap:1rem; align-items:center; flex-wrap:wrap; }
.meta-pill { background:#fff; border:1px solid var(--border); padding:.25rem .75rem; border-radius:9999px; font-weight:500; }
.toast-container { position:fixed; top:1rem; right:1rem; display:flex; flex-direction:column; gap:.5rem; z-index:1000; }
.toast { background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:500; min-width:280px; box-shadow:var(--shadow-lg); animation:slideIn .3s ease; }
.toast.ok   { border-left:4px solid var(--success);  background:linear-gradient(135deg,var(--success-light), #fff); }
.toast.warn { border-left:4px solid var(--warning);  background:linear-gradient(135deg,var(--warning-light), #fff); }
.toast.err  { border-left:4px solid var(--danger);   background:linear-gradient(135deg,var(--danger-light),  #fff); }
@keyframes slideIn{ from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)} }

/* Responsive */
@media (max-width:768px){ .header-container{flex-direction:column; align-items:stretch; gap:1rem; padding:1rem;} .edge-pad{padding:1rem} }
</style>

<div class="full-bleed">
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

    <!-- INSTRUCTIONS -->
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

    <!-- DECK -->
    <div class="edge-pad">
      <form id="flipForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
        @csrf
        <input type="hidden" name="score"   id="finalScore"   value="0">
        <input type="hidden" name="answers" id="answersData"  value="{}">

        <!-- Progress header -->
        <div class="items-container" style="margin-bottom:1rem;">
            <div class="items-header">
                <div class="items-title">Flip all cards</div>
                <div class="progress-container">
                    <div class="progress-bar"><div class="progress-fill" id="progressBar"></div></div>
                </div>
            </div>
        </div>

        <div class="deck-grid" id="deckGrid">
@forelse($deck as $card)
  <div class="flip-card" data-card-id="{{ $card['id'] }}" tabindex="0" role="button" aria-label="Flip card {{ $card['id'] }}">
      <span class="ribbon">VIEWED</span>
      <div class="flip-inner">
          <!-- FRONT -->
          <div class="face front">
              <div class="card-head">
                  <div class="card-title">
                      <span class="card-icon"><i class="fas fa-bolt"></i></span>
                      <span>{{ $card['title'] ?? 'Concept' }}</span>
                  </div>
                  <span class="status-chip"><span class="dot"></span> Not flipped</span>
              </div>
              <div class="body">{!! nl2br(e($card['front'])) !!}</div>
              <div class="card-foot">
                  <span class="flip-hint">Click / press <kbd>Space</kbd> to flip</span>
                  <span class="tag-pill">Front</span>
              </div>
          </div>

          <!-- BACK -->
          <div class="face back">
              <div class="card-head">
                  <div class="card-title">
                      <span class="card-icon"><i class="fas fa-check"></i></span>
                      <span>{{ $card['title'] ?? 'Concept' }}</span>
                  </div>
                  <span class="status-chip"><span class="dot"></span> Learned</span>
              </div>
              <div class="body">{!! nl2br(e($card['back'])) !!}</div>
              <div class="card-foot">
                  <span class="flip-hint">Click / press <kbd>Space</kbd> to flip back</span>
                  <span class="tag-pill">Back</span>
              </div>
          </div>
      </div>
  </div>
@empty
  <div class="card">No cards defined for this level.</div>
@endforelse
</div>


        <!-- Controls -->
        <div class="controls-container">
            <button class="btn btn-primary"   type="button" id="btnComplete"><i class="fas fa-flag-checkered"></i> Mark Complete</button>
            <button class="btn btn-secondary" type="button" id="btnHint"><i class="fas fa-lightbulb"></i> Hint</button>
            <button class="btn btn-ghost"     type="button" id="btnShuffle"><i class="fas fa-shuffle"></i> Shuffle</button>
            <button class="btn btn-ghost"     type="button" id="btnReset"><i class="fas fa-rotate-left"></i> Reset Views</button>
        </div>
      </form>
    </div>

    <!-- OPTIONAL EXAMPLES -->
    @if(!empty($examples))
    <div class="edge-pad">
        <div class="card">
            <div class="section-title">Examples</div>
            <div class="deck-grid">
                @foreach($examples as $ex)
                    <div class="card">
                        <h4 style="margin:.25rem 0  .5rem;">{{ $ex['title'] ?? 'Example' }}</h4>
                        @if(!empty($ex['code']))
                            <pre style="margin:0"><code>{{ $ex['code'] }}</code></pre>
                        @endif
                        @if(!empty($ex['explain']))
                            <div style="margin-top:.5rem; color:var(--text-secondary)">{{ $ex['explain'] }}</div>
                        @endif
                        @if(!empty($ex['expected_output']))
                            <div style="margin-top:.5rem; font-weight:700; color:var(--primary-purple)">Expected: {{ $ex['expected_output'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
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

<!-- Toasts + icons -->
<div class="toast-container" id="toastWrap"></div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
(function(){
  const data        = window.FLIP_DATA || {};
  const CARDS       = Array.isArray(data.cards) ? data.cards : [];
  const HINTS       = Array.isArray(data.hints) ? data.hints : [];
  const timeLimit   = Number.isFinite(data.time_limit) ? data.time_limit : 240;
  const maxHints    = Number.isFinite(data.max_hints)  ? data.max_hints  : 3;

  // State
  let timeRemaining = timeLimit;
  let hintsUsed = 0;
  let flippedSet = new Set();  // card ids flipped at least once
  let submitted = false;

  // DOM
  const $timer     = document.getElementById('timeRemaining');
  const $statScore = document.getElementById('statScore');
  const $statStars = document.getElementById('statStars');
  const $metaStars = document.getElementById('metaStars');
  const $progress  = document.getElementById('progressBar');
  const $hintCount = document.getElementById('hintCount');
  const $toastWrap = document.getElementById('toastWrap');
  const $form      = document.getElementById('flipForm');
  const $deckGrid  = document.getElementById('deckGrid');

  const $toggleInstrux = document.getElementById('toggleInstrux');
  const $instruxBody   = document.getElementById('instruxBody');

  const $btnComplete = document.getElementById('btnComplete');
  const $btnHint     = document.getElementById('btnHint');
  const $btnShuffle  = document.getElementById('btnShuffle');
  const $btnReset    = document.getElementById('btnReset');

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
  function fmtTime(sec){ const m=String(Math.floor(sec/60)).padStart(2,'0'); const s=String(sec%60).padStart(2,'0'); return `${m}:${s}`; }
  function toast(msg, kind='ok'){ const el=document.createElement('div'); el.className=`toast ${kind}`; el.textContent=msg; $toastWrap.appendChild(el); setTimeout(()=>el.remove(), 2200); }
  function starsFor(score){ if(score>=90) return 3; if(score>=70) return 2; if(score>=50) return 1; return 0; }
  function updateProgress(){
    const total = CARDS.length || 1;
    const pct = Math.round(100 * (flippedSet.size) / total);
    $progress.style.width = pct + '%';
    $statScore.textContent = pct + '%';
    const starCount = starsFor(pct);
    $statStars.textContent = starCount ? '★'.repeat(starCount) : '0';
    if ($metaStars) $metaStars.textContent = starCount ? '★'.repeat(starCount) : '0';
  }

  // Flip handling
  document.querySelectorAll('.flip-card').forEach(cardEl => {
    cardEl.addEventListener('click', () => {
      cardEl.classList.toggle('flipped');
      const id = parseInt(cardEl.getAttribute('data-card-id'), 10);
      if (Number.isFinite(id)) flippedSet.add(id);
      updateProgress();
    });
  });

  // Timer
  $timer.textContent = fmtTime(timeRemaining);
  const timer = setInterval(() => {
    timeRemaining--;
    $timer.textContent = fmtTime(timeRemaining);
    if ([60,30,10].includes(timeRemaining)) toast(`${timeRemaining}s left`, 'warn');
    if (timeRemaining <= 0){
      clearInterval(timer);
      if (!submitted){ toast('Time up — submitting…', 'warn'); submitNow(); }
    }
  }, 1000);

  // Controls
  $btnHint.addEventListener('click', () => {
    if (submitted) return;
    if (hintsUsed >= maxHints) { toast('No more hints.', 'warn'); return; }
    hintsUsed++; $hintCount.textContent = hintsUsed;
    // Flip the first unflipped card as a “hint”
    const target = Array.from(document.querySelectorAll('.flip-card')).find(el => {
      const id = parseInt(el.getAttribute('data-card-id'), 10);
      return !flippedSet.has(id);
    });
    if (target){
      target.classList.add('flipped');
      const id = parseInt(target.getAttribute('data-card-id'), 10);
      if (Number.isFinite(id)) flippedSet.add(id);
    }
    const msg = HINTS[(hintsUsed - 1) % (HINTS.length || 1)] || 'input() is text. Convert first.';
    toast('Hint: ' + msg, 'ok');
    updateProgress();
  });

  $btnShuffle.addEventListener('click', () => {
    if (submitted) return;
    const items = Array.from($deckGrid.children);
    for (let i = items.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random()*(i+1));
      $deckGrid.appendChild(items[j]);
    }
    toast('Shuffled!', 'ok');
  });

  $btnReset.addEventListener('click', () => {
    if (submitted) return;
    if (!confirm('Reset flips and progress?')) return;
    flippedSet.clear();
    document.querySelectorAll('.flip-card').forEach(el => el.classList.remove('flipped'));
    updateProgress();
    toast('Cleared.', 'ok');
  });

  $btnComplete.addEventListener('click', () => { if (!submitted) submitNow(); });

  function submitNow(){
    submitted = true;
    $btnComplete.disabled = true; $btnHint.disabled = true; $btnShuffle.disabled = true; $btnReset.disabled = true;
    clearInterval(timer);

    const total = CARDS.length || 1;
    const pct = Math.round(100 * flippedSet.size / total);
    const finalScore = pct; // Learning deck: score = % viewed

    document.getElementById('finalScore').value = finalScore;
    document.getElementById('answersData').value = JSON.stringify({ viewed: Array.from(flippedSet), total });

    const passReq = {{ (int)$level->pass_score }};
    toast(finalScore >= passReq ? `Great job! Viewed ${finalScore}%` : `Viewed ${finalScore}%. Keep flipping!`, finalScore >= passReq ? 'ok' : 'err');

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

  updateProgress();
})();
</script>
</x-app-layout>
