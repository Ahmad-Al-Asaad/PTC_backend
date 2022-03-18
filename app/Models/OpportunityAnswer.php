<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityAnswer extends Model
{
    protected $guarded=[];

    public function getOpportunity()
    {
        return $this->belongsTo('App\Models\OpportunityQuestion','questionID','id');
    }

    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student','studentID','id');
    }
}
