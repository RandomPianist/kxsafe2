<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\Itens;
use App\Models\LocaisItens;

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
        $meu_tipo = intval(Empresas::find($this->retorna_empresa_logada())->tipo);
        if (!in_array($meu_tipo, [1, 3])) return redirect("/"); // ControllerKX.php
        $breadcrumb = array(
            "Home" => config("app.root_url")."/home",
            "Itens" => "#"
        );
        $pode_editar = $meu_tipo == 1;
        $ultima_atualizacao = $this->log_consultar("itens"); // ControllerKX.php
        return view("itens", compact("ultima_atualizacao", "breadcrumb", "pode_editar"));
    }
    
    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("itens.descr LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("itens.descr LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(itens.descr LIKE '%".implode("%' AND itens.descr LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        $resultado = array();
        foreach ($busca as $item) {
            $aux = new \stdClass;
            $aux->id = $item->id;
            $aux->cod_ou_id = $item->cod_ou_id;
            $aux->descr = $item->descr;
            $aux->categoria = $item->categoria;
            $aux->preco = $item->preco;
            $aux->foto = $item->foto ? asset("storage/".$item->foto) : "";
            array_push($resultado, $aux);
        }
        return json_encode($resultado);
    }

    public function consultar(Request $request) {
        if (!sizeof(
            DB::table("empresas")
                ->where("lixeira", 0)
                ->where("id", $request->id_fornecedor)
                ->where("nome_fantasia", $request->fornecedor)
                ->get()
        ) && trim($request->fornecedor)) return "Fornecedor";
        if (!sizeof(
            DB::table("categorias")
                ->where("lixeira", 0)
                ->where("id", $request->id_categoria)
                ->where("descr", $request->categoria)
                ->get()
        ) && trim($request->categoria)) return "Categoria";
        if (Itens::find($request->id) !== null) {
            if (
                $this->atribuicoes_na_referencia(Itens::find($request->id)->referencia) == 1 && // ControllerKX.php
                !trim($request->referencia)
            ) return "aviso";
        }
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
                        ->where("itens.id", $id)
                        ->first();
        if ($item !== null) {
            $item->foto = asset("storage/".$item->foto);
            if ($item->validade_ca) $item->validade_ca = Carbon::createFromFormat("Y-m-d", $item->validade_ca)->format("d/m/Y");        }
        return view("itens_crud", compact("breadcrumb", "item"));
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        $resultado->aviso = "Tem certeza que deseja excluir ".Itens::find($id)->descr."?";
        $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function validade($id) {
        return Itens::find($id)->validade;
    }

    public function salvar(Request $request) {
        $linha = Itens::firstOrNew(["id" => $request->id]);
        $this->atribuicoes_atualizar($request->id, $linha->referencia, $request->referencia, "NULL", "R");
        $linha->descr = mb_strtoupper($request->descr);
        $linha->preco = $request->preco;
        $linha->validade = $request->validade;
        $linha->ca = $request->ca;
        $linha->id_categoria = $request->id_categoria;
        $linha->id_fornecedor = $request->id_fornecedor;
        $linha->referencia = $request->referencia;
        $linha->tamanho = $request->tamanho;
        $linha->detalhes = $request->detalhes;
        $linha->consumo = $request->consumo == "S" ? 1 : 0;
        $linha->validade_ca = Carbon::createFromFormat('d/m/Y', $request->validade_ca)->format('Y-m-d');
        if ($request->file("foto")) $linha->foto = $request->file("foto")->store("uploads", "public");
        $linha->save();
        $linha->cod_ou_id = $linha->id;
        $linha->save();
        $this->atribuicoes_atualizar($request->id, $linha->cod_externo, $linha->id, "NULL", "P"); // ControllerKX.php
        $this->log_inserir($request->id ? "E" : "C", "itens", $linha->id); // ControllerKX.php
        $locais = DB::table("locais")->pluck("id")->toArray();
        foreach ($locais as $local) {
            if (!sizeof(
                DB::table("locais_itens")
                    ->where("id_local", $local)
                    ->where("id_item", $linha->id)
                    ->get()
            )) {
                $li = new LocaisItens;
                $li->id_item = $linha->id;
                $li->id_local = $local;
                $li->save();
                $this->log_inserir("C", "locais_itens", $li->id); // ControllerKX.php
            }
        }
        return redirect("/itens");
    }

    public function excluir(Request $request) {
        $linha = Itens::find($request->id);
        $linha->lixeira = 1;
        $linha->save();
        $this->log_inserir("D", "itens", $linha->id); // ControllerKX.php

        $where = "produto_ou_referencia_chave = 'P' AND produto_ou_referencia_valor = '".$linha->cod_ou_id."'";
        DB::statement("
            UPDATE atribuicoes
            SET lixeira = 1
            WHERE ".$where
        );
        $this->log_inserir2("D", "atribuicoes", $where, "NULL"); // ControllerKX.php
    }
}