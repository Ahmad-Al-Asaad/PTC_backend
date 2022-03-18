<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Student extends JsonResource
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
            'userID' => $this->userID,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'specialization' => $this->specialization,
            'location' => $this->location,
            'image' => $this->image,
            'year' => $this->year,
            'studentInfo' => $this->studentInfo,
        ];
        return $result;
    }
}
