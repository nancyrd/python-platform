
<x-guest-layout>
    <style>
        :root{
            --p1:#6a11cb; --p2:#8e2de2; --p3:#b972ff; --glow:0 10px 40px rgba(142,45,226,.45);
        }

        /* Page background */
        body{
            min-height:100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;

            background:
                radial-gradient(1200px 800px at 10% -10%, rgba(185,114,255,.25), transparent 60%),
                radial-gradient(900px 900px at 120% 20%, rgba(106,17,203,.25), transparent 60%),
                linear-gradient(135deg, var(--p1) 0%, var(--p2) 100%);
            overflow-x:hidden;
        }

        /* particles */
        .particles{ position:fixed; inset:0; pointer-events:none; z-index:0; }
        .particle{
            position:absolute; width:6px; height:6px; border-radius:50%;
            background:rgba(255,255,255,.7); box-shadow:0 0 12px rgba(255,255,255,.9);
            animation:float 10s linear infinite;
        }
        @keyframes float{
            0%{ transform: translateY(110vh) translateX(0) scale(.6); opacity:0;}
            10%{opacity:1}
            100%{ transform: translateY(-10vh) translateX(20vw) scale(1); opacity:0;}
        }

        /* Card */
        .auth-card{
            position:relative; z-index:1; width:100%; max-width:560px; margin-inline:auto;
            padding: clamp(1.25rem, 2vw, 1.75rem);
            border-radius:22px; background:linear-gradient(180deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(16px); border:1px solid rgba(255,255,255,.18); box-shadow:var(--glow);
        }
        .header-aurora{ position:absolute; inset:-2px; border-radius:24px;
            background:conic-gradient(from 140deg, rgba(255,255,255,.12), rgba(255,255,255,0), rgba(255,255,255,.12));
            filter:blur(18px); opacity:.35; z-index:-1;
        }

        /* Titles */
        .title{
            font-weight:900; letter-spacing:.4px;
            background:linear-gradient(90deg,#fff, #ffefff 30%, #e6ccff 70%);
            -webkit-background-clip:text; background-clip:text; color:transparent;
            text-shadow:0 0 24px rgba(255,255,255,.15);
        }
        .subtitle{ color:#f1e6ff; opacity:.9; font-size:.95rem }

        /* Inputs (Breeze components inherit) */
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
        .label{ color:#f7ecff; font-weight:700; font-size:.9rem; margin-bottom:.45rem; display:block; }
        .hint,.link{ color:#e9d7ff; opacity:.85; font-size:.85rem }

        /* Button */
        .btn-primary{
            display:inline-flex; align-items:center; gap:.6rem;
            background:linear-gradient(135deg, var(--p2), var(--p1));
            color:white; border:none; border-radius:14px;
            padding:12px 18px; font-weight:800; letter-spacing:.4px; box-shadow:var(--glow);
            transition: transform .18s ease, filter .18s ease;
        }
        .btn-primary:hover{ transform: translateY(-2px); filter:brightness(1.08); }

        /* Utils */
        .row{ display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap; }
        .mt{ margin-top:1rem } .mt2{ margin-top:1.25rem } .mt3{ margin-top:1.75rem }
        .center{ display:grid; place-items:center; min-height:min(92vh, 1000px); padding:24px; }
        .w-full{ width:100% }

        /* password strength */
        .strength{ height:8px; border-radius:10px; background:rgba(255,255,255,.18); overflow:hidden }
        .strength > span{ display:block; height:100%; width:0%; transition:width .35s ease;
            background:linear-gradient(90deg, #ff6b6b, #ffd166, #06d6a0);
        }
    </style>

    <div class="particles" id="particles" aria-hidden="true"></div>

    <div class="center">
        <div class="auth-card">
            <div class="header-aurora"></div>

            <div class="row" style="justify-content:center; margin-bottom:.75rem;">
                <div style="width:68px;height:68px;border-radius:16px;background:linear-gradient(135deg,#ffffffcc,#e8d0ff);display:grid;place-items:center;box-shadow:0 8px 30px rgba(255,255,255,.35);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 2l3.2 6.5L22 9l-5 4.9L18.4 21 12 17.7 5.6 21 7 13.9 2 9l6.8-.5L12 2z" fill="url(#g)"/>
                        <defs><linearGradient id="g" x1="0" x2="24" y1="0" y2="24"><stop stop-color="#6a11cb"/><stop offset="1" stop-color="#8e2de2"/></linearGradient></defs>
                    </svg>
                </div>
            </div>

            <h1 class="title" style="text-align:center; font-size:clamp(1.4rem,2.6vw,1.9rem);">Create your account</h1>
            <p class="subtitle" style="text-align:center; margin-top:.35rem;">Join the adventure — it’s fast and free</p>

            <form method="POST" action="{{ route('register') }}" class="mt2">
                @csrf

                <!-- Name -->
                <div class="mt">
                    <x-input-label for="name" class="label" :value="__('Name')" />
                    <x-text-input id="name" class="input-like w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="hint mt" />
                </div>

                <!-- Email -->
                <div class="mt2">
                    <x-input-label for="email" class="label" :value="__('Email')" />
                    <x-text-input id="email" class="input-like w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="hint mt" />
                </div>

                <!-- Password -->
                <div class="mt2">
                    <x-input-label for="password" class="label" :value="__('Password')" />
                    <x-text-input id="password" class="input-like w-full"
                        type="password" name="password" required autocomplete="new-password"
                        oninput="window.updateStrength(this.value)" />
                    <div class="strength mt"><span id="strengthBar"></span></div>
                    <x-input-error :messages="$errors->get('password')" class="hint mt" />
                </div>

                <!-- Confirm -->
                <div class="mt2">
                    <x-input-label for="password_confirmation" class="label" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="input-like w-full"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="hint mt" />
                </div>

                <!-- Actions -->
                <div class="row mt3">
                    <a class="link" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
                    <button class="btn-primary" type="submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // particles
        (function(){
            const wrap = document.getElementById('particles');
            const N = 45;
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

        // password strength
        window.updateStrength = function(v){
            const bar = document.getElementById('strengthBar');
            let score = 0;
            if(v.length >= 8) score++;
            if(/[A-Z]/.test(v)) score++;
            if(/[a-z]/.test(v)) score++;
            if(/[0-9]/.test(v)) score++;
            if(/[^A-Za-z0-9]/.test(v)) score++;

            const pct = [0,20,40,60,80,100][score];
            bar.style.width = pct + '%';
        }
    </script>
</x-guest-layout>

