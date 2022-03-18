<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsRaceQuestions extends Model
{
    public function answerQuestion()
    {
        return $this->hasMany(ProjectsRaceQuestionAns::class,'questionID');
    }
}
