<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Exhibition extends JsonResource
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
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'location' => $this->location,
            'manager' => $this->manager,
            'description' => $this->description,
        ];

        return $result;
    }
}
