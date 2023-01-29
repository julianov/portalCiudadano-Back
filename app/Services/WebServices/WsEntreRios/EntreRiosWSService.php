<?php

namespace App\Services\WebServices\WsEntreRios;

use App\Services\WebServices\WsEntreRios\Contracts\{BduActorEntidadResponse,
	CheckUserCuilResponse,
	PersonaFisicaResponse};
use Http;

class EntreRiosWSService
{

	private readonly string $baseUrl;
	private readonly string $authToken;

	public function __construct()
	{
		$this->baseUrl = "https://apps.entrerios.gov.ar/wsEntreRios/";
		$this->authToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoid3NVVE4iLCJpYXQiOjE2NzE2Mzc1NjAsImV4cCI6MTcwMzE3MzU2MCwic2lzdGVtYSI6IjIyIn0.7Ta6rtdsURlo2ccUk15WpYd5tX60If2mBcpsr2Kx5_o";
	}

	public function checkUserCuil(string $dni)
	{

		$persona = $this->getPersonaFisica($dni);

		if ($persona != "bad cuil"){
			$actor = $this->getBduActorEntidad($persona->getSexo(), $persona->getNroDocumento());
			$response = new CheckUserCuilResponse(true, $persona, $actor);
			return $response->toArray();
		}else{

			return response()->json([
                'status' => false,
                'message' => 'Bad client request'
            ], 400);
		}

		
	}

	private function getPersonaFisica(string $dni): PersonaFisicaResponse|string
	{
		$url = "consultaPF/".$dni;
		$response = Http::withHeaders(
			['Authorization' => $this->authToken])
			->get($this->baseUrl.$url);

			if(array_key_exists(0, $response->json())){
				
				return new PersonaFisicaResponse($response->json()[0]);

			}else{
				
				return "bad cuil";
				// Handle error
			}


	}

	private function getBduActorEntidad(string $sexo, string $dni): BduActorEntidadResponse|string
	{
		$sexo_var = "'".$sexo;
		$url = "consultaBduActorEntidad/".$dni."/".$sexo_var."'";
		$response = Http::withHeaders(
			['Authorization' => $this->authToken])
			->get($this->baseUrl.$url);

			if(array_key_exists(0, $response->json())){
				
				return new BduActorEntidadResponse($response->json()[0]);

			}else{
				
				return "bad cuil";
			}
	}
    
    public function getERLocations()
	{

		$url ="consultaLocalidades/null/null/'entre%20rios'/null";
		$response = Http::withHeaders([
			'Authorization' => $this->authToken,
			'forwarded' => '45.82.73.74',
			'Accept' => 'application/json, text/plain, */*'
		])->get($this->baseUrl.$url);

		return $response->json();
	}
}
