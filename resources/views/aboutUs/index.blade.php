{{-- resources/views/about/index.blade.php --}}
<x-app-layout>
   

    <!-- Page Styles (scoped to this page) -->
    <style>
      :root{
        --primary-purple:#7B2CBF;
        --secondary-purple:#9D4EDD;
        --accent-purple:#5A189A;
        --panel:#ffffff;
        --bg:#fafafa;
        --border:#d2b7f0;
        --ink:#222;
        --muted:#666;
      }
      body{ background: var(--bg); }

      .au-wrap{ max-width: 1100px; margin: 2rem auto; padding: 1rem; }

      /* Hero */
      .au-hero{ text-align:center; margin: 2rem 0 2rem; }
      .au-kicker{ display:inline-flex; gap:.5rem; align-items:center; padding:.35rem .7rem; border:1px solid var(--border); border-radius:999px; background:#fff; color:var(--accent-purple); font-weight:700; font-size:.85rem; }
      .au-title{ color:var(--accent-purple); font-size:clamp(1.9rem, 1.2rem + 2.8vw, 3rem); margin:.7rem 0 .5rem; font-weight:900; letter-spacing:-.02em; }
      .au-sub{ color:var(--muted); margin:0 auto; max-width: 760px; font-size:1.05rem; }

      /* Stats */
      .au-stats{ display:grid; grid-template-columns: repeat(2,1fr); gap:14px; margin:1.5rem auto 2.2rem; max-width:720px; }
      @media (min-width: 900px){ .au-stats{ grid-template-columns: repeat(4,1fr);} }
      .stat{ background:#fff; border:2px solid var(--border); border-radius:18px; padding:1rem; text-align:center; box-shadow:0 0 10px rgba(0,0,0,.04); }
      .stat .num{ font-weight:900; font-size:1.6rem; color:var(--primary-purple); }
      .stat .label{ color:var(--muted); font-size:.9rem; }

      /* Two-column sections */
      .grid-2{ display:grid; grid-template-columns:1fr; gap:24px; margin: 2.2rem 0; }
      @media (min-width: 960px){ .grid-2{ grid-template-columns: 1.1fr .9fr; } }

      .panel{ background: var(--panel); border: 2px solid var(--border); border-radius: 18px; box-shadow: 0 0 10px rgba(0,0,0,.05); padding: 1.25rem; }
      .panel h3{ margin:0 0 .65rem; color:var(--primary-purple); font-size:1.3rem; }
      .panel p{ color:var(--ink); margin:.4rem 0; }

      /* Feature grid */
      .feature-grid{ display:grid; grid-template-columns: 1fr; gap:16px; }
      @media (min-width: 900px){ .feature-grid{ grid-template-columns: repeat(3,1fr);} }
      .feature{ background:#fff; border:1px solid var(--border); border-radius:16px; padding:1rem; }
      .feature h4{ margin:.2rem 0 .4rem; color:var(--ink); font-size:1.05rem; }
      .feature p{ margin:0; color:var(--muted); font-size:.98rem; }

      /* Timeline */
      .timeline{ position:relative; padding-left:1rem; }
      .timeline::before{ content:""; position:absolute; left:.35rem; top:.25rem; bottom:.25rem; width:3px; background:linear-gradient(180deg, var(--secondary-purple), var(--accent-purple)); border-radius:3px; }
      .t-item{ position:relative; margin:0 0 1rem 0; padding-left:1.2rem; }
      .t-item::before{ content:""; position:absolute; left:-.1rem; top:.35rem; width:.7rem; height:.7rem; border-radius:999px; background:var(--accent-purple); box-shadow:0 0 0 4px rgba(157,78,221,.2); }
      .t-title{ margin:0; font-weight:800; color:var(--ink); }
      .t-sub{ margin:.15rem 0 0; color:var(--muted); font-size:.95rem; }

      /* Team */
      .team{ display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
      @media (min-width: 900px){ .team{ grid-template-columns: repeat(4,1fr);} }
      .member{ background:#fff; border:1px solid var(--border); border-radius:16px; padding:1rem; text-align:center; }
      .avatar{ width:72px; height:72px; border-radius:999px; background:#f3e9ff; margin:0 auto .6rem; display:flex; align-items:center; justify-content:center; font-weight:900; color:var(--accent-purple); }
      .role{ color:var(--muted); font-size:.9rem; margin:.1rem 0 0; }

      /* CTA */
      .cta{ margin: 2.6rem 0; text-align:center; background: linear-gradient(135deg, #fff, #fbf6ff); border:2px solid var(--border); border-radius:22px; padding:1.6rem; }
      .cta h3{ margin:.2rem 0 .6rem; color:var(--accent-purple); font-size:1.6rem; }
      .btn{ border:none; cursor:pointer; font-weight:800; border-radius:999px; padding:.9rem 1.6rem; transition: transform .15s, box-shadow .2s, background .2s; }
      .btn-primary{ background: var(--secondary-purple); color:#fff; }
      .btn-primary:hover{ background:var(--accent-purple); transform:translateY(-1px); box-shadow:0 8px 18px rgba(90,24,154,.3); }

      .muted{ color:var(--muted); }
    </style>

    <div class="au-wrap">
      <!-- HERO -->
      <section class="au-hero">
        <span class="au-kicker">Non‑CS? No problem! 🐍</span>
        <h1 class="au-title">We teach Python for real‑world careers — no computer science degree required.</h1>
        <p class="au-sub">
          Our mission is to open the door to tech‑powered jobs for students and professionals from non‑CS backgrounds.
          We turn complex topics into friendly, guided steps you can master — at your pace, with support when you need it.
        </p>
      </section>

      <!-- QUICK STATS -->
      <div class="au-stats">
        <div class="stat"><div class="num">30k+</div><div class="label">Learners</div></div>
        <div class="stat"><div class="num">92%</div><div class="label">Course Completion</div></div>
        <div class="stat"><div class="num">4.8/5</div><div class="label">Learner Rating</div></div>
        <div class="stat"><div class="num">100+ hrs</div><div class="label">Hands‑on Practice</div></div>
      </div>

      <!-- MISSION + HOW WE TEACH -->
      <div class="grid-2">
        <section class="panel">
          <h3>Our mission</h3>
          <p>Make Python the most accessible skill for non‑CS students. Whether you study biology, business, art, education, or law — we help you apply Python directly to your field.</p>
          <p>We believe in <strong>practice‑first learning</strong>, personal feedback, and projects that showcase your abilities to employers and graduate programs.</p>
        </section>
        <section class="panel">
          <h3>How we teach</h3>
          <div class="feature-grid">
            <div class="feature">
              <h4>Guided Paths</h4>
              <p>From zero to job‑ready: fundamentals → data analysis → automation → mini‑capstone.</p>
            </div>
            <div class="feature">
              <h4>Non‑CS Friendly</h4>
              <p>No heavy theory. Just clear explanations, analogies, and step‑by‑step practice.</p>
            </div>
            <div class="feature">
              <h4>Portfolio Projects</h4>
              <p>Build real projects (reports, dashboards, scripts) aligned to your major or career goals.</p>
            </div>
          </div>
        </section>
      </div>

      <!-- WHAT MAKES US DIFFERENT -->
      <section class="panel">
        <h3>What makes us different</h3>
        <div class="feature-grid">
          <div class="feature">
            <h4>Contextual Lessons</h4>
            <p>Apply Python to your domain: finance models, lab data, marketing analytics, classroom tools, and more.</p>
          </div>
          <div class="feature">
            <h4>Hands‑on, Not Just Videos</h4>
            <p>Short lessons, instant practice, auto‑grading, and instructor feedback keep you moving.</p>
          </div>
          <div class="feature">
            <h4>Career Support</h4>
            <p>Resume templates, project reviews, and mock interviews tailored to non‑CS profiles.</p>
          </div>
        </div>
      </section>

      <!-- TIMELINE -->
      <div class="grid-2">
        <section class="panel">
          <h3>Our story</h3>
          <div class="timeline">
            <div class="t-item">
              <p class="t-title">2022 — The idea</p>
              <p class="t-sub">We started tutoring non‑CS students 1:1 and saw how much materials assumed prior background.</p>
            </div>
            <div class="t-item">
              <p class="t-title">2023 — The first cohort</p>
              <p class="t-sub">Pilot course with 120 learners from 8 majors. 90% completion and dozens of internships.</p>
            </div>
            <div class="t-item">
              <p class="t-title">2024 — Project‑based platform</p>
              <p class="t-sub">Launched our hands‑on platform with instant feedback and capstone reviews.</p>
            </div>
            <div class="t-item">
              <p class="t-title">2025 — University partnerships</p>
              <p class="t-sub">Integrated with career centers and programs to support thousands more learners.</p>
            </div>
          </div>
        </section>
        <section class="panel">
          <h3>Meet the team</h3>
          <div class="team">
            <div class="member"><div class="avatar">AB</div><strong>Amal B.</strong><div class="role">Curriculum Lead</div></div>
            <div class="member"><div class="avatar">KM</div><strong>Kareem M.</strong><div class="role">Data Mentor</div></div>
            <div class="member"><div class="avatar">SN</div><strong>Sara N.</strong><div class="role">Student Success</div></div>
            <div class="member"><div class="avatar">YZ</div><strong>Yousef Z.</strong><div class="role">Platform Engineer</div></div>
          </div>
        </section>
      </div>

      <!-- MICRO FAQ (optional) -->
      <section class="panel">
        <h3>Questions we get a lot</h3>
        <details>
          <summary><strong>Do I need prior coding experience?</strong></summary>
          <p class="muted">No. We start from absolute zero and build up with small, practical steps.</p>
        </details>
        <details>
          <summary><strong>How much time per week?</strong></summary>
          <p class="muted">4–6 hours is typical. You can go faster or slower depending on your schedule.</p>
        </details>
        <details>
          <summary><strong>Will I build a portfolio?</strong></summary>
          <p class="muted">Yes—every path includes projects you can showcase on LinkedIn or in applications.</p>
        </details>
      </section>

      <!-- CTA -->
      <section class="cta">
        <h3>Start your Python journey today</h3>
        <p class="muted">Join thousands of non‑CS learners who’ve already taken the first step.</p>
        <div style="margin-top:.8rem; display:flex; gap:.6rem; justify-content:center; flex-wrap:wrap;">
          <a href="{{ route('register') }}" class="btn btn-primary">Create your free account</a>
          <a href="{{ route('dashboard') }}" class="btn" style="border:2px solid var(--border); background:#fff;">Explore learning paths</a>
        </div>
      </section>
    </div>
</x-app-layout>
