<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PDO;

class FileStorageRepository
{
    private $pkg = 'CIUD_TRAMITES_DATA_PKG';

    /**
     * @param UploadedFile $file
     * @param int $id ID of the inserted row in the form_data table
     * @param int $stored_file_id ID of the inserted row in the stored_files table
     */
    public function store(UploadedFile $file, int $id): int | null
    {
        $pointer = null;
		$blob_file =file_get_contents($file);

        $query = "{$this->pkg}.FORM_DATA_ADJUNTO";
        $bindings = [
            'p_file' => [
                "value" => &$blob_file,
                "type" => PDO::PARAM_LOB,
                "size" => $file->getSize()
            ],
            'file_type' => $this->getFileType($file),
            'file_extension' => $file->getClientOriginalExtension(),
            'form_data_table_id' => intval($id),
            'file_name' => $file->getClientOriginalName(),
            'P_multimedia_id' => [
                'value' => &$pointer,
                'type' => PDO::PARAM_INT
            ]
        ];

        DB::executeProcedure($query, $bindings);

        return $pointer;
    }

    public function get(string $fileId)
    {
        $result = DB::select("SELECT CIUD_ARCHIVOS_PKG.OBTENER_ARCHIVO(:file_id) as result FROM DUAL", [
            'file_id' => $fileId
        ]);

        return $result[0]->result;
    }

    public function delete(string $fileId)
    {
        $result = DB::select("SELECT CIUD_ARCHIVOS_PKG.ELIMINAR_ARCHIVO(:file_id) as result FROM DUAL", [
            'file_id' => $fileId
        ]);

        return $result[0]->result;
    }

    private function getFileType($file)
    {
        $image_extensions = ['png', 'jpg', 'jpeg'];
        $file_extension = $file->getClientOriginalExtension();

        $is_image = in_array(strtolower($file_extension), $image_extensions);

        return $is_image ? 'IMG' : 'DOC';
    }
}
