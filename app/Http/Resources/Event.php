<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
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
            'title' => $this->title,
            'groups' => $this->groups,
            'type' => $this->type,
            'location' => $this->location,
            'coachName' => $this->coachName,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'currentNumber' => $this->currentNumber,
            'maxNumber' => $this->CurrentStudents,
            'cost' => $this->cost,
            'description' => $this->description,
        ];
        return $result;
    }
}
