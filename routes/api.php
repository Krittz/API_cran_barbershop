<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/users')->group(function () {
        Route::put('/{user}', [UserController::class, 'update']);
        Route::get('/', [UserController::class, 'list'])->middleware('admin_permission');
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    Route::prefix('/barbershops')->group(function () {
        Route::post('/', [BarbershopController::class, 'store'])->middleware('barbershop_permission'); // Criar barbearia
        Route::get('/', [BarbershopController::class, 'list']);   // Listar barbearias
    });
});
