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
use App\Helpers\FormUnits\{
    CreateData,
    UpdateData,
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

    public function create(CreateData $data)
    {
<<<<<<< HEAD
       
=======
>>>>>>> cce21e740ba8de239657d2282cd675bcebac0214
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_FORMULARIO(:code, :title, :subtitle, :description, :keywords, :elements, :status, :created_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $created = $this->getByPk($data->get('code'));

        return $created;
    }

    public function getByPk(string $code)
    {
        $query = "SELECT {$this->pkg}.OBTENER_FORMULARIO_POR_PK(:code) AS result FROM DUAL";
        $bindings = [ 'code' => $code ];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
    }

    public function updateByPk(string $code, UpdateData $data)
    {
        $query = "
            DECLARE result BOOLEAN;
            BEGIN result := {$this->pkg}.ACTUALIZAR_FORMULARIO_POR_PK(:code, :title, :subtitle, :description, :keywords, :elements, :status, :updated_by);
            END;
        ";
        $bindings = array_merge($data->toArray(), [ 'code' => $code ]);
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if ($json->status) {
            throw new DatabaseUpdateError();
        }

        $updated = $this->getByPk($data->get('code'));

        return $updated;
    }

    public function removeByPk(string $code): bool
    {
        $query = "
            DECLARE result BOOL;
            BEGIN result := {$this->pkg}.ELIMINAR_FORMULARIO_POR_PK(:code);
            END;
        ";
        $bindings = [ 'code' => $code ];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseDeleteError();
        }

        return $json->status;
    }
}
