<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Company extends JsonResource
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
            'name' => $this->name,
            'Location' => $this->Location,
            'Email' => $this->Email,
            'scope' => $this->scope,
            'PhoneNumber' => $this->PhoneNumber,
            'description' => $this->description
        ];

        return $result;
    }
}
