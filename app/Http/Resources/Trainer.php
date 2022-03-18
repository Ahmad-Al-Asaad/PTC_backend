<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Trainer extends JsonResource
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
            'userID' => $this->userID,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'age' => $this->age,
            'location' => $this->location,
            'phone' => $this->phone,
            'specialization' => $this->specialization,
            'description' => $this->description,
            'user'=>User::make($this->user),
        ];
    }
}
