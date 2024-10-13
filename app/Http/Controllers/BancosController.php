<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Bancos;

class BancosController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("bancos")
                    ->select(
                        "id",
                        "cod",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Bancos" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("bancos"); // ControllerKX.php
        return view("bancos", compact("ultima_atualizacao", "breadcumb"));
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
            DB::table("bancos")
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
            "Bancos" => config("app.root_url")."/bancos",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $banco = DB::table("bancos")
                        ->select(
                            "id",
                            "cod",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("bancos_crud", compact("breadcumb", "banco"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Bancos::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Bancos::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "bancos", $linha->id);  // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Bancos::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "bancos", $linha->id);  // ControllerKX.php
    }
}