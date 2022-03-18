<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionQuestionAnswer extends Model
{
    protected $guarded=[];

    public function exhibitionQuestion()
    {
        return $this->belongsTo('App\Models\ExhibitionQuestion');
    }
}
