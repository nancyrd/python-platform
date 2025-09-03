<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
     protected $fillable = ['slug','title','description','display_order'];

    public function levels() { return $this->hasMany(Level::class)->orderBy('index'); }
    public function assessments() { return $this->hasMany(Assessment::class); }
    // app/Models/Stage.php

public function preAssessment(){ return $this->hasOne(\App\Models\Assessment::class)->where('type','pre'); }
public function postAssessment(){ return $this->hasOne(\App\Models\Assessment::class)->where('type','post'); }

}
