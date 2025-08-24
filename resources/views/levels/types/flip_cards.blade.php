<x-app-layout>
    @php $cards = $content['cards'] ?? [
        ['front' => 'What is a variable?', 'back' => 'A named storage for a value.'],
        ['front' => 'Is "7" an int?', 'back' => 'No, it is a string.'],
    ]; @endphp

    <div class="container py-4">
        <h2 class="mb-3">{{ $level->title }}</h2>
        <p class="text-muted">Flip to learn, then confirm to earn points.</p>

        <div class="row g-3">
            @foreach($cards as $i => $c)
                <div class="col-md-4">
                    <div class="card flip-card h-100">
                        <div class="card-body">
                            <div><strong>Q:</strong> {!! $c['front'] !!}</div>
                            <hr>
                            <details><summary>Show Answer</summary>{!! $c['back'] !!}</details>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form class="mt-4" method="POST" action="{{ route('levels.submit', $level) }}">
            @csrf
            <input type="hidden" name="score" value="100"> {{-- simple: award full credit for study --}}
            <button class="btn btn-primary">I studied this deck</button>
        </form>
    </div>
</x-app-layout>
