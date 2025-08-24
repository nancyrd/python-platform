<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\QuizAttempt;
use App\Models\Stage;               // needed for next-stage lookup
use App\Models\UserStageProgress;   // ensure progress row for next stage
use App\Services\ProgressUpdater;
use App\Services\StageGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function show(Assessment $assessment, StageGate $gate)
    {
        $assessment->load('stage');
        abort_unless($gate->isUnlocked($assessment->stage), 403, 'Stage is locked');

        $progress = UserStageProgress::where('user_id', Auth::id())
            ->where('stage_id', $assessment->stage_id)
            ->first();

        // block repeats for PRE unless user explicitly asks to replay
        if ($assessment->type === 'pre' && $progress && $progress->pre_completed_at) {
            if (!request()->boolean('replay')) {
                return to_route('stages.show', $assessment->stage_id)
                    ->with('status', 'Pre-assessment already completed ✅');
            }
        }

        return view('assessments.show', compact('assessment'));
    }

   public function submit(Request $request, Assessment $assessment, ProgressUpdater $updater, StageGate $gate)
{
    $assessment->load('stage');
    abort_unless($gate->isUnlocked($assessment->stage), 403, 'Stage is locked');

    $data = $request->validate([
        'answers' => 'required|array',
    ]);

    // Normalize questions to array even if DB returns JSON
    $questions = $assessment->questions;
    if (!is_array($questions)) {
        $questions = json_decode($questions ?? '[]', true) ?: [];
    }

    // Score it
    $correct = 0;
    foreach ($questions as $idx => $q) {
        $userAns = $data['answers'][$idx] ?? null;
        if ($userAns === ($q['correct'] ?? null)) {
            $correct++;
        }
    }
    $score = count($questions) ? (int) floor(($correct / count($questions)) * 100) : 0;

    // Save attempt
    $attempt = QuizAttempt::create([
        'user_id'     => Auth::id(),
        'stage_id'    => $assessment->stage_id,
        'level_id'    => null,
        'kind'        => $assessment->type, // 'pre' or 'post'
        'score'       => $score,
        'passed'      => $score >= 80,
        'answers'     => $data['answers'],
        'finished_at' => now(),
    ]);

    // Update stage/level progress
    $updater->apply($attempt);

    // ✅ ADD THIS: Redirect message for all assessments
    $redirectMessage = "Assessment completed with {$score}% score. " . 
                      ($attempt->passed ? "You passed! ✅" : "Try again! 🔄");

    // Handle POST assessment (next stage unlock)
    if ($assessment->type === 'post' && $attempt->passed) {
        $nextStage = Stage::where('display_order', '>', $assessment->stage->display_order)
            ->orderBy('display_order')
            ->orderBy('id')
            ->first();

        if ($nextStage) {
            UserStageProgress::firstOrCreate(
                ['user_id' => Auth::id(), 'stage_id' => $nextStage->id],
                ['unlocked_to_level' => 1, 'stars_per_level' => [], 'last_activity_at' => now()]
            );

            return to_route('stages.enter', $nextStage)
                ->with('status', "Post assessment passed ({$attempt->score}%). Next stage unlocked!");
        }

        return to_route('stages.show', $assessment->stage_id)
            ->with('status', "Post assessment passed ({$attempt->score}%). You've conquered the last stage!");
    }

    // ✅ ADD THIS: Redirect for PRE assessments and failed POST assessments
    return to_route('stages.show', $assessment->stage_id)
        ->with('status', $redirectMessage);
}
}
