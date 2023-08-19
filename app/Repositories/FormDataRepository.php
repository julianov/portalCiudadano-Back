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
use App\Helpers\FormData\{
    CreateData,
    UpdateData,
};

class FormDataRepository
{
    private string $pkg = "CIUD_TRAMITES_DATA_PKG";

//     public function getList()
//     {
//         $query = "SELECT {$this->pkg}.OBTENER_LISTA_FORM_DATA() AS result FROM DUAL";
//         $result = DB::select($query);
//         $json = new Result($result);
//         if (!$json->status) {
//             throw new DatabaseReadError();
//         }
//
//         return $json->data;
//     }

//     public function getById(int $id)
//     {
//         $query = "SELECT {$this->pkg}.OBTENER_FORM_DATA_ID(:id) AS result FROM DUAL";
//         $bindings = [ 'id' => $id ];
//         $result = DB::select($query, $bindings);
//
//         $json = new Result($result);
//         if (!$json->status) {
//             throw new DatabaseReadError();
//         }
//
//         return $json->data;
//     }

    public function getLastByUserId(int $user_id)
    {
        $query = "SELECT {$this->pkg}.OBTENER_ULT_FORMDATA_USER(:user_id) AS result FROM DUAL";
        $bindings = [ 'user_id' => $user_id ];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
    }

    public function create(CreateData $data)
    {
        $query = "DECLARE l_result BOOLEAN; BEGIN l_result := {$this->pkg}.CREAR_FORMULARIO_DATA(:form_unit_code, :procedure_data_id, :user_id, :elements); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $created = $this->getLastByUserId($data->get('user_id'));

        return $created;
    }

//     public function updateById(UpdateData $data)
//     {
//         $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ACTUALIZAR_FORMULARIO_POR_PK(:code, :title, :subtitle, :description, :keywords, :elements, :status, :updated_by); END;";
//         $bindings = $data->toArray();
//         $result = DB::statement($query, $bindings);
//
//         if (!$result) { throw new DatabaseWriteError(); }
//         $updated = $this->getByPk($data->get('code'));
//
//         return $updated;
//     }

//     public function removeById(string $code): bool
//     {
//         $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ELIMINAR_FORMULARIO_POR_PK(:code);END;";
//         $bindings = [ 'code' => $code ];
//         $result = DB::statement($query, $bindings);
//
//         if (!$result) { throw new DatabaseWriteError(); }
//
//
//         return $result;
//     }
}
