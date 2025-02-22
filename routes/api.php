<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/barbershops/{barbershop}/services', [ServicesController::class, 'listByBarbershop']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users', [UserController::class, 'listUsers']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/barbershops')->group(function () {
        Route::post('/', [BarbershopController::class, 'store'])->middleware('barbershop_permission'); // Criar barbearia
        Route::get('/', [BarbershopController::class, 'list']);   // Listar barbearias
    });

    Route::prefix('/services')->group(function () {
        Route::post('/', [ServicesController::class, 'store'])->middleware('barbershop_permission'); // Criar serviço
        Route::get('/', [ServicesController::class, 'list']);   // Listar serviços
    });
});
