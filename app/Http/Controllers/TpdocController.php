<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Tpdoc;

class TpdocController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("tpdoc")
                    ->select(
                        "id",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->whereRaw("id_empresa = ".Auth::user()->id_empresa." OR id_empresa IN (
                        SELECT id
                        FROM empresas
                        WHERE id_matriz = ".Auth::user()->id_empresa."
                    )")
                    ->where("lixeira", 0)
                    ->get();
    }

    public function ver() {
        $breadcumb = array(
            "Home" => config("app.root_url"),
            "Tipos de documento" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("tpdoc"); // ControllerKX.php
        return view("tpdoc", compact("ultima_atualizacao", "breadcumb"));
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
            DB::table("tpdoc")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        )) return "1";
        return "0";
    }

    public function crud($id) {
        $breadcumb = array(
            "Home" => config("app.root_url"),
            "Tipos de documento" => config("app.root_url")."/tpdoc",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $tpdoc = DB::table("tpdoc")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("tpdoc_crud", compact("breadcumb", "tpdoc"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Tpdoc::find($id)->descr;
        $erro = "";
        $lista = array(
            "faturas" => "faturas associadas",
            "titulos" => "títulos associados"
        );
        foreach ($lista as $tabela => $msg) {
            if (sizeof(
                DB::table($tabela)
                    ->where("id_tpdoc", $id)
                    ->get()
            )) $erro = $msg;    
        }
        if ($erro) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque existem ".$erro." a esse tipo de documento.";
            $resultado->permitir = 0;
        } else {
            $resultado->aviso = "Tem certeza que deseja excluir ".$nome."?";
            $resultado->permitir = 1;
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Tpdoc::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "tpdoc", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Tpdoc::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "tpdoc", $linha->id); // ControllerKX.php
    }
}