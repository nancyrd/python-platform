{{-- resources/views/assessments/post1.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl">Post 1 — Final Boss</h2>
  </x-slot>

  <div class="container py-4">
    <form method="POST" action="{{ route('assessments.submit', $assessment) }}">
      @csrf

      <div class="mb-4 p-3 border rounded">
        <div class="fw-bold mb-3">
          Q1. What does this print in Python?
          <pre class="mb-0"><code>print("Hello")</code></pre>
        </div>

        <div class="form-check mb-2">
          <input class="form-check-input" type="radio" id="a1" name="answers[0]" value="Hello" required>
          <label class="form-check-label" for="a1">Hello</label>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="radio" id="a2" name="answers[0]" value='"Hello"'>
          <label class="form-check-label" for="a2">"Hello"</label>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="radio" id="a3" name="answers[0]" value='print("Hello")'>
          <label class="form-check-label" for="a3">print("Hello")</label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</x-app-layout>
