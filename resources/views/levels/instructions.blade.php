{{-- resources/views/levels/instructions.blade.php --}}
<x-app-layout>
    @php
        // Normalize content: it may be an array or a JSON string.
        $content = $level->content ?? null;
        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $content = $decoded;
            }
        }
        // Prefer 'instructions', fallback to 'intro', else null.
        $instructions = is_array($content)
            ? ($content['instructions'] ?? ($content['intro'] ?? null))
            : null;
    @endphp

    <x-slot name="header">
        <div class="game-header-container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="stage-icon-container me-3">
                        <div class="stage-icon-wrapper">
                            <i class="fas fa-dungeon stage-icon"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="stage-title mb-0">
                            {{ $level->title ?? 'Level Instructions' }}
                        </h2>
                        <div class="stage-subtitle">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Learn Before You Play
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('stages.show', $level->stage_id) }}" class="btn btn-back-to-map">
                        <i class="fas fa-map me-2"></i> Stage Map
                    </a>
                    <a href="{{ route('levels.show', $level) }}" class="btn btn-back-to-map">
                        <i class="fas fa-play me-2"></i> Start Level
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        :root{
            --deep-purple:#1a0636;--cosmic-purple:#4a1b6d;--space-blue:#162b6f;--dark-space:#0a1028;
            --neon-blue:#00b3ff;--neon-purple:#b967ff;--bright-pink:#ff2a6d;--electric-blue:#05d9e8;
        }
        body{
            background:linear-gradient(45deg,var(--deep-purple) 0%,var(--cosmic-purple) 30%,var(--space-blue) 70%,var(--dark-space) 100%);
            color:#fff;font-family:'Orbitron','Arial',sans-serif;
        }
        .game-header-container{
            background:linear-gradient(135deg,var(--deep-purple),var(--cosmic-purple));
            border-bottom:2px solid var(--neon-purple);
            box-shadow:0 6px 30px rgba(0,0,0,.5), inset 0 -1px 0 rgba(255,255,255,.06);
            position:relative;overflow:hidden;padding:14px 0;
        }
        .game-header-container::before{
            content:'';position:absolute;inset:0;left:-100%;
            background:linear-gradient(90deg,transparent,rgba(185,103,255,.25),transparent);
            animation:headerShine 4s ease-in-out infinite;
        }
        @keyframes headerShine{0%{left:-100%}50%{left:100%}100%{left:100%}}
        .stage-icon-wrapper{
            width:64px;height:64px;border-radius:14px;border:1px solid var(--neon-purple);
            background:linear-gradient(145deg,var(--deep-purple),var(--space-blue));
            box-shadow:0 0 30px rgba(185,103,255,.2) inset,0 0 24px rgba(185,103,255,.2);
            display:flex;align-items:center;justify-content:center;animation:pulse 2.4s ease-in-out infinite;
        }
        .stage-icon{color:var(--neon-purple);font-size:26px}
        @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.06)}}
        .stage-title{
            font-size:1.8rem;font-weight:900;letter-spacing:.5px;
            background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
            text-shadow:0 6px 18px rgba(185,103,255,.3);
        }
        .stage-subtitle{color:rgba(255,255,255,.7);font-size:.95rem}
        .btn-back-to-map{
            background:linear-gradient(135deg,var(--neon-blue),var(--neon-purple));color:#fff;border:0;
            padding:10px 18px;border-radius:12px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;
            box-shadow:0 10px 24px rgba(185,103,255,.3), inset 0 -2px 0 rgba(0,0,0,.3);
        }
        .btn-back-to-map:hover{transform:translateY(-1px);color:#fff;box-shadow:0 12px 28px rgba(185,103,255,.45)}
        .game-container{position:relative;min-height:calc(100vh - 140px);padding:24px 0}
        .instructions-wrap{
            max-width:980px;margin:0 auto;background:linear-gradient(135deg,rgba(26,6,54,.8),rgba(74,27,109,.8));
            border:1px solid var(--neon-purple);border-radius:22px;padding:28px;position:relative;overflow:hidden;
            box-shadow:0 24px 60px rgba(0,0,0,.6), inset 0 0 0 1px rgba(255,255,255,.03);
        }
        .instructions-wrap::before{
            content:'';position:absolute;inset:-40%;
            background:conic-gradient(from 0deg,transparent,rgba(185,103,255,.10),transparent,rgba(185,103,255,.10));
            animation:rotate 10s linear infinite;
        }
        @keyframes rotate{100%{transform:rotate(360deg)}}
        .instructions-inner{position:relative;z-index:2}
        .title-big{
            font-size:2rem;font-weight:900;text-align:center;margin-bottom:8px;
            background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
        }
        .subtitle{color:rgba(255,255,255,.8);text-align:center;margin-bottom:20px}
        .instructions-content{
            background:rgba(22,43,111,.5);border-radius:16px;padding:22px;border:1px solid rgba(185,103,255,.3);
        }
        .instruction-text{font-size:1.08rem;line-height:1.7;color:#fff}
        .actions{display:flex;gap:12px;justify-content:center;margin-top:22px}
        .btn-ghost{
            background:rgba(185,103,255,.18);color:#fff;border:1px solid var(--neon-purple);
            padding:12px 28px;border-radius:12px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;
        }
        .btn-ghost:hover{background:rgba(185,103,255,.28)}
        .btn-primary-neo{
            background:linear-gradient(135deg,var(--neon-blue),var(--neon-purple));color:#fff;border:0;
            padding:12px 28px;border-radius:12px;font-weight:900;text-transform:uppercase;letter-spacing:.5px;
            box-shadow:0 12px 26px rgba(185,103,255,.25), inset 0 -2px 0 rgba(0,0,0,.35);
        }
        .btn-primary-neo:hover{transform:translateY(-2px);color:#fff}
        @media (max-width:768px){
            .title-big{font-size:1.6rem}
            .actions{flex-direction:column}
        }
    </style>

    <div class="game-container">
        <div class="container-xl px-3 px-md-4">
            <div class="instructions-wrap">
                <div class="instructions-inner">
                    <div class="title-big">{{ $level->title ?? 'Level Instructions' }}</div>
                    <div class="subtitle">Read this carefully before starting the level.</div>

                 <div class="instructions-content">
    <div class="instruction-text">
        @if($level->instructions)
            {!! nl2br(e($level->instructions)) !!}
        @else
            <p>No instructions available for this level yet.</p>
        @endif
    </div>
</div>

                    <div class="actions">
                        <a href="{{ route('stages.show', $level->stage_id) }}" class="btn-ghost">
                            <i class="fas fa-arrow-left me-2"></i> Back to Stage
                        </a>
                        <a href="{{ route('levels.show', $level) }}" class="btn-primary-neo">
                            <i class="fas fa-play-circle me-2"></i> Start Level
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // tiny micro-interaction
        document.addEventListener('DOMContentLoaded', () => {
            const box = document.querySelector('.instructions-wrap');
            if (!box) return;
            box.addEventListener('mousemove', (e) => {
                const r = box.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                box.style.transform = `perspective(800px) rotateX(${y * -2}deg) rotateY(${x * 2}deg)`;
            });
            box.addEventListener('mouseleave', () => {
                box.style.transform = '';
            });
        });
    </script>
</x-app-layout>
