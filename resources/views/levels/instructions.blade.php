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
</x-app-layout><x-app-layout>
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
    <div class="header-background">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
      </div>
    </div>
    <div class="header-container">
      <div class="header-content">
        <div class="header-left">
          <div class="header-icon">
            <div class="icon-glow"></div>
            <i class="fas fa-rocket"></i>
          </div>
          <div class="header-text">
            <h1 class="header-title">{{ $level->title ?? 'Level Instructions' }}</h1>
            <p class="header-subtitle">🚀 Interactive Learning Journey</p>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 25%"></div>
            </div>
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
    
    {{-- Motivational Banner --}}
    <div class="motivation-banner">
      <div class="motivation-content">
        <div class="motivation-icon">
          <i class="fas fa-star"></i>
        </div>
        <div class="motivation-text">
          <h3>Ready to Level Up? 🎯</h3>
          <p>Every expert was once a beginner. Let's build something amazing together!</p>
        </div>
        <div class="achievement-badges">
          <div class="badge badge-start">
            <i class="fas fa-play"></i>
            <span>Start</span>
          </div>
          <div class="badge badge-code">
            <i class="fas fa-code"></i>
            <span>Code</span>
          </div>
          <div class="badge badge-complete">
            <i class="fas fa-trophy"></i>
            <span>Master</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Enhanced Overview --}}
    @if($estimatedTime || !empty($goals) || !empty($prerequisites))
    <div class="overview-section">
      <div class="section-header-enhanced">
        <h2 class="section-title-main">
          <span class="title-icon">📋</span>
          <span>Quick Overview</span>
        </h2>
        <div class="section-decoration"></div>
      </div>
      
      <div class="overview-grid">
        <div class="overview-card card-primary">
          <div class="card-header">
            <div class="card-icon">
              <i class="fas fa-info-circle"></i>
            </div>
            <h3 class="card-title">At a Glance</h3>
          </div>
          <div class="overview-content">
            @if($estimatedTime)
            <div class="meta-item">
              <span class="meta-icon">⏱️</span>
              <div class="meta-content">
                <span class="meta-label">Estimated Time</span>
                <span class="meta-value">{{ $estimatedTime }}</span>
              </div>
            </div>
            @endif
            
            @if(!empty($goals))
            <div class="goals-section">
              <h4 class="section-heading">🎯 Learning Goals</h4>
              <ul class="enhanced-list goals-list">
                @foreach($goals as $goal)
                <li class="goal-item">
                  <span class="list-bullet">✨</span>
                  <span>{{ $goal }}</span>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
            
            @if(!empty($prerequisites))
            <div class="prerequisites-section">
              <h4 class="section-heading">📚 Prerequisites</h4>
              <ul class="enhanced-list prerequisites-list">
                @foreach($prerequisites as $prerequisite)
                <li class="prerequisite-item">
                  <span class="list-bullet">📖</span>
                  <span>{{ $prerequisite }}</span>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
        </div>
        
        <div class="overview-card card-secondary">
          <div class="card-header">
            <div class="card-icon">
              <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3 class="card-title">Your Learning Path</h3>
          </div>
          <div class="learning-path">
            <ol class="path-steps">
              <li class="path-step">
                <span class="step-number">1</span>
                <div class="step-content">
                  <span class="step-title">Read & Understand</span>
                  <span class="step-desc">Follow each instruction step</span>
                </div>
              </li>
              <li class="path-step">
                <span class="step-number">2</span>
                <div class="step-content">
                  <span class="step-title">Practice & Experiment</span>
                  <span class="step-desc">Try code examples in the console</span>
                </div>
              </li>
              <li class="path-step">
                <span class="step-number">3</span>
                <div class="step-content">
                  <span class="step-title">Test & Validate</span>
                  <span class="step-desc">Check your understanding</span>
                </div>
              </li>
              <li class="path-step">
                <span class="step-number">4</span>
                <div class="step-content">
                  <span class="step-title">Complete & Celebrate</span>
                  <span class="step-desc">Earn stars and level up!</span>
                </div>
              </li>
            </ol>
            
            <div class="tip-box">
              <div class="tip-icon">
                <i class="fas fa-lightbulb"></i>
              </div>
              <div class="tip-content">
                <strong>💡 Pro Tip:</strong> Use <kbd>Ctrl</kbd> + <kbd>Enter</kbd> to quickly run your code!
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- Enhanced Instructions Section --}}
    @if(count($instructionSteps) > 0)
    <div class="instructions-section" id="instructionsSection" data-total="{{ count($instructionSteps) }}">
      <div class="section-header-enhanced">
        <h2 class="section-title-main">
          <span class="title-icon">📝</span>
          <span>Step-by-Step Instructions</span>
        </h2>
        <div class="step-indicator">
          <span class="current-step">1</span> of <span class="total-steps">{{ count($instructionSteps) }}</span>
        </div>
      </div>
      
      <div class="step-viewer">
        <div class="step-header">
          <div class="step-progress-bar">
            <div class="step-progress-fill" id="stepProgressFill" style="width: {{ 100 / count($instructionSteps) }}%"></div>
          </div>
        </div>
        
        @foreach($instructionSteps as $i => $step)
        <div class="step-content" data-step="{{ $i }}" style="{{ $i === 0 ? '' : 'display:none' }}">
          <div class="step-badge">Step {{ $i + 1 }}</div>
          <div class="step-text">{!! nl2br(e($step)) !!}</div>
        </div>
        @endforeach
        
        <div class="step-controls">
          <button class="btn-step btn-step-prev" id="instrPrev" disabled>
            <i class="fas fa-chevron-left me-1"></i> Previous
          </button>
          
          <div class="step-progress">
            @for($i=0;$i<count($instructionSteps);$i++)
            <div class="progress-dot {{ $i===0?'active' : '' }}" data-dot="{{ $i }}">
              <span class="dot-number">{{ $i + 1 }}</span>
            </div>
            @endfor
          </div>
          
          <button class="btn-step btn-step-next" id="instrNext">
            {{ count($instructionSteps) > 1 ? 'Next <i class="fas fa-chevron-right ms-1"></i>' : 'Complete <i class="fas fa-check ms-1"></i>' }}
          </button>
        </div>
      </div>
    </div>
    @endif

    {{-- Enhanced Console Section --}}
    <div class="console-section" id="consoleBox" data-example-count="{{ count($examples) }}">
      <div class="console-header">
        <div class="console-title">
          <div class="console-icon">
            <i class="fas fa-terminal"></i>
          </div>
          <div class="console-text">
            <h3>Python Playground</h3>
            <span class="console-status" id="status">(loading…)</span>
          </div>
        </div>
        <div class="console-actions">
          <button class="console-btn btn-copy" id="btnCopyMain" title="Copy code">
            <i class="fas fa-copy"></i>
          </button>
          <button class="console-btn btn-run" id="btnRun" title="Run code">
            <i class="fas fa-play"></i>
          </button>
          <button class="console-btn btn-check" id="btnCheck" title="Check answer">
            <i class="fas fa-check"></i>
          </button>
          <button class="console-btn btn-clear" id="btnClear" title="Clear console">
            <i class="fas fa-broom"></i>
          </button>
        </div>
      </div>
      
      {{-- Enhanced Example Navigation --}}
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
        <div class="explanation-icon">
          <i class="fas fa-info-circle"></i>
        </div>
        <div id="exExplain"></div>
      </div>
      
      <div class="console-body">
        <div class="code-editor">
          <div class="editor-header">
            <div class="editor-tabs">
              <div class="editor-tab active">
                <i class="fab fa-python"></i>
                <span>main.py</span>
              </div>
            </div>
            <div class="editor-actions">
              <button class="editor-btn" id="btnPasteFromClipboard" title="Paste from clipboard">
                <i class="fas fa-paste"></i>
              </button>
              <button class="editor-btn" id="btnCopyToClipboard" title="Copy to clipboard">
                <i class="fas fa-copy"></i>
              </button>
            </div>
          </div>
          <textarea id="code" placeholder='# Welcome to your Python playground! 🐍
# Type your code here and press Ctrl+Enter to run
print("Hello, Amazing Coder! 🌟")'></textarea>
        </div>
        
        <div class="console-output-container">
          <div class="output-header">
            <div class="output-title">
              <i class="fas fa-play-circle"></i>
              <span>Output</span>
            </div>
            <div class="output-status" id="outputStatus">Ready to run</div>
          </div>
          <div class="console-output" id="output">
            <div class="welcome-message">
              <i class="fas fa-rocket"></i>
              <span>Ready to code? Type above and click "Run" to see the magic! ✨</span>
            </div>
          </div>
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
    
    {{-- Enhanced Challenge Section --}}
    <div class="expected-section" id="challenge" data-expected="{{ $globalExpected ?? '' }}">
      <div class="section-header-enhanced">
        <h2 class="section-title-main">
          <span class="title-icon">🎯</span>
          <span>Your Challenge</span>
        </h2>
      </div>
      
      <div class="challenge-card">
        @if($globalExpected)
        <div class="challenge-content">
          <div class="challenge-description">
            <p><strong>Mission:</strong> Make your code produce the exact output below! 🎯</p>
          </div>
          <div class="expected-output">
            <div class="output-label">Expected Output:</div>
            <div class="output-preview">
              <code>{{ $globalExpected }}</code>
            </div>
          </div>
        </div>
        @else
        <div class="challenge-content">
          <div class="challenge-description">
            <p><strong>Mission:</strong> Complete all exercises and test your understanding in the console above! 🚀</p>
          </div>
        </div>
        @endif
        
        <div class="challenge-encouragement">
          <div class="encouragement-icon">
            <i class="fas fa-heart"></i>
          </div>
          <p>Remember: Every mistake is a step closer to mastery! 💪</p>
        </div>
      </div>
    </div>
    
    {{-- Enhanced Action Section --}}
    <div class="action-section">
      <div class="action-card">
        <div class="action-content">
          <div class="action-left">
            <h3>Ready for the Next Adventure?</h3>
            <p>You've got this! Each step forward is progress worth celebrating. 🌟</p>
          </div>
          <div class="action-buttons">
            <a href="{{ route('stages.show', $level->stage_id) }}" class="btn-action btn-secondary">
              <i class="fas fa-arrow-left me-2"></i> Back to Stage
            </a>
            <a href="{{ route('levels.show', $level) }}" class="btn-action btn-primary">
              Start Level <i class="fas fa-rocket ms-2"></i>
            </a>
          </div>
        </div>
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
      setStatus('loading Python runtime...');
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
      setStatus('ready to code! 🐍');
      document.getElementById('outputStatus').textContent = 'Ready to run';
    } catch (e) {
      setStatus('failed to load');
      appendErr("Pyodide failed to load: " + (e.message || e));
    }
  }
  
  function setStatus(s){ 
    const el = document.getElementById('status'); 
    if (el) el.textContent = '(' + s + ')'; 
  }
  
  function clearOut(){ 
    const el = document.getElementById('output'); 
    if (el) el.innerHTML = '<div class="welcome-message"><i class="fas fa-broom"></i><span>Console cleared! Ready for new code. ✨</span></div>'; 
  }
  
  function appendOut(s){ 
    const el = document.getElementById('output'); 
    if (!el) return; 
    // Clear welcome message if it exists
    const welcome = el.querySelector('.welcome-message');
    if (welcome) welcome.remove();
    
    const span = document.createElement('span'); 
    span.className='ok'; 
    span.textContent=s; 
    el.appendChild(span); 
    el.scrollTop=el.scrollHeight; 
    document.getElementById('outputStatus').textContent = 'Code executed successfully';
  }
  
  function appendErr(s){ 
    const el = document.getElementById('output'); 
    if (!el) return; 
    // Clear welcome message if it exists
    const welcome = el.querySelector('.welcome-message');
    if (welcome) welcome.remove();
    
    const span = document.createElement('span'); 
    span.className='err'; 
    span.textContent=s; 
    el.appendChild(span); 
    el.scrollTop=el.scrollHeight; 
    document.getElementById('outputStatus').textContent = 'Error occurred';
  }
  
  async function runCode(code){
    clearOut();
    if(!pyReady){ 
      appendErr("⏳ Python runtime is still loading. Please wait a moment..."); 
      return { out:'', err:'not-ready' }; 
    }
    try {
      document.getElementById('outputStatus').textContent = 'Running code...';
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
      appendErr("💥 Error: " + (e.message || e));
      return { out:'', err: e.message || String(e) };
    }
  }
  
  // ===== Enhanced UI logic =====
  document.addEventListener('DOMContentLoaded', () => {
    bootPython();
    
    // ---- Enhanced Instruction Stepper ----
    const instrSection = document.getElementById('instructionsSection');
    if (instrSection) {
      const total = parseInt(instrSection.dataset.total || '0', 10);
      let idx = 0;
      const steps = Array.from(instrSection.querySelectorAll('.step-content'));
      const dots  = Array.from(document.querySelectorAll('.progress-dot'));
      const prev  = document.getElementById('instrPrev');
      const next  = document.getElementById('instrNext');
      const currentStepEl = document.querySelector('.current-step');
      const progressFill = document.getElementById('stepProgressFill');
      
      function renderInstr(){
        steps.forEach((el,i)=> el.style.display = (i===idx?'block':'none'));
        dots.forEach((d,i)=> d.classList.toggle('active', i===idx));
        prev.disabled = idx===0;
        next.innerHTML = (idx===total-1) ? 'Complete <i class="fas fa-check ms-1"></i>' : 'Next <i class="fas fa-chevron-right ms-1"></i>';
        if (currentStepEl) currentStepEl.textContent = idx + 1;
        if (progressFill) progressFill.style.width = ((idx + 1) / total * 100) + '%';
      }
      
      prev?.addEventListener('click', e=>{ e.preventDefault(); if(idx>0){ idx--; renderInstr(); } });
      next?.addEventListener('click', e=>{ e.preventDefault(); if(idx<total-1){ idx++; renderInstr(); } else { showCelebration(); } });
      dots.forEach((d,i)=> d.addEventListener('click', ()=>{ idx=i; renderInstr(); }));
      renderInstr();
    }
    
    // ---- Enhanced Examples/Console Stepper ----
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
      
      if (exPrev) exPrev.disabled = (exIdx===0);
      if (exNext) exNext.disabled = (exIdx===exRows.length-1);
      
      const out = document.getElementById('output'); 
      if (out) out.innerHTML = '<div class="welcome-message"><i class="fas fa-code"></i><span>Code loaded! Ready to run. 🚀</span></div>';
    }
    
    if (exRows.length) loadExample(0);
    exPrev?.addEventListener('click', e=>{ e.preventDefault(); loadExample(exIdx-1); });
    exNext?.addEventListener('click', e=>{ e.preventDefault(); loadExample(exIdx+1); });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('btnRun')?.click();
      }
    });
    
    // Enhanced button interactions
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('button');
      if (!btn) return;
      
      if (btn.id === 'btnRun') {
        e.preventDefault(); 
        btn.disabled = true; 
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        setStatus('running code...');
        
        const { err } = await runCode(codeTA.value);
        
        setStatus(err ? 'error occurred' : 'ready to code! 🐍');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-play"></i>';
        return;
      }
      
      if (btn.id === 'btnCheck') {
        e.preventDefault();
        const expected = (challenge?.dataset.expected || '').trim();
        btn.disabled = true; 
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        setStatus('checking answer...');
        
        const { out } = await runCode(codeTA.value);
        const clean = (out || '').trim();
        
        if (expected) {
          if (clean === expected) {
            appendOut("\n🎉 Amazing! Perfect output! You're a coding star! ⭐");
            showSuccessAnimation();
          } else {
            appendErr(`\n🤔 Almost there! Let's compare:\n📋 Expected: "${expected}"\n💻 Your output: "${clean}"\n\n💡 Tip: Check your code carefully!`);
          }
        } else {
          appendOut("\n✅ Code executed successfully! Great job! 🌟");
        }
        
        setStatus('ready to code! 🐍'); 
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        return;
      }
      
      if (btn.id === 'btnClear') { 
        e.preventDefault(); 
        codeTA.value=''; 
        clearOut();
        return; 
      }
      
      if (btn.id === 'btnCopyMain' || btn.id === 'btnCopyToClipboard') {
        e.preventDefault();
        try { 
          await navigator.clipboard.writeText(codeTA.value); 
          appendOut("\n📋 Code copied to clipboard! 👍"); 
        } catch { 
          appendErr("\n⚠️ Clipboard access blocked by browser."); 
        }
        return;
      }
      
      if (btn.id === 'btnPasteFromClipboard') {
        e.preventDefault();
        try { 
          const t = await navigator.clipboard.readText(); 
          codeTA.value = t; 
          const out = document.getElementById('output');
          if (out) out.innerHTML = '<div class="welcome-message"><i class="fas fa-paste"></i><span>Code pasted! Ready to run. 🚀</span></div>';
        } catch { 
          appendErr("\n⚠️ Clipboard read blocked by browser."); 
        }
        return;
      }
    });
    
    // Success animation
    function showSuccessAnimation() {
      const btn = document.getElementById('btnCheck');
      if (btn) {
        btn.style.background = 'linear-gradient(45deg, #10b981, #34d399)';
        btn.style.color = 'white';
        setTimeout(() => {
          btn.style.background = '';
          btn.style.color = '';
        }, 2000);
      }
    }
    
    // Celebration for completing instructions
    function showCelebration() {
      const output = document.getElementById('output');
      if (output) {
        output.innerHTML = '<div class="celebration-message"><i class="fas fa-trophy"></i><span>🎉 Congratulations! You\'ve completed all instructions! 🌟<br/>🚀 Ready to start coding? Let\'s build something amazing!</span></div>';
      }
    }
  });
</script>

<style>
  :root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #a5b4fc;
    --secondary: #8b5cf6;
    --accent: #ec4899;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --error: #ef4444;
    --light: #f8fafc;
    --light-blue: #eff6ff;
    --dark: #1e293b;
    --gray: #64748b;
    --light-gray: #f1f5f9;
    --border: #e2e8f0;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --gradient-primary: linear-gradient(135deg, var(--primary), var(--secondary));
    --gradient-success: linear-gradient(135deg, var(--success), #34d399);
    --gradient-accent: linear-gradient(135deg, var(--accent), #f472b6);
  }
  
  * {
    box-sizing: border-box;
  }
  
  body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    color: var(--dark);
    line-height: 1.6;
  }
  
  /* Enhanced Header Styles */
  .instructions-header {
    background: var(--gradient-primary);
    color: white;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }
  
  .header-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
  }
  
  .floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
  }
  
  .shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
  }
  
  .shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
  }
  
  .shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
  }
  
  .shape-3 {
    width: 60px;
    height: 60px;
    top: 10%;
    right: 30%;
    animation-delay: 4s;
  }
  
  .shape-4 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 1s;
  }
  
  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
  }
  
  .header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 2;
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 0;
  }
  
  .header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
  }
  
  .header-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    backdrop-filter: blur(10px);
  }
  
  .icon-glow {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: var(--gradient-accent);
    border-radius: 18px;
    opacity: 0.3;
    filter: blur(8px);
    animation: pulse 2s ease-in-out infinite;
  }
  
  @keyframes pulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.05); }
  }
  
  .header-icon i {
    font-size: 28px;
    color: white;
    z-index: 1;
    position: relative;
  }
  
  .header-text {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .header-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    background: linear-gradient(45deg, #ffffff, #e0e7ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .header-subtitle {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.95;
  }
  
  .progress-bar {
    width: 200px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
    overflow: hidden;
  }
  
  .progress-fill {
    height: 100%;
    background: var(--gradient-accent);
    border-radius: 2px;
    transition: width 0.3s ease;
  }
  
  .btn-back {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
  }
  
  .btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }
  
  /* Motivational Banner */
  .motivation-banner {
    margin-bottom: 2rem;
    background: var(--gradient-success);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }
  
  .motivation-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    opacity: 0.3;
  }
  
  .motivation-content {
    display: flex;
    align-items: center;
    gap: 2rem;
    position: relative;
    z-index: 2;
  }
  
  .motivation-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    backdrop-filter: blur(10px);
  }
  
  .motivation-text {
    flex: 1;
  }
  
  .motivation-text h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    font-weight: 700;
  }
  
  .motivation-text p {
    margin: 0;
    opacity: 0.95;
    font-size: 1.1rem;
  }
  
  .achievement-badges {
    display: flex;
    gap: 1rem;
  }
  
  .badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
  }
  
  .badge:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.3);
  }
  
  .badge i {
    font-size: 20px;
  }
  
  .badge span {
    font-size: 0.875rem;
    font-weight: 600;
  }
  
  /* Page Layout */
  .page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
  }
  
  /* Enhanced Section Headers */
  .section-header-enhanced {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
  }
  
  .section-title-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0;
    color: var(--dark);
  }
  
  .title-icon {
    font-size: 2rem;
  }
  
  .section-decoration {
    flex: 1;
    height: 2px;
    margin-left: 2rem;
    background: linear-gradient(90deg, var(--primary), transparent);
    border-radius: 1px;
  }
  
  /* Enhanced Overview Section */
  .overview-section {
    margin-bottom: 3rem;
  }
  
  .overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
  }
  
  .overview-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
    position: relative;
  }
  
  .overview-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
  }
  
  .card-primary {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  }
  
  .card-secondary {
    background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
  }
  
  .card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid var(--border);
  }
  
  .card-icon {
    width: 48px;
    height: 48px;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
  }
  
  .card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    color: var(--dark);
  }
  
  .overview-content {
    padding: 1.5rem;
  }
  
  .meta-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--light-gray);
    border-radius: 12px;
  }
  
  .meta-icon {
    font-size: 1.5rem;
  }
  
  .meta-content {
    display: flex;
    flex-direction: column;
  }
  
  .meta-label {
    font-size: 0.875rem;
    color: var(--gray);
    font-weight: 500;
  }
  
  .meta-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
  }
  
  .section-heading {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 1.5rem 0 1rem;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .enhanced-list {
    margin: 0;
    padding: 0;
    list-style: none;
  }
  
  .enhanced-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
  }
  
  .enhanced-list li:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow-lg);
  }
  
  .list-bullet {
    font-size: 1.1rem;
    flex-shrink: 0;
  }
  
  /* Enhanced Learning Path */
  .learning-path {
    padding: 1.5rem;
  }
  
  .path-steps {
    margin: 0;
    padding: 0;
    list-style: none;
  }
  
  .path-step {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    margin-bottom: 1rem;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    position: relative;
  }
  
  .path-step:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }
  
  .path-step::before {
    content: '';
    position: absolute;
    left: -2px;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--gradient-primary);
    border-radius: 2px;
  }
  
  .step-number {
    width: 32px;
    height: 32px;
    background: var(--gradient-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
  }
  
  .step-content {
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  
  .step-title {
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.25rem;
  }
  
  .step-desc {
    font-size: 0.9rem;
    color: var(--gray);
  }
  
  .tip-box {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: var(--gradient-accent);
    color: white;
    border-radius: 16px;
    box-shadow: var(--shadow);
  }
  
  .tip-icon {
    font-size: 1.5rem;
    opacity: 0.9;
  }
  
  .tip-content {
    flex: 1;
  }
  
  .tip-content strong {
    font-weight: 700;
  }
  
  .tip-content kbd {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.85rem;
  }
  
  /* Enhanced Instructions Section */
  .instructions-section {
    margin-bottom: 3rem;
  }
  
  .step-indicator {
    font-size: 1rem;
    color: var(--gray);
    background: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
  }
  
  .current-step {
    font-weight: 800;
    color: var(--primary);
    font-size: 1.1rem;
  }
  
  .step-viewer {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
  }
  
  .step-header {
    padding: 1rem 1.5rem;
    background: var(--light-gray);
    border-bottom: 1px solid var(--border);
  }
  
  .step-progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
  }
  
  .step-progress-fill {
    height: 100%;
    background: var(--gradient-primary);
    border-radius: 3px;
    transition: width 0.3s ease;
  }
  
  .step-content {
    padding: 2rem;
    min-height: 200px;
    position: relative;
  }
  
  .step-badge {
    display: inline-block;
    background: var(--gradient-primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 1rem;
  }
  
  .step-text {
    line-height: 1.8;
    color: var(--dark);
    font-size: 1.1rem;
  }
  
  .step-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    background: var(--light-gray);
    border-top: 1px solid var(--border);
  }
  
  .btn-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
  }
  
  .btn-step:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }
  
  .btn-step:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .step-progress {
    display: flex;
    gap: 0.75rem;
  }
  
  .progress-dot {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--border);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--gray);
  }
  
  .progress-dot.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: scale(1.1);
  }
  
  .progress-dot:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow);
  }
  
  .dot-number {
    font-size: 0.875rem;
  }
  
  /* Enhanced Console Section */
  .console-section {
    margin-bottom: 3rem;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
  }
  
  .console-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1e293b, #334155);
    color: white;
  }
  
  .console-title {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .console-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    backdrop-filter: blur(10px);
  }
  
  .console-text h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
  }
  
  .console-status {
    font-size: 0.875rem;
    opacity: 0.8;
    margin: 0;
  }
  
  .console-actions {
    display: flex;
    gap: 0.75rem;
  }
  
  .console-btn {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    backdrop-filter: blur(10px);
  }
  
  .console-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
  }
  
  .btn-run:hover {
    background: var(--gradient-success);
  }
  
  .btn-check:hover {
    background: var(--gradient-primary);
  }
  
  .btn-copy:hover {
    background: var(--gradient-accent);
  }
  
  .btn-clear:hover {
    background: linear-gradient(135deg, var(--error), #f87171);
  }
  
  /* Enhanced Example Navigation */
  .example-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: var(--light-blue);
    border-bottom: 1px solid var(--border);
  }
  
  .example-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .example-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: var(--gradient-primary);
    color: white;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    width: fit-content;
  }
  
  .example-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    color: var(--dark);
  }
  
  .example-controls {
    display: flex;
    gap: 0.75rem;
  }
  
  .btn-example {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
  }
  
  .btn-example:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }
  
  .btn-example:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .example-explanation {
    padding: 1.5rem;
    background: var(--success-light);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .explanation-icon {
    color: var(--success);
    font-size: 1.25rem;
    margin-top: 0.25rem;
  }
  
  /* Enhanced Console Body */
  .console-body {
    display: flex;
    flex-direction: column;
  }
  
  .code-editor {
    position: relative;
    background: #f8f9fa;
  }
  
  .editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #e9ecef;
    border-bottom: 1px solid var(--border);
  }
  
  .editor-tabs {
    display: flex;
    gap: 0.5rem;
  }
  
  .editor-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 8px 8px 0 0;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray);
    box-shadow: var(--shadow);
  }
  
  .editor-tab.active {
    background: #f8f9fa;
    color: var(--dark);
  }
  
  .editor-tab i {
    color: #3776ab;
  }
  
  .editor-actions {
    display: flex;
    gap: 0.5rem;
  }
  
  .editor-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: white;
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: var(--shadow);
  }
  
  .editor-btn:hover {
    background: var(--light-gray);
    transform: translateY(-1px);
  }
  
  #code {
    width: 100%;
    min-height: 250px;
    padding: 1.5rem;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 1rem;
    line-height: 1.6;
    border: none;
    resize: vertical;
    background: #f8f9fa;
    color: #333;
    border-radius: 0;
  }
  
  #code:focus {
    outline: none;
    background: #ffffff;
  }
  
  .console-output-container {
    border-top: 1px solid var(--border);
  }
  
  .output-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #1e293b;
    color: white;
  }
  
  .output-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
  }
  
  .output-status {
    font-size: 0.875rem;
    opacity: 0.8;
  }
  
  .console-output {
    padding: 1.5rem;
    background: #1e293b;
    color: #e2e8f0;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 1rem;
    line-height: 1.6;
    min-height: 150px;
    max-height: 400px;
    overflow-y: auto;
    white-space: pre-wrap;
  }
  
  .console-output .ok {
    color: #a7f3d0;
  }
  
  .console-output .err {
    color: #fecaca;
  }
  
  .welcome-message, .celebration-message {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    border-left: 4px solid #10b981;
    color: #a7f3d0;
  }
  
  .celebration-message {
    border-left-color: #f59e0b;
    color: #fde68a;
    animation: celebrate 0.5s ease-in-out;
  }
  
  @keyframes celebrate {
    0% { transform: scale(0.95); opacity: 0; }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); opacity: 1; }
  }
  
  /* Enhanced Challenge Section */
  .expected-section {
    margin-bottom: 3rem;
  }
  
  .challenge-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
  }
  
  .challenge-content {
    padding: 2rem;
  }
  
  .challenge-description {
    margin-bottom: 1.5rem;
  }
  
  .challenge-description p {
    font-size: 1.1rem;
    color: var(--dark);
    margin: 0;
  }
  
  .expected-output {
    margin-top: 1.5rem;
  }
  
  .output-label {
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .output-label::before {
    content: '🎯';
    font-size: 1.2rem;
  }
  
  .output-preview {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px solid var(--success);
    box-shadow: var(--shadow);
  }
  
  .output-preview code {
    display: block;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 1rem;
    color: var(--dark);
    font-weight: 600;
  }
  
  .challenge-encouragement {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--gradient-accent);
    color: white;
    margin-top: 1.5rem;
  }
  
  .encouragement-icon {
    font-size: 1.5rem;
    animation: heartbeat 2s ease-in-out infinite;
  }
  
  @keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
  }
  
  .challenge-encouragement p {
    margin: 0;
    font-weight: 600;
  }
  
  /* Enhanced Action Section */
  .action-section {
    margin-bottom: 3rem;
  }
  
  .action-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
  }
  
  .action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
  }
  
  .action-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
  }
  
  .action-left h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: var(--dark);
  }
  
  .action-left p {
    margin: 0;
    color: var(--gray);
    font-size: 1.1rem;
  }
  
  .action-buttons {
    display: flex;
    gap: 1rem;
  }
  
  .btn-action {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1rem;
    box-shadow: var(--shadow);
  }
  
  .btn-primary {
    background: var(--gradient-primary);
    color: white;
    border: none;
  }
  
  .btn-primary:hover {
    color: white;
    transform: translateY(-3px);
    box-shadow: var(--shadow-xl);
  }
  
  .btn-secondary {
    background: white;
    color: var(--dark);
    border: 2px solid var(--border);
  }
  
  .btn-secondary:hover {
    background: var(--light-gray);
    color: var(--dark);
    transform: translateY(-3px);
    box-shadow: var(--shadow-xl);
  }
  
  /* Responsive Design */
  @media (max-width: 1024px) {
    .overview-grid {
      grid-template-columns: 1fr;
    }
    
    .action-content {
      flex-direction: column;
      text-align: center;
    }
  }
  
  @media (max-width: 768px) {
    .header-content {
      flex-direction: column;
      align-items: flex-start;
      gap: 1.5rem;
    }
    
    .header-left {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .motivation-content {
      flex-direction: column;
      text-align: center;
    }
    
    .achievement-badges {
      justify-content: center;
    }
    
    .page-container {
      padding: 1rem;
    }
    
    .section-header-enhanced {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .section-decoration {
      display: none;
    }
    
    .step-controls {
      flex-direction: column;
      gap: 1.5rem;
    }
    
    .step-progress {
      justify-content: center;
    }
    
    .console-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1.5rem;
    }
    
    .example-nav {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .action-buttons {
      flex-direction: column;
      width: 100%;
    }
    
    .btn-action {
      justify-content: center;
    }
    
    .progress-bar {
      width: 100%;
      max-width: 300px;
    }
  }
  
  @media (max-width: 480px) {
    .header-title {
      font-size: 1.5rem;
    }
    
    .section-title-main {
      font-size: 1.5rem;
    }
    
    .title-icon {
      font-size: 1.5rem;
    }
    
    .motivation-text h3 {
      font-size: 1.25rem;
    }
    
    .step-progress {
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    
    .progress-dot {
      width: 32px;
      height: 32px;
    }
    
    .console-actions {
      flex-wrap: wrap;
    }
    
    .console-btn {
      width: 40px;
      height: 40px;
    }
  }
  
  /* Animation Classes */
  .fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .slide-in-left {
    animation: slideInLeft 0.5s ease-out;
  }
  
  @keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
  }
  
  .slide-in-right {
    animation: slideInRight 0.5s ease-out;
  }
  
  @keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
  }
  
  /* Success States */
  .success-state {
    background: var(--gradient-success) !important;
    color: white !important;
    transform: scale(1.05);
  }
  
  .error-state {
    background: linear-gradient(135deg, var(--error), #f87171) !important;
    color: white !important;
  }
  
  /* Loading States */
  .loading {
    position: relative;
    overflow: hidden;
  }
  
  .loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: loading 1.5s infinite;
  }
  
  @keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
  }
  
  /* Custom Scrollbar */
  .console-output::-webkit-scrollbar {
    width: 8px;
  }
  
  .console-output::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 4px;
  }
  
  .console-output::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 4px;
  }
  
  .console-output::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
  }
  
  /* Focus Styles */
  button:focus-visible,
  .btn-action:focus-visible,
  .btn-step:focus-visible,
  .btn-example:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
  }
  
  #code:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: -2px;
  }
  
  /* Print Styles */
  @media print {
    .instructions-header,
    .console-section,
    .action-section {
      display: none;
    }
    
    .page-container {
      max-width: none;
      padding: 0;
    }
    
    .overview-card,
    .step-viewer,
    .challenge-card {
      box-shadow: none;
      border: 1px solid #ccc;
    }
  }
</style>
</x-app-layout>