<?php

namespace App\Services\WebServices\WsEntreRios\Contracts;

class CheckUserCuilResponse {
	public readonly bool $status;
	public readonly PersonaFisicaResponse $user;
	public readonly BduActorEntidadResponse $actor;

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

	public function toArray(): array {
		return [
			"status" => $this->status,
			"user" => $this->user->toArray(),
			"actor" => $this->actor->toArray(),
		];
	}
}
