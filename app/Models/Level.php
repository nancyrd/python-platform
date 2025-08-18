<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
     protected $fillable = ['stage_id','index','type','title','pass_score','content'];
    protected $casts = ['content' => 'array'];

    public function stage() { return $this->belongsTo(Stage::class); }

}
