<?php

namespace App\Http\Resources\create;

use Illuminate\Http\Resources\Json\JsonResource;

class JobCampusesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'campus_name' => $this->campus_name
        ];
    }
}
