<?php

Route::post('computers/import', [App\Http\Controllers\ComputerController::class, 'import']);
Route::apiResource('computers', App\Http\Controllers\ComputerController::class);

use App\Http\Controllers\ComputerController;
use Illuminate\Support\Facades\Route;

Route::apiResource('computers', ComputerController::class);
