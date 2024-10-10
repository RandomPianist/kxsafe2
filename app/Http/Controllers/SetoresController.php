<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Setores;

class SetoresController extends ControllerKX {
    private function busca($param = "1") {
        $minha_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
        return DB::table("setores")
                    ->select(
                        "id",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->whereRaw("id_empresa = ".$minha_empresa." OR id_empresa IN (
                        SELECT id
                        FROM empresas
                        WHERE id_matriz = ".$minha_empresa."
                    )")
                    ->where("lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [3])) return redirect("/"); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Setores" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("setores"); // ControllerKX.php
        return view("setores", compact("ultima_atualizacao", "breadcumb"));
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
        $breadcumb = array(
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
        return view("setores_crud", compact("breadcumb", "categoria"));
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
    }

    public function excluir(Request $request) {
        $linha = Setores::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "setores", $linha->id); // ControllerKX.php
    }
}