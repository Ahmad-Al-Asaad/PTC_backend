<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsRaceRequests extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
//          'studentID'=>$this->studentID,
                'student' => isset($this->getStudent) ? $this->getStudent : null,
//                'projectsRaceID'=>$this->projectsRaceID,
                'projectsRace' => isset($this->projectsRace) ? $this->projectsRace : null,
                'state' => $this->state,
            ];
    }
}
