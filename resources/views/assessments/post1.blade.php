<x-app-layout>
  <x-slot name="header">
    <div class="epic-level-header">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-auto">
            <div class="level-badge">
              <span class="level-number">?</span>
            </div>
          </div>
          <div class="col">
            <h2 class="level-title mb-1">{{ $assessment->title }}</h2>
            <div class="level-subtitle"><i class="fas fa-gamepad me-2"></i> Cosmic Quiz Challenge</div>
          </div>
        </div>
      </div>
    </div>
  </x-slot>

  <style>
    :root{
      --deep-purple:#1a0636;--cosmic-purple:#4a1b6d;--space-blue:#162b6f;--dark-space:#0a1028;
      --neon-blue:#00b3ff;--neon-purple:#b967ff;
    }
    body{
      background:linear-gradient(45deg,var(--deep-purple),var(--cosmic-purple),var(--space-blue),var(--dark-space));
      font-family:'Orbitron','Arial',sans-serif;color:#fff;
    }
    .epic-level-header{background:rgba(10,6,30,.9);border-bottom:3px solid var(--neon-purple);padding:20px 0;}
    .level-title{color:var(--neon-blue);font-size:1.8rem;font-weight:900;}
    .level-subtitle{color:rgba(255,255,255,.8)}

    .quiz-arena {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: calc(100vh - 120px);
      padding: 40px;
    }

    .question-card {
      background: linear-gradient(145deg, rgba(26,6,54,.95), rgba(74,27,109,.95));
      border: 3px solid var(--neon-purple);
      border-radius: 28px;
      padding: 50px;
      width: 95%;
      max-width: 1600px;
      min-height: 600px;
      color: #fff;
      box-shadow: 0 0 60px rgba(185,103,255,.35);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      animation: popIn .4s ease;
    }

    @keyframes popIn {
      from { transform:scale(.9); opacity:0; }
      to { transform:scale(1); opacity:1; }
    }

    .question-card h3 {font-size: 2rem;color: var(--neon-blue);}
    .question-card p {font-size: 1.8rem;margin-bottom: 30px;font-weight: 700;}

    .options {
      margin: 0 auto 40px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }
    .option {
      display: flex;align-items: center;
      background: rgba(255,255,255,.08);
      border: 2px solid rgba(185,103,255,.4);
      padding: 22px;border-radius: 16px;
      cursor: pointer;font-size: 1.3rem;font-weight: 600;
    }
    .option:hover {background: rgba(185,103,255,.25);}
    .option input {margin-right: 14px; transform: scale(1.3);}

    .nav-btns {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 20px;
    }

    .btn-nav, .btn-submit {
      flex: 1;
      padding: 20px 28px;
      border: none;border-radius: 18px;
      font-weight: 900;font-size: 1.3rem;
      cursor: pointer;transition: all .25s;
      color: #fff;text-transform: uppercase;
      letter-spacing: 1px;
    }
    .btn-prev {background: linear-gradient(135deg,#b967ff,#6a1b9a);}
    .btn-next {background: linear-gradient(135deg,#05d9e8,#00b3ff);}
    .btn-submit {background: linear-gradient(135deg,#00b3ff,#b967ff);}
    .btn-nav:hover, .btn-submit:hover {transform: scale(1.08); box-shadow: 0 0 35px rgba(185,103,255,.5);}
  </style>

  <div class="quiz-arena">
    <form method="POST" action="{{ route('assessments.submit', $assessment) }}" id="quizForm">
      @csrf

      @php
        $questions = $assessment->questions;
        if (!is_array($questions)) {
            $questions = json_decode($questions ?? '[]', true) ?: [];
        }
      @endphp

      @foreach($questions as $index => $q)
        <div class="question-card" id="question-{{ $index }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
          <div>
            <h3>Q{{ $index + 1 }}</h3>
            <p>{{ $q['prompt'] ?? '' }}</p>
            @if(isset($q['code']))
              <pre><code>{{ $q['code'] }}</code></pre>
            @endif

            <div class="options">
              @foreach(($q['options'] ?? []) as $optIndex => $option)
                <label class="option">
                  <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" required>
                  {{ $option }}
                </label>
              @endforeach
            </div>
          </div>

          <div class="nav-btns">
            @if($index > 0)
              <button type="button" class="btn-nav btn-prev" onclick="prevQuestion({{ $index }})">⬅ Previous</button>
            @endif

            @if($index < count($questions) - 1)
              <button type="button" class="btn-nav btn-next" onclick="nextQuestion({{ $index }})">Next ➡</button>
            @else
              <button type="submit" class="btn-submit">🚀 Launch Submission</button>
            @endif
          </div>
        </div>
      @endforeach
    </form>
  </div>

  <script>
    function nextQuestion(current) {
      document.getElementById('question-' + current).style.display = 'none';
      document.getElementById('question-' + (current + 1)).style.display = 'flex';
    }
    function prevQuestion(current) {
      document.getElementById('question-' + current).style.display = 'none';
      document.getElementById('question-' + (current - 1)).style.display = 'flex';
    }
  </script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
