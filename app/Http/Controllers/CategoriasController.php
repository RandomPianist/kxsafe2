<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Categorias;

class CategoriasController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("categorias")
                    ->select(
                        "id",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->where("lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Categorias" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("categorias"); // ControllerKX.php
        return view("categorias", compact("ultima_atualizacao", "breadcrumb"));
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
            DB::table("categorias")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        )) return "1";
        return "0";
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Categorias" => config("app.root_url")."/categorias",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $categoria = DB::table("categorias")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("categorias_crud", compact("breadcrumb", "categoria"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Categorias::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Categorias::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "categorias", $linha->id);  // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Categorias::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "categorias", $linha->id);  // ControllerKX.php
    }
}