<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulsuOffices extends Model
{
    use HasFactory;

    protected $table = "bulsu_offices";

    protected $fillable = [
        'office_id',
        'office_name'
    ];
}
