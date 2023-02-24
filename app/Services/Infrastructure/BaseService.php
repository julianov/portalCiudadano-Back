<?php

namespace App\Services\Infrastructure;

use App\Repositories\Eloquent\BaseRepository;
use App\Services\Contracts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseService implements Contracts\BaseServiceInterface
{

	protected BaseRepository $repository;

	/**
	 * BaseService constructor.
	 *
	 * @param  BaseRepository  $repository
	 */
	public function __construct(BaseRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @inheritDoc
	 */
	public function all(array $columns = ['*'], array $relations = []): Collection
	{
		return $this->repository->all($columns, $relations);
	}

	/**
	 * @inheritDoc
	 */
	public function create(array $payload): Model
	{
		return $this->repository->create($payload);
	}

	/**
	 * @inheritDoc
	 */
	public function update(array $payload, string $id): Model
	{
		return $this->repository->update($payload, $id);
	}

	/**
	 * @inheritDoc
	 */
	public function delete(string $id): bool
	{
		return $this->repository->delete($id);
	}

	/**
	 * @inheritDoc
	 */
	public function find(string $id, array $columns = ['*']): Model
	{
		return $this->repository->find($id, $columns);
	}
}