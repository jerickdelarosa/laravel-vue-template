<?php

namespace App\Http\Resources\create;

use Illuminate\Http\Resources\Json\JsonResource;

class JobOfficesResource extends JsonResource
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
            'office_id' => $this->office_id,
            'office_name' => $this->office_name
        ];
    }
}
