<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderByEmailController;  // Buscar pedidos por email
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
 * Rutas PÚBLICAS — No requieren autenticación.
 * El frontend las usa para mostrar el showroom y catálogo.
 */

// Rutas PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/products',          [ProductController::class, 'index']);
Route::get('/products/{slug}',   [ProductController::class, 'show']);
Route::get('/categories',        [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Crear orden — público, no requiere login
Route::post('/orders', [OrderController::class, 'store']);

// Buscar pedidos por email — público, el cliente solo necesita su email
Route::get('/orders/by-email', OrderByEmailController::class);

// Rutas PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);
    Route::get('/orders',      [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
});