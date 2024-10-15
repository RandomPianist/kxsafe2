<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Retiradas;

class RetiradasController extends ControllerKX {
    public function consultar(Request $request) {
        return $this->retirada_consultar($request->atribuicao, $request->qtd); // ControllerKX.php
    }

    public function salvar(Request $request) {
        $linha = new Retiradas;
        $linha->id_supervisor = $request->id_supervisor;
        $linha->id_funcionario = $request->id_funcionario;
        $linha->id_atribuicao = $request->id_atribuicao;
        $linha->id_produto = $request->id_produto;        
        $linha->qtd = $request->qtd;
        $linha->save();
        $this->log_inserir("C", "retiradas", $linha->id); // ControllerKX.php
    }
}