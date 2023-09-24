<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use PDO;

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

    // TODO: Change this to the correct table name
    private string $table_name = 'procedure_data';

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
        $result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_ELIMINA_ARCHIVO(:p1, :p2) as result FROM DUAL",
        [
            'p1' =>$this->table_name,
            'p2' =>$multimedia_id // Passing the output parameter by reference
        ]);

        return $result[0]->result;
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

    private function deleteMultimedia($newAttachmentsString ,$newMultimediaIdString, $procedureDataId ){
        $query = "DECLARE l_result NUMBER; BEGIN l_result := {$this->pkg}.DELETE_PROCEDURE_MULTIMEDIA(:attachments, :multimedia_id); END;";
        $bindings = [ 'p_id' => $procedureDataId  ,'p_attachments' => $newAttachmentsString , 'p_multimedia_id' => $newMultimediaIdString ];
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

    }

    private function getFileExtension($file)
    {
        $mime_type = $file->getMimeType();

        return explode('/', $mime_type)[1];
    }
}
