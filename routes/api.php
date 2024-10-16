<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "app"], function() {
    Route::post("/ver-pessoa",             [ApiController::class, "ver_pessoa"]);
    Route::post("/produtos-por-pessoa",    [ApiController::class, "produtos_por_pessoa"]);
    Route::post("/validar",                [ApiController::class, "validar_app"]);
    Route::post("/retirar",                [ApiController::class, "retirar"]);
    Route::post("/retirar-com-supervisao", [ApiController::class, "retirar_com_supervisao"]);
    Route::post("/validar-spv",            [ApiController::class, "validar_spv"]);
});