<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\pembina\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('pembina')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('pembina.login');
    Route::get('logout', [AuthController::class, 'logout'])->name('pembina.logout');

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('pembina.dashboard');
    });
});