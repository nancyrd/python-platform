<?php

// app/Models/UserLevelProgress.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevelProgress extends Model
{
    protected $table = 'user_level_progress';

    protected $fillable = [
        'user_id','stage_id','level_id',
        'best_score','stars','attempts_count',
        'passed','first_passed_at','last_attempt_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'first_passed_at' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    public function level() { return $this->belongsTo(Level::class); }
    public function stage() { return $this->belongsTo(Stage::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
