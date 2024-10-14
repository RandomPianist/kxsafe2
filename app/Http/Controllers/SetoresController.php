<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Setores;

class SetoresController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("setores")
                    ->select(
                        "id",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->whereIn("id_empresa", $this->empresas_acessiveis()) // ControllerKX.php
                    ->where("lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [3])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Setores" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("setores"); // ControllerKX.php
        return view("setores", compact("ultima_atualizacao", "breadcrumb"));
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
            DB::table("setores")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        )) return "1";
        return "0";
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [3])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Setores" => config("app.root_url")."/setores",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $setor = DB::table("setores")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        $atribuicoes = DB::table("atribuicoes")
                            ->select(
                                DB::raw("
                                    CASE
                                        WHEN produto_ou_referencia_chave = 'P' THEN produtos.descr
                                        ELSE produtos.referencia
                                    END AS descr
                                "),
                                "atribuicoes.id",
                                "qtd",
                                "atribuicoes.validade",
                                "obrigatorio",
                                "produto_ou_referencia_chave",
                                "produto_ou_referencia_valor"
                            )
                            ->join("itens", function($join) {
                                $join->on(function($sql) {
                                    $sql->on("produto_ou_referencia_valor", "cod_ou_id")
                                        ->where("produto_ou_referencia_chave", "P");
                                })->orOn(function($sql) {
                                    $sql->on("produto_ou_referencia_valor", "referencia")
                                        ->where("produto_ou_referencia_chave", "R");
                                });
                            })
                            ->where("pessoa_ou_setor_chave", "S")
                            ->where("pessoa_ou_setor_valor", $id)
                            ->where("atribuicoes.lixeira", 0)
                            ->where("itens.lixeira", 0)
                            ->get();
        return view("setores_crud", compact("breadcrumb", "setor", "atribuicoes"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Setores::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Setores::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "setores", $linha->id); // ControllerKX.php
        $this->atribuicoes_salvar($request, "S", $linha->id);
    }

    public function excluir(Request $request) {
        $linha = Setores::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "setores", $linha->id); // ControllerKX.php

        $where = "funcionario_ou_setor_chave = 'S' AND funcionario_ou_setor_valor = '".$linha->id."'";
        DB::statement("
            UPDATE atribuicoes
            SET lixeira = 1
            WHERE ".$where
        );
        $this->log_inserir2("D", "atribuicoes", $where, "NULL"); // ControllerKX.php
    }
}