<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::apiResource('usuarios', UserController::class);

Route::apiResource('pedidos', OrderController::class);

Route::apiResource('productos', ProductController::class);

