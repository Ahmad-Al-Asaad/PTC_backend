<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLoginRequests extends Model
{
    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student','studentID','id');
    }
    public function course()
    {
        return $this->belongsTo(Courses::class,'courseID','id');
    }
}
