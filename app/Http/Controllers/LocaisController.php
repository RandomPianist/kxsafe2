<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Locais;

class LocaisController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("locais")
                    ->select(
                        "locais.id",
                        "descr",
                        "empresas.nome_fantasia AS empresa"
                    )
                    ->join("empresas", "empresas.id", "id_empresa")
                    ->whereRaw($param)
                    ->whereIn("id_empresa", $this->empresas_acessiveis()) // ControllerKX.php
                    ->where("locais.lixeira", 0)
                    ->where("empresas.lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Locais de estoque" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("locais"); // ControllerKX.php
        return view("locais", compact("ultima_atualizacao", "breadcrumb"));
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
            DB::table("locais")
                ->where("lixeira", 0)
                ->where("descr", $request->descr)
                ->get()
        ) && !intval($request->id)) return "local";
        if($this->empresas_consultar($request)) // ControllerKX.php
            return "empresa";
        return "ok";
    }

    public function mostrar($id){
        return Locais::find($id)->descr;
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Locais de estoque" => config("app.root_url")."/locais",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $local = DB::table("locais")
                        ->select(
                            "locais.id",
                            "descr",
                            "empresas.nome_fantasia AS empresa",
                            "id_empresa"
                        )
                        ->join("empresas", "empresas.id", "id_empresa")
                        ->where("locais.id", $id)
                        ->first();
        return view("locais_crud", compact("breadcrumb", "local"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Locais::find($id)->descr;
        if (sizeof(
            DB::table("maquinas")
                ->where("id_local", $id)
                ->where("lixeira", 0)
                ->get()
        )) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque existem máquinas associadas a esse local de estoque.";
            $resultado->permitir = 0;
        } else {
            $resultado->aviso = "Tem certeza que deseja excluir ".$nome."?";
            $resultado->permitir = 1;
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Locais::firstOrNew(["id" => $request->id]);
        $linha->descr = $request->descr;
        $linha->id_empresa = $request->id_empresa;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "locais", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Locais::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "locais", $linha->id); // ControllerKX.php
    }

    public function estoque(Request $request) {
        for ($i = 0; $i < sizeof($request->id_produto); $i++) {
            $linha = new Estoque;
            $linha->es = $request->es[$i];
            $linha->descr = $request->obs[$i];
            $linha->qtd = $request->qtd[$i];
            $linha->id_mp = DB::table("maquinas_produtos")
                                ->where("id_produto", $request->id_produto[$i])
                                ->where("id_maquina", $request->id_maquina)
                                ->value("id");
            $linha->save();
            $this->log_inserir("C", "estoque", $linha->id);
        }
        return redirect("/valores/maquinas");
    }
}