<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EloquentRepositoryInterface;
use DB;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
	protected Model $model;

	/**
	 * BaseService constructor.
	 *
	 * @param  Model  $model
	 */

	protected string $table;

	public function __construct(Model $model)
	{
		$this->model = $model;
		$this->table = $model->getTable();
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
	 * @throws Exception
	 */
	public function create(mixed $payload): bool
	{
		$stmt = 'BEGIN INSERTAR_FILA(:p_nombre_tabla, :p_valores_columnas); END;';
		$params = ['p_nombre_tabla' => $this->table, 'p_valores_columnas' => $payload];
		$result = DB::statement($stmt, $params);
		if (!$result) throw new Exception("Error al crear el registro");
		return $result;
	}

	public function modificarFila($valoresColumnas, $clausulaWhere): bool{
		$stmt = 'BEGIN :resultado := MODIFICAR_FILAR(:p_nombre_tabla, :p_valores_columnas, :p_clausula_where); END;';
		$params = [
			'p_nombre_tabla' => $this->table,
			'p_valores_columnas' => $valoresColumnas,
			'p_clausula_where' => $clausulaWhere];
		$result = DB::select($stmt, $params);
		return $result[0]->resultado;
	}

	public function obtenerFila($nombreCampo, $valorCampo): bool{
		$stmt = 'BEGIN :resultado := OBTENER_FILA(:p_nombre_tabla, :p_nombre_campo, :p_valor_campo); END;';
		$params = [
			'p_nombre_tabla' => $this->table,
			'p_nombre_campo' => $nombreCampo,
			'p_valor_campo' => $valorCampo];
		$result = DB::select($stmt, $params);
		return $result[0]->resultado;
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