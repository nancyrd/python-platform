{{-- resources/views/about/index.blade.php --}}
<x-app-layout>
  {{-- Header (same purple system + layout as previous stages) --}}
  <x-slot name="header">
    <div class="level-header">
      <div class="header-container">
        <div class="header-left">
          <div class="level-badge">
            <span class="level-number">ℹ︎</span>
          </div>
          <div class="level-info">
            <div class="breadcrumb">
              <span class="breadcrumb-item">Home</span>
              <span class="separator">•</span>
              <span class="breadcrumb-item type">About</span>
            </div>
            <h1 class="stage-title">{{ config('app.name', 'Platform') }}</h1>
            <div class="level-title">We teach Python for real-world careers — no CS degree required</div>
          </div>
        </div>
        <div class="header-right">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-label">Learners</div>
              <div class="stat-value">30k+</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Completion</div>
              <div class="stat-value">92%</div>
            </div>
            <div class="stat-item">
              <div class="stat-label">Rating</div>
              <div class="stat-value">4.8/5</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </x-slot>

  <style>
    :root {
      /* Same shared palette */
      --primary-purple: #7c3aed;
      --secondary-purple: #a855f7;
      --light-purple: #c084fc;
      --purple-subtle: #f3e8ff;

      --gray-50: #f8fafc;
      --gray-100: #f1f5f9;
      --gray-200: #e2e8f0;
      --gray-300: #cbd5e1;
      --gray-400: #94a3b8;
      --gray-500: #64748b;
      --gray-600: #475569;
      --gray-700: #334155;
      --gray-800: #1e293b;
      --gray-900: #0f172a;

      --success: #10b981;
      --success-light: #dcfce7;
      --warning: #f59e0b;
      --warning-light: #fef3c7;
      --danger: #ef4444;
      --danger-light: #fecaca;

      --background: #ffffff;
      --surface: #f8fafc;
      --border: #e2e8f0;
      --text-primary: #1e293b;
      --text-secondary: #475569;
      --text-muted: #64748b;

      --shadow-sm: 0 1px 2px 0 rgba(0,0,0,.05);
      --shadow:    0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
      --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
      --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
    }

    body {
      background: linear-gradient(135deg,
        rgba(124,58,237,.03) 0%,
        rgba(168,85,247,.02) 50%,
        rgba(248,250,252,1) 100%);
      color: var(--text-primary);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    }

    /* Header (shared) */
    .level-header { background: linear-gradient(135deg, rgba(124,58,237,.05), rgba(168,85,247,.03)); border-bottom:1px solid var(--border); backdrop-filter: blur(10px); }
    .header-container { display:flex; align-items:center; justify-content:space-between; padding:1.5rem 2rem; gap:2rem; }
    .header-left { display:flex; align-items:center; gap:1.5rem; flex:1; min-width:0; }
    .level-badge { width:4rem; height:4rem; border-radius:1rem; background:linear-gradient(135deg, var(--primary-purple), var(--secondary-purple)); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-md); }
    .level-number { font-weight:900; font-size:1.25rem; color:#fff; }
    .level-info { flex:1; min-width:0; }
    .breadcrumb { display:flex; align-items:center; gap:.5rem; font-size:.875rem; color:var(--text-muted); margin-bottom:.25rem; }
    .breadcrumb-item.type { color:var(--primary-purple); font-weight:500; text-transform:capitalize; }
    .separator{opacity:.6}
    .stage-title { font-size:1.5rem; font-weight:700; margin:0; color:var(--text-primary); }
    .level-title { font-size:1rem; color:var(--text-secondary); margin-top:.25rem; }
    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
    .stat-item { text-align:center; padding:.75rem 1rem; background:#fff; border:1px solid var(--border); border-radius:.75rem; box-shadow:var(--shadow-sm); min-width:6rem; }
    .stat-label { font-size:.75rem; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.05em; }
    .stat-value { font-size:1.125rem; font-weight:800; color:var(--text-primary); margin-top:.25rem; }

    /* Full-bleed helpers (shared) */
    .full-bleed { width:100vw; margin-left:calc(50% - 50vw); margin-right:calc(50% - 50vw); }
    .edge-pad { padding: 1.25rem clamp(12px, 3vw, 32px); }

    /* Cards (shared) */
    .card { background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem 1.5rem; box-shadow:var(--shadow-sm); }
    .card.accent { border-left:6px solid var(--primary-purple); background:linear-gradient(180deg, var(--purple-subtle), #fff); }
    .section-title { font-size:1.125rem; font-weight:800; margin:0 0 .75rem 0; color:var(--text-primary); }

    /* About-specific layout */
    .about-wrap { max-width:1120px; margin: 1rem auto 2rem; }
    .grid-2 { display:grid; grid-template-columns:1fr; gap:1rem; }
    @media (min-width: 960px){ .grid-2 { grid-template-columns: 1.1fr .9fr; } }

    /* Feature grid */
    .feature-grid { display:grid; grid-template-columns:1fr; gap:.75rem; }
    @media (min-width: 900px){ .feature-grid{ grid-template-columns: repeat(3,1fr);} }
    .feature { background:#fff; border:1px solid var(--border); border-radius:.75rem; padding:1rem; box-shadow:var(--shadow-sm); }
    .feature h4{ margin:.25rem 0 .35rem; color:var(--text-primary); font-weight:800; font-size:1.02rem; }
    .feature p{ margin:0; color:var(--text-secondary); }

    /* Timeline */
    .timeline { position:relative; padding-left:1rem; }
    .timeline::before{ content:""; position:absolute; left:.4rem; top:.35rem; bottom:.35rem; width:3px; background:linear-gradient(180deg, var(--secondary-purple), var(--primary-purple)); border-radius:3px; }
    .t-item{ position:relative; margin:0 0 .85rem 0; padding-left:1rem; }
    .t-item::before{ content:""; position:absolute; left:-.1rem; top:.3rem; width:.65rem; height:.65rem; border-radius:999px; background:var(--primary-purple); box-shadow:0 0 0 4px rgba(124,58,237,.15); }
    .t-title{ margin:0; font-weight:800; color:var(--text-primary); }
    .t-sub{ margin:.15rem 0 0; color:var(--text-secondary); font-size:.95rem; }

    /* Team */
    .team{ display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
    @media (min-width: 900px){ .team{ grid-template-columns: repeat(4,1fr);} }
    .member{ background:#fff; border:1px solid var(--border); border-radius:.75rem; padding:1rem; text-align:center; box-shadow:var(--shadow-sm); }
    .avatar{ width:72px; height:72px; border-radius:999px; background:var(--purple-subtle); margin:0 auto .6rem; display:flex; align-items:center; justify-content:center; font-weight:900; color:#5827c7; }
    .role{ color:var(--text-muted); font-size:.9rem; margin:.1rem 0 0; }

    /* CTA + Buttons (shared look) */
    .cta { text-align:center; background:#fff; border:1px solid var(--border); border-radius:1rem; padding:1.25rem 1.5rem; box-shadow:var(--shadow-sm); }
    .cta h3 { margin:.2rem 0 .4rem; color:var(--text-primary); font-size:1.25rem; font-weight:900; }
    .btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.25rem; border:none; border-radius:.75rem; font-weight:800; font-size:.95rem; cursor:pointer; transition:all .18s ease; text-decoration:none; }
    .btn-primary { background:linear-gradient(135deg,var(--primary-purple),var(--secondary-purple)); color:#fff; box-shadow:var(--shadow); }
    .btn-primary:hover{ transform:translateY(-2px); box-shadow:var(--shadow-lg); }
    .btn-secondary { background:var(--gray-100); color:var(--text-primary); border:1px solid var(--border); }
    .btn-secondary:hover{ background:var(--gray-200); transform:translateY(-1px); box-shadow:var(--shadow); }

    /* Spacing helpers */
    .stack { display:grid; gap:1rem; }
  </style>

  <!-- MAIN CONTENT -->
  <div class="about-wrap full-bleed edge-pad stack">

    <!-- HERO / INTRO -->
    <section class="card accent">
      <div class="section-title">Non-CS? No problem! 🐍</div>
      <h2 style="margin:.25rem 0 .5rem; font-size: clamp(1.6rem, 1rem + 2vw, 2.1rem); font-weight:900; letter-spacing:-.02em;">
        We teach Python for real-world careers — no computer science degree required.
      </h2>
      <p style="color:var(--text-secondary); max-width: 880px; margin:0;">
        Our mission is to open the door to tech-powered jobs for students and professionals from non-CS backgrounds.
        We turn complex topics into friendly, guided steps you can master — at your pace, with support when you need it.
      </p>
    </section>

    <!-- QUICK STATS (matches header style) -->
    <section class="card">
      <div class="section-title">By the numbers</div>
      <div class="stats-grid" style="grid-template-columns: repeat(4,1fr);">
        <div class="stat-item"><div class="stat-label">Learners</div><div class="stat-value">30k+</div></div>
        <div class="stat-item"><div class="stat-label">Completion</div><div class="stat-value">92%</div></div>
        <div class="stat-item"><div class="stat-label">Rating</div><div class="stat-value">4.8/5</div></div>
        <div class="stat-item"><div class="stat-label">Practice</div><div class="stat-value">100+ hrs</div></div>
      </div>
    </section>

    <!-- MISSION + HOW WE TEACH -->
    <section class="grid-2">
      <div class="card">
        <div class="section-title">Our mission</div>
        <p style="margin:.25rem 0; color:var(--text-primary);">
          Make Python the most accessible skill for non-CS students. Whether you study biology, business, art, education, or law — we help you apply Python directly to your field.
        </p>
        <p style="margin:.25rem 0; color:var(--text-primary);">
          We believe in <strong>practice-first learning</strong>, personal feedback, and projects that showcase your abilities to employers and graduate programs.
        </p>
      </div>

      <div class="card">
        <div class="section-title">How we teach</div>
        <div class="feature-grid">
          <div class="feature">
            <h4>Guided Paths</h4>
            <p>From zero to job-ready: fundamentals → data analysis → automation → mini-capstone.</p>
          </div>
          <div class="feature">
            <h4>Non-CS Friendly</h4>
            <p>No heavy theory. Just clear explanations, analogies, and step-by-step practice.</p>
          </div>
          <div class="feature">
            <h4>Portfolio Projects</h4>
            <p>Build real projects (reports, dashboards, scripts) aligned to your major or career goals.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- WHAT MAKES US DIFFERENT -->
    <section class="card">
      <div class="section-title">What makes us different</div>
      <div class="feature-grid">
        <div class="feature">
          <h4>Contextual Lessons</h4>
          <p>Apply Python to your domain: finance models, lab data, marketing analytics, classroom tools, and more.</p>
        </div>
        <div class="feature">
          <h4>Hands-on, Not Just Videos</h4>
          <p>Short lessons, instant practice, auto-grading, and instructor feedback keep you moving.</p>
        </div>
        <div class="feature">
          <h4>Career Support</h4>
          <p>Resume templates, project reviews, and mock interviews tailored to non-CS profiles.</p>
        </div>
      </div>
    </section>

    <!-- TIMELINE + TEAM -->
    <section class="grid-2">
      <div class="card">
        <div class="section-title">Our story</div>
        <div class="timeline">
          <div class="t-item">
            <p class="t-title">2022 — The idea</p>
            <p class="t-sub">We started tutoring non-CS students 1:1 and saw how much materials assumed prior background.</p>
          </div>
          <div class="t-item">
            <p class="t-title">2023 — The first cohort</p>
            <p class="t-sub">Pilot course with 120 learners from 8 majors. 90% completion and dozens of internships.</p>
          </div>
          <div class="t-item">
            <p class="t-title">2024 — Project-based platform</p>
            <p class="t-sub">Launched our hands-on platform with instant feedback and capstone reviews.</p>
          </div>
          <div class="t-item">
            <p class="t-title">2025 — University partnerships</p>
            <p class="t-sub">Integrated with career centers and programs to support thousands more learners.</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="section-title">Meet the team</div>
        <div class="team">
          <div class="member"><div class="avatar">AB</div><strong>Amal B.</strong><div class="role">Curriculum Lead</div></div>
          <div class="member"><div class="avatar">KM</div><strong>Kareem M.</strong><div class="role">Data Mentor</div></div>
          <div class="member"><div class="avatar">SN</div><strong>Sara N.</strong><div class="role">Student Success</div></div>
          <div class="member"><div class="avatar">YZ</div><strong>Yousef Z.</strong><div class="role">Platform Engineer</div></div>
        </div>
      </div>
    </section>

    <!-- MICRO FAQ -->
    <section class="card">
      <div class="section-title">Questions we get a lot</div>
      <details style="margin:.25rem 0;">
        <summary style="font-weight:800; color:var(--text-primary); cursor:pointer;">Do I need prior coding experience?</summary>
        <p style="color:var(--text-secondary); margin:.4rem 0 0;">No. We start from absolute zero and build up with small, practical steps.</p>
      </details>
      <details style="margin:.25rem 0;">
        <summary style="font-weight:800; color:var(--text-primary); cursor:pointer;">How much time per week?</summary>
        <p style="color:var(--text-secondary); margin:.4rem 0 0;">4–6 hours is typical. You can go faster or slower depending on your schedule.</p>
      </details>
      <details style="margin:.25rem 0;">
        <summary style="font-weight:800; color:var(--text-primary); cursor:pointer;">Will I build a portfolio?</summary>
        <p style="color:var(--text-secondary); margin:.4rem 0 0;">Yes—every path includes projects you can showcase on LinkedIn or in applications.</p>
      </details>
    </section>

    <!-- CTA -->
    <section class="card accent">
      <h3 class="section-title" style="margin-bottom:.25rem;">Start your Python journey today</h3>
      <p style="color:var(--text-secondary); margin:0 0 .75rem;">Join thousands of non-CS learners who’ve already taken the first step.</p>
      <div style="margin-top:.5rem; display:flex; gap:.6rem; flex-wrap:wrap;">
        <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create your free account</a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-compass"></i> Explore learning paths</a>
      </div>
    </section>

  </div>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
