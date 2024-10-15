<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Estoque;

class EstoqueController extends ControllerKX {
    public function consultar(Request $request) {
        $texto = "";
        $campos = array();
        $valores = array();

        $itens_id = explode(",", $request->itens_id);
        $itens_descr = explode(",", $request->itens_descr);
        $quantidades = explode(",", $request->quantidades);
        $es = explode(",", $request->es);

        for ($i = 0; $i < sizeof($itens_id); $i++) {
            if (!sizeof(
                DB::table("itens")
                    ->where("id", $itens_id[$i])
                    ->where("descr", $itens_descr[$i])
                    ->where("lixeira", 0)
                    ->get()
            )) {
                array_push($campos, "item-".($i + 1));
                array_push($valores, $itens_descr[$i]);
                $texto = !$texto ? "Itens não encontrados" : "Item não encontrado";
            }
        }

        if (!$texto) {
            for ($i = 0; $i < sizeof($itens_id); $i++) {
                $consulta = DB::table(DB::raw("(
                    SELECT
                        CASE
                            WHEN (es = 'E') THEN qtd
                            ELSE qtd * -1
                        END AS qtd,
                        id_li

                    FROM estoque
                ) AS estq"))->selectRaw("IFNULL(SUM(qtd), 0) AS saldo")
                    ->join("locais_itens AS li", "li.id", "estq.id_li")
                    ->where("li.id_local", $request->id_local)
                    ->where("li.id_item", $itens_id[$i])
                    ->get();
                $erro = !sizeof($consulta);
                if (!$erro) {
                    $valor = floatval($quantidades[$i]);
                    if ($es[$i] == "S") $valor *= -1;
                    $erro = (floatval($consulta[0]->saldo) + $valor) < 0;
                }
                if ($erro) {
                    array_push($campos, "qtd-".($i + 1));
                    array_push($valores, floatval($consulta[0]->saldo) * 1);
                    $linha2 = !$texto ? "Os campos foram corrigidos" : "O campo foi corrigido";
                    $linha2 .= " para zerar o estoque.<br>Por favor, verifique e tente novamente.";
                    $texto = "Essa movimentação de estoque provocaria estoque negativo.<br>".$linha2;
                }
            }
        }

        $resultado = new \stdClass;
        $resultado->texto = $texto;
        $resultado->campos = $campos;
        $resultado->valores = $valores;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        for ($i = 0; $i < sizeof($request->id_item); $i++) {
            $linha = new Estoque;
            $linha->es = $request->es[$i];
            $linha->descr = $request->obs[$i];
            $linha->qtd = $request->qtd[$i];
            $linha->id_li = DB::table("locais_itens")
                                ->where("id_item", $request->id_item[$i])
                                ->where("id_local", $request->id_local)
                                ->value("id");
            $linha->save();
            $this->log_inserir("C", "estoque", $linha->id); // ControllerKX.php
        }
        return redirect("/locais");
    }
}