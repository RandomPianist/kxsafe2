<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Enderecos;

class EmpresasController extends ControllerKX {
    private function obter_filiais($matriz) {
        return DB::table("empresas")
                    ->where("id_matriz", $matriz)
                    ->where("lixeira", 0)
                    ->pluck("id");
    }

    private function busca_main($consulta, $matriz, $tipo, $id_grupo) {
        return $consulta->where("id_matriz", $matriz)
                        ->where("tipo", $tipo)
                        ->where("lixeira", 0)
                        ->where(function($sql) use($id_grupo) {
                            if (intval($id_grupo)) $sql->where("id_grupo", $id_grupo);
                        });
    }

    private function busca($matriz, $tipo, $id_grupo) {
        $minha_empresa = Auth::user()->id_empresa;
        $meu_tipo = intval(Empresas::find($minha_empresa)->tipo);
        $filiais = $this->obter_filiais($minha_empresa);
        $consulta = DB::table("empresas")
                        ->select(
                            "id",
                            "nome_fantasia",
                            "id_matriz"
                        );
        if (($meu_tipo == 1 && $tipo == 2) || ($meu_tipo == 2 && $tipo == 3)) {
            // Franqueadoras vendo franquias ou franquias vendo clientes
            // A franquia/o cliente deve ter sido criado(a) por mim ou uma das minhas filiais
            $eu_ou_filiais = [$minha_empresa];
            foreach ($filiais as $filial) array_push($eu_ou_filiais, $filial);
            $ids = $this->busca_main($consulta, $matriz, $tipo, $id_grupo)
                        ->whereIn("id_criadora", $eu_ou_filiais)
                        ->pluck("id")
                        ->toArray();
            // Verei todas as filiais desses clientes/franquias
            // mesmo que não tenham sido criados(as) por mim
            $empresas = $ids;
            foreach ($empresas as $empresa) {
                $filiais = $this->obter_filiais($empresa);
                foreach ($filiais as $filial) {
                    if (!in_array($filial, $ids)) array_push($ids, $filial);
                }
            }
            return $this->busca_main($consulta, $matriz, $tipo, $id_grupo)
                        ->whereIn("id", $ids)
                        ->orderby("nome_fantasia")
                        ->get();
        } else if (($meu_tipo == 2 || $meu_tipo == 3) && $meu_tipo == $tipo) {
            // Franquias vendo franquias ou clientes vendo clientes
            // Apenas a si mesmo(a); se for matriz, vê as próprias filiais
            return $this->busca_main($consulta, $matriz, $tipo, $id_grupo)
                        ->where(function($sql) use($minha_empresa, $filiais) {
                            $sql->where(function($query) use($minha_empresa, $filiais) {
                                $query->whereIn("id_matriz", $filiais->toArray());
                            })->orWhere("id", $minha_empresa);
                        })
                        ->orderby("nome_fantasia")
                        ->get();
        } else if ($meu_tipo == 1 && ($tipo == 1 || $tipo == 4)) {
            // Franqueadoras vendo franqueadoras ou fornecedores
            // Tudo, matrizes e filiais
            return $this->busca_main($consulta, $matriz, $tipo, $id_grupo)
                        ->orderby("nome_fantasia")
                        ->get();
        }
        return false;
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
        if ($this->busca(0, $tipo, 0) === false) return redirect("/");
        $titulo = $this->legenda($tipo);
        $ultima_atualizacao = $this->log_consultar("empresas", $tipo); // ControllerKX.php
        $breadcumb = array(
            "Home" => config("app.root_url"),
            $titulo => "#"
        );
        $empresas = array();
        $matrizes = $this->busca(0, $tipo, $id_grupo);
        foreach ($matrizes as $matriz) {
            $matriz->filiais = $this->busca($matriz->id, $tipo, $id_grupo);
            array_push($empresas, $matriz);
        }
        $grupos = $this->grupos_buscar()->orderby("descr")->get();
        return view("empresas", compact("ultima_atualizacao", "titulo", "breadcumb", "empresas", "grupos", "id_grupo"));
    }

    private function crud($tipo, $id) {
        if ($this->busca(0, $tipo, 0) === false) return redirect("/");
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
        ))."/grupo/0";
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