<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-white tracking-wide glow-text">
                    {{ __('Player Profile') }}
                </h2>
                <p class="text-sm text-purple-200 mt-1">
                    {{ __('Manage your quest identity, access level, and account safety.') }}
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Styles --}}
    <style>
        [x-cloak]{display:none!important;}

        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 30% 30%, #4b0082, #2b0b4e, #130022);
            color: #fff;
        }

        /* Cosmic glow */
        .glow-text {
            text-shadow: 0 0 12px rgba(185, 114, 255, .8), 0 0 24px rgba(142, 45, 226, .4);
        }

        .profile-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(185, 114, 255, 0.3);
            border-radius: 1.5rem;
            backdrop-filter: blur(18px);
            box-shadow: 0 0 25px rgba(142, 45, 226, 0.25);
            padding: 2rem;
            transition: 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0 35px rgba(185, 114, 255, 0.4);
        }

        /* Tabs */
        .tab-list {
            display: flex;
            justify-content: center;
            background: linear-gradient(90deg, rgba(142,45,226,.1), rgba(106,17,203,.15));
            border-radius: 1rem;
            padding: .4rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(185,114,255,0.25);
        }

        .tab-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-weight: 700;
            color: #e8d8ff;
            transition: 0.3s ease;
            background: transparent;
        }

        .tab-btn:hover {
            background: rgba(185, 114, 255, 0.1);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #8e2de2, #6a11cb);
            box-shadow: 0 0 16px rgba(142,45,226,0.6);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Input / Form style */
        input, select {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(185,114,255,0.3);
            color: #fff;
            border-radius: 12px;
            padding: 10px 14px;
            width: 100%;
            transition: .3s ease;
        }

        input:focus {
            border-color: #b972ff;
            box-shadow: 0 0 10px rgba(185,114,255,0.6);
            outline: none;
        }

        label {
            color: #dcd3ff;
            font-weight: 600;
        }

        /* Buttons */
        .btn-purple {
            background: linear-gradient(135deg, #8e2de2, #6a11cb);
            color: #fff;
            font-weight: 700;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: 0.3s ease;
            box-shadow: 0 0 20px rgba(142,45,226,0.4);
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(185,114,255,0.6);
        }

        .danger-zone {
            border: 1px solid rgba(255, 50, 50, 0.4);
            background: rgba(255, 0, 0, 0.05);
        }

        /* Panel fade animation */
        [x-show] { transition: all .4s ease; }
    </style>

    {{-- Main section --}}
    <div class="py-10">
        <div class="profile-wrapper" 
             x-data="{ tab: (new URLSearchParams(window.location.search)).get('tab') || 'profile' }"
             x-init="$watch('tab', t => history.replaceState(null, '', `?tab=${t}`))">

            <div class="profile-card">

                {{-- Tabs --}}
                <div class="tab-list">
                    <button class="tab-btn" 
                        @click="tab='profile'" 
                        :class="{ 'active': tab==='profile' }">
                        <i class="fas fa-user-astronaut me-2"></i> Profile
                    </button>
                    <button class="tab-btn"
                        @click="tab='password'" 
                        :class="{ 'active': tab==='password' }">
                        <i class="fas fa-lock me-2"></i> Password
                    </button>
                    <button class="tab-btn"
                        @click="tab='danger'" 
                        :class="{ 'active': tab==='danger' }">
                        <i class="fas fa-skull-crossbones me-2"></i> Danger Zone
                    </button>
                </div>

                {{-- Panels --}}
                <div class="space-y-6">
                    <div x-show="tab==='profile'" x-cloak>
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div x-show="tab==='password'" x-cloak>
                        @include('profile.partials.update-password-form')
                    </div>

                    <div x-show="tab==='danger'" x-cloak>
                        <div class="danger-zone p-6 rounded-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</x-app-layout>
