<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityQuestion extends Model
{
    protected $guarded = [];

    public function opportunity()
    {
        return $this->belongsTo('App\Models\Opportunity');
    }

    public function answerQuestion()
    {
        return $this->hasMany(OpportunityQuestionAnswer::class,'questionID');
    }

    public function QuestionAnswers()
    {
        return $this->hasMany('App\Models\OpportunityQuestionAnswer');
    }
}
