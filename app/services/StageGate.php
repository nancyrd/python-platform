<?php

namespace App\Services;

use App\Models\Stage;
use App\Models\UserStageProgress;
use Illuminate\Support\Facades\Auth;

class StageGate
{
    /**
     * A stage is unlocked if:
     * - it's the first by display_order, OR
     * - the previous stage (by display_order) has post_completed_at for this user
     */
   public function isUnlocked(Stage $stage): bool
{
    $firstStage = Stage::orderBy('display_order')->first();
    if (!$firstStage) return false;

    // First stage always unlocked
    if ($stage->id === $firstStage->id) return true;

    // Find previous stage
    $prev = Stage::where('display_order', '<', $stage->display_order)
        ->orderBy('display_order', 'desc')
        ->first();

    if (!$prev) return false;

    $progressPrev = UserStageProgress::where('user_id', Auth::id())
        ->where('stage_id', $prev->id)
        ->first();

    // ✅ MODIFIED: Allow access if previous stage's PRE is completed
    // (not just post) for stage progression
    return (bool) optional($progressPrev)->pre_completed_at;
}
}
