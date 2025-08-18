<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\QuizAttempt;
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

    $progress = \App\Models\UserStageProgress::where('user_id', \Auth::id())
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

    // ✅ Normalize questions to array even if DB returns a JSON string
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

    $score = count($questions) ? (int) floor(($correct / count($questions)) * 100) : 0;

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

    $updater->apply($attempt);

    return to_route('stages.show', $assessment->stage_id)
        ->with('status', "Submitted ({$assessment->type}), score {$score}%");
}
}
