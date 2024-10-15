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
                            "setores.id",
                            "descr",
                            "id_empresa",
                            "empresas.nome_fantasia AS empresa"
                        )
                        ->leftjoin("empresas", "empresas.id", "id_empresa")
                        ->where("setores.id", $id)
                        ->first();
        $atribuicoes = $this->atribuicoes("S", $id); // ControllerKX.php
        $funcionario_ou_setor = "S";
        return view("setores_crud", compact("breadcrumb", "setor", "atribuicoes", "funcionario_ou_setor"));
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
        $linha->id_empresa = $request->id_empresa;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "setores", $linha->id); // ControllerKX.php
        $this->atribuicoes_salvar($request, "S", $linha->id); // ControllerKX.php
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