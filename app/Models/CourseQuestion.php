<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseQuestion extends Model
{
    protected $guarded=[];

    public function getCourse()
    {
        return $this->belongsTo('App\Models\courses','CourseID','id');
    }
    public function answerQuestion()
    {
        return $this->hasMany(CourseQuestionAnswer::class,'questionID');
    }

}
