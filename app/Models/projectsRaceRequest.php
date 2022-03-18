<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class projectsRaceRequest extends Model
{
    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student', 'studentID', 'id');
    }

    public function projectsRace()
    {
        return $this->belongsTo(ProjectsRace::class, 'projectsRaceID', 'id');
    }
}
