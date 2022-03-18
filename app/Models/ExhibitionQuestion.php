<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionQuestion extends Model
{
    protected $guarded=[];


    public function answerQuestion()
    {
        return $this->hasMany(ExhibitionQuestionAnswer::class,'questionID');
    }
}
