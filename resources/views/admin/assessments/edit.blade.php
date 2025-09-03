<x-app-layout>
  <x-slot name="header">
    <div class="levels-header-container">
      <div class="flex items-center">
        <div class="levels-icon-wrapper"><i class="fas fa-pen"></i></div>
        <div class="ml-4">
          <h2 class="levels-title">Edit Assessment</h2>
          <p class="levels-subtitle">Stage: {{ $stage->title }}</p>
        </div>
      </div>
    </div>
  </x-slot>

  <div class="levels-container" style="min-height: calc(100vh - 200px);">
    <div class="levels-table-container">
      <div class="table-header">
        <h3 class="table-title">{{ $assessment->title ?: 'Assessment' }}</h3>
        <p class="table-description">Update assessment details and questions</p>
      </div>

      <div class="form-wrapper">
        @if ($errors->any())
          <div style="background:#fff;border:1px solid #fecaca;color:#7f1d1d;border-left:6px solid #ef4444;border-radius:.75rem;padding:1rem 1.25rem;box-shadow:var(--shadow-sm);margin-bottom:1rem;">
            <strong>Whoops!</strong> Please fix the errors below.
            <ul style="margin:.5rem 0 0 1.25rem;">
              @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if (session('status'))
          <div style="background:#fff;border:1px solid #86efac;color:#065f46;border-left:6px solid #10b981;border-radius:.75rem;padding:1rem 1.25rem;box-shadow:var(--shadow-sm);margin-bottom:1rem;">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('admin.stages.assessments.update', [$stage, $assessment]) }}" id="editAssessmentForm">
          @csrf
          @method('PUT')

          {{-- keep type fixed --}}
          <input type="hidden" name="type" value="{{ $assessment->type }}">
          <div class="form-group">
            <label class="form-label">Assessment Type</label>
            <div class="type-badge">
              @if($assessment->type === 'pre')
                <i class="fas fa-sign-in-alt"></i> Pre-assessment
              @else
                <i class="fas fa-flag-checkered"></i> Post-assessment
              @endif
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $assessment->title) }}" required>
            @error('title') <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Time Limit (seconds)</label>
            <input type="number" name="time_limit" class="form-control" value="{{ old('time_limit', $assessment->time_limit ?? 480) }}" min="30" max="7200">
            @error('time_limit') <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Instructions (optional)</label>
            <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $assessment->instructions) }}</textarea>
            @error('instructions') <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Questions</label>
            <div id="questionsWrap">
              @php
                // Prefer validated old input
                $qs = old('questions');
                $qs = is_array($qs) ? $qs : null;

                // Fallback to model->questions (array or JSON string)
                if (!$qs) {
                    $src = $assessment->questions;
                    if (is_string($src)) {
                        $src = json_decode($src, true);
                    }
                    $src = is_array($src) ? $src : [];

                    // Normalize to blade-friendly structure
                    $qs = [];
                    foreach ($src as $i => $q) {
                        $opts = $q['options'] ?? [];
                        if (is_string($opts)) {
                            // allow stored string, split by newline/comma
                            $opts = preg_split('/\r\n|\r|\n|,/', $opts);
                        }
                        $opts = array_values(array_filter(array_map('trim', (array) $opts), fn($v) => $v !== ''));
                        $qs[$i] = [
                            'prompt'  => $q['prompt'] ?? '',
                            'options' => implode("\n", $opts),
                            'correct' => $q['correct'] ?? '',
                        ];
                    }
                }

                if (!$qs || !is_array($qs) || count($qs) === 0) {
                    $qs = [['prompt'=>'','options'=>'','correct'=>'']];
                }
              @endphp

              @foreach($qs as $idx => $q)
                <div class="question-item mb-4 p-3 border rounded">
                  <div class="mb-2">
                    <label class="form-label">Prompt</label>
                    <textarea name="questions[{{ $idx }}][prompt]" class="form-control" rows="2" required>{{ $q['prompt'] ?? '' }}</textarea>
                    @error("questions.$idx.prompt") <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
                  </div>

                  <div class="mb-2">
                    <label class="form-label">Options (one per line or comma-separated)</label>
                    <textarea name="questions[{{ $idx }}][options]" class="form-control" rows="3" required>{{ $q['options'] ?? '' }}</textarea>
                    @error("questions.$idx.options") <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
                  </div>

                  <div class="mb-2">
                    <label class="form-label">Correct Answer (optional)</label>
                    <input type="text" name="questions[{{ $idx }}][correct]" class="form-control" value="{{ $q['correct'] ?? '' }}">
                    @error("questions.$idx.correct") <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
                  </div>

                  <button type="button" class="btn-remove" onclick="removeQuestion(this)">Remove Question</button>
                </div>
              @endforeach
            </div>

            <button type="button" class="btn-add mt-2" onclick="addQuestion()">Add another question</button>
            @error('questions') <div class="form-text" style="color:#b91c1c">{{ $message }}</div> @enderror
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-submit"><i class="fas fa-save"></i><span>Save Changes</span></button>
            <a href="{{ route('admin.stages.edit', $stage) }}" class="btn-cancel"><i class="fas fa-arrow-left"></i><span>Back to Stage</span></a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function addQuestion(){
      const wrap = document.getElementById('questionsWrap');
      const index = wrap.querySelectorAll('.question-item').length;
      const el = document.createElement('div');
      el.className = 'question-item mb-4 p-3 border rounded';
      el.innerHTML = `
        <div class="mb-2">
          <label class="form-label">Prompt</label>
          <textarea name="questions[${index}][prompt]" class="form-control" rows="2" required></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label">Options (one per line or comma-separated)</label>
          <textarea name="questions[${index}][options]" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label">Correct Answer (optional)</label>
          <input type="text" name="questions[${index}][correct]" class="form-control">
        </div>
        <button type="button" class="btn-remove" onclick="removeQuestion(this)">Remove Question</button>
      `;
      wrap.appendChild(el);
    }
    function removeQuestion(btn){
      const item = btn.closest('.question-item');
      if(item) item.remove();
    }
  </script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    :root{
      --purple-50:#faf5ff; --purple-200:#e9d5ff; --purple-500:#a855f7; --purple-600:#9333ea; --purple-800:#6b21a8; --purple-900:#581c87;
      --gradient-primary: linear-gradient(135deg, var(--purple-600) 0%, var(--purple-800) 100%);
      --gradient-button:  linear-gradient(135deg, var(--purple-600) 0%, var(--purple-900) 100%);
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }
    .levels-header-container{background:var(--gradient-primary);padding:2rem;border-radius:0 0 2rem 2rem}
    .levels-icon-wrapper{width:4rem;height:4rem;background:rgba(255,255,255,.2);border-radius:1rem;display:flex;align-items:center;justify-content:center}
    .levels-icon-wrapper i{font-size:1.5rem;color:#fbbf24}
    .levels-title{font-size:1.75rem;font-weight:700;color:#fff;margin:0}
    .levels-subtitle{color:rgba(255,255,255,.85);margin-top:.25rem}
    .levels-container{padding:2rem;background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%)}
    .levels-table-container{background:#fff;border:1px solid rgba(139,92,246,.15);border-radius:1.5rem;box-shadow:var(--shadow-lg);overflow:hidden}
    .table-header{padding:2rem 2rem 1rem;border-bottom:1px solid #e2e8f0;background:var(--purple-50)}
    .table-title{font-size:1.5rem;font-weight:700;color:var(--purple-900);margin:0 0 .5rem}
    .table-description{color:#64748b;margin:0}
    .form-wrapper{padding:2rem}
    .form-group{margin-bottom:1.25rem}
    .form-label{display:block;font-weight:600;color:var(--purple-900);margin-bottom:.5rem}

    .type-badge{
      display:inline-flex; align-items:center; gap:.5rem;
      background: var(--purple-50);
      color: var(--purple-800);
      border: 1px solid var(--purple-200);
      border-radius: .5rem;
      padding: .4rem .65rem;
      font-weight: 700;
    }

    .form-control{display:block;width:100%;padding:.75rem 1rem;font-size:1rem;border:1px solid #ced4da;border-radius:.5rem}
    .form-control:focus{border-color:var(--purple-500);box-shadow:0 0 0 .25rem rgba(139,92,246,.25);outline:0}
    .form-select{display:block;width:100%;padding:.75rem 2.25rem .75rem 1rem;font-size:1rem;border:1px solid #ced4da;border-radius:.5rem;appearance:none}
    .btn-add,.btn-remove{padding:.5rem 1rem;border-radius:.5rem;font-weight:500;font-size:.875rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s;border:1px solid transparent;cursor:pointer}
    .btn-add{background:var(--gradient-button);color:#fff}
    .btn-remove{background:#fee2e2;color:#991b1b;border-color:#fecaca}
    .btn-submit,.btn-cancel{padding:.75rem 1.5rem;border-radius:.5rem;font-weight:600;font-size:1rem;display:inline-flex;align-items:center;gap:.5rem;transition:all .2s;border:1px solid transparent;cursor:pointer}
    .btn-submit{background:var(--gradient-button);color:#fff}
    .btn-cancel{background:#e2e8f0;color:#64748b;border-color:#cbd5e1}
    .question-item{background:var(--purple-50);border:1px solid var(--purple-200);border-radius:.75rem}
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
