<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblBulsuCampuses extends Model
{
    use HasFactory;

    protected $table = "tbl_bulsu_campuses";

    protected $fillable = [
        'campus_name',
    ];
}
