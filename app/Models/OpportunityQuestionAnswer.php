<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityQuestionAnswer extends Model
{

    protected $guarded = [];

    public function opportunityQuestion()
    {
        return $this->belongsTo('App\Models\OpportunityQuestion');
    }
}
