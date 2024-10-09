<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Grupos;

class GruposController extends ControllerKX {
    private function busca($param = "1") {
        return $this->grupos_buscar($param)->get(); // ControllerKX.php
    }

    public function ver() {
        $ultima_atualizacao = $this->log_consultar("grupos"); // ControllerKX.php
        return view("grupos", compact("ultima_atualizacao"));
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
            DB::table("grupos")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        )) return "1";
        return "0";
    }

    public function mostrar($id) {
        return json_encode(
            DB::table("grupos")
                ->select(
                    "id",
                    "descr"
                )
                ->where("id", $id)
                ->first()
        );
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Grupos::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Grupos::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "grupos", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Grupos::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "grupos", $linha->id); // ControllerKX.php
    }
}