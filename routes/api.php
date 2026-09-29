<?php

use App\Http\Controllers\ComputerController;
use Illuminate\Support\Facades\Route;

Route::apiResource('computers', ComputerController::class);
