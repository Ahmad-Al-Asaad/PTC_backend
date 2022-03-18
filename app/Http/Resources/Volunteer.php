<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Volunteer extends JsonResource
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'age' => $this->age,
            'volunteerTitle' => $this->volunteerTitle,
            'location' => $this->location,
            'specialization' => $this->specialization,
            'section' => $this->section,
            'phone' => $this->phone,
            'college' => $this->college,
            'description' => $this->description,
        ];
        return $result;
    }
}
