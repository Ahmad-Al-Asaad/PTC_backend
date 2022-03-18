<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $table = 'opportunities';

    protected $guarded = [];

    public function Questions()
    {
        return $this->hasMany('App\Models\OpportunityQuestion');
    }
}
