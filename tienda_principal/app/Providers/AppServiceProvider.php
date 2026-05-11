<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        View::composer('*', function ($view) {

            $tema = Cookie::get('tema', 'claro');
            $moneda = Cookie::get('moneda', 'EUR');
            $paginacion = Cookie::get('paginacion', 12);

            $view->with('prefTema', $tema);
            $view->with('prefMoneda', $moneda);
            $view->with('prefPaginacion', $paginacion);
        });
    }
}
