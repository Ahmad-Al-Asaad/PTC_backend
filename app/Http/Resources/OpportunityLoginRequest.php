<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityLoginRequest extends JsonResource
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
//            'opportunityID'=>$this->opportunityID,
            'opportunity' => isset($this->opportunity) ? $this->opportunity : null,
            'state'=>$this->state,
        ];
    }
}
