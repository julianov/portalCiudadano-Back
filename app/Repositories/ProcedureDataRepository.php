<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use PDO;

use App\Repositories\Utilities\Result;
use App\Errors\Infrastructure\Database\{
    DatabaseReadError,
    DatabaseWriteError,
};
use App\Helpers\ProcedureData\{
    GetListFilter,
    GetListBySearchFilter,
    CreateData,
    DeleteData,
    UpdateData,
};

class ProcedureDataRepository
{
    private string $pkg = "CIUD_TRAMITES_DATA_PKG";

    // TODO: Change this to the correct table name
    private string $table_name = 'procedure_data';

    public function getList(GetListFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_LISTA_TRAM_DATA(:start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getListByUser(GetListFilter $filter, int $userId)
    {
        $query = "SELECT {$this->pkg}.OBTENER_TRAM_DATA_USER(:p_user_id, :start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();
        $bindings['p_user_id'] = $userId;

        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    public function getListBySearch(GetListBySearchFilter $filter)
    {
        $query = "SELECT {$this->pkg}.OBTENER_T_DATA_BUSQUEDA(:keyword, :start_position, :end_position) AS result FROM DUAL";
        $bindings = $filter->toArray();

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


    public function getByProcedureUnitId(int $id)
    {
        $query = "SELECT {$this->pkg}.OBTENER_TRAM_DATA_UNIT_ID(:id) AS result FROM DUAL";
        $bindings = [ 'procedure_unit_id' => $id ];
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

    public function storeAttachments(UploadedFile|array $files, string $row)
    {
        $ids = [];

        $dataArray = json_decode($row, true);

        $procedureId = $dataArray[0]['ID'];
        foreach ($files as $file) {

            $id = $this->storeSingleFile($file, $procedureId);
            array_push($ids, $id);
        }

        return $ids;
    }

    public function getUploadedFile (int $multimedia_id ){

		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_LEE_ARCHIVO(:p1, :p2) as result FROM DUAL",
		[
			'p1' =>$this->table_name,
			'p2' =>$multimedia_id // Passing the output parameter by reference
		]);

		return $result[0]->result;
	}

    public function deleteUploadedFile (int $multimedia_id)
    {
        $res = DB::statement("DECLARE l_result NUMBER; BEGIN l_result := {$this->pkg}.PROCEDURE_DATA_BORRAR_ADJUNTO(:p_multimedia_id); END;",
		[
            'p_multimedia_id' => intval($multimedia_id),
        ]);

        return $res;
    }

    private function storeSingleFile(UploadedFile $file, int $procedureId)
    {
        $tipoArchivo = $file->getMimeType();
        $tipoArchivo= explode('/', $tipoArchivo)[1];
        $file_type='';
        if ($tipoArchivo == 'png' || $tipoArchivo == 'jpg' || $tipoArchivo == 'jpeg'){

            $file_type="IMG";

        }else{

            $file_type="DOC";
        }

        $pkg = "CIUD_TRAMITES_DATA_PKG";
        $pointer = null;
		$blob_file =file_get_contents($file);

        $query = "{$pkg}.PROCEDURE_DATA_ADJUNTO";
        $bindings = [
            'p_file' => [
                "value" => &$blob_file,
                "type" => PDO::PARAM_LOB,
                "size" => $file->getSize()
            ],
            'file_type' => $file_type,
            'file_extension' => $tipoArchivo,
            'procedure_data_table_id' => intval($procedureId),
            'file_name' => $file->getClientOriginalName(),


            'P_multimedia_id' => [
                'value' => &$pointer,
                'type' => PDO::PARAM_INT
            ]
        ];

        DB::executeProcedure($query, $bindings);

        return $pointer;
    }

    public function deleteMultimedia(string $newAttachmentsString , string $newMultimediaIdString, int $procedureDataId )
    {

        $res = DB::statement("DECLARE l_result NUMBER; BEGIN l_result := {$this->pkg}.DELETE_PROCEDURE_MULTIMEDIA(:p_attachments, :p_multimedia_id, :p_id); END;",
		[
			'p_attachments' => strval($newAttachmentsString),
            'p_multimedia_id' => strval($newMultimediaIdString),
            'p_id' => intval($procedureDataId),
        ]);

        if (!$res) { throw new DatabaseWriteError(); }

        return $res;

    }

}
