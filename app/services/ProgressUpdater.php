<?php
// app/Services/ProgressUpdater.php
namespace App\Services;

use App\Models\Level;
use App\Models\QuizAttempt;
use App\Models\Stage;
use App\Models\UserStageProgress;
use App\Models\UserLevelProgress; // <- add
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProgressUpdater
{
    public function apply(QuizAttempt $attempt): void
    {
        DB::transaction(function () use ($attempt) {
            $progress = UserStageProgress::firstOrCreate(
                ['user_id' => $attempt->user_id, 'stage_id' => $attempt->stage_id],
                ['unlocked_to_level' => 0, 'stars_per_level' => []]
            );

            $progress->last_activity_at = now();

            // === Pre-assessment logic ===
            if ($attempt->kind === 'pre') {
                // Always mark as completed (even if not passed)
                $progress->pre_completed_at = $progress->pre_completed_at ?? Carbon::now();

                if ($attempt->score >= 80) {
                    $maxIndex = Level::where('stage_id', $attempt->stage_id)->max('index') ?? 0;
                    $progress->unlocked_to_level = max($progress->unlocked_to_level, (int)$maxIndex);
                } else {
                    $progress->unlocked_to_level = max($progress->unlocked_to_level, 1);
                }
            }

            // === Per-level logic (this is your main unlock logic!) ===
            if ($attempt->kind === 'level' && $attempt->level_id) {
                $level = Level::findOrFail($attempt->level_id);

                // Always update user_level_progress
                $lp = UserLevelProgress::firstOrNew([
                    'user_id'  => $attempt->user_id,
                    'stage_id' => $attempt->stage_id,
                    'level_id' => $attempt->level_id,
                ]);

                $lp->attempts_count = ($lp->attempts_count ?? 0) + 1;
                $lp->last_attempt_at = now();
                $lp->best_score = max((int)($lp->best_score ?? 0), (int)$attempt->score);

                // compute stars for this attempt, and keep the max
                $latestStars = $this->computeStars($attempt, $level->id);
                $lp->stars = max((int)($lp->stars ?? 0), $latestStars);

                // passed + first_passed_at
                $justPassed = $attempt->score >= $level->pass_score;
                $lp->passed = $lp->passed || $justPassed;
                if ($justPassed && !$lp->first_passed_at) {
                    $lp->first_passed_at = now();
                }
                $lp->save();

                // ⭐⭐⭐ UNLOCK next level if at least 1 star
                if ($latestStars >= 1) {
                    if ($level->index >= $progress->unlocked_to_level) {
                        $progress->unlocked_to_level = $level->index + 1; // unlock next
                    }
                }

                // Always update stars_per_level (even if 0, so UI can display)
                $starsPerLevel = $progress->stars_per_level ?? [];
                $prev = $starsPerLevel[(string)$level->index] ?? 0;
                $starsPerLevel[(string)$level->index] = max($prev, $latestStars);
                $progress->stars_per_level = $starsPerLevel;
            }
 if ($attempt->kind === 'post') {
                if ($attempt->score >= 80) {
                    // mark this stage as completed
                    if (!$progress->post_completed_at) {
                        $progress->post_completed_at = now();
                    }

                    // ensure there is a progress row for the next stage
                    $currentStage = Stage::find($attempt->stage_id);
                    if ($currentStage) {
                        $nextStage = Stage::where('display_order', '>', $currentStage->display_order)
                            ->orderBy('display_order')
                            ->first();

                        if ($nextStage) {
                            UserStageProgress::firstOrCreate(
                                ['user_id' => $attempt->user_id, 'stage_id' => $nextStage->id],
                                ['unlocked_to_level' => 1, 'stars_per_level' => [], 'last_activity_at' => now()]
                            );
                        }
                    }
                }
            }

            $progress->save();
        });
    }



    protected function computeStars(QuizAttempt $attempt, int $levelId): int
    {
        if ($attempt->score >= 100) {
            $prevAttempts = QuizAttempt::where('user_id', $attempt->user_id)
                ->where('level_id', $levelId)
                ->where('kind', 'level')
                ->where('id', '<', $attempt->id)
                ->count();
            return $prevAttempts === 0 ? 3 : 2;
        }
        if ($attempt->score >= 80) return 2;
        if ($attempt->score >= 50) return 1;
        return 0;
    }
}
        