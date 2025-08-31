<x-app-layout>
  @php
    // ===============================
    // Safe data for Blade
    // ===============================
    // Questions array can be JSON/text or PHP array on the model
    $raw = $assessment->questions ?? [];
    $questions = is_array($raw) ? $raw : (json_decode($raw ?: '[]', true) ?: []);
    $total = count($questions);

    // Optional time limit (seconds) if present on model->settings/content; fallback 10 minutes
    $timeLimit = (int)($assessment->time_limit ?? ($assessment->content['time_limit'] ?? 600));
  @endphp

  {{-- HEADER (Cosmic style to match previous pages) --}}
  <x-slot name="header">
    <div class="epic-level-header">
      <div class="container-fluid">
        <div class="row align-items-center g-3">
          <div class="col-auto">
            <div class="level-badge">
              <span class="level-number">{{ $total ?: '?' }}</span>
            </div>
          </div>
          <div class="col">
            <div class="level-info">
              <h2 class="level-title mb-1">{{ $assessment->title }}</h2>
              <div class="level-subtitle">
                <i class="fas fa-gamepad me-2"></i> Cosmic Quiz Challenge
              </div>
            </div>
          </div>
          <div class="col-auto">
            <div class="level-stats">
              <div class="stat-item me-3">
                <div class="stat-icon">📈</div>
                <div class="stat-value" id="answeredPct">0%</div>
                <div class="stat-label">Progress</div>
              </div>
              <div class="stat-item me-3">
                <div class="stat-icon">✅</div>
                <div class="stat-value"><span id="answeredCount">0</span>/<span id="totalCount">{{ $total }}</span></div>
                <div class="stat-label">Answered</div>
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
      --deep-purple:#1a0636; --cosmic-purple:#4a1b6d; --space-blue:#162b6f; --dark-space:#0a1028;
      --neon-blue:#00b3ff; --neon-purple:#b967ff; --electric-blue:#05d9e8;
      --ink:#ffffff; --muted:#cfc8ff; --border:rgba(255,255,255,.16);
    }
    body{
      background:linear-gradient(45deg,var(--deep-purple),var(--cosmic-purple),var(--space-blue),var(--dark-space));
      min-height:100vh; font-family:'Orbitron','Arial',sans-serif; color:var(--ink);
    }

    /* Header (matches the epic pages) */
    .epic-level-header{background:rgba(10,6,30,.9);backdrop-filter:blur(20px);border-bottom:3px solid var(--neon-purple);padding:20px 0;position:relative;overflow:hidden}
    .epic-level-header::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;
      background:linear-gradient(90deg,transparent,rgba(185,103,255,.1),transparent);animation:headerShine 4s ease-in-out infinite}
    @keyframes headerShine{0%{left:-100%}50%,100%{left:100%}}
    .level-badge{width:70px;height:70px;background:linear-gradient(135deg,#ffd700,#ffed4a);border-radius:50%;
      display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(185,103,255,.6);animation:levelPulse 2s ease-in-out infinite}
    @keyframes levelPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05);box-shadow:0 0 40px rgba(185,103,255,.8)}}
    .level-number{font-size:1.8rem;font-weight:900;color:#333;text-shadow:1px 1px 2px rgba(0,0,0,.3)}
    .level-title{color:var(--neon-blue);font-size:1.8rem;font-weight:900;letter-spacing:.2px}
    .level-subtitle{color:rgba(255,255,255,.85)}
    .level-stats{display:flex;align-items:center;gap:14px}
    .stat-item{text-align:center;color:#fff;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:12px 14px;border-radius:14px;border:1px solid rgba(255,255,255,.2);min-width:110px}
    .stat-icon{font-size:1.2rem;margin-bottom:4px}
    .stat-value{font-size:1.05rem;font-weight:900;color:var(--neon-purple)}
    .stat-label{font-size:.8rem;opacity:.85}

    /* Full-width progress band */
    .progress-band{max-width:1400px;margin:16px auto 0;padding:0 20px}
    .progress-shell{height:12px;background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:999px;overflow:hidden}
    .progress-bar{height:100%;width:0%;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));position:relative;transition:width .6s cubic-bezier(.25,.46,.45,.94)}
    .progress-bar::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.45),transparent);animation:progressShine 2s ease-in-out infinite}
    @keyframes progressShine{0%{left:-100%}100%{left:100%}}

    /* Quiz Arena */
    .quiz-arena{display:flex;align-items:center;justify-content:center;min-height:calc(100vh - 180px);padding:28px}
    .question-card{
      background:linear-gradient(145deg, rgba(26,6,54,.95), rgba(74,27,109,.95));
      border:3px solid var(--neon-purple); border-radius:28px; padding:36px; width:min(1400px,96vw); min-height:520px;
      color:#fff; box-shadow:0 0 60px rgba(185,103,255,.35); display:flex; flex-direction:column; justify-content:space-between; animation:popIn .35s ease
    }
    @keyframes popIn{from{transform:scale(.96);opacity:0}to{transform:scale(1);opacity:1}}
    .q-head{display:flex;gap:12px;align-items:center;margin-bottom:8px}
    .q-number{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,var(--neon-blue),var(--neon-purple));display:flex;align-items:center;justify-content:center;font-weight:900;color:#0e1126}
    .q-title{font-size:1rem;color:#cfd4ff;letter-spacing:.3px}
    .q-text{font-size:1.6rem;margin:6px 0 10px;font-weight:900;color:#fff;line-height:1.35}
    .code{background:rgba(0,0,0,.85);border:2px solid var(--neon-blue);border-radius:14px;padding:12px 14px;margin:8px 0 12px;white-space:pre-wrap;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;color:#cfeaff}
    .options{margin:0 auto 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px}
    .option{position:relative;display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.08);border:2px solid rgba(185,103,255,.35);padding:16px 18px;border-radius:16px;cursor:pointer;font-size:1.15rem;font-weight:700;transition:transform .15s ease, box-shadow .2s ease, border-color .15s ease}
    .option:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.28);border-color:rgba(185,103,255,.7)}
    .option input{appearance:none;width:20px;height:20px;border-radius:6px;border:2px solid rgba(255,255,255,.7);display:inline-block;position:relative}
    .option input:checked{background:linear-gradient(135deg,var(--electric-blue),var(--neon-blue));border-color:transparent;box-shadow:0 0 0 3px rgba(0,179,255,.25)}
    .option.selected{outline:3px solid rgba(0,179,255,.25)}
    .nav-btns{display:flex;justify-content:space-between;gap:14px;margin-top:8px;flex-wrap:wrap}
    .btn{flex:1;min-width:170px;padding:16px 22px;border:none;border-radius:18px;font-weight:900;font-size:1.05rem;cursor:pointer;transition:transform .2s ease, box-shadow .25s ease;color:#fff;text-transform:uppercase;letter-spacing:.8px}
    .btn-prev{background:linear-gradient(135deg,#b967ff,#6a1b9a)}
    .btn-next{background:linear-gradient(135deg,#05d9e8,#00b3ff)}
    .btn-submit{background:linear-gradient(135deg,#00b3ff,#b967ff)}
    .btn:hover{transform:translateY(-2px);box-shadow:0 0 32px rgba(185,103,255,.45)}

    /* Toast feedback (matches other pages) */
    .feedback-container{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;pointer-events:none}
    .feedback{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));color:#fff;padding:12px 20px;border-radius:18px;font-weight:800;
      box-shadow:0 10px 40px rgba(0,0,0,.3);animation:pop .9s ease;margin-bottom:8px}
    .feedback.warn{background:linear-gradient(45deg,#f093fb,#f5576c)}
    .feedback.err{background:linear-gradient(45deg,#ff2a6d,#ff6a00)}
    @keyframes pop{0%{transform:scale(.9);opacity:0}25%{transform:scale(1.05);opacity:1}100%{transform:scale(1);opacity:1}}

    @media (max-width: 640px){
      .question-card{padding:20px}
      .q-text{font-size:1.3rem}
      .options{grid-template-columns:1fr}
      .btn{min-width:140px}
    }
  </style>

  {{-- Slim progress band under the header --}}
  <div class="progress-band">
    <div class="progress-shell">
      <div id="progressBar" class="progress-bar"></div>
    </div>
  </div>

  <div class="quiz-arena">
    <form method="POST" action="{{ route('assessments.submit', $assessment) }}" id="quizForm" novalidate>
      @csrf

      @if(!$total)
        <div class="question-card" style="justify-content:center;align-items:center;text-align:center;min-height:auto">
          <div class="q-text">No questions are available for this assessment.</div>
        </div>
      @endif

      @foreach($questions as $index => $q)
        @php
          $prompt = $q['prompt'] ?? '';
          $code   = $q['code'] ?? null;
          $opts   = $q['options'] ?? [];
        @endphp
        <div class="question-card" id="question-{{ $index }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
          <div>
            <div class="q-head">
              <div class="q-number">{{ $index + 1 }}</div>
              <div class="q-title">Question {{ $index + 1 }} of {{ $total }}</div>
            </div>
            <div class="q-text">{{ $prompt }}</div>
            @if($code)
              <pre class="code"><code>{{ $code }}</code></pre>
            @endif

            <div class="options" role="group" aria-labelledby="q{{ $index }}-label">
              @foreach($opts as $optIndex => $option)
                <label class="option">
                  <input type="radio"
                         name="answers[{{ $index }}]"
                         value="{{ $option }}"
                         data-q-index="{{ $index }}">
                  <span>{{ $option }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="nav-btns">
            @if($index > 0)
              <button type="button" class="btn btn-prev" data-action="prev" data-index="{{ $index }}">⬅ Previous</button>
            @endif

            @if($index < $total - 1)
              <button type="button" class="btn btn-next" data-action="next" data-index="{{ $index }}">Next ➡</button>
            @else
              <button type="submit" class="btn btn-submit">🚀 Submit Quiz</button>
            @endif
          </div>
        </div>
      @endforeach
    </form>
  </div>

  <div id="feedbackHost" class="feedback-container"></div>

  <script>
    (function(){
      const TOTAL = {{ $total }};
      const TIME_LIMIT = {{ (int)$timeLimit }};
      if (!TOTAL) return;

      // --- State ---
      let current = 0;
      let answers = {}; // index -> value (string)
      let timeRemaining = TIME_LIMIT;

      // --- DOM ---
      const $form = document.getElementById('quizForm');
      const $progress = document.getElementById('progressBar');
      const $answeredPct = document.getElementById('answeredPct');
      const $answeredCount = document.getElementById('answeredCount');
      const $time = document.getElementById('timeRemaining');
      const $feedbackHost = document.getElementById('feedbackHost');

      // Initialize: mark required only on the first visible slide
      applyRequiredForSlide(0);

      // Enhance options (remember selection & highlight)
      document.querySelectorAll('.options input[type=radio]').forEach(r => {
        r.addEventListener('change', (e) => {
          const idx = +e.target.dataset.qIndex;
          answers[idx] = e.target.value;

          // highlight selected label
          const group = document.querySelectorAll(`.options input[name="answers[${idx}]"]`);
          group.forEach(el => el.parentElement.classList.remove('selected'));
          e.target.parentElement.classList.add('selected');

          updateProgress();
        });
      });

      // Nav buttons
      document.querySelectorAll('.btn[data-action]').forEach(btn => {
        btn.addEventListener('click', () => {
          const action = btn.dataset.action;
          const idx = +btn.dataset.index;
          if (action === 'next') goTo(idx + 1, true);
          if (action === 'prev') goTo(idx - 1, false);
        });
      });

      // Keyboard navigation
      document.addEventListener('keydown', (e) => {
        // Ignore if user is typing in an input
        if (['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) return;
        if (e.key === 'ArrowRight') goTo(current + 1, true);
        if (e.key === 'ArrowLeft')  goTo(current - 1, false);
      });

      // Form submit validation
      $form.addEventListener('submit', (e) => {
        if (!allAnswered()) {
          e.preventDefault();
          const firstUn = firstUnanswered();
          showFeedback('⚠️ Please answer all questions before submitting.', 'warn');
          if (firstUn !== null) goTo(firstUn, false, true);
        }
      });

      // Timer
      $time.textContent = fmt(timeRemaining);
      const timer = setInterval(() => {
        timeRemaining--;
        $time.textContent = fmt(timeRemaining);
        if ([60, 30, 10].includes(timeRemaining)) showFeedback(`${timeRemaining}s remaining`, 'warn');
        if (timeRemaining <= 0) {
          clearInterval(timer);
          showFeedback("⏰ Time's up! Submitting…", 'err');
          $form.submit();
        }
      }, 1000);

      // Helpers
      function goTo(target, enforceAnswered, focusFirst = false){
        if (target < 0 || target >= TOTAL) return;

        // Enforce current answered before moving forward
        if (enforceAnswered && !answers.hasOwnProperty(current)) {
          showFeedback('Pick an answer to continue.', 'warn');
          return;
        }

        // Hide current, show target
        const curEl = document.getElementById('question-' + current);
        const nextEl = document.getElementById('question-' + target);
        if (curEl) curEl.style.display = 'none';
        if (nextEl) nextEl.style.display = 'flex';
        current = target;

        // Required toggling
        applyRequiredForSlide(current);

        // If we already chose on this slide, re-apply highlight
        const chosen = document.querySelector(`input[name="answers[${current}]"]:checked`);
        if (chosen) chosen.parentElement.classList.add('selected');
        if (focusFirst) {
          const firstOpt = nextEl?.querySelector('input[type=radio]');
          if (firstOpt) firstOpt.focus();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      function applyRequiredForSlide(idx){
        // Remove required from all
        document.querySelectorAll('.options input[type=radio]').forEach(r => r.required = false);
        // Make only current slide's radios required (at least one of them)
        document.querySelectorAll(`#question-${idx} .options input[type=radio]`).forEach(r => r.required = true);
      }

      function updateProgress(){
        const answered = Object.keys(answers).length;
        const pct = TOTAL ? Math.round(100 * answered / TOTAL) : 0;
        $progress.style.width = pct + '%';
        $answeredPct.textContent = pct + '%';
        $answeredCount.textContent = answered;
      }

      function allAnswered(){ return Object.keys(answers).length === TOTAL; }
      function firstUnanswered(){
        for (let i=0;i<TOTAL;i++){ if (!answers.hasOwnProperty(i)) return i; }
        return null;
      }
      function fmt(sec){
        const m = String(Math.floor(sec/60)).padStart(2,'0');
        const s = String(sec%60).padStart(2,'0');
        return `${m}:${s}`;
      }
      function showFeedback(msg, kind='ok', dur=1600){
        const el = document.createElement('div');
        el.className = 'feedback' + (kind==='warn' ? ' warn' : kind==='err' ? ' err' : '');
        el.textContent = msg;
        $feedbackHost.appendChild(el);
        setTimeout(()=>el.remove(), dur);
      }

      // Initial progress render
      updateProgress();
    })();
  </script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
