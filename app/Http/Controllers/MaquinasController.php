<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Maquinas;
use App\Models\Concessoes;

class MaquinasController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("maquinas")
                    ->select(
                        "maquinas.id",
                        "maquinas.descr",
                        "locais.descr AS local",
                        DB::raw("
                            CASE
                                WHEN conc.id_maquina IS NOT NULL THEN 1
                                ELSE 0
                            END AS possui_concessoes
                        "),
                        DB::raw("
                            CASE
                                WHEN conc.de IS NOT NULL THEN conc.de
                                ELSE '---'
                            END AS de
                        "),
                        DB::raw("
                            CASE
                                WHEN conc.para IS NOT NULL THEN conc.para
                                ELSE '---'
                            END AS para
                        ")
                    )
                    ->leftjoinsub(
                        DB::table("concessoes")
                            ->select(
                                "id_maquina",
                                "de.nome_fantasia AS de",
                                "para.nome_fantasia AS para"
                            )
                            ->leftjoin("empresas AS de", "de.id", "concessoes.id_de")
                            ->leftjoin("empresas AS para", "para.id", "concessoes.id_para")
                            ->whereRaw("CURDATE() >= inicio")
                            ->whereNull("fim")
                            ->where("concessoes.lixeira", 0),
                    "conc", "conc.id_maquina", "maquinas.id")
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
        if(!sizeof(
            DB::table("locais")
                ->where("id", $request->id_local)
                ->where("descr", $request->local)
                ->where("lixeira", 0)
                ->get())
        ) return "local";
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
            DB::table("concessoes")
                ->where("id_maquina", $id)
                ->whereRaw("CURDATE() >= inicio")
                ->whereNull("fim")
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
        $this->concessoes_excluir("id_maquina = ".$linha->id); // ControllerKX.php
    }

    public function conceder(Request $request) {
        $linha = new Concessoes;
        $linha->taxa_inicial = $request->taxa_inicial;
        $linha->inicio = Carbon::createFromFormat('d/m/Y', $request->inicio)->format('Y-m-d');
        $linha->id_de = $request->id_de;
        $linha->id_para = $request->id_para;
        $linha->id_maquina = $request->id_maquina;
        $linha->save();
        $this->log_inserir("C", "concessoes", $linha->id);
    }

    public function encerrar(Request $request) {
        $where = "id_maquina = ".$request->id_maquina;
        DB::statement("UPDATE concessoes SET fim = CURDATE() WHERE ".$where);
        $this->log_inserir2("E", "concessoes", $where, "NULL");
    }
}