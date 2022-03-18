<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitionLoginRequest extends JsonResource
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
//            'studentID'=>$this->studentID,
            'student' => isset($this->getStudent) ? $this->getStudent : null,
           // 'exhibitionID'=>$this->exhibitionID,
            'exhibition' => isset($this->exhibition) ? $this->exhibition : null,
            'state'=>$this->state,
        ];
    }
}
