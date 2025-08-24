<x-app-layout>
    @php
        $qs = $content['questions'] ?? [
            ['prompt' => 'Value of x after: x=3; x=x+2', 'answer' => '5'],
            ['prompt' => 'Type literal for True/False are called ____', 'answer' => '/^booleans?$/i'],
        ];
    @endphp
    <div class="container py-4">
        <h2 class="mb-3">{{ $level->title }}</h2>
        <form id="fiForm" method="POST" action="{{ route('levels.submit',$level) }}">
            @csrf
            <input type="hidden" name="score" id="fiScore" value="0">
            <input type="hidden" name="answers" id="fiAnswers" value="[]">

            @foreach($qs as $i => $q)
                <div class="mb-3">
                    <label class="form-label">{{ $i+1 }}. {!! $q['prompt'] !!}</label>
                    <input type="text" class="form-control" data-answer="{{ $q['answer'] }}" name="a{{ $i }}" required>
                </div>
            @endforeach

            <button type="button" class="btn btn-success" onclick="gradeFillIn()">Submit</button>
        </form>
    </div>

    <script>
    function matchAnswer(user, key){
        if (!key) return false;
        if (key.startsWith('/') && key.endsWith('/i')) {
            const body = key.slice(1, -2);
            return new RegExp(body, 'i').test(user.trim());
        }
        return user.trim() === key;
    }
    function gradeFillIn(){
        const inputs = [...document.querySelectorAll('input[data-answer]')];
        let correct = 0, total = inputs.length, given = [];
        inputs.forEach((el) => {
            const user = el.value || '';
            const key  = el.dataset.answer || '';
            given.push(user);
            if (matchAnswer(user, key)) correct++;
        });
        const pct = total ? Math.round(correct * 100 / total) : 0;
        document.getElementById('fiScore').value   = pct;
        document.getElementById('fiAnswers').value = JSON.stringify(given);
        document.getElementById('fiForm').submit();
    }
    </script>
</x-app-layout>
