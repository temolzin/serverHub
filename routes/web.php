<?php

use App\Http\Controllers\ApplianceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GcpMachineController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\PowerLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TypeApplicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.post');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('register.basic');
Route::post('/auth/register-basic', [RegisterBasic::class, 'store'])->name('register.store');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/global-search', [GlobalSearchController::class, 'search'])
        ->name('global.search');
    Route::put('/account-settings', [AccountSettingsAccount::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [AccountSettingsAccount::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/account-settings', [AccountSettingsAccount::class, 'index'])
        ->name('account.settings');
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
        ->resource('owners', OwnerController::class)
        ->except(['create', 'edit', 'show']);
    Route::get('/owners/{owner}/modal/{type}', [OwnerController::class, 'modal'])
        ->name('owners.modal');
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
        Route::get('/servers-off/{server}/modal/{type}', [ServerController::class, 'modalOff'])
            ->name('servers-off.modal');
        Route::resource('servers', ServerController::class)
            ->except(['create', 'edit', 'show']);
        Route::get('/servers/{server}/modal/{type}', [ServerController::class, 'modal'])
            ->name('servers.modal');
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
        Route::get('/appliances/{server}/modal/{type}', [ApplianceController::class, 'modal'])
            ->name('appliances.modal');
        Route::get('/appliances-off/{server}/modal/{type}', [ApplianceController::class, 'modalOff'])
            ->name('appliances-off.modal');
        Route::post('/appliances/import', [ImportController::class, 'import'])
            ->name('appliances.import');
        Route::resource('appliances', ApplianceController::class)
            ->except(['create', 'edit', 'show']);
    });

    Route::middleware('permission:viewTypeApplication')
        ->resource('type-applications', TypeApplicationController::class)
        ->except(['create', 'edit', 'show']);
    Route::get('/type-applications/{type_application}/modal/{type}', [TypeApplicationController::class, 'modal'])
        ->name('type-applications.modal');
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
        Route::get('/gcp-machines/{gcp_machine}/modal/{type}', [GcpMachineController::class, 'modal'])
            ->name('gcp-machines.modal');
        Route::get('/gcp-machines-off/{gcp_machine}/modal/{type}', [GcpMachineController::class, 'modalOff'])
            ->name('gcp-machines-off.modal');
        Route::resource('gcp-machines', GcpMachineController::class)
            ->except(['create', 'edit', 'show']);
    });

    Route::middleware('permission:viewApplication')
        ->resource('applications', ApplicationController::class)
        ->except(['create', 'edit', 'show']);
    Route::get('/applications/{application}/modal/{type}', [ApplicationController::class, 'modal'])
        ->name('applications.modal');
    Route::middleware('permission:viewDatabase')
        ->resource('databases', DatabaseController::class)
        ->except(['create', 'edit', 'show']);
    Route::get('/databases/{database}/modal/{type}', [DatabaseController::class, 'modal'])
        ->name('databases.modal');
    Route::middleware('permission:viewInstance')
        ->resource('instances', InstanceController::class)
        ->except(['create', 'edit', 'show']);
    Route::get('/instances/{instance}/modal/{type}', [InstanceController::class, 'modal'])
        ->name('instances.modal');
    Route::middleware('permission:viewStorage')
        ->resource('storages', StorageController::class)
        ->except(['create', 'edit', 'show']);
    Route::get('/storages/{storage}/modal/{type}', [StorageController::class, 'modal'])
        ->name('storages.modal');
    Route::get('/audit-logs/{audit_log}/modal/{type}', [AuditLogController::class, 'modal'])
        ->name('audit_logs.modal');
    Route::middleware('permission:viewRole')->group(function () {
        Route::resource('roles', RoleController::class)
            ->except(['create', 'edit', 'show']);
        Route::get('/roles/{role}/modal/{type}', [RoleController::class, 'modal'])
            ->name('roles.modal');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('/users/{user}/permissions', [UserController::class, 'editPermissions'])
            ->name('users.permissions.edit');
        Route::resource('users', UserController::class)
            ->except(['create', 'edit', 'show']);
        Route::get('/users/{user}/modal/{type}', [UserController::class, 'modal'])
            ->name('users.modal');
    });
});
