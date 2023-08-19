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
use App\Helpers\ProcedureData\{
    CreateData,
    DeleteData,
    UpdateData,
};

class ProcedureDataRepository
{
    private string $pkg = "CIUD_TRAMITES_DATA_PKG";

    public function getList()
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_TRAM_DATA() AS result FROM DUAL";
        $result = DB::select($query);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getListByUser(int $userId)
    {
        $query = "SELECT {$this->pkg}.OBTENER_TRAM_DATA_USER(:userId) AS result FROM DUAL";
        $bindings = [ 'userId' => $userId ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getById(int $id)
    {
        
        $query = "SELECT {$this->pkg}.OBTENER_TRAM_DATA_ID(:id) AS result FROM DUAL";
        $bindings = [ 'id' => $id ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getLastByUser(int $user_id)
    {
        $query = "SELECT {$this->pkg}.OBTENER_ULT_TRAM_DATA_USER(:user_id) AS result FROM DUAL";
        $bindings = [ 'user_id' => $user_id ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function create(CreateData $data)
    {    
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_TRAMITE_DATA(:user_id, :procedure_unit_id); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $procedure = $this->getLastByUser($data->get('user_id'));

        return $procedure;
    }

    public function updateById(UpdateData $data)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ACTUALIZAR_TRAMITE_ID(:id, :state); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        return $result;
    }

    public function removeById(DeleteData $data)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ELIMINAR_TRAMITE_ID(:title);END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        return $result;
    }
}
