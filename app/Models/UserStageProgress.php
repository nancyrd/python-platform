<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStageProgress extends Model
{
    protected $table = 'user_stage_progress';
    protected $fillable = [
        'user_id','stage_id','pre_completed_at','post_completed_at',
        'unlocked_to_level','stars_per_level','last_activity_at'
    ];
    protected $casts = [
        'pre_completed_at' => 'datetime',
        'post_completed_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'stars_per_level' => 'array',
    ];

    public function stage(){ return $this->belongsTo(Stage::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
