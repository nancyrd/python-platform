<x-app-layout>
    <x-slot name="header">
        <div class="levels-header-container">
            <div class="flex items-center justify-content-between">
                <div class="flex items-center">
                    <div class="levels-icon-wrapper">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="levels-title">Levels for Stage: {{ $stage->title }}</h2>
                        <p class="levels-subtitle">Manage individual learning challenges</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    
    <!-- Add Level Button Above Container -->
    <div class="add-level-button-container">
        <a href="{{ route('admin.stages.levels.create', $stage) }}" class="btn-create-level">
            <i class="fas fa-plus"></i>
            <span>Add Level</span>
        </a>
    </div>
    
    <div class="levels-container">
        @if(session('status'))
            <div class="success-alert">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif
        <div class="levels-table-container">
            <div class="table-header">
                <h3 class="table-title">Learning Levels</h3>
                <p class="table-description">Drag rows to reorder levels within this stage</p>
            </div>
            
            <div class="table-wrapper">
                <table class="levels-table">
                    <thead>
                        <tr>
                            <th class="index-column">#</th>
                            <th class="title-column">Title</th>
                            <th class="type-column">Type</th>
                            <th class="score-column">Pass Score</th>
                            <th class="instructions-column">Instructions</th>
                            <th class="created-column">Created</th>
                            <th class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortableLevels">
                        @forelse($levels as $level)
                            <tr data-id="{{ $level->id }}" class="level-row">
                                <td class="index-cell">
                                    <div class="index-badge">{{ $level->index }}</div>
                                </td>
                                <td class="title-cell">
                                    <div class="level-title">{{ $level->title }}</div>
                                </td>
                                <td class="type-cell">
                                    <span class="type-badge type-{{ $level->type }}">{{ ucfirst(str_replace('_', ' ', $level->type)) }}</span>
                                </td>
                                <td class="score-cell">
                                    <div class="score-display">{{ $level->pass_score }}%</div>
                                </td>
                                <td class="instructions-cell">
                                    <div class="instructions-text">{{ Str::limit($level->instructions, 40) }}</div>
                                </td>
                                <td class="created-cell">
                                    <div class="created-date">{{ $level->created_at->format('Y-m-d') }}</div>
                                </td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.stages.levels.edit', [$stage, $level]) }}" class="action-btn edit-btn">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.stages.levels.destroy', [$stage, $level]) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Delete this level?')">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7" class="empty-cell">
                                    <div class="empty-state">
                                        <i class="fas fa-puzzle-piece"></i>
                                        <p>No levels yet. Add one above.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Drag-and-drop ordering -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let sortable = new Sortable(document.getElementById('sortableLevels'), {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function () {
                    let order = [];
                    document.querySelectorAll('#sortableLevels tr').forEach((row, index) => {
                        order.push(row.getAttribute('data-id'));
                    });
                    fetch("{{ route('admin.levels.reorder', $stage) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ order })
                    }).then(res => res.json())
                      .then(data => {
                          console.log('Order updated', data);
                      });
                }
            });
        });
    </script>
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
            --gradient-button: linear-gradient(135deg, var(--purple-700) 0%, var(--purple-900) 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        .levels-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .levels-header-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }
        .levels-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .levels-icon-wrapper i {
            font-size: 1.5rem;
            color: #fbbf24;
        }
        .levels-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.025em;
        }
        .levels-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
            font-weight: 400;
        }
        
        /* Add Level Button Container */
        .add-level-button-container {
            display: flex;
            justify-content: flex-start;
            padding: 1rem 2rem 0;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .btn-create-level {
            background: var(--gradient-button);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            border: none;
        }
        .btn-create-level:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            color: white;
        }
        .btn-create-level i {
            color: #fbbf24;
        }
        .levels-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }
        .success-alert {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }
        .levels-table-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(139, 92, 246, 0.1);
            overflow: hidden;
        }
        .table-header {
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: var(--purple-50);
        }
        .table-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0 0 0.5rem 0;
        }
        .table-description {
            color: #64748b;
            margin: 0;
            font-size: 0.875rem;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .levels-table {
            width: 100%;
            border-collapse: collapse;
        }
        .levels-table thead th {
            background: var(--purple-100);
            color: var(--purple-900);
            font-weight: 600;
            padding: 1.5rem 1rem;
            text-align: left;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--purple-200);
        }
        .index-column { width: 60px; }
        .title-column { width: 25%; }
        .type-column { width: 12%; }
        .score-column { width: 10%; }
        .instructions-column { width: 25%; }
        .created-column { width: 10%; }
        .actions-column { width: 18%; }
        .level-row {
            transition: all 0.2s ease;
            cursor: move;
        }
        .level-row:hover {
            background: var(--purple-50);
        }
        .level-row td {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .index-badge {
            background: var(--gradient-primary);
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
        }
        .level-title {
            font-weight: 600;
            color: var(--purple-900);
            font-size: 1rem;
        }
        .type-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .type-drag_drop { background: #ddd6fe; color: #7c3aed; }
        .type-multiple_choice { background: #fef3c7; color: #92400e; }
        .type-tf1 { background: #d1fae5; color: #065f46; }
        .type-match_pairs { background: #dbeafe; color: #1e40af; }
        .type-flip_cards { background: #fce7f3; color: #be185d; }
        .type-reorder { background: #fed7d7; color: #991b1b; }
        .score-display {
            font-weight: 600;
            color: var(--purple-700);
        }
        .instructions-text {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.4;
        }
        .created-date {
            color: #64748b;
            font-size: 0.875rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .edit-btn {
            background: #fef3c7;
            color: #92400e;
            border-color: #fed7aa;
        }
        .edit-btn:hover {
            background: #fde68a;
            color: #92400e;
            transform: translateY(-1px);
        }
        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }
        .delete-btn:hover {
            background: #fecaca;
            color: #991b1b;
            transform: translateY(-1px);
        }
        .delete-form {
            display: inline;
        }
        .empty-row td {
            padding: 3rem 1rem;
        }
        .empty-state {
            text-align: center;
            color: #94a3b8;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--purple-300);
        }
        .empty-state p {
            font-size: 1.125rem;
            margin: 0;
        }
        /* Sortable styles */
        .sortable-ghost {
            opacity: 0.4;
        }
        .sortable-chosen {
            background: var(--purple-100) !important;
        }
        .sortable-drag {
            box-shadow: var(--shadow-xl);
            transform: rotate(2deg);
        }
        @media (max-width: 768px) {
            .levels-container {
                padding: 1rem;
            }
            .levels-header-container .flex {
                flex-direction: column;
                gap: 1rem;
            }
            .add-level-button-container {
                padding: 1rem 0 0;
                justify-content: center;
            }
            .btn-create-level {
                align-self: stretch;
                justify-content: center;
            }
            .table-wrapper {
                overflow-x: scroll;
            }
            .levels-table {
                min-width: 900px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
            .action-btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
                justify-content: center;
            }
        }
    </style>
</x-app-layout>