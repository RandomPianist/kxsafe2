<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Cep;

class CepController extends ControllerKX {
    public function mostrar($cep) {
        $resultado = new \stdClass;
        $consulta = DB::table("cep")
                        ->where("cod", $cep)
                        ->get();
        if (sizeof($consulta)) {
            $resultado->cod = 200;
            $resultado->cep = $consulta[0];
        } else $resultado->cod = 404;
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $linha = new Cep;
        $linha->cod = $request->cod;
        $linha->logradouro_tipo = $request->logradouro_tipo;
        $linha->logradouro_tipo_abv = $request->logradouro_tipo_abv;
        $linha->logradouro_descr = $request->logradouro_descr;
        // $linha->logradouro_intervalo_min = $request->logradouro_intervalo_min;
        // $linha->logradouro_intervalo_max = $request->logradouro_intervalo_max;
        // $linha->cod_ibge_uf = $request->cod_ibge_uf;
        $linha->cod_ibge_cidade = $request->cod_ibge_cidade;
        $linha->cidade = $request->cidade;
        $linha->bairro = $request->bairro;
        $linha->estado = $request->estado;
        $linha->uf = $request->uf;
        $linha->save();
        $this->log_inserir("C", "cep", $linha->id); // ControllerKX.php
    }
}