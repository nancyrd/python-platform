<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="learning-map-icon me-3">
                    <i class="fas fa-map-marked-alt text-light fs-2"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-bold text-white">Python Quest Universe</h2>
                    <p class="mb-0 text-light small">
                        <i class="fas fa-info-circle me-1"></i>
                        Complete stages to unlock your coding journey
                    </p>
                    <a class="btn btn-game rounded-pill ms-3" data-bs-toggle="modal" data-bs-target="#progressModal">
                        <i class="fas fa-chart-line me-2"></i> My Progress
                    </a>
                </div>
            </div>
            <div class="achievement-summary d-none d-md-flex">
                <div class="text-center me-4">
                    <div class="fw-bold text-warning fs-4">{{ collect($progressByStage)->sum(function($p) { return $p && $p->stars_per_level ? array_sum($p->stars_per_level) : 0; }) }}</div>
                    <small class="text-light">Total Stars</small>
                </div>
                <div class="text-center">
                    <div class="fw-bold text-success fs-4">{{ collect($progressByStage)->filter(function($p) { return $p && $p->post_completed_at; })->count() }}/{{ count($stages) }}</div>
                    <small class="text-light">Completed</small>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        // Totals already shown in header
        $totalStarsBlade = collect($progressByStage)->sum(function($p) {
            return $p && $p->stars_per_level ? array_sum($p->stars_per_level) : 0;
        });
        $totalPointsBlade = $totalStarsBlade * 10;

        // Stages stats
        $stagesTotalBlade = count($stages);
        $stagesCompletedBlade = collect($progressByStage)->filter(function($p){
            return $p && $p->post_completed_at;
        })->count();

        // Levels stats
        $levelsTotalBlade = (int) collect($stages)->sum('levels_count');
        $levelsCompletedBlade = \App\Models\UserLevelProgress::where('user_id', auth()->id())
            ->where('passed', true)->count();

        // Assessment counts
        $preDoneBlade  = collect($progressByStage)->filter(fn($p) => $p && $p->pre_completed_at)->count();
        $postDoneBlade = $stagesCompletedBlade;

        // Rank thresholds
        $rankTable = [
            ['name' => 'Beginner I', 'points' =>   0],
            ['name' => 'Beginner II','points' =>  50],
            ['name' => 'Apprentice', 'points' => 100],
            ['name' => 'Explorer',   'points' => 180],
            ['name' => 'Coder',      'points' => 280],
            ['name' => 'Pythonista', 'points' => 400],
            ['name' => 'Guru',       'points' => 600],
        ];
        $currentRank = $rankTable[0];
        $nextRank = null;
        foreach ($rankTable as $i => $r) {
            if ($totalPointsBlade >= $r['points']) {
                $currentRank = $r;
                $nextRank = $rankTable[$i + 1] ?? null;
            }
        }
        $rankPct = $nextRank
            ? (int)( ($totalPointsBlade - $currentRank['points']) * 100 / max(1, $nextRank['points'] - $currentRank['points']) )
            : 100;

        // Find a next recommended stage
        $nextStageBlade = collect($stages)->first(function($s) use ($progressByStage){
            $p = $progressByStage[$s->id] ?? null;
            return $p && ($p->unlocked_to_level ?? 0) < ($s->levels_count ?? 0);
        });
    @endphp

    <style>
        :root {
            --deep-purple: #45168786;
            --cosmic-purple: #4a1b6d;
            --space-blue: #162b6f;
            --dark-space: #7f19c39a;
            --neon-blue: #00b3ff;
            --neon-purple: #b967ff;
            --bright-pink: #ff2a6d;
            --electric-blue: #05d9e8;
        }
        
        body {
            background: linear-gradient(45deg, var(--deep-purple) 0%, var(--cosmic-purple) 30%, var(--space-blue) 70%, var(--dark-space) 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Orbitron', 'Arial', sans-serif;
            overflow-x: hidden;
            color: white;
        }
        
        .learning-map-container {
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding: 0;
        }

        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
        }

        .stage-node {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
            border: 2px solid transparent;
        }

        .stage-node:hover {
            transform: translateY(-5px) scale(1.05);
        }

        .stage-node.completed {
            animation: pulse-success 2s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(25, 135, 84, 0.7);
        }

        .stage-node.unlocked {
            animation: pulse-primary 2s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(185, 103, 255, 0.7);
        }

        @keyframes pulse-success {
            0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(25, 135, 84, 0); }
            100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
        }

        @keyframes pulse-primary {
            0% { box-shadow: 0 0 0 0 rgba(185, 103, 255, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(185, 103, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(185, 103, 255, 0); }
        }

        .connector-line {
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(108, 117, 125, 0.5), transparent);
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }

        .connector-line.active {
            background: linear-gradient(90deg, transparent, var(--neon-purple), transparent);
            animation: flow 2s linear infinite;
        }

        @keyframes flow {
            0% { background-position: -100% 0; }
            100% { background-position: 100% 0; }
        }

        .stage-card {
            backdrop-filter: blur(10px);
            background: rgba(26, 6, 54, 0.7);
            border: 1px solid var(--neon-purple);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(185, 103, 255, 0.3);
        }

        .stage-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(185, 103, 255, 0.5);
        }

        .stage-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s ease;
        }

        .stage-card:hover::before {
            left: 100%;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            stroke-dasharray: 251.2;
            stroke-dashoffset: 251.2;
            transition: stroke-dashoffset 0.5s ease-in-out;
        }

        .achievement-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 30px;
            height: 30px;
            background: linear-gradient(45deg, #ffd700, #ffed4a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            animation: bounce 1s ease infinite alternate;
        }

        @keyframes bounce {
            from { transform: translateY(0px); }
            to { transform: translateY(-5px); }
        }

        .star-animation {
            display: inline-block;
            animation: twinkle 1.5s ease-in-out infinite alternate;
        }

        @keyframes twinkle {
            from { opacity: 0.5; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .btn-game {
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            border: none;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-game:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(185, 103, 255, 0.4);
            color: white;
        }

        .btn-game::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-game:hover::before {
            width: 300px;
            height: 300px;
        }

        .mobile-stage-item {
            background: rgba(26, 6, 54, 0.7);
            backdrop-filter: blur(10px);
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            border: 1px solid rgba(185, 103, 255, 0.3);
        }

        .mobile-stage-item.completed {
            border-left-color: #28a745;
        }

        .mobile-stage-item.unlocked {
            border-left-color: var(--neon-purple);
        }

        .mobile-stage-item.locked {
            border-left-color: #6c757d;
        }

        .tooltip-custom {
            background: rgba(141, 31, 175, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            position: absolute;
            z-index: 1000;
            white-space: nowrap;
        }

        .learning-path {
            position: relative;
            z-index: 2;
        }

        .cosmic-stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 5s infinite;
        }

        .modal-content {
            background: linear-gradient(135deg, var(--deep-purple) 0%, var(--cosmic-purple) 100%);
            border: 1px solid var(--neon-purple);
            color: white;
        }

        .modal-header {
            border-bottom: 1px solid var(--neon-purple);
        }

        .modal-footer {
            border-top: 1px solid var(--neon-purple);
        }

        .table {
            color: white;
        }

        .table th {
            border-color: var(--neon-purple);
        }

        .table td {
            border-color: rgba(185, 103, 255, 0.3);
        }

        @media (max-width: 768px) {
            .achievement-summary { display: none !important; }
        }
        
    </style>

    <div class="learning-map-container py-4">
        <!-- Floating Particles Background -->
        <div class="floating-particles">
            <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
            <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 40%; animation-delay: 0.5s;"></div>
            <div class="particle" style="left: 50%; animation-delay: 1.5s;"></div>
            <div class="particle" style="left: 60%; animation-delay: 2.5s;"></div>
            <div class="particle" style="left: 70%; animation-delay: 3s;"></div>
            <div class="particle" style="left: 80%; animation-delay: 0.8s;"></div>
            <div class="particle" style="left: 90%; animation-delay: 1.8s;"></div>
        </div>

        <div class="container-fluid learning-path px-3">
            <!-- Desktop/Tablet Horizontal Stage Map -->
            <div class="row justify-content-center mb-5">
                <div class="col-12 px-0">
                    <div class="stage-card rounded-4 p-4">
                        <div class="d-none d-md-flex align-items-center justify-content-between position-relative">
                            @foreach($stages as $i => $stage)
                                @php
                                    $p = $progressByStage[$stage->id] ?? null;
                                    $isCompleted = (bool) ($p->post_completed_at ?? false);
                                    $hasPre = (bool) ($p && $p->pre_completed_at);
                                    $isUnlocked = (bool) ($stage->unlocked ?? false);
                                    
                                    $badge = $isCompleted ? '🏆' : ($isUnlocked ? '🚀' : '🔒');
                                    $nodeClass = $isCompleted ? 'completed bg-success' : ($isUnlocked ? 'unlocked bg-primary' : 'bg-secondary');
                                    
                                    $chip = $isCompleted ? 'Mastered!' : ($isUnlocked ? ($hasPre ? 'In Progress' : 'Ready to Start') : 'Locked');
                                    $ctaLabel = $isUnlocked ? ($hasPre ? 'Continue Journey' : 'Begin Adventure') : 'Locked';
                                @endphp
                                
                                <!-- Connector Line -->
                                @if($i > 0)
                                    <div class="flex-fill mx-3">
                                        <div class="connector-line {{ ($isCompleted || $isUnlocked) ? 'active' : '' }}"></div>
                                    </div>
                                @endif

                                <!-- Stage Node -->
                                <div class="text-center" style="min-width: 160px;">
                                    <div class="position-relative d-inline-block">
                                        <div class="stage-node {{ $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : '') }} 
                                                    {{ $nodeClass }} rounded-circle d-flex align-items-center justify-content-center text-white"
                                             style="width: 80px; height: 80px; font-size: 24px;"
                                             data-bs-toggle="tooltip" 
                                             data-bs-placement="top" 
                                             title="{{ $stage->title }} - {{ $chip }}">
                                            {{ $badge }}
                                        </div>
                                        
                                        @if($isCompleted)
                                            <div class="achievement-badge">
                                                <i class="fas fa-crown text-warning"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <h6 class="fw-bold text-white mb-1">{{ $stage->title }}</h6>
                                        <span class="badge {{ $isCompleted ? 'bg-success' : ($isUnlocked ? 'bg-primary' : 'bg-secondary') }} px-2 py-1">
                                            {{ $chip }}
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        @if($isUnlocked)
                                            <a href="{{ route('stages.enter', $stage) }}" 
                                               class="btn btn-game btn-sm px-3 py-2 rounded-pill">
                                                <i class="fas fa-play me-1"></i>
                                                {{ $ctaLabel }}
                                            </a>
                                        @else
                                            <button class="btn btn-secondary btn-sm px-3 py-2 rounded-pill" disabled>
                                                <i class="fas fa-lock me-1"></i>
                                                Locked
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Mobile Vertical Stage List -->
                        <div class="d-md-none">
                            @foreach($stages as $stage)
                                @php
                                    $p = $progressByStage[$stage->id] ?? null;
                                    $isCompleted = (bool) ($p->post_completed_at ?? false);
                                    $hasPre = (bool) ($p && $p->pre_completed_at);
                                    $isUnlocked = (bool) ($stage->unlocked ?? false);
                                    
                                    $badge = $isCompleted ? '🏆' : ($isUnlocked ? '🚀' : '🔒');
                                    $statusClass = $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : 'locked');
                                    $chip = $isCompleted ? 'Mastered!' : ($isUnlocked ? ($hasPre ? 'In Progress' : 'Ready to Start') : 'Locked');
                                    $ctaLabel = $isUnlocked ? ($hasPre ? 'Continue' : 'Start') : 'Locked';
                                @endphp

                                <div class="mobile-stage-item {{ $statusClass }} rounded-3 p-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="rounded-circle {{ $isCompleted ? 'bg-success' : ($isUnlocked ? 'bg-primary' : 'bg-secondary') }} text-white d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ $badge }}
                                            </div>
                                        </div>
                                        <div class="flex-fill">
                                            <h6 class="fw-bold text-white mb-1">{{ $stage->title }}</h6>
                                            <small class="text-light">{{ $chip }}</small>
                                        </div>
                                        <div>
                                            @if($isUnlocked)
                                                <a href="{{ route('stages.enter', $stage) }}" 
                                                   class="btn btn-game btn-sm px-3 py-1 rounded-pill">
                                                    {{ $ctaLabel }}
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm px-3 py-1 rounded-pill" disabled>
                                                    Locked
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Stage Cards -->
            <div class="row g-4">
                @foreach($stages as $stage)
                    @php
                        $p = $progressByStage[$stage->id] ?? null;
                        $isCompleted = (bool) ($p->post_completed_at ?? false);
                        $hasPre = (bool) ($p && $p->pre_completed_at);
                        $isUnlocked = (bool) ($stage->unlocked ?? false);
                        
                        $headerClass = $isCompleted ? 'bg-success text-white' : ($isUnlocked ? 'bg-primary text-white' : 'bg-secondary text-white');
                        $preText = $p ? ($p->pre_completed_at ? 'Completed ✅' : 'Pending') : 'Not Started';
                        $postText = $p ? ($p->post_completed_at ? 'Completed ✅' : 'Pending') : 'Not Started';
                        $levelsCount = $stage->levels_count ?? 0;
                        
                        // Calculate stars
                        $stars = 0;
                        if($p && $p->stars_per_level) {
                            foreach($p->stars_per_level as $s) { 
                                $stars += (int) $s; 
                            }
                        }
                        
                        // Calculate progress
                        $unlockedTo = $p ? (int) $p->unlocked_to_level : 0;
                        $completedApprox = min($unlockedTo, $levelsCount);
                        $pct = $levelsCount > 0 ? intval(($completedApprox / $levelsCount) * 100) : 0;
                        
                        $cardCta = $isUnlocked ? ($hasPre ? 'Enter Stage' : 'Start Pre-Assessment') : 'Locked';
                    @endphp

                    <div class="col-lg-6">
                        <div class="stage-card rounded-4 h-100 overflow-hidden">
                            <!-- Card Header -->
                            <div class="{{ $headerClass }} p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-layer-group me-2 fs-5"></i>
                                        <h5 class="mb-0 fw-bold">{{ $stage->title }}</h5>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tasks me-1"></i>
                                        <small>{{ $levelsCount }} Levels</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4">
                                <!-- Assessment Status -->
                                <div class="row g-3 mb-4">
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3" style="background: rgba(255,255,255,0.1);">
                                            <i class="fas fa-clipboard-list text-info fs-4 mb-1"></i>
                                            <div class="small text-light">Pre-Assessment</div>
                                            <div class="fw-bold small">{{ $preText }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3" style="background: rgba(255,255,255,0.1);">
                                            <i class="fas fa-unlock-alt text-warning fs-4 mb-1"></i>
                                            <div class="small text-light">Unlocked To</div>
                                            <div class="fw-bold small">Level {{ $unlockedTo }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3" style="background: rgba(255,255,255,0.1);">
                                            <i class="fas fa-graduation-cap text-success fs-4 mb-1"></i>
                                            <div class="small text-light">Post-Assessment</div>
                                            <div class="fw-bold small">{{ $postText }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stars and Progress -->
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="stars-container">
                                        @if($stars > 0)
                                            @for($i = 0; $i < min($stars, 10); $i++)
                                                <span class="star-animation text-warning fs-5" style="animation-delay: {{ $i * 0.1 }}s;">⭐</span>
                                            @endfor
                                            @if($stars > 10)
                                                <span class="badge bg-warning text-dark ms-2">+{{ $stars - 10 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-light small">
                                                <i class="far fa-star me-1"></i>
                                                No stars earned yet
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <div class="progress-container position-relative d-inline-block">
                                            <svg class="progress-ring" width="50" height="50">
                                                <circle class="progress-ring-circle" 
                                                        stroke="{{ $isCompleted ? '#198754' : '#0d6efd' }}" 
                                                        stroke-width="4" 
                                                        fill="transparent" 
                                                        r="20" 
                                                        cx="25" 
                                                        cy="25"
                                                        style="stroke-dashoffset: calc(251.2 - (251.2 * {{ $pct }}) / 100);"/>
                                            </svg>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <small class="fw-bold text-white">{{ $pct }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="text-end">
                                    @if($isUnlocked)
                                        <a href="{{ route('stages.enter', $stage) }}" 
                                           class="btn btn-game px-4 py-2 rounded-pill">
                                            <i class="fas fa-{{ $isCompleted ? 'redo' : 'play' }} me-2"></i>
                                            {{ $cardCta }}
                                        </a>
                                    @else
                                        <button class="btn btn-secondary px-4 py-2 rounded-pill" disabled>
                                            <i class="fas fa-lock me-2"></i>
                                            Complete Previous Stage
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover focus'
                });
            });

            // Add click effects to buttons
            const gameButtons = document.querySelectorAll('.btn-game');
            gameButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = button.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    
                    button.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Animate stage nodes on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.stage-node, .stage-card').forEach(el => {
                observer.observe(el);
            });

            // Add floating particles animation
            function createParticle() {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                
                const particlesContainer = document.querySelector('.floating-particles');
                if (particlesContainer) {
                    particlesContainer.appendChild(particle);
                    
                    // Remove particle after animation
                    setTimeout(() => {
                        particle.remove();
                    }, 6000);
                }
            }

            // Create particles periodically
            setInterval(createParticle, 800);

            // Add success sound effect simulation (visual feedback)
            const completedNodes = document.querySelectorAll('.stage-node.completed');
            completedNodes.forEach(node => {
                node.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.1) rotate(5deg)';
                });
                
                node.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-5px) scale(1.05) rotate(0deg)';
                });
            });

            // Animate progress rings
            const progressRings = document.querySelectorAll('.progress-ring-circle');
            progressRings.forEach(ring => {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            ring.style.transition = 'stroke-dashoffset 1.5s ease-in-out';
                        }
                    });
                });
                observer.observe(ring);
            });

            // Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    const focused = document.activeElement;
                    if (focused.classList.contains('stage-node')) {
                        const button = focused.closest('.text-center').querySelector('.btn');
                        if (button && !button.disabled) {
                            button.click();
                        }
                    }
                }
            });

            // Mobile touch improvements
            if ('ontouchstart' in window) {
                document.querySelectorAll('.stage-card, .mobile-stage-item').forEach(card => {
                    card.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                    });
                    
                    card.addEventListener('touchend', function() {
                        this.style.transform = '';
                    });
                });
            }
        });

        // Add CSS animation keyframes dynamically
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- My Progress Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-white" id="progressModalLabel">
                        <i class="fas fa-user-astronaut me-2 text-primary"></i> Your Journey Stats
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Rank -->
                    <div class="p-3 rounded-3 mb-3" style="background:linear-gradient(90deg,rgba(238,242,255,0.2),rgba(245,243,255,0.2));">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small text-light">Python Level</div>
                                <div class="fs-5 fw-bold text-white">
                                    {{ $currentRank['name'] }}
                                    <span class="text-light fw-normal">({{ $totalPointsBlade }} pts)</span>
                                </div>
                                <div class="small text-light">Next: {{ $nextRank['name'] ?? 'MAX' }}</div>
                            </div>
                            <div style="min-width:260px;">
                                <div class="progress" style="height:10px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $rankPct }}%"
                                         aria-valuenow="{{ $rankPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-light mt-1">
                                    <span>{{ $currentRank['points'] }} pts</span>
                                    <span>{{ $nextRank['points'] ?? $totalPointsBlade }} pts</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Counters -->
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background: rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-success">{{ $stagesCompletedBlade }}/{{ $stagesTotalBlade }}</div>
                                <div class="small text-light">Stages Completed</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background: rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-primary">{{ $levelsCompletedBlade }}/{{ $levelsTotalBlade }}</div>
                                <div class="small text-light">Levels Completed</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background: rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-warning">{{ $totalStarsBlade }}</div>
                                <div class="small text-light">Total Stars</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background: rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-info">{{ $totalPointsBlade }}</div>
                                <div class="small text-light">Total Points</div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessments -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border" style="border-color: var(--neon-purple) !important;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clipboard-list me-3 text-info"></i>
                                    <div>
                                        <div class="fw-bold text-white">Pre-Assessments</div>
                                        <div class="small text-light">{{ $preDoneBlade }} completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border" style="border-color: var(--neon-purple) !important;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-graduation-cap me-3 text-success"></i>
                                    <div>
                                        <div class="fw-bold text-white">Post-Assessments</div>
                                        <div class="small text-light">{{ $postDoneBlade }} completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next best actions -->
                    <div class="mt-3 p-3 rounded-3" style="background:rgba(255,255,255,0.1);">
                        <div class="fw-bold mb-1 text-white">
                            <i class="fas fa-lightbulb me-2 text-warning"></i>Next Best Actions
                        </div>
                        <ul class="small mb-0 text-light">
                            @if($nextStageBlade)
                                <li>
                                    Continue <strong>{{ $nextStageBlade->title }}</strong> —
                                    level {{ (($progressByStage[$nextStageBlade->id]->unlocked_to_level ?? 0) + 1) }} is waiting.
                                </li>
                            @else
                                <li>Great job! All unlocked levels are done. Improve stars to boost your rank.</li>
                            @endif
                            <li>Retake any 2⭐ level and aim for 3⭐ to earn more points.</li>
                        </ul>
                    </div>

                  <!-- Per-stage mini table -->
<div class="mt-3">
  <div class="fw-bold mb-2 text-white">
    <i class="fas fa-list-check me-2 text-primary"></i>
    Per-Stage Breakdown
  </div>

  <div class="card rounded-3 border-0 shadow-sm" style="background:rgba(20,16,40,.65);">
    <div class="table-responsive">
      <table class="table table-dark table-striped table-hover align-middle mb-0">
        <thead class="sticky-top" style="background:linear-gradient(90deg, rgba(0,179,255,.2), rgba(185,103,255,.2));">
          <tr>
            <th class="border-0 text-uppercase small fw-bold">Stage</th>
            <th class="border-0 text-center text-uppercase small fw-bold">Levels</th>
            <th class="border-0 text-center text-uppercase small fw-bold">Unlocked</th>
            <th class="border-0 text-center text-uppercase small fw-bold">Stars</th>
            <th class="border-0 text-center text-uppercase small fw-bold">Pre</th>
            <th class="border-0 text-center text-uppercase small fw-bold">Post</th>
          </tr>
        </thead>
        <tbody>
          @foreach($stages as $s)
    @php
  $pp = $progressByStage[$s->id] ?? null;

  $levels     = (int) ($s->levels_count ?? 0);
  $unlockedTo = (int) ($pp->unlocked_to_level ?? 0);

  // What we SHOW: clamp to total levels so we never see "4 / 3"
  $displayUnlocked = min($unlockedTo, $levels);

  // Percent for the bar
  $pct = $levels > 0 ? intval(($displayUnlocked / $levels) * 100) : 0;

  // Stars sum
  $starsSum = $pp && $pp->stars_per_level ? array_sum($pp->stars_per_level) : 0;
@endphp
<tr>
  <td class="fw-semibold">
    <span class="me-2">{{ $s->title }}</span>
    @if(optional($pp)->post_completed_at)
      <span class="badge bg-success-subtle text-success border border-success-subtle">Mastered</span>
    @elseif(optional($pp)->pre_completed_at)
      <span class="badge bg-info-subtle text-info border border-info-subtle">In progress</span>
    @else
      <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Locked</span>
    @endif
  </td>

  <td class="text-center">{{ $levels }}</td>

  <td class="text-center" style="min-width:160px;">
    <div class="small text-light mb-1">
      Lvl {{ $displayUnlocked }} / {{ $levels }}
      @if($levels > 0 && $displayUnlocked >= $levels)
        <span class="badge bg-success ms-1">All cleared</span>
      @endif
    </div>

    <div class="progress progress-neon mx-auto" style="height:6px; max-width:140px;">
      <div class="progress-bar bg-neon" role="progressbar"
           style="width: {{ $pct }}%"
           aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    @if($unlockedTo > $levels)
      <div class="mt-1 small text-muted">
        <em>(next index {{ $unlockedTo }}, capped for display)</em>
      </div>
    @endif
  </td>

  <td class="text-center">
    @if($starsSum > 0)
      <span class="text-warning fw-bold">{{ $starsSum }}</span>
      <span class="opacity-75">⭐</span>
    @else
      <span class="text-muted">—</span>
    @endif
  </td>

  <td class="text-center">
    @if($pp && $pp->pre_completed_at)
      <span class="badge rounded-pill bg-success"><i class="fas fa-check me-1"></i>Done</span>
    @else
      <span class="badge rounded-pill bg-secondary"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
    @endif
  </td>

  <td class="text-center">
    @if($pp && $pp->post_completed_at)
      <span class="badge rounded-pill bg-success"><i class="fas fa-crown me-1"></i>Cleared</span>
    @else
      <span class="badge rounded-pill bg-secondary"><i class="fas fa-lock me-1"></i>Pending</span>
    @endif
  </td>
</tr>

          @endforeach

          @if(count($stages) === 0)
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                <i class="fas fa-meteor me-2"></i>No stages yet. Come back after your first adventure!
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>


                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-game rounded-pill">
                        <i class="fas fa-bolt me-2"></i> Go Improve Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>