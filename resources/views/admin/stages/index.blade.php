<x-app-layout>
    <x-slot name="header">
        <div class="stages-header-container">
            <div class="flex items-center">
                <div class="stages-icon-wrapper">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="ml-4">
                    <h2 class="stages-title">Manage Stages</h2>
                    <p class="stages-subtitle">Organize and control learning pathways</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="stages-container">
        <div class="stages-toolbar">
            <a href="{{ route('admin.stages.create') }}" class="btn-create-stage">
                <i class="fas fa-plus"></i>
                <span>Add New Stage</span>
            </a>
        </div>

        <div class="stages-table-container">
            <div class="table-header">
                <h3 class="table-title">Learning Stages</h3>
                <p class="table-description">Drag rows to reorder stages</p>
            </div>
            
            <div class="table-wrapper">
                <table class="stages-table">
                    <thead>
                        <tr>
                            <th class="order-column">Order</th>
                            <th class="title-column">Title</th>
                            <th class="slug-column">Slug</th>
                            <th class="description-column">Description</th>
                            <th class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stages as $stage)
                            <tr data-id="{{ $stage->id }}" class="stage-row">
                                <td class="order-cell">
                                    <div class="order-badge">{{ $stage->display_order }}</div>
                                </td>
                                <td class="title-cell">
                                    <div class="stage-title">{{ $stage->title }}</div>
                                </td>
                                <td class="slug-cell">
                                    <code class="stage-slug">{{ $stage->slug }}</code>
                                </td>
                                <td class="description-cell">
                                    <div class="stage-description">{{ $stage->description }}</div>
                                </td>
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.stages.edit', $stage) }}" class="action-btn edit-btn">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </a>
                                        
                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.stages.destroy', $stage) }}" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>

                                        <!-- Manage Levels -->
                                        <a href="{{ route('admin.stages.levels.index', $stage) }}" class="action-btn levels-btn">
                                            <i class="fas fa-layer-group"></i>
                                            <span>Levels</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Drag-and-drop ordering -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        new Sortable(document.querySelector("tbody"), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.stage-row',
            onEnd: function(evt) {
                let order = {};
                document.querySelectorAll("tbody tr").forEach((row, index) => {
                    order[row.dataset.id] = index + 1;
                });

                fetch("{{ route('admin.stages.reorder') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order })
                });
            }
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
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .stages-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .stages-header-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }

        .stages-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .stages-icon-wrapper i {
            font-size: 1.5rem;
            color: #fbbf24;
        }

        .stages-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .stages-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
            font-weight: 400;
        }

        .stages-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }

        .stages-toolbar {
            margin-bottom: 2rem;
        }

        .btn-create-stage {
            background: var(--gradient-primary);
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

        .btn-create-stage:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            color: white;
        }

        .btn-create-stage i {
            font-size: 1.125rem;
        }

        .stages-table-container {
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

        .stages-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stages-table thead th {
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

        .order-column { width: 80px; }
        .title-column { width: 25%; }
        .slug-column { width: 20%; }
        .description-column { width: 30%; }
        .actions-column { width: 25%; }

        .stage-row {
            transition: all 0.2s ease;
            cursor: move;
        }

        .stage-row:hover {
            background: var(--purple-50);
        }

        .stage-row td {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .order-badge {
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

        .stage-title {
            font-weight: 600;
            color: var(--purple-900);
            font-size: 1rem;
        }

        .stage-slug {
            background: #f1f5f9;
            color: #475569;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
        }

        .stage-description {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.5;
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

        .levels-btn {
            background: var(--purple-100);
            color: var(--purple-700);
            border-color: var(--purple-200);
        }

        .levels-btn:hover {
            background: var(--purple-200);
            color: var(--purple-800);
            transform: translateY(-1px);
        }

        .delete-form {
            display: inline;
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
            transform: rotate(5deg);
        }

        @media (max-width: 768px) {
            .stages-container {
                padding: 1rem;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            .stages-table {
                min-width: 800px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
</x-app-layout>