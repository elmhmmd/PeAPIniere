<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::group(['prefix' => 'plants', 'middleware' => ['auth:api', 'role:client']], function () {
    Route::get('/', [PlantController::class, 'index']); // List all plants
    Route::get('/{slug}', [PlantController::class, 'show']); // Show plant by slug
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'role:admin']], function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::post('/plants', [PlantController::class, 'store']);
    Route::put('/plants/{id}', [PlantController::class, 'update']);
    Route::delete('/plants/{id}', [PlantController::class, 'destroy']);
});

Route::group(['prefix' => 'orders', 'middleware' => ['auth:api', 'role:admin']], function () {
    Route::get('/stats', [OrderController::class, 'stats']); // Sales stats
});

Route::group(['prefix' => 'orders', 'middleware' => ['auth:api', 'role:client']], function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});


Route::group(['prefix' => 'orders', 'middleware' => ['auth:api', 'role:employee']], function () {
    Route::put('/{id}/prepared', [OrderController::class, 'markPrepared']); // Mark as prepared
    Route::put('/{id}/ready', [OrderController::class, 'markReady']);       // Mark as ready
});
 