<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Modulos;
use App\Models\Menu;
use App\Models\MenuPerfis;

class MenuController extends Controller {
    private function cria_mp($id, Request $request) {
        if ($request->franqueadora != "N") {
            $mp = new MenuPerfis;
            $mp->id_menu = $id;
            $mp->tipo = 1;
            $mp->admin = $request->franqueadora == "A" ? 1 : 0;
            $mp->save();
        }

        if ($request->franquia != "N") {
            $mp = new MenuPerfis;
            $mp->id_menu = $id;
            $mp->tipo = 2;
            $mp->admin = $request->franquia == "A" ? 1 : 0;
            $mp->save();
        }

        if ($request->cliente != "N") {
            $mp = new MenuPerfis;
            $mp->id_menu = $id;
            $mp->tipo = 2;
            $mp->admin = $request->cliente == "A" ? 1 : 0;
            $mp->save();
        }
    }

    public function novo_modulo(Request $request) {
        $linha = new Modulos;
        $linha->descr = $request->descr;
        $linha->save();
        $linha->ordem = $linha->id;
        $linha->save();
    }

    public function menu_form() {
        if (Auth::user()->id != 1) return redirect("/");
        $modulos = DB::table("modulos")->get();
        return view("menu_form", compact("modulos"));
    }

    public function menu_salvar(Request $request) {
        if (Auth::user()->id != 1) return redirect("/");
        $linha = new Menu;
        $linha->url = $request->url;
        $linha->descr = $request->descr;
        $linha->id_modulo = str_replace("modulo-", "", $request->modulo);
        $linha->save();
        $linha->ordem = $linha->id;
        $linha->save();
        $this->cria_mp($linha->id, $request);

        if ($request->url_novo) {
            $pai = $linha->id;
            $linha = new Menu;
            $linha->url = $request->url_novo;
            $linha->descr = "Novo";
            $linha->id_modulo = str_replace("modulo-", "", $request->modulo);
            $linha->id_pai = $pai;
            $linha->save();
            $this->cria_mp($linha->id, $request);
        }
        return redirect("/menu-form");
    }
}