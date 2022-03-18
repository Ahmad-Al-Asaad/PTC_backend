<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class courseLoginRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
            return [
                'id'=>$this->id,
//                'studentID'=>$this->studentID,
                'student' => isset($this->getStudent) ? $this->getStudent : null,
                //'courseID'=>$this->courseID,
                'course' => isset($this->course) ? $this->course : null,
                'state'=>$this->state,
            ];
    }
}
