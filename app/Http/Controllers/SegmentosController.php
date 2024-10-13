<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Segmentos;

class SegmentosController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("segmentos")
                    ->select(
                        "id",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->whereIn("id_empresa", $this->empresas_acessiveis) // ControllerKX.php
                    ->where("lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Segmentos" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("segmentos"); // ControllerKX.php
        return view("segmentos", compact("ultima_atualizacao", "breadcrumb"));
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
            DB::table("segmentos")
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
            "Segmentos" => config("app.root_url")."/segmentos",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $segmento = DB::table("segmentos")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("segmentos_crud", compact("breadcrumb", "segmento"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Segmentos::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Segmentos::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "segmentos", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Segmentos::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "segmentos", $linha->id); // ControllerKX.php
    }
}