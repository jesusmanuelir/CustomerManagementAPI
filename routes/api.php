<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('login', [AuthController::class, 'login']);

Route::middleware([CheckToken::class])->group(function () {
    // a) Registrar customers (validar region/commune).
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');

    // b) Consultar customers por DNI/email.
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    // c) Eliminar lógicamente customers (cambiar estado).
    Route::patch('/customers/{customer}/soft-delete', [CustomerController::class, 'softDelete'])->name('customers.softDelete');
});







