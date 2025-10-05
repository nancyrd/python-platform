<x-app-layout>
    <x-slot name="header">
        <!-- Font Awesome (latest 6.x) -->
<link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
      integrity="sha512-zNf1+fKp6u6A7+EpnXprKKe2Kmx0pN0KjcgH2jD/AXb4z4V2fITwAtybEM8Lklh6w76S+E6dM8h2xC8E+Wr9Q==" 
      crossorigin="anonymous" referrerpolicy="no-referrer" />

        <div class="admin-header-container">
            <div class="flex items-center">
                <div class="admin-icon-wrapper">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="ml-4">
                    <h2 class="admin-title">Administration Panel</h2>
                    <p class="admin-subtitle">System Management & Control</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="admin-dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-card">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1 class="welcome-title">Welcome Back, Administrator</h1>
                    <p class="welcome-description">
                        Manage your educational platform with comprehensive tools for content creation, 
                        stage management, and system oversight.
                    </p>
                </div>
                <div class="welcome-graphic">
                    <div class="floating-elements">
                        <div class="element element-1"></div>
                        <div class="element element-2"></div>
                        <div class="element element-3"></div>
                    </div>
                </div>
            </div>
        </div>
<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $stagesCount }}</h3>
            <p class="stat-label">Active Stages</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon indigo">
            <i class="fas fa-puzzle-piece"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $levelsCount }}</h3>
            <p class="stat-label">Total Levels</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon violet">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ number_format($usersCount) }}</h3>
            <p class="stat-label">Active Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amethyst">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $completionRate }}%</h3>
            <p class="stat-label">Completion Rate</p>
        </div>
    </div>
</div>

        <!-- Management Tools -->
        <div class="management-section">
            <div class="section-header">
                <h2 class="section-title">Management Tools</h2>
                <p class="section-description">Access core administrative functions</p>
            </div>
            
            <div class="tools-grid">
                <a href="{{ route('admin.stages.index') }}" class="tool-card primary">
                    <div class="tool-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="tool-content">
                        <h3 class="tool-title">Stage Management</h3>
                        <p class="tool-description">Create, edit, and organize learning stages</p>
                        <div class="tool-action">
                            <span>Manage Stages</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
                     <a href="{{ route('admin.users.index') }}" class="tool-card primary">
                    <div class="tool-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="tool-content">
                        <h3 class="tool-title">User Management</h3>
                        <p class="tool-description">Monitor and manage user accounts</p>
                        <div class="tool-action">
                            <span>Manage Users</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
                

                <div class="tool-card secondary">
                    <div class="tool-icon">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <div class="tool-content">
                        <h3 class="tool-title">Level Designer</h3>
                        <p class="tool-description">Build interactive learning experiences</p>
                        <div class="tool-action">
                            <span>Coming Soon</span>
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

             
                <div class="tool-card quaternary">
                    <div class="tool-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="tool-content">
                        <h3 class="tool-title">Analytics</h3>
                        <p class="tool-description">View performance metrics and insights</p>
                        <div class="tool-action">
                            <span>Coming Soon</span>
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        :root {
            --purple-50: #faf5ff;
            --purple-100: #f3e8ff;
            --purple-200: #e9d5ff;
            --purple-300: #d8b4fe;
            --purple-400: #c084fc;
            --purple-500: #a855f7;
            --purple-600: #9333ea;
            --purple-700: #7c3aed;
            --purple-800: #6b21a8;
            --purple-900: #581c87;
            
            --indigo-500: #6366f1;
            --violet-500: #8b5cf6;
            --amethyst-500: #a78bfa;
            
            --gradient-primary: linear-gradient(135deg, var(--purple-600) 0%, var(--purple-800) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--indigo-500) 0%, var(--purple-600) 100%);
            --gradient-tertiary: linear-gradient(135deg, var(--violet-500) 0%, var(--purple-700) 100%);
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .admin-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .admin-header-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }

        .admin-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .admin-icon-wrapper i {
            font-size: 1.5rem;
            color: #fbbf24;
        }

        .admin-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .admin-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
            font-weight: 400;
        }

        .admin-dashboard-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }

        .welcome-card {
            background: white;
            border-radius: 1.5rem;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(139, 92, 246, 0.1);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .welcome-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0 0 1rem 0;
            letter-spacing: -0.025em;
        }

        .welcome-description {
            font-size: 1.125rem;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            max-width: 600px;
            font-weight: 400;
        }

        .welcome-graphic {
            position: relative;
            width: 200px;
            height: 150px;
        }

        .floating-elements {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .element {
            position: absolute;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .element-1 {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            top: 20px;
            left: 20px;
            animation-delay: 0s;
        }

        .element-2 {
            width: 40px;
            height: 40px;
            background: var(--gradient-secondary);
            top: 60px;
            right: 30px;
            animation-delay: 2s;
        }

        .element-3 {
            width: 30px;
            height: 30px;
            background: var(--gradient-tertiary);
            bottom: 20px;
            left: 50px;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(139, 92, 246, 0.1);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
        }

        .stat-icon.purple { background: var(--gradient-primary); }
        .stat-icon.indigo { background: linear-gradient(135deg, var(--indigo-500) 0%, var(--purple-600) 100%); }
        .stat-icon.violet { background: linear-gradient(135deg, var(--violet-500) 0%, var(--purple-700) 100%); }
        .stat-icon.amethyst { background: linear-gradient(135deg, var(--amethyst-500) 0%, var(--purple-800) 100%); }

        .stat-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0 0 0.25rem 0;
        }

        .stat-label {
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }

        .management-section, .quick-actions-section {
            margin-bottom: 3rem;
        }

        .section-header {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.025em;
        }

        .section-description {
            color: #64748b;
            margin: 0;
            font-size: 1.125rem;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .tool-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(139, 92, 246, 0.1);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .tool-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .tool-card.primary::before { background: var(--gradient-primary); }
        .tool-card.secondary::before { background: var(--gradient-secondary); }
        .tool-card.tertiary::before { background: var(--gradient-tertiary); }
        .tool-card.quaternary::before { background: linear-gradient(135deg, var(--amethyst-500) 0%, var(--purple-800) 100%); }

        .tool-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .tool-icon {
            width: 4rem;
            height: 4rem;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .tool-icon i {
            color: var(--purple-600);
            font-size: 1.5rem;
        }

        .tool-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--purple-900);
            margin: 0 0 0.5rem 0;
        }

        .tool-description {
            color: #64748b;
            margin: 0 0 1.5rem 0;
            line-height: 1.6;
        }

        .tool-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--purple-600);
            font-weight: 600;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            background: white;
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
        }

        .action-btn:hover {
            border-color: var(--purple-400);
            background: var(--purple-50);
            transform: translateY(-2px);
        }

        .action-btn i {
            font-size: 1.5rem;
            color: var(--purple-600);
        }

        .action-btn.create:hover { border-color: #10b981; background: #ecfdf5; }
        .action-btn.create:hover i { color: #10b981; }
        
        .action-btn.edit:hover { border-color: #f59e0b; background: #fffbeb; }
        .action-btn.edit:hover i { color: #f59e0b; }
        
        .action-btn.view:hover { border-color: #3b82f6; background: #eff6ff; }
        .action-btn.view:hover i { color: #3b82f6; }
        
        .action-btn.settings:hover { border-color: #6b7280; background: #f9fafb; }
        .action-btn.settings:hover i { color: #6b7280; }

        @media (max-width: 768px) {
            .admin-dashboard-container {
                padding: 1rem;
            }

            .welcome-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-graphic {
                margin-top: 2rem;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .tools-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</x-app-layout>