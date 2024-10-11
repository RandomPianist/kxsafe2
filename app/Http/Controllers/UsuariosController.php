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
        $param2 = str_replace("name", "email", $param);
        return DB::table("users")
                    ->select(
                        "users.id",
                        "users.name",
                        "users.email",
                        DB::raw("IFNULL(users.admin, 0) AS admin"),
                        DB::raw("IFNULL(users.foto, '') AS foto")
                    )
                    ->join("empresas_usuarios AS eu", "eu.id_usuario", "users.id_aux")
                    ->whereRaw("((".$param.") OR (".$param2."))")
                    ->whereIn("eu.id_empresa", $this->empresas_acessiveis()) // ControllerKX.php
                    ->groupby(
                        "id",
                        "name",
                        "email",
                        "foto",
                        "admin"
                    )
                    ->get();
    }

    public function ver() {
        if (!Auth::user()->admin) return redirect("/");
        $breadcumb = array(
            "Home" => config("app.root_url")."/home",
            "Usuários" => "#"
        );
        $ultima_atualizacao = $this->log_consultar("users"); // ControllerKX.php
        return view("usuarios", compact("ultima_atualizacao", "breadcumb"));
    }

    public function crud($id) {
        if (!Auth::user()->admin && $id != Auth::user()->id) return redirect("/");
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
                            DB::raw("IFNULL(users.admin, 0) AS admin"),
                            DB::raw("IFNULL(foto, '') AS foto")
                        )
                        ->where("id", $id)
                        ->first();
        $empresas = DB::table("empresas")
                        ->select(
                            "empresas.id",
                            "nome_fantasia"
                        )
                        ->leftjoin("empresas_usuarios AS eu", "id_empresa", "empresas.id")
                        ->where(function($sql) use($id) {
                            $id = intval($id);
                            if ($id) $sql->where("id_usuario", $id);
                            else $sql->where("id_empresa", $this->retorna_empresa_logada()); // ControllerKX.php
                        })
                        ->groupby(
                            "id_empresa",
                            "nome_fantasia"
                        )
                        ->get();
        if ($usuario !== null) {
            if ($usuario->foto) $usuario->foto = asset("storage/".$usuario->foto);
        }
        return view("usuarios_crud", compact("breadcumb", "usuario", "empresas"));
    }

    public function listar(Request $request) {
        $filtro = trim($request->filtro);
        if ($filtro) {
            $busca = $this->busca("name LIKE '".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("name LIKE '%".$filtro."%'");
            if (sizeof($busca) < 3) $busca = $this->busca("(name LIKE '%".implode("%' AND name LIKE '%", explode(" ", str_replace("  ", " ", $filtro)))."%')");
        } else $busca = $this->busca();
        $resultado = array();
        foreach ($busca as $usuario) {
            $aux = new \stdClass;
            $aux->id = $usuario->id;
            $aux->name = $usuario->name;
            $aux->email = $usuario->email;
            $aux->admin = $usuario->admin;
            $aux->foto = $usuario->foto ? asset("storage/".$usuario->foto) : "";
            array_push($resultado, $aux);
        }
        return json_encode($resultado);
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
        $id = 0;
        $senha = Hash::make($request->senha);
        $foto = $request->file("foto") ? "foto = '".$request->file("foto")->store("uploads", "public")."'," : "";
        if (!$request->id) {
            DB::statement("INSERT INTO users (name, email, password, admin) VALUES ('".mb_strtoupper($request->nome)."', '".mb_strtolower($request->email)."', '".$senha."', ".$request->admin.")");
            $id = DB::table("users")
                    ->orderby("id", "DESC")
                    ->value("id");
            if ($foto) DB::statement("UPDATE users SET ".str_replace(",", "", $foto)." WHERE id = ".$id);
            $this->log_inserir("C", "users", $id); // ControllerKX.php
        } else {
            $id = $request->id;
            $senha = $request->senha ? "password = '".$senha."'," : "";
            DB::statement("
                UPDATE users SET
                    ".$foto.$senha."
                    name = '".mb_strtoupper($request->nome)."',
                    admin = ".$request->admin.",
                    email = '".mb_strtolower($request->email)."'
                WHERE id = ".$request->id
            );
            $this->log_inserir("E", "users", $request->id); // ControllerKX.php
        }

        $this->log_inserir2("D", "empresas_usuarios", "id_usuario = ".$id, "NULL"); // ControllerKX.php
        DB::statement("DELETE FROM empresas_usuarios WHERE id_usuario = ".$id);
        $empresas = explode("|", $request->empresas);
        foreach ($empresas as $empresa) {
            $linha = new EmpresasUsuarios;
            $linha->id_usuario = $id;
            $linha->id_empresa = $empresa;
            $linha->save();
            $this->log_inserir("C", "empresas_usuarios", $linha->id); // ControllerKX.php
        }

        return redirect("/usuarios");
    }

    public function excluir(Request $request) {
        DB::statement("DELETE FROM users WHERE id = ".$request->id);
        $this->log_inserir("D", "users", $request->id); // ControllerKX.php
        $this->log_inserir2("D", "empresas_usuarios", "id_usuario = ".$request->id, "NULL"); // ControllerKX.php
        DB::statement("DELETE FROM empresas_usuarios WHERE id_usuario = ".$request->id);
    }
}