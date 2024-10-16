<?php

namespace App\Providers;

use DB;
use Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Empresas;

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
            $view->with([
                'empresa_logada' => $kx->retorna_empresa_logada(),
                'legenda' => function($tipo) use($kx) {
                    $aux = explode(" ", $kx->empresas_legenda($tipo));
                    return ucfirst($aux[1]);
                },
                'legenda2' => function($tipo) {
                    switch (intval($tipo)) {
                        case 1:
                            return "franqueadoras";
                            break;
                        case 2:
                            return "franquias";
                            break;
                        case 3:
                            return "clientes";
                            break;
                    }
                },
                'multiempresa' => Auth::check() ? sizeof(
                    DB::table("empresas_usuarios")
                        ->where("id_usuario", Auth::user()->id)
                        ->get()
                ) > 1 : false
            ]);
        });
    }
}
