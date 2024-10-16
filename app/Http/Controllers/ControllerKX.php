<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Empresas;
use App\Models\Atribuicoes;
use App\Models\Itens;

class ControllerKX extends Controller {
    public function retorna_empresa_logada() {
        if (Auth::check()) {
            return DB::table("users")
                    ->where("id", Auth::user()->id)
                    ->value("id_empresa");
        }
        return 0;
    }

    public function empresas_legenda($tipo) {
        $legenda = "";
        switch($tipo) {
            case 1:
                $legenda = "Nova franqueadora";
                break;
            case 2:
                $legenda = "Nova franquia";
                break;
            case 3:
                $legenda = "Novo cliente";
                break;
            case 4:
                $legenda = "Novo fornecedor";
                break;
        }
        return $legenda;
    }

    public function empresas_legenda2($tipo) {
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

    public function empresas_url() {
        return strtolower($this->empresas_legenda2(Empresas::find($this->retorna_empresa_logada())->tipo))."/grupo/0";
    }

    protected function log_consultar($tabela, $tipo = 0) {
        $query = "
            SELECT
                IFNULL(users.name, CONCAT(
                    'API',
                    IFNULL(CONCAT(' - ', log.nome), '')
                )) AS nome,
                DATE_FORMAT(log.created_at, '%d/%m/%Y') AS data
                /*DATE_FORMAT(log.created_at, '%d/%m/%Y às %H:%i') AS data*/
            
            FROM log

            LEFT JOIN users
                ON users.id_aux = log.id_usuario
        ";
        if (in_array($tabela, ["setores", "funcionarios", "empresas", "grupos", "segmentos"])) {
            $query .= " JOIN ".$tabela." ON ".$tabela.".id = log.fk";
            if ($tabela == "empresas") $query .= " WHERE tipo = ".$tipo;
            else $query .= " WHERE ".$tabela.".id_empresa IN (".implode(",", $this->empresas_acessiveis()).")";
        } else $query .= " WHERE 1 ";
        $query .= " AND tabela = '".$tabela."'";
        $consulta = DB::select(DB::raw($query));
        return sizeof($consulta) ? "Última atualização feita por ".$consulta[0]->nome." em ".$consulta[0]->data : "Nenhuma atualização feita";
    }

    protected function log_inserir($acao, $tabela, $fk, $api = false) {
        $linha = new Log;
        $linha->acao = $acao;
        $linha->tabela = $tabela;
        $linha->fk = $fk;
        if (!$api) $linha->id_usuario = Auth::user()->id;
        $linha->save();
        return $linha;
    }

    protected function log_inserir2($acao, $tabela, $where, $nome, $api = false) {
        if ($nome != "NULL") $nome = "'".$nome."'";
        $sql = "INSERT INTO log (acao, tabela, nome, ";
        if (!$api) $sql .= "id_usuario, ";
        $sql .= "fk) SELECT
            '".$acao."',
            '".$tabela."',
            ".$nome.",
        ";
        if (!$api) $sql .= Auth::user()->id.",";
        $sql .= "
            id

            FROM ".$tabela."

            WHERE ".$where;
        DB::statement($sql);
    }

    protected function empresas_acessiveis() {
        $minha_empresa = $this->retorna_empresa_logada();
        $meu_tipo = intval(Empresas::find($minha_empresa)->tipo);
        $ids = array();
        $query = "
            SELECT empresas.id
            FROM empresas
        ";
        $query .= $meu_tipo < 3 ? "
            JOIN (
                SELECT id

                FROM empresas
                
                WHERE ".($meu_tipo == 1 ?
                    "tipo = 1 OR tipo = 4" 
                :
                    "id = ".$minha_empresa." OR id_matriz = ".$minha_empresa
                )."
                
                UNION ALL (
                    SELECT empresas.id

                    FROM empresas

                    JOIN (
                        SELECT empresas.id

                        FROM empresas

                        JOIN (
                            SELECT id
                            FROM empresas
                            WHERE id = ".$minha_empresa."
                               OR id_matriz = ".$minha_empresa."
                        ) AS aux3 ON aux3.id = id_criadora
                    ) AS aux2 ON aux2.id = empresas.id OR aux2.id = empresas.id_matriz
                )
            ) AS aux ON aux.id = empresas.id

            WHERE lixeira = 0

            GROUP BY empresas.id
        " : " WHERE (id = ".$minha_empresa." OR id_matriz = ".$minha_empresa.") AND lixeira = 0";
        return DB::table(DB::raw("(".$query.") AS tab"))->pluck("id")->toArray();
    }

    protected function empresas_consultar(Request $request) {
        return (!sizeof(
            DB::table("empresas")
                ->where("id", $request->id_empresa)
                ->where("nome_fantasia", $request->empresa)
                ->where("lixeira", 0)
                ->get()
        ));
    }

    protected function grupos_buscar($param = "1") {
        $minha_empresa = $this->retorna_empresa_logada();
        return DB::table("grupos")
                    ->select(
                        "id",
                        "descr"
                    )
                    ->whereRaw($param)
                    ->whereIn("id_empresa", $this->empresas_acessiveis())
                    ->where("lixeira", 0);
    }

    protected function atribuicoes($funcionario_ou_setor_chave, $funcionario_ou_setor_valor) {
        return DB::table("atribuicoes")
                    ->select(
                        DB::raw("
                            CASE
                                WHEN produto_ou_referencia_chave = 'P' THEN descr
                                ELSE referencia
                            END AS descr
                        "),
                        "atribuicoes.id",
                        "qtd",
                        "atribuicoes.validade",
                        "obrigatorio",
                        "produto_ou_referencia_chave",
                        "produto_ou_referencia_valor"
                    )
                    ->join("itens", function($join) {
                        $join->on(function($sql) {
                            $sql->on("produto_ou_referencia_valor", "cod_ou_id")
                                ->where("produto_ou_referencia_chave", "P");
                        })->orOn(function($sql) {
                            $sql->on("produto_ou_referencia_valor", "referencia")
                                ->where("produto_ou_referencia_chave", "R");
                        });
                    })
                    ->where("funcionario_ou_setor_chave", $funcionario_ou_setor_chave)
                    ->where("funcionario_ou_setor_valor", $funcionario_ou_setor_valor)
                    ->where("atribuicoes.lixeira", 0)
                    ->where("itens.lixeira", 0)
                    ->groupby(
                        "produto_ou_referencia_chave",
                        DB::raw("
                            CASE
                                WHEN produto_ou_referencia_chave = 'P' THEN descr
                                ELSE referencia
                            END
                        "),
                        "atribuicoes.id",
                        "qtd",
                        "atribuicoes.validade",
                        "obrigatorio",
                        "produto_ou_referencia_chave",
                        "produto_ou_referencia_valor"
                    )
                    ->get();
    }

    protected function atribuicoes_na_referencia($referencia) {
        return sizeof(
            DB::table("atribuicoes")
                ->where("lixeira", 0)
                ->where("produto_ou_referencia_valor", $referencia)
                ->where("produto_ou_referencia_chave", "R")
                ->get()
        );
    }

    protected function atribuicoes_atualizar($id, $antigo, $novo, $nome, $chave, $api = false) {
        if ($id) {
            $novo = trim($novo);
            $where = "produto_ou_referencia_valor = '".$antigo."' AND produto_ou_referencia_chave = '".$chave."'";
            if ($novo) {
                DB::statement("
                    UPDATE atribuicoes
                    SET produto_ou_referencia_valor = '".$novo."'
                    WHERE ".$where
                );
                $this->log_inserir2("E", "atribuicoes", $where, $nome, $api);
            } else if ($chave == "R" && $this->atribuicoes_na_referencia($antigo) == 1) {
                DB::statement("
                    UPDATE atribuicoes
                    SET lixeira = 1
                    WHERE ".$where
                );
                $this->log_inserir2("D", "atribuicoes", $where, $nome, $api);
            }
        }
    }

    protected function atribuicoes_salvar(Request $request, $funcionario_ou_setor_chave, $funcionario_ou_setor_valor) {
        foreach (array("prod" => array(
            "id" => $request->atb_prod_id,
            "valor" => $request->atb_prod_valor,
            "qtd" => $request->atb_prod_qtd,
            "validade" => $request->atb_prod_validade,
            "obrigatorio" => $request->atb_prod_obrigatorio,
            "operacao" => $request->atb_prod_operacao
        ), "refer" => array(
            "id" => $request->atb_refer_id,
            "valor" => $request->atb_refer_valor,
            "qtd" => $request->atb_refer_qtd,
            "validade" => $request->atb_refer_validade,
            "obrigatorio" => $request->atb_refer_obrigatorio,
            "operacao" => $request->atb_refer_operacao
        )) as $tipo => $atb) {
            foreach ($atb as &$parte) $parte = explode("|", $parte);
            foreach ($atb["operacao"] as $i => $operacao) {
                $atribuicao = null;
                switch ($operacao) {
                    case "C":
                        $item = Itens::find($atb["valor"][$i]);
                        if ($tipo == "prod") {    
                            $item->cod_ou_id = $atb["valor"][$i];
                            $item->save();
                            $this->log_inserir("E", "itens", $atb["valor"][$i]);
                        }
                        $atribuicao = new Atribuicoes;
                        $atribuicao->funcionario_ou_setor_chave = $funcionario_ou_setor_chave;
                        $atribuicao->funcionario_ou_setor_valor = $funcionario_ou_setor_valor;
                        $atribuicao->produto_ou_referencia_chave = $tipo == "prod" ? "P" : "R";
                        $atribuicao->produto_ou_referencia_valor = $tipo == "prod" ? $atb["valor"][$i] : $item->referencia;
                        $atribuicao->qtd = $atb["qtd"][$i];
                        $atribuicao->validade = $atb["validade"][$i];
                        $atribuicao->obrigatorio = $atb["obrigatorio"][$i] == "Sim" ? 1 : 0;
                        $atribuicao->save();
                        $this->log_inserir("C", "atribuicoes", $atribuicao->id);
                        break;
                    case "D":
                        if (intval($atb["id"][$i])) {
                            $atribuicao = Atribuicoes::find($atb["id"][$i]);
                            $atribuicao->lixeira = 1;
                            $atribuicao->save();
                            $this->log_inserir("D", "atribuicoes", $atribuicao->id);
                        }
                        break;
                }
            }
        }
    }

    protected function supervisor_consultar(Request $request) {
        $consulta = DB::table("funcionarios")
                        ->where("cpf", $request->cpf)
                        ->where("senha", $request->senha)
                        ->where("supervisor", 1)
                        ->where("lixeira", 0)
                        ->get();
        return sizeof($consulta) ? $consulta[0]->id : 0;
    }

    protected function concessoes_excluir($where) {
        DB::statement("UPDATE concessoes SET fim = CURDATE() WHERE ".$where." AND CURDATE() >= inicio");
        DB::statement("UPDATE concessoes SET lixeira = 1     WHERE ".$where." AND CURDATE() <  inicio");
        // log
    }

    protected function retirada_consultar($id_atribuicao, $qtd) {
        $atribuicao = Atribuicoes::find($id_atribuicao);
        $ja_retirados = DB::table("retiradas")
                            ->selectRaw("IFNULL(SUM(retiradas.qtd), 0) AS qtd")
                            ->join("atribuicoes", "atribuicoes.id", "retiradas.id_atribuicao")
                            ->whereRaw("DATE_ADD(retiradas.data, INTERVAL atribuicoes.validade DAY) >= CURDATE()")
                            ->where("atribuicoes.id", $id_atribuicao)
                            ->get();
        if (floatval($atribuicao->qtd) < (floatval($qtd) + (sizeof($ja_retirados) ? floatval($ja_retirados[0]->qtd) : 0))) return 0;
        return 1;
    }

    protected function retirada_salvar($json) {
        $linha = new Retiradas;
        if (isset($json["obs"])) $linha->obs = $json["obs"];
        if (isset($json["id_supervisor"])) {
            if (intval($json["id_supervisor"])) $linha->id_supervisor = $json["id_supervisor"];
        }
        $linha->id_funcionario = $json["id_funcionario"];
        $linha->id_atribuicao = $json["id_atribuicao"];
        $linha->id_produto = $json["id_produto"];
        $linha->id_maquina = $json["id_maquina"];
        $linha->qtd = $json["qtd"];
        $linha->data = $json["data"];
        $linha->save();
        $api = $json["id_maquina"] > 0;
        $modelo = $this->log_inserir("C", "retiradas", $linha->id, $api);
        if ($api) {
            $modelo->nome = "APP";
            $modelo->save();
        }
    }
}