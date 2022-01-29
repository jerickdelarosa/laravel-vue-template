<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblJobStatus extends Model
{
    use HasFactory;

    protected $table = "tbl_job_status";

    protected $fillable = [
        'status_id',
        'status_name',
    ];
}
