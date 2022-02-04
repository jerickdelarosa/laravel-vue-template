<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobOpeningResource extends JsonResource
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
            'jo_id' => $this->jo_id,
            'jo_date_posted' => $this->jo_date_posted->format('Y-m-d'),
            'jo_position' => $this->jo_position,
            'jo_campus' => $this->jo_campus,
            'jo_office' => $this->jo_office,
            'jo_vacancy_count' => $this->jo_vacancy_count,
            'jo_status' => $this->status->status_name,
            'jo_visibility' => $this->jo_visibility
        ];
    }
}
