<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionAnswer extends Model
{
    protected $guarded=[];

    public function getExhibition()
    {
        return $this->belongsTo('App\Models\ExhibitionQuestion','questionID','id');
    }

    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student','studentID','id');
    }
}
