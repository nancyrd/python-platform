<x-app-layout>
@php
    // --- Normalize content & metadata ---
    $instructions = $level->instructions;
    $content = $level->content;
    if (is_string($content)) {
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) $content = $decoded;
    }
    if (empty($instructions) && is_array($content)) {
        $instructions = $content['instructions'] ?? ($content['intro'] ?? null);
    }
    // Build instruction steps (array of strings)
    $instructionSteps = [];
    if (is_array($instructions)) {
        $instructionSteps = array_values(array_filter(array_map(fn($s)=>is_string($s)?trim($s):'', $instructions)));
    } elseif (is_string($instructions)) {
        // split by: blank lines OR lines of --- OR headings
        $instructionSteps = array_values(array_filter(array_map('trim',
            preg_split('/(\R{2,}|^\s*[-]{3,}\s*$|^#+\s.*$)/m', $instructions)
        )));
        if (!$instructionSteps) $instructionSteps = [trim($instructions)];
    }
    // Examples (array of dicts)
    $examples = is_array($content) ? ($content['examples'] ?? []) : [];
    // If examples is a single dict, wrap it
    if ($examples && array_keys($examples) !== range(0, count($examples) - 1)) {
        $examples = [$examples];
    }
    $estimatedTime  = is_array($content) ? ($content['estimated_time'] ?? null) : null;
    $goals          = is_array($content) ? ($content['goals'] ?? []) : [];
    $prerequisites  = is_array($content) ? ($content['prerequisites'] ?? []) : [];
    // Optional single expected_output (fallback when example lacks its own)
    $globalExpected = is_array($content) ? ($content['expected_output'] ?? null) : null;
@endphp
<x-slot name="header">
  <div class="instructions-header">
    <div class="header-container">
      <div class="header-content">
        <div class="header-left">
          <div class="header-icon">
            <i class="fas fa-code"></i>
          </div>
          <div>
            <h1 class="header-title">{{ $level->title ?? 'Level Instructions' }}</h1>
            <p class="header-subtitle">Interactive Learning Environment</p>
          </div>
        </div>
        <a href="{{ route('stages.show', $level->stage_id) }}" class="btn-back">
          <i class="fas fa-arrow-left me-2"></i> Back to Stage
        </a>
      </div>
    </div>
  </div>
</x-slot>

<div class="instructions-page">
  <div class="page-container">
    {{-- At a glance --}}
    @if($estimatedTime || !empty($goals) || !empty($prerequisites))
    <div class="overview-section">
      <div class="overview-grid">
        <div class="overview-card">
          <h3 class="card-title">
            <i class="fas fa-info-circle me-2"></i> Overview
          </h3>
          <div class="overview-content">
            @if($estimatedTime)
            <div class="meta-item">
              <span class="meta-icon">⏱️</span>
              <span class="meta-text">Estimated time: {{ $estimatedTime }}</span>
            </div>
            @endif
            
            @if(!empty($goals))
            <div class="goals-section">
              <h4 class="section-heading">Learning Goals</h4>
              <ul class="goals-list">
                @foreach($goals as $goal)
                <li class="goal-item">{{ $goal }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            
            @if(!empty($prerequisites))
            <div class="prerequisites-section">
              <h4 class="section-heading">Prerequisites</h4>
              <ul class="prerequisites-list">
                @foreach($prerequisites as $prerequisite)
                <li class="prerequisite-item">{{ $prerequisite }}</li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
        </div>
        
        <div class="overview-card">
          <h3 class="card-title">
            <i class="fas fa-route me-2"></i> Learning Path
          </h3>
          <div class="learning-path">
            <ol class="path-steps">
              <li class="path-step">Read each instruction step</li>
              <li class="path-step">Try the code examples in the console</li>
              <li class="path-step">Check your understanding with exercises</li>
              <li class="path-step">Complete the level to earn stars</li>
            </ol>
            <div class="tip-box">
              <div class="tip-icon">
                <i class="fas fa-lightbulb"></i>
              </div>
              <div class="tip-content">
                <strong>Tip:</strong> Use the "Copy & Run" button on examples to quickly test code.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- Instructions: Step Viewer --}}
    @if(count($instructionSteps) > 0)
    <div class="instructions-section" id="instructionsSection" data-total="{{ count($instructionSteps) }}">
      <div class="section-header">
        <h2 class="section-title">
          <i class="fas fa-list-ol me-2"></i> Instructions
        </h2>
        <div class="step-indicator">
          <span class="current-step">1</span> of <span class="total-steps">{{ count($instructionSteps) }}</span>
        </div>
      </div>
      
      <div class="step-viewer" id="instrViewer">
        @foreach($instructionSteps as $i => $step)
        <div class="step-content" data-step="{{ $i }}" style="{{ $i === 0 ? '' : 'display:none' }}">
          <div class="step-text">{!! nl2br(e($step)) !!}</div>
        </div>
        @endforeach
        
        <div class="step-controls">
          <button class="btn-step btn-step-prev" id="instrPrev" disabled>
            <i class="fas fa-chevron-left me-1"></i> Previous
          </button>
          
          <div class="step-progress">
            @for($i=0;$i<count($instructionSteps);$i++)
            <div class="progress-dot {{ $i===0?'active' : '' }}" data-dot="{{ $i }}"></div>
            @endfor
          </div>
          
          <button class="btn-step btn-step-next" id="instrNext">
            {{ count($instructionSteps) > 1 ? 'Next <i class="fas fa-chevron-right ms-1"></i>' : 'Finish <i class="fas fa-check ms-1"></i>' }}
          </button>
        </div>
      </div>
    </div>
    @endif

    {{-- Python console with Example stepper --}}
    <div class="console-section" id="consoleBox" data-example-count="{{ count($examples) }}">
      <div class="console-header">
        <div class="console-title">
          <i class="fas fa-terminal me-2"></i> Python Console
          <span class="console-status" id="status">(loading…)</span>
        </div>
        <div class="console-actions">
          <button class="console-btn" id="btnCopyMain" title="Copy code">
            <i class="fas fa-copy"></i>
          </button>
          <button class="console-btn" id="btnRun" title="Run code">
            <i class="fas fa-play"></i>
          </button>
          <button class="console-btn" id="btnCheck" title="Check answer">
            <i class="fas fa-check"></i>
          </button>
          <button class="console-btn" id="btnClear" title="Clear console">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
      
      {{-- Example metadata (title / explain) --}}
      <div class="example-nav" id="consoleNav" style="{{ count($examples) ? '' : 'display:none' }}">
        <div class="example-info">
          <span class="example-badge" id="exIndexBadge">Example 1 / {{ max(1,count($examples)) }}</span>
          <h3 class="example-title" id="exTitle"></h3>
        </div>
        <div class="example-controls">
          <button class="btn-example btn-example-prev" id="exPrev" {{ count($examples) > 1 ? '' : 'disabled' }}>
            <i class="fas fa-chevron-left me-1"></i> Previous
          </button>
          <button class="btn-example btn-example-next" id="exNext" {{ count($examples) > 1 ? '' : 'disabled' }}>
            Next <i class="fas fa-chevron-right ms-1"></i>
          </button>
        </div>
      </div>
      
      <div class="example-explanation" id="exExplainWrap" style="display:none">
        <div id="exExplain"></div>
      </div>
      
      <div class="console-body">
        <div class="code-editor">
          <textarea id="code" placeholder='# Type your Python code here
print("Hello, World!")'></textarea>
          <div class="editor-actions">
            <button class="editor-btn" id="btnPasteFromClipboard" title="Paste from clipboard">
              <i class="fas fa-paste"></i>
            </button>
            <button class="editor-btn" id="btnCopyToClipboard" title="Copy to clipboard">
              <i class="fas fa-copy"></i>
            </button>
          </div>
        </div>
        
        <div class="console-output" id="output">
          Type code above and click "Run".
        </div>
      </div>
      
      {{-- hidden example data to hydrate JS --}}
      <div id="examplesData" style="display:none">
        @foreach($examples as $i => $ex)
        @php
          $title   = trim($ex['title'] ?? ('Example '.($i+1)));
          $code    = (string)($ex['code'] ?? '');
          $explain = (string)($ex['explain'] ?? '');
          $expect  = (string)($ex['expected_output'] ?? ($globalExpected ?? ''));
        @endphp
        <div class="ex-row"
             data-title="{{ e($title) }}"
             data-code="{{ base64_encode($code) }}"
             data-explain="{{ e($explain) }}"
             data-expected="{{ e($expect) }}"></div>
        @endforeach
      </div>
    </div>
    
    {{-- Expected output section --}}
    <div class="expected-section" id="challenge" data-expected="{{ $globalExpected ?? '' }}">
      <h3 class="section-title">
        <i class="fas fa-flag-checkered me-2"></i> Challenge
      </h3>
      @if($globalExpected)
      <div class="expected-output">
        <p>Your code should produce this output:</p>
        <div class="output-preview">
          <code>{{ $globalExpected }}</code>
        </div>
      </div>
      @else
      <div class="challenge-description">
        <p>Complete the exercises and test your understanding in the console above.</p>
      </div>
      @endif
    </div>
    
    {{-- Action buttons --}}
    <div class="action-section">
      <div class="action-buttons">
        <a href="{{ route('stages.show', $level->stage_id) }}" class="btn-action btn-secondary">
          <i class="fas fa-arrow-left me-2"></i> Back to Stage
        </a>
        <a href="{{ route('levels.show', $level) }}" class="btn-action btn-primary">
          Start Level <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </div>
</div>

{{-- Pyodide (client-side Python) --}}
<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
<script>
  let pyodide, pyReady = false;
  async function bootPython() {
    try {
      pyodide = await loadPyodide({
        stdout: s => appendOut(s + "\n"),
        stderr: s => appendErr(s + "\n")
      });
      await pyodide.runPythonAsync(`
import builtins
try:
    from js import prompt as __prompt
    builtins.input = lambda p='': __prompt(p)
except Exception:
    pass
      `);
      pyReady = true;
      setStatus('ready');
    } catch (e) {
      setStatus('failed');
      appendErr("Pyodide failed to load: " + (e.message || e));
    }
  }
  function setStatus(s){ const el = document.getElementById('status'); if (el) el.textContent = '('+s+')'; }
  function clearOut(){ const el = document.getElementById('output'); if (el) el.textContent = ''; }
  function appendOut(s){ const el = document.getElementById('output'); if (!el) return; const span = document.createElement('span'); span.className='ok'; span.textContent=s; el.appendChild(span); el.scrollTop=el.scrollHeight; }
  function appendErr(s){ const el = document.getElementById('output'); if (!el) return; const span = document.createElement('span'); span.className='err'; span.textContent=s; el.appendChild(span); el.scrollTop=el.scrollHeight; }
  async function runCode(code){
    clearOut();
    if(!pyReady){ appendErr("Python runtime is not ready yet."); return { out:'', err:'not-ready' }; }
    try {
      pyodide.globals.set("USER_CODE", code);
      await pyodide.runPythonAsync(`
import sys, io
_out = io.StringIO(); _err = io.StringIO()
__so, __se = sys.stdout, sys.stderr
sys.stdout, sys.stderr = _out, _err
ns = {}
try:
    exec(USER_CODE, ns, ns)
except Exception:
    import traceback; traceback.print_exc()
finally:
    sys.stdout, sys.stderr = __so, __se
OUT = _out.getvalue(); ERR = _err.getvalue()
      `);
      const OUT = pyodide.globals.get('OUT') || '';
      const ERR = pyodide.globals.get('ERR') || '';
      if (OUT) appendOut(OUT);
      if (ERR) appendErr(ERR);
      return { out: OUT, err: ERR };
    } catch(e) {
      appendErr("Error: " + (e.message || e));
      return { out:'', err: e.message || String(e) };
    }
  }
  // ===== UI logic (Instructions + Examples stepper + console) =====
  document.addEventListener('DOMContentLoaded', () => {
    bootPython();
    // ---- Instruction Stepper ----
    const instrSection = document.getElementById('instructionsSection');
    if (instrSection) {
      const total = parseInt(instrSection.dataset.total || '0', 10);
      let idx = 0;
      const steps = Array.from(instrSection.querySelectorAll('.step-content'));
      const dots  = Array.from(document.querySelectorAll('.progress-dot'));
      const prev  = document.getElementById('instrPrev');
      const next  = document.getElementById('instrNext');
      const currentStepEl = document.querySelector('.current-step');
      
      function renderInstr(){
        steps.forEach((el,i)=> el.style.display = (i===idx?'block':'none'));
        dots.forEach((d,i)=> d.classList.toggle('active', i===idx));
        prev.disabled = idx===0;
        next.innerHTML = (idx===total-1) ? 'Finish <i class="fas fa-check ms-1"></i>' : 'Next <i class="fas fa-chevron-right ms-1"></i>';
        if (currentStepEl) currentStepEl.textContent = idx + 1;
      }
      prev?.addEventListener('click', e=>{ e.preventDefault(); if(idx>0){ idx--; renderInstr(); } });
      next?.addEventListener('click', e=>{ e.preventDefault(); if(idx<total-1){ idx++; renderInstr(); } else { /* finished */ } });
      // Allow clicking dots
      dots.forEach((d,i)=> d.addEventListener('click', ()=>{ idx=i; renderInstr(); }));
      renderInstr();
    }
    // ---- Examples/Console Stepper ----
    const exRows = Array.from(document.querySelectorAll('#examplesData .ex-row'));
    let exIdx = 0;
    const exPrev = document.getElementById('exPrev');
    const exNext = document.getElementById('exNext');
    const exTitle = document.getElementById('exTitle');
    const exExplainWrap = document.getElementById('exExplainWrap');
    const exExplain = document.getElementById('exExplain');
    const exIndexBadge = document.getElementById('exIndexBadge');
    const codeTA = document.getElementById('code');
    const challenge = document.getElementById('challenge');
    function loadExample(i){
      if (!exRows.length) return;
      exIdx = Math.max(0, Math.min(i, exRows.length-1));
      const row = exRows[exIdx];
      const title = row.dataset.title || ('Example ' + (exIdx+1));
      const explain = row.dataset.explain || '';
      const b64 = row.dataset.code || '';
      const expected = (row.dataset.expected || '').trim();
      if (exTitle) exTitle.textContent = title;
      if (exExplainWrap) exExplainWrap.style.display = explain ? '' : 'none';
      if (exExplain) exExplain.textContent = explain;
      if (exIndexBadge) exIndexBadge.textContent = `Example ${exIdx+1} / ${exRows.length}`;
      if (codeTA) codeTA.value = b64 ? atob(b64) : '';
      if (challenge) challenge.dataset.expected = expected;
      // nav enable/disable
      if (exPrev) exPrev.disabled = (exIdx===0);
      if (exNext) exNext.disabled = (exIdx===exRows.length-1);
      // reset output text
      const out = document.getElementById('output'); if (out) out.textContent = ' Code loaded. Click "Run" or edit it.';
    }
    if (exRows.length) loadExample(0);
    exPrev?.addEventListener('click', e=>{ e.preventDefault(); loadExample(exIdx-1); });
    exNext?.addEventListener('click', e=>{ e.preventDefault(); loadExample(exIdx+1); });
    // Keyboard: Ctrl/Cmd + Enter to Run
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('btnRun')?.click();
      }
    });
    // Delegated clicks for console controls
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('button');
      if (!btn) return;
      if (btn.id === 'btnRun') {
        e.preventDefault(); btn.disabled = true; setStatus('running');
        const { err } = await runCode(codeTA.value);
        setStatus(err ? 'error' : 'ready'); btn.disabled = false; return;
      }
      if (btn.id === 'btnCheck') {
        e.preventDefault();
        const expected = (challenge?.dataset.expected || '').trim();
        btn.disabled = true; setStatus('checking');
        const { out } = await runCode(codeTA.value);
        const clean = (out || '').trim();
        if (expected) {
          if (clean === expected) appendOut("\n✅ Correct! Great job!");
          else appendErr(`\n❌ Not quite. Expected: "${expected}"\nYour output: "${clean}"`);
        } else {
          appendOut("\n✓ Code executed successfully!");
        }
        setStatus('ready'); btn.disabled = false; return;
      }
      if (btn.id === 'btnClear') { e.preventDefault(); codeTA.value=''; document.getElementById('output').textContent=' Ready.'; return; }
      if (btn.id === 'btnCopyMain' || btn.id === 'btnCopyToClipboard') {
        e.preventDefault();
        try { await navigator.clipboard.writeText(codeTA.value); appendOut("\n📋 Code copied."); }
        catch { appendErr("\n⚠️ Clipboard permission blocked."); }
        return;
      }
      if (btn.id === 'btnPasteFromClipboard') {
        e.preventDefault();
        try { const t = await navigator.clipboard.readText(); codeTA.value=t; document.getElementById('output').textContent=' Pasted. Run it!'; }
        catch { appendErr("\n⚠️ Clipboard read blocked by browser."); }
        return;
      }
    });
  });
</script>

<style>
  :root {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --secondary: #8b5cf6;
    --accent: #7c3aed;
    --light: #f8fafc;
    --dark: #1e293b;
    --gray: #64748b;
    --light-gray: #f1f5f9;
    --border: #e2e8f0;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --success: #10b981;
    --success-bg: #d1fae5;
    --success-text: #065f46;
    --error: #ef4444;
    --error-bg: #fee2e2;
    --error-text: #b91c1c;
  }
  
  * {
    box-sizing: border-box;
  }
  
  body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background-color: var(--light);
    color: var(--dark);
    line-height: 1.6;
  }
  
  /* Header Styles */
  .instructions-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    box-shadow: var(--shadow);
  }
  
  .header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 0;
  }
  
  .header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .header-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .header-icon i {
    font-size: 24px;
    color: white;
  }
  
  .header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
  }
  
  .header-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
  }
  
  .btn-back {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: background 0.2s;
  }
  
  .btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
  }
  
  /* Page Layout */
  .instructions-page {
    width: 100%;
    min-height: calc(100vh - 80px);
  }
  
  .page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  /* Overview Section */
  .overview-section {
    margin-bottom: 2rem;
  }
  
  .overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
  }
  
  .overview-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
  }
  
  .card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
    color: var(--primary-dark);
    display: flex;
    align-items: center;
  }
  
  .meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }
  
  .meta-icon {
    font-size: 1.25rem;
  }
  
  .meta-text {
    font-weight: 500;
  }
  
  .section-heading {
    font-size: 1rem;
    font-weight: 700;
    margin: 1rem 0 0.5rem;
    color: var(--dark);
  }
  
  .goals-list, .prerequisites-list {
    margin: 0;
    padding-left: 1.5rem;
  }
  
  .goal-item, .prerequisite-item {
    margin-bottom: 0.5rem;
  }
  
  .learning-path {
    margin-top: 1rem;
  }
  
  .path-steps {
    margin: 0;
    padding-left: 1.5rem;
  }
  
  .path-step {
    margin-bottom: 0.5rem;
  }
  
  .tip-box {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-top: 1rem;
    padding: 1rem;
    background: var(--light-gray);
    border-radius: 8px;
    border-left: 4px solid var(--accent);
  }
  
  .tip-icon {
    color: var(--accent);
    font-size: 1.25rem;
  }
  
  .tip-content {
    flex: 1;
  }
  
  .tip-content strong {
    color: var(--accent);
  }
  
  /* Instructions Section */
  .instructions-section {
    margin-bottom: 2rem;
  }
  
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  
  .section-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: var(--dark);
    display: flex;
    align-items: center;
  }
  
  .step-indicator {
    font-size: 0.875rem;
    color: var(--gray);
  }
  
  .current-step {
    font-weight: 700;
    color: var(--primary);
  }
  
  .step-viewer {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
  }
  
  .step-content {
    padding: 1.5rem;
    min-height: 150px;
  }
  
  .step-text {
    line-height: 1.7;
    color: var(--dark);
  }
  
  .step-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background: var(--light-gray);
    border-top: 1px solid var(--border);
  }
  
  .btn-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .btn-step:hover:not(:disabled) {
    background: var(--light-gray);
  }
  
  .btn-step:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .step-progress {
    display: flex;
    gap: 0.5rem;
  }
  
  .progress-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--border);
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .progress-dot.active {
    background: var(--primary);
  }
  
  /* Console Section */
  .console-section {
    margin-bottom: 2rem;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
  }
  
  .console-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--light-gray);
    border-bottom: 1px solid var(--border);
  }
  
  .console-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
  }
  
  .console-status {
    font-size: 0.875rem;
    color: var(--gray);
  }
  
  .console-actions {
    display: flex;
    gap: 0.5rem;
  }
  
  .console-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: white;
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .console-btn:hover {
    background: var(--light-gray);
  }
  
  /* Example Navigation */
  .example-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
  }
  
  .example-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .example-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--light-gray);
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    width: fit-content;
  }
  
  .example-title {
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
  }
  
  .example-controls {
    display: flex;
    gap: 0.5rem;
  }
  
  .btn-example {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .btn-example:hover:not(:disabled) {
    background: var(--light-gray);
  }
  
  .btn-example:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .example-explanation {
    padding: 1rem 1.5rem;
    background: var(--light-gray);
    border-bottom: 1px solid var(--border);
  }
  
  /* Console Body */
  .console-body {
    display: flex;
    flex-direction: column;
  }
  
  .code-editor {
    position: relative;
  }
  
  #code {
    width: 100%;
    min-height: 200px;
    padding: 1rem;
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 0.95rem;
    line-height: 1.5;
    border: none;
    resize: vertical;
    background: #f8f9fa;
    color: #333;
  }
  
  #code:focus {
    outline: none;
  }
  
  .editor-actions {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    gap: 0.5rem;
  }
  
  .editor-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: white;
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: var(--shadow);
  }
  
  .editor-btn:hover {
    background: var(--light-gray);
  }
  
  .console-output {
    padding: 1rem;
    background: #1e293b;
    color: #e2e8f0;
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 0.95rem;
    line-height: 1.5;
    min-height: 100px;
    max-height: 300px;
    overflow-y: auto;
    white-space: pre-wrap;
  }
  
  .console-output .ok {
    color: #a7f3d0;
  }
  
  .console-output .err {
    color: #fecaca;
  }
  
  /* Expected Section */
  .expected-section {
    margin-bottom: 2rem;
  }
  
  .expected-output {
    padding: 1rem;
    background: var(--light-gray);
    border-radius: 8px;
    border-left: 4px solid var(--success);
  }
  
  .output-preview {
    margin-top: 0.75rem;
    padding: 0.75rem;
    background: white;
    border-radius: 6px;
    border: 1px solid var(--border);
  }
  
  .output-preview code {
    display: block;
    font-family: 'Consolas', 'Monaco', monospace;
  }
  
  .challenge-description {
    padding: 1rem;
    background: var(--light-gray);
    border-radius: 8px;
    border-left: 4px solid var(--primary);
  }
  
  /* Action Section */
  .action-section {
    margin-bottom: 2rem;
  }
  
  .action-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
  }
  
  .btn-action {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
  }
  
  .btn-primary {
    background: var(--primary);
    color: white;
    border: none;
  }
  
  .btn-primary:hover {
    background: var(--primary-dark);
    color: white;
  }
  
  .btn-secondary {
    background: white;
    color: var(--dark);
    border: 1px solid var(--border);
  }
  
  .btn-secondary:hover {
    background: var(--light-gray);
    color: var(--dark);
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .header-content {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .page-container {
      padding: 1rem;
    }
    
    .overview-grid {
      grid-template-columns: 1fr;
    }
    
    .section-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.5rem;
    }
    
    .step-controls {
      flex-direction: column;
      gap: 1rem;
    }
    
    .console-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .example-nav {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .action-buttons {
      flex-direction: column;
    }
  }
</style>
</x-app-layout>