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
                <a href="{{ route('dashboard') }}" class="btn btn-game">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Map
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        :root{
          --bg-start:#3B146B;
          --bg-end:#1A082D;
          --primary:#7A2EA5;
          --accent:#B967FF;
          --card:#EDE6FF;
          --card-brd:rgba(122,46,165,.32);
          --tile:#F2EBFF;
          --ink:#2B1F44;
          --muted:#5B556A;
          --success:#16A34A;
          --warn:#F59E0B;
        }

        html, body { height:100%; }
        body{
          margin:0;
          background:linear-gradient(45deg,var(--bg-start),var(--bg-end));
          color:#fff; font-family:'Orbitron','Arial',sans-serif;
        }

        .game-header-container{
          background:linear-gradient(135deg,var(--bg-start),var(--primary));
          border-bottom:1px solid var(--accent);
          box-shadow:0 10px 28px rgba(25,10,41,.35), inset 0 -1px 0 rgba(255,255,255,.06);
          position:relative; overflow:hidden; padding:14px 16px;
        }
        .game-header-container::before{
          content:''; position:absolute; inset:0; left:-100%;
          background:linear-gradient(90deg,transparent,rgba(185,103,255,.35),transparent);
          animation:headerShine 4s ease-in-out infinite;
        }
        @keyframes headerShine{0%{left:-100%}50%{left:100%}100%{left:100%}}

        .stage-icon-wrapper{
          width:64px;height:64px;border-radius:14px;
          background:linear-gradient(145deg,var(--bg-start),#321052);
          border:1px solid var(--accent);
          box-shadow:0 0 30px rgba(185,103,255,.18) inset, 0 0 22px rgba(185,103,255,.22);
          display:flex;align-items:center;justify-content:center;animation:pulse 2.4s ease-in-out infinite;
        }
        .stage-icon{ color:var(--accent); font-size:26px; }
        @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.06)}}

        .stage-title{
          font-size:2rem;font-weight:900;letter-spacing:.5px;margin:0;
          background:linear-gradient(45deg,var(--primary),var(--accent));
          -webkit-background-clip:text; -webkit-text-fill-color:transparent;
          text-shadow:0 6px 18px rgba(185,103,255,.28);
        }
        .stage-subtitle{ color:rgba(255,255,255,.75); font-size:.95rem; }

        .card-surface{
          background:var(--card); color:var(--ink);
          border:1px solid var(--card-brd);
          position:relative;
          box-shadow:0 12px 34px rgba(25,10,41,.18), 0 0 0 1px rgba(185,103,255,.06);
        }
        .card-surface .muted{ color:var(--muted)!important; }

        /* Full-bleed page */
        .game-viewport{min-height:calc(100vh - 0px);display:flex;flex-direction:column;}
        .game-container{position:relative;flex:1;overflow-x:hidden;}
        .full-bleed-content{ width:100vw; max-width:100vw; margin:0; padding:0; }
        .full-bleed-content .row{ --bs-gutter-x: 0; --bs-gutter-y: 0; } /* default no gutters */

        /* Restore gutters ONLY inside the levels grid */
        .levels-inner .row{ --bs-gutter-x: 1.5rem; --bs-gutter-y: 2rem; }

        .floating-elements{position:absolute;inset:0;pointer-events:none;z-index:1;}
        .floating-coin,.floating-gem,.floating-star{position:absolute;font-size:20px;opacity:.5;animation:float 8s ease-in-out infinite}
        .floating-gem{animation-delay:1.8s}.floating-star{animation-delay:3.2s}
        @keyframes float{0%,100%{transform:translateY(0) rotate(0)}25%{transform:translateY(-18px) rotate(90deg)}50%{transform:translateY(0) rotate(180deg)}75%{transform:translateY(-12px) rotate(270deg)}}

        .section-gap{margin:28px 0;}

        .btn-game, .btn-level, .btn-instructions, .btn-final-boss{
          background:linear-gradient(45deg,var(--primary),var(--accent));
          color:#fff; border:none; font-weight:800; letter-spacing:.4px;
          border-radius:12px; padding:12px 22px; text-transform:uppercase;
          box-shadow:0 12px 26px rgba(185,103,255,.25); position:relative; overflow:hidden;
          transition:.22s ease;
        }
        .btn-game:hover, .btn-level:hover:not(:disabled), .btn-instructions:hover:not(:disabled), .btn-final-boss:hover:not(:disabled){
          transform:translateY(-2px); color:#fff; box-shadow:0 14px 32px rgba(185,103,255,.38);
        }
        .btn-level:disabled, .btn-final-boss:disabled{
          background:linear-gradient(135deg,#bfb8d6,#b3aacd);
          color:#6b6880;
        }

        .cosmic-separator{
          position:relative; height:28px; margin:6px 0 22px; width:100%;
        }
        .cosmic-separator::after{
          content:''; position:absolute; left:0; right:0; top:50%;
          height:1px;
          background:linear-gradient(90deg, transparent, rgba(185,103,255,.45), transparent);
        }
        .cosmic-separator .label{
          position:absolute; left:50%; top:50%; transform:translate(-50%,-50%);
          background:linear-gradient(45deg,var(--primary),var(--accent));
          color:#fff; font-weight:900; letter-spacing:.4px; text-transform:uppercase;
          font-size:.8rem; padding:6px 12px; border-radius:999px;
          box-shadow:0 10px 24px rgba(185,103,255,.25);
        }

        .assessment-boss-battle,
        .levels-arena,
        .post-assessment-final-boss{ padding:26px; }

        .assessment-boss-battle.card-surface::after,
        .post-assessment-final-boss.card-surface::after{
          content:''; position:absolute; inset:0; left:-100%;
          background:linear-gradient(90deg,transparent,rgba(185,103,255,.10),transparent);
          animation:headerShine 6s linear infinite;
          border-radius:inherit; pointer-events:none;
        }

        .boss-icon, .final-boss-icon{
          width:84px;height:84px;border-radius:16px;margin:0; color:#fff;
          display:flex;align-items:center;justify-content:center;font-size:40px;
          background:radial-gradient(var(--accent),var(--primary));
          box-shadow:0 0 38px rgba(185,103,255,.35); animation:breath 1.8s ease-in-out infinite;
        }
        @keyframes breath{0%,100%{transform:scale(1)}50%{transform:scale(1.08)}}

        .assessment-title, .arena-title, .final-boss-title{
          font-weight:900; margin:0 0 6px 0;
          background:linear-gradient(45deg,var(--primary),var(--accent));
          -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .arena-title{text-align:center;font-size:1.6rem}
        .arena-sub{ color:var(--muted); text-align:center; margin-bottom:18px; }

        .levels-inner{ padding:0 16px 16px; }

        .level-card{
          background:var(--card); color:var(--ink);
          border:1px solid var(--card-brd); border-radius:18px; padding:22px;
          text-align:center; height:100%; position:relative; overflow:hidden;
          transition:transform .22s ease, box-shadow .22s ease;
          box-shadow:0 10px 28px rgba(25,10,41,.16);
          /* removed margin so spacing comes from gutters */
        }
        .level-card.unlocked{ cursor:pointer; }
        .level-card.unlocked:hover{ transform:translateY(-6px); box-shadow:0 16px 44px rgba(25,10,41,.22); }

        .level-number{
          position:absolute; top:-12px; left:-12px; width:52px; height:52px; border-radius:12px;
          display:flex; align-items:center; justify-content:center; font-weight:900; font-size:1.05rem; color:#fff;
          background:linear-gradient(135deg,var(--primary),var(--accent));
          box-shadow:0 16px 28px rgba(185,103,255,.26), inset 0 -2px 0 rgba(0,0,0,.15); z-index:3;
        }
        .level-type-badge{
          position:absolute; top:14px; right:14px; padding:6px 12px; border-radius:999px;
          background:linear-gradient(135deg,var(--primary),var(--accent)); color:#fff; font-weight:800; font-size:.75rem; letter-spacing:.4px;
          border:1px solid rgba(185,103,255,.35);
        }
        .level-title{ font-size:1.1rem; font-weight:800; margin:18px 0 8px; }
        .stars-display{ margin:10px 0 8px; font-size:1.6rem; height:38px; display:flex; align-items:center; justify-content:center; gap:4px; }
        .star{ display:inline-block; margin:0 2px; transition:transform .18s; }
        .star.earned{ color:#ffd700; text-shadow:0 0 12px #ffd54a; }
        .star.empty{ color:#C6B9F0; }
        .star:hover{ transform:scale(1.15) rotate(10deg); }

        .meta{ font-size:.9rem; }
        .meta .score{ color:var(--primary); font-weight:700; }
        .meta .passed{ color:var(--success); font-weight:800; }

        .level-actions{ display:flex; gap:12px; margin-top:16px; justify-content:center; }
        .level-actions .btn{ flex:1; min-width:120px; }

        .locked-overlay{
          position:absolute; inset:0; background:rgba(77,56,106,.25);
          backdrop-filter: blur(2px); border-radius:18px; display:flex; align-items:center; justify-content:center;
          font-size:2.2rem; color:#8b7ea5; z-index:5;
        }

        .progress-bar-container{
          background:#E6DFFF; height:8px; border-radius:10px; overflow:hidden; margin:10px 0;
          outline:1px solid rgba(122,46,165,.18);
        }
        .progress-bar-fill{
          height:100%; background:linear-gradient(90deg,var(--primary),var(--accent));
          border-radius:10px; transition:width 1s ease; position:relative;
        }
        .progress-bar-fill::after{
          content:''; position:absolute; inset:0; left:-100%;
          background:linear-gradient(90deg,transparent,rgba(255,255,255,.35),transparent);
          animation:shimmer 2.2s ease-in-out infinite;
        }
        @keyframes shimmer{0%{left:-100%}100%{left:100%}}

        .success-notification{
          background:var(--card); color:var(--ink);
          border:1px solid var(--card-brd); border-radius:0; padding:14px 18px;
          box-shadow:0 12px 26px rgba(25,10,41,.16);
          margin:0;
        }

        .completed-stamp{
          position:absolute; top:16px; right:16px;
          background:linear-gradient(45deg,var(--primary),var(--accent)); color:#fff;
          border-radius:999px; padding:6px 12px; font-weight:800; box-shadow:0 10px 20px rgba(185,103,255,.28);
        }

        @media (max-width:768px){
          .stage-title{font-size:1.5rem}
          .arena-title{font-size:1.4rem}
          .boss-icon,.final-boss-icon{width:72px;height:72px;font-size:34px}
          .level-card{padding:18px}
          .level-actions{flex-direction:column;gap:8px}
          .level-actions .btn{width:100%}
        }
    </style>

    <div class="game-viewport">
        <div class="game-container">

            <!-- Floating Game Elements -->
            <div class="floating-elements">
                <div class="floating-coin" style="top:10%; left:5%;">🪙</div>
                <div class="floating-gem"  style="top:20%; left:90%;">💎</div>
                <div class="floating-star" style="top:60%; left:3%;">⭐</div>
                <div class="floating-coin" style="top:80%; left:95%;">🪙</div>
                <div class="floating-gem"  style="top:40%; left:2%;">💎</div>
                <div class="floating-star" style="top:15%; left:85%;">⭐</div>
            </div>

            <div class="full-bleed-content">

                @if(session('status'))
                    <div class="success-notification section-gap rounded-0 shadow-sm">
                        <div class="d-flex align-items-center" style="padding:4px 6px;">
                            <i class="fas fa-trophy me-3 fs-4" style="color:var(--primary)"></i>
                            <div>
                                <strong>Achievement Unlocked!</strong><br>
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="cosmic-separator">
                    <div class="label">Pre-Assessment</div>
                </div>

                <div class="assessment-boss-battle card-surface section-gap">
                    <div class="d-flex align-items-center px-3">
                        <div class="boss-icon me-4">🧠</div>
                        <div class="flex-fill">
                            <h3 class="assessment-title mb-2">Pre-Assessment Boss Battle</h3>
                            <div class="assessment-status">
                                @if($progress->pre_completed_at)
                                    <div class="d-flex align-items-center">
                                        <span class="me-3" style="color:var(--success);font-weight:800;">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Victory Achieved!
                                        </span>
                                        <small class="muted">
                                            Conquered on {{ $progress->pre_completed_at->format('M j, Y \a\t H:i') }}
                                        </small>
                                    </div>
                                    <div class="progress-bar-container mt-2">
                                        <div class="progress-bar-fill" style="width:100%;"></div>
                                    </div>
                                @else
                                    <div class="muted">
                                        <i class="fas fa-sword me-1" style="color:var(--primary)"></i>
                                        Defeat this boss to unlock the arena levels!
                                    </div>
                                    <div class="progress-bar-container mt-2">
                                        <div class="progress-bar-fill" style="width:0%;"></div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="ms-2">
                            @if(!$progress->pre_completed_at && $pre)
                                <a class="btn btn-level" href="{{ route('assessments.show', $pre) }}">
                                    <i class="fas fa-fist-raised me-2"></i> Fight Boss
                                </a>
                            @else
                                <button class="btn btn-level" disabled>
                                    <i class="fas fa-crown me-2"></i> Defeated
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($progress->pre_completed_at)
                        <div class="completed-stamp">
                            <i class="fas fa-trophy me-1"></i> Completed
                        </div>
                    @endif
                </div>

                <div class="cosmic-separator">
                    <div class="label">Levels Arena</div>
                </div>

                <div class="levels-arena card-surface section-gap">
                    <h2 class="arena-title">Battle Arena Levels</h2>
                    <div class="arena-sub">Earn stars, raise your rank, and unlock the final boss.</div>

                    <div class="levels-inner">
                        <!-- NOTE: gutters restored here with gy/gx; rows now have space -->
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
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

                                <div class="col d-flex">
                                    <div class="level-card {{ $unlocked ? 'unlocked' : 'locked' }} position-relative w-100">
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
                                                <div class="level-actions">
                                                    <a class="btn btn-level" href="{{ route('levels.show', $level) }}">
                                                        <i class="fas fa-{{ $passed ? 'redo' : 'play' }} me-2"></i>
                                                        {{ $passed ? 'Retake' : 'Battle' }}
                                                    </a>
                                                    <a class="btn btn-instructions" href="{{ route('levels.instructions', $level) }}">
                                                        <i class="fas fa-book me-2"></i> Instructions
                                                    </a>
                                                </div>
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
                </div>

                <div class="cosmic-separator">
                    <div class="label">Final Boss</div>
                </div>

                <div class="post-assessment-final-boss card-surface section-gap position-relative">
                    <div class="d-flex flex-column align-items-center">
                        <div class="final-boss-icon mb-3">🏁</div>
                        <h3 class="final-boss-title mb-2">FINAL BOSS BATTLE</h3>

                        <div class="text-center mb-4" style="color:var(--ink)">
                            @if($progress->post_completed_at)
                                <div class="fs-6 fw-bold mb-1" style="color:var(--success)">
                                    <i class="fas fa-crown me-2"></i> LEGENDARY VICTORY ACHIEVED!
                                </div>
                                <div class="muted">
                                    Conquered on {{ $progress->post_completed_at->format('M j, Y \a\t H:i') }}
                                </div>
                                <div class="progress-bar-container mt-3">
                                    <div class="progress-bar-fill" style="width:100%;"></div>
                                </div>
                            @else
                                <div class="fs-6 fw-bold mb-1" style="color:var(--primary)">
                                    <i class="fas fa-dragon me-2"></i> The Ultimate Challenge Awaits
                                </div>
                                <div class="muted">Complete all arena levels to face the final boss</div>
                                <div class="progress-bar-container mt-3">
                                    <div class="progress-bar-fill" style="width:0%;"></div>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            @if($post)
                                <a class="btn btn-final-boss" href="{{ route('assessments.show', $post) }}">
                                    <i class="fas fa-sword me-2"></i> Face Final Boss
                                </a>
                            @endif
                        </div>

                        @if($progress->post_completed_at)
                            <div class="completed-stamp">
                                <i class="fas fa-crown me-1"></i> LEGENDARY
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
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
              background:rgba(255,255,255,.3);border-radius:50%;transform:scale(0);
              animation:gameRipple .6s linear;pointer-events:none;z-index:1;`;
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
          });
        });

        document.querySelectorAll('.level-card.unlocked').forEach(card => {
          card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
              const btn = this.querySelector('.btn-level:not(:disabled)');
              if (btn) btn.click();
            }
          });
        });
      });

      const dynamicStyles = document.createElement('style');
      dynamicStyles.textContent = `@keyframes gameRipple{ to{ transform:scale(4); opacity:0 } }`;
      document.head.appendChild(dynamicStyles);
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>
