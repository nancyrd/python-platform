<x-app-layout>
    <div class="container py-4">
        <h2 class="fw-bold mb-3 text-white">{{ $level->title }}</h2>

        <p class="text-light">{{ $level->instructions }}</p>

        @if(isset($level->content['sections']))
            @foreach($level->content['sections'] as $section)
                <div class="card shadow-lg mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $section['heading'] ?? '' }}</h4>
                        <p>{{ $section['body'] ?? '' }}</p>

                        @if(!empty($section['code']))
                            <p><strong>Python Example:</strong></p>
                            <pre class="bg-dark text-light p-3 rounded">
<code>{{ $section['code'] }}</code>
</pre>
                        @endif

                        @if(!empty($section['expected_output']))
                            <p><strong>What it shows:</strong></p>
                            <pre class="bg-light p-3 rounded">
<code>{{ $section['expected_output'] }}</code>
</pre>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Small warning box for infinite loops if relevant --}}
        @if(str_contains(strtolower($level->title), 'infinite'))
            <div class="alert alert-warning">
                ⚠️ Be careful! Infinite loops can freeze your program if you forget to stop them.
            </div>
        @endif

        {{-- Navigation --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('stages.show', $level->stage) }}" class="btn btn-secondary">
                ← Back to Stage
            </a>

            @if($nextLevel)
                <a href="{{ route('levels.show', $nextLevel) }}" class="btn btn-success">
                    Continue →
                </a>
            @else
                <a href="{{ route('stages.show', $level->stage) }}" class="btn btn-primary">
                    Finish Stage
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
