<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Atribuicoes;

class ApiController extends ControllerKX {
    private function retirar_main(Request $request) {
        $resultado = new \stdClass;
        $cont = 0;
        while (isset($request[$cont]["id_atribuicao"])) {
            $retirada = $request[$cont];
            $atribuicao = Atribuicoes::find($retirada["id_atribuicao"]);
            if ($atribuicao == null) {
                $resultado->code = 404;
                $resultado->msg = "Atribuição não encontrada";
                return json_encode($resultado);
            }
            $maquinas = DB::table("maquinas")->where("id", $retirada["id_maquina"])->get();
            if (!sizeof($maquinas)) {
                $resultado->code = 404;
                $resultado->msg = "Máquina não encontrada";
                return json_encode($resultado);
            }
            $concessao = DB::table("concessoes")->where("id_maquina", $retirada["id_maquina"])->get();
            if (!sizeof($concessao)) {
                $resultado->code = 404;
                $resultado->msg = "Essa máquina não está em nenhuma empresa";
                return json_encode($resultado);
            }
            if (!isset($retirada["id_supervisor"]) && !$this->retirada_consultar($retirada["id_atribuicao"], $retirada["qtd"])) { // ControllerKX.php
                $resultado->code = 401;
                $resultado->msg = "Essa quantidade de produtos não é permitida para essa pessoa";
                return json_encode($resultado);
            }
            if (floatval($retirada["qtd"]) > floatval(DB::table(DB::raw("(
                SELECT
                    CASE
                        WHEN (es = 'E') THEN qtd
                        ELSE qtd * -1
                    END AS qtd
                
                FROM estoque
    
                JOIN locais_itens AS li
                    ON li.id = estoque.id_li

                JOIN maquinas
                    ON maquinas.id_local = li.id_local
    
                WHERE maquinas.id = ".$maquinas[0]->id."
                  AND id_item = ".$retirada["id_produto"]."
            ) AS tab"))->selectRaw("SUM(qtd) AS qtd")->value("qtd"))) {
                $resultado->code = 500;
                $resultado->msg = "Essa quantidade de itens não está disponível em estoque";
                return json_encode($resultado);
            }
            $salvar = array(
                "id_pessoa" => $retirada["id_pessoa"],
                "id_produto" => $retirada["id_produto"],
                "id_atribuicao" => $retirada["id_atribuicao"],
                "id_comodato" => $comodato[0]->id,
                "qtd" => $retirada["qtd"],
                "data" => date("Y-m-d")
            );
            if (isset($retirada["id_supervisor"])) {
                $salvar += [
                    "id_supervisor" => $retirada["id_supervisor"],
                    "obs" => $retirada["obs"]
                ];
            }
            $this->retirada_salvar($salvar);
            $linha = new Estoque;
            $linha->es = "S";
            $linha->descr = "RETIRADA";
            $linha->qtd = $retirada["qtd"];
            $linha->id_mp = DB::table("maquinas_produtos")
                                ->where("id_produto", $retirada["id_produto"])
                                ->where("id_maquina", $maquinas[0]->id)
                                ->value("id");
            $linha->save();
            $this->log_inserir("C", "estoque", $linha->id, true);
            $cont++;
        }
        $resultado->code = 201;
        $resultado->msg = "Sucesso";
        return json_encode($resultado);
    }

    public function validar_app(Request $request) {
        return sizeof(
            DB::table("pessoas")
                ->where("cpf", $request->cpf)
                ->where("senha", $request->senha)
                ->where("lixeira", 0)
                ->get()
        ) ? 1 : 0;
    }

    public function ver_pessoa(Request $request) {
        return json_encode(
            DB::table("pessoas")
                ->where("cpf", $request->cpf)
                ->first()
        );
    }

    public function produtos_por_pessoa(Request $request) {
        $consulta = DB::table("produtos")
                        ->select(
                            "produtos.id",
                            "produtos.referencia",
                            "produtos.descr AS nome",
                            "produtos.detalhes",
                            "produtos.cod_externo AS codbar",
                            DB::raw("IFNULL(produtos.tamanho, '') AS tamanho"),
                            DB::raw("IFNULL(produtos.foto, '') AS foto"),
                            "atribuicoes.id AS id_atribuicao",
                            "atribuicoes.obrigatorio",
                            DB::raw("(atribuicoes.qtd - IFNULL(ret.qtd, 0)) AS qtd"),
                            DB::raw("IFNULL(ret.ultima_retirada, '') AS ultima_retirada"),
                            DB::raw("DATE_FORMAT(IFNULL(ret.proxima_retirada, CURDATE()), '%d/%m/%Y') AS proxima_retirada")
                        )->join("atribuicoes", "atribuicoes.id", DB::raw("(
                            SELECT atribuicoes.id
                            
                            FROM atribuicoes

                            JOIN pessoas
                                ON (atribuicoes.pessoa_ou_setor_chave = 'P' AND atribuicoes.pessoa_ou_setor_valor = pessoas.id)
                                    OR (atribuicoes.pessoa_ou_setor_chave = 'S' AND atribuicoes.pessoa_ou_setor_valor = pessoas.id_setor)
                            
                            WHERE (
                                (produto_ou_referencia_chave = 'P' AND produto_ou_referencia_valor = produtos.cod_externo)
                            OR (produto_ou_referencia_chave = 'R' AND produto_ou_referencia_valor = produtos.referencia)
                            ) AND pessoas.cpf = '".$request->cpf."'
                              AND pessoas.lixeira = 0
                              AND atribuicoes.lixeira = 0

                            ORDER BY pessoa_ou_setor_chave

                            LIMIT 1
                        )"))->leftjoinSub(
                            DB::table("retiradas")
                                ->select(
                                    DB::raw("SUM(retiradas.qtd) AS qtd"),
                                    "id_atribuicao",
                                    DB::raw("DATE_FORMAT(MAX(retiradas.data), '%d/%m/%Y') AS ultima_retirada"),
                                    DB::raw("DATE_ADD(MAX(retiradas.data), INTERVAL atribuicoes.validade DAY) AS proxima_retirada")
                                )
                                ->join("atribuicoes", "atribuicoes.id", "retiradas.id_atribuicao")
                                ->whereRaw("DATE_ADD(retiradas.data, INTERVAL atribuicoes.validade DAY) >= CURDATE()")
                                ->groupby(
                                    "id_atribuicao",
                                    "atribuicoes.validade"
                                ),
                        "ret", "ret.id_atribuicao", "atribuicoes.id")
                        ->get();
        $resultado = array();
        foreach ($consulta as $linha) {
            if ($linha->foto) {
                $foto = explode("/", $linha->foto);
                $linha->foto = $foto[sizeof($foto) - 1];
            }
            array_push($resultado, $linha);
        }
        return json_encode(collect($resultado)->groupBy("referencia")->map(function($itens) use($request) {
            return [
                "id_pessoa" => DB::table("pessoas")->where("cpf", $request->cpf)->value("id"),
                "nome" => $itens[0]->nome,
                "foto" => $itens[0]->foto,
                "referencia" => $itens[0]->referencia,
                "qtd" => $itens[0]->qtd,
                "detalhes" => $itens[0]->detalhes,
                "ultima_retirada" => $itens[0]->ultima_retirada,
                "proxima_retirada" => $itens[0]->proxima_retirada,
                "obrigatorio" => $itens[0]->obrigatorio,
                "tamanhos" => $itens->map(function($tamanho) use($request) {
                    return [
                        "id" => $tamanho->id,
                        "id_pessoa" => DB::table("pessoas")->where("cpf", $request->cpf)->value("id"),
                        "id_atribuicao" => $tamanho->id_atribuicao,
                        "selecionado" => false,
                        "codbar" => $tamanho->codbar,
                        "numero" => $tamanho->tamanho ? $tamanho->tamanho : "UN"
                    ];
                })->values()->all()
            ];
        })->sortBy("nome")->values()->all());
    }

    public function retirar(Request $request) {
        return $this->retirar_main($request);
    }

    public function validar_spv(Request $request) {
        return $this->supervisor_consultar($request); // ControllerKX.php
    }

    public function retirar_com_supervisao(Request $request) {
        return $this->retirar_main($request);
    }
}