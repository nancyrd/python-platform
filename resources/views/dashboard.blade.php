<x-app-layout>
    <x-slot name="header">
       <div class="row align-items-center">
    <!-- LEFT SIDE -->
    <div class="col">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-map-marked-alt text-light fs-2 me-2"></i>
            <h2 class="mb-1 fw-bold text-white">Python Quest Universe</h2>
        </div>
        <p class="mb-0 text-light small">
            <i class="fas fa-info-circle me-1"></i>
            For each Stage you will be redirected to a quiz to test your knowledge.<br>
            Levels unlock based on your score.<br>
            Start by learning the lesson first by clicking <b>"Learn Lesson"</b>, then proceed with the game.
        </p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="col-auto d-flex align-items-center">
       <button class="btn btn-game rounded-pill me-3" 
        data-bs-toggle="modal" 
        data-bs-target="#progressModal" 
        type="button">
    <i class="fas fa-chart-line me-2"></i> My Progress
</button>

        <div class="achievement-summary d-none d-md-flex">
            <div class="text-center me-4">
                <div class="fw-bold text-warning fs-4">
                    {{ collect($progressByStage)->sum(function($p) { return $p && $p->stars_per_level ? array_sum($p->stars_per_level) : 0; }) }}
                </div>
                <small class="text-light">Total Stars</small>
            </div>
            <div class="text-center">
                <div class="fw-bold text-success fs-4">
                    {{ collect($progressByStage)->filter(function($p) { return $p && $p->post_completed_at; })->count() }}/{{ count($stages) }}
                </div>
                <small class="text-light">Completed</small>
            </div>
        </div>
    </div>
</div>

    </x-slot>
    
    @php
        $totalStarsBlade = collect($progressByStage)->sum(function($p) {
            return $p && $p->stars_per_level ? array_sum($p->stars_per_level) : 0;
        });
        $totalPointsBlade = $totalStarsBlade * 10;
        $stagesTotalBlade = count($stages);
        $stagesCompletedBlade = collect($progressByStage)->filter(function($p){
            return $p && $p->post_completed_at;
        })->count();
        $levelsTotalBlade = (int) collect($stages)->sum('levels_count');
        $levelsCompletedBlade = \App\Models\UserLevelProgress::where('user_id', auth()->id())
            ->where('passed', true)->count();
        $preDoneBlade  = collect($progressByStage)->filter(fn($p) => $p && $p->pre_completed_at)->count();
        $postDoneBlade = $stagesCompletedBlade;
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
        $nextStageBlade = collect($stages)->first(function($s) use ($progressByStage){
            $p = $progressByStage[$s->id] ?? null;
            return $p && ($p->unlocked_to_level ?? 0) < ($s->levels_count ?? 0);
        });
        
        // Find the last opened stage for character position
        $lastOpenedStageIndex = 0;
        $maxUnlockedLevel = 0;
        foreach ($stages as $index => $stage) {
            $p = $progressByStage[$stage->id] ?? null;
            $unlockedTo = $p ? (int) $p->unlocked_to_level : 0;
            if ($unlockedTo > 0 || ($stage->unlocked ?? false)) {
                $lastOpenedStageIndex = $index;
            }
        }
    @endphp
    
    <style>
        /* ===================== ENHANCED SNAKE MAP ===================== */
        :root{
          --bg-start:#3B146B; --bg-end:#1A082D;
          --primary:#7A2EA5;  --accent:#B967FF;
          --card:#EDE6FF;     --card-brd:rgba(122,46,165,.32);
          --tile:#F2EBFF;     --ink:#2B1F44; --muted:#5B556A;
          --success:#16A34A;  --warn:#F59E0B; --ring-track:#E4DBFF;
          --map-path: #4A2C7A; --map-node: #6A3FAB;
          --character: #FFD700;
          --snake-body: #8B5CF6;
          --snake-head: #A78BFA;
        }
        
/* Add to your style block */
.page-header,
[x-slot="header"],
.app-header {
  position: static !important;
  position: relative !important;
}
           header, .header, [class*="header"], x-slot[name="header"], 
        .row.align-items-center {
          position: static !important;
          position: relative !important;
        }
        body{ background:linear-gradient(45deg,var(--bg-start),var(--bg-end));
              min-height:100vh;margin:0;padding:0;overflow-x:hidden;
              font-family:'Orbitron','Arial',sans-serif;color:#fff; }
        /* replace bootstrap blues with purples */
        .bg-primary,.badge.bg-primary,.progress-bar.bg-primary{background-color:var(--primary)!important;color:#fff!important}
        .text-primary{color:var(--primary)!important}
        .bg-info,.badge.bg-info,.progress-bar.bg-info{background-color:var(--accent)!important;color:#1a082d!important}
        .text-info{color:var(--accent)!important}
        .bg-secondary{background-color:rgba(214,182,255,.16)!important;color:#ddd!important}
        .bg-cosmic{background:linear-gradient(90deg,#3B146B,var(--primary))!important;color:#fff!important}
        .text-cosmic{color:var(--primary)!important}
        .text-neon{color:var(--accent)!important}
        .learning-map-container{min-height:100vh;position:relative}
        .floating-particles{position:absolute;inset:0;z-index:1;overflow:hidden;pointer-events:none}
        .particle{position:absolute;width:4px;height:4px;background:rgba(255,255,255,.35);border-radius:50%;animation:float 6s ease-in-out infinite}
        @keyframes float{0%,100%{transform:translateY(0) rotate(0);opacity:.35}50%{transform:translateY(-18px) rotate(180deg);opacity:.8}}
        .learning-path{position:relative;z-index:2}
        /* Add this at the end of your style block */
.modal-backdrop {
    position: fixed !important;
}

body.modal-open {
    overflow: hidden !important;
    position: static !important;
}
        /* Enhanced Snake Map Styles */
        .map-container {
            position: relative;
            background: linear-gradient(135deg, rgba(26, 8, 45, 0.8), rgba(59, 20, 107, 0.8));
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: 2px solid rgba(185, 103, 255, 0.3);
        }
        
        .map-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23b967ff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: -1;
        }
        
        .map-title {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .snake-map {
            position: relative;
            height: 400px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .snake-path {
            position: absolute;
            top: 50%;
            left: 5%;
            right: 5%;
            height: 80px;
            transform: translateY(-50%);
            z-index: 1;
        }
        /* ===================== ENHANCED SNAKE MAP ===================== */
:root{
  --bg-start:#3B146B; --bg-end:#1A082D;
  --primary:#7A2EA5;  --accent:#B967FF;
  --card:#EDE6FF;     --card-brd:rgba(122,46,165,.32);
  --tile:#F2EBFF;     --ink:#2B1F44; --muted:#5B556A;
  --success:#16A34A;  --warn:#F59E0B; --ring-track:#E4DBFF;
  --map-path: #4A2C7A; --map-node: #6A3FAB;
  --character: #FFD700;
  --snake-body: #8B5CF6;
  --snake-head: #A78BFA;
}

/* Force header to scroll naturally */
header, .header, [class*="header"], x-slot[name="header"], 
.row.align-items-center {
  position: static !important;
  position: relative !important;
}

/* Remove any sticky/fixed positioning from parent containers */


body{ background:linear-gradient(45deg,var(--bg-start),var(--bg-end));
      min-height:100vh;margin:0;padding:0;overflow-x:hidden;
      font-family:'Orbitron','Arial',sans-serif;color:#fff; }

/* replace bootstrap blues with purples */
.bg-primary,.badge.bg-primary,.progress-bar.bg-primary{background-color:var(--primary)!important;color:#fff!important}
.text-primary{color:var(--primary)!important}
.bg-info,.badge.bg-info,.progress-bar.bg-info{background-color:var(--accent)!important;color:#1a082d!important}
.text-info{color:var(--accent)!important}
.bg-secondary{background-color:rgba(214,182,255,.16)!important;color:#ddd!important}
.bg-cosmic{background:linear-gradient(90deg,#3B146B,var(--primary))!important;color:#fff!important}
.text-cosmic{color:var(--primary)!important}
.text-neon{color:var(--accent)!important}
.learning-map-container{min-height:100vh;position:relative}
.floating-particles{position:absolute;inset:0;z-index:1;overflow:hidden;pointer-events:none}
.particle{position:absolute;width:4px;height:4px;background:rgba(255,255,255,.35);border-radius:50%;animation:float 6s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0) rotate(0);opacity:.35}50%{transform:translateY(-18px) rotate(180deg);opacity:.8}}
.learning-path{position:relative;z-index:2}

/* Enhanced Snake Map Styles */
.map-container {
    position: relative;
    background: linear-gradient(135deg, rgba(26, 8, 45, 0.8), rgba(59, 20, 107, 0.8));
    border-radius: 1.5rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    border: 2px solid rgba(185, 103, 255, 0.3);
}

.map-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23b967ff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    z-index: -1;
}

.map-title {
    text-align: center;
    margin-bottom: 1.5rem;
    color: #fff;
    font-size: 1.8rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.snake-map {
    position: relative;
    height: 400px;
    margin: 0 auto;
    overflow-x: auto;
    overflow-y: hidden;
}

/* Custom scrollbar styling */
.snake-map::-webkit-scrollbar {
    height: 10px;
}

.snake-map::-webkit-scrollbar-track {
    background: rgba(26, 8, 45, 0.5);
    border-radius: 10px;
}

.snake-map::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: 10px;
}

.snake-map::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(90deg, var(--accent), var(--primary));
}

/* Firefox scrollbar */
.snake-map {
    scrollbar-width: thin;
    scrollbar-color: var(--accent) rgba(26, 8, 45, 0.5);
}
        .snake-body {
            position: absolute;
            height: 60px;
            background: linear-gradient(90deg, var(--snake-body), var(--snake-head));
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            z-index: 1;
        }
        
        .snake-head {
            position: absolute;
            width: 80px;
            height: 80px;
            background: var(--snake-head);
            border-radius: 50%;
            top: -10px;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 6px 20px rgba(167, 139, 250, 0.6);
        }
        
        .snake-eye {
            position: absolute;
            width: 12px;
            height: 12px;
            background: #fff;
            border-radius: 50%;
            top: 25px;
        }
        
        .snake-eye.left {
            left: 20px;
        }
        
        .snake-eye.right {
            right: 20px;
        }
        
        .snake-tongue {
            position: absolute;
            width: 30px;
            height: 8px;
            background: #ff6b6b;
            border-radius: 4px;
            top: 45px;
            left: 25px;
            transform-origin: left center;
            animation: tongueWiggle 1.5s ease-in-out infinite;
        }
        
        @keyframes tongueWiggle {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }
        
        .stage-nodes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
        }
        
        .stage-node {
    position: absolute;
    width: 150px;              /* was 100px → bigger nodes */
    height: 150px;
    background: var(--map-node);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    cursor: pointer;
    overflow: hidden;
    border: 4px solid transparent;
    z-index: 3;
}

.stage-node:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.4);
}

/* Adjust number and text inside */
.stage-number {
    font-size: 3rem;           /* was 2.5rem */
    font-weight: 800;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stage-node-title {
    font-size: 1rem;           /* was 0.8rem */
    text-align: center;
    max-width: 90%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stage-node-status {
    position: absolute;
    bottom: 8px;
    font-size: 0.8rem;          /* slightly larger */
    padding: 0.3rem 0.8rem;
    border-radius: 1rem;
    background: rgba(0, 0, 0, 0.3);
    font-weight: 500;
}
        .stage-node.unlocked {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-color: var(--accent);
            animation: pulse-primary 2s ease-in-out infinite;
        }
        
        .stage-node.completed {
            background: linear-gradient(135deg, var(--success), #9c0d93ff);
            border-color: var(--success);
            animation: pulse-success 2s ease-in-out infinite;
        }
        
        @keyframes pulse-primary {
            0% { box-shadow: 0 0 0 0 rgba(185, 103, 255, 0.7); }
            70% { box-shadow: 0 0 0 16px rgba(185, 103, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(185, 103, 255, 0); }
        }
        
        @keyframes pulse-success {
            0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
            70% { box-shadow: 0 0 0 16px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }
        
        .stage-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.3rem;
        }
        
        .stage-node-title {
            font-size: 0.8rem;
            text-align: center;
            max-width: 90%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .stage-node-status {
            position: absolute;
            bottom: 5px;
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            background: rgba(0, 0, 0, 0.3);
        }
        
        .character {
            position: absolute;
            width: 50px;
            height: 50px;
            background: var(--character);
            border-radius: 50%;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 4;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
            40% { transform: translateX(-50%) translateY(-10px); }
            60% { transform: translateX(-50%) translateY(-5px); }
        }
        
        /* Stage Filters */
        .stage-filters {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .filter-btn {
            padding: 0.6rem 1.8rem;
            border-radius: 2rem;
            border: 2px solid var(--accent);
            background: rgba(122, 46, 165, 0.2);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .filter-btn:hover {
            background: rgba(122, 46, 165, 0.4);
            transform: translateY(-2px);
        }
        
        .filter-btn.active {
            background: var(--accent);
            color: var(--bg-end);
        }
        
        
        .stage-card{background:var(--card);color:var(--ink);border:1px solid var(--card-brd);
                    border-radius:1rem;box-shadow:0 12px 34px rgba(25,10,41,.18),0 0 0 1px rgba(185,103,255,.06);
                    transition:.25s ease;position:relative;overflow:hidden}
        .stage-card:hover{transform:translateY(-4px);box-shadow:0 18px 48px rgba(25,10,41,.24)}
        .stage-card::before{display:none}
        .stage-card .p-4,.stage-card .p-4 .small,.stage-card .p-4 .fw-bold{color:var(--ink)!important}
        .stage-card .text-light{color:var(--muted)!important}
        .stage-card .text-muted{color:#6c6a76!important}
        .stage-card .p-3.bg-cosmic{background:linear-gradient(90deg,#3B146B,var(--primary))!important;color:#fff!important}
        .tile-soft{background:var(--tile)!important;border:1px solid var(--card-brd)!important;color:var(--ink)!important}
        .achievement-badge{position:absolute;top:-10px;right:-10px;width:30px;height:30px;background:linear-gradient(45deg,#ffd700,#ffed4a);
                           border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;animation:bounce 1s ease infinite alternate}
        .btn-game{background:linear-gradient(45deg,var(--primary),var(--accent));border:none;color:#fff;font-weight:600;letter-spacing:.5px;
                  transition:.25s ease;position:relative;overflow:hidden;box-shadow:0 10px 26px rgba(185,103,255,.25);
                  z-index:5;pointer-events:auto}
        .btn-game:hover{transform:translateY(-2px);box-shadow:0 12px 34px rgba(185,103,255,.45)}
        .btn-game::before{content:'';position:absolute;top:50%;left:50%;width:0;height:0;background:rgba(255,255,255,.20);
                          border-radius:50%;transform:translate(-50%,-50%);transition:width .6s,height .6s}
        .btn-game:hover::before{width:300px;height:300px}
        .mobile-stage-item{background:var(--card);color:var(--ink);border:1px solid var(--card-brd);
                           border-left:4px solid transparent}
        .mobile-stage-item.completed{border-left-color:var(--success)}
        .mobile-stage-item.unlocked{border-left-color:var(--accent)}
        .mobile-stage-item.locked{border-left-color:#6c757d}
        .stage-card .map-stage-title{color:var(--ink)!important}
        .modal-content{background:linear-gradient(135deg,var(--bg-start),var(--bg-end));border:1px solid var(--accent);color:#fff}
        .modal-header{border-bottom:1px solid var(--accent)}
        .modal-footer{border-top:1px solid var(--accent)}
        .table{color:#fff}
        .table th{border-color:var(--accent)}
        .table td{border-color:rgba(185,103,255,.30)}
        .progress-ring{transform:rotate(-90deg)}
        .progress-ring-circle,.progress-ring-track{stroke-dasharray:251.2;transition:stroke-dashoffset .6s ease-in-out}
        
        /* Mobile Responsive */
        @media (max-width:768px){
            .achievement-summary{display:none!important}
            .snake-map {
                height: 300px;
            }
            .stage-node {
                width: 70px;
                height: 70px;
            }
            .stage-number {
                font-size: 1.8rem;
            }
            .stage-node-title {
                font-size: 0.6rem;
            }
            .character {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
                top: -17px;
            }
            .snake-head {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            .snake-eye {
                width: 8px;
                height: 8px;
                top: 18px;
            }
            .snake-eye.left {
                left: 15px;
            }
            .snake-eye.right {
                right: 15px;
            }
     
            .snake-tongue {
                width: 20px;
                height: 6px;
                top: 32px;
                left: 20px;
            }
            
        }
    </style>
    
    <div class="learning-map-container py-4">
        <div class="floating-particles" aria-hidden="true">
            <div class="particle" style="left:10%;animation-delay:0s"></div>
            <div class="particle" style="left:20%;animation-delay:1s"></div>
            <div class="particle" style="left:30%;animation-delay:2s"></div>
            <div class="particle" style="left:40%;animation-delay:.5s"></div>
            <div class="particle" style="left:50%;animation-delay:1.5s"></div>
            <div class="particle" style="left:60%;animation-delay:2.5s"></div>
            <div class="particle" style="left:70%;animation-delay:3s"></div>
            <div class="particle" style="left:80%;animation-delay:.8s"></div>
            <div class="particle" style="left:90%;animation-delay:1.8s"></div>
        </div>
        
        <div class="container-fluid learning-path px-3">
            <!-- Enhanced Snake Game Map -->
            <div class="map-container">
                <h3 class="map-title">Python Quest Snake Path</h3>
                
                <!-- Game Map -->
                <div class="snake-map">
                    <div class="snake-path">
                        <div class="snake-body"></div>
                        <div class="snake-head">
                            <div class="snake-eye left"></div>
                            <div class="snake-eye right"></div>
                            <div class="snake-tongue"></div>
                        </div>
                    </div>
                    
                    <div class="stage-nodes">
                        @foreach($stages as $index => $stage)
                            @php
                                $p = $progressByStage[$stage->id] ?? null;
                                $isCompleted = (bool) ($p->post_completed_at ?? false);
                                $hasPre = (bool) ($p && $p->pre_completed_at);
                                $isUnlocked = (bool) ($stage->unlocked ?? false);
                                $statusClass = $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : '');
                                $chip = $isCompleted ? 'Mastered!' : ($isUnlocked ? ($hasPre ? 'In Progress' : 'Ready') : 'Locked');
                                
                                // Calculate position for snake-like arrangement
                                $position = 'left: ' . (5 + ($index * 15)) . '%; top: 50%; transform: translateY(-50%);';
                                if ($index % 2 === 1) {
                                    $position = 'left: ' . (5 + ($index * 15)) . '%; top: 30%; transform: translateY(-50%);';
                                }
                                if ($index % 3 === 0 && $index > 0) {
                                    $position = 'left: ' . (5 + ($index * 15)) . '%; top: 70%; transform: translateY(-50%);';
                                }
                            @endphp
                         <div class="stage-node {{ $statusClass }}" 
     id="stage-{{ $index + 1 }}"
     data-index="{{ $index + 1 }}"
     style="{{ $position }}">
    <div class="stage-number">{{ $index + 1 }}</div>
    <div class="stage-node-title">{{ $stage->title }}</div>
    <div class="stage-node-status">{{ $chip }}</div>
</div>

                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Stage Filters (moved here) -->
            <div class="stage-filters">
                <button class="filter-btn active" data-filter="all">All Stages</button>
                <button class="filter-btn" data-filter="unlocked">Unlocked</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
            </div>
            
            <!-- Detailed Stage Cards -->
            <div class="row g-4">
                @foreach($stages as $stage)
                    @php
                        $p = $progressByStage[$stage->id] ?? null;
                        $isCompleted = (bool) ($p->post_completed_at ?? false);
                        $hasPre = (bool) ($p && $p->pre_completed_at);
                        $isUnlocked = (bool) ($stage->unlocked ?? false);
                        $preText = $p ? ($p->pre_completed_at ? 'Completed ✅' : 'Pending') : 'Not Started';
                        $postText = $p ? ($p->post_completed_at ? 'Completed ✅' : 'Pending') : 'Not Started';
                        $levelsCount = $stage->levels_count ?? 0;
                        $stars = $p && $p->stars_per_level ? array_sum($p->stars_per_level) : 0;
                        $unlockedTo = $p ? (int) $p->unlocked_to_level : 0;
                        $completedApprox = min($unlockedTo, $levelsCount);
                        $pct = $levelsCount > 0 ? intval(($completedApprox / $levelsCount) * 100) : 0;
                        $cardCta = $isUnlocked ? ($hasPre ? 'Enter Stage' : 'Start Pre-Assessment') : 'Locked';
                        $statusClass = $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : 'locked');
                    @endphp
                    <div class="col-lg-6 stage-card-container" data-stage-status="{{ $statusClass }}">
                        <div class="stage-card rounded-4 h-100 overflow-hidden">
                            <div class="p-3 bg-cosmic">
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
                            <div class="p-4">
                                <div class="row g-3 mb-4">
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3 tile-soft">
                                            <i class="fas fa-clipboard-list fs-4 mb-1" style="color:var(--accent)"></i>
                                            <div class="small">Pre-Assessment</div>
                                            <div class="fw-bold small">{{ $preText }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3 tile-soft">
                                            <i class="fas fa-unlock-alt fs-4 mb-1" style="color:var(--warn)"></i>
                                            <div class="small">Unlocked To</div>
                                            <div class="fw-bold small">Level {{ $unlockedTo }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-2 rounded-3 tile-soft">
                                            <i class="fas fa-graduation-cap fs-4 mb-1 text-success"></i>
                                            <div class="small">Post-Assessment</div>
                                            <div class="fw-bold small">{{ $postText }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="stars-container" style="color:var(--ink)">
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
                                            <svg class="progress-ring" width="50" height="50" role="img" aria-label="Stage progress">
                                                <circle class="progress-ring-track" stroke="var(--ring-track)" stroke-width="4" fill="transparent" r="20" cx="25" cy="25"/>
                                                <circle class="progress-ring-circle"
                                                        stroke="{{ $isCompleted ? '#16A34A' : 'var(--primary)' }}"
                                                        stroke-width="4" fill="transparent" r="20" cx="25" cy="25"
                                                        style="stroke-dashoffset: calc(251.2 - (251.2 * {{ $pct }}) / 100);"/>
                                            </svg>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <small class="fw-bold" style="color:var(--ink)">{{ $pct }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($isUnlocked)
                                        <a href="{{ route('stages.enter', $stage) }}" class="btn btn-game px-4 py-2 rounded-pill" role="button">
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
    
    <!-- Font Awesome + Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- My Progress Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-white" id="progressModalLabel">
                        <i class="fas fa-user-astronaut me-2" style="color:var(--accent)"></i> Your Journey Stats
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 rounded-3 mb-3" style="background:linear-gradient(90deg,rgba(214,182,255,.20),rgba(185,103,255,.20));">
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
                                         style="width: {{ $rankPct }}%" aria-valuenow="{{ $rankPct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-light mt-1">
                                    <span>{{ $currentRank['points'] }} pts</span>
                                    <span>{{ $nextRank['points'] ?? $totalPointsBlade }} pts</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background:rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-success">{{ $stagesCompletedBlade }}/{{ $stagesTotalBlade }}</div>
                                <div class="small text-light">Stages Completed</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background:rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-cosmic">{{ $levelsCompletedBlade }}/{{ $levelsTotalBlade }}</div>
                                <div class="small text-light">Levels Completed</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background:rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-warning">{{ $totalStarsBlade }}</div>
                                <div class="small text-light">Total Stars</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 text-center rounded-3" style="background:rgba(255,255,255,0.1);">
                                <div class="fw-bold fs-4 text-neon">{{ $totalPointsBlade }}</div>
                                <div class="small text-light">Total Points</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border" style="border-color:var(--accent)!important;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clipboard-list me-3" style="color:var(--accent)"></i>
                                    <div>
                                        <div class="fw-bold text-white">Pre-Assessments</div>
                                        <div class="small text-light">{{ $preDoneBlade }} completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border" style="border-color:var(--accent)!important;">
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
                    <div class="mt-3">
                        <div class="fw-bold mb-2 text-white">
                            <i class="fas fa-list-check me-2" style="color:var(--accent)"></i>
                            Per-Stage Breakdown
                        </div>
                        <div class="card rounded-3 border-0 shadow-sm" style="background:rgba(59,20,107,.65);">
                            <div class="table-responsive">
                                <table class="table table-dark table-striped table-hover align-middle mb-0">
                                    <thead class="sticky-top" style="background:linear-gradient(90deg, rgba(214,182,255,.22), rgba(185,103,255,.22));">
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
                                                $levels = (int) ($s->levels_count ?? 0);
                                                $unlockedTo = (int) ($pp->unlocked_to_level ?? 0);
                                                $displayUnlocked = min($unlockedTo, $levels);
                                                $pct = $levels > 0 ? intval(($displayUnlocked / $levels) * 100) : 0;
                                                $starsSum = $pp && $pp->stars_per_level ? array_sum($pp->stars_per_level) : 0;
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold">
                                                    <span class="me-2">{{ $s->title }}</span>
                                                    @if(optional($pp)->post_completed_at)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Mastered</span>
                                                    @elseif(optional($pp)->pre_completed_at)
                                                        <span class="badge" style="background:rgba(185,103,255,.25);color:#fff;border:1px solid rgba(185,103,255,.45)">In progress</span>
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
                                                    <div class="progress mx-auto" style="height:6px; max-width:140px; background:rgba(214,182,255,.18);">
                                                        <div class="progress-bar" role="progressbar"
                                                             style="width: {{ $pct }}%; background: var(--accent);"
                                                             aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    @if($unlockedTo > $levels)
                                                        <div class="mt-1 small text-muted"><em>(next index {{ $unlockedTo }}, capped for display)</em></div>
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
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-game rounded-pill" role="button">
                        <i class="fas fa-bolt me-2"></i> Go Improve Now
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el, {trigger:'hover focus'}));
        
        // Stage filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const stageCards = document.querySelectorAll('.stage-card-container');
        const stageNodes = document.querySelectorAll('.stage-node');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active filter button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                
                // Filter stage cards
                stageCards.forEach(card => {
                    const status = card.getAttribute('data-stage-status');
                    if (filter === 'all' || status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Filter stage nodes
                stageNodes.forEach(node => {
                    const status = node.getAttribute('data-status');
                    if (filter === 'all' || status === filter || (filter === 'unlocked' && status === 'completed')) {
                        node.style.display = 'flex';
                    } else {
                        node.style.display = 'none';
                    }
                });
            });
        });
      
document.addEventListener('DOMContentLoaded', function() {
    const snakeHead = document.querySelector('.snake-head');
    const stages = Array.from(document.querySelectorAll('.stage-node'));
    let currentIndex = 0; // start at the first stage

    function moveSnakeTo(nextIndex) {
        if (!snakeHead || !stages[nextIndex]) return;

        const nextNode = stages[nextIndex];
        const rect = nextNode.getBoundingClientRect();
        const containerRect = document.querySelector('.snake-map').getBoundingClientRect();

        // Calculate relative position inside the map
        const x = rect.left - containerRect.left + rect.width / 2 - snakeHead.offsetWidth / 2;
        const y = rect.top - containerRect.top + rect.height / 2 - snakeHead.offsetHeight / 2;

        // Animate the snake head
        snakeHead.style.transition = 'transform 1.5s ease-in-out';
        snakeHead.style.transform = `translate(${x}px, ${y}px)`;

        // Optional: Glow animation when it reaches a node
        snakeHead.addEventListener('transitionend', () => {
            nextNode.classList.add('highlighted');
            setTimeout(() => nextNode.classList.remove('highlighted'), 1000);
        }, { once: true });
    }

    // Simulate movement after completing a stage
    function simulateStageCompletion() {
        if (currentIndex < stages.length - 1) {
            moveSnakeTo(currentIndex + 1);
            currentIndex++;
        }
    }

    // Listen for a fake “stage completed” event (you can trigger this in real time from backend)
    document.addEventListener('stageCompleted', simulateStageCompletion);

    // Initial position
    moveSnakeTo(currentIndex);
});


        // ripple effect
        document.querySelectorAll('.btn-game').forEach(button=>{
          button.addEventListener('click', function(e){
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size/2;
            const y = e.clientY - rect.top - size/2;
            const ripple = document.createElement('span');
            ripple.style.cssText = `
              position:absolute;width:${size}px;height:${size}px;left:${x}px;top:${y}px;
              background:rgba(255,255,255,.3);border-radius:50%;transform:scale(0);
              animation:ripple .6s linear;pointer-events:none;`;
            button.appendChild(ripple);
            setTimeout(()=>ripple.remove(), 600);
          });
        });
        
        // SAFETY: force navigation even if some script preventsDefault on anchors
        document.addEventListener('click', function(e){
          const link = e.target.closest('a.btn-game[href]');
          if (!link) return;
          if (link.hasAttribute('data-bs-toggle')) return; // allow modal triggers
          // Ensure it navigates on next tick
          setTimeout(()=>{ if (link.href) window.location.href = link.href; }, 0);
        });
        
        // intersection animations
        const io = new IntersectionObserver((entries)=>entries.forEach(en=>{ if(en.isIntersecting){ en.target.style.animationPlayState='running'; }}),
          {threshold:.1, rootMargin:'0px 0px -50px 0px'});
        document.querySelectorAll('.stage-node, .stage-card').forEach(el=>io.observe(el));
        
        // particles
        const container = document.querySelector('.floating-particles');
        function createParticle(){
          const p = document.createElement('div');
          p.className='particle';
          p.style.left = Math.random()*100+'%';
          p.style.animationDelay = Math.random()*6+'s';
          p.style.animationDuration = (Math.random()*3+3)+'s';
          container && container.appendChild(p);
          setTimeout(()=>p.remove(), 6000);
        }
        setInterval(createParticle, 800);
        
        // completed nodes fun hover
        document.querySelectorAll('.stage-node.completed').forEach(node=>{
          node.addEventListener('mouseenter', ()=> node.style.transform='translateY(-10px) scale(1.1) rotate(5deg)');
          node.addEventListener('mouseleave', ()=> node.style.transform='translateY(-10px) scale(1.05) rotate(0deg)');
        });
        
        // ring transition
        document.querySelectorAll('.progress-ring-circle').forEach(ring=>{
          const o = new IntersectionObserver((ents)=>ents.forEach(en=>{ if(en.isIntersecting){ ring.style.transition='stroke-dashoffset 1.5s ease-in-out'; }}));
          o.observe(ring);
        });
        
        // keyboard activation
        document.addEventListener('keydown', function(e){
          if(e.key==='Enter' || e.key===' '){
            const focused = document.activeElement;
            if(focused && focused.classList.contains('stage-node')){
              const btn = focused.closest('.text-center')?.querySelector('.btn:not([disabled])');
              if(btn) btn.click();
            }
          }
        });
      });
      
      const style = document.createElement('style');
      style.textContent = `@keyframes ripple{ to{ transform:scale(4); opacity:0 } }`;
      document.head.appendChild(style);
    </script>
</x-app-layout>