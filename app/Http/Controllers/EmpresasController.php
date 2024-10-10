<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Enderecos;

class EmpresasController extends ControllerKX {
    private function permanecer($tipo) {
        $minha_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
        $meu_tipo = intval(Empresas::find($minha_empresa)->tipo);
        return (($meu_tipo == 1 && $tipo == 2) || ($meu_tipo == 2 && $tipo == 3)) || 
                (($meu_tipo == 2 || $meu_tipo == 3) && $meu_tipo == $tipo) || 
                ($meu_tipo == 1 && ($tipo == 1 || $tipo == 4));
    }

    private function busca_main($consulta, $matriz, $tipo, $id_grupo) {
        return $consulta->where("id_matriz", $matriz)
                        ->where("lixeira", 0)
                        ->where("tipo", $tipo)
                        ->where(function($sql) use($id_grupo, $tipo) {
                            if (intval($id_grupo)) $sql->where("id_grupo", $id_grupo);
                        });
    }

    private function busca($matriz, $tipo, $id_grupo) {
        return DB::table("empresas")
                    ->select(
                        "id",
                        "nome_fantasia",
                        "id_matriz"
                    )
                    ->where("id_matriz", $matriz)
                    ->where("lixeira", 0)
                    ->where("tipo", $tipo)
                    ->whereIn("id", $this->empresas_acessiveis()) // ControllerKX.php
                    ->where(function($sql) use($id_grupo, $tipo) {
                        if (intval($id_grupo)) $sql->where("id_grupo", $id_grupo);
                    })
                    ->orderby("nome_fantasia")
                    ->get();
    }

    private function legenda($tipo) {
        $titulo = "";
        switch($tipo) {
            case 1:
                $titulo = "Franqueadoras";
                break;
            case 2:
                $titulo = "Franquias";
                break;
            case 3:
                $titulo = "Clientes";
                break;
            case 4:
                $titulo = "Fornecedores";
                break;
        }
        return $titulo;
    }

    private function ver($tipo, $id_grupo) {
        if (!$this->permanecer($tipo)) return redirect("/");
        $tipo = intval($tipo);
        $meu_tipo = intval(Empresas::find($this->retorna_empresa_logada())->tipo); // ControllerKX.php
        $pode_criar = ($meu_tipo == 1 || ($meu_tipo == 2 && $tipo == 3));
        $titulo = $this->legenda($tipo);
        $ultima_atualizacao = $this->log_consultar("empresas", $tipo); // ControllerKX.php
        $breadcumb = array(
            "Home" => $tipo != $meu_tipo ? config("app.root_url")."/home" : "#",
            $titulo => "#"
        );
        $empresas = array();
        $matrizes = $this->busca(0, $tipo, $id_grupo);
        foreach ($matrizes as $matriz) {
            $matriz->filiais = $this->busca($matriz->id, $tipo, $id_grupo);
            array_push($empresas, $matriz);
        }
        $grupos = $this->grupos_buscar()->orderby("descr")->get();
        $novo = "";
        switch($tipo) {
            case 1:
                $novo = "Nova franqueadora";
                break;
            case 2:
                $novo = "Nova franquia";
                break;
            case 3:
                $novo = "Novo cliente";
                break;
            case 4:
                $novo = "Novo fornecedor";
                break;
        }
        return view("empresas", compact("ultima_atualizacao", "titulo", "breadcumb", "empresas", "grupos", "id_grupo", "pode_criar", "novo"));
    }

    private function crud($tipo, Request $request) {
        $id = $request->id;
        if (!$this->permanecer($tipo)) return redirect("/");
        $titulo = $this->legenda($tipo);
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            $titulo => config("app.root_url")."/".strtolower($titulo)."/grupo/0",
            (intval($request->id) ? "Editar" : "Novo") => "#"
        );
        $criando = new \stdClass;
        $criando->matriz = DB::table("empresas")
                                ->select(
                                    "id",
                                    "nome_fantasia"
                                )
                                ->where("id", $request->id_matriz)
                                ->first();
        $criando->grupo = DB::table("grupos")
                            ->select(
                                "id",
                                "descr"
                            )
                            ->where("id", $request->id_grupo)
                            ->first();
        $empresa = DB::table("empresas")
                        ->select(
                            "empresas.*",
                            DB::raw("IFNULL(segmentos.descr, '') AS segmento"),
                            DB::raw("IFNULL(grupos.descr, '') AS grupo"),
                            DB::raw("IFNULL(matriz.nome_fantasia, '') AS matriz")
                        )
                        ->leftjoin("segmentos", "segmentos.id", "empresas.id_segmento")
                        ->leftjoin("grupos", "grupos.id", "empresas.id_grupo")
                        ->leftjoin("empresas AS matriz", "matriz.id", "empresas.id_matriz")
                        ->where("empresas.id", $request->id)
                        ->first();
        if ($empresa !== null) {
            $empresa->enderecos = DB::table("cep")
                                    ->select(
                                        "cod AS cep",
                                        "logradouro_tipo_abv",
                                        "logradouro_descr",
                                        "bairro",
                                        "cidade",
                                        "uf",
                                        "numero",
                                        "complemento",
                                        "referencia"
                                    )
                                    ->join("enderecos", "enderecos.id_cep", "cep.id")
                                    ->where("id_empresa", $empresa->id)
                                    ->get();
        }
        return view("empresas_crud", compact("titulo", "breadcumb", "empresa", "criando"));
    }

    private function url() {
        return strtolower($this->legenda(Empresas::find($this->retorna_empresa_logada())->tipo))."/grupo/0";  // ControllerKX.php
    }

    public function home() {
        return redirect("/".$this->url());
    }

    private function selecionarMain($empresa) {
        DB::statement("
            UPDATE users
            SET id_empresa = ".$empresa."
            WHERE id = ".Auth::user()->id
        );
        return $this->url();
    }

    public function selecionar(Request $request) {
        return $this->selecionarMain($request->id_empresa);
    }

    public function minhas() {
        $resultado = new \stdClass;
        $resultado->empresas = DB::table("empresas_usuarios")
                                    ->select(
                                        "empresas.id",
                                        "empresas.nome_fantasia"
                                    )
                                    ->join("empresas", "empresas.id", "empresas_usuarios.id_empresa")
                                    ->where("id_usuario", Auth::user()->id)
                                    ->get();
        $resultado->usuario = DB::table("users")
                                ->select(
                                    "name",
                                    DB::raw("IFNULL(foto, '') AS foto")
                                )
                                ->where("id", Auth::user()->id)
                                ->first();
        if ($resultado->usuario->foto) $resultado->usuario->foto = asset("storage/".$resultado->usuario->foto);
        if (sizeof($resultado->empresas) > 1) return view("empresas_sel", compact("resultado"));
        return redirect("/".$this->selecionarMain($resultado->empresas[0]->id));
    }

    public function franqueadoras($id_grupo) {
        return $this->ver(1, $id_grupo);
    }

    public function franquias($id_grupo) {
        return $this->ver(2, $id_grupo);
    }

    public function clientes($id_grupo) {
        return $this->ver(3, $id_grupo);
    }

    public function fornecedores($id_grupo) {
        return $this->ver(4, $id_grupo);
    }

    public function franqueadoras_crud(Request $request) {
        return $this->crud(1, $request);
    }

    public function franquias_crud(Request $request) {
        return $this->crud(2, $request);
    }

    public function clientes_crud(Request $request) {
        return $this->crud(3, $request);
    }

    public function fornecedores_crud(Request $request) {
        return $this->crud(4, $request);
    }

    public function consultar(Request $request) {
        if (sizeof(
            DB::table("empresas")
                ->where("lixeira", 0)
                ->where("cnpj", $request->cnpj)
                ->where("tipo", $request->tipo)
                ->get()
        )) return "1";
        return "0";
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $nome = Empresas::find($id)->nome_fantasia;
        $tipo = "";
        switch(Empresas::find($id)->tipo) {
            case 1:
                $tipo = "a franqueadora";
                break;
            case 2:
                $tipo = "a franquia";
                break;
            case 3:
                $tipo = "e cliente";
                break;
        }
        if (sizeof(
            DB::table("funcionarios")
                ->where("id_empresa", $id)
                ->where("lixeira", 0)
                ->get()
        )) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque existem pessoas vinculadas a ess".$tipo.".";
            $resultado->permitir = 0;
        } else if (sizeof(
            DB::table("concessoes")
                ->whereRaw($id." IN (id_franquia, id_franqueadora)")
                ->get()
        )) {
            $resultado->aviso = "Não é possível excluir ".$nome." porque ess".$tipo." faz parte de um processo de concessão.";
            $resultado->permitir = 0;
        } else {
            $resultado->aviso = "Tem certeza que deseja excluir ".$nome."?";
            $resultado->permitir = 1;
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Empresas::firstOrNew(["id" => $request->id]);
        $linha->nome_fantasia = mb_strtoupper($request->nome_fantasia);
        $linha->razao_social = mb_strtoupper($request->razao_social);
        $linha->cnpj = $request->cnpj;
        $linha->ie = $request->ie;
        $linha->email = mb_strtoupper($request->email);
        $linha->telefone = $request->telefone;
        $linha->tipo_contribuicao = $request->tipo_contribuicao;
        $linha->tipo = $request->tipo;
        $linha->royalties = $request->royalties;
        $linha->id_grupo = $request->id_grupo;
        $linha->id_segmento = $request->id_segmento;
        $linha->id_matriz = $request->id_matriz ? $request->id_matriz : 0;
        $linha->id_criadora = $this->retorna_empresa_logada(); // ControllerKX.php
        $linha->save();

        $ceps = explode($request->cep, ",");
        $numeros = explode($request->numeros, ",");
        $referencias = explode($request->referencias, ",");
        $complementos = explode($request->complementos, ",");

        for ($i = 0; $i < sizeof($ceps); $i++) {
            $where = "id_empresa = ".$request->id." AND id_cep IN (
                SELECT id
                FROM ceps
                WHERE cod = '".$ceps[$i]."'
            )";
            $this->log_inserir2("D", "enderecos", $where, "NULL"); // ControllerKX.php
            DB::statement("DELETE FROM enderecos WHERE ".$where);
            $linha = new Enderecos;
            $linha->id_cep = DB::table("ceps")
                                ->where("cod", $ceps[$i])
                                ->value("id");
            $linha->numero = $numeros[$i];
            $linha->referencia = $referencias[$i];
            $linha->complemento = $complementos[$i];
            $linha->save();
            $this->log_inserir("C", "enderecos", $linha->id); // ControllerKX.php
        }
        $this->log_inserir($request->id ? "E" : "C", "empresas", $linha->id); // ControllerKX.php
    }

    public function excluir(Request $request) {
        $linha = Empresas::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "empresas", $linha->id); // ControllerKX.php
    }
}