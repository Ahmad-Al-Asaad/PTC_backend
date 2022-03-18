<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class   Opportunity extends JsonResource
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
            'state' => $this->state,
            'type' => $this->type,
            'companyID' => $this->companyID,
            'time' => $this->time,
            'freeDesks' => $this->freeDesks,
            'salary' => $this->salary,
            'lastDateForRegister' => $this->lastDateForRegister,
            'location' => $this->location,
            'scope' => $this->scope,
            'description' => $this->description
        ];

        return $result;
    }
}
