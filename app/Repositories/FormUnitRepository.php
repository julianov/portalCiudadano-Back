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
    GetListFilter,
    GetListBySearchFilter,
    GetListPublicFilter,
    CreateData,
    UpdateData,
};

class FormUnitRepository
{
    private string $pkg = "CIUD_TRAMITES_PKG";

    public function getList(GetListFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_FORMULARIOS(:start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
    }

    public function getPublishedList(GetListPublicFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_FORM_PUBLICADOS(:start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
    }

    public function getListBySearch(GetListBySearchFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_F_UNIT_BUSQUEDA(:keyword, :start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
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

    public function getElementsByPk(string $code)
    {
        $query = "SELECT {$this->pkg}.OBTENER_FORM_ELEMENTS(:code) AS result FROM DUAL";
        $bindings = [ 'code' => $code ];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
    }

    public function create(CreateData $data)
    {
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_FORMULARIO(:code, :title, :subtitle, :description, :keywords, :elements, :status, :created_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $created = $this->getByPk($data->get('code'));

        return $created;
    }

    public function updateByPk(UpdateData $data)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ACTUALIZAR_FORMULARIO_POR_PK(:code, :title, :subtitle, :description, :keywords, :elements, :status, :updated_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);

        if (!$result) { throw new DatabaseWriteError(); }
        $updated = $this->getByPk($data->get('code'));

        return $updated;
    }

    public function removeByPk(string $code): bool
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ELIMINAR_FORMULARIO_POR_PK(:code);END;";
        $bindings = [ 'code' => $code ];
        $result = DB::statement($query, $bindings);

        if (!$result) { throw new DatabaseWriteError(); }


        return $result;
    }
}
