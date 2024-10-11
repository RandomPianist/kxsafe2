<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Locais;

class LocaisController extends ControllerKX {
    private function busca($param = "1") {
        $minha_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
        return DB::table("locais")
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
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Locais de estoque" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("locais"); // ControllerKX.php
        return view("locais", compact("ultima_atualizacao", "breadcumb"));
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
        )) return "1";
        return "0";
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1, 2])) return redirect("/"); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Locais de estoque" => config("app.root_url")."/locais",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $local = DB::table("locais")
                        ->select(
                            "id",
                            "descr"
                        )
                        ->where("id", $id)
                        ->first();
        return view("locais_crud", compact("breadcumb", "local"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Locais::find($id)->descr;
        $erro = "";
        $lista = array(
            "locais_itens" => "itens associados",
            "maquinas" => "máquinas associadas"
        );
        foreach ($lista as $tabela => $msg) {
            if (sizeof(
                DB::table($tabela)
                    ->where("id_local", $id)
                    ->get()
            )) $erro = $msg;    
        }
        if ($erro) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque existem ".$erro." a esse local de estoque.";
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
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "locais", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Locais::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "locais", $linha->id); // ControllerKX.php
    }
}