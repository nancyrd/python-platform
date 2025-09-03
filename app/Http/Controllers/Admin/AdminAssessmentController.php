<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminAssessmentController extends Controller
{
    /**
     * Show create form for a stage's (pre|post) assessment.
     * Expects ?type=pre|post
     */
    public function create(Stage $stage, Request $request)
    {
        $type = $request->query('type');

        if (!in_array($type, ['pre', 'post'], true)) {
            return redirect()
                ->route('admin.stages.edit', $stage)
                ->withErrors(['type' => 'Please choose pre or post to create an assessment.']);
        }

        // If it already exists, send to edit
        $existing = Assessment::where('stage_id', $stage->id)->where('type', $type)->first();
        if ($existing) {
            return redirect()
                ->route('admin.stages.assessments.edit', [$stage, $existing])
                ->with('status', "This stage already has a {$type} assessment. You can edit it here.");
        }

        return view('admin.assessments.create', [
            'stage' => $stage,
            'type'  => $type,
        ]);
    }

    /**
     * Store a new assessment for a stage.
     */
    public function store(Stage $stage, Request $request)
    {
        $data = $request->validate([
            'type'         => ['required', Rule::in(['pre', 'post'])],
            'title'        => ['required', 'string', 'max:255'],
            'time_limit'   => ['nullable', 'integer', 'min:30', 'max:7200'],
            'instructions' => ['nullable', 'string'],
            'questions'    => ['required', 'array', 'min:1'],
            'questions.*.prompt'  => ['required', 'string'],
            'questions.*.options' => ['required', 'string'], // textarea (will split)
            'questions.*.correct' => ['nullable', 'string'],
        ]);

        // Enforce 1 per (stage,type)
        $already = Assessment::where('stage_id', $stage->id)
            ->where('type', $data['type'])
            ->exists();

        if ($already) {
            return back()->withErrors(["type" => "This stage already has a {$data['type']} assessment."])
                         ->withInput();
        }

        $questions = $this->formatQuestions($data['questions']);

        $assessment = new Assessment();
        $assessment->stage_id     = $stage->id;
        $assessment->type         = $data['type'];
        $assessment->title        = $data['title'];
        $assessment->time_limit   = $data['time_limit'] ?? 480;
        $assessment->instructions = $data['instructions'] ?? null;
        $assessment->questions    = $questions; // casts to json in model
        $assessment->save();

        return redirect()
            ->route('admin.stages.edit', $stage)
            ->with('status', ucfirst($data['type']).' assessment created.');
    }

    /**
     * Show edit form.
     */
    public function edit(Stage $stage, Assessment $assessment)
    {
        // Ensure assessment belongs to this stage
        if ($assessment->stage_id !== $stage->id) {
            abort(404);
        }

        return view('admin.assessments.edit', [
            'stage'       => $stage,
            'assessment'  => $assessment,
            'type'        => $assessment->type,
        ]);
    }

    /**
     * Update an assessment.
     */
    public function update(Stage $stage, Assessment $assessment, Request $request)
    {
        if ($assessment->stage_id !== $stage->id) {
            abort(404);
        }

        $data = $request->validate([
            'type'         => ['required', Rule::in(['pre', 'post'])],
            'title'        => ['required', 'string', 'max:255'],
            'time_limit'   => ['nullable', 'integer', 'min:30', 'max:7200'],
            'instructions' => ['nullable', 'string'],
            'questions'    => ['required', 'array', 'min:1'],
            'questions.*.prompt'  => ['required', 'string'],
            'questions.*.options' => ['required', 'string'],
            'questions.*.correct' => ['nullable', 'string'],
        ]);

        // Uniqueness for (stage,type) excluding current
        $exists = Assessment::where('stage_id', $stage->id)
            ->where('type', $data['type'])
            ->where('id', '!=', $assessment->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(["type" => "This stage already has a {$data['type']} assessment."])
                         ->withInput();
        }

        $questions = $this->formatQuestions($data['questions']);

        $assessment->type         = $data['type'];
        $assessment->title        = $data['title'];
        $assessment->time_limit   = $data['time_limit'] ?? $assessment->time_limit ?? 480;
        $assessment->instructions = $data['instructions'] ?? null;
        $assessment->questions    = $questions;
        $assessment->save();

        return redirect()
            ->route('admin.stages.edit', $stage)
            ->with('status', ucfirst($assessment->type).' assessment updated.');
    }

    /**
     * Delete assessment.
     */
   // app/Http/Controllers/Admin/AdminAssessmentController.php

public function destroy(\App\Models\Stage $stage, \App\Models\Assessment $assessment)
{
    // ensure the assessment belongs to this stage
    if ($assessment->stage_id !== $stage->id) {
        return back()->with('error', 'Assessment does not belong to this stage.');
    }

    $title = $assessment->title ?: $assessment->type.' assessment';
    $assessment->delete();

    return redirect()
        ->route('admin.stages.edit', $stage)
        ->with('success', "Deleted “{$title}”. You can add a new assessment now.");
}

    /**
     * Convert textarea input into normalized question objects.
     * Each options textarea can be newline or comma-separated.
     */
    private function formatQuestions(array $input): array
    {
        $out = [];

        foreach ($input as $q) {
            $prompt  = trim($q['prompt'] ?? '');
            $rawOpt  = trim($q['options'] ?? '');
            $correct = isset($q['correct']) ? trim($q['correct']) : null;

            if ($prompt === '' || $rawOpt === '') {
                continue;
            }

            // Split options by newline first, else commas
            $lines = preg_split("/\r\n|\n|\r/", $rawOpt);
            if (count($lines) <= 1) {
                $lines = array_map('trim', explode(',', $rawOpt));
            }

            // Clean, unique, non-empty
            $options = array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
            $options = array_values(array_unique($options));

            // If a correct answer is present, ensure it matches one of the options
            if ($correct !== null && $correct !== '' && !in_array($correct, $options, true)) {
                // Silently drop mismatched "correct" to avoid validation blowups in UI
                $correct = null;
            }

            $out[] = [
                'prompt'  => $prompt,
                'options' => $options,
                'correct' => $correct,
            ];
        }

        return $out;
    }
}
