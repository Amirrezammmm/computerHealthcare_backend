<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ComputerController;

/*
|--------------------------------------------------------------------------
| مسیرهای عمومی احراز هویت (Public Auth Routes)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| مسیرهای حفاظت‌شده (Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'last_seen'])->group(function () {

    // اطلاعات کاربر لاگین‌شده و خروج
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // مسیرهای مدیریت تجهیزات (CRUD اموال و سیستم‌ها)
    // دسترسی برای Manager و Admin و User (مشاهده)
    Route::apiResource('computers', ComputerController::class);

    /*
    |----------------------------------------------------------------------
    | مسیرهای ویژه فرمانده کل قوا (Manager Only)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:manager')->prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
        Route::post('/users/{user}/sync-projects', [UserController::class, 'syncProjects']);
    });

});
