<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;

class HomeController extends ControllerKX {
    private function str_ireplace2($search, $replace, $subject) {
        $len = strlen($search);
        $result = "";
        $i = 0;
    
        while ($i < strlen($subject)) {
            if (strtolower(substr($subject, $i, $len)) === strtolower($search)) {
                $j = 0;
                $insideTag = false;
                foreach (str_split($replace) as $char) {
                    if ($char == "<") {
                        $insideTag = true;
                        $result .= $char;
                    } else if ($char == ">") {
                        $insideTag = false;
                        $result .= $char;
                    } else if (!$insideTag) {
                        $result .= ctype_upper($subject[$i + $j]) ? strtoupper($char) : strtolower($char);
                        $j++;
                    } else $result .= $char;
                }
                $i += $len;
            } else {
                $result .= $subject[$i];
                $i++;
            }
        }
        return $result;
    }

    public function index() {
        return redirect("/empresas/selecionar");
    }

    public function autocomplete(Request $request) { 
        $tabela = $request->table;       
        $minha_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
        $meu_tipo = Empresas::find($minha_empresa)->tipo;

        $where = " AND ".$request->column." LIKE '";
        if ($tabela == "menu") $where .= "%";
        $where .= $request->search."%'";
        
        if ($tabela == "empresas") {
            if ($request->filter_col) {
                $colunas = explode(",", $request->filter_col);
                $valores = explode(",", $request->filter);
                if (sizeof($colunas) == sizeof($valores)) {
                    for ($i = 0; $i < sizeof($colunas); $i++) {
                        $valor = $valores[$i];
                        if (!in_array($colunas[$i], ["id_matriz", "id_grupo", "id_segmento", "id_criadora", "tipo", "tipo_contribuicao"])) $valor = "'".$valor."'";
                        $where .= " AND ".$colunas[$i]." = ".$valor;
                    }
                } else $where .= " AND ".$colunas[0]." IN (".implode(",", $valores).")";
            }
        } else if ($request->filter_col) {
            $where .= $request->column != "referencia" ? " AND ".$request->filter_col." = '".$request->filter."'" : " AND referencia NOT IN (
                SELECT produto_ou_referencia_valor
                FROM atribuicoes
                WHERE pessoa_ou_setor_valor = ".$request->filter."
                  AND lixeira = 0
            )";
        }

        if ($tabela == "menu") {
            $tabela = "(
                SELECT * FROM (
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
                            AND mp.tipo = ".$meu_tipo."
                        )
                    ) AS menua ON menua.id_modulo = modulos.id

                    JOIN menu_perfis AS mp
                        ON mp.id_menu = menua.id

                    WHERE (url IS NOT NULL OR submenu_url IS NOT NULL)
                      AND mp.tipo = ".$meu_tipo."
                      AND mp.admin IN (0, ".Auth::user()->admin.")
                        
                    ORDER BY modulos.ordem, menua.ordem
                ) AS main

                GROUP BY
                    id,
                    descr,
                    lixeira
            ) AS tab";
        } else if (in_array($tabela, ["empresas", "locais"])) $where .= " AND id".($tabela == "locais" ? "_empresa" : "")." IN (".implode(",", $this->empresas_acessiveis()).")"; // ControllerKX.php

        $query = "SELECT ";
        if ($request->column == "referencia") $query .= "MIN(id) AS ";
        $query .= "id, ".$request->column;
        $query .= " FROM ".$tabela;
        $query .= " WHERE lixeira = 0".$where;
        if ($request->column == "referencia") $query .= " GROUP BY referencia";
        if ($tabela != "menu") $query .= " ORDER BY ".$request->column;
        $query .= " LIMIT 30";
        
        $resultado = array();
        $consulta = DB::select(DB::raw($query));
        foreach ($consulta as $linha) {
            $linha = (array) $linha;
            $aux = array(
                "id" => $linha["id"],
                $request->column => $this->str_ireplace2($request->search, "<b>".$request->search."</b>", $linha[$request->column])
            );
            array_push($resultado, $aux);
        }
        return json_encode($resultado);
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
                                ->where("mp.tipo", Empresas::find($this->retorna_empresa_logada())->tipo) // ControllerKX.php
                                ->whereIn("mp.admin", [0, Auth::user()->admin])
                                ->orderby("ordem")
                                ->get();
            if (sizeof($modulo->itens)) array_push($resultado, $modulo);
        }
        return json_encode($resultado);
    }

    public function novo_modulo(Request $request) {
        $linha = new Modulos;
        $linha->descr = $request->descr;
        $linha->save();
        $linha->ordem = $linha->id;
        $linha->save();
    }

    public function menu_form() {
        $modulos = DB::table("modulos")->get();
        return view("menu_form", compact("modulos"));
    }

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

    public function menu_salvar(Request $request) {
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