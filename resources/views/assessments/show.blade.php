<x-app-layout>
    <x-slot name="header">
        <div class="cosmic-header-assessment">
            <div class="d-flex align-items-center">
                <i class="fas fa-brain me-3 fs-2 text-electric-blue"></i>
                <div>
                    <h2 class="fw-bold text-white mb-1 fs-3">
                        🧠 {{ strtoupper($assessment->type) }} COSMIC CHALLENGE
                    </h2>
                    <p class="text-light opacity-75 mb-0">
                        <i class="fas fa-rocket me-2"></i>
                        Stage: {{ $assessment->stage->title }} — Test your cosmic knowledge!
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        :root {
            --cosmic-purple: #6A1B9A;
            --neon-purple: #8E24AA;
            --deep-purple: #4A148C;
            --electric-blue: #00E5FF;
            --neon-pink: #FF006E;
            --neon-green: #00FF7F;
            --neon-yellow: #FFD700;
            --dark-space: #0A0A0F;
            --space-blue: #1A1A3E;
        }

        body {
            background: linear-gradient(45deg, var(--deep-purple) 0%, var(--cosmic-purple) 30%, var(--space-blue) 70%, var(--dark-space) 100%);
            min-height: 100vh;
            font-family: 'Orbitron', 'Arial', sans-serif;
            overflow-x: hidden;
        }

        .cosmic-header-assessment {
            background: linear-gradient(135deg, rgba(106, 27, 154, 0.9), rgba(74, 20, 140, 0.9));
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--electric-blue);
            box-shadow: 0 4px 30px rgba(0, 229, 255, 0.3);
            padding: 2rem;
        }

        .text-electric-blue {
            color: var(--electric-blue);
            filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.8));
        }

        .assessment-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 1rem;
            position: relative;
        }

        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--electric-blue);
            border-radius: 50%;
            animation: float-particle 8s ease-in-out infinite;
            box-shadow: 0 0 10px var(--electric-blue);
        }

        @keyframes float-particle {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 0.8; }
        }

        .assessment-form {
            position: relative;
            z-index: 2;
        }

        .question-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 2px solid rgba(0, 229, 255, 0.3);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255,255,255,0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .question-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--neon-pink), var(--electric-blue), var(--neon-green));
        }

        .question-card:hover {
            transform: translateY(-5px);
            border-color: rgba(0, 229, 255, 0.5);
            box-shadow: 
                0 30px 60px rgba(0, 0, 0, 0.4),
                inset 0 2px 0 rgba(255,255,255,0.2);
        }

        .question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--neon-purple), var(--electric-blue));
            border-radius: 50%;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.5);
        }

        .question-text {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .option-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .option-label {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(0, 229, 255, 0.2);
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .option-label:hover {
            transform: translateX(10px);
            border-color: rgba(0, 229, 255, 0.5);
            background: rgba(0, 229, 255, 0.1);
            box-shadow: 0 10px 25px rgba(0, 229, 255, 0.2);
        }

        .option-radio {
            width: 20px;
            height: 20px;
            margin-right: 1rem;
            accent-color: var(--electric-blue);
            transform: scale(1.2);
        }

        .option-text {
            color: white;
            font-weight: 500;
            flex-grow: 1;
        }

        .option-label input[type="radio"]:checked + .option-text {
            color: var(--electric-blue);
            font-weight: bold;
        }

        .option-label:has(input[type="radio"]:checked) {
            border-color: var(--electric-blue);
            background: rgba(0, 229, 255, 0.15);
            box-shadow: 0 0 30px rgba(0, 229, 255, 0.4);
        }

        .submit-container {
            text-align: center;
            margin-top: 3rem;
        }

        .btn-cosmic-submit {
            background: linear-gradient(135deg, var(--neon-purple), var(--electric-blue));
            border: none;
            color: white;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 1.5rem 3rem;
            border-radius: 50px;
            font-size: 1.1rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 15px 35px rgba(0, 229, 255, 0.4),
                inset 0 1px 0 rgba(255,255,255,0.2);
            cursor: pointer;
        }

        .btn-cosmic-submit:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 
                0 25px 50px rgba(0, 229, 255, 0.6),
                inset 0 2px 0 rgba(255,255,255,0.3);
            color: white;
        }

        .btn-cosmic-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s ease;
        }

        .btn-cosmic-submit:hover::before {
            left: 100%;
        }

        .empty-state {
            background: rgba(255, 193, 7, 0.1);
            border: 2px solid rgba(255, 193, 7, 0.3);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            color: var(--neon-yellow);
            backdrop-filter: blur(10px);
        }

        .error-message {
            background: rgba(255, 0, 110, 0.1);
            border: 2px solid rgba(255, 0, 110, 0.3);
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            color: var(--neon-pink);
            font-size: 0.9rem;
        }

        .no-options {
            color: rgba(255, 255, 255, 0.5);
            font-style: italic;
            text-align: center;
            padding: 1rem;
        }

        /* Particle positions */
        .particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 30%; top: 60%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 60%; top: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 80%; top: 70%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 20%; top: 80%; animation-delay: 4s; }

        @media (max-width: 768px) {
            .assessment-container {
                padding: 2rem 0.5rem;
            }
            
            .question-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .question-text {
                font-size: 1rem;
            }
            
            .option-label {
                padding: 0.75rem 1rem;
            }
            
            .btn-cosmic-submit {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
            
            .cosmic-header-assessment {
                padding: 1rem;
            }
        }
    </style>

    <!-- Floating Particles Background -->
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="assessment-container">
        <form method="POST" action="{{ route('assessments.submit', $assessment) }}" class="assessment-form">
            @csrf

            {{-- If questions is somehow a JSON string, decode it defensively --}}
            @php
                $qs = $assessment->questions;
                if (!is_array($qs)) {
                    $qs = json_decode($qs ?? '[]', true) ?: [];
                }
            @endphp

            @if(empty($qs))
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle fs-1 mb-3"></i>
                    <h3 class="fw-bold mb-2">No Cosmic Challenges Found</h3>
                    <p class="mb-0">This assessment dimension appears to be empty. Please contact mission control!</p>
                </div>
            @else
                @foreach($qs as $i => $q)
                    @php
                        $opts = is_array($q['options'] ?? null) ? $q['options'] : [];
                        $oldValue = old("answers.$i");
                    @endphp

                    <div class="question-card">
                        <div class="d-flex align-items-start mb-3">
                            <div class="question-number">{{ $i + 1 }}</div>
                            <div class="question-text">
                                {{ $q['prompt'] ?? 'Cosmic Question' }}
                            </div>
                        </div>

                        <div class="option-container">
                            @forelse($opts as $opt)
                                <label class="option-label">
                                    <input
                                        type="radio"
                                        name="answers[{{ $i }}]"
                                        value="{{ $opt }}"
                                        class="option-radio"
                                        {{ $oldValue === $opt ? 'checked' : '' }}
                                        required
                                    >
                                    <span class="option-text">{{ $opt }}</span>
                                </label>
                            @empty
                                <div class="no-options">
                                    <i class="fas fa-question-circle me-2"></i>
                                    No cosmic options provided for this challenge.
                                </div>
                            @endforelse
                        </div>

                        @error("answers.$i")
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach

                <div class="submit-container">
                    <button type="submit" class="btn-cosmic-submit">
                        <i class="fas fa-rocket me-2"></i>
                        Launch Submission
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            @endif
        </form>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</x-app-layout>