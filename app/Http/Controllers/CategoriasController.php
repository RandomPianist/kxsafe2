<?php

namespace App\Http\Controllers;

use DB;
use Auth;
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
                    ->whereRaw("id_empresa = ".Auth::user()->id_empresa." OR id_empresa IN (
                        SELECT id
                        FROM empresas
                        WHERE id_matriz = ".Auth::user()->id_empresa."
                    )")
                    ->where("lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find(Auth::user()->id_empresa)->tipo), [1, 2])) return redirect("/");
        $breadcumb = array(
            "Home" => config("app.root_url"),
            "Categorias" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("categorias"); // ControllerKX.php
        return view("categorias", compact("ultima_atualizacao", "breadcumb"));
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
        $breadcumb = array(
            "Home" => config("app.root_url"),
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
        return view("categorias_crud", compact("breadcumb", "categoria"));
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