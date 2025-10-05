<x-app-layout>
    <x-slot name="header">
        <div class="user-header-container">
            <div class="flex items-center">
                <div class="user-icon-wrapper">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="ml-4">
                    <h2 class="user-title">{{ $user->name }}</h2>
                    <p class="user-subtitle">Student Performance Overview</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="user-container">
        <!-- Quiz Attempts Section -->
        <div class="data-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h3 class="section-title">Quiz Attempts</h3>
                    <p class="section-subtitle">All quiz attempt records</p>
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Level</th>
                            <th>Kind</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Finished</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizAttempts as $qa)
                            <tr>
                                <td class="font-medium">{{ $qa->stage->title ?? '-' }}</td>
                                <td>{{ $qa->level->title ?? '-' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($qa->kind) }}</span></td>
                                <td><span class="score-badge">{{ $qa->score }}%</span></td>
                                <td>
                                    @if($qa->passed)
                                        <span class="badge badge-success">Passed</span>
                                    @else
                                        <span class="badge badge-error">Failed</span>
                                    @endif
                                </td>
                                <td class="text-sm text-muted">{{ $qa->started_at ? $qa->started_at->format('M d, Y H:i') : '-' }}</td>
                                <td class="text-sm text-muted">{{ $qa->finished_at ? $qa->finished_at->format('M d, Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No quiz attempts yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Level Progress Section -->
        <div class="data-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h3 class="section-title">Level Progress</h3>
                    <p class="section-subtitle">Performance by level</p>
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Level</th>
                            <th>Best Score</th>
                            <th>Stars</th>
                            <th>Attempts</th>
                            <th>Status</th>
                            <th>Last Attempt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($levelProgress as $lp)
                            <tr>
                                <td class="font-medium">{{ $lp->stage->title ?? '-' }}</td>
                                <td>{{ $lp->level->title ?? '-' }}</td>
                                <td><span class="score-badge">{{ $lp->best_score }}%</span></td>
                                <td>
                                    <div class="stars">
                                        @for($i = 0; $i < $lp->stars; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($i = $lp->stars; $i < 3; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td><span class="badge badge-neutral">{{ $lp->attempts_count }}</span></td>
                                <td>
                                    @if($lp->passed)
                                        <span class="badge badge-success">Passed</span>
                                    @else
                                        <span class="badge badge-warning">In Progress</span>
                                    @endif
                                </td>
                                <td class="text-sm text-muted">{{ $lp->last_attempt_at ? $lp->last_attempt_at->format('M d, Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No level progress yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stage Progress Section -->
        <div class="data-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="section-title">Stage Progress</h3>
                    <p class="section-subtitle">Overall stage completion status</p>
                </div>
            </div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Pre Assessment</th>
                            <th>Post Assessment</th>
                            <th>Unlocked Level</th>
                            <th>Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stageProgress as $sp)
                            <tr>
                                <td class="font-medium">{{ $sp->stage->title ?? '-' }}</td>
                                <td>
                                    @if($sp->pre_completed_at)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $sp->pre_completed_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="badge badge-neutral">Not Completed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sp->post_completed_at)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $sp->post_completed_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="badge badge-neutral">Not Completed</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-info">Level {{ $sp->unlocked_to_level }}</span></td>
                                <td class="text-sm text-muted">{{ $sp->last_activity_at ? $sp->last_activity_at->format('M d, Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No stage progress yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --primary-purple: #9333ea;
            --deep-purple: #6b21a8;
            --light-purple: #a78bfa;
            --ultra-light-purple: #ede9fe;
            --pure-white: #ffffff;
            --off-white: #fafafa;
             --purple-900: #581c87;
            --text-muted: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
               --purple-600: #9333ea;
            --neutral: #6b7280;
        }
        
        .user-header-container {
            background: linear-gradient(135deg, var(--primary-purple), var(--deep-purple));
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .user-header-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }
        
        .user-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        
        .user-icon-wrapper i {
            font-size: 2rem;
            color: white;
        }
        
        .user-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .user-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
            font-weight: 500;
        }
        
        .user-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }
        
        .data-section {
            background: var(--pure-white);
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.15);
            border: 2px solid var(--ultra-light-purple);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .section-header {
            background: var(--ultra-light-purple);
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--light-purple);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .section-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--primary-purple), var(--deep-purple));
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        
        .section-icon i {
            color: white;
            font-size: 1.25rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--deep-purple);
            margin: 0;
        }
        
        .section-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin: 0.25rem 0 0 0;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: var(--off-white);
        }
        
        .data-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--deep-purple);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--ultra-light-purple);
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s ease;
        }
        
        .data-table tbody tr:hover {
            background: var(--ultra-light-purple);
        }
        
        .data-table td {
            padding: 1rem 1.5rem;
            color: var(--text-dark);
            font-size: 0.95rem;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-error {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-neutral {
            background: #f3f4f6;
            color: #374151;
        }
        
        .score-badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            background: linear-gradient(135deg, var(--primary-purple), var(--light-purple));
            color: white;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.875rem;
        }
        
        .stars {
            display: flex;
            gap: 0.25rem;
        }
        
        .stars i {
            color: #fbbf24;
            font-size: 1rem;
        }
        
        .stars .far {
            color: #d1d5db;
        }
        
        .font-medium {
            font-weight: 600;
            color: var(--deep-purple);
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
        
        .text-muted {
            color: var(--neutral);
        }
        
        .text-center {
            text-align: center;
        }
        
        .empty-state {
            padding: 3rem 2rem !important;
            color: var(--neutral);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--light-purple);
            opacity: 0.5;
            margin-bottom: 1rem;
            display: block;
        }
        
        .empty-state p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .user-container {
                padding: 1rem;
            }
            
            .section-header {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            
            .data-table th,
            .data-table td {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            .user-title {
                font-size: 1.5rem;
            }
        }
    </style>
</x-app-layout>