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
  <div class="lesson-scope">
    <div class="game-header-container">
      <div class="lesson-container">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="stage-icon-container me-3"></div>
            <div>
              <h2 class="stage-title mb-0">🎮 {{ $level->title ?? 'Level Instructions' }}</h2>
            </div>
          </div>
          <a href="{{ route('stages.show', $level->stage_id) }}" class="btn btn-back-to-map">
            <i class="fas fa-map me-2"></i> World Map
          </a>
        </div>
      </div>
    </div>
  </div>
</x-slot>

<style>
/* ===== Scoped purple theme ===== */
.lesson-scope{
  --ink:#F7F4FF; --muted:#D9D2F5; --dim:#BDADEB;
  --bg0:#0C0720; --bg1:#1A1036; --bg2:#221551; --bg3:#2C1B6E;
  --p50:#F5ECFF; --p100:#EBDAFF; --p200:#D6B8FF; --p300:#C396FF;
  --p400:#AE75FF; --p500:#9A53FF; --p600:#7C39E6; --p700:#632FBA; --p800:#4A248D; --p900:#311A61;
  --a:#B967FF; --b:#7A2EA5; --g:#19C37D; --warn:#FFB020; --err:#FF5A7A;
  --ring: rgba(186,160,255,.55);
  --shadow: 0 12px 34px rgba(28,0,65,.35), 0 0 0 1px rgba(186,160,255,.10);
  --radius:14px; --radius-lg:18px;
}

/* Background + container */
.lesson-scope{background: radial-gradient(900px 600px at 10% -5%, rgba(186,160,255,.12), transparent 55%),
                         radial-gradient(900px 600px at 110% 0%, rgba(148,87,235,.14), transparent 55%),
                         linear-gradient(45deg,var(--bg0),var(--bg1) 40%,var(--bg2) 75%,var(--bg0));
              color:var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
              -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale}
.lesson-scope .lesson-container{max-width:1100px;margin:0 auto;padding:0 16px}

/* Header band */
.lesson-scope .game-header-container{background:linear-gradient(135deg,var(--bg1),var(--bg2));border-bottom:1px solid rgba(186,160,255,.25);padding:14px 0}
.lesson-scope .stage-title{font-weight:900;letter-spacing:.2px;background:linear-gradient(45deg,#fff,#E6D8FF);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.lesson-scope .btn-back-to-map{background:linear-gradient(135deg,var(--p500),var(--p700));
  color:#fff;border:0;padding:10px 16px;border-radius:12px;font-weight:800;box-shadow:var(--shadow);text-decoration:none;display:inline-flex;gap:.55rem;align-items:center}
.lesson-scope .btn-back-to-map:hover{filter:brightness(1.06);transform:translateY(-1px)}
.lesson-scope .btn-back-to-map:focus-visible{outline:2px solid var(--ring);outline-offset:2px}

/* Bands */
.lesson-scope .full-bleed{margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);width:100vw}
.lesson-scope .band{background:linear-gradient(135deg, rgba(34,21,81,.95), rgba(22,11,56,.96));border-top:1px solid rgba(186,160,255,.18);border-bottom:1px solid rgba(186,160,255,.18)}
.lesson-scope .band-inner{min-height:calc(100vh - 80px);display:flex;flex-direction:column}
.lesson-scope .band-body{padding:22px 0}

/* Cards / sections */
.lesson-scope .section{background:linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
  border:1px solid rgba(186,160,255,.25); border-radius: var(--radius); padding:18px; box-shadow:var(--shadow); margin-bottom:16px}
.lesson-scope .section h3{margin:0 0 .75rem 0; font-weight:900; display:flex; align-items:center; gap:10px}
.lesson-scope .chip{font-size:.78rem;padding:3px 8px;border-radius:999px;border:1px solid rgba(186,160,255,.30);color:var(--ink);background:rgba(186,160,255,.10)}

/* Step viewer (Instructions) */
.step-viewer{display:flex;flex-direction:column;gap:14px}
.step-surface{background:rgba(255,255,255,.05);border:1px solid rgba(186,160,255,.25);border-radius:var(--radius);padding:16px;min-height:140px}
.step-text{white-space:pre-wrap;line-height:1.7;color:var(--p100)}
.step-controls{display:flex;align-items:center;justify-content:space-between;gap:10px}
.step-controls .left, .step-controls .right{display:flex;gap:8px;align-items:center}
.btn-ghost, .btn-solid{
  appearance:none;background:rgba(186,160,255,.12);color:#fff;border:1px solid rgba(186,160,255,.35);
  padding:10px 14px;border-radius:12px;font-weight:800;cursor:pointer;transition:transform .1s ease, filter .15s ease;display:inline-flex;gap:.5rem;align-items:center;text-decoration:none
}
.btn-ghost:hover{transform:translateY(-1px);filter:brightness(1.06)}
.btn-ghost:disabled{opacity:.5;cursor:not-allowed}
.btn-solid{background:linear-gradient(135deg,var(--p500),var(--p700));border:0}
.btn-solid:hover{transform:translateY(-1px);filter:brightness(1.06)}

.progress-dots{display:flex;gap:6px;align-items:center}
.progress-dots .dot{width:10px;height:10px;border-radius:999px;background:rgba(186,160,255,.25);border:1px solid rgba(186,160,255,.35)}
.progress-dots .dot.active{background:var(--p500);box-shadow:0 0 0 4px rgba(154,83,255,.15)}

/* At-a-glance */
.grid-2{display:grid;gap:14px;grid-template-columns:1fr}
@media(min-width:860px){.grid-2{grid-template-columns:1.1fr .9fr}}
.cardish{background:rgba(255,255,255,.05);border:1px solid rgba(186,160,255,.2);border-radius:var(--radius);padding:14px}
.list-plain{margin:0;padding-left:1.2rem;color:var(--p100)}
.callout{border-radius:12px;padding:12px 14px;border:1px solid rgba(186,160,255,.35);background:rgba(186,160,255,.10);color:var(--p50)}

/* Python console */
.py-console{background:linear-gradient(180deg, rgba(12,7,32,.9), rgba(12,7,32,.92));border:1px solid rgba(186,160,255,.25);
  border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow);margin:20px 0}
.py-head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 16px;background:linear-gradient(90deg, rgba(122,46,165,.4), rgba(154,83,255,.25));border-bottom:1px solid rgba(186,160,255,.25)}
.py-title{font-weight:900}
.py-actions{display:flex;gap:8px;flex-wrap:wrap}
.btn-console{appearance:none;background:rgba(186,160,255,.12);color:#fff;border:1px solid rgba(186,160,255,.35);padding:10px 12px;border-radius:10px;font-weight:800;cursor:pointer;transition:.1s;display:inline-flex;gap:.5rem;align-items:center}
.btn-console:hover{transform:translateY(-1px)}
.btn-console:disabled{opacity:.55;cursor:not-allowed}
.io .code{display:flex;gap:10px;align-items:stretch;padding:14px}
.io textarea#code{width:100%;min-height:190px;background:#0B0920;color:#EDE6FF;border:1px solid rgba(186,160,255,.25);
  outline:none;border-radius:12px;padding:12px;line-height:1.55;font-family: ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.96rem;resize:vertical}
.io textarea#code:focus{box-shadow:0 0 0 2px var(--ring)}
.copy-right{display:flex;flex-direction:column;gap:8px}
.out{width:100%;border-top:1px solid rgba(186,160,255,.25);background:#0B0A1E;color:#F3EEFF;padding:14px;max-height:280px;overflow:auto;white-space:pre-wrap;font-family: ui-monospace,SFMono-Regular,Menlo,Consolas,monospace}
.ok{color:#BFFFEA} .err{color:#FFD1DD}

.console-nav{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 14px;border-top:1px solid rgba(186,160,255,.2);background:rgba(186,160,255,.06)}
.console-meta{display:flex;align-items:center;gap:8px}
.badge{display:inline-block;font-size:.78rem;padding:4px 8px;border-radius:999px;background:rgba(154,83,255,.18);border:1px solid rgba(154,83,255,.35);color:#fff}

/* Footer */
.lesson-scope .band-footer{padding:18px 0 26px;text-align:center}
.lesson-scope .band-footer a{margin:0 6px}
</style>

<div class="page-wrap lesson-scope">
  <div class="full-bleed band">
    <div class="band-inner">

      <div class="lesson-container">
        <div class="band-body">

          {{-- At a glance --}}
          @if($estimatedTime || !empty($goals) || !empty($prerequisites))
          <div class="grid-2">
            <div class="cardish">
              <h3>🧭 At a glance</h3>
              <div class="list-plain" style="padding-left:0">
                @if($estimatedTime)<div class="badge">⏱ Estimated: {{ $estimatedTime }}</div>@endif
              </div>
              @if(!empty($goals))
                <div class="section" style="margin-top:12px">
                  <strong>Learning goals</strong>
                  <ul class="list-plain">
                    @foreach($goals as $g)<li>{{ $g }}</li>@endforeach
                  </ul>
                </div>
              @endif
              @if(!empty($prerequisites))
                <div class="section" style="margin-top:12px">
                  <strong>Prerequisites</strong>
                  <ul class="list-plain">
                    @foreach($prerequisites as $p)<li>{{ $p }}</li>@endforeach
                  </ul>
                </div>
              @endif
            </div>
            <div class="cardish">
              <h3>🚀 Flow</h3>
              <ol class="list-plain">
                <li>Read each instruction step and click <strong>Next</strong>.</li>
                <li>Use the console’s <strong>Next/Back</strong> to iterate examples.</li>
                <li>Click <strong>Check Answer</strong> to compare output.</li>
                <li>Start the level activity to earn stars.</li>
              </ol>
              <div class="callout" style="margin-top:10px">Tip: <em>Copy &amp; Run</em> on an example will paste and execute it.</div>
            </div>
          </div>
          @endif

          {{-- Instructions: Step Viewer --}}
          @if(count($instructionSteps) > 0)
          <div class="section" id="instructionsSection"
               data-total="{{ count($instructionSteps) }}">
            <h3>📋 Instructions <span class="chip">Step-by-Step</span></h3>

            {{-- steps rendered as hidden blocks; JS flips visibility --}}
            <div class="step-viewer" id="instrViewer">
              @foreach($instructionSteps as $i => $step)
                <div class="step-surface step"
                     data-step="{{ $i }}"
                     style="{{ $i === 0 ? '' : 'display:none' }}">
                  <div class="step-text">{!! nl2br(e($step)) !!}</div>
                </div>
              @endforeach

              <div class="step-controls">
                <div class="left">
                  <button class="btn-ghost" id="instrPrev" disabled>⬅ Back</button>
                </div>
                <div class="progress-dots" id="instrDots">
                  @for($i=0;$i<count($instructionSteps);$i++)
                    <span class="dot {{ $i===0?'active':'' }}" data-dot="{{ $i }}"></span>
                  @endfor
                </div>
                <div class="right">
                  <button class="btn-solid" id="instrNext">{{ count($instructionSteps) > 1 ? 'Next ➡' : 'Done' }}</button>
                </div>
              </div>
            </div>
          </div>
          @endif

          {{-- Python console with Example stepper --}}
          <div class="py-console" id="consoleBox"
               data-example-count="{{ count($examples) }}">
            <div class="py-head">
              <div class="py-title">
                🐍 Python Console <span id="status" style="font-weight:700;opacity:.85">(loading…)</span>
              </div>
              <div class="py-actions">
                <button class="btn-console" id="btnCopyMain">📋 Copy</button>
                <button class="btn-console" id="btnRun">▶ Run</button>
                <button class="btn-console" id="btnCheck">✅ Check Answer</button>
                <button class="btn-console" id="btnClear">🗑 Clear</button>
              </div>
            </div>

            {{-- Example metadata (title / explain) --}}
            <div class="console-nav" id="consoleNav" style="{{ count($examples) ? '' : 'display:none' }}">
              <div class="console-meta">
                <span class="badge" id="exIndexBadge">Example 1 / {{ max(1,count($examples)) }}</span>
                <strong id="exTitle" style="line-height:1.2"></strong>
              </div>
              <div class="right">
                <button class="btn-ghost" id="exPrev" {{ count($examples) > 1 ? '' : 'disabled' }}>⬅ Back</button>
                <button class="btn-solid" id="exNext" {{ count($examples) > 1 ? '' : 'disabled' }}>Next ➡</button>
              </div>
            </div>

            <div class="cardish" id="exExplainWrap" style="display:none;margin:10px 14px 0">
              <div id="exExplain" style="color:var(--p100)"></div>
            </div>

            <div class="io">
              <div class="code">
                <textarea id="code" placeholder='# Type your Python code here
print("Hello, World!")'></textarea>
                <div class="copy-right">
                  <button class="btn-console" id="btnPasteFromClipboard">📥 Paste</button>
                  <button class="btn-console" id="btnCopyToClipboard">📤 Copy</button>
                </div>
              </div>
            </div>
            <div class="out" id="output"> Type code above and click "Run".</div>

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

          {{-- Optional single expected output (fallback display if no examples) --}}
          @if(!$examples && $globalExpected)
            <div class="section" id="challenge" data-expected="{{ $globalExpected }}">
              <strong>Expected Output:</strong> <code>{{ $globalExpected }}</code>
            </div>
          @else
            <div class="section" id="challenge" data-expected=""></div>
          @endif

        </div>
      </div>

      <div class="band-footer">
        <div class="lesson-container">
          <a href="{{ route('stages.show', $level->stage_id) }}" class="btn btn-back-to-map">🗺 Back to World Map</a>
          <a href="{{ route('levels.show', $level) }}" class="btn-ghost" style="border-color:rgba(186,160,255,.4)">Start Level</a>
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
      const steps = Array.from(instrSection.querySelectorAll('.step-surface.step'));
      const dots  = Array.from(document.querySelectorAll('#instrDots .dot'));
      const prev  = document.getElementById('instrPrev');
      const next  = document.getElementById('instrNext');

      function renderInstr(){
        steps.forEach((el,i)=> el.style.display = (i===idx?'block':'none'));
        dots.forEach((d,i)=> d.classList.toggle('active', i===idx));
        prev.disabled = idx===0;
        next.textContent = (idx===total-1) ? 'Finish ✅' : 'Next ➡';
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
</x-app-layout>
