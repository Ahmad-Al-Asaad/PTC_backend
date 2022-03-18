<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 * @method static where(string $string, $id)
 */
class Volunteer extends Model
{
    protected $fillable = [
        'birthday', 'academic_year', 'college' , 'university' , 'specialization' , 'user_id' , 'section' , 'position_id' ,
        'job_title' , 'status' , 'location' , 'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
