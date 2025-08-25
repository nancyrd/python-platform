<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl">{{ $assessment->title }}</h2>
  </x-slot>

  <div class="container py-4">
    <form method="POST" action="{{ route('assessments.submit', $assessment) }}">
      @csrf

      @php
        // Ensure questions are always an array
        $questions = $assessment->questions;
        if (!is_array($questions)) {
            $questions = json_decode($questions ?? '[]', true) ?: [];
        }
      @endphp

      @foreach($questions as $index => $q)
        <div class="mb-4 p-3 border rounded">
          <div class="fw-bold mb-3">
            Q{{ $index + 1 }}. {{ $q['prompt'] ?? '' }}
            @if(isset($q['code']))
              <pre class="mb-0"><code>{{ $q['code'] }}</code></pre>
            @endif
          </div>

          @foreach(($q['options'] ?? []) as $optIndex => $option)
            <div class="form-check mb-2">
              <input class="form-check-input"
                     type="radio"
                     id="q{{ $index }}_opt{{ $optIndex }}"
                     name="answers[{{ $index }}]"
                     value="{{ $option }}"
                     required>
              <label class="form-check-label" for="q{{ $index }}_opt{{ $optIndex }}">
                {{ $option }}
              </label>
            </div>
          @endforeach
        </div>
      @endforeach

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</x-app-layout>