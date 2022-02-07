<?php

namespace App\Models;

use App\Models\BulsuOffices;
use App\Models\TblJobStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulsuJobOpenings extends Model
{
    use HasFactory;

    protected $fillable = [
        'jo_date_posted',
        'jo_position',
        'jo_campus',
        'jo_office',
        'jo_vacancy_count',
        'jo_status',
        'jo_hr_type',
        'jo_qualifications_education',
        'jo_qualifications_experience',
        'jo_qualifications_training',
        'jo_qualifications_eligibility',
        'jo_qualifications_other',
        'jo_duties',
        'jo_item_number',
        'jo_salary_grade',
        'jo_salary_value',
        'jo_deadline',
        'jo_remarks',
        'jo_visibility',
        'jo_date_filled',
        'jo_hidden_at',
        'attachment',
        'show_details',
        'links',
    ];

    protected $casts = [
        'jo_date_posted' => 'date',
        'jo_date_filled' => 'date',
        'jo_deadline' => 'date',
        'jo_hidden_at' => 'date',

        'jo_remarks' => 'boolean',
        'jo_visibility' => 'boolean',
        'show_details' => 'boolean',
    ];

    public $timestamps = false;

    protected $primaryKey = 'jo_id';

    public function status()
    {
        return $this->hasOne(TblJobStatus::class, 'status_id', 'jo_status');
    }

    /* public function offices()
    {
        return $this->hasOne(BulsuOffices::class, 'office_id', 'jo_office');
    } */
}
