<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulsuJoHrmoTypes extends Model
{
    use HasFactory;

    protected $table = "bulsu_jo_hrmo_types";

    protected $fillable = [
        'name',
        'email',
    ];
}
