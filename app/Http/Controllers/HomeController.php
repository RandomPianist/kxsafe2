<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;

class HomeController extends Controller {
    public function index() {
        return redirect("/empresas/selecionar");
    }

    public function autocomplete(Request $request) {        
        $where = " AND ".$request->column." LIKE '".$request->search."%'";
        
        if ($request->filter_col) {
            $where .= $request->column != "referencia" ? " AND ".$request->filter_col." = '".$request->filter."'" : " AND referencia NOT IN (
                SELECT produto_ou_referencia_valor
                FROM atribuicoes
                WHERE pessoa_ou_setor_valor = ".$request->filter."
                  AND lixeira = 0
            )";
        }

        if ($request->table == "empresas" && Empresas::find(Auth::user()->id_empresa)->tipo > 1) $where .= " AND ".Auth::user()->id_empresa." IN (id, id_criadora)";

        $query = "SELECT ";
        if ($request->column == "referencia") $query .= "MIN(id) AS ";
        $query .= "id, ".$request->column;
        $query .= " FROM ".$request->table;
        $query .= " WHERE lixeira = 0".$where;
        if ($request->column == "referencia") $query .= " GROUP BY referencia";
        $query .= " ORDER BY ".$request->column;
        $query .= " LIMIT 30";
        
        return json_encode(DB::select(DB::raw($query)));
    }

    public function menu() {
        $resultado = array();
        $consulta = DB::table("modulos")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->orderby("ordem")
                        ->get();
        foreach ($resultado as $modulo) {
            $modulo->itens = DB::table("menu")
                                ->select(
                                    "descr",
                                    "url"
                                )
                                ->join("menu_perfis AS mp", "menu.id", "mp.id_menu")
                                ->where("id_pai", 0)
                                ->where("id_modulo", $modulo->id)
                                ->where("menu_perfis.tipo", Empresas::find(Auth::user()->id_empresa)->tipo)
                                ->get();
            if (sizeof($modulo->itens)) array_push($resultado, $modulo);
        }
        return json_encode($resultado);
    }
}