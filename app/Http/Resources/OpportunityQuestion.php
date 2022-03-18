<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityQuestion extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'opportunityId' => $this->opportunityId,
            'title' => $this->title,
            'type' => $this->type,
            'required' => $this->required,
            'answerQuestion' => isset($this->answerQuestion) ? OpportunityQuestionAnswers::collection($this->answerQuestion) : null,

        ];

        return $result;
    }
}
