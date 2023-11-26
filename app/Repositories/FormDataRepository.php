<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use PDO;

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
    // TODO: update this table name when needed
    private string $table_name = "form_data_table";

     public function getList(int $user_id)
     {
         $query = "SELECT {$this->pkg}.OBTENER_FORM_DATA_USER(:p_user_id) AS result FROM DUAL";
         $bindings = [ 'p_user_id' => $user_id ];
         $result = DB::select($query, $bindings);
         $json = new Result($result);
         if (!$json->status) {
             throw new DatabaseReadError();
        }

        return $json->data;
     }

     public function getById(int $id)
    {
        $query = "SELECT {$this->pkg}.OBTENER_FORM_DATA_ID(:id) AS result FROM DUAL";
        $bindings = [ 'id' => $id ];
        $result = DB::select($query, $bindings);
        $json = new Result($result);
        if (!$json->status) { throw new DatabaseReadError(); }

        return $json->data;
    }

    // TODO: test this
    public function getFormByCode(string $code, int $user_id)
    {
        
        $query = "SELECT {$this->pkg}.OBTENER_FORM_DATA_POR_CODIGO(:p_form_unit, :p_user_id) AS result FROM DUAL";
        $bindings = [ 'p_form_unit' => $code , 'p_user_id' => $user_id];
        $result = DB::select($query, $bindings);

        $json = new Result($result);
        if (!$json->status) {
            throw new DatabaseReadError();
        }

        return $json->data;
    }

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

    public function getElementsById(string $form_code, int $user_id)
    {

        $query = "SELECT {$this->pkg}.OBTENER_FORM_DATA_ELEMENTS(:codigo, :p_user_id) AS result FROM DUAL";
        $bindings = [ 'codigo' => $form_code , 'p_user_id' => $user_id];
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

    // TODO: test this
    public function updateById(UpdateData $data)
    {
        $query = "DECLARE result BOOLEAN; BEGIN result := {$this->pkg}.ACTUALIZAR_FORMULARIO_DATA(:form_unit_code, :procedure_data_id, :user_id, :elements); END;";
        $bindings = $data->toArray();
        $result = DB::statement($query, $bindings);
        if (!$result) { throw new DatabaseWriteError(); }

        $updated = $this->getFormByCode( $data->get('form_unit_code') , $data->get('user_id'));

        return $updated;
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

    public function getUploadedFile (string $table, int $multimedia_id ){
		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_LEE_ARCHIVO(:p1, :p2) as result FROM DUAL",
		[
			'p1' =>$table,
			'p2' =>$multimedia_id // Passing the output parameter by reference
		]);

		return $result[0]->result;
	}

    public function getAttachmentFileName (string $table, int $multimedia_id ){

		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_CONT_NOMBRE(:p1, :p2) as result FROM DUAL",
		[
			'p1' =>$table,
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

        $query = "{$pkg}.FORM_DATA_ADJUNTO";
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
       
        $res = DB::statement("DECLARE l_result NUMBER; BEGIN l_result := {$this->pkg}.DELETE_FORMS_MULTIMEDIA(:p_attachments, :p_multimedia_id, :p_id); END;",
		[
			'p_attachments' => strval($newAttachmentsString),
            'p_multimedia_id' => strval($newMultimediaIdString),
            'p_id' => intval($procedureDataId),
        ]);
        
        if (!$res) { throw new DatabaseWriteError(); }

        return $res;

    }


}

