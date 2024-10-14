<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class AtribuicoesController extends Controller {
    public function consultar(Request $request) {
        if (!sizeof(
            DB::table("itens")
                ->where("id", $request->id)
                ->where($request->coluna, $request->valor)
                ->get()
        )) return "1";
        return "0";
    }
}