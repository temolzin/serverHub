<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\GcpMachineController;
use App\Http\Controllers\TypeApplicationController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ApplianceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PowerLogController;
use App\Http\Controllers\AuditLogController;

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.post');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('register.basic');
Route::post('/auth/register-basic', [RegisterBasic::class, 'store'])->name('register.store');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('content.authentications.auth-reset-password-basic', [
        'token' => $token,
        'email' => request('email')
    ]);
})->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('permission:viewPowerLogs')->group(function () {
        Route::get('/power-logs', [PowerLogController::class, 'index'])
            ->name('power-logs.index');
        Route::middleware('permission:viewAuditLogs')->group(function () {
            Route::get('/audit-logs', [AuditLogController::class, 'index'])
                ->name('audit-logs.index');
        });
    });

    Route::get('/', [Analytics::class, 'index'])
        ->name('dashboard-analytics');
    Route::get('/dashboard/filter', [Analytics::class, 'filter'])
        ->name('dashboard.filter');
    Route::get('/dashboard/patched-machines/export', [Analytics::class, 'exportPatchedMachines'])
        ->name('dashboard.patched.export');
    Route::get('/export/{module}', [ExportController::class, 'export'])
        ->name('export');
    Route::middleware('permission:viewOwner')
        ->resource('owners', OwnerController::class);
    Route::middleware('permission:viewServer')->group(function () {
        Route::post('/servers/{server}/power-on', [ServerController::class, 'powerOn'])
            ->name('servers.power-on');
        Route::post('/servers/{server}/power-off', [ServerController::class, 'powerOff'])
            ->name('servers.power-off');
        Route::get('/servers-off', [ServerController::class, 'offIndex'])
            ->name('servers-off.index');
        Route::post('/servers-off', [ServerController::class, 'offStore'])
            ->name('servers-off.store');
        Route::put('/servers-off/{server}', [ServerController::class, 'offUpdate'])
            ->name('servers-off.update');
        Route::delete('/servers-off/{server}', [ServerController::class, 'offDestroy'])
            ->name('servers-off.destroy');
        Route::resource('servers', ServerController::class)
            ->except(['create', 'edit', 'show']);
        Route::post('/servers/import', [ImportController::class, 'import'])
            ->name('servers.import');
        Route::post('/servers-off/import', [ImportController::class, 'importPoweredOff'])
            ->name('servers-off.import');
    });

    Route::middleware('permission:viewServer')->group(function () {
        Route::post('/appliances/{server}/power-on', [ApplianceController::class, 'powerOn'])
            ->name('appliances.power-on');
        Route::post('/appliances/{server}/power-off', [ApplianceController::class, 'powerOff'])
            ->name('appliances.power-off');
        Route::get('/appliances-off', [ApplianceController::class, 'offIndex'])
            ->name('appliances-off.index');
        Route::post('/appliances-off', [ApplianceController::class, 'offStore'])
            ->name('appliances-off.store');
        Route::put('/appliances-off/{server}', [ApplianceController::class, 'offUpdate'])
            ->name('appliances-off.update');
        Route::delete('/appliances-off/{server}', [ApplianceController::class, 'offDestroy'])
            ->name('appliances-off.destroy');
        Route::post('/appliances/import', [ImportController::class, 'import'])
            ->name('appliances.import');
        Route::resource('appliances', ApplianceController::class)
            ->except(['create', 'edit', 'show']);
    });

    Route::middleware('permission:viewTypeApplication')
        ->resource('type-applications', TypeApplicationController::class);
    Route::middleware('permission:viewGcpMachine')->group(function () {
        Route::post('/gcp-machines/{gcp_machine}/power-on', [GcpMachineController::class, 'powerOn'])
            ->name('gcp-machines.power-on');
        Route::post('/gcp-machines/{gcp_machine}/power-off', [GcpMachineController::class, 'powerOff'])
            ->name('gcp-machines.power-off');
        Route::get('/gcp-machines-off', [GcpMachineController::class, 'offIndex'])
            ->name('gcp-machines-off.index');
        Route::post('/gcp-machines-off', [GcpMachineController::class, 'offStore'])
            ->name('gcp-machines-off.store');
        Route::put('/gcp-machines-off/{gcp_machine}', [GcpMachineController::class, 'offUpdate'])
            ->name('gcp-machines-off.update');
        Route::delete('/gcp-machines-off/{gcp_machine}', [GcpMachineController::class, 'offDestroy'])
            ->name('gcp-machines-off.destroy');
        Route::resource('gcp-machines', GcpMachineController::class)
            ->except(['create', 'edit', 'show']);
    });

    Route::middleware('permission:viewApplication')
        ->resource('applications', ApplicationController::class);
    Route::middleware('permission:viewDatabase')
        ->resource('databases', DatabaseController::class);
    Route::middleware('permission:viewInstance')
        ->resource('instances', InstanceController::class);
    Route::middleware('permission:viewStorage')
        ->resource('storages', StorageController::class);

    Route::middleware('role:Admin')->group(function () {
        Route::get('/users/{user}/permissions', [UserController::class, 'editPermissions'])
            ->name('users.permissions.edit');
        Route::resource('users', UserController::class);
    });
});
