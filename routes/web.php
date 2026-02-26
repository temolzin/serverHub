<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\GcpMachineController;
use App\Http\Controllers\TypeApplicationController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\StorageController;

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.post');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('register.basic');
Route::post('/auth/register-basic', [RegisterBasic::class, 'store'])->name('register.store');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [Analytics::class, 'index'])
        ->name('dashboard-analytics');
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
        Route::resource('servers', ServerController::class)->except(['create', 'edit', 'show']);
        Route::post('/servers/import', [ImportController::class, 'import'])
            ->name('servers.import');
        Route::post('/servers-off/import', [ImportController::class, 'importPoweredOff'])
            ->name('servers-off.import');
    });

    Route::middleware('permission:viewTypeApplication')
        ->resource('type-applications', TypeApplicationController::class);
    Route::middleware('permission:viewGcpMachine')
        ->resource('gcp-machines', GcpMachineController::class);
    Route::middleware('permission:viewApplication')
        ->resource('applications', ApplicationController::class);
    Route::middleware('permission:viewDatabase')
        ->resource('databases', DatabaseController::class);
    Route::middleware('permission:viewInstance')
        ->resource('instances', InstanceController::class);
    Route::middleware('permission:viewStorage')
        ->resource('storages', StorageController::class);

    Route::middleware('role:Admin')->group(function () {
        Route::get(
            '/users/{user}/permissions',
            [UserController::class, 'editPermissions']
        )->name('users.permissions.edit');
        Route::resource('users', UserController::class)->except(['show']);
    });
});
