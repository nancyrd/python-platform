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
  {{-- HEADER — SAME structure/colors as your purple level pages --}}
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
    
    * {
      box-sizing: border-box;
    }
    
    body {
      background: linear-gradient(135deg,
        rgba(124,58,237,.03) 0%,
        rgba(168,85,247,.02) 50%,
        rgba(248,250,252,1) 100%);
      color: var(--text-primary);
      font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;
      line-height: 1.5;
      overflow-x: hidden;
      width: 100%;
    }
    
    /* Header */
    .level-header { 
      background: linear-gradient(135deg, rgba(124,58,237,.05) 0%, rgba(168,85,247,.03) 100%); 
      border-bottom:1px solid var(--border); 
      backdrop-filter: blur(10px); 
      width: 100%;
      overflow: hidden;
    }
    .header-container { 
      display:flex; 
      align-items:center; 
      justify-content:space-between; 
      padding:1.5rem 2rem; 
      gap:2rem; 
      max-width: 100%;
      width: 100%;
      overflow: hidden;
    }
    .header-left { 
      display:flex; 
      align-items:center; 
      gap:1.5rem; 
      flex:1; 
      min-width:0; 
      overflow: hidden;
    }
    .level-badge { 
      width:4rem; 
      height:4rem; 
      border-radius:1rem; 
      background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      box-shadow:var(--shadow-md); 
      flex-shrink:0; 
    }
    .level-number { 
      font-weight:900; 
      font-size:1.25rem; 
      color:#fff; 
    }
    .level-info { 
      flex:1; 
      min-width:0; 
      overflow: hidden;
    }
    .breadcrumb { 
      display:flex; 
      align-items:center; 
      gap:.5rem; 
      font-size:.875rem; 
      color:var(--text-muted); 
      margin-bottom:.25rem;
      flex-wrap: wrap;
    }
    .breadcrumb-item.type { 
      text-transform:capitalize; 
      color:var(--primary-purple); 
      font-weight:500; 
    }
    .separator{opacity:.6}
    .stage-title { 
      font-size:1.5rem; 
      font-weight:700; 
      margin:0; 
      line-height:1.2; 
      color:var(--text-primary);
      word-break: break-word;
      overflow-wrap: break-word;
    }
    .level-title { 
      font-size:1rem; 
      color:var(--text-secondary); 
      margin-top:.25rem; 
    }
    .stats-grid { 
      display:grid; 
      grid-template-columns:repeat(3,1fr); 
      gap:1rem;
      width: 100%;
    }
    .stat-item { 
      text-align:center; 
      padding:.75rem 1rem; 
      background:#fff; 
      border:1px solid var(--border); 
      border-radius:.75rem; 
      box-shadow:var(--shadow-sm); 
      min-width:5rem;
    }
    .stat-label { 
      font-size:.75rem; 
      color:var(--text-muted); 
      font-weight:500; 
      text-transform:uppercase; 
      letter-spacing:.05em; 
    }
    .stat-value { 
      font-size:1.125rem; 
      font-weight:700; 
      color:var(--text-primary); 
      margin-top:.25rem; 
    }
    /* Full-bleed helpers */
    .full-bleed { 
      width:100%; 
      max-width: 100vw;
      margin-left:0;
      margin-right:0;
      overflow: hidden;
    }
    .edge-pad   { 
      padding: 1.25rem clamp(12px, 3vw, 32px);
      width: 100%;
      max-width: 100%;
      overflow: hidden;
    }
    /* Sections */
    .section-title { 
      font-size:1.125rem; 
      font-weight:700; 
      margin:0 0 1rem 0; 
      color:var(--text-primary); 
      word-break: break-word;
      overflow-wrap: break-word;
    }
    .card { 
      background:#fff; 
      border:1px solid var(--border); 
      border-radius:1rem; 
      padding:1.25rem 1.5rem; 
      box-shadow:var(--shadow-sm);
      width: 100%;
      max-width: 100%;
      overflow: hidden;
    }
    .card.accent { 
      border-left:6px solid var(--primary-purple); 
      background:linear-gradient(180deg, var(--purple-subtle), #fff); 
    }
    /* Global progress */
    .items-container { 
      background:#fff; 
      border:1px solid var(--border); 
      border-radius:1rem; 
      padding:1rem 1.25rem; 
      box-shadow:var(--shadow-sm);
      width: 100%;
      max-width: 100%;
      overflow: hidden;
    }
    .items-header { 
      display:flex; 
      align-items:center; 
      justify-content:space-between; 
      gap:1rem; 
      flex-wrap:wrap;
      width: 100%;
      max-width: 100%;
    }
    .items-title { 
      font-size:1.05rem; 
      font-weight:700; 
      word-break: break-word;
      overflow-wrap: break-word;
    }
    .progress-container { 
      flex:1; 
      max-width:280px;
      min-width: 150px;
    }
    .progress-bar { 
      height:.5rem; 
      background:var(--gray-200); 
      border-radius:.25rem; 
      overflow:hidden;
    }
    .progress-fill { 
      height:100%; 
      width:0%; 
      background:linear-gradient(90deg, var(--primary-purple), var(--secondary-purple)); 
      border-radius:.25rem; 
      transition: width .3s ease; 
    }
    /* ---------- QUESTIONS (keep your style) ---------- */
    .q-list { 
      margin-top: 1rem;
      width: 100%;
      max-width: 100%;
    }
    /* Visibility guard — DO NOT show unless active */
    .q-card { 
      display: none !important;
      width: 100%;
      max-width: 100%;
    }
    /* The actual card styles (separate to avoid overriding display) */
    .q-card {
      background:#fff;
      border:1px solid var(--border);
      border-radius:1rem;
      box-shadow:var(--shadow-md);
      padding: clamp(1rem, 2.5vw, 2rem);
      min-height: clamp(420px, 65vh, 760px);
      display: grid; /* layout model only; overridden by !important 'display:none' above */
      grid-template-rows: auto auto 1fr;  /* head, prompt/code, options */
      gap: clamp(.75rem, 1.2vw, 1.25rem);
      width: 100%;
      max-width: 100%;
      overflow: hidden;
    }
    .q-card.active { 
      display: grid !important; 
      animation: slideIn .28s ease both; 
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(12px); }
      to   { opacity: 1; transform: translateX(0); }
    }
    .q-head { 
      display:flex; 
      align-items:center; 
      gap:.75rem; 
      margin:0 0 .25rem 0;
      flex-wrap: wrap;
    }
    .q-num {
      width:2rem; height:2rem; border-radius:.5rem;
      display:flex; align-items:center; justify-content:center;
      font-weight:800; color:#fff;
      background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));
      flex-shrink:0;
    }
    .q-title {
      font-weight:800; color:var(--text-primary);
      font-size: clamp(1rem, 1.6vw, 1.15rem);
      letter-spacing:.2px;
      word-break: break-word;
      overflow-wrap: break-word;
    }
.q-text {
  font-size: clamp(1.05rem, 1.8vw, 1.25rem);
  line-height: 1.55;
  color: var(--text-primary);
  word-break: break-word;
  overflow-wrap: break-word;
  white-space: pre-wrap; /* Add this line */
}
    .q-code {
      background:#0f172a; color:#e2e8f0;
      border:1px solid rgba(255,255,255,.08);
      border-radius:.75rem;
      padding: .75rem .9rem;
      margin-top:.5rem;
      white-space:pre-wrap;
      font-family: ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;
      font-size: .92rem;
      width: 100%;
      max-width: 100%;
      overflow-x: auto;
    }
    .options {
      margin-top: .35rem;
      display:grid;
      grid-template-columns: repeat(1, minmax(0,1fr));
      gap: .85rem;
      width: 100%;
      max-width: 100%;
    }
    .opt {
      position:relative;
      display:flex; align-items:center; gap:.8rem;
      padding: clamp(.85rem, 2vw, 1.1rem) clamp(1rem, 2.4vw, 1.25rem);
      background: var(--gray-50);
      border:1px solid var(--border);
      border-radius: .9rem;
      cursor:pointer;
      transition: transform .14s ease, box-shadow .14s ease, border-color .14s ease, background .14s ease;
      width: 100%;
      max-width: 100%;
    }
    .opt:hover { 
      background:#fff; 
      border-color:var(--primary-purple); 
      box-shadow:var(--shadow); 
      transform: translateY(-1px); 
    }
    .opt input[type="radio"]{ 
      width:20px; 
      height:20px; 
      accent-color: var(--primary-purple); 
      flex-shrink:0; 
    }
    .opt span { 
      color:var(--text-primary); 
      font-weight:700; 
      font-size: clamp(.98rem, 1.5vw, 1.05rem); 
      line-height:1.35;
      word-break: break-word;
      overflow-wrap: break-word;
    }
    .opt .opt-badge {
      width: 28px; height: 28px; border-radius: .65rem;
      display:flex; align-items:center; justify-content:center;
      font-weight:800; color:#fff; flex-shrink:0;
      background: linear-gradient(135deg,var(--primary-purple),var(--secondary-purple));
      box-shadow: var(--shadow-sm);
    }
    .opt:has(input[type="radio"]:checked){
      border-color:var(--primary-purple);
      background: var(--purple-subtle);
      box-shadow: 0 0 0 3px rgba(124,58,237,.12) inset;
    }
    .q-card {
      /* Add this to your existing .q-card styles */
      scroll-margin-top: 120px; /* Adjust this value as needed */
    }
    .q-error {
      margin-top:.65rem;
      background:var(--danger-light);
      border:1px solid var(--danger);
      color:#7f1d1d;
      border-radius:.75rem;
      padding:.6rem .8rem;
      font-weight:700;
      width: 100%;
      max-width: 100%;
    }
    .nav-controls {
      display:flex; align-items:center; justify-content:space-between; gap:.75rem; margin:1rem 0;
      flex-wrap:wrap;
      width: 100%;
      max-width: 100%;
    }
    .nav-left, .nav-right { 
      display:flex; 
      align-items:center; 
      gap:.75rem; 
      flex-wrap:wrap; 
    }
    .counter-pill {
      padding:.4rem .75rem; 
      border:1px solid var(--border); 
      border-radius:999px; 
      background:#fff; 
      box-shadow:var(--shadow-sm);
      font-weight:700; 
      color:var(--text-secondary);
    }
    .btn { 
      display:inline-flex; 
      align-items:center; 
      gap:.5rem; 
      padding:.75rem 1.5rem; 
      border:none; 
      border-radius:.75rem; 
      font-weight:700; 
      font-size:.9rem; 
      cursor:pointer; 
      transition:all .18s ease; 
      text-decoration:none; 
      white-space: nowrap;
    }
    .btn-primary { 
      background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); 
      color:#fff; 
      box-shadow:var(--shadow); 
    }
    .btn-primary:hover { 
      transform:translateY(-2px); 
      box-shadow:var(--shadow-lg); 
    }
    .btn-ghost { 
      background:transparent; 
      color:var(--text-secondary); 
      border:1px solid var(--border); 
    }
    .btn-ghost:hover { 
      background:var(--gray-50); 
      border-color:var(--primary-purple); 
      color:var(--primary-purple); 
    }
    .btn[disabled] { 
      opacity:.6; 
      cursor:not-allowed; 
    }

    /* ==============================
     RESULTS SECTION
     ============================== */
    .results-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(8px);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    .results-overlay.show {
      opacity: 1;
      visibility: visible;
    }
    .results-modal {
      background: white;
      border-radius: 1.5rem;
      padding: 3rem;
      max-width: 600px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: var(--shadow-lg);
      text-align: center;
      transform: scale(0.9) translateY(20px);
      transition: transform 0.3s ease;
    }
    .results-overlay.show .results-modal {
      transform: scale(1) translateY(0);
    }
    .results-icon {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 2rem;
      font-size: 3rem;
    }
    .results-icon.success {
      background: linear-gradient(135deg, var(--success), #34d399);
      color: white;
    }
    .results-icon.warning {
      background: linear-gradient(135deg, var(--warning), #fbbf24);
      color: white;
    }
    .results-icon.danger {
      background: linear-gradient(135deg, var(--danger), #f87171);
      color: white;
    }
    .results-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: var(--text-primary);
    }
    .results-score {
      font-size: 4rem;
      font-weight: 900;
      margin: 1rem 0;
      color: var(--primary-purple);
    }
    .results-message {
      font-size: 1.25rem;
      line-height: 1.6;
      color: var(--text-secondary);
      margin-bottom: 2rem;
    }
    .results-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }
    .btn-large {
      padding: 1rem 2rem;
      font-size: 1.1rem;
      border-radius: 1rem;
    }

    .toast-container { 
      position:fixed; 
      top:1rem; 
      right:1rem; 
      display:flex; 
      flex-direction:column; 
      gap:.5rem; 
      z-index:1000;
      max-width: calc(100vw - 2rem);
    }
    .toast { 
      background:#fff; 
      border:1px solid var(--border); 
      color:var(--text-primary); 
      padding:1rem 1.25rem; 
      border-radius:.75rem; 
      font-weight:600; 
      min-width:280px; 
      max-width: 100%;
      box-shadow:var(--shadow-lg); 
      animation:slideIn .25s ease;
      word-break: break-word;
      overflow-wrap: break-word;
    }
    .toast.warn { 
      border-left:4px solid var(--warning); 
      background:linear-gradient(135deg,var(--warning-light), #fff); 
    }
    .toast.err  { 
      border-left:4px solid var(--danger);  
      background:linear-gradient(135deg,var(--danger-light),  #fff); 
    }
    .d-none { display: none !important; }
    @keyframes slideIn{ 
      from{opacity:0; transform:translateX(100%)} 
      to{opacity:1; transform:translateX(0)} 
    }
    @media (max-width:768px){
      .header-container{flex-direction:column; align-items:stretch; gap:1rem; padding:1rem;}
      .q-card { min-height: 70vh; padding: 1rem; }
      .options { grid-template-columns:1fr; }
      .opt { padding:.85rem 1rem; }
      .stats-grid { grid-template-columns: 1fr; }
      .progress-container { max-width: 100%; }
      .results-modal {
        padding: 2rem;
        width: 95%;
      }
      .results-score {
        font-size: 3rem;
      }
      .results-title {
        font-size: 2rem;
      }
      .results-actions {
        flex-direction: column;
      }
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
        <div id="instruxBody" style="white-space:pre-wrap; word-break: break-word; overflow-wrap: break-word;">{!! nl2br(e($instructions)) !!}</div>
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
          @php $letters = range('A','Z'); @endphp
          @foreach($questions as $i => $q)
            @php
              $opts = is_array($q['options'] ?? null) ? $q['options'] : [];
              $oldValue = old("answers.$i");
            @endphp
            <!-- FIRST card starts visible to avoid FOUC -->
            <div class="q-card {{ $i === 0 ? 'active' : '' }}" id="qcard-{{ $i }}" {{ $i === 0 ? '' : 'hidden' }}>
              <div class="q-head">
                <div class="q-num">{{ $i + 1 }}</div>
                <div class="q-title">Question {{ $i + 1 }} of {{ $total }}</div>
              </div>
              <div>
                <div class="q-text">{{ $q['prompt'] ?? 'Question' }}</div>
                @if(!empty($q['code']))
                  <pre class="q-code"><code>{{ $q['code'] }}</code></pre>
                @endif
              </div>
              <div class="options" role="group" aria-labelledby="q-{{ $i }}-label">
                @forelse($opts as $k => $opt)
                  <label class="opt">
                    <span class="opt-badge">{{ $letters[$k] ?? '?' }}</span>
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
                <div class="q-error">
                  <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                </div>
              @enderror
            </div>
          @endforeach
        </div>
        <!-- ONE-BY-ONE NAV -->
        <div class="nav-controls">
          <div class="nav-left">
            <span class="counter-pill" id="qCounter">Question 1 of {{ $total }}</span>
          </div>
          <div class="nav-right">
            <button type="button" class="btn btn-ghost" id="prevBtn">
              <i class="fas fa-arrow-left"></i> Previous
            </button>
            <button type="button" class="btn btn-primary" id="nextBtn">
              Next <i class="fas fa-arrow-right"></i>
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn" style="display:none;">
              <i class="fas fa-paper-plane"></i> Submit Answers
            </button>
            <button type="button" class="btn btn-ghost" id="scrollTopBtn">
              <i class="fas fa-arrow-up"></i> Back to Top
            </button>
          </div>
        </div>
      @endif
    </form>
  </div>

  <!-- RESULTS OVERLAY -->
  <div class="results-overlay" id="resultsOverlay">
    <div class="results-modal">
      <div class="results-icon" id="resultsIcon">
        <i class="fas fa-check" id="resultsIconSymbol"></i>
      </div>
      <h2 class="results-title" id="resultsTitle">Assessment Complete!</h2>
      <div class="results-score" id="resultsScore">0%</div>
      <p class="results-message" id="resultsMessage"></p>
      <div class="results-actions">
        <button type="button" class="btn btn-primary btn-large" id="proceedBtn">
          <i class="fas fa-arrow-right"></i> <span id="proceedBtnText">Continue</span>
        </button>
      </div>
    </div>
  </div>

  <!-- CSRF TOKEN for AJAX requests -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

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
      if ($progress) $progress.style.width = pct + '%';
      if ($answeredPct) $answeredPct.textContent = pct + '%';
      if ($answeredCount) $answeredCount.textContent = answered;
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
    
    // ===== One-by-one Navigator =====
    let currentIndex = 0;
    const $cards = Array.from(document.querySelectorAll('.q-card'));
    const $prev = document.getElementById('prevBtn');
    const $next = document.getElementById('nextBtn');
    const $submit = document.getElementById('submitBtn');
    const $counter = document.getElementById('qCounter');
    
    // Calculate header height for scroll offset - more precise calculation
    function getHeaderOffset() {
      const header = document.querySelector('.level-header');
      const progressBand = document.querySelector('.items-container');
      let offset = 10; // Minimal padding
      
      if (header) offset += header.offsetHeight;
      if (progressBand) offset += progressBand.offsetHeight;
      
      return offset;
    }
    
    function animateOnce(el, name='slideIn', dur=280){
      if (!el) return;
      el.style.animation = 'none';
      void el.offsetWidth; // reflow
      el.style.animation = `${name} ${dur}ms ease both`;
    }
    
    function showCard(i, opts = {scroll:true}) {
      currentIndex = Math.max(0, Math.min(TOTAL - 1, i));
      // Toggle visibility + hidden attr
      $cards.forEach((c, idx) => {
        const isActive = idx === currentIndex;
        c.classList.toggle('active', isActive);
        c.hidden = !isActive;
      });
      
      // Buttons state
      if ($prev) $prev.disabled = (currentIndex === 0);
      if ($next) $next.style.display = (currentIndex < TOTAL - 1) ? 'inline-flex' : 'none';
      if ($submit) $submit.style.display = (currentIndex === TOTAL - 1) ? 'inline-flex' : 'none';
      
      // Counter
      if ($counter) $counter.textContent = `Question ${currentIndex + 1} of ${TOTAL}`;
      
      // Progress UI
      updateProgress();
      
      // Focus first radio for accessibility
      const firstRadio = $cards[currentIndex].querySelector('input[type="radio"]');
      if (firstRadio) firstRadio.focus({preventScroll:true});
      
      // Animate current slide
      animateOnce($cards[currentIndex]);
      
      if (opts.scroll) {
        // Get the question card element
        const currentCard = $cards[currentIndex];
        
        // Calculate position with minimal offset
        const offset = getHeaderOffset();
        const cardTop = currentCard.getBoundingClientRect().top + window.pageYOffset;
        
        // Scroll to position with minimal offset
        window.scrollTo({
          top: cardTop - offset,
          behavior: 'smooth'
        });
      }
    }
    
    // Hook buttons
    if ($prev) $prev.addEventListener('click', () => showCard(currentIndex - 1));
    if ($next) $next.addEventListener('click', () => {
      const anyChecked = !!$cards[currentIndex].querySelector('input[type="radio"]:checked');
      if (!anyChecked) toast('Please select an answer before going next.', 'warn');
      else showCard(currentIndex + 1);
    });
    
    // Auto-advance when a choice is picked
    document.querySelectorAll('.opt input[type="radio"]').forEach(r => {
      r.addEventListener('change', () => {
        updateProgress();
        if (currentIndex < TOTAL - 1) {
          setTimeout(() => showCard(currentIndex + 1), 200);
        }
      });
    });
    
    // Submit gate — require all questions answered
    $form.addEventListener('submit', (e) => {
      const answered = document.querySelectorAll('.opt input[type="radio"]:checked').length;
      if (answered !== TOTAL) {
        e.preventDefault();
        toast('Please answer all questions before submitting.', 'warn');
        // Jump to first unanswered
        for (let i=0;i<TOTAL;i++){
          if (!document.querySelector(`input[name="answers[${i}]"]:checked`)) {
            showCard(i);
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
    
    // Initialize view
    showCard(0);
    
    // Optional: keyboard navigation (left/right)
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') { if ($prev && !$prev.disabled) $prev.click(); }
      if (e.key === 'ArrowRight') {
        if ($next && $next.style.display !== 'none') $next.click();
        else if ($submit && $submit.style.display !== 'none') $submit.click();
      }
    });
  })();
</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>