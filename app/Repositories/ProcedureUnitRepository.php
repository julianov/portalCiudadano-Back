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
    SearchFilter,
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

    public function getPublicList() {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_TRAMITES_PUBLICOS() AS result FROM DUAL";
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
        $query = "SELECT {$this->pkg}.OBTENER_TRAMITE_POR_TITULO(:title) AS result FROM DUAL";
        $bindings = [ 'title' => $title ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function create(CreateData $data)
    {
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_TRAMITE(:title, :state, :secretary, :description, :forms, :theme, :attachments, :citizen_level, :created_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $created = $this->getByTitle($data->get('title'));

        return $created;
    }

    public function updateByTitle(UpdateData $data)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ACTUALIZAR_TRAMITE(:id, :title, :theme, :forms, :description, :state, :attachments, :citizen_level, :updated_by); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $updated =  $this->getByTitle($data->get('title'));

        return $updated;
    }

    public function removeByTitle(string $title)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ELIMINAR_TRAMITE_POR_TITULO(:title);END;";
        $bindings = [ 'title' => $title ];
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        return $result;
    }

    public function removeById (int $id)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ELIMINAR_TRAMITE_POR_ID(:id);END;";
        $bindings = [ 'id' => $id ];
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        return $result;

    }

    public function getCategories()
    {
        $query = "SELECT {$this->pkg}.TEMATICAS_TRAMITES() AS result FROM DUAL";
        $result = DB::select($query);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getListBySearch(SearchFilter $filter)
    {
        $query = "VARIABLE l_cursor REFCURSOR; EXEC INTERNET.CIUDADANO_PKG.CIU_TRAMITES_BUSCA(:title, :category, l_cursor";
        $bindings = $filter->getFilter();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }
}
