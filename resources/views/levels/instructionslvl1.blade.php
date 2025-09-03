{{-- resources/views/instrcutionslvl1.blade.php --}}
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
                        <h2 class="stage-title mb-0">Level 1 Instructions</h2>
                        <div class="stage-subtitle">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Stage 1 - Adventure Zone
                        </div>
                    </div>
                </div>
                <a href="{{ route('stages.show', 1) }}" class="btn btn-game">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Stage
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
        .game-viewport{min-height:calc(100vh - 0px);display:flex;flex-direction:column;}
        .game-container{position:relative;flex:1;overflow-x:hidden;}
        .full-bleed-content{ width:100vw; max-width:100vw; margin:0; padding:0; }
        .section-gap{margin:28px 0;}
        .btn-game, .btn-primary, .btn-secondary{
          background:linear-gradient(45deg,var(--primary),var(--accent));
          color:#fff; border:none; font-weight:800; letter-spacing:.4px;
          border-radius:12px; padding:12px 22px; text-transform:uppercase;
          box-shadow:0 12px 26px rgba(185,103,255,.25); position:relative; overflow:hidden;
          transition:.22s ease;
        }
        .btn-game:hover, .btn-primary:hover, .btn-secondary:hover{
          transform:translateY(-2px); color:#fff; box-shadow:0 14px 32px rgba(185,103,255,.38);
        }
        .btn-secondary{
          background:linear-gradient(135deg,#bfb8d6,#b3aacd);
          color:#6b6880;
        }
        .btn-secondary:hover{
          background:linear-gradient(135deg,#b3aacd,#a79fc4);
        }
        .instructions-container{
          padding:30px;
          max-width:800px;
          margin:0 auto;
        }
        .instructions-card{
          background:var(--card);
          border-radius:18px;
          padding:30px;
          box-shadow:0 12px 34px rgba(25,10,41,.18);
          border:1px solid var(--card-brd);
        }
        .instructions-title{
          font-size:1.8rem;
          font-weight:800;
          margin-bottom:20px;
          color:var(--primary);
          text-align:center;
        }
        .instructions-section{
          margin-bottom:25px;
        }
        .instructions-section-title{
          font-size:1.3rem;
          font-weight:700;
          margin-bottom:15px;
          color:var(--primary);
        }
        .instructions-content{
          font-size:1.1rem;
          line-height:1.6;
          color:var(--ink);
        }
        .code-block{
          background:var(--bg-start);
          color:#fff;
          padding:15px;
          border-radius:8px;
          font-family:'Courier New', monospace;
          margin:15px 0;
          overflow-x:auto;
        }
        .tip-box{
          background:rgba(185,103,255,.1);
          border-left:4px solid var(--accent);
          padding:15px;
          margin:15px 0;
          border-radius:0 8px 8px 0;
        }
        .tip-title{
          font-weight:700;
          color:var(--primary);
          margin-bottom:5px;
        }
        .instructions-actions{
          display:flex;
          justify-content:center;
          gap:15px;
          margin-top:30px;
        }
        @media (max-width:768px){
          .instructions-container{padding:20px;}
          .instructions-card{padding:20px;}
          .instructions-title{font-size:1.5rem;}
          .instructions-section-title{font-size:1.1rem;}
          .instructions-content{font-size:1rem;}
          .instructions-actions{flex-direction:column;}
        }
    </style>
    <div class="game-viewport">
        <div class="game-container">
            <div class="full-bleed-content">
                <div class="instructions-container">
                    <div class="instructions-card">
                        <h2 class="instructions-title">Welcome to Your First Challenge!</h2>
                        
                        <div class="instructions-section">
                            <h3 class="instructions-section-title">Objective</h3>
                            <div class="instructions-content">
                                In this level, you'll learn the basics of Python programming. Your goal is to understand how to use the <code>print()</code> function and work with different data types.
                            </div>
                        </div>
                        
                        <div class="instructions-section">
                            <h3 class="instructions-section-title">Key Concepts</h3>
                            <div class="instructions-content">
                                <ul>
                                    <li><strong>print() function:</strong> Used to display output to the screen</li>
                                    <li><strong>Strings:</strong> Text values enclosed in quotes</li>
                                    <li><strong>Numbers:</strong> Integer and floating-point values</li>
                                    <li><strong>Concatenation:</strong> Joining strings together</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="instructions-section">
                            <h3 class="instructions-section-title">Examples</h3>
                            <div class="instructions-content">
                                <p>Here are some examples of what you'll be working with:</p>
                                
                                <div class="code-block">
                                    # Printing a simple message<br>
                                    print("Hello, World!")<br><br>
                                    
                                    # Printing numbers<br>
                                    print(42)<br><br>
                                    
                                    # Joining strings<br>
                                    print("Hello" + " " + "World")
                                </div>
                            </div>
                        </div>
                        
                        <div class="tip-box">
                            <div class="tip-title">Pro Tip</div>
                            <div class="instructions-content">
                                Remember that strings must be enclosed in quotes (single or double), but numbers should not be quoted. Mixing strings and numbers directly will cause an error!
                            </div>
                        </div>
                        
                        <div class="instructions-section">
                            <h3 class="instructions-section-title">What You'll Learn</h3>
                            <div class="instructions-content">
                                After completing this level, you'll be able to:
                                <ul>
                                    <li>Use the print() function to display output</li>
                                    <li>Distinguish between strings and numbers</li>
                                    <li>Combine strings using concatenation</li>
                                    <li>Understand basic Python syntax</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="instructions-actions">
                            <a href="{{ route('levels.show', 1) }}" class="btn btn-primary">
                                <i class="fas fa-play me-2"></i> Start Level
                            </a>
                            <a href="{{ route('stages.show', 1) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Stage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>