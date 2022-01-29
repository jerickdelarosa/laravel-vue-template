<?php

namespace App\Http\Controllers;

use App\Http\Resources\create\JobOfficesResource;
use App\Models\BulsuOffices;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function get()
    {
        $list = BulsuOffices::orderBy('office_id')->get();
        return JobOfficesResource::collection($list);
    }
}
