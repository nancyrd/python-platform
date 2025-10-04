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
                        New to Python? Don’t worry! This roadmap is designed for 
                        <strong>non-CS students</strong> and beginners. 
                        Step by step, you’ll unlock knowledge, practice challenges, 
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
        .game-viewport {
            background: linear-gradient(45deg, var(--bg-start), var(--bg-end));
            min-height: calc(100vh - 120px);
        }
        .stage-card {
            background: var(--card);
            border: 1px solid var(--card-brd);
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.25);
            transition: .2s;
        }
        .stage-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(185,103,255,.35);
        }
        .stage-tip {
            background: linear-gradient(135deg, #6a11cb, #8e2de2);
            color: #fff;
            font-size: .9rem;
            padding: 10px 14px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(142, 45, 226, 0.3);
        }
        .tip-icon {
            margin-right: 6px;
        }
        .list-disc li::marker {
            color: var(--accent);
        }
    </style>
</x-app-layout>
