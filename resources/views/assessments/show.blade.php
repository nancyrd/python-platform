<x-app-layout>
  @php
    // ===============================
    // Safe data for Blade
    // ===============================
    $raw = $assessment->questions ?? [];
    $questions = is_array($raw) ? $raw : (json_decode($raw ?: '[]', true) ?: []);
    $total = count($questions);

    // Optional time limit (seconds) from model/content; fallback 8 minutes
    $timeLimit = (int)($assessment->time_limit ?? ($assessment->content['time_limit'] ?? 480));

    // Optional instructions
    $instructions = $assessment->instructions
      ?? ($assessment->content['instructions'] ?? null);
  @endphp

  {{-- HEADER — EXACT same structure/colors as your purple “level-header” pages --}}
  <x-slot name="header">
    <div class="level-header">
      <div class="header-container">
        <!-- Left -->
        <div class="header-left">
          <div class="level-badge">
            <span class="level-number">{{ $total ?: '?' }}</span>
          </div>
          <div class="level-info">
            <div class="breadcrumb">
              <span class="breadcrumb-item">{{ $assessment->stage->title ?? 'Stage' }}</span>
              <span class="separator">•</span>
              <span class="breadcrumb-item">Questions</span>
              <span class="separator">•</span>
              <span class="breadcrumb-item type">{{ strtolower($assessment->type ?? 'assessment') }}</span>
            </div>
            <h1 class="stage-title">{{ $assessment->title }}</h1>
            <div class="level-title">Answer all questions, then submit</div>
          </div>
        </div>

        <!-- Right stats -->
        <div class="header-right">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-label">Progress</div>
              <div class="stat-value" id="answeredPct">0%</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Answered</div>
              <div class="stat-value"><span id="answeredCount">0</span>/<span id="totalCount">{{ $total }}</span></div>
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
      /* SAME palette as your purple UI pages */
      --primary-purple: #7c3aed;
      --secondary-purple: #a855f7;
      --light-purple: #c084fc;
      --purple-subtle: #f3e8ff;

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

      --success: #10b981;
      --success-light: #dcfce7;
      --warning: #f59e0b;
      --warning-light: #fef3c7;
      --danger: #ef4444;
      --danger-light: #fecaca;

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
      font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;
      line-height: 1.5;
    }

    /* Header */
    .level-header { background: linear-gradient(135deg, rgba(124,58,237,.05) 0%, rgba(168,85,247,.03) 100%); border-bottom:1px solid var(--border); backdrop-filter: blur(10px); }
    .header-container { display:flex; align-items:center; justify-content:space-between; padding:1.5rem 2rem; gap:2rem; }
    .header-left { display:flex; align-items:center; gap:1.5rem; flex:1; min-width:0; }
    .level-badge { width:4rem; height:4rem; border-radius:1rem; background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-md); flex-shrink:0; }
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

    /* Sections */
    .section-title { font-size:1.125rem; font-weight:700; margin:0 0 1rem 0; color:var(--text-primary); }
    .card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem 1.5rem; box-shadow:var(--shadow-sm); }
    .card.accent { border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff); }

    /* Global progress */
    .items-container { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
    .items-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .items-title { font-size:1.05rem; font-weight:700; }
    .progress-container { flex:1; max-width:280px; }
    .progress-bar { height:.5rem; background:var(--gray-200); border-radius:.25rem; overflow:hidden; }
    .progress-fill { height:100%; width:0%; background:linear-gradient(90deg, var(--primary-purple), var(--secondary-purple)); border-radius:.25rem; transition: width .3s ease; }

    /* Question list */
    .q-list { display:flex; flex-direction:column; gap:1rem; margin-top:1rem; }
    .q-card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1rem 1.25rem; box-shadow:var(--shadow-sm); }
    .q-head { display:flex; align-items:center; gap:.75rem; margin-bottom:.5rem; }
    .q-num { width:2rem; height:2rem; border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-weight:800; color:#fff; background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); }
    .q-title { font-weight:700; color:var(--text-primary); }
    .q-code { background:#0f172a; color:#e2e8f0; border:1px solid rgba(255,255,255,.08); border-radius:.5rem; padding:.5rem .625rem; margin:.5rem 0 0; white-space:pre-wrap; font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace; }

    .options { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:.75rem; margin-top:.75rem; }
    .opt { display:flex; align-items:center; gap:.6rem; padding:.75rem 1rem; background:var(--gray-50); border:1px solid var(--border); border-radius:.75rem; cursor:pointer; transition:all .18s ease; }
    .opt:hover { background:var(--gray-100); border-color:var(--primary-purple); box-shadow:var(--shadow); transform:translateY(-1px); }
    .opt input[type="radio"]{ width:18px; height:18px; accent-color: var(--primary-purple); }
    .opt span { color:var(--text-primary); font-weight:600; }
    .opt:has(input[type="radio"]:checked){ border-color:var(--primary-purple); box-shadow:0 0 0 3px rgba(124,58,237,.18) inset; background:var(--purple-subtle); }

    /* Submit */
    .controls { display:flex; justify-content:center; gap:1rem; margin:1.25rem 0 .25rem; flex-wrap:wrap; }
    .btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.5rem; border:none; border-radius:.75rem; font-weight:700; font-size:.9rem; cursor:pointer; transition:all .18s ease; text-decoration:none; }
    .btn-primary { background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:var(--shadow-lg); }
    .btn-ghost { background:transparent; color:var(--text-secondary); border:1px solid var(--border); }
    .btn-ghost:hover { background:var(--gray-50); border-color:var(--primary-purple); color:var(--primary-purple); }

    /* Toasts */
    .toast-container { position:fixed; top:1rem; right:1rem; display:flex; flex-direction:column; gap:.5rem; z-index:1000; }
    .toast { background:#fff; border:1px solid var(--border); color:var(--text-primary); padding:1rem 1.25rem; border-radius:.75rem; font-weight:600; min-width:280px; box-shadow:var(--shadow-lg); animation:slideIn .25s ease; }
    .toast.warn { border-left:4px solid var(--warning); background:linear-gradient(135deg,var(--warning-light), #fff); }
    .toast.err  { border-left:4px solid var(--danger);  background:linear-gradient(135deg,var(--danger-light),  #fff); }
    @keyframes slideIn{ from{opacity:0; transform:translateX(100%)} to{opacity:1; transform:translateX(0)} }

    @media (max-width:768px){
      .header-container{flex-direction:column; align-items:stretch; gap:1rem; padding:1rem;}
      .options { grid-template-columns:1fr; }
    }
  </style>

  <!-- PROGRESS BAND (under header) -->
  <div class="full-bleed edge-pad">
    <div class="items-container">
      <div class="items-header">
        <div class="items-title">Assessment Progress</div>
        <div class="progress-container">
          <div class="progress-bar"><div id="progressBar" class="progress-fill"></div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- INSTRUCTIONS (TOP) -->
  @if($instructions)
    <div class="full-bleed edge-pad">
      <div class="card accent" id="instructionsCard">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
          <div class="section-title">Instructions</div>
          <button class="btn btn-ghost" type="button" id="toggleInstrux" aria-expanded="true">
            <i class="fas fa-chevron-up"></i> Collapse
          </button>
        </div>
        <div id="instruxBody" style="white-space:pre-wrap;">{!! nl2br(e($instructions)) !!}</div>
      </div>
    </div>
  @endif

  <!-- QUESTIONS -->
  <div class="full-bleed edge-pad">
    <form method="POST" action="{{ route('assessments.submit', $assessment) }}" id="quizForm" novalidate>
      @csrf

      @if(!$total)
        <div class="card">
          <strong>No questions available.</strong>
        </div>
      @else
        <div class="q-list">
          @foreach($questions as $i => $q)
            @php
              $opts = is_array($q['options'] ?? null) ? $q['options'] : [];
              $oldValue = old("answers.$i");
            @endphp

            <div class="q-card" id="qcard-{{ $i }}">
              <div class="q-head">
                <div class="q-num">{{ $i + 1 }}</div>
                <div class="q-title">Question {{ $i + 1 }} of {{ $total }}</div>
              </div>

              <div class="q-text">{{ $q['prompt'] ?? 'Question' }}</div>
              @if(!empty($q['code']))
                <pre class="q-code"><code>{{ $q['code'] }}</code></pre>
              @endif

              <div class="options" role="group" aria-labelledby="q-{{ $i }}-label">
                @forelse($opts as $opt)
                  <label class="opt">
                    <input
                      type="radio"
                      name="answers[{{ $i }}]"
                      value="{{ $opt }}"
                      data-q-index="{{ $i }}"
                      {{ $oldValue === $opt ? 'checked' : '' }}
                      required
                    >
                    <span>{{ $opt }}</span>
                  </label>
                @empty
                  <div style="color:var(--text-muted); font-style:italic;">No options provided.</div>
                @endforelse
              </div>

              @error("answers.$i")
                <div style="margin-top:.5rem; background:var(--danger-light); border:1px solid var(--danger); color:#7f1d1d; border-radius:.5rem; padding:.5rem .75rem;">
                  <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                </div>
              @enderror
            </div>
          @endforeach
        </div>

        <div class="controls">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Submit Answers
          </button>
          <button type="button" class="btn btn-ghost" id="scrollTopBtn">
            <i class="fas fa-arrow-up"></i> Back to Top
          </button>
        </div>
      @endif
    </form>
  </div>

  <div class="toast-container" id="toastWrap"></div>

  <script>
    (function(){
      const TOTAL = {{ $total }};
      const TIME_LIMIT = {{ (int)$timeLimit }};
      const $form = document.getElementById('quizForm');
      const $progress = document.getElementById('progressBar');
      const $answeredPct = document.getElementById('answeredPct');
      const $answeredCount = document.getElementById('answeredCount');
      const $time = document.getElementById('timeRemaining');
      const $toastWrap = document.getElementById('toastWrap');

      // Instructions collapse (if present)
      const $toggleInstrux = document.getElementById('toggleInstrux');
      const $instruxBody = document.getElementById('instruxBody');
      if ($toggleInstrux && $instruxBody){
        $toggleInstrux.addEventListener('click', () => {
          const hidden = $instruxBody.classList.toggle('d-none');
          $toggleInstrux.innerHTML = hidden
            ? '<i class="fas fa-chevron-down"></i> Expand'
            : '<i class="fas fa-chevron-up"></i> Collapse';
          $toggleInstrux.setAttribute('aria-expanded', String(!hidden));
        });
      }

      function toast(msg, kind='ok'){
        const el = document.createElement('div');
        el.className = 'toast ' + (kind === 'warn' ? 'warn' : kind === 'err' ? 'err' : '');
        el.textContent = msg;
        $toastWrap.appendChild(el);
        setTimeout(()=>el.remove(), 2200);
      }

      if (!TOTAL) return;

      // Style selected options + update progress
      document.querySelectorAll('.opt input[type="radio"]').forEach(r => {
        if (r.checked) r.closest('.opt')?.classList.add('selected');
        r.addEventListener('change', (e) => {
          const idx = e.target.dataset.qIndex;
          document.querySelectorAll(`input[name="answers[${idx}]"]`).forEach(x => x.closest('.opt')?.classList.remove('selected'));
          e.target.closest('.opt')?.classList.add('selected');
          updateProgress();
        });
      });

      function updateProgress(){
        const answered = document.querySelectorAll('.opt input[type="radio"]:checked').length;
        const pct = TOTAL ? Math.round(100 * answered / TOTAL) : 0;
        $progress.style.width = pct + '%';
        $answeredPct.textContent = pct + '%';
        $answeredCount.textContent = answered;
      }
      updateProgress();

      // Timer
      let timeRemaining = TIME_LIMIT;
      function fmt(sec){ const m=String(Math.floor(sec/60)).padStart(2,'0'); const s=String(sec%60).padStart(2,'0'); return `${m}:${s}`; }
      if ($time) $time.textContent = fmt(timeRemaining);
      const timer = setInterval(() => {
        timeRemaining--;
        if ($time) $time.textContent = fmt(timeRemaining);
        if ([60,30,10].includes(timeRemaining)) toast(`${timeRemaining}s remaining`, 'warn');
        if (timeRemaining <= 0) {
          clearInterval(timer);
          toast("⏰ Time's up! Submitting…", 'err');
          $form.submit();
        }
      }, 1000);

      // Submit gate — require all questions answered
      $form.addEventListener('submit', (e) => {
        const answered = document.querySelectorAll('.opt input[type="radio"]:checked').length;
        if (answered !== TOTAL) {
          e.preventDefault();
          toast('Please answer all questions before submitting.', 'warn');
          for (let i=0;i<TOTAL;i++){
            if (!document.querySelector(`input[name="answers[${i}]"]:checked`)) {
              document.getElementById('qcard-'+i)?.scrollIntoView({behavior:'smooth', block:'center'});
              break;
            }
          }
        }
      });

      // Back to top
      const $scrollTopBtn = document.getElementById('scrollTopBtn');
      if ($scrollTopBtn){
        $scrollTopBtn.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));
      }
    })();
  </script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
