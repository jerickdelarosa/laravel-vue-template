<?php

namespace App\Http\Controllers;


use App\Models\TblJobStatus;
use Illuminate\Http\Request;
use App\Http\Resources\create\JobStatusResource;

class StatusController extends Controller
{
    public function get()
    {
        $list = TblJobStatus::orderBy('status_id')->get();
        return JobStatusResource::collection($list);
    }
}
