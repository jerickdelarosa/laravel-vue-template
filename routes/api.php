<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\DocumentController;

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

Route::prefix('auth')->name('auth.')->group(function () {
    // Authentication
    Route::get('google', [AuthController::class, 'redirectToProvider'])->name('google.login');

    Route::post('login', [AuthController::class, 'handleProviderCallback'])->name('login');


    // User information
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('user', [AuthController::class, 'show'])->name('user');
    });
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {

        // Photo
        Route::post('/photo', [PhotoController::class, 'uploadPhoto'])->name('photo.upload');

        // Document
        Route::post('/document', [DocumentController::class, 'store'])->name('document.upload');
    });
});
