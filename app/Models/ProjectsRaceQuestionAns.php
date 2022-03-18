<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsRaceQuestionAns extends Model
{
    public function projectsRaceQuestion()
    {
        return $this->belongsTo('App\Models\ProjectsRaceQuestions');
    }
}
