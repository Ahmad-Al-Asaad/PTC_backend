<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectsRace extends JsonResource
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
            'title'=>$this->title,
            'location'=>$this->location,
            'manager'=>$this->manager,
            'startDate'=>$this->startDate,
            'endDate'=>$this->endDate,
            'description'=>$this->description,
        ];
    }
}
