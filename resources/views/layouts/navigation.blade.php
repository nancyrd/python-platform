@php
    $items = [];
    
    // Only add items if their routes exist
    if (Route::has('dashboard')) {
        $items[] = ['label' => 'Continue', 'route' => 'dashboard'];
    }
    if (Route::has('about')) {
        $items[] = ['label' => 'About Us', 'route' => 'about'];
    }
    if (Route::has('contact')) {
        $items[] = ['label' => 'support', 'route' => 'support'];
    }
@endphp
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
  :root{
    /* Unified purple theme (same as Help Center/About) */
    --nav-primary:#7B2CBF;          /* primary */
    --nav-primary-700:#5A189A;       /* deep accent */
    --nav-primary-300:#CDB4DB;       /* soft border */
    --nav-primary-100:#F3E8FF;       /* light wash */
    --nav-primary-50:#FAF5FF;

    --ink:#1F2937;                   /* gray-800 */
    --muted:#6B7280;                 /* gray-500/600 */
    --panel:#FFFFFF;
  }

  .nav-container{
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--nav-primary-100);
    box-shadow: 0 2px 12px rgba(17,24,39,.06);
  }
  .nav-container.scrolled{
    background: rgba(255,255,255,.98);
    box-shadow: 0 8px 24px rgba(17,24,39,.08);
  }

  .container-app{ max-width:1400px; margin:0 auto; padding:0 1rem; }

  /* Brand */
  .brand-link{ display:flex; align-items:center; gap:.75rem; text-decoration:none; transition:.2s ease; position:relative; }
  .brand-link:hover{ transform: translateY(-1px); filter: drop-shadow(0 0 10px rgba(123,44,191,.18)); }

  .brand-icon{
  width:40px; height:40px; border-radius:12px;
  background:linear-gradient(135deg, var(--nav-primary-700), var(--nav-primary));
  display:flex; align-items:center; justify-content:center;
  font-weight:800; color:#fff; font-size:1rem;
  box-shadow: 0 4px 12px rgba(90,24,154,.25);
}

.brand-text{
  font-weight:800; font-size:1.15rem; letter-spacing:-.02em;
  background: linear-gradient(90deg, var(--nav-primary-700), var(--nav-primary));
  -webkit-background-clip:text;
  background-clip:text;
  -webkit-text-fill-color:transparent;
}

  .brand-link:hover .brand-text{
    background: linear-gradient(90deg, var(--nav-primary-700), var(--nav-primary));
    -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
  }

  /* Nav pills */
  .nav-pill{
    position:relative; overflow:hidden;
    padding:.5rem 1rem; border-radius:10px; font-weight:600;
    color:var(--muted); text-decoration:none; transition:.2s ease;
  }
  .nav-pill:hover{ background:var(--nav-primary-50); color:var(--nav-primary-700); }
  .nav-pill.is-active{
    color:var(--nav-primary-700);
    background:#fff;
    border:1px solid var(--nav-primary-100);
    box-shadow:0 1px 0 rgba(90,24,154,.06);
  }
  .nav-pill.is-active::after{
    content:''; position:absolute; left:18%; right:18%; bottom:6px; height:2px;
    background:linear-gradient(90deg, var(--nav-primary), var(--nav-primary-700));
    opacity:.55;
  }

  /* User dropdown trigger */
  .user-dropdown-btn{
    display:flex; align-items:center; gap:.5rem;
    padding:.5rem 1rem; border-radius:10px;
    color:var(--ink); background:#fff;
    border:1px solid var(--nav-primary-100); transition:.2s ease;
  }
  .user-dropdown-btn:hover{
    background:var(--nav-primary-50);
    border-color:var(--nav-primary-300);
    box-shadow:0 4px 10px rgba(90,24,154,.12);
  }

  /* Mobile */
  .mobile-menu-btn{
    display:inline-flex; align-items:center; justify-content:center;
    padding:.5rem; border-radius:10px; background:#fff; border:1px solid #e5e7eb; transition:.2s ease; color:var(--ink);
  }
  .mobile-menu-btn:hover{ background:var(--nav-primary-50); border-color:var(--nav-primary-100); }

  .mobile-panel{
    background:#fff;
    border-top:1px solid var(--nav-primary-100);
    box-shadow:0 12px 20px rgba(17,24,39,.06);
  }
  .mobile-nav-item{
    display:block; padding:.75rem 1rem; border-radius:10px;
    color:var(--ink); text-decoration:none; transition:.2s ease;
  }
  .mobile-nav-item:hover{ background:var(--nav-primary-50); color:var(--nav-primary-700); }
  .mobile-nav-item.active{ background:var(--nav-primary-100); color:var(--nav-primary-700); font-weight:600; }

  .dropdown-content{
    background:#fff; border:1px solid var(--nav-primary-100);
    border-radius:12px; box-shadow:0 12px 24px rgba(17,24,39,.08);
  }
  .dropdown-link{ display:block; padding:.75rem 1rem; color:var(--ink); }
  .dropdown-link:hover{ background:var(--nav-primary-50); color:var(--nav-primary-700); }

  .user-info-name{ padding:0 1rem; font-size:.9rem; color:var(--ink); font-weight:600; }
  .user-info-email{ padding:0 1rem; font-size:.78rem; color:var(--muted); margin-bottom:.5rem; }

  .logout-btn{ width:100%; text-align:left; background:transparent; border:none; padding:.75rem 1rem; border-radius:10px; color:var(--ink); transition:.2s ease; }
  .logout-btn:hover{ background:#fee2e2; color:#b91c1c; padding-left:1.5rem; }

  /* brand mark sizing */
  .brand-icon svg.brand-mark{ width:26px; height:26px; }
</style>


<nav x-data="{ open: false }" class="sticky top-0 z-40 nav-container">
    <div class="container-app h-16 flex items-center justify-between">

        <!-- Brand -->
      <a href="/" class="brand-link" aria-label="PyLearn home">
  <div class="brand-icon">
    Py
  </div>
  <span class="brand-text">PyLearn</span>
</a>





        <!-- Desktop Nav -->
        @auth
            <div class="hidden sm:flex items-center gap-1">
                @foreach ($items as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="nav-pill {{ $active ? 'is-active' : '' }}">
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
        <button @click="open = !open" class="sm:hidden mobile-menu-btn">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="sm:hidden mobile-panel">
        <div class="container-app py-3 space-y-1">
            @auth
                @foreach ($items as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="mobile-nav-item {{ $active ? 'active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="mobile-user-section">
                    <div class="user-info-name">{{ Auth::user()->name }}</div>
                    <div class="user-info-email">{{ Auth::user()->email }}</div>
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-item">
                        {{ __('Profile') }}
                    </a>
                   <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="dropdown-link w-full text-left">
        {{ __('Log Out') }}
    </button>
</form>
                </div>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-item">
                    Sign In
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="mobile-nav-item active">
                        Get Started
                    </a>
                @endif
            @endauth
        </div>
    </div>
</nav>

<script>
    
    // Add scroll effect to navigation
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.nav-container');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Add sparkle effect on brand hover
    document.querySelector('.brand-link').addEventListener('mouseenter', function() {
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                const spark = document.createElement('div');
                spark.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: #8b5cf6;
                    border-radius: 50%;
                    pointer-events: none;
                    animation: sparkFade 0.6s ease-out forwards;
                    left: ${Math.random() * 40}px;
                    top: ${Math.random() * 40}px;
                `;
                this.appendChild(spark);
                setTimeout(() => spark.remove(), 600);
            }, i * 100);
        }
    });

    // Add animation styles if not already present
    if (!document.querySelector('#nav-animations')) {
        const style = document.createElement('style');
        style.id = 'nav-animations';
        style.textContent = `
            @keyframes sparkFade {
                0% {
                    transform: scale(0) translate(0, 0);
                    opacity: 1;
                }
                100% {
                    transform: scale(1.5) translate(10px, -10px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
   
</script>