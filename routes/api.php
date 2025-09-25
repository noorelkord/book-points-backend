<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MeetingPointController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\ItemController;

Route::get('/test-colleges', function () {
    return 'colleges route test';
});


Route::get('/ping', fn() => ['message' => 'Book Points backend is running!']);

Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{location}', [LocationController::class, 'show']);
Route::get('/locations/{location}/meeting-points', [MeetingPointController::class, 'index']);

Route::get('/colleges', [CollegeController::class, 'index']);
Route::get('/items',    [ItemController::class, 'index']);
Route::get('/items/filter', [ItemController::class, 'filter']);
Route::get('/items/search', [ItemController::class, 'search']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',           [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/items',       [ItemController::class, 'store']);
    Route::post('/items/{item}/reserve', [ItemController::class, 'reserve']);
});

