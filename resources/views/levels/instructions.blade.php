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
@endphp

    <x-slot name="header">
        <div class="game-header-container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="stage-icon-container me-3">
                        <div class="stage-icon-wrapper">
                            <i class="fas fa-graduation-cap stage-icon"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="stage-title mb-0">🎮 {{ $level->title ?? 'Level Instructions' }}</h2>
                        <div class="stage-subtitle"><i class="fas fa-brain me-1"></i> Level Up Your Skills!</div>
                    </div>
                </div>
                <a href="{{ route('stages.show', $level->stage_id) }}" class="btn btn-back-to-map">
                    <i class="fas fa-map me-2"></i> World Map
                </a>
            </div>
        </div>
    </x-slot>

    {{-- FULL-BLEED STYLES --}}
    <style>
        :root{
            --deep:#1a0636; --cosmic:#4a1b6d; --space:#162b6f; --dark:#0a1028;
            --nblue:#00b3ff; --npurple:#b967ff; --green:#00ff88; --warn:#ff9500; --err:#ff3366;
            --headerH: 80px; /* approximate header height */
        }

        /* Make the layout background and fonts consistent */
        html, body { height: 100%; }
        body{
            margin:0;
            background:linear-gradient(45deg,var(--deep),var(--cosmic) 35%,var(--space) 70%,var(--dark));
            color:#fff; font-family:'Orbitron','Arial',sans-serif;
        }

        .game-header-container{
            background:linear-gradient(135deg,var(--deep),var(--cosmic));
            border-bottom:2px solid var(--npurple);
            padding:14px 18px;
        }
        .stage-icon-wrapper{
            width:56px;height:56px;border-radius:12px;border:1px solid var(--npurple);
            background:linear-gradient(145deg,var(--deep),var(--space)); display:flex;align-items:center;justify-content:center;
        }
        .stage-icon{ color:var(--green); font-size:22px; }
        .stage-title{
            font-size:1.6rem; font-weight:900;
            background:linear-gradient(45deg,var(--green),var(--npurple));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .stage-subtitle{ color:rgba(255,255,255,.75); }
        .btn-back-to-map{
            background:linear-gradient(135deg,var(--nblue),var(--npurple)); color:#fff; border:0;
            padding:10px 16px; border-radius:10px; font-weight:800;
        }

        /* ---- Full-bleed content (touch the edges) ---- */
        .page-wrap {
            /* remove any outer padding; take the whole width */
            padding: 0;
        }
        /* Full-bleed trick: pull to viewport edges even if parent has padding/containers */
        .full-bleed {
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            width: 100vw;
        }

        /* The main "card" becomes a full-width band with square corners */
        .band {
            background:linear-gradient(135deg, rgba(26,6,54,.96), rgba(74,27,109,.96));
            border-top:2px solid var(--npurple);
            border-bottom:2px solid var(--npurple);
            /* touch left/right edges thanks to full-bleed */
        }

        /* Make it fill (almost) the viewport height under the header */
        .band-inner {
            min-height: calc(100vh - var(--headerH));
            display: flex;
            flex-direction: column;
        }

        .band-header {
            background:linear-gradient(135deg,var(--nblue),var(--npurple));
            padding: 22px 24px;
            text-align: center;
        }
        .band-title { margin:0; font-size:2.1rem; font-weight:900; }

        .band-body {
            padding: 24px;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
        }

        /* Sections */
        .section{
            background: rgba(22,43,111,.6);
            border:1px solid rgba(185,103,255,.35);
            border-radius: 14px;
            padding: 18px 18px;
            margin-bottom: 18px;
        }
        .section h3{ color:var(--green); margin-bottom: .8rem; font-weight:800; }

        .instructions-content { white-space: pre-wrap; line-height: 1.6; color: rgba(255,255,255,.95); }
        .instructions-content code {
            background: rgba(0,255,136,0.15); color: #00ff88; padding: 2px 6px; border-radius: 4px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        .hint{
            background: rgba(255,149,0,.15);
            border:1px solid var(--warn);
            border-radius:12px; padding:14px; margin:14px 0;
        }

        /* Python console */
        .py-console{
            background:#0a1028; border:2px solid var(--nblue);
            border-radius:14px; overflow:hidden; margin:20px 0;
            box-shadow: 0 12px 30px rgba(0,0,0,.35);
        }
        .py-head{
            display:flex; align-items:center; justify-content:space-between; gap:10px; padding:12px 16px;
            background:linear-gradient(135deg,#05d9e8,var(--nblue));
        }
        .py-title{ font-weight:800; }
        .py-actions{ display:flex; gap:8px; flex-wrap:wrap; }
        .btn-console{
            background: rgba(255,255,255,.2); color:white; border:1px solid rgba(255,255,255,.35);
            padding:8px 12px; border-radius:8px; font-weight:600; cursor:pointer;
        }
        .btn-console:hover{ background: rgba(255,255,255,.3); }
        .io .code{ padding: 14px; }
        .io .code textarea{
            width:100%; min-height:140px; background:transparent; color:var(--green); border:none; outline:none;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size:.95rem; resize:vertical;
        }
        .out{
            width:100%; border-top:1px solid rgba(5,217,232,.25); background:#070c22;
            padding:14px; font-family: ui-monospace, monospace; max-height:260px; overflow:auto; white-space:pre-wrap;
        }
        .ok{ color:var(--green); } .err{ color:var(--err); }

        /* Footer actions pinned visually to the bottom spacing */
        .band-footer {
            padding: 0 24px 28px;
            display:flex; gap:10px; flex-wrap:wrap; justify-content:center;
        }

        @media (max-width: 768px){
            .band-title{ font-size:1.7rem; }
        }
    </style>

    <div class="page-wrap">
        <!-- FULL WIDTH STRIP -->
        <div class="full-bleed band">
            <div class="band-inner">
                <div class="band-header">
                    <h1 class="band-title">🎯 {{ $level->title ?? 'Python Quest' }}</h1>
                </div>

                <div class="band-body">
                    {{-- Instructions from DB/content --}}
                    @if($instructions)
                        <div class="section">
                            <h3>📋 Instructions</h3>
                            <div class="instructions-content">{!! nl2br(e($instructions)) !!}</div>
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
                    @if($hints)
                        <div class="hint">
                            <strong>💡 Tips:</strong>
                            @if(is_array($hints))
                                <ul style="margin:.5rem 0 0 1rem;">
                                    @foreach($hints as $hint)
                                        <li>{{ $hint }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div>{{ $hints }}</div>
                            @endif
                        </div>
                    @endif

                    {{-- Python console --}}
                    <div class="py-console">
                        <div class="py-head">
                            <div class="py-title">🐍 Python Console <span id="status" style="font-weight:600;">(loading…)</span></div>
                            <div class="py-actions">
                                <button class="btn-console" id="btnRun">▶ Run</button>
                                <button class="btn-console" id="btnCheck">✅ Check Answer</button>
                                <button class="btn-console" id="btnClear">🗑 Clear</button>
                            </div>
                        </div>
                        <div class="io">
                            <div class="code">
                                <textarea id="code" placeholder='# Type your Python code here
print("Hello, World!")'></textarea>
                            </div>
                        </div>
                        <div class="out" id="output">👋 Type code above and click "Run".</div>
                    </div>

                    {{-- Optional expected output from content --}}
                    @php $expectedOutput = is_array($content) ? ($content['expected_output'] ?? null) : null; @endphp
                    @if($expectedOutput)
                        <div class="section" id="challenge" data-expected="{{ $expectedOutput }}">
                            <strong>Expected Output:</strong> <code>{{ $expectedOutput }}</code>
                        </div>
                    @endif
                </div>

                <div class="band-footer">
                    <a href="{{ route('stages.show', $level->stage_id) }}" class="btn btn-back-to-map">🗺 Back to World Map</a>
                    <a href="{{ route('levels.show', $level) }}" class="btn btn-console" style="border-color:var(--nblue)">🚀 Start Level</a>
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
        function clearOut(){ const el = document.getElementById('output'); if (el) el.textContent = ''; }
        function appendOut(s){ const el = document.getElementById('output'); if (!el) return; const span=document.createElement('span'); span.className='ok'; span.textContent=s; el.appendChild(span); el.scrollTop=el.scrollHeight; }
        function appendErr(s){ const el = document.getElementById('output'); if (!el) return; const span=document.createElement('span'); span.className='err'; span.textContent=s; el.appendChild(span); el.scrollTop=el.scrollHeight; }

        async function runCode(code){
            clearOut();
            if(!pyReady){ appendErr("Python runtime is not ready yet."); return {out:'',err:'not-ready'}; }
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
                return {out: OUT, err: ERR};
            } catch(e) {
                appendErr("Error: " + (e.message || e));
                return {out:'', err:e.message || String(e)};
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const btnRun   = document.getElementById('btnRun');
            const btnCheck = document.getElementById('btnCheck');
            const btnClear = document.getElementById('btnClear');
            const codeTA   = document.getElementById('code');
            const output   = document.getElementById('output');

            if (btnRun) {
                btnRun.addEventListener('click', async () => {
                    btnRun.disabled = true; setStatus('running');
                    const {err} = await runCode(codeTA.value);
                    setStatus(err ? 'error' : 'ready');
                    btnRun.disabled = false;
                });
            }
            if (btnCheck) {
                btnCheck.addEventListener('click', async () => {
                    const expected = (document.getElementById('challenge')?.dataset.expected || '').trim();
                    btnCheck.disabled = true; setStatus('checking');
                    const {out} = await runCode(codeTA.value);
                    const clean = (out || '').trim();
                    if (expected) {
                        if (clean === expected) appendOut("\n✅ Correct! Great job!");
                        else appendErr(`\n❌ Not quite. Expected: "${expected}"\nYour output: "${clean}"`);
                    } else {
                        appendOut("\n✓ Code executed successfully!");
                    }
                    setStatus('ready'); btnCheck.disabled = false;
                });
            }
            if (btnClear) {
                btnClear.addEventListener('click', () => {
                    codeTA.value = '';
                    output.textContent = '👋 Type code above and click "Run".';
                });
            }

            bootPython();
        });
    </script>
</x-app-layout>
