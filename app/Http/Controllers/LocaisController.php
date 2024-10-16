<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Locais;
use App\Models\LocaisItens;

class LocaisController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("locais")
                    ->select(
                        "locais.id",
                        "locais.descr",
                        "empresas.nome_fantasia AS empresa",
                        DB::raw("
                            CASE
                                WHEN estq.id_local IS NOT NULL THEN 1
                                ELSE 0
                            END AS possui_estoque
                        ")
                    )
                    ->join("empresas", "empresas.id", "locais.id_empresa")
                    ->leftjoinsub(
                        DB::table("locais")
                            ->select("id AS id_local")
                            ->joinsub(
                                DB::table("estoque")
                                    ->select("id_local")
                                    ->join("locais_itens AS li", "li.id", "estoque.id_li"),
                                "aux", "aux.id_local", "locais.id"
                            )
                            ->groupby("id"),
                        "estq", "estq.id_local", "locais.id"
                    )
                    ->whereRaw($param)
                    ->whereIn("id_empresa", $this->empresas_acessiveis()) // ControllerKX.php
                    ->where("locais.lixeira", 0)
                    ->where("empresas.lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Locais de estoque" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("locais"); // ControllerKX.php
        return view("locais", compact("ultima_atualizacao", "breadcrumb"));
    }
    
    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("descr LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("descr LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(descr LIKE '%".implode("%' AND descr LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        return json_encode($busca);
    }

    public function consultar(Request $request) {
        if (sizeof(
            DB::table("locais")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        ) && !intval($request->id)) return "local";
        if ($this->empresas_consultar($request)) return "empresa"; // ControllerKX.php
        return "ok";
    }

    public function mostrar($id){
        return Locais::find($id)->descr;
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Locais de estoque" => config("app.root_url")."/locais",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $local = DB::table("locais")
                        ->select(
                            "locais.id",
                            "descr",
                            "empresas.nome_fantasia AS empresa",
                            "id_empresa"
                        )
                        ->join("empresas", "empresas.id", "id_empresa")
                        ->where("locais.id", $id)
                        ->first();
        return view("locais_crud", compact("breadcrumb", "local"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Locais::find($id)->descr;
        if (sizeof(
            DB::table("maquinas")
                ->where("id_local", $id)
                ->where("lixeira", 0)
                ->get()
        )) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque existem máquinas associadas a esse local de estoque.";
            $resultado->permitir = 0;
        } else {
            $resultado->aviso = "Tem certeza que deseja excluir ".$nome."?";
            $resultado->permitir = 1;
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Locais::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->id_empresa = $request->id_empresa;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "locais", $linha->id); // ControllerKX.php
        $itens = DB::table("itens")->pluck("id")->toArray();
        foreach ($itens as $item) {
            if (!sizeof(
                DB::table("locais_itens")
                    ->where("id_local", $linha->id)
                    ->where("id_item", $item)
                    ->get()
            )) {
                $li = new LocaisItens;
                $li->id_local = $linha->id;
                $li->id_item = $item;
                $li->save();
                $this->log_inserir("C", "locais_itens", $li->id); // ControllerKX.php
            }
        }
    }

    public function excluir(Request $request) {
        $linha = Locais::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "locais", $linha->id); // ControllerKX.php
    }
}