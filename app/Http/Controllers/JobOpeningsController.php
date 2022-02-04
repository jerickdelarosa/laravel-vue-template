<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobOpeningRequest;
use App\Http\Resources\create\JobHrmoTypeResource;
use App\Http\Resources\create\JobOfficesResource;
use App\Http\Resources\create\JobStatusResource;
use App\Models\BulsuJobOpenings;
use Illuminate\Http\Request;
use App\Http\Resources\JobOpeningResource;
use App\Models\BulsuJoHrmoTypes;
use App\Models\BulsuOffices;
use App\Models\TblJobStatus;

class JobOpeningsController extends Controller
{

    public function get()
    {
        $list = BulsuJobOpenings::orderBy('jo_date_posted', 'DESC')->get();
        return JobOpeningResource::collection($list);
    }

    public function create()
    {
        $offices = BulsuOffices::orderBy('office_id')->get();
        $status = TblJobStatus::orderBy('status_id')->get();
        $hrmoTypes = BulsuJoHrmoTypes::orderBy('id')->get();

        return [
            'offices' => JobOfficesResource::collection($offices),
            'status' => JobStatusResource::collection($status),
            'hrmoTypes' => JobHrmoTypeResource::collection($hrmoTypes)
        ];
    }

    public function store(JobOpeningRequest $request)
    {
        $query = new BulsuJobOpenings;
        $query->fill($request->all());

        return [
            'created' => $query->save()
        ];
    }
}
