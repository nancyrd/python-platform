<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">User Management</h2>
    </x-slot>

    <div class="p-6">
        <table class="min-w-full border rounded-lg shadow-md bg-white">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Quiz Attempts</th>
                    <th class="p-3">Level Progress</th>
                    <th class="p-3">Stage Progress</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">{{ $user->quiz_attempts_count }}</td>
                        <td class="p-3">{{ $user->level_progress_count }}</td>
                        <td class="p-3">{{ $user->stage_progress_count }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
