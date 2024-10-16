<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Retiradas;

class RetiradasController extends ControllerKX {
    public function consultar(Request $request) {
        return $this->retirada_consultar($request->atribuicao, $request->qtd); // ControllerKX.php
    }

    public function salvar(Request $request) {
        // ControllerKX.php
        $this->retirada_salvar(array(
            "id_maquina" => 0,
            "id_supervisor" => $request->id_supervisor,
            "id_atribuicao" => $request->id_atribuicao,
            "id_produto" => $request->id_produto,
            "qtd" => $request->qtd,
            "data" => Carbon::createFromFormat('d/m/Y', $request->data)->format('Y-m-d'),
        ));
    }
}