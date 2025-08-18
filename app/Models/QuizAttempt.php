<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id','stage_id','level_id','kind','score','passed','answers','started_at','finished_at'
    ];
    protected $casts = ['answers' => 'array','started_at' => 'datetime','finished_at' => 'datetime'];

    public function stage(){ return $this->belongsTo(Stage::class); }
    public function level(){ return $this->belongsTo(Level::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
