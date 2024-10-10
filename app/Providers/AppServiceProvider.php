<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Aplicar o composer para todas as views
        View::composer('*', function ($view) {
            // Exemplo de função no controller que deseja tornar visível
            $kx = new \App\Http\Controllers\ControllerKX;
            $empresa_logada = $kx->retorna_empresa_logada();
            $view->with('empresa_logada', $empresa_logada);
        });
    }
}
