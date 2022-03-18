<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAnswers extends Model
{
    public function getCourse()
    {
        return $this->belongsTo('App\Models\CourseQuestion','questionID','id');
    }

    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student','studentID','id');
    }
}
