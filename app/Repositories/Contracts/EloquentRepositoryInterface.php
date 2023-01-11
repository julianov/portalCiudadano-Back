<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
	/**
	 * Get all records
	 * @param  array  $columns
	 * @param  array  $relations
	 * @return Collection
	 */
	public function all(array $columns = ['*'], array $relations = []): Collection;

	/**
	 * Get all trash models
	 * @return Collection
	 */
	public function allTrahsed(): Collection;

	/**
	 * Find thrashed model by id
	 * @param  string  $id
	 * @return Model
	 */
	public function findTrashed(string $id): Model;

	/**
	 * Create a model
	 * @param  mixed $payload
	 * @return bool
	 */
	public function create(mixed $payload): bool;

	/**
	 * Update a model
	 * @param  array  $payload
	 * @param  string  $id
	 * @return Model
	 */
	public function update(array $payload, string $id): Model;

	/**
	 * Restore model
	 * @param  string  $id
	 * @return bool
	 */
	public function restore(string $id): bool;

	/**
	 * Force delete model
	 * @param  string  $id
	 * @return bool
	 */
	public function forceDelete(string $id): bool;

	/**
	 * Get all records with pagination
	 * @param  int  $perPage
	 * @param  array  $columns
	 * @param  array  $relations
	 * @return LengthAwarePaginator
	 */
	public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator;

	/**
	 * Delete a model
	 * @param  string  $id
	 * @return bool
	 */
	public function delete(string $id): bool;

	/**
	 * Find a model by id
	 * @param  string  $id
	 * @param  array  $columns
	 * @return Model
	 */
	public function find(string $id, array $columns = ['*']): Model;
}