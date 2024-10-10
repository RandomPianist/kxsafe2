<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CepController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\SegmentosController;
use App\Http\Controllers\SetoresController;
use App\Http\Controllers\NaturezasController;
use App\Http\Controllers\TpdocController;
use App\Http\Controllers\LocaisController;
use App\Http\Controllers\CategoriasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware("auth")->group(function () {
    Route::get("/",             [HomeController::class,     "index"]);
    Route::get("/autocomplete", [HomeController::class,     "autocomplete"]);
    Route::get("/menu",         [HomeController::class,     "menu"]);
    Route::get("/home",         [EmpresasController::class, "home"]);

    $empresas = ["franqueadoras", "franquias", "clientes", "fornecedores"];
    foreach ($empresas as $empresa) {
        Route::group(["prefix" => $empresa], function() use($empresa) {
            Route::get("/grupo/{id_grupo}", [EmpresasController::class, $empresa]);
            Route::get("/crud",             [EmpresasController::class, $empresa."_crud"]);
        });
    }

    Route::group(["prefix" => "cep"], function() {
        Route::get ("/mostrar/{id}", [CepController::class, "mostrar"]);
        Route::post("/salvar",       [CepController::class, "salvar"]);
    });

    Route::group(["prefix" => "usuarios"], function() {
        Route::get ("/",           [UsuariosController::class, "ver"]);
        Route::get ("/listar",     [UsuariosController::class, "listar"]);
        Route::get ("/consultar",  [UsuariosController::class, "consultar"]);
        Route::get ("/crud",       [UsuariosController::class, "crud"]);
        Route::get ("/aviso/{id}", [UsuariosController::class, "aviso"]);
        Route::post("/salvar",     [UsuariosController::class, "salvar"]);
        Route::post("/excluir",    [UsuariosController::class, "excluir"]);

        Route::group(["prefix" => "empresas"], function() {
            Route::get ("/{id_usuario}", [UsuariosController::class, "empresas_listar"]);
            Route::post("/adicionar",    [UsuariosController::class, "empresas_adicionar"]);
            Route::post("/remover/{id}", [UsuariosController::class, "empresas_remover"]);
        });
    });

    Route::group(["prefix" => "empresas"], function() {
        Route::get ("/selecionar", [EmpresasController::class, "minhas"]);
        Route::get ("/consultar",  [EmpresasController::class, "consultar"]);
        Route::get ("/crud",       [EmpresasController::class, "crud"]);
        Route::get ("/aviso/{id}", [EmpresasController::class, "aviso"]);
        Route::post("/salvar",     [EmpresasController::class, "salvar"]);
        Route::post("/excluir",    [EmpresasController::class, "excluir"]);
        Route::post("/selecionar", [EmpresasController::class, "selecionar"]);
    });

    Route::group(["prefix" => "grupos"], function() {
        Route::get ("/",           [GruposController::class, "ver"]);
        Route::get ("/listar",     [GruposController::class, "listar"]);
        Route::get ("/consultar",  [GruposController::class, "consultar"]);
        Route::get ("/crud",       [GruposController::class, "crud"]);
        Route::get ("/aviso/{id}", [GruposController::class, "aviso"]);
        Route::post("/salvar",     [GruposController::class, "salvar"]);
        Route::post("/excluir",    [GruposController::class, "excluir"]);
    });

    Route::group(["prefix" => "segmentos"], function() {
        Route::get ("/",           [SegmentosController::class, "ver"]);
        Route::get ("/listar",     [SegmentosController::class, "listar"]);
        Route::get ("/consultar",  [SegmentosController::class, "consultar"]);
        Route::get ("/crud",       [SegmentosController::class, "crud"]);
        Route::get ("/aviso/{id}", [SegmentosController::class, "aviso"]);
        Route::post("/salvar",     [SegmentosController::class, "salvar"]);
        Route::post("/excluir",    [SegmentosController::class, "excluir"]);
    });

    Route::group(["prefix" => "setores"], function() {
        Route::get ("/",           [SetoresController::class, "ver"]);
        Route::get ("/listar",     [SetoresController::class, "listar"]);
        Route::get ("/consultar",  [SetoresController::class, "consultar"]);
        Route::get ("/crud",       [SetoresController::class, "crud"]);
        Route::get ("/aviso/{id}", [SetoresController::class, "aviso"]);
        Route::post("/salvar",     [SetoresController::class, "salvar"]);
        Route::post("/excluir",    [SetoresController::class, "excluir"]);
    });

    Route::group(["prefix" => "naturezas"], function() {
        Route::get ("/",           [NaturezasController::class, "ver"]);
        Route::get ("/listar",     [NaturezasController::class, "listar"]);
        Route::get ("/consultar",  [NaturezasController::class, "consultar"]);
        Route::get ("/crud",       [NaturezasController::class, "crud"]);
        Route::get ("/aviso/{id}", [NaturezasController::class, "aviso"]);
        Route::post("/salvar",     [NaturezasController::class, "salvar"]);
        Route::post("/excluir",    [NaturezasController::class, "excluir"]);
    });

    Route::group(["prefix" => "tpdoc"], function() {
        Route::get ("/",           [TpdocController::class, "ver"]);
        Route::get ("/listar",     [TpdocController::class, "listar"]);
        Route::get ("/consultar",  [TpdocController::class, "consultar"]);
        Route::get ("/crud",       [TpdocController::class, "crud"]);
        Route::get ("/aviso/{id}", [TpdocController::class, "aviso"]);
        Route::post("/salvar",     [TpdocController::class, "salvar"]);
        Route::post("/excluir",    [TpdocController::class, "excluir"]);
    });

    Route::group(["prefix" => "locais"], function() {
        Route::get ("/",           [LocaisController::class, "ver"]);
        Route::get ("/listar",     [LocaisController::class, "listar"]);
        Route::get ("/consultar",  [LocaisController::class, "consultar"]);
        Route::get ("/crud",       [LocaisController::class, "crud"]);
        Route::get ("/aviso/{id}", [LocaisController::class, "aviso"]);
        Route::post("/salvar",     [LocaisController::class, "salvar"]);
        Route::post("/excluir",    [LocaisController::class, "excluir"]);
    });

    Route::group(["prefix" => "categorias"], function() {
        Route::get ("/",           [CategoriasController::class, "ver"]);
        Route::get ("/listar",     [CategoriasController::class, "listar"]);
        Route::get ("/consultar",  [CategoriasController::class, "consultar"]);
        Route::get ("/crud",       [CategoriasController::class, "crud"]);
        Route::get ("/aviso/{id}", [CategoriasController::class, "aviso"]);
        Route::post("/salvar",     [CategoriasController::class, "salvar"]);
        Route::post("/excluir",    [CategoriasController::class, "excluir"]);
    });
});

require __DIR__.'/auth.php';