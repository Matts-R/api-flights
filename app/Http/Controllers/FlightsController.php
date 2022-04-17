<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FlightService;

class FlightsController extends Controller {

	public function group(Request $request) {
		try {
			$flights = FlightService::groupFlights();

			if(!$flights) return response()->json(['message' => "Nenhum voo foi encontrado"], 404);

			return response()->json($flights, 200);
		} catch (\Exception $e) {
			return response()->json(['message' => "Ocorreu um erro ao tentar realizar a requisição"], 500);
		}
	}
}