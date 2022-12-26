<?php

namespace App\Services\WebServices\WsEntreRios\Contracts;

use DateTime;

class PersonaFisicaResponse
{
	private int $id;
	private string $TipoDocumento;
	private string $NroDocumento;
	private string $Apellido;
	private string $Nombres;
	private DateTime $FechaNacimiento;
	private ?DateTime $FechaDefuncion;
	private string $Sexo;
	private ?string $Localidad;
	private ?string $Departamento;
	private ?string $Provincia;
	private ?string $Barrio;
	private ?string $Calle;
	private ?string $Numeracion;
	private ?string $Piso;
	private ?string $Depto;
	private ?string $UF;
	private ?string $Referencias;
	private ?string $Latitud;
	private ?string $Longitud;
	private string $Cuil;

	public function __construct(array $payload)
	{
		$this->id = $payload["ID"];
		$this->TipoDocumento = $payload["TIPO_DOCUMENTO"];
		$this->NroDocumento = $payload["NRO_DOCUMENTO"];
		$this->Apellido = $payload["APELLIDO"];
		$this->Nombres = $payload["NOMBRES"];
		$this->FechaNacimiento = $payload["FECHA_NACIMIENTO"];
		$this->FechaDefuncion = $payload["FECHA_DEFUNCION"];
		$this->Sexo = $payload["SEXO"];
		$this->Localidad = $payload["LOCALIDAD"];
		$this->Departamento = $payload["DEPARTAMENTO"];
		$this->Provincia = $payload["PROVINCIA"];
		$this->Barrio = $payload["BARRIO"];
		$this->Calle = $payload["CALLE"];
		$this->Numeracion = $payload["NUMERACION"];
		$this->Piso = $payload["PISO"];
		$this->Depto = $payload["DEPTO"];
		$this->UF = $payload["UF"];
		$this->Referencias = $payload["REFERENCIAS"];
		$this->Latitud = $payload["LATITUD"];
		$this->Longitud = $payload["LONGITUD"];
		$this->Cuil = $payload["CUIL"];
	}

	public function getid(): int {
		return $this->id;
	}

	public function getTipoDocumento(): string {
		return $this->TipoDocumento;
	}
	public function getNroDocumento(): string {
		return $this->NroDocumento;
	}
	public function getApellido(): string {
		return $this->Apellido;
	}
	public function getNombres(): string {
		return $this->Nombres;
	}

	public function getFullName(): string
	{
		return $this->Apellido . ", " . $this->Nombres;
	}

	public function getFechaNacimiento(): DateTime {
		return DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $this->FechaNacimiento);
	}
	public function getFechaDefuncion(): ?DateTime {
		return DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $this->FechaDefuncion);
	}
	public function getSexo(): string {
		return $this->Sexo;
	}
	public function getLocalidad(): ?string {
		return $this->Localidad;
	}
	public function getDepartamento(): ?string {
		return $this->Departamento;
	}
	public function getProvincia(): ?string {
		return $this->Provincia;
	}
	public function getBarrio(): ?string {
		return $this->Barrio;
	}
	public function getCalle(): ?string {
		return $this->Calle;
	}
	public function getNumeracion(): ?string {
		return $this->Numeracion;
	}
	public function getPiso(): ?string {
		return $this->Piso;
	}
	public function getDepto(): ?string {
		return $this->Depto;
	}
	public function getUF(): ?string {
		return $this->UF;
	}
	public function getReferencias(): ?string {
		return $this->Referencias;
	}
	public function getLatitud(): ?string {
		return $this->Latitud;
	}
	public function getLongitud(): ?string {
		return $this->Longitud;
	}
	public function getCuil(): string {
		return $this->Cuil;
	}
}
