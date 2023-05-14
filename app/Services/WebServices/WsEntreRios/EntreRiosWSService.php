<?php

namespace App\Services\WebServices\WsEntreRios;

use App\Services\WebServices\WsEntreRios\Contracts\{BduActorEntidadResponse,
	CheckUserCuilResponse,
	PersonaFisicaResponse
};
use Http;

class EntreRiosWSService
{

	private string $baseUrl;
	private string $authToken;

	public function __construct()
	{ 
		$this->baseUrl = env('BASEURL_ERWS');
		$this->authToken = env('AUTHTOKEN_ERWS');
	}

	/**
	 * @param  string  $dni
	 * @return array
	 */
	public function checkUserCuil(string $dni)
	{
		$persona = $this->getPersonaFisica($dni);

		if (!$persona) {
			return response()->json([
				'status' => false,
				'message' => 'Cuil not existing in DB'
			], 422);
		}

		$is_actor = false;

		$actor = $this->getBduActorEntidad($persona->getSexo(), $persona->getNroDocumento());

		$response = new CheckUserCuilResponse(true, $persona, $actor);
		
		if ($response->getActor()->getEntId() != null) { $is_actor=true; }

		return response()->json([
			"status" => true,
			"fullName" => $response->getUser()->getFullName(),
			"prs_id" => $response->getUser()->getid(),
			"Cuil" => $response->getUser()->getCuil(),
			"Nombres" => $response->getUser()->getNombres(),
			"Apellido" => $response->getUser()->getApellido(),
			"Actor" => $is_actor
		], 200);
	}


	public function getPrs_id(string $dni){

		$persona = $this->getPersonaFisica($dni);

		if (!$persona) {
			
			return "bad dni";
		}

		return $persona->getid();
	}


	private function getPersonaFisica(string $dni): ?PersonaFisicaResponse
	{
		$url = "consultaPF/".$dni;
		$response = Http::withHeaders(['Authorization' => $this->authToken])
			->get($this->baseUrl.$url);

		if(!array_key_exists(0, $response->json())){ return null; }

		return new PersonaFisicaResponse($response->json()[0]);
	}

	private function getBduActorEntidad(string $sexo, string $dni): ?BduActorEntidadResponse
	{
		$url = "consultaBduActorEntidad/$dni./'$sexo'";
		$response = Http::withHeaders(
			['Authorization' => $this->authToken])
			->get($this->baseUrl.$url);

		if (!array_key_exists(0, $response->json())) { return null; }

		return new BduActorEntidadResponse($response->json()[0]);
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
