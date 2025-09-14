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
    <div class="modern-header">
        <div class="header-container">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="header-text">
                    <h1>Level {{ $level->index ?? '' }} - {!! $level->title ?? 'Level Instructions' !!}</h1>
                    <p>Interactive Learning Journey</p>
                </div>
            </div>
            <a href="{{ route('stages.show', $level->stage_id) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Stage
            </a>
        </div>
    </div>
</x-slot>

<div class="learning-interface">
    <!-- Main Layout Container -->
    <div class="main-layout">
        <!-- Top Section: Instructions & Overview -->
        <div class="top-section">
            <div class="instructions-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-book-open"></i>
                        Instructions & Overview
                    </div>
                    <div class="panel-controls">
                        <button class="collapse-btn" id="toggleInstructions">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>
                </div>
                
                <div class="panel-content" id="instructionsContent">
                    <!-- Overview Section -->
                    @if($estimatedTime || !empty($goals) || !empty($prerequisites))
                    <div class="overview-section">
                        <div class="overview-grid">
                            @if($estimatedTime)
                            <div class="meta-card">
                                <span class="meta-icon">⏱️</span>
                                <div class="meta-content">
                                    <span class="meta-label">Estimated Time</span>
                                    <span class="meta-value">{!! $estimatedTime !!}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if(!empty($goals))
                            <div class="meta-card">
                                <span class="meta-icon">🎯</span>
                                <div class="meta-content">
                                    <span class="meta-label">Goals</span>
                                    <span class="meta-value">{{ count($goals) }} learning objectives</span>
                                </div>
                            </div>
                            @endif
                            
                            @if(!empty($prerequisites))
                            <div class="meta-card">
                                <span class="meta-icon">📚</span>
                                <div class="meta-content">
                                    <span class="meta-label">Prerequisites</span>
                                    <span class="meta-value">{{ count($prerequisites) }} requirements</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Instructions Steps -->
                    @if(count($instructionSteps) > 0)
                    <div class="instructions-container">
                        <div class="step-navigation">
                            <button class="nav-btn" id="prevStepBtn" disabled>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="step-info">
                                <span class="step-current" id="currentStep">1</span> / <span id="totalSteps">{{ count($instructionSteps) }}</span>
                            </div>
                            <button class="nav-btn" id="nextStepBtn">
                                <i class="fas fa-chevron-right"></i>
                            </button>
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
            </div>
        </div>

        <!-- Bottom Section: Code Workspace -->
        <div class="bottom-section">
            <!-- Console Header -->
            <div class="console-header">
                <div class="console-info">
                    <div class="console-icon">
                        <i class="fab fa-python"></i>
                    </div>
                    <div>
                        <div class="console-title">Python Playground</div>
                        <div class="console-status" id="pythonStatus">Loading...</div>
                    </div>
                </div>
                <div class="console-actions">
                    <div class="layout-controls">
                        <button class="layout-btn" id="verticalLayoutBtn" title="Vertical Layout">
                            <i class="fas fa-columns"></i> Vertical
                        </button>
                        <button class="layout-btn active" id="horizontalLayoutBtn" title="Horizontal Layout">
                            <i class="fas fa-grip-lines"></i> Horizontal
                        </button>
                        <button class="layout-btn" id="fullscreenCodeBtn" title="Fullscreen Code">
                            <i class="fas fa-expand"></i> Fullscreen
                        </button>
                    </div>
                </div>
            </div>

            <!-- Example Navigation -->
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

            <!-- Example Code Preview -->
            <div class="example-preview" id="examplePreview" style="display: none;">
                <div class="example-code" id="exampleCodeDisplay"></div>
                <div class="example-explanation" id="exampleExplanation" style="display: none;"></div>
            </div>
            @endif

            <!-- Resizable Workspace -->
            <div class="workspace-container" id="workspaceContainer">
                <!-- Editor Panel -->
                <div class="editor-panel" id="editorPanel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i class="fab fa-python"></i>
                            Code Editor
                        </div>
                        <div class="panel-controls">
                            <!-- Action buttons moved here -->
                            <div class="editor-actions">
                                <button class="console-btn btn-run" id="runBtn" title="Run Code (Ctrl+Enter)">
                                    <i class="fas fa-play"></i> Run
                                </button>
                                <button class="console-btn btn-check" id="checkBtn" title="Check Answer">
                                    <i class="fas fa-check"></i> Check
                                </button>
                                <button class="console-btn btn-clear" id="clearBtn" title="Clear Code">
                                    <i class="fas fa-broom"></i> Clear
                                </button>
                        
                            </div>
                            <button class="control-btn" id="expandEditorBtn" title="Expand Editor">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="editor-content">
                        <textarea 
                            class="code-editor" 
                            id="codeEditor" 
                            placeholder="# Write your Python code here
# Press Ctrl+Enter to run quickly
print('Hello, Python!')"
                        ></textarea>
                    </div>
                </div>

                <!-- Resize Handle -->
                <div class="resize-handle" id="resizeHandle">
                    <div class="resize-line"></div>
                </div>

                <!-- Output Panel -->
                <div class="output-panel" id="outputPanel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i class="fas fa-terminal"></i>
                            Output
                        </div>
                        <div class="panel-controls">
                            <div class="output-status" id="outputStatus">Ready</div>
                            <button class="control-btn" id="expandOutputBtn" title="Expand Output">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="output-content">
                        <div class="console-output" id="consoleOutput">
                            <div class="welcome-message">
                                <i class="fas fa-rocket"></i>
                                <span>Ready to code! Write your Python code and click "Run" to see the magic.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Challenge Section -->
    <div class="challenge-section" id="challengeSection" data-expected="{{ $globalExpected ?? '' }}">
        <div class="challenge-header">
            <i class="fas fa-bullseye"></i>
            <h3>Your Challenge</h3>
        </div>
        @if($globalExpected)
        <p>Make your code produce the exact output below!</p>
        <div class="expected-output">
            <div class="output-label">Expected Output:</div>
            <div class="output-preview" id="expectedOutputPreview">{!! $globalExpected !!}</div>
        </div>
        @else
        <p>Complete the exercises and test your understanding using the console above.</p>
        @endif
    </div>

    <!-- Hidden Data Container for Examples -->
    @if(count($examples) > 0)
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
    @endif
</div>

{{-- Pyodide (client-side Python) --}}
<script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
<script>
    // Global variables
    let pyodide;
    let pyReady = false;
    let currentStepIndex = 0;
    let currentExampleIndex = 0;
    let instructionSteps = [];
    let examples = [];
    let expectedOutput = '';
    let isResizing = false;
    let currentLayout = 'horizontal'; // 'horizontal', 'vertical', 'code-only', 'output-only'

    // Initialize Python environment
    async function initializePython() {
        try {
            updateStatus('Loading Python runtime...');
            pyodide = await loadPyodide({
                stdout: (text) => appendOutput(text + "\n", 'ok'),
                stderr: (text) => appendOutput(text + "\n", 'err')
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
            updateStatus('Ready to code!');
            updateOutputStatus('Ready to run');
        } catch (error) {
            updateStatus('Failed to load Python');
            appendOutput(`Failed to load Python: ${error.message}`, 'err');
        }
    }

    // Status update functions
    function updateStatus(message) {
        const statusEl = document.getElementById('pythonStatus');
        if (statusEl) statusEl.textContent = message;
    }

    function updateOutputStatus(message) {
        const statusEl = document.getElementById('outputStatus');
        if (statusEl) statusEl.textContent = message;
    }

    // Output functions
    function clearOutput() {
        const outputEl = document.getElementById('consoleOutput');
        if (outputEl) {
            outputEl.innerHTML = `
                <div class="welcome-message">
                    <i class="fas fa-broom"></i>
                    <span>Console cleared! Ready for new code.</span>
                </div>
            `;
        }
    }

    function appendOutput(text, type = 'ok') {
        const outputEl = document.getElementById('consoleOutput');
        if (!outputEl) return;

        const welcomeMsg = outputEl.querySelector('.welcome-message');
        if (welcomeMsg) welcomeMsg.remove();

        const span = document.createElement('span');
        span.className = type;
        span.textContent = text;
        outputEl.appendChild(span);
        outputEl.scrollTop = outputEl.scrollHeight;
    }

    // Code execution
    async function runCode() {
        const codeEl = document.getElementById('codeEditor');
        const code = codeEl ? codeEl.value : '';

        if (!code.trim()) {
            appendOutput('No code to run!\n', 'err');
            return;
        }

        clearOutput();
        
        if (!pyReady) {
            appendOutput('Python runtime is still loading. Please wait...\n', 'err');
            return { out: '', err: 'not-ready' };
        }

        try {
            updateOutputStatus('Running code...');
            updateStatus('Executing...');

            pyodide.globals.set("USER_CODE", code);
            await pyodide.runPythonAsync(`
import sys, io
_out = io.StringIO()
_err = io.StringIO()
__so, __se = sys.stdout, sys.stderr
sys.stdout, sys.stderr = _out, _err
ns = {}
try:
    exec(USER_CODE, ns, ns)
except Exception:
    import traceback
    traceback.print_exc()
finally:
    sys.stdout, sys.stderr = __so, __se
OUT = _out.getvalue()
ERR = _err.getvalue()
            `);

            const out = pyodide.globals.get('OUT') || '';
            const err = pyodide.globals.get('ERR') || '';

            if (out) appendOutput(out, 'ok');
            if (err) appendOutput(err, 'err');

            updateStatus('Ready to code!');
            updateOutputStatus(err ? 'Error occurred' : 'Code executed successfully');

            return { out, err };
        } catch (error) {
            appendOutput(`Error: ${error.message}\n`, 'err');
            updateStatus('Ready to code!');
            updateOutputStatus('Error occurred');
            return { out: '', err: error.message };
        }
    }

    // Check answer function
    async function checkAnswer() {
        const { out } = await runCode();
        const cleanOutput = (out || '').trim();
        
        if (expectedOutput) {
            if (cleanOutput === expectedOutput.trim()) {
                appendOutput('\n🎉 Perfect! Your output matches exactly! 🌟\n', 'ok');
            } else {
                appendOutput(`\n🤔 Almost there! Let's compare:\n`, 'err');
                appendOutput(`Expected: "${expectedOutput.trim()}"\n`, 'err');
                appendOutput(`Your output: "${cleanOutput}"\n`, 'err');
                appendOutput(`💡 Tip: Check your code carefully!\n`, 'err');
            }
        } else {
            appendOutput('\n✅ Code executed successfully! Great work! 🚀\n', 'ok');
        }
    }

    // Copy/Paste functionality
    async function copyCode() {
        const codeEditor = document.getElementById('codeEditor');
        if (codeEditor) {
            try {
                await navigator.clipboard.writeText(codeEditor.value);
                updateOutputStatus('Code copied to clipboard!');
                setTimeout(() => updateOutputStatus('Ready'), 2000);
            } catch (error) {
                codeEditor.select();
                codeEditor.setSelectionRange(0, 99999);
                try {
                    document.execCommand('copy');
                    updateOutputStatus('Code copied to clipboard!');
                    setTimeout(() => updateOutputStatus('Ready'), 2000);
                } catch (fallbackError) {
                    updateOutputStatus('Copy failed - please select and copy manually');
                    setTimeout(() => updateOutputStatus('Ready'), 3000);
                }
            }
        }
    }

    async function pasteCode() {
        const codeEditor = document.getElementById('codeEditor');
        if (codeEditor) {
            try {
                const text = await navigator.clipboard.readText();
                codeEditor.value = text;
                updateOutputStatus('Code pasted from clipboard!');
                setTimeout(() => updateOutputStatus('Ready'), 2000);
                codeEditor.focus();
            } catch (error) {
                updateOutputStatus('Paste failed - please use Ctrl+V manually');
                setTimeout(() => updateOutputStatus('Ready'), 3000);
            }
        }
    }

    // Layout management
    function setLayout(layout) {
        const container = document.getElementById('workspaceContainer');
        const editorPanel = document.getElementById('editorPanel');
        const outputPanel = document.getElementById('outputPanel');
        const resizeHandle = document.getElementById('resizeHandle');
        const buttons = document.querySelectorAll('.layout-btn');

        buttons.forEach(btn => btn.classList.remove('active'));

        switch(layout) {
            case 'vertical':
                container.className = 'workspace-container vertical';
                document.getElementById('verticalLayoutBtn').classList.add('active');
                resizeHandle.style.display = 'block';
                editorPanel.style.display = 'flex';
                outputPanel.style.display = 'flex';
                break;
            case 'horizontal':
                container.className = 'workspace-container horizontal';
                document.getElementById('horizontalLayoutBtn').classList.add('active');
                resizeHandle.style.display = 'block';
                editorPanel.style.display = 'flex';
                outputPanel.style.display = 'flex';
                break;
            case 'code-only':
                container.className = 'workspace-container code-only';
                document.getElementById('fullscreenCodeBtn').classList.add('active');
                resizeHandle.style.display = 'none';
                editorPanel.style.display = 'flex';
                outputPanel.style.display = 'none';
                break;
        }
        currentLayout = layout;
    }

    // Resize functionality
    function initializeResize() {
        const resizeHandle = document.getElementById('resizeHandle');
        const container = document.getElementById('workspaceContainer');
        
        resizeHandle.addEventListener('mousedown', (e) => {
            isResizing = true;
            document.addEventListener('mousemove', handleResize);
            document.addEventListener('mouseup', stopResize);
            e.preventDefault();
        });

        function handleResize(e) {
            if (!isResizing) return;
            
            const containerRect = container.getBoundingClientRect();
            
            if (currentLayout === 'horizontal') {
                const newWidth = ((e.clientX - containerRect.left) / containerRect.width) * 100;
                if (newWidth >= 20 && newWidth <= 80) {
                    document.documentElement.style.setProperty('--editor-width', `${newWidth}%`);
                    document.documentElement.style.setProperty('--output-width', `${100 - newWidth}%`);
                }
            } else if (currentLayout === 'vertical') {
                const newHeight = ((e.clientY - containerRect.top) / containerRect.height) * 100;
                if (newHeight >= 20 && newHeight <= 80) {
                    document.documentElement.style.setProperty('--editor-height', `${newHeight}%`);
                    document.documentElement.style.setProperty('--output-height', `${100 - newHeight}%`);
                }
            }
        }

        function stopResize() {
            isResizing = false;
            document.removeEventListener('mousemove', handleResize);
            document.removeEventListener('mouseup', stopResize);
        }
    }

    // Step navigation
    function updateStepDisplay() {
        const currentStepEl = document.getElementById('currentStep');
        const stepContents = document.querySelectorAll('.step-content');
        const prevBtn = document.getElementById('prevStepBtn');
        const nextBtn = document.getElementById('nextStepBtn');

        if (currentStepEl) currentStepEl.textContent = currentStepIndex + 1;
        
        stepContents.forEach((content, index) => {
            content.style.display = index === currentStepIndex ? 'block' : 'none';
        });

        if (prevBtn) prevBtn.disabled = currentStepIndex === 0;
        if (nextBtn) {
            nextBtn.disabled = currentStepIndex === stepContents.length - 1;
        }
    }

    function navigateStep(direction) {
        const stepContents = document.querySelectorAll('.step-content');
        if (direction === 'prev' && currentStepIndex > 0) {
            currentStepIndex--;
            updateStepDisplay();
        } else if (direction === 'next' && currentStepIndex < stepContents.length - 1) {
            currentStepIndex++;
            updateStepDisplay();
        }
    }

    // Example management
    function updateExampleDisplay() {
        if (examples.length === 0) return;

        const exampleBadgeEl = document.getElementById('exampleBadge');
        const exampleTitleEl = document.getElementById('exampleTitle');
        const exampleCodeDisplayEl = document.getElementById('exampleCodeDisplay');
        const exampleExplanationEl = document.getElementById('exampleExplanation');
        const examplePreviewEl = document.getElementById('examplePreview');
        const prevExampleBtn = document.getElementById('prevExampleBtn');
        const nextExampleBtn = document.getElementById('nextExampleBtn');

        const currentExample = examples[currentExampleIndex];
        
        if (exampleBadgeEl) exampleBadgeEl.textContent = `Example ${currentExampleIndex + 1} / ${examples.length}`;
        if (exampleTitleEl) exampleTitleEl.textContent = currentExample.title || `Example ${currentExampleIndex + 1}`;
        
        if (exampleCodeDisplayEl) {
            exampleCodeDisplayEl.textContent = currentExample.code || '';
            if (currentExample.code) {
                examplePreviewEl.style.display = 'block';
            } else {
                examplePreviewEl.style.display = 'none';
            }
        }
        
        if (exampleExplanationEl) {
            if (currentExample.explain) {
                exampleExplanationEl.textContent = currentExample.explain;
                exampleExplanationEl.style.display = 'block';
            } else {
                exampleExplanationEl.style.display = 'none';
            }
        }

        if (prevExampleBtn) prevExampleBtn.disabled = currentExampleIndex === 0;
        if (nextExampleBtn) nextExampleBtn.disabled = currentExampleIndex === examples.length - 1;

        expectedOutput = currentExample.expected_output || '';
        updateChallengeDisplay();
    }

    function navigateExample(direction) {
        if (direction === 'prev' && currentExampleIndex > 0) {
            currentExampleIndex--;
            updateExampleDisplay();
        } else if (direction === 'next' && currentExampleIndex < examples.length - 1) {
            currentExampleIndex++;
            updateExampleDisplay();
        }
    }

    function loadExampleToEditor() {
        const codeEditor = document.getElementById('codeEditor');
        const exampleCodeDisplay = document.getElementById('exampleCodeDisplay');
        
        if (codeEditor && exampleCodeDisplay) {
            codeEditor.value = exampleCodeDisplay.textContent;
            clearOutput();
            updateOutputStatus('Example loaded!');
            setTimeout(() => updateOutputStatus('Ready'), 2000);
            codeEditor.focus();
        }
    }

    function updateChallengeDisplay() {
        const expectedOutputPreviewEl = document.getElementById('expectedOutputPreview');
        if (expectedOutput && expectedOutputPreviewEl) {
            expectedOutputPreviewEl.textContent = expectedOutput;
        }
    }

    function initializeExamples() {
        const exampleRows = document.querySelectorAll('#examplesData .ex-row');
        examples = [];
        
        exampleRows.forEach(row => {
            const title = row.dataset.title || '';
            const code = row.dataset.code ? atob(row.dataset.code) : '';
            const explain = row.dataset.explain || '';
            const expected_output = row.dataset.expected || '';
            
            examples.push({
                title,
                code,
                explain,
                expected_output
            });
        });

        if (examples.length > 0) {
            updateExampleDisplay();
        }
    }

    // Toggle instructions panel
    function toggleInstructions() {
        const content = document.getElementById('instructionsContent');
        const button = document.getElementById('toggleInstructions');
        const icon = button.querySelector('i');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.className = 'fas fa-chevron-up';
        } else {
            content.style.display = 'none';
            icon.className = 'fas fa-chevron-down';
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize
        initializePython();
        initializeExamples();
        updateStepDisplay();
        initializeResize();
        setLayout('horizontal');

        // Button event listeners
        document.getElementById('runBtn')?.addEventListener('click', runCode);
        document.getElementById('checkBtn')?.addEventListener('click', checkAnswer);
        document.getElementById('clearBtn')?.addEventListener('click', () => {
            document.getElementById('codeEditor').value = '';
            clearOutput();
        });
        document.getElementById('copyBtn')?.addEventListener('click', copyCode);
        document.getElementById('pasteBtn')?.addEventListener('click', pasteCode);

        // Layout controls
        document.getElementById('verticalLayoutBtn')?.addEventListener('click', () => setLayout('vertical'));
        document.getElementById('horizontalLayoutBtn')?.addEventListener('click', () => setLayout('horizontal'));
        document.getElementById('fullscreenCodeBtn')?.addEventListener('click', () => setLayout('code-only'));

        // Step navigation
        document.getElementById('prevStepBtn')?.addEventListener('click', () => navigateStep('prev'));
        document.getElementById('nextStepBtn')?.addEventListener('click', () => navigateStep('next'));

        // Example navigation
        document.getElementById('prevExampleBtn')?.addEventListener('click', () => navigateExample('prev'));
        document.getElementById('nextExampleBtn')?.addEventListener('click', () => navigateExample('next'));
        document.getElementById('loadExampleBtn')?.addEventListener('click', loadExampleToEditor);

        // Instructions toggle
        document.getElementById('toggleInstructions')?.addEventListener('click', toggleInstructions);

        // Panel expansion
        document.getElementById('expandEditorBtn')?.addEventListener('click', () => setLayout('code-only'));
        document.getElementById('expandOutputBtn')?.addEventListener('click', () => {
            if (currentLayout === 'code-only') {
                setLayout('horizontal');
            } else {
                // Toggle output visibility
                const outputPanel = document.getElementById('outputPanel');
                outputPanel.style.display = outputPanel.style.display === 'none' ? 'flex' : 'none';
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                runCode();
            }
            // ESC to return to normal layout
            if (e.key === 'Escape' && currentLayout === 'code-only') {
                setLayout('horizontal');
            }
        });

        // Auto-focus code editor
        const codeEditor = document.getElementById('codeEditor');
        if (codeEditor) {
            codeEditor.focus();
        }
    });
</script>

<style>
:root {
    --primary: #3b82f6;
    --primary-light: #60a5fa;
    --primary-dark: #1d4ed8;
    --secondary: #8b5cf6;
    --accent: #f59e0b;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --dark: #1f2937;
    --gray: #6b7280;
    --light-gray: #f3f4f6;
    --border: #e5e7eb;
    --white: #ffffff;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --editor-width: 50%;
    --output-width: 50%;
    --editor-height: 50%;
    --output-height: 50%;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    color: var(--dark);
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

.learning-interface {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Modern Header */
.modern-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 1rem 0;
    color: white;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.modern-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.3;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
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

.header-text h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.header-text p {
    opacity: 0.9;
    font-size: 0.9rem;
    margin: 0;
}

.back-btn {
    padding: 0.75rem 1.25rem;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
}

/* Main Layout */
.main-layout {
    flex: 1;
    display: flex;
    flex-direction: column;
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem;
    gap: 1rem;
}

/* Top Section - Instructions */
.top-section {
    flex-shrink: 0;
}

.instructions-panel {
    background: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.panel-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--light-gray) 0%, #e2e8f0 100%);
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-title {
    font-size: 1.1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.panel-controls {
    display: flex;
    gap: 0.5rem;
}

.collapse-btn, .control-btn {
    width: 32px;
    height: 32px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--white);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.collapse-btn:hover, .control-btn:hover {
    background: var(--primary);
    color: white;
}

.panel-content {
    padding: 1.5rem;
}

/* Overview Section */
.overview-section {
    margin-bottom: 1.5rem;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.meta-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--light-gray);
    border-radius: 8px;
    border-left: 4px solid var(--primary);
}

.meta-icon {
    font-size: 1.25rem;
}

.meta-content {
    display: flex;
    flex-direction: column;
}

.meta-label {
    font-size: 0.8rem;
    color: var(--gray);
    font-weight: 500;
}

.meta-value {
    font-weight: 600;
    color: var(--dark);
}

/* Instructions Container */
.instructions-container {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.step-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: var(--light-gray);
    border-bottom: 1px solid var(--border);
}

.nav-btn {
    width: 36px;
    height: 36px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--white);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.nav-btn:hover:not(:disabled) {
    background: var(--primary);
    color: white;
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.step-info {
    font-weight: 600;
    color: var(--dark);
}

.step-current {
    color: var(--primary);
    font-size: 1.1rem;
}

.step-container {
    padding: 1.5rem;
    min-height: 100px;
}

.step-content {
    line-height: 1.7;
    color: var(--dark);
}

/* Bottom Section - Code Workspace */
.bottom-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

/* Console Header */
.console-header {
    padding: 1rem 1.5rem;
    background: var(--dark);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
    flex-shrink: 0;
}

.console-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.console-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.console-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.console-status {
    font-size: 0.8rem;
    opacity: 0.8;
}

.console-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Editor Actions */
.editor-actions {
    display: flex;
    gap: 0.5rem;
    margin-right: 0.75rem;
    padding-right: 0.75rem;
    border-right: 1px solid var(--border);
}

.console-btn {
    padding: 0.5rem 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.console-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.btn-run:hover { background: var(--success) !important; }
.btn-check:hover { background: var(--primary) !important; }
.btn-clear:hover { background: var(--error) !important; }
.console-btn:hover:has(i.fa-copy) { background: var(--accent) !important; }
.console-btn:hover:has(i.fa-paste) { background: var(--secondary) !important; }

.layout-controls {
    display: flex;
    gap: 0.5rem;
}

.layout-btn {
    padding: 0.5rem 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.layout-btn:hover, .layout-btn.active {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Example Navigation */
.example-nav {
    padding: 0.75rem 1.5rem;
    background: var(--light-gray);
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.example-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.example-badge {
    font-size: 0.75rem;
    background: var(--primary);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    width: fit-content;
}

.example-title {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.9rem;
}

.example-controls {
    display: flex;
    gap: 0.5rem;
}

.btn-example {
    padding: 0.4rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--white);
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.btn-example:hover:not(:disabled) {
    background: var(--primary);
    color: white;
}

.btn-example:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Example Preview */
.example-preview {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}

.example-code {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 1rem;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.5;
    color: var(--dark);
    white-space: pre-wrap;
    overflow-x: auto;
    margin-bottom: 0.75rem;
}

.example-explanation {
    background: rgba(16, 185, 129, 0.1);
    border-left: 3px solid var(--success);
    padding: 0.75rem;
    border-radius: 4px;
    font-size: 0.9rem;
    color: var(--dark);
    line-height: 1.5;
}

/* Workspace Container */
.workspace-container {
    flex: 1;
    display: flex;
    background: var(--white);
    border-radius: 0 0 12px 12px;
    overflow: hidden;
    min-height: 400px;
    box-shadow: var(--shadow-md);
}

.workspace-container.horizontal {
    flex-direction: row;
}

.workspace-container.vertical {
    flex-direction: column;
}

.workspace-container.code-only .output-panel {
    display: none;
}

/* Editor Panel */
.editor-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    border-right: 1px solid var(--border);
    min-width: 0;
}

.workspace-container.horizontal .editor-panel {
    width: var(--editor-width);
}

.workspace-container.vertical .editor-panel {
    height: var(--editor-height);
    border-right: none;
    border-bottom: 1px solid var(--border);
}

.workspace-container.code-only .editor-panel {
    border-right: none;
}

.editor-content {
    flex: 1;
    position: relative;
}

.code-editor {
    width: 100%;
    height: 100%;
    border: none;
    padding: 1rem;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
    resize: none;
    background: #fafafa;
    color: var(--dark);
    outline: none;
}

.code-editor:focus {
    background: var(--white);
}

/* Resize Handle */
.resize-handle {
    background: var(--border);
    cursor: ew-resize;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: background-color 0.2s ease;
}

.workspace-container.horizontal .resize-handle {
    width: 4px;
    cursor: ew-resize;
}

.workspace-container.vertical .resize-handle {
    height: 4px;
    cursor: ns-resize;
}

.resize-handle:hover {
    background: var(--primary);
}

.resize-line {
    background: var(--white);
    border-radius: 2px;
}

.workspace-container.horizontal .resize-line {
    width: 2px;
    height: 20px;
}

.workspace-container.vertical .resize-line {
    width: 20px;
    height: 2px;
}

/* Output Panel */
.output-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.workspace-container.horizontal .output-panel {
    width: var(--output-width);
}

.workspace-container.vertical .output-panel {
    height: var(--output-height);
}

.output-content {
    flex: 1;
    background: var(--dark);
    position: relative;
}

.console-output {
    width: 100%;
    height: 100%;
    padding: 1rem;
    color: #e5e7eb;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
    overflow-y: auto;
    white-space: pre-wrap;
    background: var(--dark);
}

.console-output .ok {
    color: #86efac;
}

.console-output .err {
    color: #fca5a5;
}

.welcome-message {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border-left: 3px solid var(--success);
    color: #86efac;
}

/* Challenge Section */
.challenge-section {
    margin-top: 1rem;
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    flex-shrink: 0;
}

.challenge-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.challenge-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.challenge-section p {
    margin-bottom: 1rem;
    color: var(--dark);
    line-height: 1.6;
}

.expected-output {
    background: var(--light-gray);
    border-radius: 8px;
    padding: 1rem;
}

.output-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray);
    margin-bottom: 0.5rem;
}

.output-preview {
    background: var(--white);
    border: 2px solid var(--success);
    border-radius: 6px;
    padding: 0.75rem;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 14px;
    color: var(--dark);
    font-weight: 600;
}

.output-status {
    font-size: 0.8rem;
    opacity: 0.8;
    margin-right: 0.5rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-layout {
        padding: 0.75rem;
    }
    
    .console-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .editor-actions {
        flex-wrap: wrap;
        margin-right: 0.5rem;
        padding-right: 0.5rem;
    }
}

@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .workspace-container.horizontal {
        flex-direction: column;
    }
    
    .workspace-container.horizontal .editor-panel {
        width: 100%;
        height: 50%;
        border-right: none;
        border-bottom: 1px solid var(--border);
    }
    
    .workspace-container.horizontal .output-panel {
        width: 100%;
        height: 50%;
    }
    
    .workspace-container.horizontal .resize-handle {
        width: 100%;
        height: 4px;
        cursor: ns-resize;
    }
    
    .workspace-container.horizontal .resize-line {
        width: 20px;
        height: 2px;
    }
    
    .example-nav {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
    }
    
    .overview-grid {
        grid-template-columns: 1fr;
    }
    
    .panel-controls {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
    
    .editor-actions {
        border-right: none;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
        margin-right: 0;
        padding-right: 0;
    }
}

/* Custom Scrollbar */
.console-output::-webkit-scrollbar,
.panel-content::-webkit-scrollbar {
    width: 6px;
}

.console-output::-webkit-scrollbar-track {
    background: #374151;
}

.panel-content::-webkit-scrollbar-track {
    background: var(--light-gray);
}

.console-output::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 3px;
}

.panel-content::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

.console-output::-webkit-scrollbar-thumb:hover,
.panel-content::-webkit-scrollbar-thumb:hover {
    background: var(--gray);
}

/* Focus States */
button:focus-visible,
.nav-btn:focus-visible,
.console-btn:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

.code-editor:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: -2px;
}

/* Utility Classes */
.hidden { display: none !important; }
</style>
</x-app-layout>