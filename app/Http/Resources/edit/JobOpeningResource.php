<?php

namespace App\Http\Resources\edit;

use App\Http\Resources\create\JobOfficesResource;
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
            'jo_status' => $this->jo_status,
            'jo_hr_type' => $this->jo_hr_type,
            'jo_qualifications_education' => $this->jo_qualifications_education,
            'jo_qualifications_experience' => $this->jo_qualifications_experience,
            'jo_qualifications_training' => $this->jo_qualifications_training,
            'jo_qualifications_eligibility' => $this->jo_qualifications_eligibility,
            'jo_qualifications_other' => $this->jo_qualifications_other,
            'jo_duties' => $this->jo_duties,
            'jo_item_number' => $this->jo_item_number,
            'jo_salary_grade' => $this->jo_salary_grade,
            'jo_salary_value' => $this->jo_salary_value,
            'jo_deadline' => $this->jo_deadline !== null ? $this->jo_deadline->format('Y-m-d') : $this->jo_deadline,
            'jo_remarks' => $this->jo_remarks,
            'jo_visibility' => $this->jo_visibility,
            'jo_date_filled' => $this->jo_date_filled !== null ? $this->jo_date_filled->format('Y-m-d') : $this->jo_date_filled,
            'jo_hidden_at' => $this->jo_hidden_at !== null ? $this->jo_hidden_at->format('Y-m-d') : $this->jo_hidden_at,
            'attachment' => $this->attachment,
            'show_details' => $this->show_details,
            'links' => $this->links,
        ];
    }
}
