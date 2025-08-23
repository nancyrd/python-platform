<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PyLearn') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;inter:400,500,600,700&display=swap" rel="stylesheet"/>

   
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}"></script>
</head>
<body class="font-sans antialiased bg-gradient-to-b from-purple-50 via-white to-white text-gray-900">
    <div class="relative min-h-screen">

        <!-- soft ambient blobs -->
        <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 -top-24 h-72 bg-hero"></div>

        <!-- Top navigation -->
        @include('layouts.navigation')

        @isset($header)
            <header class="sticky top-0 z-30 backdrop-blur bg-white/70 border-b border-purple-100">
                <div class="container-app py-5">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="container-app py-8">
            {{ $slot }}
        </main>

      <!-- Footer -->
<!-- Footer -->
<footer class="py-footer mt-16">
  <style>
    /* ❌ No :root, no body rules here */
    .py-footer{
      /* Define variables only for this component */
      --ft-primary:#7B2CBF;   /* mid purple */
      --ft-accent:#5A189A;    /* deep purple */
      --ft-light:#E9D5FF;     /* light purple */
      --ft-muted:#C4B5FD;     /* soft lavender */
      --ink:#F9FAFB;          /* near-white */

      background:linear-gradient(180deg, var(--ft-accent) 0%, var(--ft-primary) 100%);
      color:var(--ink);
    }

    .py-footer .container-app{ max-width:1400px; margin:0 auto; padding:0 1rem; }

    .py-footer .ft-top{ display:flex; flex-direction:column; gap:18px; padding:28px 0; }
    @media (min-width: 900px){ .py-footer .ft-top{ flex-direction:row; align-items:center; justify-content:space-between; } }

    .py-footer .brand-link{ display:flex; align-items:center; gap:.75rem; text-decoration:none; }
    .py-footer .brand-box{
      width:42px; height:42px; border-radius:12px;
      background:#fff; color:var(--ft-accent);
      display:flex; align-items:center; justify-content:center;
      font-weight:900; font-size:1rem;
    }
    .py-footer .brand-text{ font-weight:900; font-size:1.15rem; letter-spacing:-.02em; color:var(--ink); }
    .py-footer .ft-mission{ color:var(--ft-muted); max-width:720px; }

    .py-footer .ft-grid{ display:grid; gap:20px; padding:12px 0 28px; grid-template-columns: 1fr 1fr; }
    @media (min-width: 680px){ .py-footer .ft-grid{ grid-template-columns: repeat(3,1fr); } }
    @media (min-width: 980px){ .py-footer .ft-grid{ grid-template-columns: 2fr 1fr 1fr 1fr; } }

    .py-footer .ft-card{ background:rgba(255,255,255,0.05); border-radius:16px; padding:16px; }
    .py-footer .ft-title{ margin:0 0 .6rem; color:#fff; font-size:1.05rem; font-weight:800; }

    .py-footer .ft-list{ list-style:none; padding:0; margin:.25rem 0 0; }
    .py-footer .ft-list a{ display:block; padding:.4rem 0; color:var(--ft-light); text-decoration:none; border-radius:8px; transition:.2s; }
    .py-footer .ft-list a:hover{ color:#fff; transform:translateX(2px); }

    .py-footer .ft-input{ flex:1; border:none; border-radius:999px; padding:.8rem 1rem; background:#fff; color:#111; }
    .py-footer .ft-btn{
      border:none; border-radius:999px; padding:.8rem 1.1rem; font-weight:800; cursor:pointer;
      color:#fff; background:linear-gradient(90deg, #6D28D9, var(--ft-primary));
    }
    .py-footer .ft-btn:hover{ opacity:.9; }

    .py-footer .ft-bottom{ display:flex; flex-direction:column; gap:12px; padding:18px 0 28px; border-top:1px solid rgba(255,255,255,.15); }
    @media (min-width: 900px){ .py-footer .ft-bottom{ flex-direction:row; align-items:center; justify-content:space-between; } }

    .py-footer .ft-meta{ color:var(--ft-muted); font-size:.95rem; }
    .py-footer .ft-actions{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; }

    .py-footer .ft-social a{
      display:inline-flex; align-items:center; justify-content:center;
      width:36px; height:36px; border-radius:10px;
      color:#fff; background:rgba(255,255,255,.08);
      transition:.2s;
    }
    .py-footer .ft-social a:hover{ background:rgba(255,255,255,.2); transform:translateY(-1px); }
  </style>

  <div class="container-app">
    <!-- Top -->
    <div class="ft-top">
      <a href="{{ url('/') }}" class="brand-link">
        <div class="brand-box">Py</div>
        <span class="brand-text">{{ config('app.name', 'PyLearn') }}</span>
      </a>
      <p class="ft-mission">
        Python learning that’s welcoming to <strong>non-CS students</strong> — clear explanations, hands-on practice, and projects you can show off.
      </p>
    </div>

    <!-- Middle -->
    <div class="ft-grid">
      <div class="ft-card">
        <h3 class="ft-title">Newsletter</h3>
        <p style="color:var(--ft-muted); margin:.2rem 0 .6rem;">Get weekly Python tips for busy learners.</p>
        <form action="#" class="flex gap-2">
          <input class="ft-input" type="email" placeholder="you@example.com" required>
          <button class="ft-btn">Subscribe</button>
        </form>
      </div>

      <div class="ft-card">
        <h3 class="ft-title">Platform</h3>
        <ul class="ft-list">
          <li><a href="{{ Route::has('dashboard') ? route('dashboard') : url('/dashboard') }}">Continue Learning</a></li>
          <li><a href="{{ Route::has('paths.index') ? route('paths.index') : url('/paths') }}">Learning Paths</a></li>
          <li><a href="{{ Route::has('about') ? route('about') : url('/about-us') }}">About Us</a></li>
          <li><a href="{{ Route::has('support') ? route('support') : url('/support') }}">Support</a></li>
        </ul>
      </div>

      <div class="ft-card">
        <h3 class="ft-title">Resources</h3>
        <ul class="ft-list">
          <li><a href="{{ url('/blog') }}">Blog</a></li>
          <li><a href="{{ url('/docs') }}">Docs</a></li>
          <li><a href="{{ url('/status') }}">System Status</a></li>
          <li><a href="{{ url('/faq') }}">FAQs</a></li>
        </ul>
      </div>

      <div class="ft-card">
        <h3 class="ft-title">Legal</h3>
        <ul class="ft-list">
          <li><a href="{{ url('/terms') }}">Terms</a></li>
          <li><a href="{{ url('/privacy') }}">Privacy</a></li>
          <li><a href="{{ url('/cookies') }}">Cookies</a></li>
          <li><a href="{{ url('/license') }}">License</a></li>
        </ul>
      </div>
    </div>

    <!-- Bottom -->
    <div class="ft-bottom">
      <div class="ft-meta">© {{ date('Y') }} {{ config('app.name', 'PyLearn') }}</div>
      <div class="ft-actions">
        <span class="ft-meta">Made with ❤️ for learners</span>
        <div class="ft-social">
          <a href="#" aria-label="GitHub">GH</a>
          <a href="#" aria-label="YouTube">YT</a>
          <a href="#" aria-label="LinkedIn">In</a>
        </div>
      </div>
    </div>
  </div>
</footer>


    </div>
</body>
</html>
