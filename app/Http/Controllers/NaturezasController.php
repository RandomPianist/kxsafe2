<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Naturezas;

class NaturezasController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("naturezas")
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
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Naturezas do documento" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("naturezas"); // ControllerKX.php
        return view("naturezas", compact("ultima_atualizacao", "breadcrumb"));
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
            DB::table("naturezas")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        )) return "1";
        return "0";
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Naturezas do documento" => config("app.root_url")."/naturezas",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $natureza = DB::table("naturezas")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("naturezas_crud", compact("breadcrumb", "natureza"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Naturezas::find($id)->descr;
        if (sizeof(
            DB::table("notas")
                ->where("id_natureza", $id)
                ->get()
        )) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque existem notas associadas essa natureza de documento.";
            $resultado->permitir = 1;
        } else {
            $resultado->aviso = "Tem certeza que deseja excluir ".$nome."?";
            $resultado->permitir = 1;
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Naturezas::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "naturezas", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Naturezas::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "naturezas", $linha->id); // ControllerKX.php
    }
}