<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseQuestionAnswer extends Model
{
        protected $guarded=[];

        public function getCourseQuestion()
        {
            return $this->belongsTo('App\Models\CourseQuestion','questionID','id');
        }
}
