<x-guest-layout>
  
    <style>
        :root{
            --p1:#6a11cb;      /* deep purple */
            --p2:#8e2de2;      /* vivid purple */
            --p3:#b972ff;      /* soft purple */
            --glow:0 10px 40px rgba(142,45,226,.45);
        }

        /* Full-screen cosmic gradient */
        body{
            min-height:100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;

            background:
                radial-gradient(1200px 800px at 10% -10%, rgba(185,114,255,.25), transparent 60%),
                radial-gradient(900px 900px at 120% 20%, rgba(106,17,203,.25), transparent 60%),
                linear-gradient(135deg, var(--p1) 0%, var(--p2) 100%);
            overflow-x:hidden;
        }

        /* floating particles */
        .particles{
            position:fixed; inset:0; pointer-events:none; z-index:0;
        }
        .particle{
            position:absolute; width:6px; height:6px; border-radius:50%;
            background:rgba(255,255,255,.6); filter:blur(.3px);
            animation: float 10s linear infinite;
            box-shadow: 0 0 12px rgba(255,255,255,.9);
        }
        @keyframes float{
            0%{ transform: translateY(110vh) translateX(0) scale(.6); opacity:0;}
            10%{opacity:1}
            100%{ transform: translateY(-10vh) translateX(20vw) scale(1); opacity:0;}
        }

        /* Glass card */
        .auth-card{
            position:relative;
            z-index:1;
            width:100%;
            max-width: 460px;
            margin-inline:auto;
            padding: clamp(1.25rem, 2vw, 1.75rem);
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.18);
            box-shadow: var(--glow);
        }

        /* header/logo aura */
        .header-aurora{
            position:absolute; inset:-2px;
            border-radius:24px;
            background: conic-gradient(from 140deg, rgba(255,255,255,.12), rgba(255,255,255,0), rgba(255,255,255,.12));
            filter: blur(18px); opacity:.35; z-index:-1;
        }

        .title{
            font-weight:900; letter-spacing:.4px;
            background:linear-gradient(90deg,#fff, #ffefff 30%, #e6ccff 70%);
            -webkit-background-clip:text; background-clip:text; color:transparent;
            text-shadow:0 0 24px rgba(255,255,255,.15);
        }
        .subtitle{ color:#e9d7ff; opacity:.85; font-size:.95rem }

        /* inputs (Breeze components inherit these) */
        .input-like{
            background: rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.25);
            color:#fff; border-radius:14px; padding:14px 14px;
            transition:.25s ease; outline:none;
        }
        .input-like:focus{
            border-color:#fff; box-shadow:0 0 0 4px rgba(185,114,255,.25), var(--glow);
            transform: translateY(-1px);
        }
        .label{
            color:#f7ecff; font-weight:700; font-size:.9rem; margin-bottom:.45rem; display:block;
        }
        .hint,.link{ color:#e9d7ff; opacity:.85; font-size:.85rem }

        /* purple primary button */
        .btn-primary{
            display:inline-flex; align-items:center; gap:.6rem;
            background:linear-gradient(135deg, var(--p2), var(--p1));
            color:white; border:none; border-radius:14px;
            padding:12px 18px; font-weight:800; letter-spacing:.4px;
            box-shadow: var(--glow);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }
        .btn-primary:hover{ transform: translateY(-2px); filter:brightness(1.08); }
        .btn-primary:active{ transform: translateY(0); }

        /* tiny utilities */
        .row{ display:flex; align-items:center; justify-content:space-between; gap:.75rem; }
        .mt{ margin-top:1rem } .mt2{ margin-top:1.25rem } .mt3{ margin-top:1.75rem }
        .center{ display:grid; place-items:center; min-height: min(92vh, 900px); padding: 24px; }
        .w-full{ width:100% }

        /* checkbox */
        .checkbox{
            width:18px; height:18px; appearance:none; border-radius:6px;
            border:1px solid rgba(255,255,255,.35); background:rgba(255,255,255,.06);
            display:inline-grid; place-items:center; transition:.2s ease;
        }
        .checkbox:checked{
            background:linear-gradient(135deg, var(--p3), #ffffffbb);
            border-color:#ffffff;
            box-shadow:0 0 0 4px rgba(185,114,255,.25), var(--glow);
        }
        .checkbox:checked::after{
            content:"✓"; color:#2b0b4e; font-weight:900; font-size:.8rem; line-height:1;
        }

        /* “or” separator */
        .sep{ color:#e9d7ff; opacity:.65; display:flex; align-items:center; gap:.75rem; }
        .sep::before,.sep::after{ content:""; height:1px; flex:1; background:linear-gradient(90deg,transparent, rgba(255,255,255,.35), transparent); }
    </style>

    <!-- floating particles -->
    <div class="particles" aria-hidden="true" id="particles"></div>

    <!-- status -->
    <div class="center">
        <div class="auth-card">
            <div class="header-aurora"></div>

            <div class="row" style="justify-content:center; margin-bottom:.75rem;">
                <div style="width:62px;height:62px;border-radius:16px;background:linear-gradient(135deg,#ffffffcc,#e8d0ff);display:grid;place-items:center;box-shadow:0 8px 30px rgba(255,255,255,.35);">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 2l3.2 6.5L22 9l-5 4.9L18.4 21 12 17.7 5.6 21 7 13.9 2 9l6.8-.5L12 2z" fill="url(#g)"/>
                        <defs><linearGradient id="g" x1="0" x2="24" y1="0" y2="24"><stop stop-color="#6a11cb"/><stop offset="1" stop-color="#8e2de2"/></linearGradient></defs>
                    </svg>
                </div>
            </div>
            

            <h1 class="title" style="text-align:center; font-size:clamp(1.4rem,2.6vw,1.9rem);">Welcome back</h1>
            <p class="subtitle" style="text-align:center; margin-top:.35rem;">Sign in to continue your cosmic journey</p>

            <div class="mt">
                <x-auth-session-status class="mb-4 hint" :status="session('status')" />
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt2">
                @csrf

                <!-- Email -->
                <div class="mt">
                    <x-input-label for="email" class="label" :value="__('Email')" />
                    <x-text-input id="email"
                        class="input-like w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="hint mt" />
                </div>

                <!-- Password -->
                <div class="mt2">
                    <x-input-label for="password" class="label" :value="__('Password')" />
                    <x-text-input id="password"
                        class="input-like w-full"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="hint mt" />
                </div>

                <!-- Remember + Forgot -->
                <div class="row mt2" style="align-items:center;">
                    <label for="remember_me" class="row" style="gap:.55rem;">
                        <input id="remember_me" type="checkbox" class="checkbox" name="remember">
                        <span class="hint">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="link" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <div class="row mt3" style="justify-content:flex-end;">
                    <button class="btn-primary" type="submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h11M12 5l7 7-7 7" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ __('Log in') }}
                    </button>
                </div>

                <!-- Optional: register link -->
                @if (Route::has('register'))
                    <div class="sep mt3">{{ __('or') }}</div>
                    <div class="row mt2" style="justify-content:center;">
                        <a href="{{ route('register') }}" class="link" style="text-decoration:underline;">
                            {{ __('Create a new account') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        // generate a subtle starfield
        (function(){
            const wrap = document.getElementById('particles');
            const N = 40;
            const vw = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            const vh = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

            for(let i=0;i<N;i++){
                const s = document.createElement('span');
                s.className = 'particle';
                const size = Math.random()*4 + 2;
                s.style.width = size+'px';
                s.style.height = size+'px';
                s.style.left = Math.random()*vw+'px';
                s.style.animationDuration = (8 + Math.random()*10)+'s';
                s.style.animationDelay = (-Math.random()*10)+'s';
                wrap.appendChild(s);
            }
        })();
    </script>
</x-guest-layout>
