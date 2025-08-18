<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
     protected $fillable = ['slug','title','description','display_order'];

    public function levels() { return $this->hasMany(Level::class)->orderBy('index'); }
    public function assessments() { return $this->hasMany(Assessment::class); }
}
