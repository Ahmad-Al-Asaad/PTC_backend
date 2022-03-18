<?php

namespace App\Http\Resources;

use App\Models\Courses;
use Illuminate\Http\Resources\Json\JsonResource;

class Course extends JsonResource
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
            'state' => $this->state,
            'title' => $this->title,
            'location' => $this->location,
            'Duration' => $this->Duration,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'maxStudents' => $this->maxStudents,
            'CurrentStudents' => $this->CurrentStudents,
            'cost' => $this->cost,
            'description' => $this->description,
            'coach' => isset($this->coach) ? $this->coach : null,
//            'coach' => User::make($this->coach),
            'courseQuestion' => isset($this->courseQuestion) ? Question::collection($this->courseQuestion) : [],

        ];
        return $result;
    }
}
