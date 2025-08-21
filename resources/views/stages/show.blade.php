<x-app-layout>
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
                        <h2 class="stage-title mb-0">{{ $stage->title }}</h2>
                        <div class="stage-subtitle">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Adventure Zone
                        </div>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-back-to-map">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Map
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Purple Cosmic Theme -->
    <style>
        :root {
            --deep-purple: #1a0636;
            --cosmic-purple: #4a1b6d;
            --space-blue: #162b6f;
            --dark-space: #0a1028;
            --neon-blue: #00b3ff;
            --neon-purple: #b967ff;
            --bright-pink: #ff2a6d;
            --electric-blue: #05d9e8;
            --gold-gradient: linear-gradient(135deg, #ffd700 0%, #ffed4a 100%);
        }

        body {
            background: linear-gradient(45deg, var(--deep-purple) 0%, var(--cosmic-purple) 30%, var(--space-blue) 70%, var(--dark-space) 100%);
            min-height: 100vh;
            font-family: 'Orbitron', 'Arial', sans-serif;
            color: white;
        }

        .game-viewport {
            min-height: calc(100vh - 0px);
            display: flex;
            flex-direction: column;
        }

        .section-gap {
            margin: 28px 0;
        }

        /* Subtle hex grid */
        .game-container {
            position: relative;
            flex: 1;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60"><g fill="none" stroke="%23ffffff10" stroke-width="1"><path d="M30 0l30 17v26L30 60 0 43V17z"/></g></svg>');
            background-size: 60px 60px;
            overflow-x: hidden;
        }

        /* Floating collectibles */
        .floating-elements {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .floating-coin, .floating-gem, .floating-star {
            position: absolute;
            font-size: 20px;
            opacity: .6;
            animation: float 8s ease-in-out infinite;
        }

        .floating-gem {
            animation-delay: 1.8s;
        }

        .floating-star {
            animation-delay: 3.2s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0);
            }
            25% {
                transform: translateY(-18px) rotate(90deg);
            }
            50% {
                transform: translateY(0) rotate(180deg);
            }
            75% {
                transform: translateY(-12px) rotate(270deg);
            }
        }

        /* Header */
        .game-header-container {
            background: linear-gradient(135deg, var(--deep-purple) 0%, var(--cosmic-purple) 100%);
            border-bottom: 2px solid var(--neon-purple);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.5), inset 0 -1px 0 rgba(255, 255, 255, 0.06);
            position: relative;
            overflow: hidden;
        }

        .game-header-container::before {
            content: '';
            position: absolute;
            inset: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(185, 103, 255, 0.4), transparent);
            animation: headerShine 4s ease-in-out infinite;
        }

        @keyframes headerShine {
            0% {
                left: -100%;
            }
            50% {
                left: 100%;
            }
            100% {
                left: 100%;
            }
        }

        .stage-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            background: linear-gradient(145deg, var(--deep-purple), var(--space-blue));
            border: 1px solid var(--neon-purple);
            box-shadow: 0 0 30px rgba(185, 103, 255, 0.2) inset, 0 0 24px rgba(185, 103, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2.4s ease-in-out infinite;
        }

        .stage-icon {
            color: var(--neon-purple);
            font-size: 26px;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.06);
            }
        }

        .stage-title {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: .5px;
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 6px 18px rgba(185, 103, 255, 0.3);
        }

        .stage-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: .95rem;
        }

        .btn-back-to-map {
            background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
            color: white;
            border: 0;
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 800;
            box-shadow: 0 10px 24px rgba(185, 103, 255, 0.3), inset 0 -2px 0 rgba(0, 0, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .btn-back-to-map:hover {
            transform: translateY(-1px);
            color: white;
            box-shadow: 0 12px 28px rgba(185, 103, 255, 0.4);
        }

        /* Panels */
        .assessment-boss-battle {
            background: linear-gradient(135deg, rgba(26, 6, 54, 0.7) 0%, rgba(74, 27, 109, 0.7) 100%);
            border: 1px solid var(--neon-purple);
            border-radius: 18px;
            padding: 22px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6), inset 0 0 0 1px rgba(255, 255, 255, 0.03);
        }

        .assessment-boss-battle::before {
            content: '';
            position: absolute;
            inset: -50%;
            background: conic-gradient(from 0deg, transparent, rgba(185, 103, 255, 0.1), transparent, rgba(185, 103, 255, 0.1));
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        .boss-icon {
            width: 78px;
            height: 78px;
            border-radius: 16px;
            background: radial-gradient(var(--neon-purple), var(--deep-purple));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            box-shadow: 0 0 40px rgba(185, 103, 255, 0.5);
            animation: breath 2s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes breath {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
        }

        .assessment-title {
            font-weight: 900;
            color: white;
        }

        /* Arena */
        .levels-arena {
            background: linear-gradient(180deg, rgba(26, 6, 54, 0.8), rgba(22, 43, 111, 0.9));
            border: 1px solid var(--neon-purple);
            border-radius: 22px;
            padding: 26px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 2;
        }

        .arena-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 900;
            margin-bottom: 16px;
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .arena-sub {
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            margin-bottom: 18px;
            font-size: .95rem;
        }

        /* Level card */
        .level-card {
            background: linear-gradient(180deg, rgba(26, 6, 54, 0.8), rgba(22, 43, 111, 0.8));
            border: 1px solid var(--neon-purple);
            border-radius: 18px;
            padding: 22px;
            text-align: center;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6), inset 0 0 0 1px rgba(255, 255, 255, 0.03);
        }

        .level-card.unlocked {
            cursor: pointer;
        }

        .level-card:hover.unlocked {
            transform: translateY(-8px);
            box-shadow: 0 18px 44px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(185, 103, 255, 0.2);
        }

        .level-card::before {
            content: '';
            position: absolute;
            inset: -40%;
            background: radial-gradient(circle at 20% -10%, rgba(185, 103, 255, 0.1) 0%, transparent 60%);
        }

        .level-number {
            position: absolute;
            top: -12px;
            left: -12px;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.1rem;
            color: white;
            background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
            box-shadow: 0 16px 30px rgba(185, 103, 255, 0.3), inset 0 -2px 0 rgba(0, 0, 0, 0.3);
            z-index: 3;
        }

        .level-type-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            padding: 6px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--neon-blue), var(--electric-blue));
            color: white;
            font-weight: 700;
            font-size: .75rem;
            letter-spacing: .4px;
            border: 1px solid rgba(98, 231, 255, 0.4);
        }

        .level-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: white;
            margin: 18px 0 8px;
        }

        .stars-display {
            margin: 10px 0 8px;
            font-size: 1.6rem;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .star {
            display: inline-block;
            margin: 0 2px;
            transition: transform .2s;
        }

        .star.earned {
            color: #ffd700;
            text-shadow: 0 0 12px #ffd54a;
        }

        .star.empty {
            color: rgba(255, 255, 255, 0.3);
        }

        .star:hover {
            transform: scale(1.15) rotate(10deg);
        }

        .meta {
            font-size: .9rem;
        }

        .meta .score {
            color: var(--neon-blue);
        }

        .meta .passed {
            color: var(--neon-purple);
            font-weight: 700;
        }

        .btn-level {
            background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
            color: white;
            border: 0;
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .5px;
            box-shadow: 0 12px 26px rgba(185, 103, 255, 0.2), inset 0 -2px 0 rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-level:hover:not(:disabled) {
            transform: translateY(-2px);
            color: white;
        }

        .btn-level:disabled {
            background: linear-gradient(135deg, #303b48, #243140);
            color: #8aa1b4;
        }

        .locked-overlay {
            position: absolute;
            inset: 0;
            background: rgba(10, 20, 36, 0.8);
            backdrop-filter: blur(2px);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: rgba(107, 114, 128, 0.7);
            z-index: 5;
        }

        /* Final Boss */
        .post-assessment-final-boss {
            background: linear-gradient(135deg, rgba(26, 6, 54, 0.7) 0%, rgba(74, 27, 109, 0.7) 100%);
            border: 1px solid var(--neon-purple);
            border-radius: 22px;
            padding: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6), inset 0 0 0 1px rgba(255, 255, 255, 0.03);
        }

        .post-assessment-final-boss::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 22px;
            background: linear-gradient(45deg, rgba(185, 103, 255, 0.3), rgba(5, 217, 232, 0.3), rgba(185, 103, 255, 0.3));
            mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            padding: 2px;
            animation: borderGlow 2.6s ease-in-out infinite;
        }

        @keyframes borderGlow {
            50% {
                opacity: .6;
            }
        }

        .final-boss-icon {
            width: 96px;
            height: 96px;
            border-radius: 18px;
            margin: 0 auto 16px;
            background: radial-gradient(var(--neon-purple), var(--deep-purple));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 46px;
            box-shadow: 0 0 50px rgba(185, 103, 255, 0.4);
            animation: breath 1.6s ease-in-out infinite;
        }

        .final-boss-title {
            font-weight: 900;
            text-align: center;
            color: white;
        }

        .btn-final-boss {
            background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
            color: white;
            border: 0;
            padding: 16px 34px;
            border-radius: 14px;
            font-weight: 900;
            letter-spacing: .6px;
            text-transform: uppercase;
            box-shadow: 0 16px 32px rgba(185, 103, 255, 0.2);
        }

        .btn-final-boss:hover:not(:disabled) {
            transform: translateY(-2px);
        }

        /* Notifications & Progress */
        .success-notification {
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            color: white;
            border: 0;
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 18px 40px rgba(185, 103, 255, 0.2);
        }

        .progress-bar-container {
            background: rgba(10, 27, 47, 0.5);
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
            outline: 1px solid rgba(19, 46, 82, 0.5);
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
            border-radius: 10px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            inset: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2.2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .stage-title {
                font-size: 1.4rem;
            }
            .arena-title {
                font-size: 1.4rem;
            }
            .level-card {
                padding: 18px;
            }
            .boss-icon, .final-boss-icon {
                width: 70px;
                height: 70px;
                font-size: 32px;
            }
        }
    </style>

    <div class="game-viewport">
        <div class="game-container">

            <!-- Floating Game Elements -->
            <div class="floating-elements">
                <div class="floating-coin" style="top: 10%; left: 5%;">🪙</div>
                <div class="floating-gem" style="top: 20%; left: 90%;">💎</div>
                <div class="floating-star" style="top: 60%; left: 3%;">⭐</div>
                <div class="floating-coin" style="top: 80%; left: 95%;">🪙</div>
                <div class="floating-gem" style="top: 40%; left: 2%;">💎</div>
                <div class="floating-star" style="top: 15%; left: 85%;">⭐</div>
            </div>

            <div class="container-xl px-3 px-md-4 py-3 py-md-4">

                @if(session('status'))
                    <div class="success-notification rounded-3 shadow-lg section-gap">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-trophy me-3 fs-4"></i>
                            <div>
                                <strong>Achievement Unlocked!</strong><br>
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pre-Assessment Boss Battle -->
                <div class="assessment-boss-battle section-gap">
                    <div class="d-flex align-items-center">
                        <div class="boss-icon me-4">🧠</div>
                        <div class="flex-fill">
                            <h3 class="assessment-title mb-2">Pre-Assessment Boss Battle</h3>
                            <div class="assessment-status">
                                @if($progress->pre_completed_at)
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">
                                            <i class="fas fa-check-circle me-1 text-success"></i>
                                            Victory Achieved!
                                        </span>
                                        <small class="text-info">
                                            Conquered on {{ $progress->pre_completed_at->format('M j, Y \a\t H:i') }}
                                        </small>
                                    </div>
                                    <div class="progress-bar-container mt-2">
                                        <div class="progress-bar-fill" style="width: 100%;"></div>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-sword me-1 text-info"></i>
                                        Defeat this boss to unlock the arena levels!
                                    </div>
                                    <div class="progress-bar-container mt-2">
                                        <div class="progress-bar-fill" style="width: 0%;"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="ms-2">
                            @if(!$progress->pre_completed_at && $pre)
                                <a class="btn btn-boss-fight btn-level" href="{{ route('assessments.show', $pre) }}">
                                    <i class="fas fa-fist-raised me-2"></i>
                                    Fight Boss
                                </a>
                            @else
                                <button class="btn btn-boss-fight btn-level" disabled style="opacity: 0.6;">
                                    <i class="fas fa-crown me-2"></i>
                                    Defeated
                                </button>
                            @endif
                        </div>
                    </div>
                    @if($progress->pre_completed_at)
                        <div class="completed-stamp">
                            <i class="fas fa-trophy me-1"></i>
                            Completed
                        </div>
                    @endif
                </div>

                <!-- Levels Arena -->
                <div class="levels-arena section-gap">
                    <h2 class="arena-title">Battle Arena Levels</h2>
                    <div class="arena-sub">Earn stars, raise your rank, and unlock the final boss.</div>

                    <div class="row g-4">
                        @foreach($stage->levels as $level)
                            @php
                                $levelProgress = \App\Models\UserLevelProgress::where('user_id', auth()->id())
                                    ->where('stage_id', $stage->id)
                                    ->where('level_id', $level->id)
                                    ->first();

                                $unlocked  = $level->index <= $progress->unlocked_to_level;
                                $stars     = $levelProgress?->stars ?? 0;
                                $bestScore = $levelProgress?->best_score ?? 0;
                                $passed    = $levelProgress?->passed ?? false;
                            @endphp

                            <div class="col-xl-4 col-md-6">
                                <div class="level-card {{ $unlocked ? 'unlocked' : 'locked' }} position-relative h-100">
                                    <div class="level-number">{{ $level->index }}</div>
                                    <div class="level-type-badge">{{ $level->type }}</div>

                                    @if(!$unlocked)
                                        <div class="locked-overlay">🔒</div>
                                    @endif

                                    <div class="level-title">{{ $level->title }}</div>

                                    <div class="stars-display">
                                        @for($i=1;$i<=3;$i++)
                                            <span class="star {{ $i <= $stars ? 'earned' : 'empty' }}">
                                                {{ $i <= $stars ? '⭐' : '☆' }}
                                            </span>
                                        @endfor
                                    </div>

                                    @if($bestScore>0)
                                        <div class="meta mb-2">
                                            <span class="score"><i class="fas fa-trophy me-1"></i>Best: {{ $bestScore }}%</span>
                                            @if($passed)
                                                <span class="passed ms-2"><i class="fas fa-check-circle me-1"></i>Passed</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        @if($unlocked)
                                            <a class="btn btn-level" href="{{ route('levels.show', $level) }}">
                                                <i class="fas fa-{{ $passed ? 'redo' : 'play' }} me-2"></i>
                                                {{ $passed ? 'Retake Level' : 'Enter Battle' }}
                                            </a>
                                        @else
                                            <button class="btn btn-level" disabled>
                                                <i class="fas fa-lock me-2"></i> Locked
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Post-Assessment Final Boss -->
                <div class="post-assessment-final-boss section-gap">
                    <div class="final-boss-icon">🏁</div>
                    <h3 class="final-boss-title">FINAL BOSS BATTLE</h3>
                    <div class="text-center mb-4">
                        @if($progress->post_completed_at)
                            <div class="fs-5 mb-2">
                                <i class="fas fa-crown me-2 text-warning"></i>
                                LEGENDARY VICTORY ACHIEVED!
                            </div>
                            <div class="text-info opacity-75">
                                Conquered on {{ $progress->post_completed_at->format('M j, Y \a\t H:i') }}
                            </div>
                            <div class="progress-bar-container mt-3">
                                <div class="progress-bar-fill" style="width: 100%;"></div>
                            </div>
                        @else
                            <div class="fs-5 mb-2">
                                <i class="fas fa-dragon me-2 text-info"></i>
                                The Ultimate Challenge Awaits
                            </div>
                            <div class="text-muted">Complete all arena levels to face the final boss</div>
                            <div class="progress-bar-container mt-3">
                                <div class="progress-bar-fill" style="width: 0%;"></div>
                            </div>
                        @endif
                    </div>

                    <div class="text-center">
                        @if($post)
                            <a class="btn btn-final-boss" href="{{ route('assessments.show', $post) }}">
                                <i class="fas fa-{{ $progress->post_completed_at ? 'redo' : 'sword' }} me-2"></i>
                                {{ $progress->post_completed_at ? 'Challenge Again' : 'Face Final Boss' }}
                            </a>
                        @else
                            <button class="btn btn-final-boss" disabled style="opacity: .7;">
                                <i class="fas fa-lock me-2"></i>
                                Boss Not Available
                            </button>
                        @endif
                    </div>

                    @if($progress->post_completed_at)
                        <div class="completed-stamp" style="top:20px; right:20px; background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple)); color:white;">
                            <i class="fas fa-crown me-1"></i>
                            LEGENDARY
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts (kept same functionality) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });

            // ripple
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.disabled) return;
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    ripple.style.cssText = `
                        position:absolute;width:${size}px;height:${size}px;left:${x}px;top:${y}px;
                        background:rgba(255,255,255,0.3);border-radius:50%;transform:scale(0);
                        animation:gameRipple .6s linear;pointer-events:none;z-index:1;`;
                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // star hover
            document.querySelectorAll('.star').forEach(star => {
                star.addEventListener('mouseenter', function() {
                    if (this.classList.contains('earned')) this.style.transform = 'scale(1.25) rotate(10deg)';
                });
                star.addEventListener('mouseleave', function() { this.style.transform = ''; });
            });

            // card hover click-through
            document.querySelectorAll('.level-card.unlocked').forEach(card => {
                card.addEventListener('click', function() {
                    const btn = this.querySelector('.btn-level:not(:disabled)');
                    if (btn) btn.click();
                });
            });

            // boss icons micro-interaction
            document.querySelectorAll('.boss-icon, .final-boss-icon').forEach(icon => {
                icon.addEventListener('mouseenter', function() { this.style.animationDuration='0.6s'; this.style.transform='scale(1.12) rotate(8deg)'; });
                icon.addEventListener('mouseleave', function() { this.style.animationDuration='1.6s'; this.style.transform=''; });
            });

            // floating collectibles click
            document.querySelectorAll('.floating-coin, .floating-gem, .floating-star').forEach(el => {
                el.addEventListener('click', function() {
                    this.style.animation='none'; this.style.transform='scale(1.4) rotate(360deg)'; this.style.opacity='0';
                    setTimeout(() => { this.style.animation=''; this.style.transform=''; this.style.opacity=''; }, 900);
                });
            });

            // progress shimmer restart on view
            const observer = new IntersectionObserver((entries)=>{
                entries.forEach(entry=>{
                    if(entry.isIntersecting){
                        const width = entry.target.style.width;
                        entry.target.style.width = '0%';
                        setTimeout(()=> entry.target.style.width = width, 120);
                    }
                });
            });
            document.querySelectorAll('.progress-bar-fill').forEach(bar=>observer.observe(bar));

            // mobile touch feedback
            if ('ontouchstart' in window) {
                document.querySelectorAll('.level-card, .assessment-boss-battle, .post-assessment-final-boss').forEach(el=>{
                    el.addEventListener('touchstart', ()=> el.style.transform='scale(0.985)');
                    el.addEventListener('touchend', ()=> el.style.transform='');
                });
            }

            // easter egg (unchanged)
            let konamiCode = []; const seq=['ArrowUp','ArrowUp','ArrowDown','ArrowDown','ArrowLeft','ArrowRight','ArrowLeft','ArrowRight','KeyB','KeyA'];
            document.addEventListener('keydown', function(e){
                konamiCode.push(e.code); if (konamiCode.length>seq.length) konamiCode.shift();
                if (konamiCode.join(',')===seq.join(',')){
                    document.body.style.filter='hue-rotate(160deg)';
                    for(let i=0;i<18;i++){
                        setTimeout(()=>{
                            const c=document.createElement('div');
                            c.innerHTML=['🎉','✨','🏆','⭐'][Math.floor(Math.random()*4)];
                            c.style.cssText=`position:fixed;top:${Math.random()*100}vh;left:${Math.random()*100}vw;font-size:2rem;z-index:9999;pointer-events:none;animation:celebrate 2s ease-out forwards;`;
                            document.body.appendChild(c); setTimeout(()=>c.remove(),2000);
                        }, i*90);
                    }
                    setTimeout(()=>document.body.style.filter='',4200); konamiCode=[];
                }
            });
        });

        // dynamic keyframes
        const dynamicStyles=document.createElement('style');
        dynamicStyles.textContent=`
            @keyframes gameRipple{to{transform:scale(4);opacity:0}}
            @keyframes celebrate{0%{transform:translateY(0) rotate(0);opacity:1}100%{transform:translateY(-220px) rotate(360deg);opacity:0}}
        `;
        document.head.appendChild(dynamicStyles);
    </script>

    <!-- Icons & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>