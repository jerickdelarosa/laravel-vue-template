<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $filled = [
        'photo',
        'date_updated'
    ];

    protected $casts = [
        'date_updated' => 'datetime'
    ];
}
