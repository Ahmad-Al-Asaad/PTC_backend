<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityLoginRequest extends Model
{
    protected $guarded = [];

    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student', 'studentID', 'id');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'opportunityID', 'id');
    }
}
