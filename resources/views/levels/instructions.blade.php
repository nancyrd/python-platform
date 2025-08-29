<x-app-layout>
@php
    // --- Instructions + content normalization ---
    $instructions = $level->instructions;
    $content = $level->content;

    if (is_string($content)) {
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) $content = $decoded;
    }

    if (empty($instructions) && is_array($content)) {
        $instructions = $content['instructions'] ?? ($content['intro'] ?? null);
    }

    // Examples
    $examples = is_array($content) ? ($content['examples'] ?? []) : [];

    // NEW: optional meta pulled from content
    $estimatedTime  = is_array($content) ? ($content['estimated_time'] ?? null) : null;
    $goals          = is_array($content) ? ($content['goals'] ?? []) : [];
    $prerequisites  = is_array($content) ? ($content['prerequisites'] ?? []) : [];
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

{{-- FULL-BLEED STYLES (scoped so it won’t affect your global navbar) --}}
<style>
/* ====== LESSON SCOPE (won’t touch your global nav) ====== */
.lesson-scope{
  /* Theme tokens for this view only */
  --deep:#0c1026; --cosmic:#2b1b57; --space:#14245a; --dark:#0a1028;
  --nblue:#00b3ff; --npurple:#b967ff; --green:#00ff88; --warn:#ff9500; --err:#ff3366;
  --ink:#f6f7ff; --muted:rgba(238,240,255,.86); --dim:rgba(238,240,255,.65);
  --surface-1: rgba(17,22,54,.72);
  --surface-2: rgba(12,18,46,.55);
  --surface-3: rgba(255,255,255,.05);
  --border-1: rgba(255,255,255,.14);
  --border-2: rgba(255,255,255,.08);
  --ring: rgba(0,179,255,.55);
  --shadow: 0 10px 28px rgba(0,0,0,.35);
  --radius: 14px; --radius-lg: 18px; --headerH: 80px;

  /* Unified spacing & type scale */
  --space-sm: 12px; --space-md: 18px; --space-lg: 24px; --space-xl: 32px;
  --font-sm: 0.9rem; --font-md: 1rem; --font-lg: 1.2rem; --font-xl: 1.6rem; --font-xxl: 2rem;
}

/* Shared inner container to keep header / body / footer same width */
.lesson-scope .lesson-container{
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 16px;
}

/* Background + base text only inside the lesson area */
.lesson-scope{
  background:
    radial-gradient(900px 600px at 15% -10%, rgba(0,179,255,.10), transparent 55%),
    radial-gradient(900px 600px at 110% 0%, rgba(185,103,255,.12), transparent 55%),
    linear-gradient(45deg,var(--deep),var(--cosmic) 40%,var(--space) 75%,var(--dark));
  color:var(--ink);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  line-height:1.5;
  font-family: ui-sans-serif, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif;
}

/* Header band (scoped) */
.lesson-scope .game-header-container{
  background:linear-gradient(135deg,var(--deep),var(--cosmic));
  border-bottom:1px solid var(--border-1);
  padding: var(--space-md) 0; /* vertical; horizontal comes from .lesson-container */
}
.lesson-scope .stage-title{
  font-size: var(--font-xl); font-weight:900;
  background:linear-gradient(45deg,#bfefff, #e2ccff);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  letter-spacing:.2px;
}
.lesson-scope .btn-back-to-map{
  background:linear-gradient(135deg,var(--nblue),var(--npurple));
  color:#fff; border:0; padding:10px 16px; border-radius:10px; font-weight:800;
  box-shadow: var(--shadow);
  transition: transform .12s ease, box-shadow .2s ease, opacity .12s ease;
  text-decoration:none;
  display:inline-flex; align-items:center; gap:.5rem;
}
.lesson-scope .btn-back-to-map:hover{ transform: translateY(-1px); filter: brightness(1.05); }
.lesson-scope .btn-back-to-map:focus-visible{ outline:2px solid var(--ring); outline-offset:2px; }

/* Layout bands */
.lesson-scope .full-bleed { margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw); width:100vw; }
.lesson-scope .band{
  background:linear-gradient(135deg, rgba(26,6,54,.94), rgba(35,21,73,.96));
  border-top:1px solid var(--border-1); border-bottom:1px solid var(--border-1);
}
.lesson-scope .band-inner{ min-height: calc(100vh - var(--headerH)); display:flex; flex-direction:column; }
.lesson-scope .band-header{
  background:linear-gradient(135deg, rgba(0,179,255,.18), rgba(185,103,255,.18));
  padding:22px 24px; text-align:center; border-bottom:1px solid var(--border-2);
}
.lesson-scope .band-title{ margin:0; font-size:2.05rem; font-weight:900; }

/* Use lesson-container for width; band-body just handles vertical spacing */
.lesson-scope .band-body{
  padding: var(--space-lg) 0;
  font-size: var(--font-md);
  line-height: 1.6;
}

/* Sections & cards */
.lesson-scope .section{
  background: var(--surface-1);
  border:1px solid var(--border-1);
  border-radius: var(--radius);
  padding:18px; margin-bottom:18px; box-shadow: var(--shadow);
}
.lesson-scope .section h3{
  color:#e9ecff; margin:0 0 .8rem 0; font-weight:800; display:flex; align-items:center; gap:8px;
}
.lesson-scope .section h3 .chip{
  font-size:.78rem; padding:3px 8px; border-radius:999px; border:1px solid var(--border-2); color:var(--ink);
  background:rgba(255,255,255,.05);
}
.lesson-scope .cardish{
  background: var(--surface-2);
  border:1px solid var(--border-2);
  border-radius: var(--radius);
  padding:14px;
}
.lesson-scope .instructions-content{ white-space:pre-wrap; line-height:1.7; color:var(--muted); }
.lesson-scope .instructions-content code{
  background: rgba(0,255,136,.14); color:#aafad9; padding:2px 6px; border-radius:6px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
}

/* Panels */
.lesson-scope .glance{ display:grid; gap:12px; grid-template-columns:1fr; margin-bottom:18px; }
@media(min-width:820px){ .lesson-scope .glance{ grid-template-columns: 1.2fr 1fr; } }
.lesson-scope .list-plain{ margin:0; padding-left:1.15rem; }
.lesson-scope .list-plain li{ margin:.22rem 0; color:var(--muted); }

/* Stepper */
.lesson-scope .stepper{ counter-reset: step; display:grid; gap:12px; }
.lesson-scope .step{
  display:flex; gap:12px; align-items:flex-start;
  background: var(--surface-3); border:1px solid var(--border-2);
  border-radius:12px; padding:12px;
}
.lesson-scope .step::before{
  counter-increment: step; content: counter(step);
  min-width:28px; height:28px; display:flex; align-items:center; justify-content:center;
  border-radius:999px; font-weight:900; background:linear-gradient(135deg,#9be4ff,#e0bcff); color:#0b0f26;
}

/* Callouts */
.lesson-scope .callout{ border-radius:12px; padding:12px 14px; border:1px solid; }
.lesson-scope .callout.info{ background:rgba(0,179,255,.10); border-color:rgba(0,179,255,.42); color:#dff5ff; }
.lesson-scope .callout.warn{ background:rgba(255,149,0,.10); border-color:rgba(255,149,0,.45); color:#ffe9cc; }
.lesson-scope .callout.good{ background:rgba(0,255,136,.10); border-color:rgba(0,255,136,.45); color:#d3ffe9; }

/* Console */
.lesson-scope .py-console{
  background:#0a1028; border:1px solid var(--border-1);
  border-radius: var(--radius-lg); overflow:hidden; margin:20px 0; box-shadow: var(--shadow);
}
.lesson-scope .py-head{
  display:flex; align-items:center; justify-content:space-between; gap:10px; padding:12px 16px;
  background:linear-gradient(135deg, rgba(5,217,232,.25), rgba(0,179,255,.25));
  border-bottom:1px solid var(--border-1);
}
.lesson-scope .py-title{ font-weight:800; }
.lesson-scope .py-actions{ display:flex; gap:8px; flex-wrap:wrap; }

/* Button look for both <button> and <a> when used as console buttons */
.lesson-scope .btn-console,
.lesson-scope a.btn-console{
  appearance:none; -webkit-appearance:none;
  background: rgba(255,255,255,.14); color:#f8f9ff; border:1px solid var(--border-1);
  padding:10px 14px; border-radius:10px; font-weight:700; cursor:pointer;
  transition: transform .12s ease, box-shadow .2s ease, background .12s ease, opacity .12s ease;
  text-decoration:none; display:inline-flex; align-items:center; gap:.5rem;
}
.lesson-scope .btn-console:hover{ transform: translateY(-1px); background: rgba(255,255,255,.18); }
.lesson-scope .btn-console:active{ transform: translateY(0); }
.lesson-scope .btn-console:disabled{ opacity:.55; cursor:not-allowed; }
.lesson-scope .btn-console:focus-visible{ outline:2px solid var(--ring); outline-offset:2px; }

.lesson-scope .io .code{ padding: 14px; display:flex; gap:10px; align-items:stretch; }
.lesson-scope .io .code textarea{
  width:100%; min-height:170px; background:#070c22; color:#bfffe9; border:1px solid var(--border-2);
  outline:none; box-shadow: inset 0 0 0 1px rgba(255,255,255,.03);
  border-radius:10px; padding:12px; line-height:1.55;
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size:.96rem; resize:vertical;
}
.lesson-scope .io .code textarea:focus{ box-shadow: 0 0 0 2px var(--ring); }

.lesson-scope .copy-right{ display:flex; flex-direction:column; gap:8px; }
.lesson-scope .out{
  width:100%; border-top:1px solid var(--border-2); background:#060b1c;
  padding:14px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  max-height:280px; overflow:auto; white-space:pre-wrap; color:#e7efff;
}
.lesson-scope .ok{ color:#b2ffe6; } .lesson-scope .err{ color:#ffcbd7; }

/* Footer (uses lesson-container) */
.lesson-scope .band-footer{
  font-size: var(--font-sm);
  padding: var(--space-md) 0 var(--space-lg);
}
.lesson-scope .band-footer .lesson-container{ display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }
.lesson-scope .band-footer a{ text-decoration:none; }

/* Collapsible */
.lesson-scope .collapse-wrap.collapsed .collapse-body{
  max-height: 140px; overflow: hidden;
  mask-image: linear-gradient(#000 70%, transparent);
  -webkit-mask-image: linear-gradient(#000 70%, transparent);
}
.lesson-scope .collapse-toggle{ margin-top:10px; }

/* Scrollbars (WebKit) */
.lesson-scope .out::-webkit-scrollbar,
.lesson-scope textarea::-webkit-scrollbar{ width:10px; height:10px; }
.lesson-scope .out::-webkit-scrollbar-thumb,
.lesson-scope textarea::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.18); border-radius:10px; }
.lesson-scope .out::-webkit-scrollbar-thumb:hover,
.lesson-scope textarea::-webkit-scrollbar-thumb:hover{ background:rgba(255,255,255,.26); }

/* MOBILE ADJUSTMENTS */
@media (max-width: 768px) {
  .lesson-scope .game-header-container { padding: var(--space-sm) 0; }
  .lesson-scope .lesson-container { padding: 0 12px; }

  .lesson-scope .stage-title { font-size: var(--font-lg); }
  .lesson-scope .band-body { padding: var(--space-md) 0; font-size: var(--font-sm); }
  .lesson-scope .band-footer { padding: var(--space-md) 0 var(--space-md); }
}

/* DESKTOP LARGER */
@media (min-width: 1200px) {
  .lesson-scope .stage-title { font-size: var(--font-xxl); }
  .lesson-scope .band-body { font-size: var(--font-lg); }
}
</style>

<div class="page-wrap lesson-scope">
  <div class="full-bleed band">
    <div class="band-inner">

      <div class="lesson-container">
        <div class="band-body">

          {{-- NEW: At a glance --}}
          @if($estimatedTime || !empty($goals) || !empty($prerequisites))
            <div class="glance">
              <div class="cardish">
                <h3>🧭 At a glance</h3>
                <div class="stepper">
                  @if($estimatedTime)
                    <div class="step"><div><strong>Estimated time:</strong> {{ $estimatedTime }}</div></div>
                  @endif
                  @if(!empty($goals))
                    <div class="step">
                      <div>
                        <strong>Learning goals</strong>
                        <ul class="list-plain">
                          @foreach($goals as $g)<li>{{ $g }}</li>@endforeach
                        </ul>
                      </div>
                    </div>
                  @endif
                  @if(!empty($prerequisites))
                    <div class="step">
                      <div>
                        <strong>Prerequisites</strong>
                        <ul class="list-plain">
                          @foreach($prerequisites as $p)<li>{{ $p }}</li>@endforeach
                        </ul>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
              <div class="cardish">
                <h3>🚀 What you’ll do</h3>
                <ol class="list-plain" style="padding-left:1.1rem;">
                  <li>Read the short lesson below.</li>
                  <li>Copy an example into the Python console and run it.</li>
                  <li>Compare your output with the expected result.</li>
                  <li>Start the level activity to earn stars.</li>
                </ol>
                <div class="callout info" style="margin-top:10px;">Tip: You can use <em>Copy &amp; Run</em> on any example to auto-paste and execute.</div>
              </div>
            </div>
          @endif

          {{-- Instructions (collapsible if long) --}}
          @if($instructions)
            @php $isLong = mb_strlen($instructions) > 420; @endphp
            <div class="section collapse-wrap {{ $isLong ? 'collapsed' : '' }}" id="lessonBox">
              <h3>📋 Instructions <span class="chip">Lesson</span></h3>
              <div class="instructions-content collapse-body">{!! nl2br(e($instructions)) !!}</div>
              @if($isLong)
                <button class="btn-console collapse-toggle" data-target="#lessonBox">Show more</button>
              @endif
            </div>
          @endif

          {{-- Examples --}}
          @if(!empty($examples))
            <div class="section">
              <h3>🧪 Try These Examples <span class="chip">Copy → Run</span></h3>
              <div style="display:grid; gap:14px;">
                @foreach($examples as $i => $ex)
                  @php
                    $exTitle   = $ex['title'] ?? ('Example '.($i+1));
                    $exCode    = $ex['code']  ?? '';
                    $exExplain = $ex['explain'] ?? null;
                    $exExpected= $ex['expected_output'] ?? null;
                  @endphp
                  <div class="cardish">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
                      <h4 style="margin:0; font-weight:800;">{{ $exTitle }}</h4>
                      <div>
                        <button class="btn-console" data-action="copy" data-code="{{ base64_encode($exCode) }}">📋 Copy to Console</button>
                        <button class="btn-console" data-action="copyrun" data-code="{{ base64_encode($exCode) }}">▶ Copy & Run</button>
                        @if($exExpected)
                          <button class="btn-console" data-action="expected" data-expected="{{ e($exExpected) }}">👀 Expected</button>
                        @endif
                      </div>
                    </div>
                    @if($exExplain)
                      <div style="margin:.5rem 0 .25rem; color:var(--muted);">{{ $exExplain }}</div>
                    @endif
                    <pre class="mt-2" style="white-space:pre-wrap;"><code>{{ $exCode }}</code></pre>
                  </div>
                @endforeach
              </div>
              <div class="callout good" style="margin-top:12px;">
                Pro tip: Edit the code after running it. Try changing numbers or text and see how the output changes.
              </div>
            </div>
          @endif

          {{-- Hints --}}
          @php
            $hints = $level->hints;
            if (empty($hints) && is_array($content)) $hints = $content['hints'] ?? null;
            if (is_string($hints)) {
                $decodedHints = json_decode($hints, true);
                if (json_last_error() === JSON_ERROR_NONE) $hints = $decodedHints;
            }
          @endphp

          {{-- Python console --}}
          <div class="py-console">
            <div class="py-head">
              <div class="py-title">🐍 Python Console <span id="status" style="font-weight:600;">(loading…)</span></div>
              <div class="py-actions">
                <button class="btn-console" id="btnCopyMain">📋 Copy</button>
                <button class="btn-console" id="btnRun">▶ Run</button>
                <button class="btn-console" id="btnCheck">✅ Check Answer</button>
                <button class="btn-console" id="btnClear">🗑 Clear</button>
              </div>
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
          </div>

          {{-- Optional expected output from content --}}
          @php $expectedOutput = is_array($content) ? ($content['expected_output'] ?? null) : null; @endphp
          @if($expectedOutput)
            <div class="section" id="challenge" data-expected="{{ $expectedOutput }}">
              <strong>Expected Output:</strong> <code>{{ $expectedOutput }}</code>
            </div>
          @endif

        </div> {{-- /.band-body --}}
      </div> {{-- /.lesson-container --}}

      <div class="band-footer">
        <div class="lesson-container">
          <a href="{{ route('stages.show', $level->stage_id) }}" class="btn btn-back-to-map">🗺 Back to World Map</a>
          <a href="{{ route('levels.show', $level) }}" class="btn btn-console" style="border-color:var(--nblue)">Start Level</a>
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

  function setStatus(s){
    const el = document.getElementById('status');
    if (el) el.textContent = `(${s})`;
  }
  function clearOut(){
    const el = document.getElementById('output');
    if (el) el.textContent = '';
  }
  function appendOut(s){
    const el = document.getElementById('output'); if (!el) return;
    const span = document.createElement('span'); span.className = 'ok'; span.textContent = s;
    el.appendChild(span); el.scrollTop = el.scrollHeight;
  }
  function appendErr(s){
    const el = document.getElementById('output'); if (!el) return;
    const span = document.createElement('span'); span.className = 'err'; span.textContent = s;
    el.appendChild(span); el.scrollTop = el.scrollHeight;
  }

  async function runCode(code){
    clearOut();
    if(!pyReady){
      appendErr("Python runtime is not ready yet.");
      return { out:'', err:'not-ready' };
    }
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

  document.addEventListener('DOMContentLoaded', () => {
    const codeTA = document.getElementById('code');
    const output = document.getElementById('output');

    // Keyboard shortcut: Ctrl/Cmd + Enter to Run
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        const runBtn = document.getElementById('btnRun');
        if (runBtn) runBtn.click();
      }
    });

    // Single delegated click handler for ALL buttons
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('button');
      if (!btn) return;

      // --- Top console actions by ID ---
      if (btn.id === 'btnRun') {
        e.preventDefault();
        btn.disabled = true; setStatus('running');
        const { err } = await runCode(codeTA.value);
        setStatus(err ? 'error' : 'ready');
        btn.disabled = false;
        return;
      }

      if (btn.id === 'btnCheck') {
        e.preventDefault();
        const expected = (document.getElementById('challenge')?.dataset.expected || '').trim();
        btn.disabled = true; setStatus('checking');
        const { out } = await runCode(codeTA.value);
        const clean = (out || '').trim();
        if (expected) {
          if (clean === expected) appendOut("\n✅ Correct! Great job!");
          else appendErr(`\n❌ Not quite. Expected: "${expected}"\nYour output: "${clean}"`);
        } else {
          appendOut("\n✓ Code executed successfully!");
        }
        setStatus('ready'); btn.disabled = false;
        return;
      }

      if (btn.id === 'btnClear') {
        e.preventDefault();
        codeTA.value = '';
        output.textContent = ' Type code above and click "Run".';
        return;
      }

      if (btn.id === 'btnCopyMain' || btn.id === 'btnCopyToClipboard') {
        e.preventDefault();
        try {
          await navigator.clipboard.writeText(codeTA.value);
          appendOut("\n📋 Code copied to clipboard.");
        } catch {
          appendErr("\n⚠️ Could not copy (clipboard permission).");
        }
        return;
      }

      if (btn.id === 'btnPasteFromClipboard') {
        e.preventDefault();
        try {
          const t = await navigator.clipboard.readText();
          codeTA.value = t;
          output.textContent = ' Code pasted. Click "Run" or edit it.';
        } catch {
          appendErr("\n⚠️ Clipboard read blocked by browser.");
        }
        return;
      }

      // Collapsible toggles
      if (btn.classList.contains('collapse-toggle')) {
        e.preventDefault();
        const box = document.querySelector(btn.getAttribute('data-target'));
        if (!box) return;
        const nowCollapsed = box.classList.toggle('collapsed');
        btn.textContent = nowCollapsed ? 'Show more' : 'Show less';
        return;
      }

      // --- Example buttons by data-action ---
      const action = btn.getAttribute('data-action');
      if (!action) return;

      if (action === 'copy' || action === 'copyrun') {
        e.preventDefault();
        const b64  = btn.getAttribute('data-code') || '';
        const code = b64 ? atob(b64) : '';
        codeTA.value = code;
        output.textContent = ' Code pasted. Click "Run" or edit it.';
        if (action === 'copyrun') {
          btn.disabled = true; setStatus('running');
          const { err } = await runCode(codeTA.value);
          setStatus(err ? 'error' : 'ready');
          btn.disabled = false;
        }
        return;
      }

      if (action === 'expected') {
        e.preventDefault();
        const exp = btn.getAttribute('data-expected') || '';
        appendOut(`\n🧭 Expected output: ${exp}\n`);
        return;
      }
    });

    bootPython(); // initialize Python last
  });
</script>

</x-app-layout>
