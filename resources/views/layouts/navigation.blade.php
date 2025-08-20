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
        $items[] = ['label' => 'Contact Us', 'route' => 'contact'];
    }
@endphp
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root {
        /* Electric Purple & Cyan Theme */
        --nav-primary: #8b5cf6;
        --nav-primary-dark: #7c3aed;
        --nav-secondary: #06b6d4;
        --nav-accent: #10b981;
        
        /* Dark Mode Colors */
        --nav-bg-dark: #0f0f23;
        --nav-bg-darker: #0a0a1b;
        --nav-bg-card: #1a1a2e;
        --nav-bg-hover: #252542;
        
        /* Text */
        --nav-text-primary: #ffffff;
        --nav-text-secondary: #a1a1aa;
        --nav-text-muted: #71717a;
        
        /* Gradients */
        --nav-gradient-main: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
        --nav-gradient-glow: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 50%, #10b981 100%);
    }

    .nav-container {
        background: rgba(15, 15, 35, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .nav-container.scrolled {
        background: rgba(15, 15, 35, 0.98);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
    }

    .container-app {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Brand Logo */
    .brand-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .brand-link:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.8));
    }

    .brand-icon {
        width: 40px;
        height: 40px;
        background: var(--nav-gradient-main);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
        animation: iconPulse 2s ease-in-out infinite;
    }

    @keyframes iconPulse {
        0%, 100% { 
            transform: scale(1); 
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
        }
        50% { 
            transform: scale(1.05); 
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.8);
        }
    }

    .brand-text {
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: -0.025em;
        background: var(--nav-gradient-main);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Navigation Pills */
    .nav-pill {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        color: var(--nav-text-secondary);
        transition: all 0.3s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .nav-pill::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, transparent 70%);
        transition: all 0.5s ease;
        transform: translate(-50%, -50%);
    }

    .nav-pill:hover {
        color: var(--nav-text-primary);
        background: rgba(139, 92, 246, 0.1);
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.2);
    }

    .nav-pill:hover::before {
        width: 100px;
        height: 100px;
    }

    .nav-pill.is-active {
        color: var(--nav-primary);
        background: rgba(139, 92, 246, 0.2);
        font-weight: 600;
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
    }

    .nav-pill.is-active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20%;
        right: 20%;
        height: 2px;
        background: var(--nav-gradient-main);
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
        animation: glowLine 2s ease-in-out infinite;
    }

    @keyframes glowLine {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 1; }
    }

    /* User Dropdown Button */
    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        color: var(--nav-text-primary);
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .user-dropdown-btn:hover {
        background: rgba(139, 92, 246, 0.2);
        border-color: rgba(139, 92, 246, 0.5);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
        transform: translateY(-1px);
    }

    .user-dropdown-btn:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.5), 0 0 20px rgba(139, 92, 246, 0.4);
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 8px;
        color: var(--nav-text-primary);
        background: transparent;
        border: 1px solid rgba(139, 92, 246, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .mobile-menu-btn:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: rgba(139, 92, 246, 0.5);
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);
    }

    .mobile-menu-btn:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.5);
    }

    /* Mobile Panel */
    .mobile-panel {
        background: rgba(15, 15, 35, 0.98);
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        backdrop-filter: blur(20px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .mobile-nav-item {
        display: block;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: var(--nav-text-secondary);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .mobile-nav-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 0;
        height: 100%;
        background: linear-gradient(90deg, rgba(139, 92, 246, 0.2) 0%, transparent 100%);
        transition: width 0.3s ease;
        transform: translateY(-50%);
    }

    .mobile-nav-item:hover {
        color: var(--nav-text-primary);
        background: rgba(139, 92, 246, 0.1);
        padding-left: 1.5rem;
    }

    .mobile-nav-item:hover::before {
        width: 100%;
    }

    .mobile-nav-item.active {
        background: rgba(139, 92, 246, 0.2);
        color: var(--nav-primary);
        font-weight: 600;
        box-shadow: inset 0 0 20px rgba(139, 92, 246, 0.1);
    }

    .mobile-user-section {
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        position: relative;
    }

    .mobile-user-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20%;
        right: 20%;
        height: 1px;
        background: var(--nav-gradient-main);
        box-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
    }

    .user-info-name {
        padding: 0 1rem;
        font-size: 0.875rem;
        color: var(--nav-text-primary);
        font-weight: 600;
    }

    .user-info-email {
        padding: 0 1rem;
        font-size: 0.75rem;
        color: var(--nav-text-muted);
        margin-bottom: 0.75rem;
    }

    .logout-btn {
        width: 100%;
        text-align: left;
        background: transparent;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: var(--nav-text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .logout-btn:hover {
        background: rgba(236, 72, 153, 0.1);
        color: #ec4899;
        padding-left: 1.5rem;
    }

    /* Dropdown Content Styling (if using x-dropdown component) */
    .dropdown-content {
        background: var(--nav-bg-card);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(20px);
    }

    .dropdown-link {
        display: block;
        padding: 0.75rem 1rem;
        color: var(--nav-text-secondary);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .dropdown-link:hover {
        background: rgba(139, 92, 246, 0.1);
        color: var(--nav-text-primary);
    }
</style>

<nav x-data="{ open: false }" class="sticky top-0 z-40 nav-container">
    <div class="container-app h-16 flex items-center justify-between">

        <!-- Brand -->
        <a href="/" class="brand-link">
            <div class="brand-icon">🚀</div>
            <span class="brand-text">CodeLadder</span>
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