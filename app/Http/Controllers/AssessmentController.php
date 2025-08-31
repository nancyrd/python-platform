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

    // --- grade ---
    $questions = $assessment->questions;
    if (!is_array($questions)) {
        $questions = json_decode($questions ?? '[]', true) ?: [];
    }

    $correct = 0;
    foreach ($questions as $idx => $q) {
        $userAns = $data['answers'][$idx] ?? null;
        if ($userAns === ($q['correct'] ?? null)) {
            $correct++;
        }
    }
    $score  = count($questions) ? (int) floor(($correct / count($questions)) * 100) : 0;
    $passed = $score >= 50;

    // --- persist attempt ---
    $attempt = \App\Models\QuizAttempt::create([
        'user_id'     => Auth::id(),
        'stage_id'    => $assessment->stage_id,
        'level_id'    => null,
        'kind'        => $assessment->type,
        'score'       => $score,
        'passed'      => $passed,
        'answers'     => $data['answers'],
        'finished_at' => now(),
    ]);

    // Update other progress (stars, etc.)
    $updater->apply($attempt);

    // Ensure progress row
    $progress = UserStageProgress::firstOrCreate(
        ['user_id' => Auth::id(), 'stage_id' => $assessment->stage_id],
        ['unlocked_to_level' => 1, 'stars_per_level' => [], 'last_activity_at' => now()]
    );

    // Mark pre/post timestamps
    if ($assessment->type === 'pre') {
        $progress->pre_completed_at = now();
    } elseif ($assessment->type === 'post') {
        // ✅ Only mark post as completed if PASSED
        if ($passed) {
            $progress->post_completed_at = now();
        }
    }
    $progress->last_activity_at = now();
    $progress->save();

    // --- where to go & what to show ---
    if ($assessment->type === 'post') {
        if ($passed) {
            // Back to the stage page with a success toast
            return to_route('stages.show', $assessment->stage_id)->with([
                'flash.type'    => 'success',
                'flash.title'   => 'Post Assessment Passed!',
                'flash.message' => "Great job! You scored {$score}%. The next stage is now unlocked. 🚀",
                // keep 'status' too if you already surface it somewhere else
                'status'        => "Post assessment passed ({$score}%).",
            ]);
        } else {
            // Back to the same stage with a retry toast
            return to_route('stages.show', $assessment->stage_id)->with([
                'flash.type'    => 'danger',
                'flash.title'   => 'Try Again',
                'flash.message' => "You scored {$score}%. You need at least 50% to pass. Retake the post assessment.",
                'status'        => "Post assessment failed ({$score}%).",
            ]);
        }
    }

    // For PRE (or anything else), just go back to the stage page with info
    return to_route('stages.show', $assessment->stage_id)->with([
        'flash.type'    => 'info',
        'flash.title'   => 'Pre-Assessment Saved',
        'flash.message' => "You completed the pre-assessment with a score of {$score}%.",
        'status'        => "Pre assessment submitted ({$score}%).",
    ]);
}
}
