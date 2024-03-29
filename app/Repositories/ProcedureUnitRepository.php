<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Repositories\Utilities\Result;
use App\Errors\Infrastructure\Database\{
    DatabaseReadError,
    DatabaseWriteError,
};
use App\Helpers\ProcedureUnits\{
    GetListFilter,
    GetListPublicFilter,
    CreateData,
    UpdateData,
    SearchFilter,
    SearchWebFilter,
};

use App\Helpers\ProcedureData\{
    GetListBySearchFilter,
};

use App\Helpers\Pagination;

class ProcedureUnitRepository
{
    private string $pkg = "CIUD_TRAMITES_PKG";

    public function getList(GetListFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_TRAMITES(:start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getPublicList(GetListPublicFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_TRAMITES_PUBL(:start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getById(int $id)
    {
        $query = "SELECT {$this->pkg}.OBTENER_TRAMITE_POR_ID(:id) AS result FROM DUAL";
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
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_TRAMITE(:title, :state, :secretary, :description, :forms, :theme, :attachments, :citizen_level, :price, :url, :icon, :sys_exp_id, :c, :content_id, :orf_id, :created_by); END;";
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

    public function getListBySearch(GetListBySearchFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_T_UNIT_BUSQUEDA(:keyword, :start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getListBySearchWeb(SearchWebFilter $filter)
    {
        $query = "SELECT {$this->pkg}.TRAMITES_BUSCA_WEB() AS result FROM DUAL";
        $result = DB::select($query);
        $json = new Result($result);
        
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }
}
