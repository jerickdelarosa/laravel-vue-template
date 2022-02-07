<?php

namespace App\Http\Resources\edit;

use Illuminate\Http\Resources\Json\JsonResource;

class JobStatusResource extends JsonResource
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
            'status_id' => $this->status_id,
            'status_name' => $this->status_name
        ];
    }
}
