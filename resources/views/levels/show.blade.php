<x-app-layout>
    @php
        // These are only for the Level page
        $alreadyPassed = ($levelProgress ?? null) && ($levelProgress->passed ?? false) && !request()->boolean('replay');
        $savedScore    = $levelProgress->best_score ?? null;
        $savedStars    = $levelProgress->stars ?? 0;

        // Safe content defaults from DB (seeders)
        $c = $level->content ?? [];
        $intro        = $c['intro']        ?? null;
        $instructions = $c['instructions'] ?? null;
        $timeLimit    = (int)($c['time_limit'] ?? 300);     // seconds
        $maxHints     = (int)($c['max_hints']  ?? 3);
        $hints        = $c['hints']        ?? [];
        $categories   = $c['categories']   ?? [];           // map: label => [items]
    @endphp

    <x-slot name="header">
        <div class="epic-level-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="level-badge-container">
                            <div class="level-badge">
                                <span class="level-number">{{ $level->index }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="level-info">
                            <h2 class="level-title mb-1">{{ $level->stage->title }}</h2>
                            <div class="level-subtitle">
                                <i class="fas fa-gamepad me-2"></i>
                                Level {{ $level->index }} • {{ $level->type }} Challenge
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="level-stats">
                            <div class="stat-item me-3">
                                <div class="stat-icon">🏆</div>
                                <div class="stat-value" id="currentScore">0</div>
                                <div class="stat-label">Score</div>
                            </div>
                            <div class="stat-item me-3">
                                <div class="stat-icon">⭐</div>
                                <div class="stat-value" id="starsEarned">0</div>
                                <div class="stat-label">Stars</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">⏱️</div>
                                <div class="stat-value" id="timeRemaining">
                                    {{ str_pad(intval($timeLimit/60),2,'0',STR_PAD_LEFT) }}:{{ str_pad($timeLimit%60,2,'0',STR_PAD_LEFT) }}
                                </div>
                                <div class="stat-label">Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Epic Styles (unchanged look & feel) -->
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
            min-height: 100vh; font-family: 'Orbitron', 'Arial', sans-serif; color: white;
        }
        .epic-level-header{background:rgba(10,6,30,.9);backdrop-filter:blur(20px);border-bottom:3px solid var(--neon-purple);padding:20px 0;position:relative;overflow:hidden}
        .epic-level-header::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(185,103,255,.1),transparent);animation:headerShine 4s ease-in-out infinite}
        @keyframes headerShine{0%{left:-100%}50%,100%{left:100%}}
        .level-badge{width:70px;height:70px;background:var(--gold-gradient);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(185,103,255,.6);animation:levelPulse 2s ease-in-out infinite;position:relative;z-index:2}
        .level-number{font-size:1.8rem;font-weight:900;color:#333;text-shadow:1px 1px 2px rgba(0,0,0,.3)}
        @keyframes levelPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05);box-shadow:0 0 40px rgba(185,103,255,.8)}}
        .level-title{color:var(--neon-purple);font-size:1.8rem;font-weight:900;text-shadow:2px 2px 4px rgba(0,0,0,.5);letter-spacing:1px}
        .level-subtitle{color:rgba(255,255,255,.8);font-size:1rem}
        .level-stats{display:flex;align-items:center}
        .stat-item{text-align:center;color:white;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:15px;border-radius:15px;border:1px solid rgba(255,255,255,.2);min-width:80px}
        .stat-icon{font-size:1.5rem;margin-bottom:5px}
        .stat-value{font-size:1.2rem;font-weight:900;color:var(--neon-purple)}
        .stat-label{font-size:.8rem;opacity:.8}
        .game-arena{background:rgba(26,6,54,.7);backdrop-filter:blur(20px);border-radius:30px;margin:30px auto;padding:40px;max-width:1200px;box-shadow:0 20px 60px rgba(0,0,0,.3);border:2px solid rgba(185,103,255,.3);position:relative;overflow:hidden}
        .game-arena::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(185,103,255,.05) 0%,transparent 70%);animation:arenaGlow 6s ease-in-out infinite}
        @keyframes arenaGlow{0%,100%{transform:rotate(0)}50%{transform:rotate(180deg)}}
        .challenge-title{text-align:center;font-size:2.5rem;font-weight:900;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:30px;position:relative;z-index:2}
        .challenge-description{text-align:center;font-size:1.2rem;color:rgba(255,255,255,.8);margin-bottom:24px;position:relative;z-index:2}
        .drag-drop-container{display:grid;grid-template-columns:1fr 1fr;gap:50px;margin:40px 0;position:relative;z-index:2}
        .draggables-section,.drop-zones-section{border-radius:20px;padding:30px;border:3px dashed var(--neon-purple);position:relative;overflow:hidden}
        .draggables-section{background:linear-gradient(135deg,rgba(40,10,70,.7) 0%,rgba(60,20,90,.7) 100%)}
        .draggables-section::before{content:'🎯 DRAG FROM HERE';position:absolute;top:15px;right:20px;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));color:white;padding:8px 16px;border-radius:20px;font-size:.8rem;font-weight:700;letter-spacing:1px}
        .drop-zones-section{background:linear-gradient(135deg,rgba(70,30,100,.7) 0%,rgba(90,40,120,.7) 100%)}
        .drop-zones-section::before{content:'🎯 DROP HERE';position:absolute;top:15px;right:20px;background:linear-gradient(45deg,var(--bright-pink),var(--neon-purple));color:white;padding:8px 16px;border-radius:20px;font-size:.8rem;font-weight:700;letter-spacing:1px}
        .section-title{text-align:center;font-size:1.5rem;font-weight:800;color:white;margin-bottom:25px}
        .draggable-item{background:linear-gradient(135deg,rgba(30,10,60,.8) 0%,rgba(50,20,80,.8) 100%);border:3px solid var(--neon-blue);border-radius:15px;padding:20px;margin:15px 0;color:white;user-select:none;cursor:grab;transition:all .3s cubic-bezier(.175,.885,.32,1.275);box-shadow:0 8px 25px rgba(0,179,255,.15);position:relative;overflow:hidden}
        .draggable-item::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(0,179,255,.1),transparent);transition:left .5s ease}
        .draggable-item:hover::before{left:100%}
        .draggable-item:hover{transform:translateY(-8px) scale(1.02);box-shadow:0 15px 40px rgba(0,179,255,.25);border-color:var(--electric-blue)}
        .draggable-item:active{cursor:grabbing;transform:scale(1.05);box-shadow:0 20px 50px rgba(0,179,255,.4);z-index:1000}
        .draggable-item.dragging{transform:rotate(5deg) scale(1.1);opacity:.8;z-index:1000;box-shadow:0 25px 60px rgba(0,179,255,.5)}
        .item-icon{font-size:2rem;margin-right:15px;vertical-align:middle}
        .item-text{font-size:1.1rem;font-weight:600;vertical-align:middle}
        .drop-zone{background:linear-gradient(135deg,rgba(30,10,60,.8) 0%,rgba(50,20,80,.8) 100%);border:3px dashed var(--neon-purple);border-radius:15px;padding:25px;margin:15px 0;min-height:100px;display:flex;align-items:center;justify-content:center;transition:all .3s ease;position:relative;overflow:hidden}
        .drop-zone-placeholder{color:rgba(255,255,255,.7);font-size:1.1rem;font-weight:600;text-align:center;opacity:.7}
        .drop-zone.drag-over{background:linear-gradient(135deg,rgba(40,167,69,.3) 0%,rgba(56,239,125,.3) 100%);border-color:#28a745;border-style:solid;transform:scale(1.05);box-shadow:0 0 30px rgba(40,167,69,.4)}
        .drop-zone.drag-over::before{content:'✨ DROP IT! ✨';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(255,255,255,.9);color:#28a745;padding:10px 20px;border-radius:25px;font-weight:800;font-size:1rem;letter-spacing:1px;animation:dropPulse .5s ease-in-out infinite alternate}
        @keyframes dropPulse{0%{transform:translate(-50%,-50%) scale(1)}100%{transform:translate(-50%,-50%) scale(1.1)}}
        .drop-zone.correct{background:linear-gradient(135deg,rgba(40,167,69,.3) 0%,rgba(56,239,125,.3) 100%);border-color:#28a745;border-style:solid;animation:correctDrop 1s ease}
        @keyframes correctDrop{0%{transform:scale(1)}50%{transform:scale(1.1);box-shadow:0 0 50px rgba(40,167,69,.8)}100%{transform:scale(1)}}
        .drop-zone.incorrect{background:linear-gradient(135deg,rgba(220,53,69,.3) 0%,rgba(255,106,0,.3) 100%);border-color:#dc3545;border-style:solid;animation:incorrectDrop .5s ease}
        @keyframes incorrectDrop{0%,100%{transform:translateX(0)}25%{transform:translateX(-10px)}75%{transform:translateX(10px)}}
        .dropped-item{background:linear-gradient(135deg,rgba(40,167,69,.8) 0%,rgba(56,239,125,.8) 100%);border:3px solid #28a745;border-radius:15px;padding:20px;color:white;font-weight:600;box-shadow:0 8px 25px rgba(40,167,69,.3);animation:itemDrop .5s ease;position:relative;overflow:hidden}
        @keyframes itemDrop{0%{transform:scale(0) rotate(180deg);opacity:0}100%{transform:scale(1) rotate(0);opacity:1}}
        .controls-section{text-align:center;margin:40px 0;position:relative;z-index:2}
        .btn-epic{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));border:none;color:white;padding:18px 40px;border-radius:30px;font-size:1.2rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;transition:all .3s ease;box-shadow:0 8px 25px rgba(102,126,234,.3);position:relative;overflow:hidden;margin:0 10px}
        .btn-epic:hover{transform:translateY(-5px) scale(1.05);box-shadow:0 15px 40px rgba(185,103,255,.5);color:white}
        .btn-epic::before{content:'';position:absolute;top:50%;left:50%;width:0;height:0;background:rgba(255,255,255,.2);border-radius:50%;transition:all .6s ease;transform:translate(-50%,-50%)}
        .btn-epic:hover::before{width:300px;height:300px}
        .btn-reset{background:linear-gradient(45deg,var(--bright-pink),#ff6a00);box-shadow:0 8px 25px rgba(238,9,121,.3)}
        .btn-reset:hover{box-shadow:0 15px 40px rgba(238,9,121,.5)}
        .btn-hint{background:linear-gradient(45deg,#f093fb,#f5576c);box-shadow:0 8px 25px rgba(240,147,251,.3)}
        .btn-hint:hover{box-shadow:0 15px 40px rgba(240,147,251,.5)}
        .feedback-container{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;pointer-events:none}
        .feedback-message{background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));color:white;padding:20px 40px;border-radius:25px;font-size:1.5rem;font-weight:800;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.3);animation:feedbackPop 2s ease;position:relative;overflow:hidden}
        .feedback-message.error{background:linear-gradient(45deg,var(--bright-pink),#ff6a00)}
        @keyframes feedbackPop{0%{transform:scale(0) rotate(180deg);opacity:0}20%{transform:scale(1.2) rotate(0);opacity:1}80%{transform:scale(1)}100%{transform:scale(0);opacity:0}}
        .progress-bar-container{background:rgba(0,0,0,.1);height:12px;border-radius:10px;overflow:hidden;margin:20px 0;position:relative;z-index:2}
        .progress-bar{height:100%;background:linear-gradient(45deg,var(--neon-blue),var(--neon-purple));border-radius:10px;transition:width .8s cubic-bezier(.25,.46,.45,.94);position:relative;overflow:hidden}
        .progress-bar::after{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);animation:progressShine 2s ease-in-out infinite}
        @keyframes progressShine{0%{left:-100%}100%{left:100%}}
        .completion-celebration{position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9999}
        .confetti{position:absolute;width:10px;height:10px;background:var(--neon-purple);animation:confettiFall 3s linear infinite}
        @keyframes confettiFall{0%{transform:translateY(-100vh) rotate(0);opacity:1}100%{transform:translateY(100vh) rotate(720deg);opacity:0}}
        @media (max-width: 768px){.drag-drop-container{grid-template-columns:1fr;gap:30px}.level-stats{flex-direction:column;gap:10px}.stat-item{padding:10px;min-width:60px}.challenge-title{font-size:2rem}}
    </style>

    <div class="game-arena">
        <!-- Challenge Header -->
        <h1 class="challenge-title">{{ $level->title }}</h1>

        @if($intro)
            <p class="challenge-description desktop-only">{!! nl2br(e($intro)) !!}</p>
        @endif

        @if($instructions)
            <p class="challenge-description">{!! nl2br(e($instructions)) !!}</p>
        @endif

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
        </div>

        <!-- Main Game Area -->
        <div class="drag-drop-container">
            <!-- Draggables Section -->
            <div class="draggables-section">
                <h3 class="section-title">🐍 Items</h3>
                <div id="draggablesContainer"></div>
            </div>

            <!-- Drop Zones Section -->
            <div class="drop-zones-section">
                <h3 class="section-title">🎯 Match Here</h3>
                <div id="dropZonesContainer"></div>
            </div>
        </div>

        <!-- Game Controls -->
        <div class="controls-section">
            <button class="btn-epic" onclick="checkAnswers()">
                <i class="fas fa-magic me-2"></i>
                Check Answers
            </button>
            <button class="btn-epic btn-hint" onclick="showHint()">
                <i class="fas fa-lightbulb me-2"></i>
                Hint
            </button>
            <button class="btn-epic btn-reset" onclick="resetGame()">
                <i class="fas fa-redo me-2"></i>
                Reset
            </button>
        </div>

        <!-- Score Submission -->
        <form method="POST" action="{{ route('levels.submit',$level) }}" id="scoreForm" style="display:none;">
            @csrf
            <input type="hidden" name="score" id="finalScore" value="0">
        </form>
    </div>

    <!-- Feedback + Celebration -->
    <div class="feedback-container" id="feedbackContainer"></div>
    <div class="completion-celebration" id="celebrationContainer"></div>

    <!-- JS Game Engine (now fully data-driven from DB) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Safely inject level content from DB
        const LEVEL_CONTENT = @json($level->content, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)
                            || {};

        class EpicDragDropGame {
            constructor() {
                // Pull from DB content (with fallbacks)
                this.gameData = {
                    categories: LEVEL_CONTENT.categories || {},
                    hints: LEVEL_CONTENT.hints || [],
                };

                this.timeLimit = Number(LEVEL_CONTENT.time_limit ?? {{ $timeLimit }});
                this.maxHints  = Number(LEVEL_CONTENT.max_hints ?? {{ $maxHints }});

                this.score = 0;
                this.timeRemaining = this.timeLimit;
                this.hintsUsed = 0;
                this.gameStarted = false;
                this.gameCompleted = false;
                this.correctAnswers = 0;
                this.totalQuestions = 0;
                this.timer = null;

                this.init();
            }

            init() {
                this.generateGameElements();
                this.setupEventListeners();
                this.startTimer();
                this.updateUI();
                this.showFeedback("🎮 Quest Begins! 🎮", "success", 1500);
            }

            generateGameElements() {
                const draggablesContainer = document.getElementById('draggablesContainer');
                const dropZonesContainer  = document.getElementById('dropZonesContainer');
                draggablesContainer.innerHTML = '';
                dropZonesContainer.innerHTML  = '';

                // Collect all items from categories
                const allItems = [];
                Object.values(this.gameData.categories).forEach(items => { if (Array.isArray(items)) allItems.push(...items); });

                // Shuffle items
                const shuffled = this.shuffleArray(allItems);
                this.totalQuestions = shuffled.length;

                // Render draggables
                shuffled.forEach((item, i) => {
                    const el = this.createDraggableElement(item, i);
                    draggablesContainer.appendChild(el);
                });

                // Render drop zones
                Object.keys(this.gameData.categories).forEach((cat, i) => {
                    const el = this.createDropZoneElement(cat, i);
                    dropZonesContainer.appendChild(el);
                });
            }

            createDraggableElement(item, index) {
                const div = document.createElement('div');
                div.className = 'draggable-item';
                div.draggable = true;
                div.dataset.item = item;
                div.dataset.index = index;

                // try to split "emoji label" if present
                const parts = String(item).trim().split(' ');
                const icon  = parts[0] || '🔹';
                const text  = parts.length > 1 ? parts.slice(1).join(' ') : parts[0];

                div.innerHTML = `
                    <span class="item-icon">${icon}</span>
                    <span class="item-text">${text}</span>
                `;
                return div;
            }

            createDropZoneElement(category, index) {
                const div = document.createElement('div');
                div.className = 'drop-zone';
                div.dataset.category = category;
                div.dataset.index = index;
                div.innerHTML = `
                    <div class="drop-zone-placeholder">
                        <strong>${category}</strong><br>
                        <small>Drop items here</small>
                    </div>
                `;
                return div;
            }

            setupEventListeners() {
                document.addEventListener('dragstart', this.handleDragStart.bind(this));
                document.addEventListener('dragover',  e => { e.preventDefault(); return false; });
                document.addEventListener('dragenter', this.handleDragEnter.bind(this));
                document.addEventListener('dragleave', this.handleDragLeave.bind(this));
                document.addEventListener('drop',      this.handleDrop.bind(this));
                document.addEventListener('dragend',   this.handleDragEnd.bind(this));

                // keyboard helpers
                document.addEventListener('keydown', (e) => {
                    if (e.key.toLowerCase() === 'h') this.showHint();
                    if (e.key.toLowerCase() === 'r') this.resetGame();
                    if (e.key === 'Enter') this.checkAnswers();
                });

                // basic touch support
                this.setupTouchEvents();
            }

            handleDragStart(e) {
                if (!e.target.classList.contains('draggable-item')) return;
                e.target.classList.add('dragging');
                e.dataTransfer.setData('text/plain', e.target.dataset.item);
                e.dataTransfer.setData('application/json', JSON.stringify({
                    item: e.target.dataset.item,
                    index: e.target.dataset.index
                }));
                if (!this.gameStarted) { this.gameStarted = true; this.showFeedback("🚀 Go!", "success", 800); }
            }
            handleDragEnter(e) {
                const dz = e.target.classList.contains('drop-zone') ? e.target : e.target.closest('.drop-zone');
                if (!dz) return;
                dz.classList.add('drag-over');
            }
            handleDragLeave(e) {
                const dz = e.target.classList.contains('drop-zone') ? e.target : e.target.closest('.drop-zone');
                if (!dz) return;
                dz.classList.remove('drag-over');
            }
            handleDragEnd(e) { e.target.classList.remove('dragging'); }

            handleDrop(e) {
                e.preventDefault();
                const dz = e.target.classList.contains('drop-zone') ? e.target : e.target.closest('.drop-zone');
                if (!dz) return;

                const draggedItem = e.dataTransfer.getData('text/plain');
                dz.classList.remove('drag-over');

                // remove placeholder
                const ph = dz.querySelector('.drop-zone-placeholder'); if (ph) ph.remove();

                // dropped block
                const dropped = document.createElement('div');
                dropped.className = 'dropped-item';
                dropped.dataset.item = draggedItem;

                const parts = String(draggedItem).trim().split(' ');
                const icon  = parts[0] || '🔹';
                const text  = parts.length > 1 ? parts.slice(1).join(' ') : parts[0];

                dropped.innerHTML = `
                    <span class="item-icon">${icon}</span>
                    <span class="item-text">${text}</span>
                    <button class="btn btn-sm btn-light ms-2" onclick="game.removeDroppedItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                dz.appendChild(dropped);

                // remove original draggable
                const orig = document.querySelector(`.draggable-item[data-item="${CSS.escape(draggedItem)}"]`);
                if (orig) orig.remove();

                this.updateProgress();

                // auto-check when all placed
                if (!document.querySelector('.draggable-item')) {
                    setTimeout(() => this.checkAnswers(), 600);
                }
            }

            setupTouchEvents() {
                let touchItem = null, touchOffset = {x:0,y:0};

                document.addEventListener('touchstart', (e) => {
                    const t = e.target.closest('.draggable-item');
                    if (!t) return;
                    touchItem = t;
                    const touch = e.touches[0], rect = t.getBoundingClientRect();
                    touchOffset = { x: touch.clientX - rect.left, y: touch.clientY - rect.top };
                    t.classList.add('dragging'); e.preventDefault();
                }, {passive:false});

                document.addEventListener('touchmove', (e) => {
                    if (!touchItem) return;
                    const touch = e.touches[0];
                    Object.assign(touchItem.style, {
                        position:'fixed', left:(touch.clientX - touchOffset.x)+'px',
                        top:(touch.clientY - touchOffset.y)+'px', zIndex:'1000', transform:'rotate(5deg) scale(1.1)'
                    });
                    e.preventDefault();
                }, {passive:false});

                document.addEventListener('touchend', (e) => {
                    if (!touchItem) return;
                    const touch = e.changedTouches[0];
                    const el = document.elementFromPoint(touch.clientX, touch.clientY);
                    const dz = el?.closest('.drop-zone');

                    // reset styles
                    Object.assign(touchItem.style, {position:'',left:'',top:'',zIndex:'',transform:''});
                    touchItem.classList.remove('dragging');

                    if (dz) {
                        const fake = {
                            preventDefault: ()=>{},
                            target: dz,
                            dataTransfer: { getData: (t) => t==='text/plain' ? touchItem.dataset.item : '' }
                        };
                        this.handleDrop(fake);
                    }
                    touchItem = null;
                });
            }

            removeDroppedItem(btn) {
                const item = btn.closest('.dropped-item');
                const zone = item.closest('.drop-zone');
                const val = item.dataset.item;
                item.remove();

                // restore draggable
                const cont = document.getElementById('draggablesContainer');
                cont.appendChild(this.createDraggableElement(val, Date.now()));

                // restore placeholder if empty
                if (!zone.querySelector('.dropped-item')) {
                    const cat = zone.dataset.category;
                    zone.innerHTML = `<div class="drop-zone-placeholder"><strong>${cat}</strong><br><small>Drop items here</small></div>`;
                }

                this.updateProgress();
                this.showFeedback("🔄 Returned!", "info", 900);
            }

            checkAnswers() {
                if (this.gameCompleted) return;

                let correct = 0, total = 0;

                document.querySelectorAll('.drop-zone').forEach(zone => {
                    const cat = zone.dataset.category;
                    const correctItems = this.gameData.categories[cat] || [];
                    zone.querySelectorAll('.dropped-item').forEach(di => {
                        total++;
                        const ok = correctItems.includes(di.dataset.item);
                        if (ok) {
                            correct++; zone.classList.add('correct');
                            di.style.background = 'linear-gradient(135deg, rgba(40, 167, 69, 0.8) 0%, rgba(56, 239, 125, 0.8) 100%)';
                        } else {
                            zone.classList.add('incorrect');
                            di.style.background = 'linear-gradient(135deg, rgba(220, 53, 69, 0.8) 0%, rgba(255, 106, 0, 0.8) 100%)';
                        }
                    });
                });

                const pct = total ? Math.round((correct/total)*100) : 0;
                this.score = Math.max(0, pct - (this.hintsUsed * 5));
                const timeBonus = Math.max(0, Math.floor(this.timeRemaining / 10));
                this.score = Math.min(100, this.score + timeBonus);
                this.correctAnswers = correct;

                this.updateUI();

                if (pct >= 80) {
                    this.showFeedback("🏆 LEGENDARY VICTORY! 🏆", "success", 2400);
                    this.startCelebration();
                    this.completeGame(true);
                } else {
                    this.showFeedback(`🎯 Score: ${this.score}% — ${correct}/${total} correct`, pct >= 60 ? "warning" : "error", 2400);
                    this.completeGame(false);
                }

                this.gameCompleted = true;
                clearInterval(this.timer);
                setTimeout(() => {
                    document.getElementById('finalScore').value = this.score;
                    document.getElementById('scoreForm').submit();
                }, 2400);
            }

            completeGame(success=true){
                this.gameCompleted = true;
                clearInterval(this.timer);
            }

            startCelebration(){
                const c = document.getElementById('celebrationContainer');
                for(let i=0;i<100;i++){
                    setTimeout(()=>{
                        const conf = document.createElement('div');
                        conf.className = 'confetti';
                        conf.style.left = Math.random()*100+'vw';
                        conf.style.background = ['#ffd700','#ff6b6b','#4ecdc4','#45b7d1','#f9ca24'][Math.floor(Math.random()*5)];
                        conf.style.animationDelay = Math.random()*3+'s';
                        c.appendChild(conf);
                        setTimeout(()=>conf.remove(),3000);
                    }, i*25);
                }
            }

            showHint(){
                if (this.hintsUsed >= this.maxHints) { this.showFeedback('🔮 No more hints!', 'error', 1200); return; }
                this.hintsUsed++;
                const hs = Array.isArray(this.gameData.hints) ? this.gameData.hints : [];
                const msg = hs.length ? hs[Math.floor(Math.random()*hs.length)] : 'Try grouping like concepts together.';
                this.showFeedback('💡 Hint: ' + msg, 'info', 2200);
            }

            resetGame(){ if (confirm('Reset this level?')) location.reload(); }

            updateProgress(){
                const total = this.totalQuestions;
                const placed = document.querySelectorAll('.dropped-item').length;
                const pct = total ? Math.round((placed/total)*100) : 0;
                document.getElementById('progressBar').style.width = pct+'%';
            }

            updateUI(){
                document.getElementById('currentScore').textContent = this.score;
                document.getElementById('starsEarned').textContent = this.getStars();
                const m = Math.floor(this.timeRemaining/60);
                const s = this.timeRemaining%60;
                document.getElementById('timeRemaining').textContent =
                    String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
            }

            getStars(){
                if (this.score >= 90) return '⭐⭐⭐';
                if (this.score >= 70) return '⭐⭐';
                if (this.score >= 50) return '⭐';
                return '0';
            }

            startTimer(){
                this.timer = setInterval(()=>{
                    this.timeRemaining--;
                    this.updateUI();
                    if (this.timeRemaining <= 0){
                        clearInterval(this.timer);
                        this.showFeedback("⏰ Time's Up! Submitting…", "error", 1600);
                        setTimeout(()=>this.checkAnswers(), 900);
                    }
                },1000);
            }

            shuffleArray(a){
                const x = [...a];
                for (let i=x.length-1;i>0;i--){
                    const j = Math.floor(Math.random()*(i+1));
                    [x[i],x[j]] = [x[j],x[i]];
                }
                return x;
            }

            showFeedback(msg, type="success", dur=2000){
                const box = document.createElement('div');
                box.className = `feedback-message ${type}`;
                box.textContent = msg;
                document.getElementById('feedbackContainer').appendChild(box);
                setTimeout(()=>box.remove(), dur);
            }
        }

        // Helpers for buttons
        function checkAnswers(){ game.checkAnswers(); }
        function showHint(){ game.showHint(); }
        function resetGame(){ game.resetGame(); }

        // Boot
        let game;
        document.addEventListener('DOMContentLoaded', ()=> { game = new EpicDragDropGame(); });

        // Dynamic keyframes used by effects
        const dyn = document.createElement('style');
        dyn.textContent = `
            @keyframes sparkle{0%{opacity:1;transform:scale(0) rotate(0)}50%{opacity:1;transform:scale(1) rotate(180deg)}100%{opacity:0;transform:scale(0) rotate(360deg)}}
            @keyframes explode{0%{opacity:1;transform:translate(-50%,-50%) scale(0) rotate(0)}100%{opacity:0;transform:translate(calc(-50% + var(--x,0px)),calc(-50% + var(--y,0px))) scale(1.4) rotate(360deg)}}
        `;
        document.head.appendChild(dyn);
    </script>

    <!-- Icons & Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>
