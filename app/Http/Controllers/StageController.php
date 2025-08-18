<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Stage;
use App\Models\UserStageProgress;
use Illuminate\Support\Facades\Auth;
use App\Services\StageGate;


class StageController extends Controller
{
    public function show(Stage $stage ,StageGate $gate)
    {
       abort_unless($gate->isUnlocked($stage), 403, 'Stage is locked');

    $stage->load('levels');
    $progress = UserStageProgress::firstOrCreate(
        ['user_id' => Auth::id(), 'stage_id' => $stage->id],
        ['unlocked_to_level' => 0, 'stars_per_level' => []]
    );

    $pre = Assessment::where('stage_id',$stage->id)->where('type','pre')->first();
    $post = Assessment::where('stage_id',$stage->id)->where('type','post')->first();

    return view('stages.show', compact('stage','progress','pre','post'));
    }




    public function enter(Stage $stage, StageGate $gate)
{
    // 1) Lock check
    abort_unless($gate->isUnlocked($stage), 403, 'Stage is locked');

    // 2) Ensure progress row exists
    $progress = UserStageProgress::firstOrCreate(
        ['user_id' => Auth::id(), 'stage_id' => $stage->id],
        ['unlocked_to_level' => 0, 'stars_per_level' => []]
    );

    // 3) If pre not completed → go to pre assessment
    $pre = Assessment::where('stage_id', $stage->id)->where('type', 'pre')->first();
    if ($pre && !$progress->pre_completed_at) {
        return to_route('assessments.show', $pre);
    }

    // else go to the stage map
    return to_route('stages.show', $stage);
}

}
