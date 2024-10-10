<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Models\EmpresasUsuarios;

class UsuariosController extends ControllerKX {
    private function busca($param = "1") {
        $minha_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
        $param2 = str_replace("name", "email", $param);
        return DB::table("users")
                    ->select(
                        "users.id",
                        "users.name",
                        "users.email",
                        "users.foto"
                    )
                    ->leftjoin("empresas_usuarios AS eu", "eu.id_usuario", "users.id_aux")
                    ->whereRaw("((".$param.") OR (".$param2."))")
                    ->where(function($sql) use($minha_empresa) {
                        $tipo = Empresas::find($minha_empresa)->tipo;
                        if ($tipo > 1) {
                            $sql->whereIn("eu.id_empresa", DB::table("empresas")->where($tipo == 2 ? "id_criadora" : "id_matriz", $minha_empresa)->pluck("id")->toArray())
                                ->orWhere("eu.id_empresa", $minha_empresa);
                        }
                    })
                    ->groupby(
                        "id",
                        "name",
                        "email",
                        "foto"
                    )
                    ->orderby("name")
                    ->get();
    }

    public function ver() {
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Usuários" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("users"); // ControllerKX.php
        return view("usuarios", compact("ultima_atualizacao", "breadcumb"));
    }

    public function crud($id) {
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Usuários" => config("app.root_url")."/usuarios",
            (intval($id) ? "Editar" : "Novo") => "#"
        );
        $usuario = DB::table("users")
                        ->select(
                            "id",
                            "name",
                            "email",
                            "foto"
                        )
                        ->where("id", $id)
                        ->first();
        return view("usuarios_crud", compact("breadcumb", "usuario"));
    }

    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("name LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("name LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(name LIKE '%".implode("%' AND name LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        return json_encode($busca);
    }

    public function consultar(Request $request) {
        if (sizeof(
            DB::table("users")
                ->where("email", $request->email)
                ->get()
        )) return "1";
        return "0";
    }

    public function aviso($id) {
        $resultado = new \stdClass;
        if ($id == Auth::user()->id) {
            $resultado->permitir = 0;
            $resultado->aviso = "Não é possível excluir a si mesmo.";
        } else {
            $resultado->permitir = 1;
            $resultado->aviso = "Tem certeza que deseja excluir ".Auth::user()->name."?";
        }
        return json_encode($resultado);
    }

    public function salvar(Request $request) {
        $senha = Hash::make($request->senha);
        $foto = $request->file("foto") ? "foto = '".$request->file("foto")->store("uploads", "public")."'," : "";
        if (!$request->id) {
            DB::statement("INSERT INTO users (name, email, password) VALUES ('".mb_strtoupper($request->nome)."', '".mb_strtolower($request->email)."', '".$senha."')");
            $id = DB::table("users")
                    ->orderby("id", "DESC")
                    ->value("id");
            if ($foto) DB::statement("UPDATE users SET ".str_replace(",", "", $foto)." WHERE id = ".$id);
            $this->log_inserir("C", "users", $id); // ControllerKX.php
            $linha = new EmpresasUsuarios;
            $linha->id_usuario = DB::table("users")->orderby("id", "DESC")->value("id");
            $linha->id_empresa = $this->retorna_empresa_logada(); // ControllerKX.php
            $linha->save();
            $this->log_inserir("C", "empresas_usuarios", $linha->id); // ControllerKX.php
        } else {
            DB::statement("
                UPDATE users SET
                    name = '".mb_strtoupper($request->nome)."',
                    email = '".mb_strtolower($request->email)."',
                    ".$foto."
                    password = '".$senha."'
                WHERE id = ".$request->id
            );
            $this->log_inserir("E", "users", $request->id); // ControllerKX.php
        }
        return redirect("/usuarios");
    }

    public function excluir(Request $request) {
        DB::statement("DELETE FROM users WHERE id = ".$request->id);
        $this->log_inserir("D", "users", $request->id); // ControllerKX.php
        $lista = DB::table("empresas_usuarios")
                    ->where("id_usuario", $request->id)
                    ->pluck("id");
        foreach ($lista as $empresa) $this->log_inserir("D", "empresas_usuarios", $empresa); // ControllerKX.php
        DB::statement("DELETE FROM empresas_usuarios WHERE id_usuario = ".$request->id);
    }

    public function empresas_listar($id_usuario) {
        return json_encode(
            DB::table("empresas_usuario")
                ->select(
                    "empresas_usuario.id",
                    "empresas.nome_fantasia"
                )
                ->join("empresas", "empresas.id", "empresas_usuario.id_empresa")
                ->where("empresas.lixeira", 0)
                ->where("empresas_usuario.id_usuario", $id_usuario)
                ->get()
        );
    }

    public function empresas_adicionar(Request $request) {
        $linha = new EmpresasUsuarios;
        $linha->id_empresa = $request->id_empresa;
        $linha->id_usuario = $request->id_usuario;
        $linha->save();
        $this->log_inserir("C", "empresas_usuario", $linha->id); // ControllerKX.php
    }

    public function empresas_remover($id) {
        $linha = EmpresasUsuarios::find($id);
        $linha->delete();
        $this->log_inserir("D", "empresas_usuario", $id); // ControllerKX.php
    }
}