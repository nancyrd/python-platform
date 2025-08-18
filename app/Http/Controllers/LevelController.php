<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\QuizAttempt;
use App\Models\UserStageProgress;
use App\Models\UserLevelProgress;
use App\Services\ProgressUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public function show(Request $request, Level $level)
    {
        // Stage progress for gating/unlock logic
        $stageProgress = UserStageProgress::where('user_id', Auth::id())
            ->where('stage_id', $level->stage_id)
            ->first();

        $unlocked = $stageProgress && $level->index <= $stageProgress->unlocked_to_level;
        abort_unless($unlocked, 403, 'Level is locked');

        // Per-level saved progress (best score, stars, passed)
        $levelProgress = UserLevelProgress::where('user_id', Auth::id())
            ->where('stage_id', $level->stage_id)
            ->where('level_id', $level->id)
            ->first();

        // Next level inside the same stage (generic; no magic numbers)
        $nextLevel = Level::where('stage_id', $level->stage_id)
            ->where('index', '>', $level->index)
            ->orderBy('index')
            ->first();

        // If the user already passed and is NOT replaying, many level UIs should
        // show a completed panel. Your blade can handle this with $alreadyPassed.
        $alreadyPassed = ($levelProgress && $levelProgress->passed) && !$request->boolean('replay');

        // Dynamically resolve which blade to render based on $level->type.
        // Convention:
        //   resources/views/levels/types/{type}.blade.php
        // Fallbacks:
        //   resources/views/levels/{type}.blade.php
        //   resources/views/levels/show.blade.php
        $view = $this->resolveLevelView($level);

        return view($view, [
            'level'         => $level,
            'progress'      => $stageProgress,
            'levelProgress' => $levelProgress,
            'nextLevel'     => $nextLevel,
            // quality-of-life flags (optional; your blade can recompute too)
            'alreadyPassed' => $alreadyPassed,
            'savedScore'    => $levelProgress->best_score ?? null,
            'savedStars'    => $levelProgress->stars ?? 0,
        ]);
    }

    public function submit(Request $request, Level $level, ProgressUpdater $updater)
    {
        $data = $request->validate([
            'score'   => 'required|integer|min:0|max:100',
            'answers' => 'nullable|array',
        ]);

        $stageProgress = UserStageProgress::where('user_id', Auth::id())
            ->where('stage_id', $level->stage_id)
            ->firstOrFail();

        abort_unless($level->index <= $stageProgress->unlocked_to_level, 403, 'Level is locked');

        $attempt = QuizAttempt::create([
            'user_id'     => Auth::id(),
            'stage_id'    => $level->stage_id,
            'level_id'    => $level->id,
            'kind'        => 'level',
            'score'       => $data['score'],
            'passed'      => $data['score'] >= $level->pass_score,
            'answers'     => $data['answers'] ?? null,
            'finished_at' => now(),
        ]);

        // Persist level + stage aggregates (best score, stars, unlock next, etc.)
        $updater->apply($attempt);

        // If passed: take the user out of the level (to the stage map).
        // If failed: stay on level page to retry, with a status flash.
        if ($attempt->passed) {
            return to_route('stages.enter', $level->stage)
                ->with('status', "Level {$level->index} complete! Score {$data['score']}%");
        }

        return to_route('levels.show', $level)
            ->with('status', "Submitted. Score {$data['score']}%. Try again!");
    }

    /**
     * Resolve the blade to render for this level, based on $level->type.
     * Add more fallbacks here if you like different folder conventions.
     */
    protected function resolveLevelView(Level $level): string
    {
        $type = trim((string) $level->type);

        $candidates = [
            "levels.types.$type", // e.g. resources/views/levels/types/fill.blade.php
            "levels.$type",       // e.g. resources/views/levels/fill.blade.php
            "levels.show",        // your current generic blade (fallback)
        ];

        foreach ($candidates as $view) {
            if (view()->exists($view)) {
                return $view;
            }
        }

        // Absolute last resort (shouldn't happen if levels/show exists)
        return 'levels.show';
    }
}
