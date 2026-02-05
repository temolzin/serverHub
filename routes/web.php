<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\dashboard\Analytics;

use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;

use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;

use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;

use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;

use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;

use App\Http\Controllers\tables\Basic as TablesBasic;

use App\Http\Controllers\OwnerController;
use App\Http\Controllers\GcpMachineController;
use App\Http\Controllers\TypeApplicationController;
use App\Http\Controllers\ServerController;

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.post');

Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('register.basic');
Route::post('/auth/register-basic', [RegisterBasic::class, 'store'])->name('register.store');

Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])
    ->name('auth-reset-password-basic');

Route::middleware('auth')->group(function () {

    Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

    Route::get('/admin', fn () => 'Solo Admins')
        ->middleware('role:Admin');

    Route::get('/layouts/without-menu', [WithoutMenu::class, 'index']);
    Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index']);
    Route::get('/layouts/fluid', [Fluid::class, 'index']);
    Route::get('/layouts/container', [Container::class, 'index']);
    Route::get('/layouts/blank', [Blank::class, 'index']);

    Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index']);
    Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index']);
    Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index']);
    Route::get('/pages/misc-error', [MiscError::class, 'index']);
    Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index']);

    Route::get('/cards/basic', [CardBasic::class, 'index']);

    Route::get('/ui/accordion', [Accordion::class, 'index']);
    Route::get('/ui/alerts', [Alerts::class, 'index']);
    Route::get('/ui/badges', [Badges::class, 'index']);
    Route::get('/ui/buttons', [Buttons::class, 'index']);
    Route::get('/ui/carousel', [Carousel::class, 'index']);
    Route::get('/ui/collapse', [Collapse::class, 'index']);
    Route::get('/ui/dropdowns', [Dropdowns::class, 'index']);
    Route::get('/ui/footer', [Footer::class, 'index']);
    Route::get('/ui/list-groups', [ListGroups::class, 'index']);
    Route::get('/ui/modals', [Modals::class, 'index']);
    Route::get('/ui/navbar', [Navbar::class, 'index']);
    Route::get('/ui/offcanvas', [Offcanvas::class, 'index']);
    Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index']);
    Route::get('/ui/progress', [Progress::class, 'index']);
    Route::get('/ui/spinners', [Spinners::class, 'index']);
    Route::get('/ui/tabs-pills', [TabsPills::class, 'index']);
    Route::get('/ui/toasts', [Toasts::class, 'index']);
    Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index']);
    Route::get('/ui/typography', [Typography::class, 'index']);

    Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index']);
    Route::get('/extended/ui-text-divider', [TextDivider::class, 'index']);
    Route::get('/icons/boxicons', [Boxicons::class, 'index']);

    Route::get('/forms/basic-inputs', [BasicInput::class, 'index']);
    Route::get('/forms/input-groups', [InputGroups::class, 'index']);
    Route::get('/form/layouts-vertical', [VerticalForm::class, 'index']);
    Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index']);

    Route::get('/tables/basic', [TablesBasic::class, 'index']);

    Route::get('/owners', [OwnerController::class, 'index'])
        ->middleware('permission:viewOwner')
        ->name('owners.index');

    Route::get('/servers', [ServerController::class, 'index'])
        ->middleware('permission:viewServer')
        ->name('servers.index');

    Route::get('/gcp-machines', [GcpMachineController::class, 'index'])
        ->middleware('permission:viewGcpMachine')
        ->name('gcp-machines.index');

    Route::get('/type-applications', [TypeApplicationController::class, 'index'])
        ->middleware('permission:viewTypeApplication')
        ->name('type-applications.index');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
