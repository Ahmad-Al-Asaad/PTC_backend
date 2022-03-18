<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

//    public function course()
//    {
//            return $this->hasMany(Courses::class,'coachID','id');
//    }
}
