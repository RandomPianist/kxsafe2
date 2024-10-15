<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class AtribuicoesController extends ControllerKX {
    public function consultar(Request $request) {
        if (!sizeof(
            DB::table("itens")
                ->where("id", $request->id)
                ->where($request->coluna, $request->valor)
                ->get()
        )) return "1";
        return "0";
    }

    public function produtos($id) {
        return json_encode(
            DB::table("itens")
                ->select(
                    "itens.id",
                    DB::raw("
                        CASE
                            WHEN produto_ou_referencia_chave = 'R' THEN CONCAT(descr, ' ', tamanho)
                            ELSE descr
                        END AS descr
                    "),
                    DB::raw("
                        CASE
                            WHEN produto_ou_referencia_chave = 'R' THEN referencia
                            ELSE descr
                        END AS titulo
                    ")
                )
                ->join("atribuicoes", function($join) {
                    $join->on(function($sql) {
                        $sql->on("produto_ou_referencia_valor", "cod_ou_id")
                            ->where("produto_ou_referencia_chave", "P");
                    })->orOn(function($sql) {
                        $sql->on("produto_ou_referencia_valor", "referencia")
                            ->where("produto_ou_referencia_chave", "R");
                    });
                })
                ->where("atribuicoes.id", $id)
                ->where("itens.lixeira", 0)
                ->where("atribuicoes.lixeira", 0)
                ->orderby("descr")
                ->get()
        );
    }
}