<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitionQuestion extends JsonResource
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
            'id' => $this->id,
            'exhibitionId' => $this->exhibitionId,
            'title' => $this->title,
            'type' => $this->type,
            'required' => $this->required,
            'answerQuestion' => isset($this->answerQuestion) ? ExhibitionQuestionAnswers::collection($this->answerQuestion) : null,

        ];
    }
}
