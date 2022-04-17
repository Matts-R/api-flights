<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightsController;
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

Route::prefix("flights")->group(function () {

	Route::controller(FlightsController::class)->group(function () {
		Route::get("/group", "group");
	});

});

Route::fallback(function (Request $request) {
	return response()->json(['message' => "O endpoint chamado não corresponde a nenhum serviço."], 404);
});