<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Server;
use App\Observers\ServerObserver;
use App\Models\GcpMachine;
use App\Observers\GcpMachineObserver;
use App\Models\Database;
use App\Observers\DatabaseObserver;
use App\Models\Application;
use App\Observers\ApplicationObserver;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        View::composer(['layouts.sections.menu.verticalMenu', 'layouts/sections/menu/verticalMenu'], function ($view) {

            if (!Auth::check()) {
                return;
            }

            $menuPath = resource_path('menu/verticalMenu.json');
            $menuData = json_decode(file_get_contents($menuPath), true);
            $user = Auth::user();

            $filteredMenu = collect($menuData['menu'])
                ->filter(function ($item) use ($user) {

                    if ($user->hasRole('Admin')) {
                        return true;
                    }

                    if (!empty($item['role'])) {
                        return $user->hasRole($item['role']);
                    }

                    if (!empty($item['permission'])) {
                        return $user->can($item['permission']);
                    }

                    return $item['slug'] === 'dashboard';
                })
                ->values()
                ->toArray();

            $view->with('menu', $filteredMenu);
        });

        Server::observe(ServerObserver::class);
        GcpMachine::observe(GcpMachineObserver::class);
        Database::observe(DatabaseObserver::class);
        Application::observe(ApplicationObserver::class);
    }
}
