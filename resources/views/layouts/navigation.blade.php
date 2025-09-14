@php
    $items = [];

    if (Auth::check() && Auth::user()->role !== 'admin') {
        // Normal user links only
        $items[] = ['label' => 'Home', 'href' => url('/')];

        if (Route::has('dashboard')) {
            $items[] = ['label' => 'Dashboard', 'href' => route('dashboard'), 'route' => 'dashboard'];
        }
        if (Route::has('about')) {
            $items[] = ['label' => 'About Us', 'href' => route('about'), 'route' => 'about'];
        }
        if (Route::has('support')) {
            $items[] = ['label' => 'Support', 'href' => route('support'), 'route' => 'support'];
        }
    }

    if (Auth::check() && Auth::user()->role === 'admin') {
        // Admin links only
        $items[] = ['label' => 'Admin Dashboard', 'href' => route('admin.dashboard'), 'route' => 'admin.dashboard'];
        $items[] = ['label' => 'Manage Stages', 'href' => route('admin.stages.index'), 'route' => 'admin.stages.index'];
        $items[] = ['label' => 'Manage Levels', 'href' => route('admin.levels.index'), 'route' => 'admin.levels.index'];
    }
@endphp


<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
  :root{
    --nav-primary:#7B2CBF; --nav-primary-700:#5A189A;
    --nav-primary-300:#CDB4DB; --nav-primary-100:#F3E8FF; --nav-primary-50:#FAF5FF;
    --ink:#1F2937; --muted:#6B7280; --panel:#FFFFFF;
  }

  .nav-container{
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--nav-primary-100);
    box-shadow: 0 2px 12px rgba(17,24,39,.06);
  }
  .nav-container.scrolled{ background: rgba(255,255,255,.98); box-shadow: 0 8px 24px rgba(17,24,39,.08); }

  .container-app{ max-width:1400px; margin:0 auto; padding:0 1rem; }

  /* Brand */
  .brand-link{ display:flex; align-items:center; gap:.75rem; text-decoration:none; transition:.2s ease; position:relative; }
  .brand-link:hover{ transform: translateY(-1px); filter: drop-shadow(0 0 10px rgba(123,44,191,.18)); }
  .brand-icon{ width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg, var(--nav-primary-700), var(--nav-primary));
    display:flex; align-items:center; justify-content:center; font-weight:800; color:#fff; font-size:1rem; box-shadow: 0 4px 12px rgba(90,24,154,.25); }
  .brand-text{ font-weight:800; font-size:1.15rem; letter-spacing:-.02em;
    background: linear-gradient(90deg, var(--nav-primary-700), var(--nav-primary));
    -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }

  /* Nav pills */
  .nav-pill{ position:relative; overflow:hidden; padding:.5rem 1rem; border-radius:10px; font-weight:600; color:var(--muted); text-decoration:none; transition:.2s ease; }
  .nav-pill:hover{ background:var(--nav-primary-50); color:var(--nav-primary-700); }
  .nav-pill.is-active{ color:var(--nav-primary-700); background:#fff; border:1px solid var(--nav-primary-100); box-shadow:0 1px 0 rgba(90,24,154,.06); }
  .nav-pill.is-active::after{ content:''; position:absolute; left:18%; right:18%; bottom:6px; height:2px; background:linear-gradient(90deg, var(--nav-primary), var(--nav-primary-700)); opacity:.55; }

  /* User dropdown trigger */
  .user-dropdown-btn{ display:flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:10px; color:var(--ink); background:#fff; border:1px solid var(--nav-primary-100); transition:.2s ease; }
  .user-dropdown-btn:hover{ background:var(--nav-primary-50); border-color:var(--nav-primary-300); box-shadow:0 4px 10px rgba(90,24,154,.12); }

  /* Mobile */
  .mobile-menu-btn{ display:inline-flex; align-items:center; justify-content:center; padding:.5rem; border-radius:10px; background:#fff; border:1px solid #e5e7eb; transition:.2s ease; color:var(--ink); }
  .mobile-menu-btn:hover{ background:var(--nav-primary-50); border-color:var(--nav-primary-100); }
  .mobile-panel{ background:#fff; border-top:1px solid var(--nav-primary-100); box-shadow:0 12px 20px rgba(17,24,39,.06); }
  .mobile-nav-item{ display:block; padding:.75rem 1rem; border-radius:10px; color:var(--ink); text-decoration:none; transition:.2s ease; }
  .mobile-nav-item:hover{ background:var(--nav-primary-50); color:var(--nav-primary-700); }
  .mobile-nav-item.active{ background:var(--nav-primary-100); color:var(--nav-primary-700); font-weight:600; }

  .dropdown-content{ background:#fff; border:1px solid var(--nav-primary-100); border-radius:12px; box-shadow:0 12px 24px rgba(17,24,39,.08); }
  .dropdown-link{ display:block; padding:.75rem 1rem; color:var(--ink); }
  .dropdown-link:hover{ background:var(--nav-primary-50); color:var(--nav-primary-700); }

  .user-info-name{ padding:0 1rem; font-size:.9rem; color:var(--ink); font-weight:600; }
  .user-info-email{ padding:0 1rem; font-size:.78rem; color:var(--muted); margin-bottom:.5rem; }

  .logout-btn{ width:100%; text-align:left; background:transparent; border:none; padding:.75rem 1rem; border-radius:10px; color:var(--ink); transition:.2s ease; }
  .logout-btn:hover{ background:#fee2e2; color:#b91c1c; padding-left:1.5rem; }

  /* --- Utility shims so this works without Tailwind --- */
  .hidden{display:none;} .flex{display:flex;} .inline-flex{display:inline-flex;}
  .items-center{align-items:center;} .justify-between{justify-content:space-between;}
  .gap-1{gap:.25rem;} .gap-2{gap:.5rem;}
  .h-16{height:4rem;} .h-6{height:1.5rem;} .w-6{width:1.5rem;} .h-4{height:1rem;} .w-4{width:1rem;}
  .ms-6{margin-inline-start:1.5rem;} .space-y-1 > * + *{margin-top:.25rem;}
  .sticky{position:sticky;} .top-0{top:0;} .z-40{z-index:40;}
  .sm\:flex{display:none;} .sm\:items-center{} .sm\:ms-6{} .sm\:hidden{}
  @media (min-width:640px){
    .sm\:flex{display:flex !important;}
    .hidden.sm\:flex{display:flex !important;}
    .sm\:items-center{align-items:center !important;}
    .sm\:ms-6{margin-inline-start:1.5rem !important;}
    .sm\:hidden{display:none !important;}
  }
  [x-cloak]{display:none!important;}
</style>

<nav x-data="{ open: false }" class="sticky top-0 z-40 nav-container">
  <div class="container-app h-16 flex items-center justify-between">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="brand-link" aria-label="Zero2Py home">
      <div class="brand-icon">Py</div>
      <span class="brand-text">Zero2Py</span>
    </a>

    <!-- Desktop Nav -->
    @auth
      <div class="hidden sm:flex items-center gap-1">
        @foreach ($items as $item)
          @php
            $active = isset($item['route'])
              ? request()->routeIs($item['route'])
              : request()->is('/'); // Home
          @endphp
          <a href="{{ $item['href'] }}" class="nav-pill {{ $active ? 'is-active' : '' }}">
            {{ $item['label'] }}
          </a>
        @endforeach
      </div>

      <!-- User dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ms-6">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="user-dropdown-btn">
              <div class="font-medium">{{ Auth::user()->name }}</div>
              <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
              </svg>
            </button>
          </x-slot>

          <x-slot name="content">
            <div class="dropdown-content">
              <x-dropdown-link :href="route('profile.edit')" class="dropdown-link">
                {{ __('Profile') }}
              </x-dropdown-link>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" class="dropdown-link"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                  {{ __('Log Out') }}
                </x-dropdown-link>
              </form>
            </div>
          </x-slot>
        </x-dropdown>
      </div>
    @else
      <!-- Guest Navigation -->
      <div class="hidden sm:flex items-center gap-2">
        <a href="{{ route('login') }}" class="nav-pill">Sign In</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="nav-pill is-active">Get Started</a>
        @endif
      </div>
    @endauth

    <!-- Mobile hamburger -->
    <button @click="open = !open" class="sm:hidden mobile-menu-btn" aria-label="Toggle menu">
      <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <template x-if="!open">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </template>
        <template x-if="open">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"/>
        </template>
      </svg>
    </button>
  </div>

  <!-- Mobile panel -->
  <div x-show="open" x-transition x-cloak class="sm:hidden mobile-panel">
    <div class="container-app py-3 space-y-1">
      @auth
        @foreach ($items as $item)
          @php
            $active = isset($item['route'])
              ? request()->routeIs($item['route'])
              : request()->is('/');
          @endphp
          <a href="{{ $item['href'] }}" class="mobile-nav-item {{ $active ? 'active' : '' }}">
            {{ $item['label'] }}
          </a>
        @endforeach

        <div class="mobile-user-section">
          <div class="user-info-name">{{ Auth::user()->name }}</div>
          <div class="user-info-email">{{ Auth::user()->email }}</div>
          <a href="{{ route('profile.edit') }}" class="mobile-nav-item">{{ __('Profile') }}</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mobile-nav-item" style="background:#fee2e2;color:#b91c1c;">
              {{ __('Log Out') }}
            </button>
          </form>
        </div>
      @else
        <a href="{{ route('login') }}" class="mobile-nav-item">Sign In</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="mobile-nav-item active">Get Started</a>
        @endif
      @endauth
    </div>
  </div>
</nav>

<script>
  // Scroll effect
  window.addEventListener('scroll', () => {
    const nav = document.querySelector('.nav-container');
    if (!nav) return;
    if (window.scrollY > 50) { nav.classList.add('scrolled'); }
    else { nav.classList.remove('scrolled'); }
  });

  // Brand sparkle
  const brand = document.querySelector('.brand-link');
  if (brand){
    brand.addEventListener('mouseenter', function() {
      for (let i = 0; i < 3; i++) {
        setTimeout(() => {
          const spark = document.createElement('div');
          spark.style.cssText = `
            position:absolute;width:4px;height:4px;background:#8b5cf6;border-radius:50%;
            pointer-events:none;animation:sparkFade .6s ease-out forwards;
            left:${Math.random()*40}px;top:${Math.random()*40}px;`;
          this.appendChild(spark);
          setTimeout(() => spark.remove(), 600);
        }, i*100);
      }
    });
  }

  // Animation keyframes (once)
  if (!document.querySelector('#nav-animations')) {
    const style = document.createElement('style');
    style.id = 'nav-animations';
    style.textContent = `
      @keyframes sparkFade {
        0% { transform: scale(0) translate(0,0); opacity: 1; }
        100% { transform: scale(1.5) translate(10px,-10px); opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  }
</script>
{{-- ========= END NAV ========= --}}
