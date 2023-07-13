<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Repositories\Utilities\Result;
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

    public function getList()
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_FORMULARIOS() AS result FROM DUAL";
        $result = DB::select($query);
        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }
      
        return $json->data;
    }

    public function getByPk(string $code)
    {
        $pkg = $this->pkg;
        $query = "SELECT " . $pkg . ".OBTENER_FORMULARIO_POR_PK(:code) AS result FROM DUAL";        $bindings = [
            'code' => $code,
        ];
        $result = DB::select($query, $bindings);
        
        $json = new Result($result);

        return $result[0]->result;
       
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

    public function create(CreateData $data)
    {
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_FORMULARIO(:code, :title, :subtitle, :description, :keywords, :elements, :status, :created_by); END;";

        $bindings = array_merge($data->toArray(), [
            'elements' => trim(json_encode($data->get('elements')), '"')
        ]);
        
        $result = DB::statement($query, $bindings);
        if (!$result) {
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
