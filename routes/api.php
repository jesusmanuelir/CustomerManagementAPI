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


Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware([CheckToken::class])->group(function () {

    Route::post('/customers/create', [CustomerController::class, 'store'])->name('customers.store')->middleware(['validate.customer']);

    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search')->middleware(['validate.search']);

    Route::patch('/customers/delete/{dni?}', [CustomerController::class, 'softDelete'])->name('customers.softDelete')->middleware('validate.delete');
});







