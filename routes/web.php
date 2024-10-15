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
use App\Http\Controllers\BancosController;
use App\Http\Controllers\CfopController;
use App\Http\Controllers\ItensController;
use App\Http\Controllers\FuncionariosController;
use App\Http\Controllers\AtribuicoesController;
use App\Http\Controllers\RetiradasController;
use App\Http\Controllers\MaquinasController;
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
        Route::get ("/mostrar/{cep}", [CepController::class, "mostrar"]);
        Route::post("/salvar",        [CepController::class, "salvar"]);
    });

    Route::group(["prefix" => "usuarios"], function() {
        Route::get ("/",           [UsuariosController::class, "ver"]);
        Route::get ("/listar",     [UsuariosController::class, "listar"]);
        Route::get ("/consultar",  [UsuariosController::class, "consultar"]);
        Route::get ("/crud/{id}",  [UsuariosController::class, "crud"]);
        Route::get ("/aviso/{id}", [UsuariosController::class, "aviso"]);
        Route::post("/salvar",     [UsuariosController::class, "salvar"]);
        Route::post("/excluir",    [UsuariosController::class, "excluir"]);
    });

    Route::group(["prefix" => "grupos"], function() {
        Route::get ("/",           [GruposController::class, "ver"]);
        Route::get ("/listar",     [GruposController::class, "listar"]);
        Route::get ("/consultar",  [GruposController::class, "consultar"]);
        Route::get ("/crud/{id}",  [GruposController::class, "crud"]);
        Route::get ("/aviso/{id}", [GruposController::class, "aviso"]);
        Route::post("/salvar",     [GruposController::class, "salvar"]);
        Route::post("/excluir",    [GruposController::class, "excluir"]);
    });

    Route::group(["prefix" => "segmentos"], function() {
        Route::get ("/",           [SegmentosController::class, "ver"]);
        Route::get ("/listar",     [SegmentosController::class, "listar"]);
        Route::get ("/consultar",  [SegmentosController::class, "consultar"]);
        Route::get ("/crud/{id}",  [SegmentosController::class, "crud"]);
        Route::get ("/aviso/{id}", [SegmentosController::class, "aviso"]);
        Route::post("/salvar",     [SegmentosController::class, "salvar"]);
        Route::post("/excluir",    [SegmentosController::class, "excluir"]);
    });

    Route::group(["prefix" => "setores"], function() {
        Route::get ("/",           [SetoresController::class, "ver"]);
        Route::get ("/listar",     [SetoresController::class, "listar"]);
        Route::get ("/consultar",  [SetoresController::class, "consultar"]);
        Route::get ("/crud/{id}",  [SetoresController::class, "crud"]);
        Route::get ("/aviso/{id}", [SetoresController::class, "aviso"]);
        Route::post("/salvar",     [SetoresController::class, "salvar"]);
        Route::post("/excluir",    [SetoresController::class, "excluir"]);
    });

    Route::group(["prefix" => "naturezas"], function() {
        Route::get ("/",           [NaturezasController::class, "ver"]);
        Route::get ("/listar",     [NaturezasController::class, "listar"]);
        Route::get ("/consultar",  [NaturezasController::class, "consultar"]);
        Route::get ("/crud/{id}",  [NaturezasController::class, "crud"]);
        Route::get ("/aviso/{id}", [NaturezasController::class, "aviso"]);
        Route::post("/salvar",     [NaturezasController::class, "salvar"]);
        Route::post("/excluir",    [NaturezasController::class, "excluir"]);
    });

    Route::group(["prefix" => "tpdoc"], function() {
        Route::get ("/",           [TpdocController::class, "ver"]);
        Route::get ("/listar",     [TpdocController::class, "listar"]);
        Route::get ("/consultar",  [TpdocController::class, "consultar"]);
        Route::get ("/crud/{id}",  [TpdocController::class, "crud"]);
        Route::get ("/aviso/{id}", [TpdocController::class, "aviso"]);
        Route::post("/salvar",     [TpdocController::class, "salvar"]);
        Route::post("/excluir",    [TpdocController::class, "excluir"]);
    });

    Route::group(["prefix" => "locais"], function() {
        Route::get ("/",             [LocaisController::class, "ver"]);
        Route::get ("/listar",       [LocaisController::class, "listar"]);
        Route::get ("/consultar",    [LocaisController::class, "consultar"]);
        Route::get ("/mostrar/{id}", [LocaisController::class, "mostrar"]);
        Route::get ("/crud/{id}",    [LocaisController::class, "crud"]);
        Route::get ("/aviso/{id}",   [LocaisController::class, "aviso"]);
        Route::post("/salvar",       [LocaisController::class, "salvar"]);
        Route::post("/excluir",      [LocaisController::class, "excluir"]);
        Route::post("/estoque",      [LocaisController::class, "estoque"]);
    });

    Route::group(["prefix" => "categorias"], function() {
        Route::get ("/",           [CategoriasController::class, "ver"]);
        Route::get ("/listar",     [CategoriasController::class, "listar"]);
        Route::get ("/consultar",  [CategoriasController::class, "consultar"]);
        Route::get ("/crud/{id}",  [CategoriasController::class, "crud"]);
        Route::get ("/aviso/{id}", [CategoriasController::class, "aviso"]);
        Route::post("/salvar",     [CategoriasController::class, "salvar"]);
        Route::post("/excluir",    [CategoriasController::class, "excluir"]);
    });

    Route::group(["prefix" => "bancos"], function() {
        Route::get ("/",           [BancosController::class, "ver"]);
        Route::get ("/listar",     [BancosController::class, "listar"]);
        Route::get ("/consultar",  [BancosController::class, "consultar"]);
        Route::get ("/crud/{id}",  [BancosController::class, "crud"]);
        Route::get ("/aviso/{id}", [BancosController::class, "aviso"]);
        Route::post("/salvar",     [BancosController::class, "salvar"]);
        Route::post("/excluir",    [BancosController::class, "excluir"]);
    });

    Route::group(["prefix" => "cfop"], function() {
        Route::get ("/",           [CfopController::class, "ver"]);
        Route::get ("/listar",     [CfopController::class, "listar"]);
        Route::get ("/consultar",  [CfopController::class, "consultar"]);
        Route::get ("/crud/{id}",  [CfopController::class, "crud"]);
        Route::get ("/aviso/{id}", [CfopController::class, "aviso"]);
        Route::post("/salvar",     [CfopController::class, "salvar"]);
        Route::post("/excluir",    [CfopController::class, "excluir"]);
    });

    Route::group(["prefix" => "itens"], function() {
        Route::get ("/",              [ItensController::class, "ver"]);
        Route::get ("/listar",        [ItensController::class, "listar"]);
        Route::get ("/consultar",     [ItensController::class, "consultar"]);
        Route::get ("/crud/{id}",     [ItensController::class, "crud"]);
        Route::get ("/aviso/{id}",    [ItensController::class, "aviso"]);
        Route::get ("/validade/{id}", [ItensController::class, "validade"]);
        Route::post("/salvar",        [ItensController::class, "salvar"]);
        Route::post("/excluir",       [ItensController::class, "excluir"]);
    });

    Route::group(["prefix" => "empresas"], function() {
        Route::get ("/selecionar",  [EmpresasController::class, "minhas"]);
        Route::get ("/consultar",   [EmpresasController::class, "consultar"]);
        Route::get ("/consultar2",  [EmpresasController::class, "consultar2"]);
        Route::get ("/aviso/{id}",  [EmpresasController::class, "aviso"]);
        Route::post("/salvar",      [EmpresasController::class, "salvar"]);
        Route::post("/excluir",     [EmpresasController::class, "excluir"]);
        Route::post("/selecionar",  [EmpresasController::class, "selecionar"]);
    });

    Route::group(["prefix" => "funcionarios"], function() {
        Route::get ("/",           [FuncionariosController::class, "ver"]);
        Route::get ("/listar",     [FuncionariosController::class, "listar"]);
        Route::get ("/consultar",  [FuncionariosController::class, "consultar"]);
        Route::get ("/crud/{id}",  [FuncionariosController::class, "crud"]);
        Route::get ("/aviso/{id}", [FuncionariosController::class, "aviso"]);
        Route::post("/salvar",     [FuncionariosController::class, "salvar"]);
        Route::post("/excluir",    [FuncionariosController::class, "excluir"]);
        Route::post("/supervisor", [FuncionariosController::class, "supervisor"]);
    });

    Route::group(["prefix" => "maquinas"], function() {
        Route::get ("/",           [MaquinasController::class, "ver"]);
        Route::get ("/listar",     [MaquinasController::class, "listar"]);
        Route::get ("/consultar",  [MaquinasController::class, "consultar"]);
        Route::get ("/crud/{id}",  [MaquinasController::class, "crud"]);
        Route::get ("/aviso/{id}", [MaquinasController::class, "aviso"]);
        Route::post("/salvar",     [MaquinasController::class, "salvar"]);
        Route::post("/excluir",    [MaquinasController::class, "excluir"]);
    });

    Route::group(["prefix" => "atribuicoes"], function() {
        Route::get("/consultar",     [AtribuicoesController::class, "consultar"]);
        Route::get("/produtos/{id}", [AtribuicoesController::class, "produtos"]);
    });

    Route::group(["prefix" => "retiradas"], function() {
        Route::get ("/consultar", [RetiradasController::class, "consultar"]);
        Route::get("/salvar",    [RetiradasController::class, "salvar"]);
    });
});

require __DIR__.'/auth.php';