<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\UserStageProgress;
use App\Services\StageGate;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLevelProgress;


class DashboardController extends Controller
{
      public function index(StageGate $gate)
    {
        $stages = Stage::withCount('levels')->orderBy('display_order')->get();
        $progressByStage = UserStageProgress::where('user_id', Auth::id())
            ->get()->keyBy('stage_id');

        // NEW: aggregate stars/points per stage from user_level_progress
        $levelAggByStage = UserLevelProgress::selectRaw('stage_id, SUM(stars) as stars_sum')
            ->where('user_id', Auth::id())
            ->groupBy('stage_id')
            ->pluck('stars_sum', 'stage_id'); // e.g. [stage_id => stars_sum]

        // (Optional) overall totals for a header card
        $totalStars  = (int) ($levelAggByStage->sum() ?? 0);
        $totalPoints = $totalStars * 10; // simple rule: 10 pts per star (adjust if you like)

        // decorate stages with unlock flag AND attach aggregates (if any)
        $decorated = $stages->map(function ($s) use ($gate, $levelAggByStage) {
            $s->unlocked   = $gate->isUnlocked($s);
            $s->stars_sum  = (int) ($levelAggByStage[$s->id] ?? 0);
            $s->points_sum = $s->stars_sum * 10; // same rule: 10 pts/star
            return $s;
        });
   // ==== NEW: progress stats ====
    $stagesCompleted = $progressByStage->filter(fn ($p) => $p && $p->post_completed_at)->count();
    $stagesTotal     = $stages->count();

    $levelsCompleted = UserLevelProgress::where('user_id', Auth::id())
        ->where('passed', true)->count();
    $levelsTotal     = (int) $stages->sum('levels_count');

    $preDone  = $progressByStage->filter(fn ($p) => $p && $p->pre_completed_at)->count();
    $postDone = $stagesCompleted; // same as above, but explicit

    // Rank thresholds (tweak freely)
    $ranks = [
        ['name' => 'Beginner I', 'points' =>   0],
        ['name' => 'Beginner II','points' =>  50],
        ['name' => 'Apprentice', 'points' => 100],
        ['name' => 'Explorer',   'points' => 180],
        ['name' => 'Coder',      'points' => 280],
        ['name' => 'Pythonista', 'points' => 400],
        ['name' => 'Guru',       'points' => 600],
    ];

    // find current & next rank
    $currentRank = $ranks[0];
    $nextRank = null;
    foreach ($ranks as $i => $r) {
        if ($totalPoints >= $r['points']) {
            $currentRank = $r;
            $nextRank = $ranks[$i + 1] ?? null;
        }
    }
    $rankProgress = [
        'current' => $currentRank['name'],
        'currentFloor' => $currentRank['points'],
        'next' => $nextRank['name'] ?? 'MAX',
        'nextFloor' => $nextRank['points'] ?? $totalPoints,
        'pct' => $nextRank
            ? (int) ( ($totalPoints - $currentRank['points']) * 100 / max(1, $nextRank['points'] - $currentRank['points']) )
            : 100,
    ];
    // ==== /NEW ====

    return view('dashboard', [
        'stages'          => $decorated,
        'progressByStage' => $progressByStage,
        'totalStars'      => $totalStars,
        'totalPoints'     => $totalPoints,

        // NEW to blade:
        'stagesCompleted' => $stagesCompleted,
        'stagesTotal'     => $stagesTotal,
        'levelsCompleted' => $levelsCompleted,
        'levelsTotal'     => $levelsTotal,
        'preDone'         => $preDone,
        'postDone'        => $postDone,
        'rankProgress'    => $rankProgress,
    ]);
}
}