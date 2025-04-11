<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PlaceController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\RouteController;

Route::prefix('v1')->group(function () {
    // Аутентификация (без middleware, чтобы можно было войти)
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('auth/logout', [AuthController::class, 'logout']);

    // Маршруты для мест (без проверки токена)
    Route::get('place', [PlaceController::class, 'index']);
    Route::get('place/{id}', [PlaceController::class, 'show']);
    Route::post('place', [PlaceController::class, 'store']);
    Route::match(['put', 'patch'], 'place/{id}', [PlaceController::class, 'update']);
    Route::delete('place/{id}', [PlaceController::class, 'destroy']);

    // Маршруты для расписаний
    Route::get('schedule', [ScheduleController::class, 'index']);
    Route::post('schedule', [ScheduleController::class, 'store']);
    Route::match(['put', 'patch'], 'schedule/{id}', [ScheduleController::class, 'update']);
    Route::delete('schedule/{id}', [ScheduleController::class, 'destroy']);

    // Маршруты для поиска маршрутов и истории выбора
    Route::get('route/search/{from_place_id}/{to_place_id}/{departure_time?}', [RouteController::class, 'search']);
    Route::post('route/selection', [RouteController::class, 'storeSelectionHistory']);
});

