<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionLoginRequest extends Model
{
    protected $guarded=[];

    public function getStudent()
    {
        return $this->belongsTo('App\Models\Student','studentID','id');
    }
    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class,'exhibitionsID','id');
    }
}
