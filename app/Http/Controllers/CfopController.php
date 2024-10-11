<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Cfop;

class CfopController extends ControllerKX {
    private function busca($param = "1") {
        $minha_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
        return DB::table("cfop")
                    ->select(
                        "id",
                        "cfop",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Cfop" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("cfop"); // ControllerKX.php
        return view("cfop", compact("ultima_atualizacao", "breadcumb"));
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
            DB::table("cfop")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        )) return "1";
        return "0";
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1])) return redirect("/"); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Cfop" => config("app.root_url")."/cfop",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $cfop = DB::table("cfop")
                        ->select(
                            "id",
                            "cfop",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("cfop_crud", compact("breadcumb", "cfop"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Cfop::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Cfop::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "cfop", $linha->id);  // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Cfop::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "cfop", $linha->id);  // ControllerKX.php
    }
}