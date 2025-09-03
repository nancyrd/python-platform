<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
     protected $fillable = ['stage_id','type','title','questions'];
    protected $casts = ['questions' => 'array'];

   public function stage()
{
    return $this->belongsTo(\App\Models\Stage::class);
}
// In your AssessmentController or similar controller

public function completePostAssessment(Request $request, Stage $stage)
{
    $userId = auth()->id();
    
    // Get or create the user's stage progress
    $progress = UserStageProgress::firstOrCreate(
        [
            'user_id' => $userId,
            'stage_id' => $stage->id,
            'type', 'title',
    'time_limit', 'instructions', 'questions',
        ],
        [
            'pre_completed_at' => now(), // Assuming pre is done
            'unlocked_to_level' => 3, // Assuming all levels done
        ]
    );
    
    // Mark post-assessment as completed
    $progress->post_completed_at = now();
    $progress->save();
    
    // This should now unlock the next stage
    return redirect()->route('dashboard')
        ->with('success', 'Stage completed! Next stage unlocked!');
}

}
