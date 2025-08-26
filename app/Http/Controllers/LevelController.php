<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\QuizAttempt;
use App\Models\UserStageProgress;
use App\Models\UserLevelProgress;
use App\Services\ProgressUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;

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

        // Next level inside the same stage
        $nextLevel = Level::where('stage_id', $level->stage_id)
            ->where('index', '>', $level->index)
            ->orderBy('index')
            ->first();

        $alreadyPassed = ($levelProgress && $levelProgress->passed) && !$request->boolean('replay');

        // Pick a blade view by type with sensible fallbacks
        $view = $this->resolveLevelView($level);

        return view($view, [
            'level'         => $level,
            'progress'      => $stageProgress,
            'levelProgress' => $levelProgress,
            'nextLevel'     => $nextLevel,
            'alreadyPassed' => $alreadyPassed,
            'savedScore'    => $levelProgress->best_score ?? null,
            'savedStars'    => $levelProgress->stars ?? 0,
        ]);
    }

    public function submit(Request $request, Level $level, ProgressUpdater $updater) 
    {
        // Validate score; accept answers as string JSON
        $data = $request->validate([
            'score'   => 'required|integer|min:0|max:100',
            'answers' => 'nullable|string', // answers are sent as JSON string from your JS
        ]);

        // Decode JSON safely to a PHP array; normalize to ints; allow -1 for unanswered
        $answers = null;
        if ($request->filled('answers')) {
            $decoded = json_decode($request->input('answers'), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $answers = array_map(function ($v) {
                    return is_numeric($v) ? (int)$v : -1;
                }, $decoded);
            } else {
                $answers = [];
            }
        }

        $stageProgress = UserStageProgress::where('user_id', Auth::id())
            ->where('stage_id', $level->stage_id)
            ->firstOrFail();

        abort_unless($level->index <= $stageProgress->unlocked_to_level, 403, 'Level is locked');

        $attempt = QuizAttempt::create([
            'user_id'     => Auth::id(),
            'stage_id'    => $level->stage_id,
            'level_id'    => $level->id,
            'kind'        => 'level',
            'score'       => (int)$data['score'],
            'passed'      => (int)$data['score'] >= (int)$level->pass_score,
            'answers'     => $answers,
            'finished_at' => now(),
        ]);

        $updater->apply($attempt);
        

        // re-fetch latest progress after updater mutates it
        $levelProgress = UserLevelProgress::where([
            'user_id' => Auth::id(),
            'level_id' => $level->id,
        ])->first();

        $stars     = $levelProgress->stars      ?? 0;
        $bestScore = $levelProgress->best_score ?? (int)$data['score'];

        $message  = "Level {$level->index} completed! Score: {$data['score']}%. ";
        $message .= $bestScore > $data['score'] ? "Best Score: {$bestScore}%. " :
                    ($bestScore == $data['score'] ? "New Best Score! " : "");
        $message .= "You earned {$stars} star" . ($stars == 1 ? '' : 's') . ".";

        return to_route('stages.show', $level->stage)
            ->with('status', $message)
            ->with('timestamp', time());
    }

    /**
     * Resolve the blade to render for this level, based on $level->type.
     */
    protected function resolveLevelView(Level $level): string
    {
        $type = trim((string) $level->type);

        $candidates = [
            "levels.types.$type", // e.g. resources/views/levels/types/fill.blade.php
            "levels.$type",       // e.g. resources/views/levels/fill.blade.php
            "levels.show",        // generic fallback
        ];

        foreach ($candidates as $view) {
            if (view()->exists($view)) {
                return $view;
            }
        }
        return 'levels.show';
    }
    public function instructions(Level $level)
{
    return view('levels.instructions', compact('level'));
}
public function executePython(Request $request)
{
    $data = $request->validate([
        'code' => 'required|string|max:5000',
    ]);

    // (Optional) naive guard against obviously dangerous modules
    $blocked = ['os.', 'subprocess', 'socket', 'sys.exit', 'open(', 'eval(', 'exec('];
    foreach ($blocked as $bad) {
        if (stripos($data['code'], $bad) !== false) {
            return response()->json([
                'success' => false,
                'error' => 'Disallowed code detected.',
            ], 400);
        }
    }

    $tmp = tempnam(sys_get_temp_dir(), 'py_') . '.py';
    file_put_contents($tmp, $data['code']);

    // Use 'python' on Windows, 'python3' elsewhere
    $exe = stripos(PHP_OS_FAMILY, 'Windows') === 0 ? 'python' : 'python3';
    $process = new Process([$exe, $tmp]);
    $process->setTimeout(10); // seconds
    $process->run();

    @unlink($tmp);

    if (!$process->isSuccessful()) {
        // combine STDOUT + STDERR so users see errors
        $out = trim($process->getOutput() . $process->getErrorOutput());
        return response()->json(['success' => false, 'error' => $out ?: 'Execution failed.'], 200);
    }

    $out = trim($process->getOutput());
    return response()->json(['success' => true, 'output' => $out !== '' ? $out : '(No output)']);
}
}
