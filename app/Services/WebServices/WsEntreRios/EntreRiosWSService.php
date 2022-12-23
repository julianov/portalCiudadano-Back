<?php

namespace App\Services\WebServices\WsEntreRios;

use App\Services\WebServices\WsEntreRios\Contracts\{BduActorEntidadResponse,
	CheckUserCuilResponse,
	PersonaFisicaResponse
};
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

	private function getPersonaFisica(string $dni): PersonaFisicaResponse
	{
		$url = "consultaPF/".$dni;
		$response = Http::withHeaders(
			['Authorization' => $this->authToken])
			->get($this->baseUrl.$url);
		return new PersonaFisicaResponse($response->json());
	}

	private function getBduActorEntidad(string $sexo, string $dni): BduActorEntidadResponse
	{

		$url = "consultaBduActorEntidad/".$dni."/".$sexo;
		$response = Http::withHeaders(
			['Authorization' => $this->authToken])
			->get($this->baseUrl.$url);
		return new BduActorEntidadResponse($response->json());
	}

	public function checkUserCuil(string $dni): CheckUserCuilResponse
	{

		$persona = $this->getPersonaFisica($dni);
		$actor = $this->getBduActorEntidad($persona->getSexo(), $persona->getNroDocumento());

		return new CheckUserCuilResponse(status: true, user: $persona, actor: $actor);
	}
}