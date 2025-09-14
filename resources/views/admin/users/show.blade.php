<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">User Details: {{ $user->name }}</h2>
    </x-slot>

    <div class="p-6 space-y-8">
        <!-- Quiz Attempts -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Quiz Attempts</h3>
            <table class="min-w-full border rounded bg-white shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Stage</th>
                        <th class="p-2">Level</th>
                        <th class="p-2">Kind</th>
                        <th class="p-2">Score</th>
                        <th class="p-2">Passed</th>
                        <th class="p-2">Started</th>
                        <th class="p-2">Finished</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizAttempts as $qa)
                        <tr class="border-t">
                            <td class="p-2">{{ $qa->stage->title ?? '-' }}</td>
                            <td class="p-2">{{ $qa->level->title ?? '-' }}</td>
                            <td class="p-2">{{ ucfirst($qa->kind) }}</td>
                            <td class="p-2">{{ $qa->score }}</td>
                            <td class="p-2">{{ $qa->passed ? '✅' : '❌' }}</td>
                            <td class="p-2">{{ $qa->started_at }}</td>
                            <td class="p-2">{{ $qa->finished_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Level Progress -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Level Progress</h3>
            <table class="min-w-full border rounded bg-white shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Stage</th>
                        <th class="p-2">Level</th>
                        <th class="p-2">Best Score</th>
                        <th class="p-2">Stars</th>
                        <th class="p-2">Attempts</th>
                        <th class="p-2">Passed</th>
                        <th class="p-2">Last Attempt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levelProgress as $lp)
                        <tr class="border-t">
                            <td class="p-2">{{ $lp->stage->title ?? '-' }}</td>
                            <td class="p-2">{{ $lp->level->title ?? '-' }}</td>
                            <td class="p-2">{{ $lp->best_score }}</td>
                            <td class="p-2">{{ $lp->stars }}</td>
                            <td class="p-2">{{ $lp->attempts_count }}</td>
                            <td class="p-2">{{ $lp->passed ? '✅' : '❌' }}</td>
                            <td class="p-2">{{ $lp->last_attempt_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Stage Progress -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Stage Progress</h3>
            <table class="min-w-full border rounded bg-white shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Stage</th>
                        <th class="p-2">Pre Completed</th>
                        <th class="p-2">Post Completed</th>
                        <th class="p-2">Unlocked To Level</th>
                        <th class="p-2">Last Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stageProgress as $sp)
                        <tr class="border-t">
                            <td class="p-2">{{ $sp->stage->title ?? '-' }}</td>
                            <td class="p-2">{{ $sp->pre_completed_at }}</td>
                            <td class="p-2">{{ $sp->post_completed_at }}</td>
                            <td class="p-2">{{ $sp->unlocked_to_level }}</td>
                            <td class="p-2">{{ $sp->last_activity_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
