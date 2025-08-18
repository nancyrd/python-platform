<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ strtoupper($assessment->type) }} Assessment — {{ $assessment->stage->title }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
<form method="POST" action="{{ route('assessments.submit', $assessment) }}" class="space-y-6">
    @csrf

    {{-- If questions is somehow a JSON string, decode it defensively --}}
    @php
        $qs = $assessment->questions;
        if (!is_array($qs)) {
            $qs = json_decode($qs ?? '[]', true) ?: [];
        }
    @endphp

    @if(empty($qs))
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded">
            No questions found for this assessment.
        </div>
    @else
        @foreach($qs as $i => $q)
            @php
                $opts = is_array($q['options'] ?? null) ? $q['options'] : [];
                $oldValue = old("answers.$i");
            @endphp

            <div class="bg-white p-5 rounded shadow">
                <div class="font-semibold mb-3">
                    {{ $i + 1 }}) {{ $q['prompt'] ?? 'Question' }}
                </div>

                <div class="space-y-2">
                    @forelse($opts as $opt)
                        <label class="flex items-center gap-2">
                            <input
                                type="radio"
                                name="answers[{{ $i }}]"
                                value="{{ $opt }}"
                                class="rounded"
                                {{ $oldValue === $opt ? 'checked' : '' }}
                                required
                            >
                            <span>{{ $opt }}</span>
                        </label>
                    @empty
                        <div class="text-sm text-gray-500">No options provided.</div>
                    @endforelse
                </div>

                @error("answers.$i")
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-black hover:bg-blue-700">
                Submit
            </button>
        </div>
    @endif
</form>

    </div>
</x-app-layout>
