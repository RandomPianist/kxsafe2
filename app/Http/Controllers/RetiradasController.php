<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class RetiradasController extends ControllerKX {
    public function consultar(Request $request) {
        return $this->retirada_consultar($request->atribuicao, $request->qtd);
    }

    public function salvar(Request $request) {
        
    }
}