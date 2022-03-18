<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Question extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'CourseID' => $this->CourseID,
            'title' => $this->title,
            'type' => $this->type,
            'required' => $this->required,
            'answerQuestion' => isset($this->answerQuestion) ? CourseQuestionAnswer::collection($this->answerQuestion) : null,

        ];

        return $result;
    }
}
