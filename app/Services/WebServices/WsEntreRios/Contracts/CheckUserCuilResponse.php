<?php

namespace App\Services\WebServices\WsEntreRios\Contracts;

class CheckUserCuilResponse {
	private readonly bool $status;
	private readonly PersonaFisicaResponse $user;
	private readonly BduActorEntidadResponse $actor;

	public function __construct(bool $status, PersonaFisicaResponse $user, BduActorEntidadResponse $actor) {
		$this->status = $status;
		$this->user = $user;
		$this->actor = $actor;
	}

	public function getStatus(): bool {
		return $this->status;
	}

	public function getUser(): PersonaFisicaResponse {
		return $this->user;
	}

	public function getActor(): BduActorEntidadResponse {
		return $this->actor;
	}
}
