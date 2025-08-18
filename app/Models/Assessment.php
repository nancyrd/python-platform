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

}
