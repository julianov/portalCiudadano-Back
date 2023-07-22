<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Repositories\Utilities\Result;
use App\Errors\Infrastructure\Database\{
    DatabaseReadError,
    DatabaseWriteError,
    // DatabaseUpdateError,
    // DatabaseDeleteError
};
use App\Helpers\ProcedureUnits\{
    CreateData,
    UpdateData,
};

class ProcedureUnitRepository
{
    private string $pkg = "CIUD_TRAMITES_PKG";

    public function getList()
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_TRAMITES() AS result FROM DUAL";
        $result = DB::select($query);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getByPk(string $id)
    {
        $query = "SELECT {$this->pkg}.OBTENER_FORMULARIO_POR_PK(:id) AS result FROM DUAL";
        $bindings = [ 'id' => $id ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getByTitle(string $title)
    {
        $query = "SELECT {$this->pkg}.OBTENER_FORMULARIO_POR_PK(:title) AS result FROM DUAL";
        $bindings = [ 'title' => $title ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function create(CreateData $data)
    {
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_TRAMITE(:title, :state, :description, :forms, :theme, :attachments, :created_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $created = $this->getByTitle($data->get('title'));

        return $created;
    }

    public function updateByPk(UpdateData $data)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ACTUALIZAR_TRAMITE_POR_PK(:id, :title, :state, :description, :forms, :theme, :attachments, :updated_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $updated = $this->getByPk($data->get('id'));

        return $updated;
    }

    public function removeByPk(int $id)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ELIMINAR_FORMULARIO_POR_PK(:id);END;";
        $bindings = [ 'id' => $id ];
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        return $result;
    }
}
