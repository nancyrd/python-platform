<x-app-layout>
    <x-slot name="header">
        <div class="users-header-container">
            <div class="flex items-center">
                <div class="users-icon-wrapper">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="ml-4">
                    <h2 class="users-title">User Management</h2>
                    <p class="users-subtitle">View all registered users and their learning progress</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="users-container">
        <div class="users-table-container">
            <div class="table-header">
                <h3 class="table-title">Registered Users</h3>
                <p class="table-description">
                    Below is a summary of all users, their quiz attempts, and learning progress.
                </p>
            </div>

            <div class="table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Quiz Attempts</th>
                            <th>Level Progress</th>
                            <th>Stage Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="user-row">
                                <td class="user-name">
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    <div>{{ $user->name }}</div>
                                </td>
                                <td class="user-email">{{ $user->email }}</td>
                                <td>{{ $user->quiz_attempts_count }}</td>
                                <td>{{ $user->level_progress_count }}</td>
                                <td>{{ $user->stage_progress_count }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="action-btn details-btn">
                                        <i class="fas fa-eye"></i>
                                        <span>Details</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

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
            --gradient-primary: linear-gradient(135deg, var(--purple-600), var(--purple-800));
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .users-header-container {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 0 0 2rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .users-header-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2zM36 4V0h-2v4h-4v2h4v4h2V6h4V4zM6 4V0H4v4H0v2h4v4h2V6h4V4z'/%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }

        .users-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .users-icon-wrapper i {
            font-size: 1.5rem;
            color: #fbbf24;
        }

        .users-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .users-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0 0;
        }

        .users-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: calc(100vh - 200px);
        }

        .users-table-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(139, 92, 246, 0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: var(--purple-50);
        }

        .table-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--purple-900);
            margin: 0;
        }

        .table-description {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table thead th {
            background: var(--purple-100);
            color: var(--purple-900);
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--purple-200);
        }

        .users-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s ease;
        }

        .users-table tbody tr:hover {
            background: var(--purple-50);
        }

        .users-table td {
            padding: 1rem;
            vertical-align: middle;
            color: #334155;
        }

        .user-name {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: var(--purple-900);
        }

        .user-avatar {
            background: var(--gradient-primary);
            color: white;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-email {
            color: #475569;
            font-size: 0.95rem;
        }

        .action-btn {
            background: var(--purple-100);
            color: var(--purple-700);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--purple-200);
            transform: translateY(-1px);
        }
    </style>
</x-app-layout>
