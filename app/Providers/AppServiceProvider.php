<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    View::composer('*', function ($view) {

        if (!Auth::check()) {
            return;
        }

        $menuPath = resource_path('menu/verticalMenu.json');
        $menuData = json_decode(file_get_contents($menuPath), true);

        $user = Auth::user();

        $filteredMenu = collect($menuData['menu'])
            ->filter(function ($item) use ($user) {

                // Admin ve todo
                if ($user->hasRole('Admin')) {
                    return true;
                }

                // Si tiene role específico
                if (!empty($item['role'])) {
                    return $user->hasRole($item['role']);
                }

                // Si tiene permiso específico
                if (!empty($item['permission'])) {
                    return $user->can($item['permission']);
                }

                // Siempre permitir dashboard
                return $item['slug'] === 'dashboard';
            })
            ->values()
            ->toArray();

        $view->with('menu', $filteredMenu);
    });
}
}
