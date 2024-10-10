<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Log;

class ControllerKX extends Controller {
    public function retorna_empresa_logada() {
        if (Auth::check()) {
            return DB::table("users")
                    ->where("id", Auth::user()->id)
                    ->value("id_empresa");
        }
        return 0;
    }

    protected function log_consultar($tabela, $tipo = 0) {
        $query = "
            SELECT
                IFNULL(users.name, CONCAT(
                    'API',
                    IFNULL(CONCAT(' - ', log.nome), '')
                )) AS nome,
                DATE_FORMAT(log.created_at, '%d/%m/%Y às %H:%i') AS data
            
            FROM log

            LEFT JOIN users
                ON users.id_aux = log.id_usuario
        ";
        switch($tabela) {
            case "empresas":
                $query .= "
                    JOIN empresas
                        ON empresas.id = log.fk

                    WHERE tipo = ".$tipo;
                break;
            default:
                $query .= "WHERE 1";
        }
        $query .= " AND tabela = '".$tabela."'";
        $consulta = DB::select(DB::raw($query));
        return sizeof($consulta) ? "Última atualização feita por ".$consulta[0]->nome." em ".$consulta[0]->data : "Nenhuma atualização feita";
    }

    protected function log_inserir($acao, $tabela, $fk, $api = false) {
        $linha = new Log;
        $linha->acao = $acao;
        $linha->tabela = $tabela;
        $linha->fk = $fk;
        if (!$api) $linha->id_pessoa = Auth::user()->id;
        $linha->save();
        return $linha;
    }

    protected function log_inserir2($acao, $tabela, $where, $nome, $api = false) {
        if ($nome != "NULL") $nome = "'".$nome."'";
        $sql = "INSERT INTO log (acao, tabela, nome, ";
        if (!$api) $sql .= "id_pessoa, ";
        $sql .= "fk) SELECT
            '".$acao."',
            '".$tabela."',
            ".$nome.",
        ";
        if (!$api) $sql .= Auth::user()->id_pessoa.",";
        $sql .= "
            id

            FROM ".$tabela."

            WHERE ".$where;
        DB::statement($sql);
    }

    protected function grupos_buscar($param = "1") {
        $minha_empresa = $this->retorna_empresa_logada();
        return DB::table("grupos")
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
                    ->where("lixeira", 0);
    }
}