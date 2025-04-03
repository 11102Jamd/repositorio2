<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::apiResource('usuarios', UserController::class);

Route::apiResource('pedidos', OrderController::class);

Route::apiResource('productos', ProductController::class);

Route::apiResource('proveedores', SupplierController::class);

Route::apiResource('compras', PurchaseOrderController::class);

