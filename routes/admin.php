<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\viewController;
use App\Http\Controllers\admin\SiswaController;
use App\Http\Controllers\admin\ModuleController;
use App\Http\Controllers\admin\PembinaController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LogSystemController;
use App\Http\Controllers\admin\StatisticController;
use App\Http\Controllers\admin\UserGroupController;
use App\Http\Controllers\admin\SettingSmtpController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ------------------------------------------  Admin -----------------------------------------------------------------
Route::prefix('admin')->group(function () {
    //Reset Password
    Route::get('profile/password/request', [ProfileController::class, 'request'])->name('admin.profile.password.request');
    Route::post('profile/password/request', [ProfileController::class, 'email'])->name('admin.profile.password.email');
    Route::get('profile/password/reset/{token}', [ProfileController::class, 'resetPassword'])->name('admin.profile.password.reset');
    Route::post('profile/password/reset/{token}', [ProfileController::class, 'updatePassword'])->name('admin.profile.password.update');


    Route::get('registrasi', [AuthController::class, 'registrasi'])->name('admin.registrasi');

    Route::get('registrasi/pembina', [AuthController::class, 'registrasi_pembina'])->name('admin.registrasi.pembina');
    Route::post('registrasi/pembina/save', [AuthController::class, 'registrasi_pembina_save'])->name('admin.registrasi.pembina.save');
    Route::post('registrasi/pembina/checkEmail', [AuthController::class, 'registrasi_pembina_checkEmail'])->name('admin.registrasi.pembina.checkEmail');
    Route::post('registrasi/pembina/checkTelepon', [AuthController::class, 'registrasi_pembina_checkTelepon'])->name('admin.registrasi.pembina.checkTelepon');

    Route::get('registrasi/siswa', [AuthController::class, 'registrasi_siswa'])->name('admin.registrasi.siswa');
    Route::post('registrasi/siswa/save', [AuthController::class, 'registrasi_siswa_save'])->name('admin.registrasi.siswa.save');
    Route::post('registrasi/siswa/checkEmail', [AuthController::class, 'registrasi_siswa_checkEmail'])->name('admin.registrasi.siswa.checkEmail');
    Route::post('registrasi/siswa/checkNis', [AuthController::class, 'registrasi_siswa_checkNis'])->name('admin.registrasi.siswa.checkNis');
    Route::post('registrasi/siswa/checkTelepon', [AuthController::class, 'registrasi_siswa_checkTelepon'])->name('admin.registrasi.siswa.checkTelepon');
    

    Route::get('login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('login/checkEmail', [AuthController::class, 'checkEmail'])->name('admin.login.checkEmail');
    Route::post('login/checkPassword', [AuthController::class, 'checkPassword'])->name('admin.login.checkPassword');
    Route::post('loginProses', [AuthController::class, 'loginProses'])->name('admin.loginProses');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    Route::get('main-admin', [viewController::class, 'main_admin'])->name('main_admin');

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('dashboard/fetchData', [DashboardController::class, 'fetchData'])->name('admin.dashboard.fetchData');

        //Log Systems
        Route::get('log-systems', [LogSystemController::class, 'index'])->name('admin.logSystems');
        Route::get('log-systems/getData', [LogSystemController::class, 'getData'])->name('admin.logSystems.getData');
        Route::get('log-systems/getDataModule', [LogSystemController::class, 'getDataModule'])->name('admin.logSystems.getDataModule');
        Route::get('log-systems/getDataUser', [LogSystemController::class, 'getDataUser'])->name('admin.logSystems.getDataUser');
        Route::get('log-systems/getDetail{id}', [LogSystemController::class, 'getDetail'])->name('admin.logSystems.getDetail');
        Route::get('log-systems/clearLogs', [LogSystemController::class, 'clearLogs'])->name('admin.logSystems.clearLogs');
        Route::get('log-systems/generatePDF', [LogSystemController::class, 'generatePDF'])->name('admin.logSystems.generatePDF');
    
        //User Group
        Route::get('user-groups', [UserGroupController::class, 'index'])->name('admin.user_groups');
        Route::get('user-groups/add', [UserGroupController::class, 'add'])->name('admin.user_groups.add');
        Route::get('user-groups/getData', [UserGroupController::class, 'getData'])->name('admin.user_groups.getData');
        Route::post('user-groups/save', [UserGroupController::class, 'save'])->name('admin.user_groups.save');
        Route::get('user-groups/edit/{id}', [UserGroupController::class, 'edit'])->name('admin.user_groups.edit');
        Route::put('user-groups/update', [UserGroupController::class, 'update'])->name('admin.user_groups.update');
        Route::delete('user-groups/delete', [UserGroupController::class, 'delete'])->name('admin.user_groups.delete');
        Route::get('user-groups/getDetail-{id}', [UserGroupController::class, 'getDetail'])->name('admin.user_groups.getDetail');
        Route::post('user-groups/changeStatus',[UserGroupController::class, 'changeStatus'])->name('admin.user_groups.changeStatus');
        Route::post('user-groups/checkName',[UserGroupController::class, 'checkName'])->name('admin.user_groups.checkName');
        
        //User
        Route::get('users', [UserController::class, 'index'])->name('admin.users');
        Route::get('users/add', [UserController::class, 'add'])->name('admin.users.add');
        Route::get('users/getData', [UserController::class, 'getData'])->name('admin.users.getData');
        Route::post('users/save', [UserController::class, 'save'])->name('admin.users.save');
        Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/update', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/delete', [UserController::class, 'delete'])->name('admin.users.delete');
        Route::get('users/getDetail-{id}', [UserController::class, 'getDetail'])->name('admin.users.getDetail');
        Route::get('users/getUserGroup', [UserController::class, 'getUserGroup'])->name('admin.users.getUserGroup');
        Route::get('users/getDataUserGroup', [UserController::class, 'getDataUserGroup'])->name('admin.users.getDataUserGroup');
        Route::post('users/changeStatus',[UserController::class, 'changeStatus'])->name('admin.users.changeStatus');
        Route::get('users/generateKode',[UserController::class, 'generateKode'])->name('admin.users.generateKode');
        Route::post('users/checkEmail',[UserController::class, 'checkEmail'])->name('admin.users.checkEmail');
        Route::post('users/checkKode',[UserController::class, 'checkKode'])->name('admin.users.checkKode');

        Route::get('users/arsip',[UserController::class, 'arsip'])->name('admin.users.arsip');
        Route::get('users/arsip/getDataArsip',[UserController::class, 'getDataArsip'])->name('admin.users.getDataArsip');
        Route::put('users/arsip/restore',[UserController::class, 'restore'])->name('admin.users.restore');
        Route::delete('users/arsip/forceDelete',[UserController::class, 'forceDelete'])->name('admin.users.forceDelete');
        
        //Profile
        Route::get('profile/{kode}', [ProfileController::class, 'index'])->name('admin.profile');
        Route::get('profile/getData', [ProfileController::class, 'getData'])->name('admin.profile.getData');
        Route::put('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::get('profile/getDetail-{kode}', [ProfileController::class, 'getDetail'])->name('admin.profile.getDetail');
        Route::post('profile/checkEmail',[ProfileController::class, 'checkEmail'])->name('admin.profile.checkEmail');
        
        //Setting
        Route::get('setting-general', [SettingController::class, 'index'])->name('admin.settings.general');
        Route::put('setting-general/update', [SettingController::class, 'update'])->name('admin.settings.general.update');

        //Setting SMTP
        Route::get('setting-smtp', [SettingSmtpController::class, 'index'])->name('admin.settings.smtp');
        Route::put('setting-smtp/update', [SettingSmtpController::class, 'update'])->name('admin.settings.smtp.update');

        //Setting
        Route::get('settings', [SettingController::class, 'main'])->name('admin.settings');
        Route::get('settings/admin', [SettingController::class, 'admin'])->name('admin.settings.admin');
        Route::get('settings/frontpage', [SettingController::class, 'frontpage'])->name('admin.settings.frontpage');
        Route::get('settings/admin/general', [SettingController::class, 'admin_general'])->name('admin.settings.admin.general');
        Route::put('settings/admin/general/update', [SettingController::class, 'admin_general_update'])->name('admin.settings.admin.general.update');
        Route::get('settings/admin/smtp', [SettingController::class, 'admin_smtp'])->name('admin.settings.admin.smtp');
        Route::put('settings/admin/smtp/update', [SettingController::class, 'admin_smtp_update'])->name('admin.settings.admin.smtp.update');

        //Modul dan Modul Akses
        Route::get('module', [ModuleController::class, 'index'])->name('admin.module');
        Route::get('module/add', [ModuleController::class, 'add'])->name('admin.module.add');
        Route::get('module/getData', [ModuleController::class, 'getData'])->name('admin.module.getData');
        Route::post('module/save', [ModuleController::class, 'save'])->name('admin.module.save');
        Route::get('module/edit/{id}', [ModuleController::class, 'edit'])->name('admin.module.edit');
        Route::put('module/update', [ModuleController::class, 'update'])->name('admin.module.update');
        Route::delete('module/delete', [ModuleController::class, 'delete'])->name('admin.module.delete');
        Route::get('module/getDetail-{id}', [ModuleController::class, 'getDetail'])->name('admin.module.getDetail');

        //Statistic
        Route::get('statistic', [StatisticController::class, 'index'])->name('admin.statistic');
        Route::get('statistic/getData', [StatisticController::class, 'getData'])->name('admin.statistic.getData');
        Route::get('statistic/getDetail{id}', [StatisticController::class, 'getDetail'])->name('admin.statistic.getDetail');

        //Siswa
        Route::get('siswa', [SiswaController::class, 'index'])->name('admin.siswa');
        Route::get('siswa/add', [SiswaController::class, 'add'])->name('admin.siswa.add');
        Route::get('siswa/getData', [SiswaController::class, 'getData'])->name('admin.siswa.getData');
        Route::post('siswa/save', [SiswaController::class, 'save'])->name('admin.siswa.save');
        Route::get('siswa/edit/{id}', [SiswaController::class, 'edit'])->name('admin.siswa.edit');
        Route::put('siswa/update', [SiswaController::class, 'update'])->name('admin.siswa.update');
        Route::delete('siswa/delete', [SiswaController::class, 'delete'])->name('admin.siswa.delete');
        Route::get('siswa/getDetail-{id}', [SiswaController::class, 'getDetail'])->name('admin.siswa.getDetail');
        Route::get('siswa/getUserGroup', [SiswaController::class, 'getUserGroup'])->name('admin.siswa.getUserGroup');
        Route::get('siswa/getDataUserGroup', [SiswaController::class, 'getDataUserGroup'])->name('admin.siswa.getDataUserGroup');
        Route::post('siswa/changeStatus',[SiswaController::class, 'changeStatus'])->name('admin.siswa.changeStatus');
        Route::get('siswa/generateKode',[SiswaController::class, 'generateKode'])->name('admin.siswa.generateKode');
        Route::post('siswa/checkEmail',[SiswaController::class, 'checkEmail'])->name('admin.siswa.checkEmail');
        Route::post('siswa/checkKode',[SiswaController::class, 'checkKode'])->name('admin.siswa.checkKode');
        Route::post('siswa/checkNis',[SiswaController::class, 'checkNis'])->name('admin.siswa.checkNis');
        Route::post('siswa/checkTelepon',[SiswaController::class, 'checkTelepon'])->name('admin.siswa.checkTelepon');

        Route::get('siswa/arsip',[SiswaController::class, 'arsip'])->name('admin.siswa.arsip');
        Route::get('siswa/arsip/getDataArsip',[SiswaController::class, 'getDataArsip'])->name('admin.siswa.getDataArsip');
        Route::put('siswa/arsip/restore',[SiswaController::class, 'restore'])->name('admin.siswa.restore');
        Route::delete('siswa/arsip/forceDelete',[SiswaController::class, 'forceDelete'])->name('admin.siswa.forceDelete');

        //Pembina
        Route::get('pembina', [PembinaController::class, 'index'])->name('admin.pembina');
        Route::get('pembina/add', [PembinaController::class, 'add'])->name('admin.pembina.add');
        Route::get('pembina/getData', [PembinaController::class, 'getData'])->name('admin.pembina.getData');
        Route::post('pembina/save', [PembinaController::class, 'save'])->name('admin.pembina.save');
        Route::get('pembina/edit/{id}', [PembinaController::class, 'edit'])->name('admin.pembina.edit');
        Route::put('pembina/update', [PembinaController::class, 'update'])->name('admin.pembina.update');
        Route::delete('pembina/delete', [PembinaController::class, 'delete'])->name('admin.pembina.delete');
        Route::get('pembina/getDetail-{id}', [PembinaController::class, 'getDetail'])->name('admin.pembina.getDetail');
        Route::get('pembina/getUserGroup', [PembinaController::class, 'getUserGroup'])->name('admin.pembina.getUserGroup');
        Route::get('pembina/getDataUserGroup', [PembinaController::class, 'getDataUserGroup'])->name('admin.pembina.getDataUserGroup');
        Route::post('pembina/changeStatus',[PembinaController::class, 'changeStatus'])->name('admin.pembina.changeStatus');
        Route::get('pembina/generateKode',[PembinaController::class, 'generateKode'])->name('admin.pembina.generateKode');
        Route::post('pembina/checkEmail',[PembinaController::class, 'checkEmail'])->name('admin.pembina.checkEmail');
        Route::post('pembina/checkKode',[PembinaController::class, 'checkKode'])->name('admin.pembina.checkKode');
        Route::post('pembina/checkTelepon',[PembinaController::class, 'checkTelepon'])->name('admin.pembina.checkTelepon');

        Route::get('pembina/arsip',[PembinaController::class, 'arsip'])->name('admin.pembina.arsip');
        Route::get('pembina/arsip/getDataArsip',[PembinaController::class, 'getDataArsip'])->name('admin.pembina.getDataArsip');
        Route::put('pembina/arsip/restore',[PembinaController::class, 'restore'])->name('admin.pembina.restore');
        Route::delete('pembina/arsip/forceDelete',[PembinaController::class, 'forceDelete'])->name('admin.pembina.forceDelete');
    });
});
