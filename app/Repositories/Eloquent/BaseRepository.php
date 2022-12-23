<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
	protected Model $model;

	/**
	 * BaseService constructor.
	 *
	 * @param Model $model
	 */
	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	/**
	 * @inheritDoc
	 */
	public function all(array $columns = ['*'], array $relations = []): Collection
	{
		return $this->model->with($relations)->get($columns);
	}

	/**
	 * @inheritDoc
	 */
	public function allTrahsed(): Collection
	{
		return $this->model->onlyTrashed()->get();
	}

	/**
	 * @inheritDoc
	 */
	public function findTrashed(string $id): Model
	{
		return $this->model->onlyTrashed()->findOrFail($id);
	}

	/**
	 * @inheritDoc
	 */
	public function create(array $payload): ?Model
	{
		$model = $this->model->create($payload);
		return $model->fresh();
	}

	/**
	 * @inheritDoc
	 */
	public function update(array $payload, string $id): Model
	{
		$model = $this->model->findOrFail($id);
		$model->update($payload);
		return $model->fresh();
	}

	/**
	 * @inheritDoc
	 */
	public function restore(string $id): bool
	{
		$model = $this->model->onlyTrashed()->findOrFail($id);
		return $model->restore();
	}

	/**
	 * @inheritDoc
	 */
	public function forceDelete(string $id): bool
	{
		$model = $this->model->onlyTrashed()->findOrFail($id);
		return $model->forceDelete();
	}

	/**
	 * @inheritDoc
	 */
	public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
	{
		return $this->model->with($relations)->paginate($perPage, $columns);
	}

	/**
	 * @inheritDoc
	 */
	public function delete(string $id): bool
	{
		$model = $this->model->findOrFail($id);
		return $model->delete();
	}

	public function find(string $id, array $columns = ['*']): Model
	{
		return $this->model->findOrFail($id, $columns);
	}
}