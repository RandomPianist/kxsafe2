<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Funcionarios;

class FuncionariosController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("funcionarios")
                    ->select(
                        "funcionarios.id",
                        "nome",
                        DB::raw("
                            CASE
                                WHEN setores.id IS NULL THEN 'A CLASSIFICAR'
                                ELSE setores.descr
                            END AS setor
                        "),
                        DB::raw("
                            CASE
                                WHEN empresas.id IS NULL THEN 'A CLASSIFICAR'
                                ELSE empresas.nome_fantasia
                            END AS empresa
                        "),
                        DB::raw("IFNULL(foto, '') AS foto")
                    )
                    ->leftjoin("setores", "setores.id", "id_setor")
                    ->leftjoin("empresas", "empresas.id", "funcionarios.id_empresa")
                    ->whereRaw($param)
                    ->whereIn("funcionarios.id_empresa", $this->empresas_acessiveis()) // ControllerKX.php
                    ->where("empresas.lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [3])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Funcionários" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("funcionarios"); // ControllerKX.php
        return view("funcionarios", compact("ultima_atualizacao", "breadcrumb"));
    }
    
    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("descr LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("descr LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(descr LIKE '%".implode("%' AND descr LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        $resultado = array();
        foreach ($busca as $funcionario) {
            $aux = new \stdClass;
            $aux->id = $funcionario->id;
            $aux->nome = $funcionario->nome;
            $aux->empresa = $funcionario->empresa;
            $aux->setor = $funcionario->setor;
            $aux->foto = $funcionario->foto ? asset("storage/".$funcionario->foto) : "";
            array_push($resultado, $aux);
        }
        return json_encode($resultado);
    }

    public function consultar(Request $request) {
        $resultado = new \stdClass;
        if (!sizeof(
            DB::table("setores")
                ->where("id", $request->id_setor)
                ->where("descr", $request->setor)
                ->get()
        )) {
            $resultado->tipo = "invalido";
            $resultado->dado = "Setor";
        } else if ($this->empresas_consultar($request)) {
            $resultado->tipo = "invalido";
            $resultado->dado = "Empresa";
        } else if (sizeof(
            DB::table("funcionarios")
                ->where("lixeira", 0)
                ->where("cpf", $request->cpf)
                ->get()
        ) && trim($request->cpf)) {
            $resultado->tipo = "duplicado";
            $resultado->dado = "CPF";
        } else if (sizeof(
            DB::table("funcionarios")
                ->where("lixeira", 0)
                ->where("email", $request->email)
                ->get()
        )) {
            $resultado->tipo = "duplicado";
            $resultado->dado = "e-mail";
        } else {
            $resultado->tipo = "ok";
            $resultado->dado = "";
        }
        return json_encode($resultado);
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [3])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Funcionários" => config("app.root_url")."/funcionarios",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $funcionario = DB::table("funcionarios")
                            ->select(
                                "funcionarios.*",
                                DB::raw("
                                    CASE
                                        WHEN setores.id IS NULL THEN 'A CLASSIFICAR'
                                        ELSE setores.descr
                                    END AS setor
                                "),
                                DB::raw("
                                    CASE
                                        WHEN empresas.id IS NULL THEN 'A CLASSIFICAR'
                                        ELSE empresas.nome_fantasia
                                    END AS empresa
                                "),
                                DB::raw("IFNULL(foto, '') AS foto")
                            )
                            ->leftjoin("setores", "setores.id", "funcionarios.id_setor")
                            ->leftjoin("empresas", "empresas.id", "funcionarios.id_empresa")
                            ->where("funcionarios.id", $id)
                            ->first();
        if ($funcionario !== null) {
            $funcionario->foto = asset("storage/".$funcionario->foto);
            $funcionario->admissao = Carbon::createFromFormat('Y-m-d', $funcionario->admissao)->format('d/m/Y');
        }
        $atribuicoes = $this->atribuicoes("F", $id); // ControllerKX.php
        $funcionario_ou_setor = "F";
        return view("funcionarios_crud", compact("breadcrumb", "funcionario", "atribuicoes", "funcionario_ou_setor"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Funcionarios::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Funcionarios::firstOrNew(["id" => $request->id]);
        $linha->nome = $request->nome;
        $linha->cpf = $request->cpf;
        $linha->funcao = $request->funcao;
        $linha->admissao = Carbon::createFromFormat('d/m/Y', $request->admissao)->format('Y-m-d');
        $linha->senha = $request->senha;
        if ($request->file("foto")) $linha->foto = $request->file("foto")->store("uploads", "public");
        $linha->telefone = $request->telefone;
        $linha->email = $request->email;
        $linha->pis = $request->pis;
        $linha->supervisor = $request->supervisor;
        $linha->lixeira = $request->lixeira;
        $linha->id_empresa = $request->id_empresa;
        $linha->id_setor = $request->id_setor;
        $linha->save();
        $this->log_inserir($request->id ? "E" : "C", "funcionarios", $linha->id); // ControllerKX.php
        $this->atribuicoes_salvar($request, "F", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Funcionarios::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "funcionarios", $linha->id); // ControllerKX.php

        $where = "funcionario_ou_setor_chave = 'F' AND funcionario_ou_setor_valor = '".$linha->id."'";
        DB::statement("
            UPDATE atribuicoes
            SET lixeira = 1
            WHERE ".$where
        );
        $this->log_inserir2("D", "atribuicoes", $where, "NULL"); // ControllerKX.php
    }

    public function supervisor(Request $request) {
        return $this->supervisor_consultar($request); // ControllerKX.php
    }
}