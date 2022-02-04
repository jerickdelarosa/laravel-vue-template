<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\HrmoTypeController;
use App\Http\Controllers\JobOpeningsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('job_openings')->name('job_openings.')->group(function () {
    //Get Job
    Route::get('/', [JobOpeningsController::class, 'get'])->name('get');

    //Get Offices/Job Status/HRMO Type for form
    Route::get('/create', [JobOpeningsController::class, 'create'])->name('create');

    //Store New Job
    Route::post('/', [JobOpeningsController::class, 'store'])->name('store');



    //Get Offices
    Route::get('/offices', [OfficeController::class, 'get'])->name('offices.get');

    //Get Job Status
    Route::get('/status', [StatusController::class, 'get'])->name('status.get');

    //Get HRMO Type
    Route::get('/hrmo-type', [HrmoTypeController::class, 'get'])->name('hrmo.get');
});
