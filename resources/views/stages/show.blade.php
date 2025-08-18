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

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .game-container {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23ffffff10" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-coin, .floating-gem, .floating-star {
            position: absolute;
            font-size: 20px;
            animation: float-around 8s ease-in-out infinite;
            opacity: 0.6;
        }

        .floating-coin { animation-delay: 0s; }
        .floating-gem { animation-delay: 2s; }
        .floating-star { animation-delay: 4s; }

        @keyframes float-around {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(90deg); }
            50% { transform: translateY(0px) rotate(180deg); }
            75% { transform: translateY(-15px) rotate(270deg); }
        }

        .game-header-container {
            background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
            backdrop-filter: blur(10px);
            border-bottom: 3px solid #ffd700;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .game-header-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,215,0,0.2), transparent);
            animation: shine 3s ease-in-out infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        .stage-icon-container {
            position: relative;
        }

        .stage-icon-wrapper {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #ffd700, #ffed4a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
            animation: pulse-gold 2s ease-in-out infinite;
        }

        .stage-icon {
            font-size: 24px;
            color: #333;
            animation: rotate-gentle 4s ease-in-out infinite;
        }

        @keyframes pulse-gold {
            0%, 100% { transform: scale(1); box-shadow: 0 0 20px rgba(255, 215, 0, 0.5); }
            50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(255, 215, 0, 0.8); }
        }

        @keyframes rotate-gentle {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(5deg); }
        }

        .stage-title {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(45deg, #ffd700, #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            letter-spacing: 1px;
        }

        .stage-subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
        }

        .btn-back-to-map {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-back-to-map:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
            color: white;
        }

        .assessment-boss-battle {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            border: 3px solid #e74c3c;
            border-radius: 20px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            margin: 30px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .assessment-boss-battle::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.1), transparent, rgba(255,255,255,0.1));
            animation: rotate 8s linear infinite;
        }

        @keyframes rotate {
            100% { transform: rotate(360deg); }
        }

        .boss-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
            box-shadow: 0 0 30px rgba(231, 76, 60, 0.6);
            animation: boss-breathe 2s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }

        @keyframes boss-breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .assessment-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            position: relative;
            z-index: 2;
        }

        .assessment-status {
            position: relative;
            z-index: 2;
        }

        .btn-boss-fight {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.4);
            position: relative;
            overflow: hidden;
            z-index: 2;
        }

        .btn-boss-fight:hover:not(:disabled) {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(231, 76, 60, 0.6);
            color: white;
        }

        .btn-boss-fight::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transition: all 0.5s ease;
            transform: translate(-50%, -50%);
        }

        .btn-boss-fight:hover::before {
            width: 300px;
            height: 300px;
        }

        .levels-arena {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
        }

        .arena-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            position: relative;
        }

        .arena-title::after {
            content: '⚔️ 🏆 ⚔️';
            display: block;
            font-size: 1rem;
            margin-top: 10px;
        }

        .level-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 3px solid #e9ecef;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .level-card.unlocked {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
            cursor: pointer;
        }

        .level-card.locked {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-color: #6c757d;
            opacity: 0.7;
        }

        .level-card:hover.unlocked {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .level-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .level-card:hover::before {
            opacity: 1;
        }

        .level-number {
            position: absolute;
            top: -15px;
            left: -15px;
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #ffd700, #ffed4a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.2rem;
            color: #333;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
            z-index: 3;
        }

        .level-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .level-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin: 15px 0 10px 0;
        }

        .stars-display {
            margin: 15px 0;
            font-size: 1.8rem;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .star {
            display: inline-block;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .star.earned {
            color: #ffd700;
            animation: twinkle 1.5s ease-in-out infinite alternate;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
        }

        .star.empty {
            color: #dee2e6;
        }

        .star:hover {
            transform: scale(1.2) rotate(15deg);
        }

        @keyframes twinkle {
            0% { opacity: 0.7; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.1); }
        }

        .btn-level {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-level:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-level:disabled {
            background: linear-gradient(45deg, #6c757d, #5a6268);
            cursor: not-allowed;
            opacity: 0.8;
        }

        .btn-level::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transition: all 0.5s ease;
            transform: translate(-50%, -50%);
        }

        .btn-level:hover::before {
            width: 200px;
            height: 200px;
        }

        .post-assessment-final-boss {
            background: linear-gradient(135deg, #fd7e14 0%, #e63946 100%);
            border: 4px solid #ffd700;
            border-radius: 25px;
            padding: 30px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .post-assessment-final-boss::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #ffd700, #ffed4a, #ffd700, #ffed4a);
            border-radius: 25px;
            z-index: -1;
            animation: border-glow 2s ease-in-out infinite;
        }

        @keyframes border-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .final-boss-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #ffd700, #ffed4a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #333;
            box-shadow: 0 0 40px rgba(255, 215, 0, 0.8);
            animation: final-boss-pulse 1.5s ease-in-out infinite;
            margin: 0 auto 20px;
        }

        @keyframes final-boss-pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        .final-boss-title {
            color: white;
            font-size: 2rem;
            font-weight: 900;
            text-align: center;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
            margin-bottom: 15px;
        }

        .btn-final-boss {
            background: linear-gradient(45deg, #ffd700, #ffed4a);
            color: #333;
            border: none;
            padding: 18px 40px;
            border-radius: 35px;
            font-weight: 900;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(255, 215, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-final-boss:hover:not(:disabled) {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(255, 215, 0, 0.6);
            color: #333;
        }

        .success-notification {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 15px;
            color: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
            animation: slideInFromTop 0.5s ease;
        }

        @keyframes slideInFromTop {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .completed-stamp {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.4);
            animation: stamp-appear 0.5s ease;
        }

        @keyframes stamp-appear {
            from { transform: scale(0) rotate(45deg); opacity: 0; }
            to { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        .locked-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(108, 117, 125, 0.8);
            backdrop-filter: blur(3px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            z-index: 5;
        }

        @media (max-width: 768px) {
            .stage-title {
                font-size: 1.5rem;
            }
            
            .arena-title {
                font-size: 1.5rem;
            }
            
            .level-card {
                padding: 20px;
            }
            
            .boss-icon, .final-boss-icon {
                width: 60px;
                height: 60px;
                font-size: 30px;
            }
        }

        .progress-bar-container {
            background: rgba(0,0,0,0.1);
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 10px;
            transition: width 1s ease;
            position: relative;
        }
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

/* Keep your existing styles below… */


        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: progress-shimmer 2s ease-in-out infinite;
        }

        @keyframes progress-shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>

  <div style="width:100vw; min-height:1px; padding:0; margin:0;">


        <!-- Floating Game Elements -->
        <div class="floating-elements">
            <div class="floating-coin" style="top: 10%; left: 5%;">🪙</div>
            <div class="floating-gem" style="top: 20%; left: 90%;">💎</div>
            <div class="floating-star" style="top: 60%; left: 3%;">⭐</div>
            <div class="floating-coin" style="top: 80%; left: 95%;">🪙</div>
            <div class="floating-gem" style="top: 40%; left: 2%;">💎</div>
            <div class="floating-star" style="top: 15%; left: 85%;">⭐</div>
        </div>

      <div class="w-100" style="padding:0; margin:0;">

            @if(session('status'))
                <div class="success-notification rounded-3 shadow-lg">
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
            <div class="assessment-boss-battle">
                <div class="d-flex align-items-center">
                    <div class="boss-icon me-4">🧠</div>
                    <div class="flex-fill">
                        <h3 class="assessment-title mb-2">Pre-Assessment Boss Battle</h3>
                        <div class="assessment-status text-white">
                            @if($progress->pre_completed_at)
                                <div class="d-flex align-items-center">
                                    <span class="me-3">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Victory Achieved! 
                                    </span>
                                    <small class="opacity-75">
                                        Conquered on {{ $progress->pre_completed_at->format('M j, Y \a\t H:i') }}
                                    </small>
                                </div>
                                <div class="progress-bar-container mt-2">
                                    <div class="progress-bar-fill" style="width: 100%;"></div>
                                </div>
                            @else
                                <div>
                                    <i class="fas fa-sword me-1"></i>
                                    Defeat this boss to unlock the arena levels!
                                </div>
                                <div class="progress-bar-container mt-2">
                                    <div class="progress-bar-fill" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if(!$progress->pre_completed_at && $pre)
                            <a class="btn btn-boss-fight" href="{{ route('assessments.show', $pre) }}">
                                <i class="fas fa-fist-raised me-2"></i>
                                Fight Boss
                            </a>
                        @else
                            <button class="btn btn-boss-fight" disabled style="opacity: 0.6;">
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
            <div class="levels-arena">
                <h2 class="arena-title">
                    Battle Arena Levels
                </h2>

                <div class="row g-4">
                    @foreach($stage->levels as $level)
                        @php
                            $unlocked = $level->index <= $progress->unlocked_to_level;
                            $stars = (int) data_get($progress->stars_per_level, (string)$level->index, 0);
                        @endphp
                        
                        <div class="col-lg-4 col-md-6">
                            <div class="level-card {{ $unlocked ? 'unlocked' : 'locked' }} position-relative">
                                <div class="level-number">{{ $level->index }}</div>
                                <div class="level-type-badge">{{ $level->type }}</div>
                                
                                @if(!$unlocked)
                                    <div class="locked-overlay">
                                        🔒
                                    </div>
                                @endif

                                <div class="level-title">{{ $level->title }}</div>
                                
                                <div class="stars-display">
                                    @for($i = 1; $i <= 3; $i++)
                                        <span class="star {{ $i <= $stars ? 'earned' : 'empty' }}">
                                            {{ $i <= $stars ? '⭐' : '☆' }}
                                        </span>
                                    @endfor
                                </div>

                                @if($stars > 0)
                                    <div class="mb-3 text-success fw-bold">
                                        <i class="fas fa-medal me-1"></i>
                                        {{ $stars }} Star{{ $stars > 1 ? 's' : '' }} Earned!
                                    </div>
                                @endif

                                <div class="mt-4">
                                  @if($unlocked)
    @php
        $levelProgress = \App\Models\UserLevelProgress::where('user_id', auth()->id())
            ->where('stage_id', $stage->id)
            ->where('level_id', $level->id)
            ->first();
    @endphp

    <a class="btn btn-level" href="{{ route('levels.show', $level) }}">
        <i class="fas fa-{{ $levelProgress && $levelProgress->passed ? 'redo' : 'play' }} me-2"></i>
        {{ $levelProgress && $levelProgress->passed ? 'Retake Level' : 'Enter Battle' }}
    </a>
@else
    <button class="btn btn-level" disabled>
        <i class="fas fa-lock me-2"></i>
        Locked
    </button>
@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Post-Assessment Final Boss -->
            <div class="post-assessment-final-boss position-relative">
                <div class="final-boss-icon">🏁</div>
                <h3 class="final-boss-title">FINAL BOSS BATTLE</h3>
                <div class="text-center text-white mb-4">
                    @if($progress->post_completed_at)
                        <div class="fs-5 mb-2">
                            <i class="fas fa-crown me-2"></i>
                            LEGENDARY VICTORY ACHIEVED!
                        </div>
                        <div class="opacity-75">
                            Conquered on {{ $progress->post_completed_at->format('M j, Y \a\t H:i') }}
                        </div>
                        <div class="progress-bar-container mt-3">
                            <div class="progress-bar-fill" style="width: 100%; background: linear-gradient(90deg, #ffd700, #ffed4a);"></div>
                        </div>
                    @else
                        <div class="fs-5 mb-2">
                            <i class="fas fa-dragon me-2"></i>
                            The Ultimate Challenge Awaits
                        </div>
                        <div class="opacity-75">Complete all arena levels to face the final boss</div>
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
                        <button class="btn btn-final-boss" disabled style="opacity: 0.6;">
                            <i class="fas fa-lock me-2"></i>
                            Boss Not Available
                        </button>
                    @endif
                </div>
                
                @if($progress->post_completed_at)
                    <div class="completed-stamp" style="top: 20px; right: 20px; background: linear-gradient(45deg, #ffd700, #ffed4a); color: #333;">
                        <i class="fas fa-crown me-1"></i>
                        LEGENDARY
                    </div>
                @endif
            </div>
            
        </div>
    </div>

    <!-- Game Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Sound effect simulation through visual feedback
            function playSuccessEffect(element) {
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = '';
                }, 200);
            }

            // Add click effects to all buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!this.disabled) {
                        // Create ripple effect
                        const ripple = document.createElement('span');
                        const rect = this.getBoundingClientRect();
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
                            animation: gameRipple 0.6s linear;
                            pointer-events: none;
                            z-index: 1000;
                        `;
                        
                        this.appendChild(ripple);
                        
                        setTimeout(() => {
                            ripple.remove();
                        }, 600);
                    }
                });
            });

            // Animate stars on hover
            document.querySelectorAll('.star').forEach(star => {
                star.addEventListener('mouseenter', function() {
                    if (this.classList.contains('earned')) {
                        this.style.transform = 'scale(1.3) rotate(15deg)';
                        this.style.textShadow = '0 0 15px rgba(255, 215, 0, 1)';
                    }
                });
                
                star.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.textShadow = '';
                });
            });

            // Level card hover effects
            document.querySelectorAll('.level-card.unlocked').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const levelNumber = this.querySelector('.level-number');
                    if (levelNumber) {
                        levelNumber.style.transform = 'scale(1.1) rotate(5deg)';
                        levelNumber.style.boxShadow = '0 6px 20px rgba(255, 215, 0, 0.6)';
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    const levelNumber = this.querySelector('.level-number');
                    if (levelNumber) {
                        levelNumber.style.transform = '';
                        levelNumber.style.boxShadow = '';
                    }
                });
                
                // Make entire card clickable for unlocked levels
                card.addEventListener('click', function() {
                    const button = this.querySelector('.btn-level:not(:disabled)');
                    if (button) {
                        button.click();
                    }
                });
            });

            // Boss battle breathing effect
            const bossIcons = document.querySelectorAll('.boss-icon, .final-boss-icon');
            bossIcons.forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.animationDuration = '0.5s';
                    this.style.transform = 'scale(1.2) rotate(10deg)';
                });
                
                icon.addEventListener('mouseleave', function() {
                    this.style.animationDuration = '2s';
                    this.style.transform = '';
                });
            });

            // Floating elements interaction
            document.querySelectorAll('.floating-coin, .floating-gem, .floating-star').forEach(element => {
                element.addEventListener('click', function() {
                    this.style.animation = 'none';
                    this.style.transform = 'scale(1.5) rotate(360deg)';
                    this.style.opacity = '0';
                    
                    setTimeout(() => {
                        this.style.animation = '';
                        this.style.transform = '';
                        this.style.opacity = '';
                    }, 1000);
                });
            });

            // Progress bar animation
            const progressBars = document.querySelectorAll('.progress-bar-fill');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const width = entry.target.style.width;
                        entry.target.style.width = '0%';
                        setTimeout(() => {
                            entry.target.style.width = width;
                        }, 100);
                    }
                });
            });

            progressBars.forEach(bar => {
                observer.observe(bar);
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    const focused = document.activeElement;
                    if (focused.classList.contains('level-card') && focused.classList.contains('unlocked')) {
                        const button = focused.querySelector('.btn-level:not(:disabled)');
                        if (button) {
                            e.preventDefault();
                            button.click();
                        }
                    }
                }
            });

            // Mobile touch feedback
            if ('ontouchstart' in window) {
                document.querySelectorAll('.level-card, .assessment-boss-battle, .post-assessment-final-boss').forEach(element => {
                    element.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                    });
                    
                    element.addEventListener('touchend', function() {
                        this.style.transform = '';
                    });
                });
            }

            // Easter egg: Konami code
            let konamiCode = [];
            const konamiSequence = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'KeyB', 'KeyA'];
            
            document.addEventListener('keydown', function(e) {
                konamiCode.push(e.code);
                if (konamiCode.length > konamiSequence.length) {
                    konamiCode.shift();
                }
                
                if (konamiCode.join(',') === konamiSequence.join(',')) {
                    // Easter egg activated
                    document.body.style.filter = 'hue-rotate(180deg)';
                    
                    // Create celebration
                    for (let i = 0; i < 20; i++) {
                        setTimeout(() => {
                            const celebration = document.createElement('div');
                            celebration.innerHTML = ['🎉', '✨', '🏆', '⭐'][Math.floor(Math.random() * 4)];
                            celebration.style.cssText = `
                                position: fixed;
                                top: ${Math.random() * 100}vh;
                                left: ${Math.random() * 100}vw;
                                font-size: 2rem;
                                z-index: 9999;
                                pointer-events: none;
                                animation: celebrate 2s ease-out forwards;
                            `;
                            document.body.appendChild(celebration);
                            
                            setTimeout(() => celebration.remove(), 2000);
                        }, i * 100);
                    }
                    
                    setTimeout(() => {
                        document.body.style.filter = '';
                    }, 5000);
                    
                    konamiCode = [];
                }
            });

            // Achievement system simulation
            const achievements = [
                { name: 'Speed Runner', condition: () => Date.now() - pageLoadTime < 5000 },
                { name: 'Explorer', condition: () => document.querySelectorAll('.level-card:hover').length > 0 },
                { name: 'Star Gazer', condition: () => document.querySelectorAll('.star:hover').length > 0 }
            ];

            const pageLoadTime = Date.now();
            let earnedAchievements = [];

            setInterval(() => {
                achievements.forEach(achievement => {
                    if (!earnedAchievements.includes(achievement.name) && achievement.condition()) {
                        earnedAchievements.push(achievement.name);
                        showAchievement(achievement.name);
                    }
                });
            }, 1000);

            function showAchievement(name) {
                const notification = document.createElement('div');
                notification.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trophy me-3 text-warning fs-4"></i>
                        <div>
                            <strong>Achievement Unlocked!</strong><br>
                            <small>${name}</small>
                        </div>
                    </div>
                `;
                notification.className = 'position-fixed top-0 end-0 m-3 p-3 bg-dark text-white rounded-3 shadow-lg';
                notification.style.cssText += `
                    z-index: 9999;
                    animation: slideInFromRight 0.5s ease;
                    max-width: 300px;
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.animation = 'slideOutToRight 0.5s ease forwards';
                    setTimeout(() => notification.remove(), 500);
                }, 3000);
            }
        });

        // Add dynamic CSS animations
        const dynamicStyles = document.createElement('style');
        dynamicStyles.textContent = `
            @keyframes gameRipple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            @keyframes celebrate {
                0% {
                    transform: translateY(0) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-200px) rotate(360deg);
                    opacity: 0;
                }
            }
            
            @keyframes slideInFromRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutToRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(dynamicStyles);
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>