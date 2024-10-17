<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Concessoes;

class ConcessoesController extends ControllerKX {
    private function ultimo($id_maquina) {
        $resultado = new \stdClass;
        $consulta = DB::table("concessoes")
                        ->where("id_maquina", $id_maquina)
                        ->where("lixeira", 0)
                        ->whereNotNull("fim");
        if (sizeof($consulta->get())) {
            $resultado->data = Carbon::createFromFormat('Y-m-d', $consulta->value("fim"))->format('d/m/Y');
            $resultado->permitir = strtotime($consulta->value("fim")) <= strtotime(date('Y-m-d')) ? 1 : 0;
        } else $resultado->permitir = 1;
        return json_encode($resultado);
    }

    public function conceder(Request $request) {
        $inicio = Carbon::createFromFormat('d/m/Y', $request->inicio)->format('Y-m-d');
        $consulta = DB::table("concessoes")
                        ->where("id_maquina", $request->id_maquina)
                        ->where("lixeira", 0)
                        ->whereNotNull("fim");
        if (sizeof($consulta->get())) {
            $de_para = DB::table("concessoes")
                            ->select(
                                "de.nome_fantasia AS de",
                                "para.nome_fantasia AS para"
                            )
                            ->leftjoin("empresas AS de", "de.id", "concessoes.id_de")
                            ->leftjoin("empresas AS para", "para.id", "concessoes.id_para")
                            ->where("concessoes.id", $consulta->value("id"))
                            ->first();
            $msg = "Essa máquina está concedida de ".$de_para->de." para ".$de_para->para." até ".Carbon::createFromFormat('Y-m-d', $consulta->value("fim"))->format('d/m/Y').".<br />A data de início deve ser posterior a essa.";
            if (strtotime($inicio) < strtotime($consulta->value("fim"))) return $msg;
            if (sizeof($consulta->whereNotNull("inicio")->get())) {
                if (strtotime($inicio) < strtotime($consulta->whereNotNull("inicio")->value("inicio"))) return $msg;
            }
        }
        $linha = new Concessoes;
        $linha->taxa_inicial = $request->taxa_inicial;
        $linha->inicio = $inicio;
        $linha->id_de = $request->id_de;
        $linha->id_para = $request->id_para;
        $linha->id_maquina = $request->id_maquina;
        $linha->save();
        $this->log_inserir("C", "concessoes", $linha->id); // ControllerKX.php
        return "ok";
    }

    public function encerrar(Request $request) {
        $where = "id_maquina = ".$request->id_maquina;
        DB::statement("UPDATE concessoes SET fim = '".Carbon::createFromFormat('d/m/Y', $request->fim)->format('Y-m-d')."' WHERE ".$where);
        $this->log_inserir2("E", "concessoes", $where, "NULL"); // ControllerKX.php
    }
}