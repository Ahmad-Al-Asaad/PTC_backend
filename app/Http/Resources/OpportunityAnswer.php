<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityAnswer extends JsonResource
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
            'question' => isset($this->getOpportunity) ? $this->getOpportunity : null,
            'answer'=>$this->answer,
        ];
    }
}
