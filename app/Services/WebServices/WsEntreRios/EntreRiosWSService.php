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
		$this->baseUrl = "http://jbossdesa:3003/";
		$this->authToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoid3NVVE4iLCJpYXQiOjE2NzE2Mzc1NjAsImV4cCI6MTcwMzE3MzU2MCwic2lzdGVtYSI6IjIyIn0.7Ta6rtdsURlo2ccUk15WpYd5tX60If2mBcpsr2Kx5_o";
	}

	private function getPersonaFisica(string $dni): PersonaFisicaResponse
	{
		$url = "consultaPF/".$dni;
		# $response = Http::withHeaders(
		# 	['Authorization' => $this->authToken])
		# 	->get($this->baseUrl.$url);

		return new PersonaFisicaResponse(
			[
				"ID" => 876231,
				"TIPO_DOCUMENTO" => "DNI",
				"NRO_DOCUMENTO" => 35441282,
				"APELLIDO" => "OVIEDO",
				"NOMBRES" => "JULIAN ARIEL",
				"FECHA_NACIMIENTO" => "1990-11-30T03:00:00.000Z",
				"FECHA_DEFUNCION" => null,
				"SEXO" => "M",
				"LOCALIDAD" => "PARANA",
				"DEPARTAMENTO" => "PARANA",
				"PROVINCIA" => "ENTRE RIOS",
				"BARRIO" => null,
				"CALLE" => "SIN ESPECIFICAR",
				"NUMERACION" => null,
				"PISO" => null,
				"DEPTO" => null,
				"UF" => null,
				"REFERENCIAS" => "FRAY F.CASTAÃ`EDA 1178",
				"LATITUD" => null,
				"LONGITUD" => null,
				"CUIL" => "23354412829",
			]);
	}

	private function getBduActorEntidad(string $sexo, string $dni): BduActorEntidadResponse
	{

		$url = "consultaBduActorEntidad/".$dni."/".$sexo;
		# $response = Http::withHeaders(
		#	['Authorization' => $this->authToken])
		#	->get($this->baseUrl.$url);
		return new BduActorEntidadResponse([
			"PRS_ID" => 408752,
			"NRO_DOCUMENTO" => 24155974,
			"APELLIDO" => "DE LAS CASAS",
			"NOMBRES" => "GONZALO ANDRES",
			"ENT_ID" => 31489,
			"ORF_ID" => 1003017,
			"DESCRIPCION" => "OFIC. SEC. GUALEGUAYCHU (CIUDAD) - DIR. DEL REG. DE ESTADO CIVIL Y CAPACIDAD DE LAS PERSONAS - SEC. JUSTICIA - MIN. GOBIERNO Y JUSTICIA - PODER EJECUTIVO",
		]);
	}

	public function checkUserCuil(string $dni): array
	{

		$persona = $this->getPersonaFisica($dni);

		$actor = $this->getBduActorEntidad($persona->getSexo(), $persona->getNroDocumento());
		$response = new CheckUserCuilResponse(true, $persona, $actor);
		return $response->toArray();
	}
}
