<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;

class HomeController extends Controller {
    public function index() {
        return redirect("/empresas/selecionar");
    }

    public function autocomplete(Request $request) {        
        $tipo = Empresas::find(Auth::user()->id_empresa)->tipo;

        $where = " AND ".$request->column." LIKE '".$request->search."%'";
        
        if ($request->filter_col) {
            $where .= $request->column != "referencia" ? " AND ".$request->filter_col." = '".$request->filter."'" : " AND referencia NOT IN (
                SELECT produto_ou_referencia_valor
                FROM atribuicoes
                WHERE pessoa_ou_setor_valor = ".$request->filter."
                  AND lixeira = 0
            )";
        }

        if ($request->table == "empresas" && $tipo > 1) $where .= " AND ".Auth::user()->id_empresa." IN (id, id_criadora)";

        $tabela = $request->table;
        if ($tabela == "menu") {
            $tabela = "(
                SELECT
                    IFNULL(submenu_url, url) AS id,
                    CONCAT(modulos.descr, ' > ', menua.descr, IFNULL(CONCAT(' > ', submenu_descr), '')) AS descr,
                    0 AS lixeira
                    
                FROM modulos

                JOIN (
                    SELECT
                        id,
                        id_modulo,
                        descr,
                        url,
                        NULL AS submenu_descr,
                        NULL AS submenu_url,
                        ordem
                    
                    FROM menu
                    
                    WHERE id_pai = 0
                    
                    UNION ALL (
                        SELECT
                            menu.id,
                            menu.id_modulo,
                            menu.descr,
                            menu.url,
                            submenu.descr,
                            submenu.url,
                            0
                        
                        FROM menu
                        
                        LEFT JOIN menu AS submenu
                            ON submenu.id_pai = menu.id

                        JOIN menu_perfis AS mp
                            ON mp.id_menu = submenu.id
                        
                        WHERE menu.id_pai = 0
                          AND mp.tipo = ".$tipo."
                    )
                ) AS menua ON menua.id_modulo = modulos.id

                JOIN menu_perfis AS mp
                    ON mp.id_menu = menua.id

                WHERE (url IS NOT NULL OR submenu_url IS NOT NULL)
                  AND mp.tipo = ".$tipo."
                    
                ORDER BY modulos.ordem, menua.ordem
            ) AS tab";
        }

        $query = "SELECT ";
        if ($request->column == "referencia") $query .= "MIN(id) AS ";
        $query .= "id, ".$request->column;
        $query .= " FROM ".$tabela;
        $query .= " WHERE lixeira = 0".$where;
        if ($request->column == "referencia") $query .= " GROUP BY referencia";
        if ($tabela != "menu") $query .= " ORDER BY ".$request->column;
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
        foreach ($consulta as $modulo) {
            $modulo->itens = DB::table("menu")
                                ->select(
                                    "descr",
                                    "url"
                                )
                                ->join("menu_perfis AS mp", "menu.id", "mp.id_menu")
                                ->where("id_pai", 0)
                                ->where("id_modulo", $modulo->id)
                                ->where("mp.tipo", Empresas::find(Auth::user()->id_empresa)->tipo)
                                ->orderby("ordem")
                                ->get();
            if (sizeof($modulo->itens)) array_push($resultado, $modulo);
        }
        return json_encode($resultado);
    }
}