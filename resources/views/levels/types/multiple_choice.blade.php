<x-app-layout>
@php
    // ===============================
    // Safe, precomputed data for Blade
    // ===============================
    $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
    $savedScore    = $levelProgress->best_score ?? null;
    $savedStars    = $levelProgress->stars ?? 0;

    // Content fallbacks
    $timeLimit  = (int)($level->content['time_limit'] ?? 180);
    $hints      = $level->content['hints'] ?? [];
    $questions  = $level->content['questions'] ?? [];
    $introText  = $level->content['intro'] ?? '';
    $uiInstrux  = $level->content['instructions'] ?? 'Choose the best answer for each question.';

    // Default hints if none supplied
    $defaultHints = [
        "Read the code carefully and watch for small details like spaces and exact output.",
        "Recall Python basics from the lesson above before answering.",
        "Eliminate obviously wrong choices first, then pick the best remaining option.",
        "If two answers seem right, re-check exact wording and number formatting.",
    ];
    $hintsForJs = !empty($hints) ? $hints : $defaultHints;

    // Build answer key and explanations arrays safely for JS
    $answerKeyJs = array_map(function ($q) {
        return $q['correct_answer'] ?? null;
    }, $questions);

    $explanationsJs = array_map(function ($q) {
        return $q['explanation'] ?? '';
    }, $questions);
@endphp

<x-slot name="header">
    <div class="mcq-header">
        <div class="container-fluid">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="lvl-badge">
                        <span class="lvl-number">{{ $level->index }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="lvl-meta">
                        <div class="lvl-stage">{{ $level->stage->title }}</div>
                        <h2 class="lvl-title">{{ $level->title }}</h2>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="lvl-stats">
                        <div class="stat">
                            <div class="stat-label">Score</div>
                            <div class="stat-value" id="statScore">0%</div>
                        </div>
                        <div class="stat">
                            <div class="stat-label">Stars</div>
                            <div class="stat-value" id="statStars">0</div>
                        </div>
                        <div class="stat">
                            <div class="stat-label">Time</div>
                            <div class="stat-value" id="timeRemaining">--:--</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>


<style>
:root{
  --bg-1:#0a1028;
  --bg-2:#14163b;
  --ink:#e9e7ff;
  --muted:#cfc8ff;
  --accent-1:#00b3ff;
  --accent-2:#b967ff;
  --accent-3:#05d9e8;
  --danger:#ff5a7a;
  --ok:#35d19b;
  --warn:#ffb020;
  --card:#121735;
  --border:rgba(255,255,255,.12);
}

/* Layout backdrop */
body{ background: radial-gradient(1200px 800px at 20% -10%, rgba(0,179,255,.12), transparent 60%), radial-gradient(1000px 700px at 110% 10%, rgba(185,103,255,.12), transparent 60%), linear-gradient(180deg, var(--bg-1), var(--bg-2)); color:var(--ink); }

/* Header */
.mcq-header{ background: rgba(10,16,40,.85); border-bottom:1px solid var(--border); padding:16px 0; }
.lvl-badge{ width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--accent-2),var(--accent-1)); display:flex;align-items:center;justify-content:center; box-shadow:0 10px 30px rgba(0,0,0,.25); }
.lvl-number{ font-weight:900;font-size:1.35rem;color:#0f0f1a; }
.lvl-meta .lvl-stage{ font-size:.85rem;color:var(--muted); letter-spacing:.02em; }
.lvl-meta .lvl-title{ margin:0;color:#fff;font-weight:800;letter-spacing:.2px; }

.lvl-stats{ display:flex;gap:18px; }
.stat{ min-width:90px;background:rgba(255,255,255,.06);border:1px solid var(--border);padding:10px 14px;border-radius:12px;text-align:center; }
.stat-label{ font-size:.75rem;color:var(--muted); }
.stat-value{ font-size:1.05rem;font-weight:800;color:#fff; }

/* Container */
.mcq-wrap{ max-width:1040px;margin:24px auto;padding:0 16px; }

/* Lesson panel */
.lesson{ background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:16px;padding:18px 18px; }
.lesson h3{ margin:0 0 8px 0;font-size:1.05rem;color:var(--accent-3);font-weight:800; }
.lesson pre{ background:#0d1330;border:1px solid var(--border);border-radius:12px;padding:12px 14px; color:#cfeaff; overflow:auto; margin:10px 0 0 0; }

/* Progress bar */
.progress-shell{ height:12px;background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:999px;overflow:hidden;margin:18px 0; }
.progress-bar{ height:100%; width:0%; background:linear-gradient(90deg,var(--accent-1),var(--accent-2)); transition:width .4s ease; }

/* Question list */
.q-list{ display:grid;grid-template-columns:1fr;gap:16px;margin-top:16px; }
@media(min-width:900px){ .q-list{ grid-template-columns:1fr 1fr; } }

.q-card{ background:var(--card);border:1px solid var(--border);border-radius:14px;padding:16px; }
.q-head{ display:flex;justify-content:space-between;gap:10px;margin-bottom:10px; }
.q-index{ font-weight:800;color:var(--accent-2); }
.q-score-dot{ width:10px;height:10px;border-radius:50%; background:rgba(255,255,255,.15); align-self:center; }

.q-text{ color:#e7e5ff;margin:0 0 10px 0; font-weight:700; line-height:1.35; }
.q-text code{ background:#0d1330;border:1px solid var(--border);border-radius:8px;padding:2px 6px;color:#bde0ff; }
.q-text .block{ display:block;white-space:pre-wrap; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace; background:#0d1330;border:1px dashed var(--border);border-radius:12px;padding:10px; color:#cfeaff; margin-top:8px; }

.q-options{ display:flex; flex-direction:column; gap:8px; margin-top:6px; }
.q-option{ position:relative; }
.q-option input{ position:absolute; inset:0; opacity:0; }
.q-option label{
  display:block; padding:10px 12px; border:1px solid var(--border); border-radius:12px;
  background:rgba(255,255,255,.03); cursor:pointer; color:#e9e7ff; font-weight:600;
  transition: all .18s ease;
}
.q-option:hover label{ border-color:rgba(185,103,255,.55); background:rgba(185,103,255,.08); transform: translateY(-1px); }
.q-option input:checked + label{ border-color:var(--accent-1); box-shadow:0 0 0 3px rgba(0,179,255,.2) inset; }

.q-card.correct{ border-color:rgba(53,209,155,.65); box-shadow:0 0 0 3px rgba(53,209,155,.2) inset; }
.q-card.incorrect{ border-color:rgba(255,90,122,.6); box-shadow:0 0 0 3px rgba(255,90,122,.18) inset; }
.q-explain{ margin-top:10px; font-size:.95rem; color:#cfd7ff; border-top:1px dashed var(--border); padding-top:8px; display:none; }
.q-card.show-explain .q-explain{ display:block; }

/* Controls */
.controls{ display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin:18px 0 6px 0; }
.btn{
  border:none; border-radius:12px; padding:12px 18px; font-weight:800; letter-spacing:.2px;
  color:#0f1020; cursor:pointer; transition: transform .12s ease, box-shadow .2s ease;
}
.btn:disabled{ opacity:.6; cursor:not-allowed; }
.btn-primary{ background:linear-gradient(135deg,var(--accent-1),var(--accent-2)); color:#0e1126; }
.btn-secondary{ background:linear-gradient(135deg,#5ad0ff,#a58aff); color:#0e1126; }
.btn-ghost{ background:transparent; color:var(--ink); border:1px solid var(--border); }

.btn:hover{ transform: translateY(-1px); box-shadow: 0 10px 22px rgba(0,0,0,.25); }

/* Toast feedback */
.toast-wrap{ position:fixed; top:16px; right:16px; display:flex; flex-direction:column; gap:8px; z-index:1000; }
.toast{
  background:rgba(10,16,40,.9); border:1px solid var(--border); color:#fff; padding:10px 12px; border-radius:12px;
  font-weight:700; min-width:220px;
}
.toast.ok{ border-color:rgba(53,209,155,.6); }
.toast.warn{ border-color:rgba(255,176,32,.6); }
.toast.err{ border-color:rgba(255,90,122,.6); }

/* Footer meta */
.meta{ display:flex; justify-content:space-between; gap:10px; margin-top:10px; color:var(--muted); font-size:.9rem; }
.meta .left{ display:flex; gap:10px; align-items:center; }
.meta .pill{ border:1px solid var(--border); padding:4px 8px; border-radius:999px; }
</style>

<div class="mcq-wrap">
    @if($alreadyPassed)
        <div class="lesson" style="border-left:4px solid var(--ok);">
            <h3>Level Completed</h3>
            <p class="m-0">You’ve already passed this level{{ $savedScore ? " (best score: {$savedScore}%)" : '' }}. You can <a href="{{ route('levels.show', $level) }}?replay=1">replay</a> to improve your stars.</p>
        </div>
        <div style="height:12px;"></div>
    @endif



    <div class="lesson" style="margin-top:12px;">
        <h3>How to answer</h3>
        <div style="white-space:pre-wrap;">{!! nl2br($uiInstrux) !!}</div>
@if($introText)
    <div class="intro-text mt-2" style="white-space:pre-wrap;">{!! $introText !!}</div>
@endif

    </div>

    <form id="quizForm" method="POST" action="{{ route('levels.submit', $level) }}" novalidate>
        @csrf
        <input type="hidden" name="score" id="finalScore" value="0">
        <input type="hidden" name="answers" id="answersData" value="[]">

        <div class="q-list">
            @foreach($questions as $i => $q)
                <div class="q-card" data-q="{{ $i }}">
                    <div class="q-head">
                        <div class="q-index">Q{{ $i + 1 }}</div>
                        <div class="q-score-dot" aria-hidden="true"></div>
                    </div>

                    <p class="q-text">
                        {!! $q['question'] !!}
                        @php
                            // If the author put raw "python\n..." in question text, format it nicely:
                            $qtxt = $q['question'];
                        @endphp
                        @if(is_string($qtxt) && str_starts_with(trim($qtxt), 'What prints?') && str_contains($qtxt, "python"))
                            @php
                                $code = trim(preg_replace('/^.*?python/i', '', $qtxt));
                            @endphp
                            <span class="block">{{ $code }}</span>
                        @endif
                    </p>

                    <div class="q-options">
                        @foreach(($q['options'] ?? []) as $j => $opt)
                            <div class="q-option">
                                <input type="radio" id="q{{ $i }}_{{ $j }}" name="q{{ $i }}" value="{{ $j }}">
                                <label for="q{{ $i }}_{{ $j }}">{{ $opt }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="q-explain" id="exp{{ $i }}"></div>
                </div>
            @endforeach
        </div>

        <div class="controls">
            <button class="btn btn-primary" type="button" id="btnCheck">Submit Answers</button>
            <button class="btn btn-secondary" type="button" id="btnHint">Show Hint</button>
            <button class="btn btn-ghost" type="button" id="btnReset">Reset</button>
        </div>

        <div class="meta">
            <div class="left">
                <span class="pill">Pass score: {{ (int)$level->pass_score }}%</span>
                @if(!is_null($savedScore)) <span class="pill">Best: {{ (int)$savedScore }}%</span> @endif
                <span class="pill">Stars: <span id="metaStars">0</span></span>
            </div>
            <div>Tips used: <span id="hintCount">0</span></div>
        </div>
    </form>
</div>

<div class="toast-wrap" id="toastWrap"></div>

<script>
(function(){
    // ---- Precomputed values from PHP ----
    const timeLimit    = {{ $timeLimit }};
    const answerKey    = @json($answerKeyJs);
    const explanations = @json($explanationsJs);
    const hints        = @json($hintsForJs);

    // ---- State ----
    let timeRemaining = timeLimit;
    let hintsUsed = 0;
    let submitted  = false;

    // ---- DOM ----
    const $timer      = document.getElementById('timeRemaining');
    const $statScore  = document.getElementById('statScore');
    const $statStars  = document.getElementById('statStars');
    const $metaStars  = document.getElementById('metaStars');
    const $progress   = document.getElementById('progressBar');
    const $hintCount  = document.getElementById('hintCount');
    const $toastWrap  = document.getElementById('toastWrap');
    const $btnCheck   = document.getElementById('btnCheck');
    const $btnHint    = document.getElementById('btnHint');
    const $btnReset   = document.getElementById('btnReset');
    const $form       = document.getElementById('quizForm');

    // ---- Helpers ----
    function fmtTime(sec){
        const m = Math.floor(sec/60).toString().padStart(2,'0');
        const s = (sec%60).toString().padStart(2,'0');
        return `${m}:${s}`;
    }
    function toast(msg, kind='ok'){
        const el = document.createElement('div');
        el.className = `toast ${kind}`;
        el.textContent = msg;
        $toastWrap.appendChild(el);
        setTimeout(() => el.remove(), 2200);
    }
    function starsFor(score){
        if (score >= 90) return 3;
        if (score >= 70) return 2;
        if (score >= 50) return 1;
        return 0;
    }
    function updateProgressBar(){
        const total = document.querySelectorAll('.q-card').length;
        const answered = document.querySelectorAll('.q-option input:checked').length;
        const pct = total ? Math.round(100 * answered / total) : 0;
        $progress.style.width = pct + '%';
    }

    // ---- Timer ----
    $timer.textContent = fmtTime(timeRemaining);
    const t = setInterval(() => {
        timeRemaining--;
        $timer.textContent = fmtTime(timeRemaining);
        if (timeRemaining === 60 || timeRemaining === 30 || timeRemaining === 10){
            toast(`${timeRemaining}s left`, 'warn');
        }
        if (timeRemaining <= 0){
            clearInterval(t);
            if (!submitted){
                toast('Time up — submitting…', 'warn');
                submitNow();
            }
        }
    }, 1000);

    // ---- Events ----
    document.querySelectorAll('.q-option input').forEach(r => {
        r.addEventListener('change', updateProgressBar);
    });

    $btnHint.addEventListener('click', () => {
        if (submitted) return;
        hintsUsed++;
        $hintCount.textContent = hintsUsed;
        const hint = hints[Math.floor(Math.random() * hints.length)] || 'Think about the lesson examples.';
        toast('Hint: ' + hint, 'ok');
    });

    $btnReset.addEventListener('click', () => {
        if (submitted) return;
        if (confirm('Reset your selections?')){
            document.querySelectorAll('.q-option input:checked').forEach(i => i.checked = false);
            document.querySelectorAll('.q-card').forEach(c => {
                c.classList.remove('correct','incorrect','show-explain');
                const ex = c.querySelector('.q-explain'); if (ex) ex.textContent = '';
            });
            updateProgressBar();
            toast('Cleared.', 'ok');
        }
    });

    $btnCheck.addEventListener('click', () => {
        if (submitted) return;
        submitNow();
    });

    // ---- Submit & grade ----
    function submitNow(){
        submitted = true;
        $btnCheck.disabled = true;
        $btnHint.disabled = true;
        $btnReset.disabled = true;
        clearInterval(t);

        const answers = [];
        let correct = 0;
        const cards = Array.from(document.querySelectorAll('.q-card'));

        cards.forEach((card, i) => {
            const chosen = card.querySelector('input[type=radio]:checked');
            let val = -1;
            let isCorrect = false;

            if (chosen && Number.isFinite(parseInt(chosen.value))){
                val = parseInt(chosen.value);
                if (val === parseInt(answerKey[i])){
                    isCorrect = true; correct++;
                }
            }
            answers.push(val);

            card.classList.add(isCorrect ? 'correct' : 'incorrect');
            const ex = card.querySelector('.q-explain');
            if (ex){
                ex.textContent = explanations[i] || (isCorrect ? 'Correct.' : 'Check the lesson again.');
                card.classList.add('show-explain');
            }
        });

        const rawPct = cards.length ? Math.round((correct / cards.length) * 100) : 0;
        const hintPenalty = hintsUsed * 5; // small nudge for using hints
        const finalScore = Math.max(0, Math.min(100, rawPct - hintPenalty));

        // Update UI
        $statScore.textContent = finalScore + '%';
        const starCount = starsFor(finalScore);
        $statStars.textContent = '★'.repeat(starCount) || '0';
        if ($metaStars) $metaStars.textContent = '★'.repeat(starCount) || '0';

        // Fill hidden fields and submit
        document.getElementById('finalScore').value = finalScore;
        document.getElementById('answersData').value = JSON.stringify(answers);

        const passReq = {{ (int)$level->pass_score }};
        if (finalScore >= passReq){
            toast(`Great job! Score ${finalScore}%`, 'ok');
        } else {
            toast(`Score ${finalScore}%. Keep practicing!`, 'err');
        }

        // Submit after a small delay so students can see feedback
        setTimeout(() => {
            if ($form.requestSubmit) $form.requestSubmit();
            else $form.submit();
        }, 900);
    }

    // Keyboard helpers
    document.addEventListener('keydown', (e) => {
        if (submitted) return;
        if (e.key === 'Enter' && e.ctrlKey){ e.preventDefault(); submitNow(); }
        if (e.key.toLowerCase() === 'h'){ e.preventDefault(); $btnHint.click(); }
        if (e.key.toLowerCase() === 'r'){ e.preventDefault(); $btnReset.click(); }
    });
})();
</script>
</x-app-layout>
