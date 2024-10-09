<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Enderecos;

class EmpresasController extends ControllerKX {
    private function busca($param, $tipo, $id_grupo) {
        return DB::table("empresas")
                ->select(
                    "id",
                    "nome_fantasia",
                    "id_matriz",
                    DB::raw("
                        CASE
                            WHEN ".Auth::user()->id_empresa." = id THEN 'S'
                            ELSE 'N'
                        END AS proprio
                    ")
                )
                ->where("lixeira", 0)
                ->where("tipo", $tipo)
                ->where(function($sql) use($id_grupo) {
                    if ($id_grupo) $sql->where("id_grupo", $id_grupo);
                })
                ->where(function($sql) {
                    $sql->whereRaw(Auth::user()->id_empresa." IN (id, id_criadora)");
                })
                ->where("id_matriz", $param, 0)
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

    private function ver($tipo) {
        $titulo = $this->legenda($tipo);
        $ultima_atualizacao = $this->log_consultar("empresas", $tipo);
        $breadcumb = array(
            "Home" => config("app.root_url"),
            $titulo => "#"
        );
        return view("empresas", compact("ultima_atualizacao", "titulo", "breadcumb"));
    }

    private function crud($tipo, $id) {
        $titulo = $this->legenda($tipo);
        $breadcumb = array(
            "Home" => config("app.root_url"),
            $titulo => config("app.root_url")."/".strtolower($titulo),
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $empresa = DB::table("empresas")
                        ->select(
                            "empresas.*",
                            DB::raw("IFNULL(segmentos.descr, '') AS segmento"),
                            DB::raw("IFNULL(grupos.descr, '') AS grupo")
                        )
                        ->leftjoin("segmentos", "segmentos.id", "empresas.id_segmento")
                        ->leftjoin("grupos", "grupos.id", "empresas.id_grupo")
                        ->where("id", $id)
                        ->first();
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
                                    ->get();
        return view("empresas_crud", compact("titulo", "breadcumb", "empresa"));
    }

    private function selecionarMain($empresa) {
        DB::statement("
            UPDATE users
            SET id_empresa = ".$empresa."
            WHERE id = ".Auth::user()->id
        );
        return strtolower($this->legenda(Empresas::find(
            DB::table("users")
                ->where("id", Auth::user()->id)
                ->value("id_empresa")
            )->tipo
        ));
    }

    public function selecionar(Request $request) {
        return redirect("/".$this->selecionarMain($request->id_empresa));
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

    public function franqueadoras() {
        return $this->ver(1);
    }

    public function franquias() {
        return $this->ver(2);
    }

    public function clientes() {
        return $this->ver(3);
    }

    public function fornecedores() {
        return $this->ver(4);
    }

    public function franqueadoras_crud($id) {
        return $this->crud(1, $id);
    }

    public function franquias_crud($id) {
        return $this->crud(2, $id);
    }

    public function clientes_crud($id) {
        return $this->crud(3, $id);
    }

    public function fornecedores_crud($id) {
        return $this->crud(4, $id);
    }

    public function listar(Request $request) {
        $id_grupo = 0;
        if (isset($request->id_grupo)) $id_grupo = $request->id_grupo;
        $resultado = new \stdClass;
        $resultado->inicial = $this->busca("=", $request->tipo, $id_grupo);
        $resultado->final = $this->busca(">", $request->tipo, $id_grupo);
        return json_encode($resultado);
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
        $linha->id_criadora = Auth::user()->id_empresa;
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
            $this->log_inserir2("D", "enderecos", $where, "NULL");
            DB::statement("DELETE FROM enderecos WHERE ".$where);
            $linha = new Enderecos;
            $linha->id_cep = DB::table("ceps")
                                ->where("cod", $ceps[$i])
                                ->value("id");
            $linha->numero = $numeros[$i];
            $linha->referencia = $referencias[$i];
            $linha->complemento = $complementos[$i];
            $linha->save();
            $this->log_inserir("C", "enderecos", $linha->id);
        }
        $this->log_inserir($request->id ? "E" : "C", "empresas", $linha->id);
    }

    public function excluir(Request $request) {
        $linha = Empresas::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "empresas", $linha->id);
    }
}