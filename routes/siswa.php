<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\siswa\VerifiedController;
use App\Http\Controllers\siswa\DashboardController;

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

Route::prefix('siswa')->group(function () {

    Route::get('login', [AuthController::class, 'login'])->name('siswa.login');
    Route::get('logout', [AuthController::class, 'logout'])->name('siswa.logout');


    Route::get('/otp/send/{uuid}', [OtpController::class, 'sendOtp'])->name('siswa.otp.send');

    Route::get('/verified/{uuid}', [VerifiedController::class, 'index'])->name('siswa.verified');
    Route::post('/verified/{uuid}/verify', [VerifiedController::class, 'verify'])->name('siswa.verified.verify');

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('siswa.dashboard');
    });
});