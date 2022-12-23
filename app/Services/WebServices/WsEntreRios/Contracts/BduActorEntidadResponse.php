<?php

namespace App\Services\WebServices\WsEntreRios\Contracts;

class BduActorEntidadResponse
{
	private int $PrsId;
	private int $NroDocumento;
	private string $Apellido;
	private string $Nombres;
	private int $EntId;
	private int $OrfId;
	private string $Descripcion;

	public function __construct(array $payload)
	{
		$this->PrsId = $payload["PRS_ID"];
		$this->NroDocumento = $payload["NRO_DOCUMENTO"];
		$this->Apellido = $payload["APELLIDO"];
		$this->Nombres = $payload["NOMBRES"];
		$this->EntId = $payload["ENT_ID"];
		$this->OrfId = $payload["ORF_ID"];
		$this->Descripcion = $payload["DESCRIPCION"];
	}

	public function getPrsId(): int {
		return $this->PrsId;
	}
	public function getNroDocumento(): int {
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

	public function getEntId(): int {
		return $this->EntId;
	}
	public function getOrfId(): int {
		return $this->OrfId;
	}
	public function getDescripcion(): string {
		return $this->Descripcion;
	}
}