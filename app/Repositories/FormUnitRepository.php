<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Repositories\Utitilies\Result;
use App\Errors\Infrastructure\Database\{
    DatabaseReadError,
    DatabaseWriteError,
    DatabaseUpdateError,
    DatabaseDeleteError
};
use App\Models\FormUnitModel as Model;
use App\Helpers\{
    FormUnitCreateData as CreateData,
    FormUnitUpdateData as UpdateData,
    FormUnitPrimaryKeys as PrimaryKeys
};

class FormUnitRepository
{
    private string $pkg = "CIUD_TRAMITES_PKG";

    public function getList(): array
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_FORMULARIOS() AS result FROM DUAL";
        $result = DB::select($query);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        if (is_array($json->data)) {
            return array_map(function ($value) {
                return new Model($value);
            }, $json->data);
        }

        return [];
    }

    public function getByPk(int $code): Model
    {
        $query = "SELECT {this->pkg}.OBTENER_FORMULARIO_POR_PK(:code); AS result FROM DUAL";
        $bindings = [
            'code' => $code,
        ];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        if (is_array($json->data)) {
            return new Model($json->data);
        }

        return null;
    }

    public function getByPks(PrimaryKeys $primaryKeys): array
    {
        $query = "SELECT {$this->pkg}.OBTENER_FORMULARIOS_POR_PKS(:primaryKeys) AS result FROM DUAL";
        $bindings = $primaryKeys->toArray();
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return array_map(function ($value) {
            return new Model($value);
        }, $json->data);
    }

    public function create(CreateData $data): Model
    {
        $query = "SELECT {$this->pkg}.CREAR_FORMULARIO(:code, :title, :subtitle, :description, :keywords, :elements, :status, :created_by) AS result FROM DUAL";
        $bindings = array_merge($data->toArray(), [
            'elements' => json_encode($data->get('elements'))
        ]);
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseWriteError();
        }

        $created = $this->getByPk($data->get('code'));

        return $created;
    }

    public function updateByPk(string $code, int $version, UpdateData $data): Model
    {
        $query = "
            DECLARE result BOOL;
            BEGIN result := CIUD_UTILIDADES_PKG.ACTUALIZAR_FORMULARIO_POR_PK(:code, :version, :title, :subtitle, :description, :status, :updatedBy);
            END;
        ";
        $bindings = $data->toArray();
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if ($json->status) {
            throw new DatabaseUpdateError();
        }

        $updated = $this->getByPk($code, $version);

        return $updated;
    }

    public function removeByPk(string $code, int $version): bool
    {
        $query = "
            DECLARE result BOOL;
            BEGIN result := CIUD_UTILIDADES_PKG.ELIMINAR_FORMULARIO_POR_PK(:code, :version);
            END;
        ";
        $bindings = [
            'code' => $code,
            'version' => $version
        ];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseDeleteError();
        }

        return $json->status;
    }

    // ****************************************************************************************************
    // *************************************** Utilities **************************************************
    // ****************************************************************************************************

    public function validateForms(PrimaryKeys $primaryKeys): bool
    {
        $forms = $this->getByPks($primaryKeys);

        return count($forms) == count($primaryKeys->toArray());
    }
}