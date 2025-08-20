<x-app-layout>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CodeLadder — Climb Your Way to Coding Mastery</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|space-grotesk:400,500,600,700" rel="stylesheet" />

    <!-- App styles if Vite exists -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
      /* ========= DESIGN SYSTEM ========= */
      :root {
        /* Electric Purple & Cyan Theme */
        --primary: #8b5cf6;
        --primary-dark: #7c3aed;
        --primary-light: #a78bfa;
        --secondary: #06b6d4;
        --secondary-dark: #0891b2;
        --accent: #10b981;
        --accent-dark: #059669;
        
        /* Dark Mode Colors */
        --bg-dark: #0f0f23;
        --bg-darker: #0a0a1b;
        --bg-card: #1a1a2e;
        --bg-hover: #252542;
        
        /* Neon Colors */
        --neon-purple: #8b5cf6;
        --neon-cyan: #06b6d4;
        --neon-green: #10b981;
        --neon-yellow: #fbbf24;
        --neon-pink: #ec4899;
        
        /* Text */
        --text-primary: #ffffff;
        --text-secondary: #a1a1aa;
        --text-muted: #71717a;
        
        /* Gradients */
        --gradient-main: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
        --gradient-card: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
        --gradient-glow: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 50%, #10b981 100%);
        
        /* Shadows */
        --shadow-neon: 0 0 20px rgba(139, 92, 246, 0.5);
        --shadow-neon-cyan: 0 0 20px rgba(6, 182, 212, 0.5);
        --shadow-card: 0 10px 40px rgba(0, 0, 0, 0.5);
      }

      /* ========= RESET & BASE ========= */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      html {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        scroll-behavior: smooth;
      }

      body {
        color: var(--text-primary);
        background: var(--bg-darker);
        overflow-x: hidden;
        position: relative;
      }

      /* ========= ANIMATED BACKGROUND ========= */
      .bg-animation {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: 
          radial-gradient(circle at 20% 50%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
          radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.3) 0%, transparent 50%),
          radial-gradient(circle at 40% 30%, rgba(16, 185, 129, 0.2) 0%, transparent 50%);
        animation: bgShift 20s ease-in-out infinite;
      }

      @keyframes bgShift {
        0%, 100% { transform: rotate(0deg) scale(1); }
        33% { transform: rotate(1deg) scale(1.1); }
        66% { transform: rotate(-1deg) scale(1.05); }
      }

      .grid-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
          linear-gradient(rgba(139, 92, 246, 0.1) 1px, transparent 1px),
          linear-gradient(90deg, rgba(139, 92, 246, 0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        z-index: -1;
        opacity: 0.3;
        animation: gridMove 10s linear infinite;
      }

      @keyframes gridMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
      }

      /* ========= HEADER ========= */
      .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: rgba(15, 15, 35, 0.8);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        transition: all 0.3s ease;
      }

      .header.scrolled {
        background: rgba(15, 15, 35, 0.95);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      }

      .nav {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
      }

      .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.3s ease;
      }

      .logo:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.8));
      }

      .logo-icon {
        width: 40px;
        height: 40px;
        background: var(--gradient-main);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: var(--shadow-neon);
        animation: logoPulse 2s ease-in-out infinite;
      }

      @keyframes logoPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 0 20px rgba(139, 92, 246, 0.5); }
        50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(139, 92, 246, 0.8); }
      }

      .nav-buttons {
        display: flex;
        align-items: center;
        gap: 1rem;
      }

      /* ========= HERO SECTION ========= */
      .hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        padding-top: 80px;
      }

      .hero-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        position: relative;
        z-index: 2;
      }

      .hero-text {
        position: relative;
      }

      .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--gradient-card);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.3);
        animation: glow 2s ease-in-out infinite;
      }

      @keyframes glow {
        0%, 100% { box-shadow: 0 0 10px rgba(139, 92, 246, 0.5); }
        50% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.8); }
      }

      .hero-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(3rem, 6vw, 4.5rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
        background: var(--gradient-glow);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 30px rgba(139, 92, 246, 0.5));
      }

      .hero-subtitle {
        font-size: 1.25rem;
        line-height: 1.6;
        color: var(--text-secondary);
        margin-bottom: 2.5rem;
        max-width: 600px;
      }

      .hero-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
      }

      /* ========= GAMIFIED ELEMENTS ========= */
      .hero-visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .game-card {
        background: var(--bg-card);
        border: 2px solid rgba(139, 92, 246, 0.3);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-card);
        backdrop-filter: blur(20px);
        max-width: 500px;
        position: relative;
        overflow: hidden;
        animation: cardFloat 6s ease-in-out infinite;
      }

      @keyframes cardFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(1deg); }
      }

      .game-card::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: var(--gradient-glow);
        opacity: 0.5;
        filter: blur(10px);
        z-index: -1;
        animation: rotate 3s linear infinite;
      }

      @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      .level-display {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
      }

      .level-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--gradient-main);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
      }

      .xp-display {
        color: var(--neon-yellow);
        font-weight: 600;
        text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
      }

      .progress-bars {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
      }

      .progress-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }

      .progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        color: var(--text-secondary);
      }

      .progress-bar {
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
      }

      .progress-fill {
        height: 100%;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        animation: fillProgress 2s ease-out forwards;
      }

      @keyframes fillProgress {
        from { width: 0; }
      }

      .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 2s infinite;
      }

      @keyframes shine {
        to { left: 100%; }
      }

      .progress-fill.javascript {
        background: var(--gradient-main);
        width: 85%;
      }

      .progress-fill.python {
        background: linear-gradient(90deg, #10b981, #06b6d4);
        width: 72%;
      }

      .progress-fill.react {
        background: linear-gradient(90deg, #ec4899, #8b5cf6);
        width: 90%;
      }

      .achievement-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
      }

      .achievement {
        aspect-ratio: 1;
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
      }

      .achievement:hover {
        transform: scale(1.1);
        background: rgba(139, 92, 246, 0.2);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
      }

      .achievement.locked {
        opacity: 0.3;
        cursor: not-allowed;
      }

      /* ========= BUTTONS ========= */
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
      }

      .btn-primary {
        background: var(--gradient-main);
        color: white;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.6);
      }

      .btn-secondary {
        background: transparent;
        color: var(--neon-cyan);
        border: 2px solid var(--neon-cyan);
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.3);
      }

      .btn-secondary:hover {
        background: rgba(6, 182, 212, 0.1);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.5);
        transform: translateY(-2px);
      }

      .btn-ghost {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .btn-ghost:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        border-color: rgba(139, 92, 246, 0.5);
      }

      /* ========= FEATURES SECTION ========= */
      .features-section {
        padding: 8rem 0;
        position: relative;
      }

      .section-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
      }

      .section-header {
        text-align: center;
        margin-bottom: 4rem;
      }

      .section-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        font-weight: 700;
        margin-bottom: 1rem;
        background: var(--gradient-main);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .section-subtitle {
        font-size: 1.25rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
      }

      .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 4rem;
      }

      .feature-card {
        background: var(--bg-card);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 20px;
        padding: 2.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }

      .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--gradient-main);
      }

      .feature-card:hover {
        transform: translateY(-8px);
        border-color: rgba(139, 92, 246, 0.5);
        box-shadow: 0 10px 30px rgba(139, 92, 246, 0.2);
      }

      .feature-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-main);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
      }

      .feature-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
      }

      .feature-description {
        color: var(--text-secondary);
        line-height: 1.7;
      }

      /* ========= STATS SECTION ========= */
      .stats-section {
        padding: 6rem 0;
        position: relative;
        background: var(--bg-card);
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
      }

      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 3rem;
        margin-top: 3rem;
      }

      .stat-item {
        text-align: center;
      }

      .stat-number {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: var(--gradient-main);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .stat-label {
        font-size: 1.1rem;
        color: var(--text-secondary);
      }

      /* ========= CTA SECTION ========= */
      .cta-section {
        padding: 8rem 0;
        text-align: center;
        position: relative;
      }

      .cta-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 2rem;
      }

      .cta-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        font-weight: 700;
        margin-bottom: 1.5rem;
        background: var(--gradient-glow);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .cta-subtitle {
        font-size: 1.25rem;
        color: var(--text-secondary);
        margin-bottom: 2.5rem;
      }

      /* ========= FOOTER ========= */
      .footer {
        background: var(--bg-card);
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        padding: 3rem 0 2rem;
      }

      .footer-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
      }

      .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-primary);
      }

      .footer-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
      }

      /* ========= RESPONSIVE ========= */
      @media (max-width: 1024px) {
        .hero-content {
          grid-template-columns: 1fr;
          gap: 3rem;
          text-align: center;
        }

        .hero-visual {
          order: -1;
        }

        .game-card {
          max-width: 400px;
        }

        .hero-subtitle {
          margin: 0 auto 2.5rem;
        }
      }

      @media (max-width: 768px) {
        .nav {
          padding: 0 1rem;
        }

        .hero-content {
          padding: 0 1rem;
        }

        .section-content {
          padding: 0 1rem;
        }

        .features-grid {
          grid-template-columns: 1fr;
        }

        .stats-grid {
          grid-template-columns: 1fr;
          gap: 2rem;
        }

        .footer-content {
          flex-direction: column;
          text-align: center;
        }

        .btn {
          width: 100%;
          justify-content: center;
        }

        .hero-buttons {
          flex-direction: column;
          width: 100%;
        }
      }

      /* ========= ANIMATIONS ========= */
      .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
      }

      .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* Floating particles */
      .particle {
        position: fixed;
        pointer-events: none;
        opacity: 0;
        animation: particleFloat 10s infinite;
      }

      @keyframes particleFloat {
        0% {
          opacity: 0;
          transform: translateY(100vh) rotate(0deg);
        }
        10% {
          opacity: 1;
        }
        90% {
          opacity: 1;
        }
        100% {
          opacity: 0;
          transform: translateY(-100vh) rotate(720deg);
        }
      }
    </style>
  </head>
  <body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    <div class="grid-overlay"></div>

    <!-- Floating Particles -->
    <div class="particle" style="left: 10%; font-size: 20px; animation-delay: 0s; color: #8b5cf6;">⚡</div>
    <div class="particle" style="left: 30%; font-size: 24px; animation-delay: 2s; color: #06b6d4;">💎</div>
    <div class="particle" style="left: 50%; font-size: 18px; animation-delay: 4s; color: #10b981;">🚀</div>
    <div class="particle" style="left: 70%; font-size: 22px; animation-delay: 6s; color: #fbbf24;">⭐</div>
    <div class="particle" style="left: 90%; font-size: 20px; animation-delay: 8s; color: #ec4899;">🔥</div>

    <!-- ===== HEADER ===== -->
  <!--  <header class="header" id="header">
      <nav class="nav">
        <a href="/" class="logo">
          <div class="logo-icon">🚀</div>
          <span>CodeLadder</span>
        </a>
        
        @if (Route::has('login'))
          <div class="nav-buttons">
            @auth
              <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Dashboard</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-ghost">Sign In</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
              @endif
            @endauth
          </div>
        @endif
      </nav>
    </header>
--->
    <!-- ===== HERO SECTION ===== -->
    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <div class="hero-badge">
            <span>⚡</span>
            <span>Level up your coding skills</span>
          </div>
          
          <h1 class="hero-title">
            Climb The Ranks.
            <br>
            Master The Code.
          </h1>
          
          <p class="hero-subtitle">
            Join thousands of developers climbing the ladder to coding mastery. 
            Battle through challenges, earn achievements, and build real projects 
            in our gamified learning platform.
          </p>
          
          <div class="hero-buttons">
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-primary">
                <span>🎮</span>
                Start Your Journey
              </a>
            @endif
            @if (Route::has('login'))
              <a href="{{ route('login') }}" class="btn btn-secondary">
                <span>⚔️</span>
                Continue Quest
              </a>
            @endif
          </div>
        </div>
        
        <div class="hero-visual">
          <div class="game-card">
            <div class="level-display">
              <div class="level-badge">
                <span>⭐</span>
                <span>LEVEL 42</span>
              </div>
              <div class="xp-display">2,450 XP</div>
            </div>
            
            <div class="progress-bars">
              <div class="progress-item">
                <div class="progress-label">
                  <span>JavaScript Mastery</span>
                  <span>85%</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill javascript"></div>
                </div>
              </div>
              
              <div class="progress-item">
                <div class="progress-label">
                  <span>Python Power</span>
                  <span>72%</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill python"></div>
                </div>
              </div>
              
              <div class="progress-item">
                <div class="progress-label">
                  <span>React Mastery</span>
                  <span>90%</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill react"></div>
                </div>
              </div>
            </div>
            
            <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Recent Achievements</h3>
            <div class="achievement-grid">
              <div class="achievement" title="Speed Coder">⚡</div>
              <div class="achievement" title="Bug Slayer">🐛</div>
              <div class="achievement" title="100 Day Streak">🔥</div>
              <div class="achievement" title="Algorithm Master">🧠</div>
              <div class="achievement locked" title="Locked">🔒</div>
              <div class="achievement locked" title="Locked">🔒</div>
              <div class="achievement locked" title="Locked">🔒</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FEATURES SECTION ===== -->
    <section class="features-section">
      <div class="section-content">
        <div class="section-header fade-in">
          <h2 class="section-title">Level Up Your Skills</h2>
          <p class="section-subtitle">
            Experience coding education reimagined as an epic adventure
          </p>
        </div>
        
        <div class="features-grid">
          <div class="feature-card fade-in">
            <div class="feature-icon">🎮</div>
            <h3 class="feature-title">Gamified Learning</h3>
            <p class="feature-description">
              Earn XP, unlock achievements, and climb the global leaderboard. 
              Every line of code brings you closer to mastery.
            </p>
          </div>
          
          <div class="feature-card fade-in">
            <div class="feature-icon">⚔️</div>
            <h3 class="feature-title">Code Battles</h3>
            <p class="feature-description">
              Challenge other developers in real-time coding duels. 
              Test your skills and learn from the community.
            </p>
          </div>
          
          <div class="feature-card fade-in">
            <div class="feature-icon">🏰</div>
            <h3 class="feature-title">Project Quests</h3>
            <p class="feature-description">
              Build real-world applications through guided quests. 
              From web apps to AI projects, create an impressive portfolio.
            </p>
          </div>
          
          <div class="feature-card fade-in">
            <div class="feature-icon">📈</div>
            <h3 class="feature-title">Skill Trees</h3>
            <p class="feature-description">
              Visualize your learning path with interactive skill trees. 
              Choose your specialization and track your progress.
            </p>
          </div>
          
          <div class="feature-card fade-in">
            <div class="feature-icon">🏆</div>
            <h3 class="feature-title">Daily Challenges</h3>
            <p class="feature-description">
              New coding challenges every day keep your skills sharp. 
              Maintain your streak and earn exclusive rewards.
            </p>
          </div>
          
          <div class="feature-card fade-in">
            <div class="feature-icon">👥</div>
            <h3 class="feature-title">Guild System</h3>
            <p class="feature-description">
              Join or create guilds with fellow coders. 
              Collaborate on projects and participate in team events.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== STATS SECTION ===== -->
    <section class="stats-section">
      <div class="section-content">
        <h2 class="section-title">Join The Adventure</h2>
        <p class="section-subtitle">
          Be part of a thriving community of developers
        </p>
        
        <div class="stats-grid">
          <div class="stat-item fade-in">
            <div class="stat-number">50,000+</div>
            <div class="stat-label">Active Players</div>
          </div>
          <div class="stat-item fade-in">
            <div class="stat-number">1,200+</div>
            <div class="stat-label">Coding Challenges</div>
          </div>
          <div class="stat-item fade-in">
            <div class="stat-number">300+</div>
            <div class="stat-label">Project Quests</div>
          </div>
          <div class="stat-item fade-in">
            <div class="stat-number">15M+</div>
            <div class="stat-label">Lines of Code Written</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== CTA SECTION ===== -->
    <section class="cta-section">
      <div class="cta-content fade-in">
        <h2 class="cta-title">Ready to Start Climbing?</h2>
        <p class="cta-subtitle">
          Your coding adventure begins now. Join CodeLadder and transform 
          the way you learn programming.
        </p>
        
        <div class="hero-buttons">
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary">
              <span></span>
              Begin Your Quest
            </a>
          @endif
          @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-ghost">
              <span>📚</span>
              View Learning Paths
            </a>
          @endif
        </div>
      </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-logo">
          <div class="logo-icon">🚀</div>
          <span>CodeLadder</span>
        </div>
        <div class="footer-text">
          © {{ date('Y') }} CodeLadder. Level up your code, level up your career.
        </div>
      </div>
    </footer>

    <script>
      // Header scroll effect
      window.addEventListener('scroll', () => {
        const header = document.getElementById('header');
        if (window.scrollY > 100) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      });

      // Scroll animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, observerOptions);

      document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
      });

      // Achievement hover effect
      document.querySelectorAll('.achievement:not(.locked)').forEach(achievement => {
        achievement.addEventListener('mouseenter', function() {
          this.style.transform = 'scale(1.2) rotate(5deg)';
        });
        
        achievement.addEventListener('mouseleave', function() {
          this.style.transform = 'scale(1) rotate(0deg)';
        });
      });

      // Progress bar animations on scroll
      const progressBars = document.querySelectorAll('.progress-fill');
      const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.animation = 'fillProgress 2s ease-out forwards, shine 2s infinite';
          }
        });
      }, { threshold: 0.5 });

      progressBars.forEach(bar => {
        progressObserver.observe(bar);
      });

      // Interactive particle creation on click
      document.addEventListener('click', function(e) {
        if (e.target.closest('.btn') || e.target.closest('a')) return;
        
        const particles = ['⚡', '💫', '✨', '🌟'];
        const particle = document.createElement('div');
        particle.className = 'click-particle';
        particle.textContent = particles[Math.floor(Math.random() * particles.length)];
        particle.style.cssText = `
          position: fixed;
          left: ${e.clientX}px;
          top: ${e.clientY}px;
          font-size: 20px;
          pointer-events: none;
          z-index: 9999;
          animation: clickParticle 1s ease-out forwards;
        `;
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 1000);
      });

      // Add click particle animation
      const style = document.createElement('style');
      style.textContent = `
        @keyframes clickParticle {
          0% {
            transform: translate(-50%, -50%) scale(0);
            opacity: 1;
          }
          100% {
            transform: translate(-50%, -150%) scale(1.5);
            opacity: 0;
          }
        }
      `;
      document.head.appendChild(style);

      // Smooth button interactions
      document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
          const rect = this.getBoundingClientRect();
          const x = rect.left + rect.width / 2;
          const y = rect.top + rect.height / 2;
          
          for (let i = 0; i < 3; i++) {
            setTimeout(() => {
              const spark = document.createElement('div');
              spark.style.cssText = `
                position: fixed;
                left: ${x + (Math.random() - 0.5) * 40}px;
                top: ${y + (Math.random() - 0.5) * 40}px;
                width: 4px;
                height: 4px;
                background: #8b5cf6;
                border-radius: 50%;
                pointer-events: none;
                z-index: 9999;
                animation: sparkFade 0.5s ease-out forwards;
              `;
              document.body.appendChild(spark);
              setTimeout(() => spark.remove(), 500);
            }, i * 50);
          }
        });
      });

      // Add spark fade animation
      const sparkStyle = document.createElement('style');
      sparkStyle.textContent = `
        @keyframes sparkFade {
          0% {
            transform: scale(0);
            opacity: 1;
          }
          100% {
            transform: scale(2);
            opacity: 0;
          }
        }
      `;
      document.head.appendChild(sparkStyle);
    </script>
  </body>
    </x-app-layout></div>
              <div class="achievement locked" title="Locke