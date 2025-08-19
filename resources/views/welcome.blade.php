<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Python Platform — Welcome</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700" rel="stylesheet" />

    <!-- App styles if Vite exists -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
      /* ========= BRAND TOKENS ========= */
      :root{
        --brand-50:#f5f3ff; --brand-100:#ede9fe; --brand-200:#ddd6fe; --brand-300:#c4b5fd;
        --brand-400:#a78bfa; --brand-500:#8b5cf6; --brand-600:#7c3aed; --brand-700:#6d28d9; --brand-800:#5b21b6;
        --ink:#0f1220; --ink-2:#667085; --paper:#ffffff; --paper-2:#fbfbfe; --line:rgba(139,92,246,.22);
        --card:#ffffff; --ring:rgba(139,92,246,.45); --shadow:0 1px 2px rgba(16,24,40,.06), 0 24px 32px rgba(16,24,40,.06);
        --grad-1: radial-gradient(60% 40% at 0% -10%, rgba(124,58,237,.18), rgba(124,58,237,0) 60%);
        --grad-2: radial-gradient(70% 50% at 100% 0%, rgba(167,139,250,.18), rgba(167,139,250,0) 60%);
      }
      @media (prefers-color-scheme:dark){
        :root{
          --ink:#ededec; --ink-2:#a1a09a; --paper:#09090b; --paper-2:#0f0f12; --line:rgba(167,139,250,.3);
          --card:#141417; --shadow:0 1px 1px rgba(0,0,0,.45), 0 20px 32px rgba(0,0,0,.35);
          --grad-1: radial-gradient(60% 40% at 0% -10%, rgba(124,58,237,.28), rgba(124,58,237,0) 60%);
          --grad-2: radial-gradient(70% 50% at 100% 0%, rgba(167,139,250,.28), rgba(167,139,250,0) 60%);
        }
      }

      /* ========= BASE ========= */
      *{box-sizing:border-box} html{font-family:'Instrument Sans',system-ui,sans-serif}
      body{margin:0; color:var(--ink); background:linear-gradient(180deg,#ffffff 0%,#fafaff 100%);}
      a{color:inherit; text-decoration:none}

      /* ========= LAYOUT ========= */
      .container{width:100%; max-width:1200px; margin-inline:auto; padding-inline:1rem}
      .app-header{position:sticky; top:0; z-index:50; backdrop-filter:saturate(140%) blur(10px); background:rgba(255,255,255,.7); border-bottom:1px solid var(--line)}
      @media (prefers-color-scheme:dark){ .app-header{ background:rgba(12,12,18,.55) } }
      .navbar{display:flex; align-items:center; justify-content:space-between; padding:.9rem 0}
      .brand{display:flex; align-items:center; gap:.75rem}
      .brand-badge{width:40px; height:40px; border-radius:9999px; display:inline-flex; align-items:center; justify-content:center; font-weight:800; color:white; background:linear-gradient(120deg,var(--brand-700),var(--brand-500)); box-shadow:0 10px 20px rgba(124,58,237,.35)}
      .brand-sub{color:var(--ink-2); font-size:.9rem}

      .hero{position:relative; isolation:isolate; padding-block:clamp(2.5rem, 5vw, 4rem)}
      .hero::before{content:""; position:absolute; inset:0; background:var(--grad-1), var(--grad-2); z-index:-1}
      .hero-grid{display:grid; gap:2rem; align-items:stretch; grid-template-columns:1fr}
      @media (min-width:1024px){ .hero-grid{grid-template-columns:1.1fr .9fr} }

      .card{background:var(--card); border:1px solid var(--line); border-radius:16px; padding:clamp(1rem, 2vw, 1.5rem); box-shadow:var(--shadow)}
      .card h1{font-size:clamp(2rem, 4vw, 3rem); line-height:1.1; margin:.25rem 0 .6rem}
      .muted{color:var(--ink-2)}

      .btn{display:inline-flex; align-items:center; justify-content:center; gap:.6rem; padding:.8rem 1.05rem; border-radius:12px; font-weight:700; border:1px solid transparent; transition:transform .06s ease, box-shadow .2s ease, background .2s ease}
      .btn:active{transform:translateY(1px)}
      .btn-brand{background:linear-gradient(120deg,var(--brand-700),var(--brand-600)); color:#fff; box-shadow:0 14px 28px rgba(124,58,237,.28)}
      .btn-brand:hover{filter:brightness(.98)}
      .btn-ghost{background:transparent; color:var(--brand-700); border-color:var(--brand-400)}
      .btn-ghost:hover{background:var(--brand-50)}

      .pill{display:inline-flex; align-items:center; gap:.5rem; background:var(--brand-50); color:var(--brand-700); padding:.35rem .7rem; border-radius:9999px; font-weight:700; font-size:.8rem}

      .code{background:var(--paper-2); border:1px solid var(--line); border-radius:12px; padding:1rem; overflow:auto; font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace; font-size:.95rem}
      .k-purple{color:var(--brand-700)} .k-gray{color:#475467} .k-green{color:#059669}

      .list{list-style:none; margin:0; padding:0}
      .list-item{display:flex; gap:.85rem; margin:1rem 0}
      .badge{width:30px; height:30px; border-radius:9999px; display:inline-flex; align-items:center; justify-content:center; background:var(--brand-100); color:var(--brand-700); font-weight:800}

      .features{display:grid; gap:1.25rem; grid-template-columns:1fr}
      @media (min-width:900px){ .features{grid-template-columns:repeat(3,1fr)} }

      .feature{position:relative; padding-left:2.25rem}
      .feature::before{content:""; position:absolute; left:.6rem; top:.9rem; width:8px; height:8px; border-radius:9999px; background:var(--brand-500); box-shadow:0 0 0 6px var(--brand-100)}

      .trust{display:flex; gap:1rem; flex-wrap:wrap; align-items:center; margin-top:1rem}
      .trust .chip{font-size:.8rem; padding:.45rem .65rem; border:1px solid var(--line); border-radius:9999px; background:var(--paper); color:var(--ink-2)}

      footer{border-top:1px solid var(--line); margin-top:2.5rem; padding-block:1.25rem; color:var(--ink-2)}
      .footer-row{display:flex; align-items:center; justify-content:space-between}

      /* Subtle raised gradient bar at the very bottom */
      .footbar{position:fixed; inset:auto 0 0 0; height:4px; background:linear-gradient(90deg,var(--brand-500),var(--brand-600),var(--brand-700)); opacity:.35}
    </style>
  </head>
  <body>
    <!-- ===== Header ===== -->
    <header class="app-header" role="banner">
      <div class="container navbar">
        <a href="/" class="brand" aria-label="Python Platform home">
          <span class="brand-badge" aria-hidden>Py</span>
          <div>
            <div style="font-weight:800">Python Platform</div>
            <div class="brand-sub">for Non‑CS Students</div>
          </div>
        </a>
        @if (Route::has('login'))
          <nav style="display:flex; gap:.75rem; align-items:center" aria-label="Primary">
            @auth
              <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Dashboard</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-ghost">Log in</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-brand">Get started</a>
              @endif
            @endauth
          </nav>
        @endif
      </div>
    </header>

    <!-- ===== Hero ===== -->
    <main class="container hero">
      <div class="hero-grid">
        <!-- Left: copy -->
        <section class="card" aria-labelledby="hero-title">
          <span class="pill">No CS background required</span>
          <h1 id="hero-title">Learn Python step‑by‑step</h1>
          <p class="muted" style="font-size:1.08rem">
            Bite‑sized lessons, interactive practice, and a friendly path built for complete beginners.
            Start coding real things in minutes — not months.
          </p>
          <div style="margin-top:1.2rem; display:flex; gap:.75rem; flex-wrap:wrap">
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-brand">Start learning</a>
            @endif
            @if (Route::has('login'))
              <a href="{{ route('login') }}" class="btn btn-ghost">I already have an account</a>
            @endif
          </div>
          <div class="trust" aria-label="Highlights">
            <span class="chip">Beginner friendly</span>
            <span class="chip">Hands‑on practice</span>
            <span class="chip">Short lessons</span>
          </div>
        </section>

        <!-- Right: preview + bullets -->
        <section class="card">
<pre class="code" aria-label="Code preview"><span class="k-purple"># Simple example</span>
<span class="k-gray">name</span> = input(<span class="k-green">"What's your name? "</span>)
print(<span class="k-green">f"Nice to meet you, {name}!"</span>)

<span class="k-purple"># Tiny challenge:</span>
for i in range(3):
    print(i + 1)
</pre>
          <ul class="list" style="margin-top:1rem">
            <li class="list-item">
              <span class="badge">1</span>
              <div>
                <div style="font-weight:700">Guided stages</div>
                <div class="muted">Variables → Loops → Functions → Projects, with checkpoints and badges.</div>
              </div>
            </li>
            <li class="list-item">
              <span class="badge">2</span>
              <div>
                <div style="font-weight:700">Beginner‑friendly</div>
                <div class="muted">No jargon. Plain language, lots of examples, instant feedback.</div>
              </div>
            </li>
            <li class="list-item">
              <span class="badge">3</span>
              <div>
                <div style="font-weight:700">Practice‑first</div>
                <div class="muted">Short exercises inside each lesson so you learn by doing.</div>
              </div>
            </li>
          </ul>
        </section>
      </div>

      <!-- Feature cards -->
      <section class="features" style="margin-top:2rem">
        <div class="card feature">
          <div style="font-weight:700; margin-bottom:.25rem">What you’ll build</div>
          <div class="muted">Mini‑projects like a quiz app, a budgeting helper, and a to‑do console app. Perfect for your portfolio and confidence.</div>
        </div>
        <div class="card feature">
          <div style="font-weight:700; margin-bottom:.25rem">Designed for non‑CS students</div>
          <div class="muted">We explain every concept from scratch and relate it to real‑life examples. No math‑heavy prerequisites.</div>
        </div>
        <div class="card feature">
          <div style="font-weight:700; margin-bottom:.25rem">Progress you can see</div>
          <div class="muted">Badges, streaks, and a clean dashboard keep you motivated day by day.</div>
        </div>
      </section>
    </main>

    <footer>
      <div class="container footer-row">
        <div class="text-sm">© {{ date('Y') }} Python Platform</div>
        <div class="text-sm">Made with <span aria-hidden>❤</span> for beginners</div>
      </div>
    </footer>

    <div class="footbar" aria-hidden></div>
  </body>
</html>
