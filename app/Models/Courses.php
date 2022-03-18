<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;

class Courses extends Model
{
        protected $guarded=[];


    public function courseQuestion()
    {
        return $this->hasMany('App\Models\CourseQuestion','CourseID','id');
    }

    public function coach()
    {
        return $this->belongsTo(Trainer::class,'coachID');
    }



}
