<x-app-layout>
    <x-slot name="header">
        <!-- Font Awesome (latest 6.x) -->
<link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
      integrity="sha512-zNf1+fKp6u6A7+EpnXprKKe2Kmx0pN0KjcgH2jD/AXb4z4V2fITwAtybEM8Lklh6w76S+E6dM8h2xC8E+Wr9Q==" 
      crossorigin="anonymous" referrerpolicy="no-referrer" />

        <div class="form-header-container">
            <div class="flex items-center">
                <div class="form-icon-wrapper">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="ml-4">
                    <h2 class="form-title">Add Stage</h2>
                    <p class="form-subtitle">Create a new learning stage for your curriculum</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h3 class="card-title">New Stage Information</h3>
                <p class="card-description">Enter the details for your new learning stage</p>
            </div>
            
            <form method="POST" action="{{ route('admin.stages.store') }}" class="stage-form">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Stage Slug</label>
                    <input type="text" name="slug" class="form-input" required>
                    <div class="form-help">URL-friendly identifier (e.g., "python-basics", "advanced-loops")</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Stage Title</label>
                    <input type="text" name="title" class="form-input" required>
                    <div class="form-help">Display name that learners will see</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="4" placeholder="Brief description of what learners will accomplish in this stage..."></textarea>
                    <div class="form-help">Optional description to help learners understand the stage goals</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-input" min="1" step="1">
                    <div class="form-help">Position in the stage sequence (leave empty to add at the end)</div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        <span>Save Stage</span>
                    </button>
                    <a href="{{ route('admin.stages.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Stages</span>
                    </a>
                </div>
            </form>
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
            
            --gradient-primary: linear-gradient(135deg, var(--purple-600) 0%, var(--purple-800) 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .form-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .form-header-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }

        .form-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .form-icon-wrapper i {
            font-size: 1.5rem;
            color: #10b981;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .form-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
            font-weight: 400;
        }

        .form-container {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }

        .form-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(139, 92, 246, 0.1);
            overflow: hidden;
        }

        .form-card-header {
            background: var(--purple-50);
            padding: 2rem 2rem 1.5rem 2rem;
            border-bottom: 1px solid var(--purple-200);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0 0 0.5rem 0;
        }

        .card-description {
            color: #64748b;
            margin: 0;
            font-size: 1rem;
        }

        .stage-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--purple-900);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: white;
            color: #1a202c;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--purple-400);
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
        }

        .form-input:hover,
        .form-textarea:hover {
            border-color: var(--purple-300);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .form-textarea::placeholder {
            color: #94a3b8;
            font-style: italic;
        }

        .form-help {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 2rem;
        }

        .btn-primary,
        .btn-secondary {
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            color: white;
        }

        .btn-secondary {
            background: #f8fafc;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
            }

            .form-card-header,
            .stage-form {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                justify-content: center;
            }
        }
    </style>
</x-app-layout>