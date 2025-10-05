<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-route text-accent fs-3"></i>
            <h2 class="stage-title mb-0">Learning Paths</h2>
        </div>
    </x-slot>

    <div class="game-viewport py-6">
        <div class="content-wrap">
            
            <!-- Intro -->
            <div class="card-surface mb-5">
                <div class="stage-tip mb-3">
                    <span class="tip-icon">💡</span>
                    <p>
                        New to Python? Don't worry! This roadmap is designed for 
                        <strong>non-CS students</strong> and beginners. 
                        Step by step, you'll unlock knowledge, practice challenges, 
                        and build confidence as you go.
                    </p>
                </div>
                <p class="muted mb-0">
                    👉 Start with the basics, test your knowledge with quizzes, 
                    and progress toward building your own Python projects.  
                    Our platform <strong>{{ config('app.name', 'PyLearn') }}</strong> 
                    is the best starting point for your coding adventure.
                </p>
            </div>

            <!-- Roadmap Blocks -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="stage-card p-4 h-100">
                        <h4 class="fw-bold text-accent mb-2"><i class="fas fa-seedling me-2"></i> Stage 1: Fundamentals</h4>
                        <p class="text-muted small mb-3">Build your foundation.</p>
                        <ul class="list-disc ms-3 text-ink small">
                            <li>Introduction to Python & setup</li>
                            <li>Variables, data types, operators</li>
                            <li>Basic input/output</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stage-card p-4 h-100">
                        <h4 class="fw-bold text-accent mb-2"><i class="fas fa-code-branch me-2"></i> Stage 2: Control Flow</h4>
                        <p class="text-muted small mb-3">Learn to think logically.</p>
                        <ul class="list-disc ms-3 text-ink small">
                            <li>Conditions (if/else)</li>
                            <li>Loops (for/while)</li>
                            <li>Problem-solving challenges</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stage-card p-4 h-100">
                        <h4 class="fw-bold text-accent mb-2"><i class="fas fa-cogs me-2"></i> Stage 3: Functions</h4>
                        <p class="text-muted small mb-3">Organize your code.</p>
                        <ul class="list-disc ms-3 text-ink small">
                            <li>Defining & calling functions</li>
                            <li>Parameters & return values</li>
                            <li>Reusability tips</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="stage-card p-4 h-100">
                        <h4 class="fw-bold text-accent mb-2"><i class="fas fa-database me-2"></i> Stage 4: Data Structures</h4>
                        <p class="text-muted small mb-3">Work with collections of data.</p>
                        <ul class="list-disc ms-3 text-ink small">
                            <li>Lists, tuples, dictionaries</li>
                            <li>Basic algorithms</li>
                            <li>Mini-project: simple to-do app</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="stage-card p-4">
                        <h4 class="fw-bold text-accent mb-2"><i class="fas fa-rocket me-2"></i> Stage 5: Projects & Beyond</h4>
                        <p class="text-muted small mb-3">Put your knowledge into action.</p>
                        <ul class="list-disc ms-3 text-ink small">
                            <li>Build small real-world projects</li>
                            <li>Take post-assessment quizzes</li>
                            <li>Earn stars and unlock your final rank 🚀</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-5">
                <a href="{{ route('dashboard') }}" class="btn btn-game px-5 py-3">
                    <i class="fas fa-play me-2"></i> Start Learning Now
                </a>
            </div>

        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --primary-purple: #7c3aed;
            --deep-purple: #6d28d9;
            --light-purple: #a78bfa;
            --ultra-light-purple: #ede9fe;
            --pure-white: #ffffff;
            --off-white: #fafafa;
            --text-dark: #4c1d95;
            --text-muted: #8b5cf6;
        }
        
        .game-viewport {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #ddd6fe 100%);
            min-height: calc(100vh - 120px);
        }
        
        .stage-title {
            color: var(--deep-purple);
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }
        
        .text-accent {
            color: var(--primary-purple) !important;
        }
        
        .card-surface {
            background: var(--pure-white);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 8px 32px rgba(124, 58, 237, 0.12);
            border: 2px solid var(--ultra-light-purple);
        }
        
        .stage-card {
            background: var(--pure-white);
            border: 2px solid var(--ultra-light-purple);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stage-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-purple), var(--light-purple));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .stage-card:hover::before {
            transform: scaleX(1);
        }
        
        .stage-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.25);
            border-color: var(--light-purple);
        }
        
        .stage-card h4 {
            color: var(--deep-purple);
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .stage-card .text-muted {
            color: var(--text-muted) !important;
            font-weight: 500;
        }
        
        .stage-tip {
            background: linear-gradient(135deg, var(--primary-purple), var(--deep-purple));
            color: var(--pure-white);
            font-size: 0.95rem;
            padding: 18px 20px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(124, 58, 237, 0.3);
            font-weight: 500;
            line-height: 1.6;
        }
        
        .stage-tip strong {
            font-weight: 700;
            text-decoration: underline;
            text-decoration-color: rgba(255, 255, 255, 0.4);
            text-underline-offset: 2px;
        }
        
        .tip-icon {
            margin-right: 8px;
            font-size: 1.2rem;
        }
        
        .muted {
            color: var(--text-dark);
            font-weight: 500;
            line-height: 1.7;
        }
        
        .text-ink {
            color: var(--text-dark);
        }
        
        .list-disc {
            list-style-type: none;
            padding-left: 0;
        }
        
        .list-disc li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .list-disc li::before {
            content: '●';
            position: absolute;
            left: 8px;
            color: var(--primary-purple);
            font-size: 1.2rem;
        }
        
        .btn-game {
            background: linear-gradient(135deg, var(--primary-purple), var(--deep-purple));
            color: var(--pure-white);
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 12px 28px rgba(124, 58, 237, 0.35);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-game:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(124, 58, 237, 0.45);
            background: linear-gradient(135deg, var(--deep-purple), var(--primary-purple));
            color: var(--pure-white);
        }
        
        .btn-game:active {
            transform: translateY(-1px);
        }
        
        .fas {
            color: var(--primary-purple);
        }
    </style>
</x-app-layout>