<?php

namespace App\Http\Controllers;

use App\Http\Resources\create\JobHrmoTypeResource;
use App\Models\BulsuJoHrmoTypes;
use Illuminate\Http\Request;

class HrmoTypeController extends Controller
{
    public function get()
    {
        $list = BulsuJoHrmoTypes::orderBy('id')->get();
        return JobHrmoTypeResource::collection($list);
    }
}
