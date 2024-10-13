<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Itens;

class ItensController extends ControllerKX {
    private function busca($param = "1") {
        return DB::table("itens")
                    ->select(
                        DB::raw("itens.*"),
                        DB::raw("
                            CASE
                                WHEN categorias.descr IS NULL OR categorias.descr = '' THEN 'A CLASSIFICAR'
                                ELSE categorias.descr
                            END AS categoria
                        ")
                    )
                    ->leftjoin("categorias", "categorias.id", "itens.id_categoria")
                    ->whereRaw($param)
                    ->where("itens.lixeira", 0)
                    ->get();
    }

    public function ver() {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Itens" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("itens"); // ControllerKX.php
        return view("itens", compact("ultima_atualizacao", "breadcrumb"));
    }
    
    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("itens.descr LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("itens.descr LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(itens.descr LIKE '%".implode("%' AND itens.descr LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        return json_encode($busca);
    }

    public function consultar(Request $request) {
        if (!sizeof(
            DB::table("empresas")
                ->where("lixeira", 0)
                ->where("id", $request->id_fornecedor)
                ->where("nome_fantasia", $request->fornecedor)
        )) return "Fornecedor";
        if (!sizeof(
            DB::table("categorias")
                ->where("lixeira", 0)
                ->where("id", $request->id_categoria)
                ->where("nome_fantasia", $request->categoria)
        )) return "Categoria";
        if (
            $this->atribuicoes_na_referencia(Itens::find($request->id)->referencia) == 1 && // ControllerKX.php
            !trim($request->referencia)
        ) return "aviso";
        return "0";
    }

    public function crud($id) {
        if (!in_array(intval(Empresas::find($this->retorna_empresa_logada())->tipo), [1])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Itens" => config("app.root_url")."/itens",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $item = DB::table("itens")
                        ->select(
                            "itens.*",
                            "categorias.descr AS categoria",
                            "empresas.nome_fantasia AS fornecedor"
                        )
                        ->leftjoin("categorias", "categorias.id", "itens.id_categoria")
                        ->leftjoin("empresas", "empresas.id", "itens.id_fornecedor")
                        ->where("id", $id)
                        ->first();
        if ($item !== null) {
            $item->foto = asset("storage/".$item->foto);
            $item->validade_ca = Carbon::createFromFormat('Y-m-d', $item->validade_ca)->format('d/m/Y');
        }
        return view("itens_crud", compact("breadcrumb", "item"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Itens::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = Itens::firstOrNew(["id" => $request->id]);
        $this->atribuicoes_atualizar($request->id, $linha->referencia, $request->referencia, "NULL", "R");
        $linha->descr = mb_strtoupper($request->descr);
        $linha->preco = $request->preco;
        $linha->validade = $request->validade;
        $linha->ca = $request->ca;
        $linha->id_categoria = $request->id_categoria;
        $linha->referencia = $request->referencia;
        $linha->tamanho = $request->tamanho;
        $linha->detalhes = $request->detalhes;
        $linha->consumo = $request->consumo == "S" ? 1 : 0;
        $linha->validade_ca = Carbon::createFromFormat('d/m/Y', $request->validade_ca)->format('Y-m-d');
        if ($request->file("foto")) $linha->foto = $request->file("foto")->store("uploads", "public");
        $linha->save();
        $linha->cod_ou_id = $linha->id;
        $linha->save();
        $this->atribuicoes_atualizar($request->id, $linha->cod_externo, $linha->id, "NULL", "P");
        $this->log_inserir($request->id ? "E" : "C", "itens", $linha->id);
        // $this->mov_estoque($linha->id, false);
        return redirect("/itens");
    }

    public function excluir(Request $request) {
        $linha = Itens::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "itens", $linha->id);  // ControllerKX.php
    }
}