<?php

namespace App\Services\Contracts;

interface BaseServiceInterface
{
	/**
	 * Get all
	 *
	 * @param  array  $columns
	 * @param  array  $relations
	 * @return mixed
	 */
	public function all(array $columns = ['*'], array $relations = []): mixed;

	/**
	 * Get one
	 *
	 * @param  array  $payload
	 * @return mixed
	 */
	public function create(array $payload): mixed;

	/**
	 * Update
	 * @param  array  $payload
	 * @param  string $id
	 * @return mixed
	 */
	public function update(array $payload, string $id): mixed;

	/**
	 * Delete
	 * @param  string $id
	 * @return mixed
	 */
	public function delete(string $id): mixed;

	/**
	 * Find by id
	 * @param  string $id
	 * @param  array  $columns
	 * @return mixed
	 */
	public function find(string $id, array $columns = ['*']): mixed;
}