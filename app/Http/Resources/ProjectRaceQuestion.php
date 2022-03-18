<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectRaceQuestion extends JsonResource
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
            'title' => $this->title,
            'type' => $this->type,
            'required' => $this->required,
            'answerQuestion' => isset($this->answerQuestion) ? ProjectsRaceQuestionAns::collection($this->answerQuestion) : null,

        ];
    }
}
