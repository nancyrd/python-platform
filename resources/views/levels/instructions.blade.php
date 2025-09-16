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
        $instructionSteps = array_values(array_filter(array_map('trim',
            preg_split('/(\R{2,}|^\s*[-]{3,}\s*$|^#+\s.*$)/m', $instructions)
        )));
        if (!$instructionSteps) $instructionSteps = [trim($instructions)];
    }
    // Examples (array of dicts)
    $examples = is_array($content) ? ($content['examples'] ?? []) : [];
    if ($examples && array_keys($examples) !== range(0, count($examples) - 1)) {
        $examples = [$examples];
    }
    $estimatedTime  = is_array($content) ? ($content['estimated_time'] ?? null) : null;
    $goals          = is_array($content) ? ($content['goals'] ?? []) : [];
    $prerequisites  = is_array($content) ? ($content['prerequisites'] ?? []) : [];
    $globalExpected = is_array($content) ? ($content['expected_output'] ?? null) : null;
@endphp
<x-slot name="header">
    <div class="game-header-container">
        <div class="header-flex">
            <div class="header-left">
                <div class="stage-icon-container me-3">
                    <div class="stage-icon-wrapper">
                        <i class="fas fa-dungeon stage-icon"></i>
                    </div>
                </div>
                <div class="min-w-0">
                    <h2 class="stage-title mb-0 text-truncate">Level {{ $level->index ?? '' }}: {!! $level->title ?? 'Instructions' !!}</h2>
                    <div class="stage-subtitle">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Adventure Zone
                    </div>
                </div>
            </div>
            <div class="header-right">
                <a href="{{ route('dashboard') }}" class="btn btn-game">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Map
                </a>
            </div>
        </div>
    </div>
</x-slot>

<div class="game-viewport">
  <div class="game-container">
    <div class="floating-elements">
      <div class="floating-coin" style="top:10%; left:5%;">🪙</div>
      <div class="floating-gem"  style="top:20%; left:92%;">💎</div>
      <div class="floating-star" style="top:65%; left:3%;">⭐</div>
      <div class="floating-coin" style="top:82%; left:96%;">🪙</div>
      <div class="floating-gem"  style="top:38%; left:4%;">💎</div>
      <div class="floating-star" style="top:18%; left:86%;">⭐</div>
    </div>

    <div class="content-wrap">
      <!-- PREAMBLE / OVERVIEW -->
      <div class="cosmic-separator"><div class="label">Instructions & Overview</div></div>
      <div class="card-surface instructions-card">
        <div class="overview-grid mb-3">
          @if($estimatedTime)
          <div class="meta-card">
            <div class="meta-icon">⏱️</div>
            <div class="meta-content">
              <div class="meta-label muted">Estimated Time</div>
              <div class="meta-value">{!! $estimatedTime !!}</div>
            </div>
          </div>
          @endif
          @if(!empty($goals))
          <div class="meta-card">
            <div class="meta-icon">🎯</div>
            <div class="meta-content">
              <div class="meta-label muted">Goals</div>
              <div class="meta-value">{{ count($goals) }} learning objectives</div>
            </div>
          </div>
          @endif
          @if(!empty($prerequisites))
          <div class="meta-card">
            <div class="meta-icon">📚</div>
            <div class="meta-content">
              <div class="meta-label muted">Prerequisites</div>
              <div class="meta-value">{{ count($prerequisites) }} requirements</div>
            </div>
          </div>
          @endif
        </div>

        @if(count($instructionSteps) > 0)
        <div class="instructions-container">
          <div class="step-navigation">
            <button class="nav-btn" id="prevStepBtn" disabled><i class="fas fa-chevron-left"></i></button>
            <div class="step-info">
              <span class="step-current" id="currentStep">1</span>
              <span class="muted">/</span>
              <span id="totalSteps">{{ count($instructionSteps) }}</span>
            </div>
            <button class="nav-btn" id="nextStepBtn"><i class="fas fa-chevron-right"></i></button>
          </div>
          <div class="step-progress">
            <div class="step-progress-fill" id="stepProgressFill" style="width: {{ max(1, (int)(100 / max(1,count($instructionSteps)))) }}%"></div>
          </div>
          <div class="step-container">
            @foreach($instructionSteps as $i => $step)
              <div class="step-content" data-step="{{ $i }}" style="{{ $i === 0 ? '' : 'display:none' }}">
                <div class="step-text">{!! nl2br($step) !!}</div>
              </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>

      <!-- PLAYGROUND / EDITOR -->
      <div class="cosmic-separator"><div class="label">Python Playground</div></div>
      <div class="card-surface mb-4">
        @if(count($examples) > 0)
        <div class="example-nav" id="exampleNav">
          <div class="example-info">
            <div class="example-badge" id="exampleBadge">Example 1 / {{ count($examples) }}</div>
            <div class="example-title" id="exampleTitle"></div>
          </div>
          <div class="example-controls">
            <button class="btn-example" id="prevExampleBtn" {{ count($examples) > 1 ? '' : 'disabled' }}>
              <i class="fas fa-chevron-left"></i> Prev
            </button>
            <button class="btn-example" id="loadExampleBtn">
              <i class="fas fa-download"></i> Load
            </button>
            <button class="btn-example" id="nextExampleBtn" {{ count($examples) > 1 ? '' : 'disabled' }}>
              Next <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
        <div class="example-preview" id="examplePreview" style="display:none;">
          <div class="example-code" id="exampleCodeDisplay"></div>
          <div class="example-explanation" id="exampleExplanation" style="display:none;"></div>
        </div>
        @endif

        <div class="console-header">
          <div class="console-info">
            <div class="console-icon"><i class="fab fa-python"></i></div>
            <div>
              <div class="console-title">Python Playground</div>
              <div class="console-status" id="pythonStatus">Loading...</div>
            </div>
          </div>
          <div class="console-actions">
            <div class="editor-actions">
              <button class="console-btn btn-run" id="runBtn" title="Run Code (Ctrl+Enter)"><i class="fas fa-play"></i> Run</button>
              <button class="console-btn btn-check" id="checkBtn" title="Check Answer"><i class="fas fa-check"></i> Check</button>
              <button class="console-btn btn-clear" id="clearBtn" title="Clear Code"><i class="fas fa-broom"></i> Clear</button>
            </div>
            <div class="layout-controls">
              <button class="layout-btn" id="verticalLayoutBtn" title="Vertical Layout"><i class="fas fa-columns"></i> Vertical</button>
              <button class="layout-btn active" id="horizontalLayoutBtn" title="Horizontal Layout"><i class="fas fa-grip-lines"></i> Horizontal</button>
              <button class="layout-btn" id="fullscreenCodeBtn" title="Code Only"><i class="fas fa-expand"></i> Code Only</button>
            </div>
          </div>
        </div>

        <div class="workspace-container" id="workspaceContainer">
          <div class="editor-panel" id="editorPanel">
            <div class="panel-header">
              <div class="panel-title"><i class="fab fa-python me-2"></i>Code Editor</div>
              <div class="panel-controls">
                <button class="control-btn" id="expandEditorBtn" title="Expand Editor"><i class="fas fa-expand-arrows-alt"></i></button>
              </div>
            </div>
            <div class="editor-content">
              <textarea class="code-editor" id="codeEditor" placeholder="# Write your Python code here\n# Press Ctrl+Enter to run quickly\nprint('Hello, Python!')"></textarea>
            </div>
          </div>
          <div class="resize-handle" id="resizeHandle"><div class="resize-line"></div></div>
          <div class="output-panel" id="outputPanel">
            <div class="panel-header">
              <div class="panel-title"><i class="fas fa-terminal me-2"></i>Output</div>
              <div class="panel-controls">
                <div class="output-status" id="outputStatus">Ready</div>
                <button class="control-btn" id="expandOutputBtn" title="Expand Output"><i class="fas fa-expand-arrows-alt"></i></button>
              </div>
            </div>
            <div class="output-content">
              <div class="console-output" id="consoleOutput">
                <div class="welcome-message"><i class="fas fa-rocket me-2"></i><span>Ready to code! Write your Python code and click "Run".</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CHALLENGE -->
      <div class="cosmic-separator"><div class="label">Challenge</div></div>
      <div class="card-surface challenge-section" id="challengeSection" data-expected="{{ $globalExpected ?? '' }}">
        <div class="challenge-header"><i class="fas fa-bullseye me-2"></i><h3 class="final-boss-title mb-0">Your Challenge</h3></div>
        @if($globalExpected)
          <p class="muted">Make your code produce the exact output below!</p>
          <div class="expected-output">
            <div class="output-label">Expected Output</div>
            <div class="output-preview" id="expectedOutputPreview">{!! $globalExpected !!}</div>
          </div>
        @else
          <p class="muted">Complete the exercises and test your understanding using the console above.</p>
        @endif
      </div>

      @if(count($examples) > 0)
      <div id="examplesData" style="display:none">
        @foreach($examples as $i => $ex)
          @php
            $title   = trim($ex['title'] ?? ('Example '.($i+1)));
            $code    = (string)($ex['code'] ?? '');
            $explain = (string)($ex['explain'] ?? '');
            $expect  = (string)($ex['expected_output'] ?? ($globalExpected ?? ''));
          @endphp
          <div class="ex-row" data-title="{{ e($title) }}" data-code="{{ base64_encode($code) }}" data-explain="{{ e($explain) }}" data-expected="{{ e($expect) }}"></div>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>

{{-- Pyodide (client-side Python) --}}
<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
<script>
  let pyodide; let pyReady=false; let currentStepIndex=0; let currentExampleIndex=0;
  let examples=[]; let expectedOutput=''; let isResizing=false; let currentLayout='horizontal';

  async function initializePython(){
    try{
      updateStatus('Loading Python runtime...');
      pyodide = await loadPyodide({
        stdout: t=>appendOutput(t+"\n",'ok'), stderr: t=>appendOutput(t+"\n",'err')
      });
      await pyodide.runPythonAsync(`
import builtins
try:
    from js import prompt as __prompt
    builtins.input = lambda p='': __prompt(p)
except Exception:
    pass
      `);
      pyReady=true; updateStatus('Ready to code!'); updateOutputStatus('Ready');
    }catch(err){ updateStatus('Failed to load Python'); appendOutput('Failed to load Python: '+err.message,'err'); }
  }
  function updateStatus(msg){ const el=document.getElementById('pythonStatus'); if(el) el.textContent=msg; }
  function updateOutputStatus(msg){ const el=document.getElementById('outputStatus'); if(el) el.textContent=msg; }
  function clearOutput(){ const out=document.getElementById('consoleOutput'); if(!out) return; out.innerHTML=`<div class="welcome-message"><i class=\"fas fa-broom me-2\"></i><span>Console cleared! Ready for new code.</span></div>`; }
  function appendOutput(text,type='ok'){ const out=document.getElementById('consoleOutput'); if(!out) return; const w=out.querySelector('.welcome-message'); if(w) w.remove(); const s=document.createElement('span'); s.className=type; s.textContent=text; out.appendChild(s); out.scrollTop=out.scrollHeight; }

  async function runCode(){
    const codeEl=document.getElementById('codeEditor'); const code=codeEl?codeEl.value:'';
    if(!code.trim()){ appendOutput('No code to run!\n','err'); return; }
    clearOutput(); if(!pyReady){ appendOutput('Python runtime is still loading. Please wait...\n','err'); return; }
    try{
      updateOutputStatus('Running code...'); updateStatus('Executing...');
      pyodide.globals.set('USER_CODE', code);
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
      const out=pyodide.globals.get('OUT')||''; const err=pyodide.globals.get('ERR')||'';
      if(out) appendOutput(out,'ok'); if(err) appendOutput(err,'err');
      updateStatus('Ready to code!'); updateOutputStatus(err? 'Error occurred' : 'Code executed successfully');
      return {out, err};
    }catch(e){ appendOutput('Error: '+e.message+'\n','err'); updateStatus('Ready to code!'); updateOutputStatus('Error occurred'); return {out:'', err:e.message}; }
  }
  async function checkAnswer(){ const {out} = await runCode(); const clean=(out||'').trim(); if(expectedOutput){ if(clean===expectedOutput.trim()){ appendOutput('\n🎉 Perfect! Your output matches exactly! 🌟\n','ok'); } else { appendOutput(`\n🤔 Almost there! Let's compare:\n`,'err'); appendOutput(`Expected: "${expectedOutput.trim()}"\n`,'err'); appendOutput(`Your output: "${clean}"\n`,'err'); appendOutput('💡 Tip: Check your code carefully!\n','err'); } } else { appendOutput('\n✅ Code executed successfully! Great work! 🚀\n','ok'); } }

  function setLayout(layout){
    const container=document.getElementById('workspaceContainer');
    const editor=document.getElementById('editorPanel'); const output=document.getElementById('outputPanel');
    const resize=document.getElementById('resizeHandle'); document.querySelectorAll('.layout-btn').forEach(b=>b.classList.remove('active'));
    if(layout==='vertical'){ container.className='workspace-container vertical'; document.getElementById('verticalLayoutBtn').classList.add('active'); resize.style.display='block'; editor.style.display='flex'; output.style.display='flex'; }
    else if(layout==='horizontal'){ container.className='workspace-container horizontal'; document.getElementById('horizontalLayoutBtn').classList.add('active'); resize.style.display='block'; editor.style.display='flex'; output.style.display='flex'; }
    else { container.className='workspace-container code-only'; document.getElementById('fullscreenCodeBtn').classList.add('active'); resize.style.display='none'; editor.style.display='flex'; output.style.display='none'; }
    currentLayout=layout;
  }

  function initializeResize(){
    const resize=document.getElementById('resizeHandle'); const container=document.getElementById('workspaceContainer');
    resize.addEventListener('mousedown',e=>{ isResizing=true; document.addEventListener('mousemove',handle); document.addEventListener('mouseup',stop); e.preventDefault(); });
    function handle(e){ if(!isResizing) return; const rect=container.getBoundingClientRect();
      if(currentLayout==='horizontal'){ const w=((e.clientX-rect.left)/rect.width)*100; if(w>=20 && w<=80){ document.documentElement.style.setProperty('--editor-width', w+'%'); document.documentElement.style.setProperty('--output-width', (100-w)+'%'); } }
      else if(currentLayout==='vertical'){ const h=((e.clientY-rect.top)/rect.height)*100; if(h>=20 && h<=80){ document.documentElement.style.setProperty('--editor-height', h+'%'); document.documentElement.style.setProperty('--output-height', (100-h)+'%'); } }
    }
    function stop(){ isResizing=false; document.removeEventListener('mousemove',handle); document.removeEventListener('mouseup',stop); }
  }

  function updateStepDisplay(){
    const cur=document.getElementById('currentStep'); const items=document.querySelectorAll('.step-content'); const prev=document.getElementById('prevStepBtn'); const next=document.getElementById('nextStepBtn');
    if(cur) cur.textContent=currentStepIndex+1; items.forEach((el,i)=>{ el.style.display = (i===currentStepIndex)?'block':'none'; });
    if(prev) prev.disabled = currentStepIndex===0; if(next) next.disabled = currentStepIndex===items.length-1;
    const progress=document.getElementById('stepProgressFill'); if(progress && items.length){ const pct = ((currentStepIndex+1)/items.length)*100; progress.style.width=pct+'%'; }
  }
  function navigateStep(dir){ const items=document.querySelectorAll('.step-content'); if(dir==='prev' && currentStepIndex>0){ currentStepIndex--; updateStepDisplay(); } else if(dir==='next' && currentStepIndex<items.length-1){ currentStepIndex++; updateStepDisplay(); } }

  function initializeExamples(){ const rows=document.querySelectorAll('#examplesData .ex-row'); examples=[]; rows.forEach(r=>{ const title=r.dataset.title||''; const code=r.dataset.code?atob(r.dataset.code):''; const explain=r.dataset.explain||''; const expected=r.dataset.expected||''; examples.push({title, code, explain, expected_output: expected}); }); if(examples.length>0) updateExampleDisplay(); }
  function updateExampleDisplay(){ if(!examples.length) return; const badge=document.getElementById('exampleBadge'); const title=document.getElementById('exampleTitle'); const codeDisp=document.getElementById('exampleCodeDisplay'); const expl=document.getElementById('exampleExplanation'); const preview=document.getElementById('examplePreview'); const prev=document.getElementById('prevExampleBtn'); const next=document.getElementById('nextExampleBtn'); const cur=examples[currentExampleIndex];
    if(badge) badge.textContent=`Example ${currentExampleIndex+1} / ${examples.length}`; if(title) title.textContent=cur.title||`Example ${currentExampleIndex+1}`;
    if(codeDisp){ codeDisp.textContent=cur.code||''; preview.style.display = cur.code? 'block':'none'; }
    if(expl){ if(cur.explain){ expl.textContent=cur.explain; expl.style.display='block'; } else { expl.style.display='none'; } }
    if(prev) prev.disabled = currentExampleIndex===0; if(next) next.disabled = currentExampleIndex===examples.length-1; expectedOutput = cur.expected_output || ''; updateChallengeDisplay(); }
  function navigateExample(d){ if(d==='prev' && currentExampleIndex>0){ currentExampleIndex--; updateExampleDisplay(); } else if(d==='next' && currentExampleIndex<examples.length-1){ currentExampleIndex++; updateExampleDisplay(); } }
  function loadExampleToEditor(){ const editor=document.getElementById('codeEditor'); const disp=document.getElementById('exampleCodeDisplay'); if(editor && disp){ editor.value = disp.textContent; clearOutput(); updateOutputStatus('Example loaded!'); setTimeout(()=>updateOutputStatus('Ready'),1500); editor.focus(); } }
  function updateChallengeDisplay(){ const el=document.getElementById('expectedOutputPreview'); if(expectedOutput && el){ el.textContent = expectedOutput; } }

  document.addEventListener('DOMContentLoaded',()=>{
    initializePython(); initializeExamples(); updateStepDisplay(); initializeResize(); setLayout('horizontal');
    document.getElementById('runBtn')?.addEventListener('click', runCode);
    document.getElementById('checkBtn')?.addEventListener('click', checkAnswer);
    document.getElementById('clearBtn')?.addEventListener('click', ()=>{ document.getElementById('codeEditor').value=''; clearOutput(); });
    document.getElementById('verticalLayoutBtn')?.addEventListener('click', ()=>setLayout('vertical'));
    document.getElementById('horizontalLayoutBtn')?.addEventListener('click', ()=>setLayout('horizontal'));
    document.getElementById('fullscreenCodeBtn')?.addEventListener('click', ()=>setLayout('code-only'));
    document.getElementById('prevStepBtn')?.addEventListener('click', ()=>navigateStep('prev'));
    document.getElementById('nextStepBtn')?.addEventListener('click', ()=>navigateStep('next'));
    document.getElementById('prevExampleBtn')?.addEventListener('click', ()=>navigateExample('prev'));
    document.getElementById('nextExampleBtn')?.addEventListener('click', ()=>navigateExample('next'));
    document.getElementById('loadExampleBtn')?.addEventListener('click', loadExampleToEditor);
    const codeEditor=document.getElementById('codeEditor'); if(codeEditor) codeEditor.focus();
    document.addEventListener('keydown',e=>{ if((e.ctrlKey||e.metaKey)&&e.key==='Enter'){ e.preventDefault(); runCode(); } if(e.key==='Escape' && currentLayout==='code-only'){ setLayout('horizontal'); } });
  });
</script>

<style>
  :root{
    /* Cosmic palette to match other blades */
    --bg-start:#3B146B; --bg-end:#1A082D; --primary:#7A2EA5; --accent:#B967FF;
    --card:#EDE6FF; --card-brd:rgba(122,46,165,.28); --tile:#F2EBFF;
    --ink:#2B1F44; --muted:#5B556A; --success:#16A34A; --warn:#F59E0B; --error:#ef4444;
    --border: rgba(122,46,165,.22);
    --editor-width: 52%; --output-width: 48%; --editor-height: 52%; --output-height: 48%;
    /* Font to match other blades */
    --font-ui: 'Orbitron','Arial',sans-serif;
  }
  /* Use the same UI font everywhere on this page (except code areas) */
  .game-viewport, .game-viewport *:not(textarea.code-editor):not(.console-output){
    font-family: var(--font-ui);
  }
  .game-viewport{ min-height: calc(100vh - 0px); background:linear-gradient(45deg,var(--bg-start),var(--bg-end)); color:#fff; }
  .game-container{ position:relative; }
  .content-wrap{ width: min(1200px, 96vw); margin: 18px auto 28px; }

  /* Header (borrowed style) */
  .game-header-container{ background:linear-gradient(135deg,var(--bg-start),var(--primary)); border-bottom:1px solid var(--accent); box-shadow:0 10px 28px rgba(25,10,41,.35), inset 0 -1px 0 rgba(255,255,255,.06); position:relative; overflow:hidden; padding:14px 16px; }
.game-header-container::before{ content:''; position:absolute; inset:0; left:-100%; background:linear-gradient(90deg,transparent,rgba(185,103,255,.35),transparent); animation:headerShine 4s ease-in-out infinite; }
/* Force same font as stages */
.game-header-container, .game-header-container * { font-family: var(--font-ui) !important; }
/* New header flex layout to avoid wrapping and match stage header */
.header-flex{ display:flex; align-items:center; justify-content:space-between; gap:16px; }
.header-left{ display:flex; align-items:center; gap:12px; min-width:0; flex:1 1 auto; }
.header-right{ flex:0 0 auto; display:flex; align-items:center; gap:12px; }
.game-header-container .btn.btn-game{ white-space:nowrap; }
.stage-title{ font-size:clamp(1.4rem, 1.2rem + 1.6vw, 2.25rem); font-weight:900; letter-spacing:.5px; margin:0; background:linear-gradient(45deg,var(--primary),var(--accent)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; text-shadow:0 6px 18px rgba(185,103,255,.28); }
.stage-subtitle{ color:rgba(255,255,255,.85); font-size:.95rem; } 
$1@keyframes headerShine{0%{left:-100%}50%{left:100%}100%{left:100%}}
  .stage-icon-wrapper{ width:64px;height:64px;border-radius:14px; background:linear-gradient(145deg,var(--bg-start),#321052); border:1px solid var(--accent); box-shadow:0 0 30px rgba(185,103,255,.18) inset, 0 0 22px rgba(185,103,255,.22); display:flex;align-items:center;justify-content:center; animation:pulse 2.4s ease-in-out infinite; }
  .stage-icon{ color:var(--accent); font-size:26px; }
  @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.06)}}
  .stage-title{ font-size:2rem;font-weight:900;letter-spacing:.5px;margin:0; background:linear-gradient(45deg,var(--accent),#fff); -webkit-background-clip:text; -webkit-text-fill-color:transparent; text-shadow:0 6px 18px rgba(185,103,255,.28); }
  .stage-subtitle{ color:rgba(255,255,255,.85); font-size:.95rem; }

  /* Cards */
  .card-surface{ background:var(--card); color:var(--ink); border:1px solid var(--card-brd); border-radius:18px; box-shadow:0 12px 34px rgba(25,10,41,.18), 0 0 0 1px rgba(185,103,255,.06); padding:18px; }
  .card-surface .muted{ color:var(--muted)!important; }

  .cosmic-separator{ position:relative; height:28px; margin:14px 0 16px; width:100%; }
  .cosmic-separator::after{ content:''; position:absolute; left:0; right:0; top:50%; height:1px; background:linear-gradient(90deg, transparent, rgba(185,103,255,.45), transparent); }
  .cosmic-separator .label{ position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); background:linear-gradient(45deg,var(--primary),var(--accent)); color:#fff; font-weight:900; letter-spacing:.4px; text-transform:uppercase; font-size:.8rem; padding:6px 12px; border-radius:999px; box-shadow:0 10px 24px rgba(185,103,255,.25); }

  /* Meta / overview */
  .overview-grid{ display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:12px; }
  .meta-card{ display:flex; align-items:center; gap:.75rem; padding:12px 14px; background:var(--tile); border:1px solid var(--border); border-radius:12px; }
  .meta-icon{ font-size:1.25rem; }
  .meta-label{ font-size:.8rem; }
  .meta-value{ font-weight:800; }

  /* Instructions */
  .instructions-container{ background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; }
  .step-navigation{ display:flex; justify-content:space-between; align-items:center; padding:10px 12px; background:#F5EEFF; border-bottom:1px solid var(--border); }
  .nav-btn{ width:36px; height:36px; border:1px solid var(--border); border-radius:8px; background:#fff; color:var(--ink); cursor:pointer; transition:.2s; }
  .nav-btn:hover:not(:disabled){ background:linear-gradient(135deg,var(--primary),var(--accent)); color:#fff; }
  .nav-btn:disabled{ opacity:.5; cursor:not-allowed; }
  .step-info{ font-weight:900; color:var(--ink); }
  .step-current{ color:var(--primary); font-size:1.1rem; }
  .step-progress{ height:6px; background:#EFE8FF; }
  .step-progress-fill{ height:100%; background:linear-gradient(90deg,var(--primary),var(--accent)); width:0%; transition:width .25s ease; }
  .step-container{ padding:16px; min-height:110px; }
  .step-content{ line-height:1.7; color:var(--ink); }

  /* Example */
  .example-nav{ padding:10px 16px; background:#F5EEFF; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
  .example-info{ display:flex; flex-direction:column; gap:2px; }
  .example-badge{ font-size:.75rem; background:linear-gradient(135deg,var(--primary),var(--accent)); color:#fff; padding:.25rem .6rem; border-radius:999px; width:fit-content; }
  .example-title{ font-weight:800; color:var(--ink); font-size:.95rem; }
  .btn-example{ padding:.4rem .75rem; border:1px solid var(--border); border-radius:8px; background:#fff; cursor:pointer; font-size:.8rem; color:#var(--ink); transition:.2s; display:flex; align-items:center; gap:.4rem; }
  .btn-example:hover:not(:disabled){ background:linear-gradient(135deg,var(--primary),var(--accent)); color:#fff; }
  .btn-example:disabled{ opacity:.5; cursor:not-allowed; }
  .example-preview{ padding:14px 16px; background:#faf7ff; border-bottom:1px solid var(--border); }
  .example-code{ background:#fff; border:1px solid var(--border); border-radius:10px; padding:12px; font-family:'Consolas','Monaco','Courier New',monospace; font-size:13px; line-height:1.5; color:var(--ink); white-space:pre-wrap; overflow-x:auto; margin-bottom:.75rem; }
  .example-explanation{ background:rgba(22,163,74,.08); border-left:3px solid var(--success); padding:.75rem; border-radius:6px; font-size:.9rem; color:var(--ink); }

  /* Console */
  .console-header{ padding:12px 16px; background:#1B1230; color:#fff; display:flex; justify-content:space-between; align-items:center; border-radius:14px 14px 0 0; }
  .console-icon{ width:36px; height:36px; background:rgba(255,255,255,.1); border-radius:8px; display:flex; align-items:center; justify-content:center; }
  .console-title{ font-weight:800; }
  .console-status{ font-size:.85rem; opacity:.9; }
  .console-actions{ display:flex; gap:.5rem; align-items:center; }
  .editor-actions{ display:flex; gap:.5rem; margin-right:.5rem; padding-right:.5rem; border-right:1px solid rgba(255,255,255,.18); }
  .console-btn{ padding:.5rem .7rem; background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.18); border-radius:8px; color:#fff; cursor:pointer; font-size:.85rem; font-weight:700; transition:.2s; display:flex; align-items:center; gap:.45rem; }
  .console-btn:hover{ background:rgba(255,255,255,.22); }
  .btn-run:hover{ background:var(--success)!important; color:#fff!important; }
  .btn-check:hover{ background:var(--accent)!important; color:#fff!important; }
  .btn-clear:hover{ background:#DD3B3B!important; color:#fff!important; }
  .layout-controls{ display:flex; gap:.5rem; }
  .layout-btn{ padding:.5rem .75rem; background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.18); border-radius:8px; color:#fff; cursor:pointer; font-size:.85rem; font-weight:700; transition:.2s; display:flex; align-items:center; gap:.45rem; }
  .layout-btn:hover, .layout-btn.active{ background:rgba(255,255,255,.22); border-color:rgba(255,255,255,.3); }

  /* Workspace sizing – prevents overfitting */
  .workspace-container{ display:flex; background:#fff; border-radius:0 0 14px 14px; overflow:hidden; min-height:420px; height:clamp(420px,58vh,64vh); border:1px solid var(--border); border-top:none; }
  .workspace-container.horizontal{ flex-direction:row; }
  .workspace-container.vertical{ flex-direction:column; }
  .workspace-container.code-only .output-panel{ display:none; }

  .panel-header{ padding:10px 12px; background:#F5EEFF; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
  .panel-title{ font-weight:900; color:var(--ink); }
  .panel-controls{ display:flex; gap:.5rem; align-items:center; }
  .control-btn{ width:34px; height:34px; border:1px solid var(--border); border-radius:8px; background:#fff; color:var(--ink); cursor:pointer; }
  .control-btn:hover{ background:linear-gradient(135deg,var(--primary),var(--accent)); color:#fff; }

  /* Editor */
  .editor-panel{ flex:1; display:flex; flex-direction:column; border-right:1px solid var(--border); min-width:0; }
  .workspace-container.horizontal .editor-panel{ width:var(--editor-width); }
/* Add a visible inner border so the resizer never sits on top of the first character */
.workspace-container.horizontal .output-panel{ width:var(--output-width); border-left: 1px solid rgba(122,46,165,.18); }
  .workspace-container.vertical .editor-panel{ height:var(--editor-height); border-right:none; border-bottom:1px solid var(--border); }
  .editor-content{ flex:1; position:relative; }
  .code-editor{ width:100%; height:100%; min-height:220px; border:none; padding:12px; font-family:'Consolas','Monaco','Courier New',monospace; font-size:14px; line-height:1.6; resize:none; background:#FAFAFF; color:var(--ink); outline:none; }
  .code-editor:focus{ background:#fff; }

  /* Resizer */
  .resize-handle{ background:#ECE3FF; flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:background .2s; position:relative; z-index:3; }
  .workspace-container.horizontal .resize-handle{ width:4px; cursor:ew-resize; }
  .workspace-container.vertical .resize-handle{ height:4px; cursor:ns-resize; }
  .resize-handle:hover{ background:var(--accent); }
  .resize-line{ background:#fff; border-radius:2px; }
  .workspace-container.horizontal .resize-line{ width:2px; height:20px; }
  .workspace-container.vertical .resize-line{ width:20px; height:2px; }

  /* Output */
  .output-panel{ flex:1; display:flex; flex-direction:column; min-width:0; position:relative; z-index:1; }
  .workspace-container.horizontal .output-panel{ width:var(--output-width); }
  .workspace-container.vertical .output-panel{ height:var(--output-height); }
  .output-content{ flex:1; background:#120A22; }
  /* Ensure first characters are not hidden by the resizer: add safe left padding */
  .console-output{ width:100%; height:100%; padding:12px 12px 12px 18px; color:#E7E3FF; font-family:'Consolas','Monaco','Courier New',monospace; font-size:14px; line-height:1.6; overflow-y:auto; white-space:pre-wrap; background:#120A22; }
  .console-output .ok{ color:#86efac; }
  .console-output .err{ color:#fca5a5; }
  .welcome-message{ display:flex; align-items:center; gap:.6rem; padding:10px; background:rgba(255,255,255,.05); border-radius:8px; border-left:3px solid var(--success); color:#86efac; }

  /* Challenge */
  .challenge-header{ display:flex; align-items:center; gap:.6rem; margin-bottom:.6rem; }
  .expected-output{ background:#F5EEFF; border-radius:12px; padding:12px; border:1px solid var(--border); }
  .output-label{ font-size:.9rem; font-weight:800; color:var(--muted); margin-bottom:.4rem; }
  .output-preview{ background:#fff; border:2px solid var(--success); border-radius:8px; padding:.75rem; font-family:'Consolas','Monaco','Courier New',monospace; font-size:14px; color:var(--ink); font-weight:700; }

  /* Floating bits */
  .floating-elements{ position:absolute; inset:0; pointer-events:none; z-index:0; }
  .floating-coin,.floating-gem,.floating-star{ position:absolute; font-size:20px; opacity:.35; animation:float 8s ease-in-out infinite }
  .floating-gem{ animation-delay:1.4s } .floating-star{ animation-delay:2.8s }
  @keyframes float{0%,100%{transform:translateY(0) rotate(0)}25%{transform:translateY(-14px) rotate(90deg)}50%{transform:translateY(0) rotate(180deg)}75%{transform:translateY(-10px) rotate(270deg)}}

  /* Buttons (imported look) */
  .btn-game, .btn-level{ background:linear-gradient(45deg,var(--primary),var(--accent)); color:#fff; border:none; font-weight:800; letter-spacing:.4px; border-radius:12px; padding:10px 18px; text-transform:uppercase; box-shadow:0 12px 26px rgba(185,103,255,.25); position:relative; overflow:hidden; transition:.22s ease; }
  .btn-game:hover, .btn-level:hover{ transform:translateY(-2px); color:#fff; box-shadow:0 14px 32px rgba(185,103,255,.38); }

  /* Responsive */
  @media (max-width: 1024px){ .content-wrap{ width:min(1100px,94vw); } .console-header{ flex-direction:column; gap:10px; align-items:flex-start; } }
  @media (max-width: 768px){
    .stage-title{ font-size:1.6rem; }
    .content-wrap{ width:min(1000px,94vw); margin:12px auto 22px; }
    .workspace-container.horizontal{ flex-direction:column; height:clamp(520px,65vh,72vh); }
    .workspace-container.horizontal .editor-panel{ width:100%; height:52%; border-right:none; border-bottom:1px solid var(--border); }
    .workspace-container.horizontal .output-panel{ width:100%; height:48%; }
    .workspace-container.horizontal .resize-handle{ width:100%; height:4px; cursor:ns-resize; }
    .workspace-container.horizontal .resize-line{ width:20px; height:2px; }
  }
html, body { height:100%; }
body{
  margin:0;
  background:linear-gradient(45deg,var(--bg-start),var(--bg-end));
  color:#fff;
}

/* Use Orbitron only inside this page’s content, not the global header/footer */
.game-viewport{
  font-family:'Orbitron','Arial',sans-serif;
}
  /* Scrollbars (only local elements) */
  .console-output::-webkit-scrollbar{ width:8px; } .console-output::-webkit-scrollbar-thumb{ background:#3B2D5F; border-radius:6px; }
</style>

</x-app-layout>
