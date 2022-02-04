<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JobOpeningRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'jo_date_posted' => [
                'date',
                'required'
            ],
            'jo_position' => [
                'string',
                'required'
            ],
            'jo_campus' => [
                'string',
                'required'
            ],
            'jo_office' => [
                'required',
                Rule::exists('bulsu_offices', 'office_id')
            ],
            'jo_vacancy_count' => [
                'numeric',
                'min: 1',
                'required'
            ],
            'jo_status' => [
                'required',
                Rule::exists('tbl_job_status', 'status_id')
            ],
            'jo_hr_type' => [
                'required',
                Rule::exists('bulsu_jo_hrmo_types', 'id')
            ],
            'jo_qualifications_education' => [
                'string',
                'nullable'
            ],
            'jo_qualifications_experience' => [
                'string',
                'nullable'
            ],
            'jo_qualifications_training' => [
                'string',
                'nullable'
            ],
            'jo_qualifications_eligibility' => [
                'string',
                'nullable'
            ],
            'jo_qualifications_other' => [
                'string',
                'nullable'
            ],
            'jo_duties' => [
                'string',
                'nullable'
            ],
            'jo_item_number' => [
                'string',
                'nullable'
            ],
            'jo_salary_grade' => [
                'string',
                'nullable'
            ],
            'jo_salary_value' => [
                'string',
                'nullable'
            ],
            'jo_deadline' => [
                'date',
                'nullable'
            ],
            'jo_remarks' => [
                'boolean',
            ],
            'jo_visibility' => [
                'boolean',
            ],
            'jo_date_filled' => [
                'date',
                'nullable'
            ],
            'jo_hidden_at' => [
                'date',
                'nullable'
            ],
            'attachment' => [
                'string',
                'nullable'
            ],
            'show_details' => [
                'boolean'
            ],
            'link' => [
                'string',
                'nullable'
            ]

        ];
    }
}
