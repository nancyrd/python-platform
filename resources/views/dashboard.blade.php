<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="learning-map-icon me-3">
                    <i class="fas fa-map-marked-alt text-primary fs-2"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-bold text-dark">Learning Adventure Map</h2>
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Complete stages to unlock your learning journey
                    </p>
                    <a class="btn btn-game rounded-pill ms-3" data-bs-toggle="modal" data-bs-target="#progressModal">
    <i class="fas fa-chart-line me-2"></i> My Progress
</a>

                </div>
            </div>
            <div class="achievement-summary d-none d-md-flex">
                <div class="text-center me-4">
                    <div class="fw-bold text-warning fs-4">{{ collect($progressByStage)->sum(function($p) { return $p && $p->stars_per_level ? array_sum($p->stars_per_level) : 0; }) }}</div>
                    <small class="text-muted">Total Stars</small>
                </div>
                <div class="text-center">
                    <div class="fw-bold text-success fs-4">{{ collect($progressByStage)->filter(function($p) { return $p && $p->post_completed_at; })->count() }}/{{ count($stages) }}</div>
                    <small class="text-muted">Completed</small>
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

    // Rank thresholds (adjust as you like)
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

    <!-- Custom Styles -->
    <style>
/* === Adventure Map Styles (ADD) === */
.adventure-map-wrap{
  position: relative;
  width: 100%;
  max-width: 1100px;
  margin: 0 auto 2rem auto;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0,0,0,0.12);
  background:
    radial-gradient(1200px 400px at 50% 120%, #effaf5 0%, rgba(239,250,245,0) 60%),
    linear-gradient(135deg,#a8e6ff 0%,#c8d8ff 35%, #f8f9ff 100%);
}

.adventure-map-bg{
  position: absolute; inset: 0; pointer-events: none;
  background:
    radial-gradient(120px 60px at 15% 25%, rgba(255,255,255,.6) 0%, rgba(255,255,255,0) 70%),
    radial-gradient(160px 80px at 80% 20%, rgba(255,255,255,.6) 0%, rgba(255,255,255,0) 70%),
    radial-gradient(200px 100px at 70% 80%, rgba(255,255,255,.6) 0%, rgba(255,255,255,0) 70%);
  z-index: 1;
}

.adventure-map-pad{ position: relative; z-index: 2; padding: 28px; }

#mapSvg{ width: 100%; height: 360px; display:block; border-radius: 20px; }

#journeyPath{
  fill: none;
  stroke: rgba(13,110,253,.22);
  stroke-width: 2.5;
  stroke-linecap: round;
  stroke-dasharray: 6 6;
}

#journeyPathActive{
  fill: none;
  stroke: #0d6efd;
  stroke-width: 4;
  stroke-linecap: round;
  filter: drop-shadow(0 4px 8px rgba(13,110,253,.25));
  stroke-dasharray: 0 9999; /* animated in */
}

.stage-marker{
  position: absolute;
  transform: translate(-50%,-50%);
  z-index: 5;
}

.stage-pin{
  width: 54px; height:54px; border-radius: 50%;
  display:flex; align-items:center; justify-content:center;
  background: white;
  border: 3px solid rgba(0,0,0,0.06);
  box-shadow: 0 8px 18px rgba(0,0,0,.10);
  transition: transform .2s ease, box-shadow .2s ease;
}
.stage-pin.completed{ border-color:#28a74533; box-shadow:0 8px 22px rgba(40,167,69,.18);}
.stage-pin.unlocked{ border-color:#0d6efd33; box-shadow:0 8px 22px rgba(13,110,253,.18);}
.stage-pin.locked{ opacity:.75; filter: grayscale(.15); }

.stage-pin:hover{ transform: translateY(-4px) scale(1.04); box-shadow:0 14px 28px rgba(0,0,0,.16); }

.stage-label{
  margin-top: 6px;
  text-align:center;
  font-size:.8rem;
  font-weight:700;
  text-shadow: 0 1px 0 rgba(255,255,255,.6);
}

.level-dots{
  display:flex; gap:4px; justify-content:center; margin-top:4px;
}
.level-dot{
  width:6px; height:6px; border-radius:50%; background:#dee2e6; opacity:.8;
}
.level-dot.done{ background:#28a745; }
.level-dot.unlock{ background:#0d6efd; }

.avatar{
  position:absolute; width:44px; height:44px; transform: translate(-50%,-50%);
  display:flex; align-items:center; justify-content:center; font-size:30px;
  z-index: 6; filter: drop-shadow(0 6px 10px rgba(0,0,0,.18));
  animation: bob 1.2s ease-in-out infinite;
}
@keyframes bob{0%,100%{transform:translate(-50%,-50%) translateY(0)}50%{transform:translate(-50%,-50%) translateY(-3px)}}

.badge-float{
  position:absolute; top:12px; right:12px; z-index:7;
  background: #0d6efd; color:white; border-radius:999px; padding:6px 10px; font-weight:700;
  box-shadow: 0 6px 14px rgba(13,110,253,.25);
}
















        .learning-map-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
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
        }

        .stage-node:hover {
            transform: translateY(-5px) scale(1.05);
        }

        .stage-node.completed {
            animation: pulse-success 2s ease-in-out infinite;
        }

        .stage-node.unlocked {
            animation: pulse-primary 2s ease-in-out infinite;
        }

        @keyframes pulse-success {
            0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(25, 135, 84, 0); }
            100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
        }

        @keyframes pulse-primary {
            0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(13, 110, 253, 0); }
            100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
        }

        .connector-line {
            height: 4px;
            background: linear-gradient(90deg, transparent, #6c757d, transparent);
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }

        .connector-line.active {
            background: linear-gradient(90deg, transparent, #0d6efd, transparent);
            animation: flow 2s linear infinite;
        }

        @keyframes flow {
            0% { background-position: -100% 0; }
            100% { background-position: 100% 0; }
        }

        .stage-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stage-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .stage-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
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
            background: linear-gradient(45deg, #667eea, #764ba2);
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
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .mobile-stage-item.completed {
            border-left-color: #198754;
        }

        .mobile-stage-item.unlocked {
            border-left-color: #0d6efd;
        }

        .mobile-stage-item.locked {
            border-left-color: #6c757d;
        }

        .tooltip-custom {
            background: rgba(0, 0, 0, 0.8);
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

        <div class="container learning-path">
            <!-- Desktop/Tablet Horizontal Stage Map -->
            <div class="row justify-content-center mb-5">
                <div class="col-12">
                    <div class="stage-card rounded-4 p-4 shadow-lg">
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
                                                    {{ $nodeClass }} rounded-circle d-flex align-items-center justify-content-center text-white shadow-lg"
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
                                        <h6 class="fw-bold text-dark mb-1">{{ $stage->title }}</h6>
                                        <span class="badge {{ $isCompleted ? 'bg-success' : ($isUnlocked ? 'bg-primary' : 'bg-secondary') }} px-2 py-1">
                                            {{ $chip }}
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        @if($isUnlocked)
                                            <a href="{{ route('stages.enter', $stage) }}" 
                                               class="btn btn-game btn-sm px-3 py-2 rounded-pill shadow-sm">
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

                                <div class="mobile-stage-item {{ $statusClass }} rounded-3 p-3 mb-3 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="rounded-circle {{ $isCompleted ? 'bg-success' : ($isUnlocked ? 'bg-primary' : 'bg-secondary') }} text-white d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px; font-size: 18px;">
                                                {{ $badge }}
                                            </div>
                                        </div>
                                        <div class="flex-fill">
                                            <h6 class="fw-bold text-dark mb-1">{{ $stage->title }}</h6>
                                            <small class="text-muted">{{ $chip }}</small>
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
                        
                        $headerClass = $isCompleted ? 'bg-success text-white' : ($isUnlocked ? 'bg-primary text-white' : 'bg-light text-dark');
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
                        <div class="stage-card rounded-4 shadow-lg h-100 overflow-hidden">
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
                                        <div class="text-center p-2 rounded-3 bg-light">
                                            <i class="fas fa-clipboard-list text-info fs-4 mb-1"></i>
                                            <div class="small text-muted">Pre-Assessment</div>
                                            <div class="fw-bold small">{{ $preText }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3 bg-light">
                                            <i class="fas fa-unlock-alt text-warning fs-4 mb-1"></i>
                                            <div class="small text-muted">Unlocked To</div>
                                            <div class="fw-bold small">Level {{ $unlockedTo }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3 bg-light">
                                            <i class="fas fa-graduation-cap text-success fs-4 mb-1"></i>
                                            <div class="small text-muted">Post-Assessment</div>
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
                                            <span class="text-muted small">
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
                                                <small class="fw-bold">{{ $pct }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="text-end">
                                    @if($isUnlocked)
                                        <a href="{{ route('stages.enter', $stage) }}" 
                                           class="btn btn-game px-4 py-2 rounded-pill shadow-sm">
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
        <h5 class="modal-title fw-bold" id="progressModalLabel">
          <i class="fas fa-user-astronaut me-2 text-primary"></i> Your Journey Stats
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Rank -->
        <div class="p-3 rounded-3 mb-3" style="background:linear-gradient(90deg,#eef2ff,#f5f3ff);">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-muted">Python Level</div>
              <div class="fs-5 fw-bold">
                {{ $currentRank['name'] }}
                <span class="text-muted fw-normal">({{ $totalPointsBlade }} pts)</span>
              </div>
              <div class="small">Next: {{ $nextRank['name'] ?? 'MAX' }}</div>
            </div>
            <div style="min-width:260px;">
              <div class="progress" style="height:10px;">
                <div class="progress-bar bg-success" role="progressbar"
                     style="width: {{ $rankPct }}%"
                     aria-valuenow="{{ $rankPct }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="d-flex justify-content-between small text-muted mt-1">
                <span>{{ $currentRank['points'] }} pts</span>
                <span>{{ $nextRank['points'] ?? $totalPointsBlade }} pts</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Counters -->
        <div class="row g-3">
          <div class="col-6 col-md-3">
            <div class="p-3 text-center bg-light rounded-3">
              <div class="fw-bold fs-4 text-success">{{ $stagesCompletedBlade }}/{{ $stagesTotalBlade }}</div>
              <div class="small text-muted">Stages Completed</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="p-3 text-center bg-light rounded-3">
              <div class="fw-bold fs-4 text-primary">{{ $levelsCompletedBlade }}/{{ $levelsTotalBlade }}</div>
              <div class="small text-muted">Levels Completed</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="p-3 text-center bg-light rounded-3">
              <div class="fw-bold fs-4 text-warning">{{ $totalStarsBlade }}</div>
              <div class="small text-muted">Total Stars</div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="p-3 text-center bg-light rounded-3">
              <div class="fw-bold fs-4 text-info">{{ $totalPointsBlade }}</div>
              <div class="small text-muted">Total Points</div>
            </div>
          </div>
        </div>

        <!-- Assessments -->
        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <div class="p-3 rounded-3 border">
              <div class="d-flex align-items-center">
                <i class="fas fa-clipboard-list me-3 text-info"></i>
                <div>
                  <div class="fw-bold">Pre-Assessments</div>
                  <div class="small text-muted">{{ $preDoneBlade }} completed</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 rounded-3 border">
              <div class="d-flex align-items-center">
                <i class="fas fa-graduation-cap me-3 text-success"></i>
                <div>
                  <div class="fw-bold">Post-Assessments</div>
                  <div class="small text-muted">{{ $postDoneBlade }} completed</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Next best actions -->
        <div class="mt-3 p-3 rounded-3" style="background:#f8f9fa;">
          <div class="fw-bold mb-1">
            <i class="fas fa-lightbulb me-2 text-warning"></i>Next Best Actions
          </div>
          <ul class="small mb-0">
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
          <div class="fw-bold mb-2"><i class="fas fa-list-check me-2 text-primary"></i>Per-Stage Breakdown</div>
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th>Stage</th>
                  <th class="text-center">Levels</th>
                  <th class="text-center">Unlocked To</th>
                  <th class="text-center">Stars</th>
                  <th class="text-center">Pre</th>
                  <th class="text-center">Post</th>
                </tr>
              </thead>
              <tbody>
                @foreach($stages as $s)
                  @php
                    $pp = $progressByStage[$s->id] ?? null;
                    $starsSum = $pp && $pp->stars_per_level ? array_sum($pp->stars_per_level) : 0;
                  @endphp
                  <tr>
                    <td>{{ $s->title }}</td>
                    <td class="text-center">{{ $s->levels_count ?? 0 }}</td>
                    <td class="text-center">{{ $pp->unlocked_to_level ?? 0 }}</td>
                    <td class="text-center">{{ $starsSum }}</td>
                    <td class="text-center">{!! $pp && $pp->pre_completed_at ? '✅' : '—' !!}</td>
                    <td class="text-center">{!! $pp && $pp->post_completed_at ? '✅' : '—' !!}</td>
                  </tr>
                @endforeach
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