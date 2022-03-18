<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitionAnswer extends JsonResource
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
//            'questionID'=>$this->questionID,
            'student' => isset($this->getStudent) ? $this->getStudent : null,
            'question' => isset($this->getExhibition) ? $this->getExhibition : null,
            'answer'=>$this->answer,
        ];
    }
}
