<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('employees', EmployeeApiController::class);
Route::post('/employee-update/{id}', [EmployeeApiController::class, 'updates']);
