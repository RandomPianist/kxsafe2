<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Maquinas;

class MaquinasController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("maquinas")
                    ->select(
                        "maquinas.id",
                        "maquinas.descr",
                        "locais.descr AS local"
                    )
                    ->join("locais", "locais.id", "id_local")
                    ->whereRaw($param)
                    ->where("maquinas.lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Maquinas" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("maquinas"); // ControllerKX.php
        return view("maquinas", compact("ultima_atualizacao", "breadcrumb"));
    }
    
    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("maquinas.descr LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("maquinas.descr LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(maquinas.descr LIKE '%".implode("%' AND maquinas.descr LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        return json_encode($busca);
    }

    public function consultar(Request $request) {
        if (sizeof(
            DB::table("maquinas")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        ) && !intval($request->id)) return "maquina";
        if(!sizeof(DB::table("locais")
                    ->where("id", $request->id_local)
                    ->where("descr", $request->local)
                    ->where("lixeira", 0)
                    ->get())) {
            return "local";
        }  
        return "ok";
    }

    public function mostrar($id){
        return Maquinas::find($id)->descr;
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Maquinas" => config("app.root_url")."/maquinas",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $maquina = DB::table("maquinas")
                        ->select(
                            "maquinas.id",
                            "maquinas.descr",
                            "locais.descr AS local",
                            "id_local"
                        )
                        ->join("locais", "locais.id", "id_local")
                        ->where("maquinas.id", $id)
                        ->first();
        return view("maquinas_crud", compact("breadcrumb", "maquina"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Maquinas::find($id)->descr;
        if (sizeof(
            DB::table("maquinas")
                ->where("id_local", $id)
                ->where("lixeira", 0)
                ->get()
        )) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque essa máquina faz parte de um processo de concessão.";
            $resultado->permitir = 0;
        } else {
            $resultado->aviso = "Tem certeza que deseja excluir ".$nome."?";
            $resultado->permitir = 1;
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Maquinas::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->id_local = $request->id_local;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "maquinas", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Maquinas::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "maquinas", $linha->id); // ControllerKX.php
    }
    
}