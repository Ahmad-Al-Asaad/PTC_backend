<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsRaceAnswer extends Model
{
    public function getProjectsRace()
    {
        return $this->belongsTo('App\Models\ProjectsRaceQuestions','questionID','id');
    }

    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student','studentID','id');
    }
}
